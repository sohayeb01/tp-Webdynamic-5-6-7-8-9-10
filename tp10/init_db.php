<?php
// Start output buffering to prevent header issues
ob_start();

// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load database configuration
require_once __DIR__ . '/config/database.php';

// Get database connection
try {
    $pdo = getDbConnection();
    echo "<h2>✅ Connected to database successfully!</h2>";
} catch (Exception $e) {
    die("<h2>❌ Connection failed: " . $e->getMessage() . "</h2>");
}

// Read and execute SQL file
try {
    echo "<h3>Initializing database...</h3>";
    
    $sql = file_get_contents(__DIR__ . '/supabase_init.sql');
    
    // Split SQL file into individual statements
    $statements = array_filter(
        array_map('trim', 
            explode(';', $sql)
        )
    );
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
            echo "<p>Executed: " . htmlspecialchars(substr($statement, 0, 50)) . "...</p>";
        }
    }
    
    echo "<h2>✅ Database initialized successfully!</h2>";
    
    // Check if tables were created
    $tables = ['exercice', 'guerrier'];
    echo "<h3>Verifying tables:</h3>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "<li>Table '$table': ✅ Exists with $count records</li>";
        } catch (PDOException $e) {
            echo "<li>Table '$table': ❌ Error: " . $e->getMessage() . "</li>";
        }
    }
    
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Database initialization failed: " . $e->getMessage() . "</h2>";
}

// Add a button to go to the homepage
echo '<p><a href="index.php" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Go to Homepage</a></p>';

// Flush output buffer
ob_end_flush();
?> 