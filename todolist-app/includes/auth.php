<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    header('Location: login.php');
    exit();
}

if (isLoggedIn() && (basename($_SERVER['PHP_SELF']) == 'login.php' || basename($_SERVER['PHP_SELF']) == 'register.php')) {
    header('Location: index.php');
    exit();
}
?>