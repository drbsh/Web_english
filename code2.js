

function closeBtn() {
     window.location.href = 'index.html';
}
// Функция для отображения меню тем
function dialogButton() {
    const menuContainer = document.getElementById('menu-theme-card-container');
    if (menuContainer) {
        menuContainer.style.display = (menuContainer.style.display === 'block') ? 'none' : 'block';
    }
}


function dialogButtonLevel() {
    const menuContainer = document.getElementById('menu-level-card-container');
    if (menuContainer) {
        menuContainer.style.display = (menuContainer.style.display === 'block') ? 'none' : 'block';
    }
}

// Обработчик клика для закрытия меню при клике вне его
window.onclick = function(event) {
    const menuThemeContainer = document.getElementById('menu-theme-card-container');
    const menuLevelContainer = document.getElementById('menu-level-card-container');

    if (menuThemeContainer && !event.target.matches('#theme-card') && menuThemeContainer.style.display === 'block') {
        menuThemeContainer.style.display = 'none';
    }

    if (menuLevelContainer && !event.target.matches('#level-card') && menuLevelContainer.style.display === 'block') {
        menuLevelContainer.style.display = 'none';
    }
}

// Привязываем функции к кнопкам
const themeCardButton = document.getElementById('theme-card');
const levelCardButton = document.getElementById('level-card');

if (themeCardButton) {
    themeCardButton.onclick = dialogButton;
} else {
    console.error('Элемент с ID "theme-card" не найден');
}

if (levelCardButton) {
    levelCardButton.onclick = dialogButtonLevel;
} else {
    console.error('Элемент с ID "level-card" не найден');
}
