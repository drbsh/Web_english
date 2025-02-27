function goToLoginPage() {
    window.location.href = 'enter.html';
} 

function createAccountButton(){
    window.location.href= 'register.html';
}

function goBack(){
    window.location.href= 'enter.html';
}

function backIconclick(){
    window.location.href= 'index.html';
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
    window.onclick = function(event) {
        const menuContainer = document.getElementById('menu-container');
        const toggleButton = document.getElementById('toggle-button');
        const strelkaImg = document.getElementById('strelkaImg');

        if (menuContainer && !event.target.matches('#toggle-button') && !event.target.matches('#strelkaImg') && menuContainer.style.display === 'block') {
            menuContainer.style.display = 'none';
        }
    }

    // Привязываем функцию к кнопкам
    const toggleButton = document.getElementById('toggle-button');
    const strelkaImg = document.getElementById('strelkaImg');

    if (toggleButton) {
        toggleButton.onclick = dialogButton; // Привязываем обработчик события
    } else {
        console.error('Элемент с ID "toggle-button" не найден');
    }

    if (strelkaImg) {
        strelkaImg.onclick = dialogButton; // Привязываем обработчик события
    } else {
        console.error('Элемент с ID "strelkaImg" не найден');
    }
});
