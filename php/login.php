<?php
session_start();
require_once 'users.php';
require_once 'logger.php';

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

if ($login === '' || $password === '') {
    writeLog($login, 'FAIL_LOGIN_EMPTY');
    header('Location: ../index.html?error=empty');
    exit;
}

if (isset($users[$login]) && password_verify($password, $users[$login]['password_hash'])) {
    $_SESSION['user_id'] = $users[$login]['id'];
    $_SESSION['login'] = $login;
    writeLog($login, 'SUCCESS_LOGIN');
    header('Location: ../cabinet.php');
    exit;
} else {
    writeLog($login, 'FAIL_LOGIN');
    header('Location: ../index.html?error=invalid');
    exit;
}