<?php
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
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Connexion</h2>
    
    <?php if ($error_message): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
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
        
        <input type="submit" value="Se connecter">
    </form>
</body>
</html> 