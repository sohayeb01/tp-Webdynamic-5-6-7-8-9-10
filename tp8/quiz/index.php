<?php
// Start session to maintain quiz state
session_start();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
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
        .quiz-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .question {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #007bff;
        }
        .question-title {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
        }
        .question-number {
            background: #007bff;
            color: white;
            padding: 5px 12px;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 10px;
        }
        .options {
            margin-top: 15px;
        }
        .option {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .option:hover {
            background-color: #e9ecef;
        }
        .option input {
            margin-right: 10px;
        }
        .option label {
            cursor: pointer;
            display: block;
            padding: 5px;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s;
            font-weight: bold;
            margin-top: 20px;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .results {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
        }
        .score-excellent {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .score-good {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }
        .score-poor {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .question-result {
            margin: 15px 0;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .correct {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .incorrect {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .score-display {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .reset-btn {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            margin-top: 10px;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .explanation {
            background: #f0f8ff;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
            color: #0066cc;
        }
        .answer-indicator {
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 10px;
        }
        .correct-indicator {
            background: #d4edda;
            color: #155724;
        }
        .incorrect-indicator {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php
    // Quiz questions and answers
    $quiz = [
        [
            'question' => 'Quelle est l\'extension correcte des fichiers PHP?',
            'options' => [
                'A' => '.html',
                'B' => '.php',
                'C' => '.js',
                'D' => '.css'
            ],
            'correct' => 'B',
            'explanation' => 'L\'extension correcte des fichiers PHP est .php, où le code est exécuté sur le serveur'
        ],
        [
            'question' => 'Quel symbole est utilisé pour commencer un code PHP?',
            'options' => [
                'A' => '&lt;script&gt;',
                'B' => '&lt;%',
                'C' => '&lt;?php',
                'D' => '&lt;#'
            ],
            'correct' => 'C',
            'explanation' => 'Le code PHP commence par le symbole <?php et se termine par ?>'
        ],
        [
            'question' => 'Quelle variable est correcte en PHP?',
            'options' => [
                'A' => 'var name',
                'B' => '$name',
                'C' => 'name$',
                'D' => '#name'
            ],
            'correct' => 'B',
            'explanation' => 'En PHP, les variables commencent par le signe dollar $ suivi du nom'
        ],
        [
            'question' => 'Quelle est la méthode correcte pour afficher du texte en PHP?',
            'options' => [
                'A' => 'console.log("Hello")',
                'B' => 'print("Hello")',
                'C' => 'echo "Hello"',
                'D' => 'Les deux B et C sont corrects'
            ],
            'correct' => 'D',
            'explanation' => 'Vous pouvez utiliser à la fois echo et print pour afficher du texte en PHP'
        ],
        [
            'question' => 'Quelle fonction est utilisée pour obtenir la longueur d\'une chaîne en PHP?',
            'options' => [
                'A' => 'length()',
                'B' => 'size()',
                'C' => 'strlen()',
                'D' => 'count()'
            ],
            'correct' => 'C',
            'explanation' => 'La fonction strlen() est utilisée pour obtenir la longueur d\'une chaîne en PHP'
        ],
        [
            'question' => 'Comment créer un tableau en PHP?',
            'options' => [
                'A' => '$arr = []',
                'B' => '$arr = array()',
                'C' => '$arr = new Array()',
                'D' => 'Les deux A et B sont corrects'
            ],
            'correct' => 'D',
            'explanation' => 'Vous pouvez créer des tableaux en PHP en utilisant [] ou array()'
        ],
        [
            'question' => 'Quelle fonction est utilisée pour vérifier si une variable est définie?',
            'options' => [
                'A' => 'isDefined()',
                'B' => 'isset()',
                'C' => 'ifexist()',
                'D' => 'checkvar()'
            ],
            'correct' => 'B',
            'explanation' => 'La fonction isset() est utilisée pour vérifier si une variable est définie et n\'est pas NULL'
        ],
        [
            'question' => 'Comment commencer une session en PHP?',
            'options' => [
                'A' => 'session_start()',
                'B' => 'session_begin()',
                'C' => '$_SESSION->start()',
                'D' => 'begin_session()'
            ],
            'correct' => 'A',
            'explanation' => 'La fonction session_start() est utilisée pour démarrer une nouvelle session ou reprendre une session existante'
        ],
        [
            'question' => 'Quelle est la méthode correcte pour inclure un fichier en PHP?',
            'options' => [
                'A' => 'include()',
                'B' => 'require()',
                'C' => 'import()',
                'D' => 'Les deux A et B sont corrects'
            ],
            'correct' => 'D',
            'explanation' => 'Vous pouvez utiliser include() ou require() pour inclure des fichiers en PHP'
        ],
        [
            'question' => 'Comment accéder aux données d\'un formulaire POST en PHP?',
            'options' => [
                'A' => '$_GET["name"]',
                'B' => '$_POST["name"]',
                'C' => '$form->get("name")',
                'D' => 'Request.Form("name")'
            ],
            'correct' => 'B',
            'explanation' => '$_POST["name"] est utilisé pour accéder aux données envoyées via la méthode POST'
        ]
    ];
    
    // Reset quiz
    if (isset($_POST['reset'])) {
        unset($_SESSION['quiz_answers']);
        unset($_SESSION['quiz_submitted']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Process form submission
    if (isset($_POST['submit'])) {
        $_SESSION['quiz_submitted'] = true;
        
        // Store user answers
        $_SESSION['quiz_answers'] = [];
        foreach ($quiz as $index => $question) {
            $questionId = 'q' . $index;
            $_SESSION['quiz_answers'][$index] = isset($_POST[$questionId]) ? $_POST[$questionId] : '';
        }
    }
    
    // Calculate score if quiz is submitted
    $score = 0;
    $totalQuestions = count($quiz);
    
    if (isset($_SESSION['quiz_submitted']) && $_SESSION['quiz_submitted']) {
        foreach ($quiz as $index => $question) {
            if (isset($_SESSION['quiz_answers'][$index]) && $_SESSION['quiz_answers'][$index] === $question['correct']) {
                $score++;
            }
        }
    }
    ?>

    <div class="container">
        <?php if (isset($_SESSION['quiz_submitted']) && $_SESSION['quiz_submitted']): ?>
            <!-- Results Page -->
            <h1>📝 Résultats du Quiz PHP</h1>
            
            <?php 
            $percentage = ($score / $totalQuestions) * 100;
            $resultClass = '';
            $resultMessage = '';
            
            if ($percentage >= 80) {
                $resultClass = 'score-excellent';
                $resultMessage = 'Excellent! Vous maîtrisez bien PHP!';
            } elseif ($percentage >= 60) {
                $resultClass = 'score-good';
                $resultMessage = 'Bon travail! Vous avez une bonne compréhension de PHP.';
            } else {
                $resultClass = 'score-poor';
                $resultMessage = 'Continuez à apprendre! Revoyez les bases de PHP.';
            }
            ?>
            
            <div class="quiz-info">
                <p>Vous avez terminé le quiz! Voici vos résultats:</p>
            </div>
            
            <div class="results <?php echo $resultClass; ?>">
                <div class="score-display">
                    <?php echo $score; ?> / <?php echo $totalQuestions; ?>
                    (<?php echo round($percentage); ?>%)
                </div>
                <p style="text-align: center; font-size: 18px;">
                    <strong><?php echo $resultMessage; ?></strong>
                </p>
            </div>
            
            <h2>Détails des réponses:</h2>
            
            <?php foreach ($quiz as $index => $question): ?>
                <?php 
                $userAnswer = isset($_SESSION['quiz_answers'][$index]) ? $_SESSION['quiz_answers'][$index] : '';
                $isCorrect = ($userAnswer === $question['correct']);
                $questionClass = $isCorrect ? 'correct' : 'incorrect';
                $indicatorClass = $isCorrect ? 'correct-indicator' : 'incorrect-indicator';
                $indicatorText = $isCorrect ? 'Correct' : 'Incorrect';
                ?>
                
                <div class="question-result <?php echo $questionClass; ?>">
                    <span class="answer-indicator <?php echo $indicatorClass; ?>"><?php echo $indicatorText; ?></span>
                    <div class="question-title">
                        <span class="question-number"><?php echo ($index + 1); ?></span>
                        <?php echo $question['question']; ?>
                    </div>
                    
                    <div style="margin: 10px 0;">
                        <strong>Votre réponse:</strong> 
                        <?php 
                        if (!empty($userAnswer)) {
                            echo $userAnswer . '. ' . $question['options'][$userAnswer];
                        } else {
                            echo '<span style="color: #dc3545;">Aucune réponse</span>';
                        }
                        ?>
                    </div>
                    
                    <?php if (!$isCorrect): ?>
                        <div style="margin: 10px 0;">
                            <strong>Réponse correcte:</strong> 
                            <?php echo $question['correct'] . '. ' . $question['options'][$question['correct']]; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="explanation">
                        <strong>Explication:</strong> <?php echo $question['explanation']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <form method="POST">
                <button type="submit" name="reset" class="reset-btn">🔄 Recommencer le Quiz</button>
            </form>
            
        <?php else: ?>
            <!-- Quiz Form -->
            <h1>📝 Quiz PHP</h1>
            
            <div class="quiz-info">
                <p>Testez vos connaissances en PHP avec ce quiz de <?php echo $totalQuestions; ?> questions!</p>
                <p><strong>Instructions:</strong> Sélectionnez la bonne réponse pour chaque question.</p>
            </div>
            
            <form method="POST">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%;">0%</div>
                </div>
                
                <?php foreach ($quiz as $index => $question): ?>
                    <div class="question" id="question-<?php echo $index; ?>" data-question="<?php echo $index; ?>">
                        <div class="question-title">
                            <span class="question-number"><?php echo ($index + 1); ?></span>
                            <?php echo $question['question']; ?>
                        </div>
                        
                        <div class="options">
                            <?php foreach ($question['options'] as $key => $option): ?>
                                <div class="option">
                                    <input type="radio" name="q<?php echo $index; ?>" id="q<?php echo $index; ?>-<?php echo $key; ?>" value="<?php echo $key; ?>">
                                    <label for="q<?php echo $index; ?>-<?php echo $key; ?>"><?php echo $key; ?>. <?php echo $option; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <button type="submit" name="submit">Soumettre les réponses</button>
            </form>
            
            <script>
                // Simple progress tracking
                document.addEventListener('DOMContentLoaded', function() {
                    const radioButtons = document.querySelectorAll('input[type="radio"]');
                    const progressFill = document.querySelector('.progress-fill');
                    const totalQuestions = <?php echo $totalQuestions; ?>;
                    let answeredQuestions = 0;
                    
                    radioButtons.forEach(radio => {
                        radio.addEventListener('change', function() {
                            // Get all questions
                            const questions = {};
                            radioButtons.forEach(btn => {
                                const questionId = btn.name;
                                questions[questionId] = true;
                            });
                            
                            // Count answered questions
                            answeredQuestions = 0;
                            Object.keys(questions).forEach(questionId => {
                                if (document.querySelector(`input[name="${questionId}"]:checked`)) {
                                    answeredQuestions++;
                                }
                            });
                            
                            // Update progress bar
                            const progress = Math.round((answeredQuestions / totalQuestions) * 100);
                            progressFill.style.width = `${progress}%`;
                            progressFill.textContent = `${progress}%`;
                        });
                    });
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>