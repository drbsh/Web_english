<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Настройки подключения к базе данных
$host = 'localhost';
$dbname = 'web_english';
$user = 'postgres';
$password = 'drbsh';

try {
    // Подключение к базе данных PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Список таблиц и их псевдонимы для URL
$table_names = [
    'prefixes' => 'prefixes',
    'past_tense' => 'past_tense',
    'word_substitution' => 'word_substitution',
    'questions' => 'questions',
    'present_tense' => 'present_tense',
    'future_tense' => 'future_tense',
];

// Получаем тип карточек из URL (например, ?type=prefixes)
$card_type = isset($_GET['type']) ? $_GET['type'] : 'prefixes';

// Проверяем, существует ли выбранный тип карточек в списке
if (array_key_exists($card_type, $table_names)) {
    $table_name = $table_names[$card_type];
} else {
    // Если тип карточек не найден, используем таблицу prefixes по умолчанию
    $table_name = 'prefixes';
}

// Запрос к базе данных для получения данных из выбранной таблицы
$stmt = $pdo->prepare("SELECT * FROM $table_name");
$stmt->execute();
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест</title>
    <link rel="stylesheet" href="code/card.css">
    <script src="test.js" defer></script>
</head>
<body>
</head>
<header class="headerCardPage" > 
<h1>Выбери правильный ответ</h1> 
<img class="close-btn" id="close-btn1" src="Image/closeCardPage.svg" onclick="closeBtn1()">   
 </header>
    <div class="test-container">    
        <form id="test-form">
            <div id="questions-container" class="questions-container" >
            <?php foreach ($cards as $index => $card): ?>
    <div class="question" data-correct="<?php echo htmlspecialchars($card['correct_answer']); ?>">
        <p><?php echo ($index + 1) . '. ' . htmlspecialchars($card['question']); ?></p>
        <input type="radio" name="q<?php echo $index; ?>" value="<?php echo htmlspecialchars($card['correct_answer']); ?>"> <?php echo htmlspecialchars($card['correct_answer']); ?><br>
        <input type="radio" name="q<?php echo $index; ?>" value="<?php echo htmlspecialchars($card['wrong_answer1']); ?>"> <?php echo htmlspecialchars($card['wrong_answer1']); ?><br>
        <input type="radio" name="q<?php echo $index; ?>" value="<?php echo htmlspecialchars($card['wrong_answer2']); ?>"> <?php echo htmlspecialchars($card['wrong_answer2']); ?><br>
        <input type="radio" name="q<?php echo $index; ?>" value="<?php echo htmlspecialchars($card['wrong_answer3']); ?>"> <?php echo htmlspecialchars($card['wrong_answer3']); ?><br>
    </div>
<?php endforeach; ?>
            </div>
            <button class="checkBtn"  type="submit">Проверить ответы</button>
        </form>
        <div id="result" style="display: none;"></div>
    </div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p id="modal-result"></p>
        <button class="goTestcss"  onclick="window.location.href='test.html'">Перейти к тестам</button>
    </div>
</div>

    <script>
       document.getElementById('test-form').addEventListener('submit', function(event) {
        event.preventDefault();
        let score = 0;
        const totalQuestions = <?php echo count($cards); ?>;

        for (let i = 0; i < totalQuestions; i++) {
            const selectedAnswer = document.querySelector(`input[name="q${i}"]:checked`);
            const correctAnswer = document.querySelector(`.question:nth-child(${i + 1})`).getAttribute('data-correct');
            if (selectedAnswer && selectedAnswer.value === correctAnswer) {
                score++;
            }
        }

        // Отображение результата в модальном окне
        const modalResult = document.getElementById('modal-result');
        modalResult.innerHTML = `Вы набрали ${score} из ${totalQuestions} баллов.`;
        
        const modal = document.getElementById('myModal');
        modal.style.display = "block";
    });

    // Функция для закрытия модального окна
    function closeModal() {
        const modal = document.getElementById('myModal');
        modal.style.display = "none";
    }

    // Закрытие модального окна при клике вне его
    window.onclick = function(event) {
        const modal = document.getElementById('myModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
</body>
</html>