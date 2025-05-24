<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de Mot de Passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #ff6b6b;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            border-radius: 5px;
        }
        .password-display {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            letter-spacing: 2px;
            word-break: break-all;
            margin: 10px 0;
        }
        .strength {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .strength-bar {
            height: 10px;
            width: 100px;
            background: #ddd;
            border-radius: 5px;
            margin-left: 10px;
            overflow: hidden;
        }
        .strength-fill {
            height: 100%;
            transition: width 0.3s;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .options {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
        }
        .checkbox-item input {
            width: auto;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Générateur de Mot de Passe</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="length">Longueur du mot de passe (8-128 caractères):</label>
                <input type="number" name="length" id="length" min="8" max="128"
                       value="<?php echo isset($_POST['length']) ? $_POST['length'] : '12'; ?>" required>
            </div>
            
            <div class="options">
                <label>Options de caractères:</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="uppercase" id="uppercase" 
                               <?php echo (isset($_POST['uppercase']) || !isset($_POST['generate'])) ? 'checked' : ''; ?>>
                        <label for="uppercase">Majuscules (A-Z)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="lowercase" id="lowercase"
                               <?php echo (isset($_POST['lowercase']) || !isset($_POST['generate'])) ? 'checked' : ''; ?>>
                        <label for="lowercase">Minuscules (a-z)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="numbers" id="numbers"
                               <?php echo (isset($_POST['numbers']) || !isset($_POST['generate'])) ? 'checked' : ''; ?>>
                        <label for="numbers">Chiffres (0-9)</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" name="symbols" id="symbols"
                               <?php echo isset($_POST['symbols']) ? 'checked' : ''; ?>>
                        <label for="symbols">Symboles (!@#$%)</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" name="generate">🎲 Générer un mot de passe</button>
        </form>

        <?php
        function generatePassword($length, $options) {
            $characters = '';
            
            if ($options['uppercase']) {
                $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }
            if ($options['lowercase']) {
                $characters .= 'abcdefghijklmnopqrstuvwxyz';
            }
            if ($options['numbers']) {
                $characters .= '0123456789';
            }
            if ($options['symbols']) {
                $characters .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
            }
            
            if (empty($characters)) {
                return false;
            }
            
            $password = '';
            $charactersLength = strlen($characters);
            
            for ($i = 0; $i < $length; $i++) {
                $password .= $characters[random_int(0, $charactersLength - 1)];
            }
            
            return $password;
        }
        
        function calculateStrength($password, $options) {
            $score = 0;
            $length = strlen($password);
            
            // Length points
            if ($length >= 8) $score += 20;
            if ($length >= 12) $score += 20;
            if ($length >= 16) $score += 20;
            
            // Character variety points
            if ($options['uppercase']) $score += 10;
            if ($options['lowercase']) $score += 10;
            if ($options['numbers']) $score += 10;
            if ($options['symbols']) $score += 10;
            
            return min(100, $score);
        }
        
        if (isset($_POST['generate'])) {
            $length = intval($_POST['length']);
            
            if ($length < 8 || $length > 128) {
                echo "<div class='result error'>";
                echo "<h3>❌ Erreur:</h3>";
                echo "<p>La longueur du mot de passe doit être comprise entre 8 et 128 caractères!</p>";
                echo "</div>";
            } else {
                $options = [
                    'uppercase' => isset($_POST['uppercase']),
                    'lowercase' => isset($_POST['lowercase']),
                    'numbers' => isset($_POST['numbers']),
                    'symbols' => isset($_POST['symbols'])
                ];
                
                if (!$options['uppercase'] && !$options['lowercase'] && !$options['numbers'] && !$options['symbols']) {
                    echo "<div class='result error'>";
                    echo "<h3>❌ Erreur:</h3>";
                    echo "<p>Vous devez sélectionner au moins un type de caractères!</p>";
                    echo "</div>";
                } else {
                    $password = generatePassword($length, $options);
                    $strength = calculateStrength($password, $options);
                    
                    if ($password) {
                        echo "<div class='result'>";
                        echo "<h3>🎯 Mot de passe généré:</h3>";
                        echo "<div class='password-display'>$password</div>";
                        
                        $strengthColor = '';
                        $strengthText = '';
                        if ($strength < 40) {
                            $strengthColor = '#dc3545';
                            $strengthText = 'Faible';
                        } elseif ($strength < 70) {
                            $strengthColor = '#ffc107';
                            $strengthText = 'Moyen';
                        } else {
                            $strengthColor = '#28a745';
                            $strengthText = 'Fort';
                        }
                        
                        echo "<div class='strength'>";
                        echo "<span>Force: <strong>$strengthText</strong></span>";
                        echo "<div class='strength-bar'>";
                        echo "<div class='strength-fill' style='width: $strength%; background-color: $strengthColor'></div>";
                        echo "</div>";
                        echo "</div>";
                        
                        echo "<p><strong>Longueur:</strong> $length caractères</p>";
                        echo "<p><strong>Contient:</strong> ";
                        $contains = [];
                        if ($options['uppercase']) $contains[] = "majuscules";
                        if ($options['lowercase']) $contains[] = "minuscules";
                        if ($options['numbers']) $contains[] = "chiffres";
                        if ($options['symbols']) $contains[] = "symboles";
                        echo implode(", ", $contains);
                        echo "</p>";
                        
                        echo "<p><small>Ce mot de passe a été généré localement dans votre navigateur et n'est pas stocké.</small></p>";
                        echo "</div>";
                    }
                }
            }
        }
        ?>
    </div>
</body>
</html>