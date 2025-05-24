<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is connected
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK') {
    header('Location: ../auth/login.php');
    exit();
}

$message = '';
$pdo = getDbConnection();

// Handle new warrior creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_warrior'])) {
    $nom = trim($_POST['nom'] ?? '');
    
    if (!empty($nom)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO guerrier (nom, degats) VALUES (?, 0)");
            $stmt->execute([$nom]);
            $message = '<div class="success">Guerrier "' . htmlspecialchars($nom) . '" créé avec succès!</div>';
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'duplicate key') !== false) {
                $message = '<div class="error">Ce nom de guerrier existe déjà!</div>';
            } else {
                $message = '<div class="error">Erreur lors de la création: ' . $e->getMessage() . '</div>';
            }
        }
    }
}

// Handle combat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['combat'])) {
    $attaquant_id = $_POST['attaquant'] ?? 0;
    $victime_id = $_POST['victime'] ?? 0;
    
    if ($attaquant_id && $victime_id && $attaquant_id != $victime_id) {
        try {
            // Get victim's current damage
            $stmt = $pdo->prepare("SELECT nom, degats FROM guerrier WHERE id = ?");
            $stmt->execute([$victime_id]);
            $victime = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stmt = $pdo->prepare("SELECT nom FROM guerrier WHERE id = ?");
            $stmt->execute([$attaquant_id]);
            $attaquant = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($victime && $attaquant) {
                $nouveaux_degats = $victime['degats'] + 5;
                
                if ($nouveaux_degats >= 100) {
                    // Warrior dies - remove from database
                    $stmt = $pdo->prepare("DELETE FROM guerrier WHERE id = ?");
                    $stmt->execute([$victime_id]);
                    $message = '<div class="success">' . htmlspecialchars($attaquant['nom']) . ' a tué ' . htmlspecialchars($victime['nom']) . '! Le guerrier a été supprimé.</div>';
                } else {
                    // Update damage
                    $stmt = $pdo->prepare("UPDATE guerrier SET degats = ? WHERE id = ?");
                    $stmt->execute([$nouveaux_degats, $victime_id]);
                    $message = '<div class="success">' . htmlspecialchars($attaquant['nom']) . ' a frappé ' . htmlspecialchars($victime['nom']) . '! Dégâts: ' . $nouveaux_degats . '/100</div>';
                }
            }
        } catch (PDOException $e) {
            $message = '<div class="error">Erreur lors du combat: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch all warriors
try {
    $stmt = $pdo->query("SELECT * FROM guerrier ORDER BY degats ASC, nom ASC");
    $guerriers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Check if the error is about undefined table
    if (strpos($e->getMessage(), 'relation "guerrier" does not exist') !== false) {
        // Database not initialized, redirect to login with init_db option
        header('Location: ../auth/login.php?error=4&init_db=true');
        exit();
    }
    
    $message = '<div class="error">Erreur lors de la récupération: ' . $e->getMessage() . '</div>';
    $guerriers = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Combat</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="navigation">
        <a href="../index.php">Accueil</a>
        <a href="../exercice/list.php">Gestion des Exercices</a>
    </div>

    <h1>🗡️ Jeu de Combat</h1>
    
    <?php echo $message; ?>
    
    <div class="section">
        <h2>Créer un nouveau guerrier</h2>
        <form method="post">
            <div class="form-group">
                <label for="nom">Nom du guerrier:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <input type="submit" name="create_warrior" value="Créer">
        </form>
    </div>
    
    <?php if (count($guerriers) >= 2): ?>
        <div class="section">
            <h2>Combat</h2>
            <form method="post">
                <div class="form-group">
                    <label for="attaquant">Attaquant:</label>
                    <select id="attaquant" name="attaquant" required>
                        <option value="">Choisir un attaquant</option>
                        <?php foreach ($guerriers as $guerrier): ?>
                            <option value="<?php echo $guerrier['id']; ?>">
                                <?php echo htmlspecialchars($guerrier['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="victime">Victime:</label>
                    <select id="victime" name="victime" required>
                        <option value="">Choisir une victime</option>
                        <?php foreach ($guerriers as $guerrier): ?>
                            <option value="<?php echo $guerrier['id']; ?>">
                                <?php echo htmlspecialchars($guerrier['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <input type="submit" name="combat" value="Attaquer!" class="combat-btn">
            </form>
        </div>
    <?php endif; ?>
    
    <h2>Liste des guerriers</h2>
    <?php if (empty($guerriers)): ?>
        <p>Aucun guerrier trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Points de vie</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guerriers as $guerrier): ?>
                <tr>
                    <td><?php echo htmlspecialchars($guerrier['nom']); ?></td>
                    <td>
                        <div class="health-bar">
                            <div class="health-fill <?php 
                                echo $guerrier['degats'] >= 75 ? 'danger' : 
                                    ($guerrier['degats'] >= 50 ? 'warning' : ''); 
                                ?>" 
                                style="width: <?php echo (100 - $guerrier['degats']); ?>%">
                            </div>
                        </div>
                    </td>
                    <td><?php echo (100 - $guerrier['degats']); ?>/100 PV</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html> 