<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse du formulaire - Méthode 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mdp'])): ?>
            <h1>Bonjour, <?php echo $_POST['prenom'] ?> <?php echo $_POST['nom'] ?></h1>
            <h2>Votre mot de passe est: <?php echo $_POST['mdp'] ?></h2>
            <p>Merci pour votre inscription !</p>
            <a href="index.html">Retour au formulaire</a>
        <?php else: ?>
            <h1>Erreur</h1>
            <p>Aucune donnée reçue. Veuillez remplir le formulaire.</p>
            <a href="index.html">Retour au formulaire</a>
        <?php endif; ?>
    </div>
</body>
</html>