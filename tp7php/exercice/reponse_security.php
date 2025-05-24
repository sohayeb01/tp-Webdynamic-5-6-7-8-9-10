<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse sécurisée</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        // Vérification que les données ont été envoyées
        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mdp'])) {
            // Sécurisation des données avec strip_tags()
            $nom = strip_tags($_POST['nom']);
            $prenom = strip_tags($_POST['prenom']);
            $mot = strip_tags($_POST['mdp']);
            
            // Affichage sécurisé
            echo "<h1>Informations reçues (sécurisées)</h1>";
            echo "<p>Bonjour <strong>" . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . "</strong></p>";
            echo "<p>Votre mot de passe est : <em>" . htmlspecialchars($mot) . "</em></p>";
            echo "<p>Merci pour votre inscription sécurisée !</p>";
            echo "<br/><a href='index.html'>Retour au formulaire</a>";
        } else {
            echo "<h1>Erreur</h1>";
            echo "<p>Aucune donnée reçue. Veuillez remplir le formulaire.</p>";
            echo "<a href='index.html'>Retour au formulaire</a>";
        }
        ?>
    </div>
</body>
</html>