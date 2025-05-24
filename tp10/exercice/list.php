<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is connected
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK') {
    header('Location: ../auth/login.php');
    exit();
}

$message = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'add_success':
            $message = '<div class="success">Exercice ajouté avec succès!</div>';
            break;
        case 'update_success':
            $message = '<div class="success">Exercice modifié avec succès!</div>';
            break;
        case 'update_error':
            $message = '<div class="error">Erreur lors de la modification!</div>';
            break;
        case 'delete_success':
            $message = '<div class="success">Exercice supprimé avec succès!</div>';
            break;
        case 'delete_error':
            $message = '<div class="error">Erreur lors de la suppression!</div>';
            break;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    
    if (!empty($titre) && !empty($auteur)) {
        try {
            $pdo = getDbConnection();
            $stmt = $pdo->prepare("INSERT INTO exercice (titre, auteur) VALUES (?, ?)");
            $stmt->execute([$titre, $auteur]);
            header('Location: list.php?msg=add_success');
            exit();
        } catch (PDOException $e) {
            $message = '<div class="error">Erreur lors de l\'ajout: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch all exercises
try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM exercice ORDER BY id DESC");
    $exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Check if the error is about undefined table
    if (strpos($e->getMessage(), 'relation "exercice" does not exist') !== false) {
        // Database not initialized, redirect to login with init_db option
        header('Location: ../auth/login.php?error=4&init_db=true');
        exit();
    }
    
    $message = '<div class="error">Erreur lors de la récupération: ' . $e->getMessage() . '</div>';
    $exercices = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Exercices</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="navigation">
        <a href="../index.php">Accueil</a>
        <a href="../game/guerrier.php">Jeu de Combat</a>
    </div>

    <h1>Gestion des Exercices</h1>
    
    <?php echo $message; ?>
    
    <div class="section">
        <h2>Ajouter un nouveau exercice</h2>
        <form method="post">
            <div class="form-group">
                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div class="form-group">
                <label for="auteur">Auteur:</label>
                <input type="text" id="auteur" name="auteur" required>
            </div>
            <input type="submit" value="Ajouter">
        </form>
    </div>
    
    <h2>Liste des exercices</h2>
    <?php if (empty($exercices)): ?>
        <p>Aucun exercice trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exercices as $exercice): ?>
                <tr>
                    <td><?php echo htmlspecialchars($exercice['id']); ?></td>
                    <td><?php echo htmlspecialchars($exercice['titre']); ?></td>
                    <td><?php echo htmlspecialchars($exercice['auteur']); ?></td>
                    <td><?php echo htmlspecialchars($exercice['date_creation']); ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $exercice['id']; ?>" class="edit">Modifier</a>
                        <a href="delete.php?id=<?php echo $exercice['id']; ?>" class="delete">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html> 