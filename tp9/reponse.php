<?php
// Variables for form processing
$showForm = false;
$errors = array();
$formData = array();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Security function to clean input
    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data); // Remove HTML and PHP tags for security
        return $data;
    }
    
    // Process form data with security
    $formData = array(
        'prenom' => isset($_POST['prenom']) ? cleanInput($_POST['prenom']) : '',
        'nom' => isset($_POST['nom']) ? cleanInput($_POST['nom']) : '',
        'email' => isset($_POST['email']) ? cleanInput($_POST['email']) : '',
        'telephone' => isset($_POST['telephone']) ? cleanInput($_POST['telephone']) : '',
        'age' => isset($_POST['age']) ? cleanInput($_POST['age']) : '',
        'genre' => isset($_POST['genre']) ? cleanInput($_POST['genre']) : '',
        'pays' => isset($_POST['pays']) ? cleanInput($_POST['pays']) : '',
        'message' => isset($_POST['message']) ? cleanInput($_POST['message']) : '',
        'newsletter' => isset($_POST['newsletter']) ? 'Oui' : 'Non',
        'conditions' => isset($_POST['conditions']) ? 'Acceptées' : 'Non acceptées'
    );
    
    // Basic validation
    if (empty($formData['prenom'])) $errors[] = "Le prénom est requis";
    if (empty($formData['nom'])) $errors[] = "Le nom est requis";
    if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Un email valide est requis";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse - Formulaire de Contact</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <span class="icon">📝</span>
                Réponse du Formulaire
            </h1>
        </div>

        <!-- PHP RESPONSE -->
        <div class="response-container">
            <?php if (empty($errors)): ?>
                <div class="success-message">
                    <span class="icon">✅</span> Formulaire envoyé avec succès !
                </div>
                
                <div class="user-info">
                    <h3 style="margin-bottom: 20px; color: #333;">
                        <span class="icon">📋</span> Informations reçues :
                    </h3>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">👤</span> Nom complet :
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($formData['prenom'] . ' ' . $formData['nom']); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">📧</span> Email :
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($formData['email']); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($formData['telephone'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">📱</span> Téléphone :
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($formData['telephone']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($formData['age'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">🎂</span> Âge :
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($formData['age']); ?> ans
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($formData['genre'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">⚧</span> Genre :
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($formData['genre']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($formData['pays'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">🌍</span> Pays :
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($formData['pays']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($formData['message'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">💬</span> Message :
                        </div>
                        <div class="info-value">
                            <?php echo nl2br(htmlspecialchars($formData['message'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">📬</span> Newsletter :
                        </div>
                        <div class="info-value">
                            <?php echo $formData['newsletter']; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <span class="icon">📋</span> Conditions :
                        </div>
                        <div class="info-value">
                            <?php echo $formData['conditions']; ?>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <div style="background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                    <h3><span class="icon">❌</span> Erreurs détectées :</h3>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <button onclick="window.location.href='index.html'" class="back-btn">
                <span class="icon">🔄</span> Nouveau formulaire
            </button>
        </div>
    </div>
</body>
</html>
