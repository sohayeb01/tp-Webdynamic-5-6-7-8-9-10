<?php
session_start();

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
</body>
</html>
