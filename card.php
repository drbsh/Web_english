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
    <title></title>
    <link rel="stylesheet" href="code/card.css">
    <script src="code_card.js" defer></script>
</head>
<header class="headerCardPage" > 
<h1>Выберете правильное название, которое изображено на картинке</h1> 
<img class="close-btn" id="close-btn" src="Image/closeCardPage.svg" onclick="closeBtn()">   
 </header>
<body class="cardPage" >
<div class="carousel-container">
<button class="arrow left-arrow" onclick="moveSlide(-1)">&#10094;</button>
    <div class="card-container">
        <?php foreach ($cards as $card): ?>
            <div class="card">
                <img class="card-img" src="<?php echo htmlspecialchars($card['photo_url']); ?>" alt="<?php echo htmlspecialchars($card['english_word']); ?>">
                <div class="card-text-container">
                    <p ><?php echo htmlspecialchars($card['english_word']); ?></p>
                    <p class="hidden-text"><?php echo htmlspecialchars($card['english_word']); ?></p></div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="arrow right-arrow" onclick="moveSlide(1)">&#10095;</button>
    </div>
</body>
</html>
