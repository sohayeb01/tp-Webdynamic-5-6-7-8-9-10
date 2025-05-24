<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is connected
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK') {
    header('Location: ../auth/login.php');
    exit();
}

$id = $_GET['id'] ?? 0;
$exercice = null;
$message = '';

// Fetch exercise data
if ($id) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("SELECT * FROM exercice WHERE id = ?");
        $stmt->execute([$id]);
        $exercice = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$exercice) {
            header('Location: list.php');
            exit();
        }
    } catch (PDOException $e) {
        $message = '<div class="error">Erreur lors de la récupération: ' . $e->getMessage() . '</div>';
    }
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("DELETE FROM exercice WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        if ($result) {
            header('Location: list.php?msg=delete_success');
            exit();
        } else {
            header('Location: list.php?msg=delete_error');
            exit();
        }
    } catch (PDOException $e) {
        $message = '<div class="error">Erreur lors de la suppression: ' . $e->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un exercice</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Supprimer un exercice</h1>
    
    <?php echo $message; ?>
    
    <?php if ($exercice): ?>
        <div class="warning">
            <strong>Attention!</strong> Vous êtes sur le point de supprimer définitivement cet exercice.
        </div>
        
        <div class="exercise-info">
            <h3>Informations de l'exercice:</h3>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($exercice['id']); ?></p>
            <p><strong>Titre:</strong> <?php echo htmlspecialchars($exercice['titre']); ?></p>
            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($exercice['auteur']); ?></p>
            <p><strong>Date de création:</strong> <?php echo htmlspecialchars($exercice['date_creation']); ?></p>
        </div>
        
        <form method="post">
            <input type="submit" name="confirm_delete" value="Confirmer la suppression">
            <a href="list.php" class="cancel">Annuler</a>
        </form>
    <?php else: ?>
        <p>Exercice introuvable.</p>
        <a href="list.php">Retour à la liste</a>
    <?php endif; ?>
</body>
</html> 