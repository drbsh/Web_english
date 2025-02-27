<?php
session_start(); // Начать сессию
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пример красивого сайта</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Title+Hero&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="code/style.css">
    <script src="code.js" defer></script>
</head>
<body>
    <header>
        <div class="icon-web">
            <a href="#page1">
                <img class="icon1" src="Image/image-Photoroom (2).png">
            </a>
        </div>
        <button id="toggle-button" class="toggle-button" onclick="dialogButton()">
            <span>Инструменты</span>  
        </button>
        <img id="strelkaImg" src="Image/strelka.svg" alt="" width="30" height="30" style="cursor: pointer;">
        <a href="#page3" class="base-button">База знаний</a>
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="search" class="search-row" placeholder="Поиск">
        </div>
        <input type="button" class="search-button" value="Найти">
        <div class="user-container">
            <img class="icon-person" src="Image/icons8-пользователь-48.png">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="logout.php" method="post">
                    <input type="submit" class="enter-button" value="Выход">
                </form>
            <?php else: ?>
                <button class="enter-button" onclick="goToLoginPage()"> Вход </button>
            <?php endif; ?>
        </div>
    </header>
    <div id="menu-container" class="menu-container" style="display: none;">
        <nav>
            <ul>
                <p class="menu-text">Занятия</p>
                <li><a href="#">Карточки</a></li>
                <li><a href="#">Выбор слов</a></li>
                <li><a href="#">Заучивание</a></li>
                <li><a href="#">Тест</a></li>
                <p class="menu-text">О нас</p>
                <li><a href="https://t.me/drbshh">Telegram</a></li>
                <li><a href="#">Vk</a></li>
                <li><a href="#">Instagram</a></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <div class="page" id="page1">
            <div class="text-first-page">
                <p class="text1"> Найди для себя простой<br> способ изучения<br> английского языка</p>
                <p class="text2">
                    <span class="checkmark"> ✓ </span> Создавай свои или уже готовые<br>
                    <span class="indent-text2">карточки со словами на сайте.</span><br>
                    <br>
                    <span class="checkmark"> ✓</span> Проходи тесты и подтверждай свой уровень. <br>
                    <br>
                    <span class="checkmark"> ✓ </span> Большая база слов и методов изучения.<br>
                    <br>
                    <span class="checkmark">✓</span> Занятия в группе с другими учениками.
                </p>
            </div>
            <div class="enBook">
                <img class="enBook-img" src="Image/english_book.png">
            </div>
            <div class="buttonGoDiv">
                <button class="buttonGo" onclick="toggleContent()"> Начать </button>
            </div>
        </div>
        <div class="page" id="page2">
            <h1 class="text-second-page"> Как вы собираетесь учить?</h1>
            <div class="scroll-container" id="scrollContainer">
                <div class="item1">
                    <p class="examText">Тесты</p>
                    <img class="examPng" src="Image/exam.png">
                </div>
                <div class="item2">
                    <p class="cardText">Выбор слов</p>
                    <img class="cardImg" src="Image/card.jpg">
                </div>
                <div class="item3">
                    <p class="learnText">Заучивание</p>
                    <img class="learnImg" src="Image/learn.png">
                </div>
                <div class="item4">
                    <p class="cardText2">Карточки</p>
                    <img class="cardImg2" src="Image/shark2.jpg">
                </div>    
            </div>
        </div>
        <div class="page" id="page3">
            <div class="imgPage3">
                <img class="speakEn-img" src="Image/DoUSpeakEn.png"> 
            </div>
            <div class="textPage3">
                <h1 class="speakEn-text">Пройди тест и узнай<br> свой уровень<br> английского </h1>
                <p class="speakEn-text2">Сделай обучение увлекательным и эффективным!<br>
                На нашем сайте ты можешь выполнять <br>
                разнообразные задания, которые помогут тебе<br>
                прокачать свои навыки и знания.<br>
                Каждый тест — это шаг к успеху,<br>
                который подтверждает твои достижения.<br> Не останавливайся на достигнутом:<br>
                повышай свой уровень с каждым днем.<br>
                Твое стремление к знаниям откроют перед тобой<br>
                новые горизонты!</p>
            </div>
            <div class="testButton-container">
                <button class="testButton" onclick="toggleContent()">Пройти тестирование!</button> 
            </div>
        </div>
    </div>
    <footer>
        <p class="footer-text" >&copy; 2025 BoostEnglish, Inc. Все права защищены.</p>
    </footer>
</body>
</html>