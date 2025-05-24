<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système d'Authentification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s;
            margin-bottom: 10px;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
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
        .welcome-box {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-radius: 15px;
            margin-bottom: 20px;
        }
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .demo-accounts {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #004085;
        }
        .account-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .account-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <?php
    // Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Demo user accounts (in real application, this would be in a database)
    $users = [
        'admin' => [
            'password' => 'admin123',
            'name' => 'Administrateur',
            'role' => 'Admin',
            'email' => 'admin@example.com'
        ],
        'user1' => [
            'password' => 'password123',
            'name' => 'Ahmed Mohamed',
            'role' => 'Utilisateur',
            'email' => 'ahmed@example.com'
        ],
        'guest' => [
            'password' => 'guest123',
            'name' => 'Invité',
            'role' => 'Invité',
            'email' => 'guest@example.com'
        ]
    ];
    
    // Handle logout
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Handle login
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (isset($users[$username]) && $users[$username]['password'] === $password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_data'] = $users[$username];
            $_SESSION['login_time'] = date('Y-m-d H:i:s');
        } else {
            $login_error = "Nom d'utilisateur ou mot de passe incorrect !";
        }
    }
    ?>

    <div class="container">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <!-- Welcome Page (User is logged in) -->
            <div class="welcome-box">
                <h1>🎉 Bienvenue !</h1>
                <h2><?php echo $_SESSION['user_data']['name']; ?></h2>
                <p>Vous êtes connecté avec succès</p>
            </div>
            
            <div class="user-info">
                <h3>📋 Informations utilisateur :</h3>
                <div class="account-item">
                    <span><strong>👤 Nom d'utilisateur :</strong></span>
                    <span><?php echo $_SESSION['username']; ?></span>
                </div>
                <div class="account-item">
                    <span><strong>🏷️ Nom complet :</strong></span>
                    <span><?php echo $_SESSION['user_data']['name']; ?></span>
                </div>
                <div class="account-item">
                    <span><strong>🎭 Rôle :</strong></span>
                    <span><?php echo $_SESSION['user_data']['role']; ?></span>
                </div>
                <div class="account-item">
                    <span><strong>📧 Email :</strong></span>
                    <span><?php echo $_SESSION['user_data']['email']; ?></span>
                </div>
                <div class="account-item">
                    <span><strong>🕐 Heure de connexion :</strong></span>
                    <span><?php echo $_SESSION['login_time']; ?></span>
                </div>
            </div>
            
            <a href="?logout=1">
                <button class="logout-btn">🚪 Déconnexion</button>
            </a>
            
        <?php else: ?>
            <!-- Login Page -->
            <h1>🔐 Connexion</h1>
            
            <div class="demo-accounts">
                <h4>🧪 Comptes de démonstration :</h4>
                <div class="account-item">
                    <span><strong>admin</strong></span>
                    <span>admin123</span>
                </div>
                <div class="account-item">
                    <span><strong>user1</strong></span>
                    <span>password123</span>
                </div>
                <div class="account-item">
                    <span><strong>guest</strong></span>
                    <span>guest123</span>
                </div>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" name="username" id="username" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           placeholder="Entrez votre nom d'utilisateur" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" 
                           placeholder="Entrez votre mot de passe" required>
                </div>
                
                <button type="submit" name="login">🔑 Connexion</button>
            </form>
            
            <?php if (isset($login_error)): ?>
                <div class="result error">
                    <h3>❌ Erreur de connexion :</h3>
                    <p><?php echo $login_error; ?></p>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</body>
</html>