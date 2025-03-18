<?php
session_start(); // Начать сессию

// Настройки подключения к базе данных
$host = 'localhost';
$dbname = 'web_english';
$user = 'postgres';
$password = 'drbsh';

try {
    // Подключение к базе данных
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Проверка, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Пароль, введенный пользователем

    // Подготовка и выполнение SQL-запроса
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Проверка, существует ли пользователь и совпадает ли пароль
    if ($stmt->rowCount() > 0) {
        // Получаем пользователя
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Сравниваем пароли с использованием password_verify
        if (password_verify($password, $user['password'])) { // Используем хеширование
            // Успешный вход
            $_SESSION['user_id'] = $user['id']; // Сохраняем ID пользователя в сессии
            header("Location: index.php"); // Перенаправление на главную страницу
            exit();
        } else {
            $error = "Неверный логин или пароль.";
        }
    } else {
        $error = "Неверный логин или пароль.";
    }
}
?>