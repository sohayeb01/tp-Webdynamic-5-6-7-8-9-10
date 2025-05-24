<?php
// =============================================================================
// EXERCISE 3: COMBAT GAME - guerrier_game.php
// =============================================================================

require_once 'db_connect.php';

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
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; }
        .section { background-color: #f8f9fa; padding: 20px; border-radius: 4px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        input[type="submit"] { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .combat-btn { background-color: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .health-bar { width: 100px; height: 20px; background-color: #e9ecef; border-radius: 10px; overflow: hidden; }
        .health-fill { height: 100%; background-color: #28a745; transition: width 0.3s; }
        .health-fill.warning { background-color: #ffc107; }
        .health-fill.danger { background-color: #dc3545; }
        .success { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .navigation { margin-bottom: 20px; }
        .navigation a { margin-right: 15px; padding: 8px 16px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="navigation">
        <a href="accueil.php">Accueil</a>
        <a href="exercice_list.php">Gestion des Exercices</a>
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
            <input type="submit" name="create_warrior" value="Créer un guerrier">
        </form>
    </div>
    
    <?php if (count($guerriers) >= 2): ?>
    <div class="section">
        <h2>Combat</h2>
        <form method="post">
            <div class="form-group">
                <label for="attaquant">Attaquant:</label>
                <select id="attaquant" name="attaquant" required>
                    <option value="">Choisir un guerrier</option>
                    <?php foreach ($guerriers as $guerrier): ?>
                        <option value="<?php echo $guerrier['id']; ?>">
                            <?php echo htmlspecialchars($guerrier['nom']); ?> (<?php echo $guerrier['degats']; ?>/100 dégâts)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="victime">Victime:</label>
                <select id="victime" name="victime" required>
                    <option value="">Choisir un guerrier</option>
                    <?php foreach ($guerriers as $guerrier): ?>
                        <option value="<?php echo $guerrier['id']; ?>">
                            <?php echo htmlspecialchars($guerrier['nom']); ?> (<?php echo $guerrier['degats']; ?>/100 dégâts)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="submit" name="combat" value="⚔️ Combattre!" class="combat-btn">
        </form>
    </div>
    <?php endif; ?>
    
    <div class="section">
        <h2>Liste des guerriers</h2>
        <?php if (empty($guerriers)): ?>
            <p>Aucun guerrier trouvé. Créez votre premier guerrier!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Dégâts</th>
                        <th>Barre de vie</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guerriers as $guerrier): ?>
                        <?php 
                        $health_percent = 100 - $guerrier['degats'];
                        $health_class = '';
                        if ($health_percent <= 25) {
                            $health_class = 'danger';
                        } elseif ($health_percent <= 50) {
                            $health_class = 'warning';
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($guerrier['nom']); ?></td>
                            <td><?php echo $guerrier['degats']; ?>/100</td>
                            <td>
                                <div class="health-bar">
                                    <div class="health-fill <?php echo $health_class; ?>" 
                                         style="width: <?php echo $health_percent; ?>%"></div>
                                </div>
                            </td>
                            <td>
                                <?php if ($guerrier['degats'] == 0): ?>
                                    <span style="color: #28a745;">✨ Parfait état</span>
                                <?php elseif ($guerrier['degats'] < 50): ?>
                                    <span style="color: #ffc107;">⚠️ Blessé</span>
                                <?php elseif ($guerrier['degats'] < 90): ?>
                                    <span style="color: #fd7e14;">🩸 Gravement blessé</span>
                                <?php else: ?>
                                    <span style="color: #dc3545;">💀 Mourant</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <div class="section">
        <h3>📋 Règles du jeu</h3>
        <ul>
            <li>Chaque guerrier commence avec 0 dégât</li>
            <li>Chaque coup inflige 5 points de dégâts</li>
            <li>Un guerrier meurt à 100 points de dégâts</li>
            <li>Les guerriers morts sont automatiquement supprimés</li>
            <li>Vous ne pouvez pas attaquer vous-même</li>
        </ul>
    </div>
    
    <script>
        // Prevent self-attack
        const attaquantSelect = document.getElementById('attaquant');
        const victimeSelect = document.getElementById('victime');
        
        function updateSelectOptions() {
            const attaquantValue = attaquantSelect.value;
            const victimeValue = victimeSelect.value;
            
            // Reset all options
            Array.from(attaquantSelect.options).forEach(option => {
                option.disabled = false;
            });
            Array.from(victimeSelect.options).forEach(option => {
                option.disabled = false;
            });
            
            // Disable selected options in the other select
            if (attaquantValue) {
                Array.from(victimeSelect.options).forEach(option => {
                    if (option.value === attaquantValue) {
                        option.disabled = true;
                    }
                });
            }
            
            if (victimeValue) {
                Array.from(attaquantSelect.options).forEach(option => {
                    if (option.value === victimeValue) {
                        option.disabled = true;
                    }
                });
            }
        }
        
        if (attaquantSelect && victimeSelect) {
            attaquantSelect.addEventListener('change', updateSelectOptions);
            victimeSelect.addEventListener('change', updateSelectOptions);
        }
    </script>
</body>
</html>