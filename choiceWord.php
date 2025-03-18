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
$table_names = [
    'animal_card' => 'animal_card',
    'fruit_card' => 'fruit_card',
    'vegetable_card' => 'veg_card', 
    'food_card' => 'food_card',
    'furn_card' => 'furn_card',
    'cloth_card' => 'cloth_card',
];
// Получаем тип карточек из URL
$card_type = isset($_GET['type']) ? $_GET['type'] : 'animal_card';

if (array_key_exists($card_type, $table_names)) {
    $table_name = $table_names[$card_type];
} else {
    $table_name = 'animal_card'; 
}

$stmt = $pdo->prepare("SELECT * FROM $table_name");
$stmt->execute();
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сопоставление слов</title>
    <link rel="stylesheet" href="code/card.css">
    <script src="choiceWord.js" defer></script>
</head>
<body>
    <header class="headerCardPage" > 
<h1>Сопоставьте английские слова с русскими</h1> 
<img class="close-btn" id="close-btn" src="Image/closeCardPage.svg" onclick="closeBtn()">   
 </header>
    <div class="choiceWord-container">
    <div class="english-cards">
    <h2>Английские слова</h2>
    <?php foreach ($cards as $card): ?>
        <div class="choiceWord" data-id="<?php echo htmlspecialchars($card['id']); ?>" data-english="<?php echo htmlspecialchars($card['english_word']); ?>">
            <?php echo htmlspecialchars($card['english_word']); ?>
        </div>
    <?php endforeach; ?>
</div>
<div class="russian-cards">
    <h2>Русские слова</h2>
    <?php foreach ($cards as $card): ?>
        <div class="choiceWord" data-id="<?php echo htmlspecialchars($card['id']); ?>" data-russian="<?php echo htmlspecialchars($card['russian_word']); ?>">
            <?php echo htmlspecialchars($card['russian_word']); ?>
        </div>
    <?php endforeach; ?>
</div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-result"></p>
            <button class="goTestcss" onclick="continueTest()">Продолжить</button>
            <button class="goTestcss" onclick="retryTest()">Пройти еще раз</button>
        </div>
    </div>
</body>
</html>