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

// Проверка, была ли отправлена форма регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка, совпадают ли пароли
    if ($password !== $confirm_password) {
        $error = "Пароли не совпадают.";
    } else {
        // Проверка, существует ли уже пользователь с таким логином
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Пользователь с таким логином уже существует.";
        } else {
            // Хеширование пароля перед сохранением
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Сохранение нового пользователя в базе данных
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            if ($stmt->execute()) {
                // Успешная регистрация
                $_SESSION['user_id'] = $pdo->lastInsertId(); // Сохраняем ID нового пользователя в сессии
                header("Location: index.php"); // Перенаправление на главную страницу
                exit();
            } else {
                $error = "Ошибка при регистрации. Попробуйте еще раз.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="code/style.css"> 
    <title>Регистрация</title>
    <script src="/code.js" defer></script>
</head>
<body class="register">
    <div class="main-container">
        <div class="icon-web-enter">
            <img class="icon-enter" src="Image/image-Photoroom (2).png">
            <h1 class="enterText">Создать учетную запись</h1>
            <div class="inputs_form">
                <form method="POST" action="">
                    <input name="username" class="login-input" placeholder="Логин" required>
                    <input name="password" type="password" class="pass-input" placeholder="Пароль" required>
                    <input name="confirm_password" type="password" class="pass-input" placeholder="Повторите пароль" required>
                    <button type="submit" name="register" class="registerPageButton">Создать учетную запись</button> 
                </form>
                <button class="back-button" onclick="goBack()">Назад</button>
            </div>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <p class="agreePageEnter">Продолжая, вы соглашаетесь на обработку персональных данных,<br>
                 а также принимаете условия пользовательского соглашения</p>
        </div>  
    </div>
</body>
</html>
