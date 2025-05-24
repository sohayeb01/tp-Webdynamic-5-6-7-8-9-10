<?php
session_start();
require_once __DIR__ . '/../config/auth.php';

// Handle logout
if (isset($_GET['afaire']) && $_GET['afaire'] == 'deconnexion') {
    session_destroy();
    header('Location: login.php?error=3');
    exit();
}

// Handle login validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Check if fields are empty
    if (empty($login) || empty($password)) {
        header('Location: login.php?error=1');
        exit();
    }
    
    // Check credentials
    if ($login !== USERLOGIN || $password !== USERPASS) {
        header('Location: login.php?error=2');
        exit();
    }
    
    // Valid credentials - create session
    $_SESSION['CONNECT'] = 'OK';
    $_SESSION['login'] = $login;
    $_SESSION['password'] = $password;
    
    header('Location: ../index.php');
    exit();
}

// Redirect to login if accessed directly
header('Location: login.php');
exit(); 