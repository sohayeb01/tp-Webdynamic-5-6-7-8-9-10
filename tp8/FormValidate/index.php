<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            font-family: inherit;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #74b9ff;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background: #d4edda;
            border-left: 4px solid #28a745;
            border-radius: 5px;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .message-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }
        .field-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        .field-label {
            font-weight: bold;
            color: #495057;
        }
        .field-value {
            color: #212529;
        }
        .required {
            color: #dc3545;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Formulaire de Contact</h1>
        
        <div class="info-box">
            <strong>📝 Instructions:</strong> Veuillez remplir tous les champs obligatoires marqués par un <span class="required">*</span>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Nom complet <span class="required">*</span>:</label>
                <input type="text" name="name" id="name" 
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                       placeholder="Entrez votre nom complet">
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="required">*</span>:</label>
                <input type="email" name="email" id="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="example@domain.com">
            </div>
            
            <div class="form-group">
                <label for="phone">Téléphone:</label>
                <input type="tel" name="phone" id="phone" 
                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                       placeholder="Optionnel - Numéro de téléphone">
            </div>
            
            <div class="form-group">
                <label for="subject">Sujet du message:</label>
                <input type="text" name="subject" id="subject" 
                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                       placeholder="Optionnel - Sujet du message">
            </div>
            
            <div class="form-group">
                <label for="message">Message <span class="required">*</span>:</label>
                <textarea name="message" id="message" 
                          placeholder="Écrivez votre message ici..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            </div>
            
            <button type="submit" name="submit">📤 Envoyer le message</button>
        </form>

        <?php
        function validateEmail($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
        
        function sanitizeInput($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        if (isset($_POST['submit'])) {
            $errors = [];
            $data = [];
            
            // Validate required fields
            if (empty($_POST['name'])) {
                $errors[] = "Le nom est requis";
            } else {
                $data['name'] = sanitizeInput($_POST['name']);
            }
            
            if (empty($_POST['email'])) {
                $errors[] = "L'email est requis";
            } elseif (!validateEmail($_POST['email'])) {
                $errors[] = "Format d'email invalide";
            } else {
                $data['email'] = sanitizeInput($_POST['email']);
            }
            
            if (empty($_POST['message'])) {
                $errors[] = "Le message est requis";
            } else {
                $data['message'] = sanitizeInput($_POST['message']);
            }
            
            // Optional fields
            $data['phone'] = !empty($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
            $data['subject'] = !empty($_POST['subject']) ? sanitizeInput($_POST['subject']) : 'Sans sujet';
            
            if (!empty($errors)) {
                echo "<div class='result error'>";
                echo "<h3>❌ Veuillez corriger les erreurs suivantes:</h3>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
            } else {
                // Success - Display submitted data
                echo "<div class='result'>";
                echo "<h3>✅ Votre message a été envoyé avec succès!</h3>";
                echo "<p>Merci de nous avoir contactés. Nous vous répondrons dans les plus brefs délais.</p>";
                
                echo "<div class='message-display'>";
                echo "<h4>📋 Détails du message envoyé:</h4>";
                
                echo "<div class='field-info'>";
                echo "<span class='field-label'>👤 Nom:</span>";
                echo "<span class='field-value'>" . $data['name'] . "</span>";
                echo "</div>";
                
                echo "<div class='field-info'>";
                echo "<span class='field-label'>📧 Email:</span>";
                echo "<span class='field-value'>" . $data['email'] . "</span>";
                echo "</div>";
                
                if (!empty($data['phone'])) {
                    echo "<div class='field-info'>";
                    echo "<span class='field-label'>📱 Téléphone:</span>";
                    echo "<span class='field-value'>" . $data['phone'] . "</span>";
                    echo "</div>";
                }
                
                echo "<div class='field-info'>";
                echo "<span class='field-label'>📝 Sujet:</span>";
                echo "<span class='field-value'>" . $data['subject'] . "</span>";
                echo "</div>";
                
                echo "<div class='field-info' style='border-bottom: none;'>";
                echo "<span class='field-label'>💬 Message:</span>";
                echo "</div>";
                echo "<div style='margin-top: 10px; padding: 10px; background: white; border-radius: 5px; line-height: 1.6;'>";
                echo nl2br($data['message']);
                echo "</div>";
                
                echo "<div style='margin-top: 15px; font-size: 14px; color: #6c757d;'>";
                echo "📅 Date d'envoi: " . date('Y-m-d H:i:s');
                echo "</div>";
                
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html>