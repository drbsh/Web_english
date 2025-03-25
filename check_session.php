<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['loggedIn' => false, 'redirect' => 'enter.html']);
} else {
    echo json_encode(['loggedIn' => true, 'redirect' => 'card.html']);
}
?>