document.addEventListener('DOMContentLoaded', function() {
    const startButton = document.getElementById('startButton');
    const sessionForm = document.getElementById('sessionForm');

    if (startButton) {
        sessionForm.addEventListener('submit', function(event) {
            event.preventDefault();

            fetch('check_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
        });
    } else {
        console.error('Кнопка "Начать" не найдена');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    function testBtn() {
        window.location.href = 'test.html'; 
    }

    const testButton = document.getElementById('testButton');
    if (testButton) {
        testButton.onclick = testBtn;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const scrollContainer = document.getElementById('scrollContainer');
    const items = Array.from(scrollContainer.children);
    const itemWidth = items[0].offsetWidth + parseInt(window.getComputedStyle(items[0]).marginRight);

    scrollContainer.innerHTML += scrollContainer.innerHTML;

    let isScrolling = false;

    scrollContainer.addEventListener('scroll', function () {
        if (!isScrolling) {
            isScrolling = true;

            if (scrollContainer.scrollLeft >= scrollContainer.scrollWidth / 2) {
                scrollContainer.scrollLeft -= scrollContainer.scrollWidth / 2;
            }
            else if (scrollContainer.scrollLeft <= 0) {
                scrollContainer.scrollLeft += scrollContainer.scrollWidth / 2;
            }

            isScrolling = false;
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-button');
    const strelkaImg = document.getElementById('strelkaImg');

    toggleButton.onclick = function() {
        if (strelkaImg.style.transform === 'rotate(180deg)') {
            strelkaImg.style.transform = 'rotate(0deg)';
        } else {
            strelkaImg.style.transform = 'rotate(180deg)';
        }
        strelkaImg.style.transition = 'transform 0.3s ease';
    };
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-row');
    const searchButton = document.querySelector('.search-button');

    searchButton.addEventListener('click', function () {
        performSearch();
    });

    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();

        switch (query) {
            case 'карточки':
                window.location.href = 'card.html';
                break;
            case 'тесты':
                window.location.href = 'test.html';
                break;
            case 'заучивание':
                window.location.href = 'learnWord.html';
                break;
            case 'выбор слов':
                window.location.href = 'choiceWord.html';
                break;
            default:
                alert('Ничего не найдено. Попробуйте ввести "карточки", "тесты", "заучивание" или "выбор слов".');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const startButton = document.getElementById('startButton');
    if (startButton) {
        startButton.addEventListener('click', function() {
            fetch('check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        window.location.href = 'enter.html';
                    } else {
                        window.location.href = 'card.html';
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        });
    } else {
        console.error('Кнопка "Начать" не найдена');
    }
});

function goToLoginPage() {
    window.location.href = 'enter.html';
} 

function createAccountButton() {
    window.location.href= 'register.php';
}

function RegisterBtn() {
    window.location.href= 'index.html';
}
function goBack() {
    window.location.href= 'enter.html';
}
function goBack2() {
    window.location.href= 'enter.html';
}
function backIconclick() {
    window.location.href= 'index.html';
}
function forgotPassBtn() {
    window.location.href= 'index.php';
}
function dialogButton() {
    const menuContainer = document.getElementById('menu-container');
    if (menuContainer) {
        if (menuContainer.style.display === 'none' || menuContainer.style.display === '') {
            menuContainer.style.display = 'block';
        } else {
            menuContainer.style.display = 'none';
        }
    } else {
        console.error('Элемент с ID "menu-container" не найден');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-button');
    const strelkaImg = document.getElementById('strelkaImg');
    const menuContainer = document.getElementById('menu-container');

    function toggleMenu() {
        if (menuContainer.style.display === 'none' || menuContainer.style.display === '') {
            menuContainer.style.display = 'block';
            strelkaImg.style.transform = 'rotate(180deg)';
        } else {
            menuContainer.style.display = 'none';
            strelkaImg.style.transform = 'rotate(0deg)';
        }
        strelkaImg.style.transition = 'transform 0.3s ease';
    }

    if (toggleButton) {
        toggleButton.onclick = toggleMenu;
    } else {
        console.error('Элемент с ID "toggle-button" не найден');
    }

    if (strelkaImg) {
        strelkaImg.onclick = toggleMenu;
    } else {
        console.error('Элемент с ID "strelkaImg" не найден');
    }

    window.onclick = function(event) {
        if (menuContainer && !event.target.matches('#toggle-button') && !event.target.matches('#strelkaImg') && menuContainer.style.display === 'block') {
            menuContainer.style.display = 'none';
            strelkaImg.style.transform = 'rotate(0deg)';
        }
    };
});