<?php
// Database connection configuration
$url = "postgresql://postgres:wWKchzTkFqwiVokqOpFhtRRHEuAPGykL@trolley.proxy.rlwy.net:54475/railway";
$dbparts = parse_url($url);
$host = $dbparts['host'];
$port = $dbparts['port'];
$dbname = ltrim($dbparts['path'], '/');
$username = $dbparts['user'];
$password = $dbparts['pass'];
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