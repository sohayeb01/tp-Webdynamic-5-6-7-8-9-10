<?php
session_start();

// Check if this is a database initialization request
if (isset($_GET['init_db']) && $_GET['init_db'] === 'true') {
    header('Location: init_db.php');
    exit();
}

// Check if user is connected
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK') {
    header('Location: auth/login.php');
    exit();
}

$username = $_SESSION['login'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="welcome">
        <h2>Hello <?php echo htmlspecialchars($username); ?></h2>
        <p>Vous êtes connecté avec succès!</p>
    </div>
    
    <div class="navigation">
        <a href="exercice/list.php">Gestion des Exercices</a>
        <a href="game/guerrier.php">Jeu de Combat</a>
    </div>
    
    <p style="margin-top: 30px;">
        <a href="auth/validation.php?afaire=deconnexion" class="logout-link">Déconnexion</a>
    </p>
    
    <?php if ($_SESSION['login'] === 'admin'): ?>
    <p style="margin-top: 20px;">
        <a href="?init_db=true" class="admin-link" onclick="return confirm('Attention: Cette action va réinitialiser la base de données. Continuer?');">Initialiser la base de données</a>
    </p>
    <?php endif; ?>
</body>
</html>
