<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['loggedIn' => false]);
} else {
    echo json_encode(['loggedIn' => true]);
}
?>
