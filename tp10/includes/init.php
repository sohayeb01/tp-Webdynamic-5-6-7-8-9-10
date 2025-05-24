<?php
require_once __DIR__ . '/../config/database.php';

try {
    // Connect to PostgreSQL
    $pdo = getDbConnection();
    
    // Create tables
    $sql = "
    -- Create exercice table for CRUD operations
    CREATE TABLE IF NOT EXISTS exercice (
        id SERIAL PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        auteur VARCHAR(255) NOT NULL,
        date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- Create guerrier table for combat game
    CREATE TABLE IF NOT EXISTS guerrier (
        id SERIAL PRIMARY KEY,
        nom VARCHAR(100) UNIQUE NOT NULL,
        degats INTEGER DEFAULT 0 CHECK (degats >= 0 AND degats <= 100)
    );
    
    -- Insert sample data
    INSERT INTO exercice (titre, auteur) VALUES 
    ('Introduction à PHP', 'Jean Dupont'),
    ('Bases de données', 'Marie Martin'),
    ('Sessions et cookies', 'Pierre Durand')
    ON CONFLICT DO NOTHING;
    
    INSERT INTO guerrier (nom, degats) VALUES 
    ('Aragorn', 15),
    ('Legolas', 25),
    ('Gimli', 35)
    ON CONFLICT (nom) DO NOTHING;
    ";
    
    $pdo->exec($sql);
    echo "Database initialized successfully!<br>";
    echo "<a href='../auth/login.php'>Go to Login</a> | ";
    echo "<a href='../exercice/list.php'>Go to Exercises</a> | ";
    echo "<a href='../game/guerrier.php'>Go to Combat Game</a>";
    
} catch(PDOException $e) {
    die("Database initialization failed: " . $e->getMessage());
}
?> 