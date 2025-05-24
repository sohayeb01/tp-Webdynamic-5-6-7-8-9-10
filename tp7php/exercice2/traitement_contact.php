<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message envoyé</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        // Fonction de validation et sécurisation
        function securiser($donnee) {
            return htmlspecialchars(strip_tags(trim($donnee)));
        }
        
        // Vérification des données obligatoires
        if (isset($_POST['nom']) && isset($_POST['prenom']) && 
            isset($_POST['email']) && isset($_POST['sujet']) && 
            isset($_POST['message'])) {
            
            // Sécurisation de toutes les données
            $civilite = isset($_POST['civilite']) ? securiser($_POST['civilite']) : '';
            $nom = securiser($_POST['nom']);
            $prenom = securiser($_POST['prenom']);
            $email = securiser($_POST['email']);
            $telephone = isset($_POST['telephone']) ? securiser($_POST['telephone']) : 'Non renseigné';
            $sujet = securiser($_POST['sujet']);
            $message = securiser($_POST['message']);
            $newsletter = isset($_POST['newsletter']) ? 'Oui' : 'Non';
            
            // Validation de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<h1>❌ Erreur</h1>";
                echo "<p>L'adresse email n'est pas valide.</p>";
                echo "<a href='contact.html'>← Retour au formulaire</a>";
            } else {
                // Affichage du récapitulatif
                echo "<h1>✅ Message envoyé avec succès !</h1>";
                echo "<div style='background-color: #e8f5e8; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
                echo "<h2>📋 Récapitulatif de votre message :</h2>";
                echo "<p><strong>Nom complet :</strong> " . $civilite . " " . $prenom . " " . $nom . "</p>";
                echo "<p><strong>Email :</strong> " . $email . "</p>";
                echo "<p><strong>Téléphone :</strong> " . $telephone . "</p>";
                echo "<p><strong>Sujet :</strong> " . $sujet . "</p>";
                echo "<p><strong>Message :</strong></p>";
                echo "<div style='background-color: white; padding: 10px; border-left: 3px solid #007bff;'>";
                echo nl2br($message);
                echo "</div>";
                echo "<p><strong>Newsletter :</strong> " . $newsletter . "</p>";
                echo "</div>";
                
                echo "<p style='color: #28a745;'>🎉 Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.</p>";
                echo "<p><a href='contact.html'>← Envoyer un autre message</a></p>";
                
                // Simulation d'envoi d'email (commenté)
                /*
                $to = "admin@monsite.com";
                $subject = "Nouveau message: " . $sujet;
                $body = "Nouveau message reçu:\n\n";
                $body .= "Nom: " . $civilite . " " . $prenom . " " . $nom . "\n";
                $body .= "Email: " . $email . "\n";
                $body .= "Téléphone: " . $telephone . "\n";
                $body .= "Sujet: " . $sujet . "\n";
                $body .= "Message:\n" . $message . "\n";
                $body .= "Newsletter: " . $newsletter . "\n";
                
                mail($to, $subject, $body);
                */
            }
        } else {
            echo "<h1>❌ Erreur</h1>";
            echo "<p>Données manquantes. Veuillez remplir tous les champs obligatoires.</p>";
            echo "<p><a href='contact.html'>← Retour au formulaire</a></p>";
        }
        ?>
    </div>
</body>
</html>