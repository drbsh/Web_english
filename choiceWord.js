function closeBtn() {
    window.location.href = 'choiceWord.html';
}
document.addEventListener('DOMContentLoaded', function() {
    let selectedEnglish = null;

    // Получаем все элементы с классом choiceWord
    const englishCards = document.querySelectorAll('.english-cards .choiceWord');
    const russianCards = document.querySelectorAll('.russian-cards .choiceWord');

    // Добавляем обработчики событий для английских слов
    englishCards.forEach(card => {
        card.addEventListener('click', function() {
            if (selectedEnglish) {
                selectedEnglish.classList.remove('selected', 'dark-gray');
            }
            selectedEnglish = this;
            selectedEnglish.classList.add('selected', 'dark-gray'); // Добавляем класс темно-серого цвета
        });
    });

    // Добавляем обработчики событий для русских слов
    russianCards.forEach(card => {
        card.addEventListener('click', function() {
            if (selectedEnglish) {
                const russianId = this.getAttribute('data-id');
                const englishId = selectedEnglish.getAttribute('data-id');

                // Проверяем, совпадают ли ID
                if (englishId === russianId) {
                    selectedEnglish.classList.add('correct');
                    this.classList.add('correct'); // Добавляем класс для правильного ответа
                    correctMatches++; // Увеличиваем счетчик правильных ответов
                } else {
                    selectedEnglish.classList.add('incorrect');
                    this.classList.add('incorrect'); // Добавляем класс для неправильного ответа
                }
                selectedEnglish.classList.remove('selected', 'dark-gray'); // Убираем выделение
                selectedEnglish = null;

                // Проверяем, выбраны ли все карточки
                checkAllSelected();
            }
        });
    });
});

let selectedPairs = []; // Массив для хранения выбранных пар (английское и русское слово)
const totalPairs = document.querySelectorAll('.english-cards .choiceWord').length; // Общее количество пар
const correctPairs = []; // Массив для хранения правильных пар
let correctMatches = 0; // Счетчик правильных ответов

// Заполняем массив правильных пар
document.querySelectorAll('.choiceWord').forEach(card => {
    const englishWord = card.getAttribute('data-english');
    const russianWord = card.getAttribute('data-russian');
    if (englishWord && russianWord) {
        correctPairs.push({ englishWord, russianWord });
    }
});

// Обработчик клика для английских карточек
document.querySelectorAll('.english-cards .choiceWord').forEach(card => {
    card.addEventListener('click', () => {
        const englishWord = card.getAttribute('data-english');
        const cardId = card.getAttribute('data-id');

        // Проверяем, выбрано ли уже это английское слово
        if (!selectedPairs.some(pair => pair.englishId === cardId)) {
            selectedPairs.push({ englishId: cardId, englishWord: englishWord });
            checkAllSelected();
        }
    });
});

// Обработчик клика для русских карточек
document.querySelectorAll('.russian-cards .choiceWord').forEach(card => {
    card.addEventListener('click', () => {
        const russianWord = card.getAttribute('data-russian');
        const cardId = card.getAttribute('data-id');

        // Проверяем, выбрано ли уже это русское слово
        if (!selectedPairs.some(pair => pair.russianId === cardId)) {
            selectedPairs.push({ russianId: cardId, russianWord: russianWord });
            checkAllSelected();
        }
    });
});

// Функция для проверки, выбраны ли все карточки
function checkAllSelected() {
    if (selectedPairs.length === totalPairs * 2) { // Умножаем на 2, так как у нас есть пара для каждого слова
        showResult();
    }
}

// Функция для отображения результата
function showResult() {
    // Отображение результата в модальном окне
    const modalResult = document.getElementById('modal-result');
    modalResult.innerHTML = `Вы выбрали ${correctMatches} из ${totalPairs} правильных совпадений.`;
    
    const modal = document.getElementById('myModal');
    modal.style.display = "block";

    // Сброс выбора
    selectedPairs = [];
    correctMatches = 0; 
}

// Функция для закрытия модального окна
function closeModal() {
    const modal = document.getElementById('myModal');
    modal.style.display = "none";
}

// Функция для продолжения теста
function continueTest() {
    closeModal();
    // Здесь вы можете добавить логику для продолжения теста
    // Например, сбросить состояние или перейти к следующему этапу
}

// Функция для повторного прохождения теста
function retryTest() {
    closeModal();
    // Здесь вы можете добавить логику для повторного прохождения теста
    // Например, сбросить состояние
    selectedPairs = [];
    correctMatches = 0; // Сброс счетчика правильных ответов
    // Сбросить классы у карточек
    document.querySelectorAll('.english-cards .choiceWord, .russian-cards .choiceWord').forEach(card => {
        card.classList.remove('correct', 'incorrect', 'selected', 'dark-gray');
    });
}

// Привязываем обработчики событий к кнопкам в модальном окне
document.getElementById('close-modal-btn').addEventListener('click', closeModal);
document.getElementById('continue-test-btn').addEventListener('click', continueTest);
document.getElementById('retry-test-btn').addEventListener('click', retryTest);