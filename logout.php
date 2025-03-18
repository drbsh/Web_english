<?php
session_start(); // Начинаем сессию
session_unset(); // Удаляем все переменные сессии
session_destroy(); // Уничтожаем сессию
header("Location: index.html"); // Перенаправление на главную страницу
exit();
?>