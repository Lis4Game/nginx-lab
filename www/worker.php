<?php
require_once 'db.php';
require_once 'Student.php';
require_once 'QueueManager.php';

echo "👷 Worker started...\n";
echo "📊 Listening for student registration messages in Kafka...\n";
echo "⏳ Press Ctrl+C to stop\n\n";

$studentModel = new Student($pdo);
$queueManager = new QueueManager();

try {
    $queueManager->consume(function($data) use ($studentModel, $pdo) {
        echo "📥 Received message: " . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'register_student':
                    echo "🎓 Processing new student registration...\n";
                    
                    $studentData = $data['data'] ?? [];
                    
                    $required = ['name', 'age', 'course', 'payment_form'];
                    foreach ($required as $field) {
                        if (!isset($studentData[$field]) || $studentData[$field] === '') {
                            echo "❌ Missing required field: $field\n";
                            echo "---\n";
                            return;
                        }
                    }
                    
                    try {
                        $studentId = $studentModel->add(
                            $studentData['name'],
                            (int)$studentData['age'],
                            $studentData['course'],
                            (bool)($studentData['certificate_needed'] ?? false),
                            $studentData['payment_form']
                        );
                        
                        echo "✅ Student saved to database. ID: $studentId\n";
                        
                        
                        
                    } catch (Exception $e) {
                        echo "❌ Error saving student: " . $e->getMessage() . "\n";
                    }
                    break;
                    
                default:
                    echo "⚠️ Unknown action: " . htmlspecialchars($data['action']) . "\n";
                    break;
            }
        } else {
            echo "⚠️ No action specified in message\n";
        }
        
        echo "---\n";
    });
    
} catch (Exception $e) {
    echo "❌ Worker error: " . $e->getMessage() . "\n";
    echo "🔄 Restarting in 5 seconds...\n";
    sleep(5);
}