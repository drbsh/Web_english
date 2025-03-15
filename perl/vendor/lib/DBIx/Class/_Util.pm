package # hide from PAUSE
  DBIx::Class::_Util;

use warnings;
use strict;

use constant SPURIOUS_VERSION_CHECK_WARNINGS => (
  ( $ENV{DBICTEST_VERSION_WARNS_INDISCRIMINATELY} or $] < 5.010 )
    ? 1
    : 0
);

BEGIN {
  package # hide from pause
    DBIx::Class::_ENV_;

  use Config;

  use constant {

    # but of course
    BROKEN_FORK => ($^O eq 'MSWin32') ? 1 : 0,

    BROKEN_GOTO => ($] < '5.008003') ? 1 : 0,

    HAS_ITHREADS => $Config{useithreads} ? 1 : 0,

    UNSTABLE_DOLLARAT => ( "$]" < 5.013002 ) ? 1 : 0,

    DBICTEST => $INC{"DBICTest/Util.pm"} ? 1 : 0,

    # During 5.13 dev cycle HELEMs started to leak on copy
    # add an escape for these perls ON SMOKERS - a user will still get death
    PEEPEENESS => ( eval { DBICTest::RunMode->is_smoker } && ($] >= 5.013005 and $] <= 5.013006) ),

    SHUFFLE_UNORDERED_RESULTSETS => $ENV{DBIC_SHUFFLE_UNORDERED_RESULTSETS} ? 1 : 0,

    ASSERT_NO_INTERNAL_WANTARRAY => $ENV{DBIC_ASSERT_NO_INTERNAL_WANTARRAY} ? 1 : 0,

    ASSERT_NO_INTERNAL_INDIRECT_CALLS => $ENV{DBIC_ASSERT_NO_INTERNAL_INDIRECT_CALLS} ? 1 : 0,

    STRESSTEST_UTF8_UPGRADE_GENERATED_COLLAPSER_SOURCE => $ENV{DBIC_STRESSTEST_UTF8_UPGRADE_GENERATED_COLLAPSER_SOURCE} ? 1 : 0,

    IV_SIZE => $Config{ivsize},

    OS_NAME => $^O,
  };

  if ($] < 5.009_005) {
    require MRO::Compat;
    constant->import( OLD_MRO => 1 );
  }
  else {
    require mro;
    constant->import( OLD_MRO => 0 );
  }
}

# FIXME - this is not supposed to be here
# Carp::Skip to the rescue soon
use DBIx::Class::Carp '^DBIx::Class|^DBICTest';

use B ();
use Carp 'croak';
use Storable 'nfreeze';
use Scalar::Util qw(weaken blessed reftype refaddr);
use Sub::Quote qw(qsub quote_sub);

use base 'Exporter';
our @EXPORT_OK = qw(
  sigwarn_silencer modver_gt_or_eq modver_gt_or_eq_and_lt
  fail_on_internal_wantarray fail_on_internal_call
  refdesc refcount hrefaddr
  scope_guard is_exception detected_reinvoked_destructor emit_loud_diag
  quote_sub qsub perlstring serialize
  UNRESOLVABLE_CONDITION
);

use constant UNRESOLVABLE_CONDITION => \ '1 = 0';

sub sigwarn_silencer ($) {
  my $pattern = shift;

  croak "Expecting a regexp" if ref $pattern ne 'Regexp';

  my $orig_sig_warn = $SIG{__WARN__} || sub { CORE::warn(@_) };

  return sub { &$orig_sig_warn unless $_[0] =~ $pattern };
}

sub perlstring ($) { q{"}. quotemeta( shift ). q{"} };

sub hrefaddr ($) { sprintf '0x%x', &refaddr||0 }

sub refdesc ($) {
  croak "Expecting a reference" if ! length ref $_[0];

  # be careful not to trigger stringification,
  # reuse @_ as a scratch-pad
  sprintf '%s%s(0x%x)',
    ( defined( $_[1] = blessed $_[0]) ? "$_[1]=" : '' ),
    reftype $_[0],
    refaddr($_[0]),
  ;
}

sub refcount ($) {
  croak "Expecting a reference" if ! length ref $_[0];

  # No tempvars - must operate on $_[0], otherwise the pad
  # will count as an extra ref
  B::svref_2object($_[0])->REFCNT;
}

sub serialize ($) {
  local $Storable::canonical = 1;
  nfreeze($_[0]);
}


my $seen_loud_screams;
sub emit_loud_diag {
  my $args = { ref $_[0] eq 'HASH' ? %{$_[0]} : @_ };

  unless ( defined $args->{msg} and length $args->{msg} ) {
    emit_loud_diag(
      msg => "No 'msg' value supplied to emit_loud_diag()"
    );
    exit 70;
  }

  my $msg = "\n" . join( ': ',
    ( $0 eq '-e' ? () : $0 ),
    $args->{msg}
  );

  # when we die - we usually want to keep doing it
  $args->{emit_dups} = !!$args->{confess}
    unless exists $args->{emit_dups};

  local $Carp::CarpLevel =
    ( $args->{skip_frames} || 0 )
      +
    $Carp::CarpLevel
      +
    # hide our own frame
    1
  ;

  my $longmess = Carp::longmess();

  # different object references will thwart deduplication without this
  ( my $key = "${msg}\n${longmess}" ) =~ s/\b0x[0-9a-f]+\b/0x.../gi;

  return $seen_loud_screams->{$key} if
    $seen_loud_screams->{$key}++
      and
    ! $args->{emit_dups}
  ;

  $msg .= $longmess
    unless $msg =~ /\n\z/;

  print STDERR "$msg\n"
    or
  print STDOUT "\n!!!STDERR ISN'T WRITABLE!!!:$msg\n";

  return $seen_loud_screams->{$key}
    unless $args->{confess};

  # increment *again*, because... Carp.
  $Carp::CarpLevel++;
  # not $msg - Carp will reapply the longmess on its own
  Carp::confess($args->{msg});
}


sub scope_guard (&) {
  croak 'Calling scope_guard() in void context makes no sense'
    if ! defined wantarray;

  # no direct blessing of coderefs - DESTROY is buggy on those
  bless [ $_[0] ], 'DBIx::Class::_Util::ScopeGuard';
}
{
  package #
    DBIx::Class::_Util::ScopeGuard;

  sub DESTROY {
    &DBIx::Class::_Util::detected_reinvoked_destructor;

    local $@ if DBIx::Class::_ENV_::UNSTABLE_DOLLARAT;

    eval {
      $_[0]->[0]->();
      1;
    } or do {
      Carp::cluck "Execution of scope guard $_[0] resulted in the non-trappable exception:\n\n$@";
    };
  }
}

sub is_exception ($) {
  my $e = $_[0];

  # this is not strictly correct - an eval setting $@ to undef
  # is *not* the same as an eval setting $@ to ''
  # but for the sake of simplicity assume the following for
  # the time being
  return 0 unless defined $e;

  my ($not_blank, $suberror);
  {
    local $@;
    eval {
      $not_blank = ($e ne '') ? 1 : 0;
      1;
    } or $suberror = $@;
  }

  if (defined $suberror) {
    if (length (my $class = blessed($e) )) {
      carp_unique( sprintf(
        'External exception class %s implements partial (broken) overloading '
      . 'preventing its instances from being used in simple ($x eq $y) '
      . 'comparisons. Given Perl\'s "globally cooperative" exception '
      . 'handling this type of brokenness is extremely dangerous on '
      . 'exception objects, as it may (and often does) result in silent '
      . '"exception substitution". DBIx::Class tries to work around this '
      . 'as much as possible, but other parts of your software stack may '
      . 'not be even aware of this. Please submit a bugreport against the '
      . 'distribution containing %s and in the meantime apply a fix similar '
      . 'to the one shown at %s, in order to ensure your exception handling '
      . 'is saner application-wide. What follows is the actual error text '
      . "as generated by Perl itself:\n\n%s\n ",
        $class,
        $class,
        'http://v.gd/DBIC_overload_tempfix/',
        $suberror,
      ));

      # workaround, keeps spice flowing
      $not_blank = ("$e" ne '') ? 1 : 0;
    }
    else {
      # not blessed yet failed the 'ne'... this makes 0 sense...
      # just throw further
      die $suberror
    }
  }

  return $not_blank;
}

{
  my $destruction_registry = {};

  sub CLONE {
    $destruction_registry = { map
      { defined $_ ? ( refaddr($_) => $_ ) : () }
      values %$destruction_registry
    };
  }

  # This is almost invariably invoked from within DESTROY
  # throwing exceptions won't work
  sub detected_reinvoked_destructor {

    # quick "garbage collection" pass - prevents the registry
    # from slowly growing with a bunch of undef-valued keys
    defined $destruction_registry->{$_} or delete $destruction_registry->{$_}
      for keys %$destruction_registry;

    if (! length ref $_[0]) {
      printf STDERR '%s() expects a blessed reference %s',
        (caller(0))[3],
        Carp::longmess,
      ;
      return undef; # don't know wtf to do
    }
    elsif (! defined $destruction_registry->{ my $addr = refaddr($_[0]) } ) {
      weaken( $destruction_registry->{$addr} = $_[0] );
      return 0;
    }
    else {
      carp_unique ( sprintf (
        'Preventing *MULTIPLE* DESTROY() invocations on %s - an *EXTREMELY '
      . 'DANGEROUS* condition which is *ALMOST CERTAINLY GLOBAL* within your '
      . 'application, affecting *ALL* classes without active protection against '
      . 'this. Diagnose and fix the root cause ASAP!!!%s',
      refdesc $_[0],
        ( ( $INC{'Devel/StackTrace.pm'} and ! do { local $@; eval { Devel::StackTrace->VERSION(2) } } )
          ? " (likely culprit Devel::StackTrace\@@{[ Devel::StackTrace->VERSION ]} found in %INC, http://is.gd/D_ST_refcap)"
          : ''
        )
      ));

      return 1;
    }
  }
}

sub modver_gt_or_eq ($$) {
  my ($mod, $ver) = @_;

  croak "Nonsensical module name supplied"
    if ! defined $mod or ! length $mod;

  croak "Nonsensical minimum version supplied"
    if ! defined $ver or $ver =~ /[^0-9\.\_]/;

  local $SIG{__WARN__} = sigwarn_silencer( qr/\Qisn't numeric in subroutine entry/ )
    if SPURIOUS_VERSION_CHECK_WARNINGS;

  croak "$mod does not seem to provide a version (perhaps it never loaded)"
    unless $mod->VERSION;

  local $@;
  eval { $mod->VERSION($ver) } ? 1 : 0;
}

sub modver_gt_or_eq_and_lt ($$$) {
  my ($mod, $v_ge, $v_lt) = @_;

  croak "Nonsensical maximum version supplied"
    if ! defined $v_lt or $v_lt =~ /[^0-9\.\_]/;

  return (
    modver_gt_or_eq($mod, $v_ge)
      and
    ! modver_gt_or_eq($mod, $v_lt)
  ) ? 1 : 0;
}

{
  my $list_ctx_ok_stack_marker;

  sub fail_on_internal_wantarray () {
    return if $list_ctx_ok_stack_marker;

    if (! defined wantarray) {
      croak('fail_on_internal_wantarray() needs a tempvar to save the stack marker guard');
    }

    my $cf = 1;
    while ( ( (caller($cf+1))[3] || '' ) =~ / :: (?:

      # these are public API parts that alter behavior on wantarray
      search | search_related | slice | search_literal

        |

      # these are explicitly prefixed, since we only recognize them as valid
      # escapes when they come from the guts of CDBICompat
      CDBICompat .*? :: (?: search_where | retrieve_from_sql | retrieve_all )

    ) $/x ) {
      $cf++;
    }

    my ($fr, $want, $argdesc);
    {
      package DB;
      $fr = [ caller($cf) ];
      $want = ( caller($cf-1) )[5];
      $argdesc = ref $DB::args[0]
        ? DBIx::Class::_Util::refdesc($DB::args[0])
        : 'non '
      ;
    };

    if (
      $want and $fr->[0] =~ /^(?:DBIx::Class|DBICx::)/
    ) {
      DBIx::Class::Exception->throw( sprintf (
        "Improper use of %s instance in list context at %s line %d\n\n    Stacktrace starts",
        $argdesc, @{$fr}[1,2]
      ), 'with_stacktrace');
    }

    my $mark = [];
    weaken ( $list_ctx_ok_stack_marker = $mark );
    $mark;
  }
}

sub fail_on_internal_call {
  my ($fr, $argdesc);
  {
    package DB;
    $fr = [ caller(1) ];
    $argdesc = ref $DB::args[0]
      ? DBIx::Class::_Util::refdesc($DB::args[0])
      : undef
    ;
  };

  if (
    $argdesc
      and
    $fr->[0] =~ /^(?:DBIx::Class|DBICx::)/
      and
    $fr->[1] !~ /\b(?:CDBICompat|ResultSetProxy)\b/  # no point touching there
  ) {
    DBIx::Class::Exception->throw( sprintf (
      "Illegal internal call of indirect proxy-method %s() with argument %s: examine the last lines of the proxy method deparse below to determine what to call directly instead at %s on line %d\n\n%s\n\n    Stacktrace starts",
      $fr->[3], $argdesc, @{$fr}[1,2], ( $fr->[6] || do {
        require B::Deparse;
        no strict 'refs';
        B::Deparse->new->coderef2text(\&{$fr->[3]})
      }),
    ), 'with_stacktrace');
  }
}

1;
