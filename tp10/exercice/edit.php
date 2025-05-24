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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    
    if (!empty($titre) && !empty($auteur)) {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare("UPDATE exercice SET titre = ?, auteur = ? WHERE id = ?");
            $result = $stmt->execute([$titre, $auteur, $id]);
            
            if ($result) {
                header('Location: list.php?msg=update_success');
                exit();
            } else {
                header('Location: list.php?msg=update_error');
                exit();
            }
        } catch (PDOException $e) {
            $message = '<div class="error">Erreur lors de la modification: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un exercice</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Modifier un exercice</h1>
    
    <?php echo $message; ?>
    
    <?php if ($exercice): ?>
        <form method="post">
            <div class="form-group">
                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($exercice['titre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="auteur">Auteur:</label>
                <input type="text" id="auteur" name="auteur" value="<?php echo htmlspecialchars($exercice['auteur']); ?>" required>
            </div>
            <input type="submit" value="Modifier">
            <a href="list.php" class="cancel">Annuler</a>
        </form>
    <?php else: ?>
        <p>Exercice introuvable.</p>
        <a href="list.php">Retour à la liste</a>
    <?php endif; ?>
</body>
</html> 