<?php
// Start output buffering to prevent header issues
ob_start();

// Start session
session_start();

require_once __DIR__ . '/../config/auth.php';

$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case '1':
            $error_message = 'Veuillez saisir un login et un mot de passe';
            break;
        case '2':
            $error_message = 'Erreur de login/mot de passe';
            break;
        case '3':
            $error_message = 'Vous avez été déconnecté du service';
            break;
        case '4':
            $error_message = 'Base de données non initialisée. Veuillez vous connecter en tant qu\'admin pour initialiser la base de données.';
            break;
    }
}

// Check if we need to initialize the database
$init_db = isset($_GET['init_db']) && $_GET['init_db'] === 'true';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .note {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 10px;
            margin: 15px 0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h2>Connexion</h2>
    
    <?php if ($error_message): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    
    <?php if ($init_db): ?>
        <div class="note">
            <p><strong>Note:</strong> Vous allez initialiser la base de données.</p>
            <p>Utilisez les identifiants d'administrateur.</p>
        </div>
    <?php endif; ?>
    
    <form action="validation.php" method="post">
        <div class="form-group">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <?php if ($init_db): ?>
            <input type="hidden" name="init_db" value="true">
        <?php endif; ?>
        
        <input type="submit" value="Se connecter">
    </form>
    
    <?php if (!$init_db): ?>
        <p style="margin-top: 20px; text-align: center;">
            <a href="?init_db=true">Initialiser la base de données</a>
        </p>
    <?php endif; ?>
</body>
</html> 