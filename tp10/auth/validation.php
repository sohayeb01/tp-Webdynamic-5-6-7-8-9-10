<?php
// Start output buffering to prevent header issues
ob_start();

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
    
    // Check credentials against valid users
    $authenticated = false;
    $userRole = '';
    
    foreach ($VALID_USERS as $user) {
        if ($login === $user['login'] && $password === $user['password']) {
            $authenticated = true;
            $userRole = $user['role'];
            break;
        }
    }
    
    if (!$authenticated) {
        header('Location: login.php?error=2');
        exit();
    }
    
    // Valid credentials - create session
    $_SESSION['CONNECT'] = 'OK';
    $_SESSION['login'] = $login;
    $_SESSION['role'] = $userRole;
    
    // Redirect to initialization page on first login if admin
    if ($userRole === 'admin' && isset($_POST['init_db']) && $_POST['init_db'] === 'true') {
        header('Location: ../init_db.php');
        exit();
    }
    
    header('Location: ../index.php');
    exit();
}

// Redirect to login if accessed directly
header('Location: login.php');
exit();

// End output buffering
ob_end_flush(); 