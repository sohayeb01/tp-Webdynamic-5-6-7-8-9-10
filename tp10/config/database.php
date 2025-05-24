<?php
// Database connection configuration
$host = 'localhost';
$port = 5432;
$dbname = 'tp_10';
$username = 'postgres';
$password = 'gotrinx123';

function getDbConnection() {
    global $host, $port, $dbname, $username, $password;
    
    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?> 