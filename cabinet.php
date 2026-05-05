<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html?error=auth_required');
    exit;
}
$login = $_SESSION['login'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Личный кабинет</h1>
    <p>Добро пожаловать, <strong><?= htmlspecialchars($login) ?></strong>!</p>
    <p><a href="php/logout.php">Выйти</a></p>
</body>
</html>