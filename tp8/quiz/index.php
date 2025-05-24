<?php
// Start session to maintain quiz state
session_start();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار قصير - PHP</title>
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
            margin-left: 10px;
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
            margin-left: 10px;
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
            'question' => 'ما هو الامتداد الصحيح لملفات PHP؟',
            'options' => [
                'A' => '.html',
                'B' => '.php',
                'C' => '.js',
                'D' => '.css'
            ],
            'correct' => 'B',
            'explanation' => 'الامتداد الصحيح لملفات PHP هو .php حيث يتم تنفيذ الكود على الخادم'
        ],
        [
            'question' => 'أي من هذه الرموز يُستخدم لبدء كود PHP؟',
            'options' => [
                'A' => '&lt;script&gt;',
                'B' => '&lt;%',
                'C' => '&lt;?php',
                'D' => '&lt;#'
            ],
            'correct' => 'C',
            'explanation' => 'يبدأ كود PHP بالرمز <?php وينتهي بـ ?>'
        ],
        [
            'question' => 'أي من هذه المتغيرات صحيح في PHP؟',
            'options' => [
                'A' => 'var name',
                'B' => '$name',
                'C' => 'name$',
                'D' => '#name'
            ],
            'correct' => 'B',
            'explanation' => 'في PHP، تبدأ المتغيرات بعلامة الدولار $ متبوعة بالاسم'
        ],
        [
            'question' => 'ما هي الطريقة الصحيحة لطباعة النص في PHP؟',
            'options' => [
                'A' => 'console.log("Hello")',
                'B' => 'print("Hello")',
                'C' => 'echo "Hello"',
                'D' => 'كلاهما B و C صحيح'
            ],
            'correct' => 'D',
            'explanation' => 'يمكن استخدام كل من echo و print لطباعة النص في PHP'
        ],
        [
            'question' => 'أي من هذه الدوال تُستخدم للحصول على طول النص في PHP؟',
            'options' => [
                'A' => 'length()',
                'B' => 'size()',
                'C' => 'strlen()',
                'D' => 'count()'
            ],
            'correct' => 'C',
            'explanation' => 'الدالة strlen() تُستخدم للحصول على طول النص في PHP'
        ],
        [
            'question' => 'كيف يتم إنشاء مصفوفة في PHP؟',
            'options' => [
                'A' => '$arr = []',
                'B' => '$arr = array()',
                'C' => '$arr = new Array()',
                'D' => 'كلاهما A و B صحيح'
            ],
            'correct' => 'D',
            'explanation' => 'يمكن إنشاء المصفوفات في PHP باستخدام [] أو array()'
        ],
        [
            'question' => 'ما هو الرمز المستخدم لربط النصوص في PHP؟',
            'options' => [
                'A' => '+',
                'B' => '&',
                'C' => '.',
                'D' => 'concat()'
            ],
            'correct' => 'C',
            'explanation' => 'يُستخدم الرمز . (النقطة) لربط النصوص في PHP'
        ],
        [
            'question' => 'أي من هذه التعليقات صحيح في PHP؟',
            'options' => [
                'A' => '// تعليق',
                'B' => '# تعليق',
                'C' => '/* تعليق */',
                'D' => 'جميع ما سبق'
            ],
            'correct' => 'D',
            'explanation' => 'PHP يدعم ثلاثة أنواع من التعليقات: // و # و /* */'
        ]
    ];
    
    $totalQuestions = count($quiz);
    $submitted = isset($_POST['submit']);
    
    // Handle form reset
    if (isset($_POST['reset'])) {
        unset($_SESSION['quiz_completed']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if ($submitted) {
        $userAnswers = $_POST;
        $score = 0;
        $results = [];
        
        foreach ($quiz as $index => $question) {
            $questionNum = $index + 1;
            $userAnswer = isset($userAnswers["q$questionNum"]) ? $userAnswers["q$questionNum"] : '';
            $isCorrect = ($userAnswer === $question['correct']);
            
            if ($isCorrect) {
                $score++;
            }
            
            $results[] = [
                'question' => $question['question'],
                'user_answer' => $userAnswer,
                'correct_answer' => $question['correct'],
                'is_correct' => $isCorrect,
                'explanation' => $question['explanation'],
                'options' => $question['options']
            ];
        }
        
        $percentage = ($score / $totalQuestions) * 100;
        $_SESSION['quiz_completed'] = true;
    }
    ?>

    <div class="container">
        <h1>🧠 اختبار قصير في PHP</h1>
        
        <?php if (!$submitted): ?>
            <!-- Quiz Form -->
            <div class="quiz-info">
                <h3>📋 معلومات الاختبار</h3>
                <p><strong>عدد الأسئلة:</strong> <?php echo $totalQuestions; ?> أسئلة</p>
                <p><strong>نوع الأسئلة:</strong> اختيار من متعدد</p>
                <p><strong>الوقت المقدر:</strong> 10 دقائق</p>
                <p><strong>التعليمات:</strong> اختر الإجابة الصحيحة لكل سؤال</p>
            </div>
            
            <form method="POST" id="quizForm">
                <?php foreach ($quiz as $index => $question): ?>
                    <div class="question">
                        <div class="question-title">
                            <span class="question-number"><?php echo $index + 1; ?></span>
                            <?php echo $question['question']; ?>
                        </div>
                        
                        <div class="options">
                            <?php foreach ($question['options'] as $key => $option): ?>
                                <div class="option">
                                    <label>
                                        <input type="radio" name="q<?php echo $index + 1; ?>" value="<?php echo $key; ?>" required>
                                        <strong><?php echo $key; ?>)</strong> <?php echo $option; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <button type="submit" name="submit">📝 إرسال الإجابات</button>
            </form>
            
        <?php else: ?>
            <!-- Results Display -->
            <div class="score-display">
                🎯 النتيجة: <?php echo $score; ?> من <?php echo $totalQuestions; ?> 
                (<?php echo round($percentage, 1); ?>%)
            </div>
            
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $percentage; ?>%">
                    <?php echo round($percentage, 1); ?>%
                </div>
            </div>
            
            <div class="results <?php 
                if ($percentage >= 80) echo 'score-excellent';
                elseif ($percentage >= 60) echo 'score-good';
                else echo 'score-poor';
            ?>">
                <h3>
                    <?php 
                    if ($percentage >= 80) echo '🎉 ممتاز! أداء رائع';
                    elseif ($percentage >= 60) echo '👍 جيد! يمكنك التحسن أكثر';
                    else echo '📚 يحتاج لمراجعة أكثر';
                    ?>
                </h3>
                <p>
                    <?php 
                    if ($percentage >= 80) echo 'لديك فهم ممتاز لأساسيات PHP. استمر في التعلم!';
                    elseif ($percentage >= 60) echo 'لديك فهم جيد للأساسيات، راجع المواضيع التي أخطأت فيها.';
                    else echo 'يُنصح بمراجعة أساسيات PHP مرة أخرى قبل المتابعة.';
                    ?>
                </p>
            </div>
            
            <!-- Detailed Results -->
            <h3 style="margin-top: 30px;">📊 تفاصيل الإجابات:</h3>
            
            <?php foreach ($results as $index => $result): ?>
                <div class="question-result <?php echo $result['is_correct'] ? 'correct' : 'incorrect'; ?>">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <span class="answer-indicator <?php echo $result['is_correct'] ? 'correct-indicator' : 'incorrect-indicator'; ?>">
                            <?php echo $result['is_correct'] ? '✅ صحيح' : '❌ خطأ'; ?>
                        </span>
                        <strong>السؤال <?php echo $index + 1; ?>:</strong>
                    </div>
                    
                    <p style="margin: 10px 0;"><?php echo $result['question']; ?></p>
                    
                    <div style="margin: 10px 0;">
                        <?php if ($result['user_answer']): ?>
                            <p><strong>إجابتك:</strong> <?php echo $result['user_answer']; ?>) <?php echo $result['options'][$result['user_answer']]; ?></p>
                        <?php else: ?>
                            <p><strong>إجابتك:</strong> لم تجب على هذا السؤال</p>
                        <?php endif; ?>
                        
                        <p><strong>الإجابة الصحيحة:</strong> <?php echo $result['correct_answer']; ?>) <?php echo $result['options'][$result['correct_answer']]; ?></p>
                    </div>
                    
                    <div class="explanation">
                        <strong>التفسير:</strong> <?php echo $result['explanation']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Action Buttons -->
            <form method="POST" style="margin-top: 30px;">
                <button type="submit" name="reset" class="reset-btn">🔄 إعادة المحاولة</button>
            </form>
            
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px; color: #666; font-size: 14px;">
            <p>💡 <strong>نصيحة:</strong> لتعلم المزيد عن PHP، قم بزيارة الوثائق الرسمية على php.net</p>
        </div>
    </div>

    <script>
        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll to results
            <?php if ($submitted): ?>
                setTimeout(() => {
                    document.querySelector('.score-display').scrollIntoView({behavior: 'smooth'});
                }, 100);
            <?php endif; ?>
            
            // Add progress indicator during quiz
            <?php if (!$submitted): ?>
                const form = document.getElementById('quizForm');
                const questions = document.querySelectorAll('.question');
                
                questions.forEach((question, index) => {
                    const inputs = question.querySelectorAll('input[type="radio"]');
                    inputs.forEach(input => {
                        input.addEventListener('change', updateProgress);
                    });
                });
                
                function updateProgress() {
                    const totalQuestions = <?php echo $totalQuestions; ?>;
                    const answeredQuestions = form.querySelectorAll('input[type="radio"]:checked').length;
                    const percentage = (answeredQuestions / totalQuestions) * 100;
                    
                    // Create or update progress bar
                    let progressBar = document.querySelector('.quiz-progress');
                    if (!progressBar) {
                        progressBar = document.createElement('div');
                        progressBar.className = 'progress-bar quiz-progress';
                        progressBar.innerHTML = '<div class="progress-fill"></div>';
                        form.insertBefore(progressBar, form.firstChild);
                    }
                    
                    const fill = progressBar.querySelector('.progress-fill');
                    fill.style.width = percentage + '%';
                    fill.textContent = `${answeredQuestions} من ${totalQuestions} أسئلة`;
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>