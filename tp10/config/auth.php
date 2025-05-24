<?php
// Authentication configuration
define('USERLOGIN', 'itisme');
define('USERPASS', 'justme');

// Admin user for database management
define('ADMINLOGIN', 'admin');
define('ADMINPASS', 'admin123');

// List of valid users
$VALID_USERS = [
    ['login' => USERLOGIN, 'password' => USERPASS, 'role' => 'user'],
    ['login' => ADMINLOGIN, 'password' => ADMINPASS, 'role' => 'admin']
]; 