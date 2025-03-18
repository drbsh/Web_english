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

// Проверка, была ли отправлена форма восстановления пароля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка, совпадают ли пароли
    if ($new_password !== $confirm_password) {
        $error = "Пароли не совпадают.";
    } else {
        // Проверка, существует ли пользователь с таким логином
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Хеширование нового пароля перед сохранением
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Обновление пароля в базе данных
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            if ($stmt->execute()) {
                // Успешное обновление пароля
                $_SESSION['user_id'] = $username; // Сохраняем логин или ID пользователя в сессии
                header("Location: index.php"); // Перенаправление на главную страницу
                exit();
            } else {
                $error = "Ошибка при обновлении пароля. Попробуйте еще раз.";
            }
        } else {
            $error = "Пользователь с таким логином не найден.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
    <link rel="stylesheet" href="code/style.css">
    <script src="/code.js" defer></script>
</head>
<body class="forgotPass">
    <div class="main-container">
        <div class="icon-web-enter">
            <img class="icon-enter" src="Image/image-Photoroom (2).png">
            <h1 class="enterText">Восстановить пароль</h1>
            <div class="inputs_form">
                <form method="POST" action="">
                    <input name="username" class="login-input" placeholder="Логин" required>
                    <input name="new_password" type="password" class="pass-input" placeholder="Новый пароль" required>
                    <input name="confirm_password" type="password" class="pass-input" placeholder="Повторите пароль" required>
                    <button type="submit" name="reset_password" class="registerPageButton">Восстановить пароль</button> 
                </form>
                <button class="back-button" onclick="goBack2()">Назад</button>
            </div>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
        </div> 
    </div>
</body>
</html>
