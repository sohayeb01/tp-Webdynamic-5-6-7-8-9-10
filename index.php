<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74EBD5 0%, #9FACE6 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            max-width: 800px;
            width: 90%;
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-5px);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #74EBD5, #9FACE6);
            border-radius: 3px;
        }
        
        p {
            text-align: center;
            color: #666;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        ul {
            list-style-type: none;
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 0;
            margin: 30px 0;
        }
        
        ul li {
            background-color: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            position: relative;
        }
        
        ul li::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, #74EBD5, #9FACE6);
        }
        
        ul li:hover {
            transform: translateX(5px);
            background-color: #f1f5f9;
        }
        
        ul li a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 20px 25px;
            font-weight: 500;
            font-size: 17px;
            transition: color 0.3s ease;
        }
        
        ul li a:hover {
            color: #4a8eff;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #888;
            font-size: 14px;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                width: 95%;
            }
            
            ul li a {
                padding: 15px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Accueil</h1>

        <p>Bienvenue sur le site de TP7PHP</p>
        <p>Choisissez une option dans le menu ci-dessous</p>
        <ul>
            <li><a href="tp5/index.html">TP5</a></li>
            <li><a href="tp6/index.html">TP6</a></li>
            <li><a href="tp7php/index.html">TP7PHP</a></li>
            <li><a href="tp8/index.html">TP8</a></li>
            <li><a href="tp9/index.html">TP9</a></li>
            <li><a href="tp10/index.php">TP10</a></li>
        </ul>
        
        <div class="footer">
            &copy; 2024 - Tous droits réservés
        </div>
    </div>
</body>
</html>
