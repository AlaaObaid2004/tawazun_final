<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (file_exists('../config/db_connection.php')) {
    require_once '../config/db_connection.php';
}
if (isset($_SESSION['user_id']) && isset($conn)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT name FROM register WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $_SESSION['name'] = $user['name'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إرسال مشكلة نفسية - منصة توازن</title>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* تنسيقات مبدئية متوافقة مع الصورة المعروضة */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .form-container {
            max-width: 650px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-header h2 {
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .form-header p {
            color: #777;
            font-size: 14px;
            margin: 0;
        }

        .privacy-notice {
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 13px;
            color: #2e7d32;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .required {
            color: red;
        }

        select,
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .priority-options {
            display: flex;
            gap: 15px;
        }

        .priority-label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 100%;
            justify-content: center;
        }

        .checkbox-group {
            margin-top: 15px;
        }

        .important-note {
            background: #fff3e0;
            border: 1px solid #ffe0b2;
            padding: 12px;
            border-radius: 4px;
            color: #e65100;
            font-size: 13px;
            margin-top: 20px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button {
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-submit {
            background-color: #008080;
            color: white;
            flex: 2;
        }

        .btn-cancel {
            background-color: #e0e0e0;
            color: #333;
            flex: 1;
        }

        /* تنسيق رسائل الاستجابة */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- الرأس (Header) -->
        <header class="main-header">
            <div class="logo-area" style="align-items: center; gap: 12px;">
                <img src="../assets/images/logo.png" alt="شعار توازن"
                    style="height: 65px; width: auto; mix-blend-mode: multiply; object-fit: contain;">
                <div class="logo-text" style="display: flex; flex-direction: column; justify-content: center;">
                    <p
                        style="font-size: 1.15rem; color: var(--primary-color); font-weight: 800; margin: 0; padding-top: 8px;">
                        للصحة النفسية</p>
                </div>
            </div>

            <div class="user-area">
                <button class="icon-btn" aria-label="تسجيل الخروج">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
                <div class="user-profile">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?></span>
                    <i class="fa-regular fa-user user-avatar"></i>
                </div>
                <button class="icon-btn notification-btn" aria-label="الإشعارات">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
            </div>
        </header>

        <div class="page-navigation">
            <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-right"></i> العودة إلى لوحة التحكم</a>
        </div>

        <div class="page-header center-header">
            <div class="page-icon teal-icon"><i class="fa-regular fa-comment-dots"></i></div>
            <h2>إرسال مشكلة نفسية</h2>
            <p>شاركنا ما تشعر به، نحن هنا للاستماع والمساعدة</p>
        </div>

        <div class="info-box privacy-box">
            <div class="info-icon"><i class="fa-solid fa-lock"></i></div>
            <div class="info-content">
                <strong>خصوصية وسرية تامة</strong>
                <p>جميع المعلومات التي تشاركها معنا محمية ومشفرة. سيتم التعامل معها بسرية تامة من قبل مختصين مؤهلين.</p>
            </div>
        </div>
        <?php
        $host = 'localhost';
        $dbname = 'tawazun_final_db';
        $username = 'root';
        $password = '';
        $message = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_name = htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); 
            $problem_type = $_POST['problem_type'] ?? '';
            $priority_level = $_POST['priority_level'] ?? 'متوسطة';
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
            if (empty($problem_type) || empty($title) || empty($description)) {
                $message = "<div class='alert error'>يرجى ملء جميع الحقول الإلزامية.</div>";
            } else {
                $sql = "INSERT INTO psychological_requests (student_name, problem_type, priority_level, title, description, is_anonymous) 
                VALUES (:student_name, :problem_type, :priority_level, :title, :description, :is_anonymous)";
                $stmt = $pdo->prepare($sql);
                try {
                    $stmt->execute([
                        ':student_name' => $student_name,
                        ':problem_type' => $problem_type,
                        ':priority_level' => $priority_level,
                        ':title' => $title,
                        ':description' => $description,
                        ':is_anonymous' => $is_anonymous
                    ]);

                    $message = "<div class='alert success'>تم إرسال مشكلتك بنجاح وبسرية تامة. سيتواصل معك الأخصائي قريباً.</div>";
                } catch (PDOException $e) {
                    $message = "<div class='alert error'>حدث خطأ أثناء إرسال طلبك: " . $e->getMessage() . "</div>";
                }
            }
        }
        ?>
        <form action="submit_request.php" method="POST">

            <div class="form-group">
                <label for="problem_type">نوع المشكلة <span class="required">*</span></label>
                <select name="problem_type" id="problem_type" required>
                    <option value="" disabled selected>اختر نوع المشكلة...</option>
                    <option value="قلق">قلق</option>
                    <option value="ضغوط الدراسة">ضغوط الدراسة</option>
                    <option value="صعوبة في التركيز">صعوبة في التركيز</option>
                    <option value="اضطرابات النوم">اضطرابات النوم</option>
                    <option value="العزلة الاجتماعية">العزلة الاجتماعية</option>
                </select>
            </div>

            <div class="form-group">
                <label>مستوى الأولوية <span class="required">*</span></label>
                <div class="priority-options">
                    <label class="priority-label">
                        <input type="radio" name="priority_level" value="عالية"> عالية
                    </label>
                    <label class="priority-label">
                        <input type="radio" name="priority_level" value="متوسطة" checked> متوسطة
                    </label>
                    <label class="priority-label">
                        <input type="radio" name="priority_level" value="منخفضة"> منخفضة
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="title">عنوان المشكلة <span class="required">*</span></label>
                <input type="text" name="title" id="title" placeholder="مثال: أشعر بضغط كبير بسبب الامتحانات" required>
            </div>

            <div class="form-group">
                <label for="description">وصف المشكلة بالتفصيل <span class="required">*</span></label>
                <textarea name="description" id="description" rows="6" placeholder="اكتب تفاصيل ما تشعر به هنا..."
                    required></textarea>
            </div>

            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="is_anonymous" value="1">
                    <strong>إرسال بشكل مجهول</strong> (لن يظهر اسمك للأخصائي)
                </label>
            </div>

            <div class="important-note">
                <strong>⚠️ ملاحظة هامة:</strong> إذا كنت تمر بأزمة حادة، يرجى الاتصال المباشر على 920033360.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">إرسال الطلب</button>
                <button type="button" class="btn-cancel" onclick="window.history.back();">إلغاء</button>
            </div>

        </form>

    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>