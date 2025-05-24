<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
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
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
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
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧮 PHP Calculator</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="num1">First Number:</label>
                <input type="number" step="any" name="num1" id="num1" 
                       value="<?php echo isset($_POST['num1']) ? $_POST['num1'] : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="operation">Operation:</label>
                <select name="operation" id="operation" required>
                    <option value="">Select Operation</option>
                    <option value="+" <?php echo (isset($_POST['operation']) && $_POST['operation'] == '+') ? 'selected' : ''; ?>>➕ Addition</option>
                    <option value="-" <?php echo (isset($_POST['operation']) && $_POST['operation'] == '-') ? 'selected' : ''; ?>>➖ Subtraction</option>
                    <option value="*" <?php echo (isset($_POST['operation']) && $_POST['operation'] == '*') ? 'selected' : ''; ?>>✖️ Multiplication</option>
                    <option value="/" <?php echo (isset($_POST['operation']) && $_POST['operation'] == '/') ? 'selected' : ''; ?>>➗ Division</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="num2">Second Number:</label>
                <input type="number" step="any" name="num2" id="num2" 
                       value="<?php echo isset($_POST['num2']) ? $_POST['num2'] : ''; ?>" required>
            </div>
            
            <button type="submit">Calculate</button>
        </form>

        <?php
        if ($_POST) {
            $num1 = $_POST['num1'];
            $num2 = $_POST['num2'];
            $operation = $_POST['operation'];
            
            if (is_numeric($num1) && is_numeric($num2) && !empty($operation)) {
                $result = 0;
                $error = false;
                
                switch ($operation) {
                    case '+':
                        $result = $num1 + $num2;
                        $op_name = "Addition";
                        break;
                    case '-':
                        $result = $num1 - $num2;
                        $op_name = "Subtraction";
                        break;
                    case '*':
                        $result = $num1 * $num2;
                        $op_name = "Multiplication";
                        break;
                    case '/':
                        if ($num2 != 0) {
                            $result = $num1 / $num2;
                            $op_name = "Division";
                        } else {
                            $error = true;
                            $error_msg = "Error: Division by zero is not allowed!";
                        }
                        break;
                    default:
                        $error = true;
                        $error_msg = "Invalid operation!";
                }
                
                if (!$error) {
                    echo "<div class='result'>";
                    echo "<h3>🎯 Result:</h3>";
                    echo "<p><strong>Operation:</strong> $num1 $operation $num2</p>";
                    echo "<p><strong>Operation Type:</strong> $op_name</p>";
                    echo "<p><strong>Result:</strong> " . number_format($result, 2) . "</p>";
                    echo "</div>";
                } else {
                    echo "<div class='result error'>";
                    echo "<h3>❌ Error:</h3>";
                    echo "<p>$error_msg</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='result error'>";
                echo "<h3>❌ Error:</h3>";
                echo "<p>Please fill in all fields with valid values!</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>
</html>