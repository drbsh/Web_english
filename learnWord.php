<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 

$host = 'localhost';
$dbname = 'web_english';
$user = 'postgres';
$password = 'drbsh';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Получаем все слова из всех таблиц
$table_names = [
    'animal_card' => 'animal_card',
    'fruit_card' => 'fruit_card',
    'vegetable_card' => 'veg_card', 
    'food_card' => 'food_card',
    'furn_card' => 'furn_card',
    'cloth_card' => 'cloth_card',
];

$all_words = [];
foreach ($table_names as $table_name) {
    $stmt = $pdo->prepare("SELECT english_word FROM $table_name");
    $stmt->execute();
    $words = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $all_words = array_merge($all_words, $words);
}

if (isset($_POST['word_count'])) {
    $word_count = intval($_POST['word_count']);
    $_SESSION['word_count'] = $word_count;
} else {
    $word_count = isset($_SESSION['word_count']) ? $_SESSION['word_count'] : 5;
}

shuffle($all_words);
$selected_words = array_slice($all_words, 0, $word_count);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Изучение слов</title>
    <link rel="stylesheet" href="code/card.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 10px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .goTestcss {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
    </style>
    <script>
    let words = <?php echo json_encode($selected_words); ?>;
    let currentIndex = 0;
    let timer;
    let timeLeft = 0;

    function showModal(message) {
        document.getElementById('modal-result').innerText = message;
        document.getElementById('myModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }

    function startLearning() {
        currentIndex = 0;
        timeLeft = words.length * 5;
        document.getElementById('time-left').innerText = timeLeft;
        document.getElementById('word-display').innerText = words[currentIndex];
        timer = setInterval(updateWord, 5000);
        setInterval(updateTime, 1000);
    }

    function updateWord() {
        currentIndex++;
        if (currentIndex < words.length) {
            document.getElementById('word-display').innerText = words[currentIndex];
        } else {
            clearInterval(timer);
            showModal('Вы закончили изучение слов!');
        }
    }

    function updateTime() {
        if (timeLeft > 0) {
            timeLeft--;
            document.getElementById('time-left').innerText = timeLeft;
        }
        if (timeLeft <= 0) {
            clearInterval(timer);
            showModal('Время вышло!');
        }
    }

    function closeBtn1() {
        window.location.href = 'index.html';
    }
    </script>
</head>
<body>
    <header class="headerCardPage"> 
        <h1>Изучение слов</h1> 
        <img class="close-btn" id="close-btn1" src="Image/closeCardPage.svg" onclick="closeBtn1()">   
    </header>
    <main style="text-align: center; margin-top: 20px;">
        <form method="POST" onsubmit="startLearning(); return false;">
            <label class="word_count_text" for="word_count">Сколько слов вы хотите изучить?</label>
            <input class="sumNumber" type="number" id="word_count" name="word_count" min="1" max="<?php echo count($all_words); ?>" value="<?php echo $word_count; ?>" required>
            <div id="word-display" style="font-size: 2em; margin: 20px;"></div>
            <div id="time-left" style="font-size: 1.5em;"></div>
            <div style="margin: 20px 0;">
                <button class="checkBtn" type="submit">Начать</button>
            </div>
        </form>
    </main>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-result"></p>
            <button class="goTestcss" onclick="window.location.href='learnWord.php'">Продолжить</button>
        </div>
    </div>
</body>
</html>