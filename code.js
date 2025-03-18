document.addEventListener('DOMContentLoaded', function() {
    // Привязываем обработчик события к кнопке "Начать"
    const startButton = document.getElementById('startButton');
    if (startButton) {
        startButton.addEventListener('click', startButton); 
    } else {
        console.error('Кнопка "Начать" не найдена');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Функция testBtn обертка
    function testBtn() {
        window.location.href = 'test.html'; 
    }

    // Привязываем функцию к кнопке "Пройти тестирование"
    const testButton = document.getElementById('testButton');
    if (testButton) {
        testButton.onclick = testBtn; // Привязываем обработчик события
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const scrollContainer = document.getElementById('scrollContainer');
    const items = Array.from(scrollContainer.children); // Получаем все элементы
    const itemWidth = items[0].offsetWidth + parseInt(window.getComputedStyle(items[0]).marginRight); // Ширина элемента + отступ

    // Клонируем элементы для создания эффекта бесконечности
    scrollContainer.innerHTML += scrollContainer.innerHTML;

    let isScrolling = false;

    // Обработчик прокрутки
    scrollContainer.addEventListener('scroll', function () {
        if (!isScrolling) {
            isScrolling = true;

            // Если прокрутка дошла до конца, возвращаемся в начало
            if (scrollContainer.scrollLeft >= scrollContainer.scrollWidth / 2) {
                scrollContainer.scrollLeft -= scrollContainer.scrollWidth / 2;
            }
            // Если прокрутка дошла до начала, возвращаемся в конец
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
        // Проверяем текущее состояние поворота
        if (strelkaImg.style.transform === 'rotate(180deg)') {
            // Если уже перевернуто, возвращаем в исходное состояние
            strelkaImg.style.transform = 'rotate(0deg)';
        } else {
            // Если не перевернуто, переворачиваем на 180 градусов
            strelkaImg.style.transform = 'rotate(180deg)';
        }
        // Добавляем плавный переход
        strelkaImg.style.transition = 'transform 0.3s ease';
    };
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-row'); // Use class selector
    const searchButton = document.querySelector('.search-button'); // Assuming you want to keep the class selector for the button

    // Обработчик для кнопки "Найти"
    searchButton.addEventListener('click', function () {
        performSearch();
    });

    // Обработчик для нажатия Enter в поисковой строке
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    function performSearch() {
        const query = searchInput.value.trim().toLowerCase(); // Получаем значение поиска и приводим к нижнему регистру

        // Проверяем введенное слово и перенаправляем на соответствующую страницу
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
                        // Если пользователь вошел, перенаправляем на card.html
                        window.location.href = 'enter.html';
                    } else {
                        // Если пользователь не вошел, перенаправляем на enter.html
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
    if (menuContainer) { // Проверяем, существует ли элемент
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
        // Переключаем видимость меню
        if (menuContainer.style.display === 'none' || menuContainer.style.display === '') {
            menuContainer.style.display = 'block';
            strelkaImg.style.transform = 'rotate(180deg)'; // Поворачиваем стрелку
        } else {
            menuContainer.style.display = 'none';
            strelkaImg.style.transform = 'rotate(0deg)'; // Возвращаем стрелку в исходное положение
        }
        // Добавляем плавный переход
        strelkaImg.style.transition = 'transform 0.3s ease';
    }

    // Привязываем обработчик события к кнопке и изображению стрелки
    if (toggleButton) {
        toggleButton.onclick = toggleMenu; // Привязываем обработчик события
    } else {
        console.error('Элемент с ID "toggle-button" не найден');
    }

    if (strelkaImg) {
        strelkaImg.onclick = toggleMenu; // Привязываем обработчик события
    } else {
        console.error('Элемент с ID "strelkaImg" не найден');
    }

    // Закрытие меню при клике вне его
    window.onclick = function(event) {
        if (menuContainer && !event.target.matches('#toggle-button') && !event.target.matches('#strelkaImg') && menuContainer.style.display === 'block') {
            menuContainer.style.display = 'none';
            strelkaImg.style.transform = 'rotate(0deg)'; // Возвращаем стрелку в исходное положение
        }
    };
});