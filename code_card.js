function closeBtn() {
    window.location.href = 'card.html';
}

// 

let currentIndex = 0;

function moveSlide(direction) {
    const cardContainer = document.querySelector('.card-container');
    const cards = document.querySelectorAll('.card');
    const totalCards = cards.length;

    
    if (direction === 1 && currentIndex < totalCards - 1) {
        currentIndex++; 
    } else if (direction === -1 && currentIndex > 0) {
        currentIndex--; 
    }

    
    const offset = -currentIndex * 300; 
    cardContainer.style.transform = `translateX(${offset}px)`;

    
    document.querySelector('.left-arrow').disabled = currentIndex === 0;
    document.querySelector('.right-arrow').disabled = currentIndex === totalCards - 1;
}


window.onload = function() {
    const totalCards = document.querySelectorAll('.card').length;
    document.querySelector('.left-arrow').disabled = true; 
    document.querySelector('.right-arrow').disabled = totalCards <= 1; 
};

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


const totalCards = document.querySelectorAll('.card').length;
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');

if (leftArrow) {
    leftArrow.disabled = true; 
}
if (rightArrow) {
    rightArrow.disabled = totalCards <= 1; 
}

