<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كتاب الزوار</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
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
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
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
            border-color: #ff9a56;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        button {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
            font-weight: bold;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .messages-section {
            margin-top: 30px;
        }
        .message-item {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .message-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .message-author {
            font-weight: bold;
            color: #333;
            font-size: 18px;
        }
        .message-date {
            color: #666;
            font-size: 14px;
        }
        .message-content {
            line-height: 1.6;
            color: #444;
            margin-top: 10px;
        }
        .no-messages {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .result {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .stats {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .required {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php
    $messagesFile = 'guestbook_messages.txt';
    
    // Function to save message to file
    function saveMessage($name, $message) {
        global $messagesFile;
        $date = date('Y-m-d H:i:s');
        $entry = "$date|$name|$message\n";
        
        // For demo purposes, we'll simulate file operations using session storage
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['guestbook_messages'])) {
            $_SESSION['guestbook_messages'] = [];
        }
        
        $_SESSION['guestbook_messages'][] = [
            'date' => $date,
            'name' => $name,
            'message' => $message
        ];
        
        return true;
    }
    
    // Function to read messages from file
    function readMessages() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['guestbook_messages'])) {
            // Initialize with some sample messages
            $_SESSION['guestbook_messages'] = [
                [
                    'date' => '2024-01-20 10:30:00',
                    'name' => 'أحمد محمد',
                    'message' => 'موقع رائع! شكراً لكم على المحتوى المفيد.'
                ],
                [
                    'date' => '2024-01-19 15:45:00',
                    'name' => 'فاطمة العلي',
                    'message' => 'أعجبني التصميم كثيراً، واصلوا الإبداع!'
                ],
                [
                    'date' => '2024-01-18 09:15:00',
                    'name' => 'محمد الأحمد',
                    'message' => 'تجربة مستخدم ممتازة، أنصح الجميع بزيارة الموقع.'
                ]
            ];
        }
        
        // Sort messages by date (newest first)
        $messages = $_SESSION['guestbook_messages'];
        usort($messages, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $messages;
    }
    
    // Handle form submission
    if (isset($_POST['submit'])) {
        $name = trim($_POST['name']);
        $message = trim($_POST['message']);
        $errors = [];
        
        // Validation
        if (empty($name)) {
            $errors[] = 'الاسم مطلوب';
        } elseif (strlen($name) < 2) {
            $errors[] = 'الاسم يجب أن يكون حرفين على الأقل';
        }
        
        if (empty($message)) {
            $errors[] = 'الرسالة مطلوبة';
        } elseif (strlen($message) < 5) {
            $errors[] = 'الرسالة يجب أن تكون 5 أحرف على الأقل';
        }
        
        if (empty($errors)) {
            // Sanitize input
            $name = htmlspecialchars($name);
            $message = htmlspecialchars($message);
            
            if (saveMessage($name, $message)) {
                $success_message = 'تم إضافة رسالتك بنجاح! شكراً لك.';
                // Clear form
                $_POST = [];
            } else {
                $error_message = 'حدث خطأ أثناء حفظ الرسالة. يرجى المحاولة مرة أخرى.';
            }
        }
    }
    
    $messages = readMessages();
    ?>

    <div class="container">
        <h1>📖 كتاب الزوار</h1>
        
        <div class="stats">
            <strong>📊 إحصائيات:</strong> 
            يحتوي كتاب الزوار على <?php echo count($messages); ?> رسالة من الزوار الكرام
        </div>
        
        <!-- Add Message Form -->
        <div class="form-section">
            <h2>✍️ اترك رسالة</h2>
            
            <?php if (isset($success_message)): ?>
                <div class="result success">
                    ✅ <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="result error">
                    ❌ <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="result error">
                    <strong>يرجى تصحيح الأخطاء التالية:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="name">الاسم <span class="required">*</span>:</label>
                    <input type="text" name="name" id="name" 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                           placeholder="أدخل اسمك" required>
                </div>
                
                <div class="form-group">
                    <label for="message">الرسالة <span class="required">*</span>:</label>
                    <textarea name="message" id="message" 
                              placeholder="اكتب رسالتك هنا..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>
                
                <button type="submit" name="submit">📝 إضافة الرسالة</button>
            </form>
        </div>
        
        <!-- Messages Display -->
        <div class="messages-section">
            <h2>💬 رسائل الزوار</h2>
            
            <?php if (empty($messages)): ?>
                <div class="no-messages">
                    📭 لا توجد رسائل بعد. كن أول من يترك رسالة!
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message-item">
                        <div class="message-header">
                            <span class="message-author">👤 <?php echo $msg['name']; ?></span>
                            <span class="message-date">🕐 <?php echo date('Y/m/d - H:i', strtotime($msg['date'])); ?></span>
                        </div>
                        <div class="message-content">
                            <?php echo nl2br($msg['message']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>