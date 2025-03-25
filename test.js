function closeBtn1() {
    window.location.href = 'test.html';
}

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('theme-card');
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
