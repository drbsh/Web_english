function closeBtn() {
    window.location.href = 'card.html';
}

// 

let currentIndex = 0;

function moveSlide(direction) {
    const cardContainer = document.querySelector('.card-container');
    const cards = document.querySelectorAll('.card');
    const totalCards = cards.length;

    // Проверяем направление и обновляем индекс текущей карточки
    if (direction === 1 && currentIndex < totalCards - 1) {
        currentIndex++; // Перелистываем вправо
    } else if (direction === -1 && currentIndex > 0) {
        currentIndex--; // Перелистываем влево
    }

    // Сдвигаем карточки
    const offset = -currentIndex * 300; // 300 - ширина карточки
    cardContainer.style.transform = `translateX(${offset}px)`;

    // Отключаем стрелочки, если достигли конца
    document.querySelector('.left-arrow').disabled = currentIndex === 0;
    document.querySelector('.right-arrow').disabled = currentIndex === totalCards - 1;
}

// Инициализация состояния стрелочек при загрузке страницы
window.onload = function() {
    const totalCards = document.querySelectorAll('.card').length;
    document.querySelector('.left-arrow').disabled = true; // Отключаем левую стрелочку
    document.querySelector('.right-arrow').disabled = totalCards <= 1; // Отключаем правую стрелочку, если карточек меньше 2
};


// Инициализация состояния стрелочек при загрузке страницы
const totalCards = document.querySelectorAll('.card').length;
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');

if (leftArrow) {
    leftArrow.disabled = true; // Отключаем левую стрелочку
}
if (rightArrow) {
    rightArrow.disabled = totalCards <= 1; // Отключаем правую стрелочку, если карточек меньше 2
}

