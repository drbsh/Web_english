function closeBtn() {
    window.location.href = 'choiceWord.html';
}

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

document.addEventListener('DOMContentLoaded', function() {
    let selectedEnglish = null;
    const englishCards = document.querySelectorAll('.english-cards .choiceWord');
    const russianCards = document.querySelectorAll('.russian-cards .choiceWord');

    englishCards.forEach(card => {
        card.addEventListener('click', function() {
            if (selectedEnglish) {
                selectedEnglish.classList.remove('selected', 'dark-gray');
            }
            selectedEnglish = this;
            selectedEnglish.classList.add('selected', 'dark-gray');
        });
    });

    russianCards.forEach(card => {
        card.addEventListener('click', function() {
            if (selectedEnglish) {
                const russianId = this.getAttribute('data-id');
                const englishId = selectedEnglish.getAttribute('data-id');

                if (englishId === russianId) {
                    selectedEnglish.classList.add('correct');
                    this.classList.add('correct');
                    correctMatches++;
                } else {
                    selectedEnglish.classList.add('incorrect');
                    this.classList.add('incorrect');
                }
                selectedEnglish.classList.remove('selected', 'dark-gray');
                selectedEnglish = null;
                checkAllSelected();
            }
        });
    });
});

let selectedPairs = [];
const totalPairs = document.querySelectorAll('.english-cards .choiceWord').length;
const correctPairs = [];
let correctMatches = 0;

document.querySelectorAll('.choiceWord').forEach(card => {
    const englishWord = card.getAttribute('data-english');
    const russianWord = card.getAttribute('data-russian');
    if (englishWord && russianWord) {
        correctPairs.push({ englishWord, russianWord });
    }
});

document.querySelectorAll('.english-cards .choiceWord').forEach(card => {
    card.addEventListener('click', () => {
        const englishWord = card.getAttribute('data-english');
        const cardId = card.getAttribute('data-id');

        if (!selectedPairs.some(pair => pair.englishId === cardId)) {
            selectedPairs.push({ englishId: cardId, englishWord: englishWord });
            checkAllSelected();
        }
    });
});

document.querySelectorAll('.russian-cards .choiceWord').forEach(card => {
    card.addEventListener('click', () => {
        const russianWord = card.getAttribute('data-russian');
        const cardId = card.getAttribute('data-id');

        if (!selectedPairs.some(pair => pair.russianId === cardId)) {
            selectedPairs.push({ russianId: cardId, russianWord: russianWord });
            checkAllSelected();
        }
    });
});

function checkAllSelected() {
    if (selectedPairs.length === totalPairs * 2) {
        showResult();
    }
}

function showResult() {
    const modalResult = document.getElementById('modal-result');
    modalResult.innerHTML = `Вы выбрали ${correctMatches} из ${totalPairs} правильных совпадений.`;
    
    const modal = document.getElementById('myModal');
    modal.style.display = "block";

    selectedPairs = [];
    correctMatches = 0; 
}

function closeModal() {
    const modal = document.getElementById('myModal');
    modal.style.display = "none";
}

function continueTest() {
    closeModal();
}

function retryTest() {
    closeModal();
    selectedPairs = [];
    correctMatches = 0; 
    document.querySelectorAll('.english-cards .choiceWord, .russian-cards .choiceWord').forEach(card => {
        card.classList.remove('correct', 'incorrect', 'selected', 'dark-gray');
    });
}

document.getElementById('close-modal-btn').addEventListener('click', closeModal);
document.getElementById('continue-test-btn').addEventListener('click', continueTest);
document.getElementById('retry-test-btn').addEventListener('click', retryTest);
