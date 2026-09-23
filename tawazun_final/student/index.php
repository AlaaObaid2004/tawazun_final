<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (file_exists('../config/db_connection.php')) {
    require_once '../config/db_connection.php';
} else {
    $conn = new mysqli("localhost", "root", "", "tawazun_final_db");
    $conn->set_charset("utf8mb4");
}
$total_requests = 0;
$pending_requests = 0;
$completed_requests = 0;
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
    $student_name = $_SESSION['name'] ?? '';
    if (!empty($student_name)) {
        $stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM psychological_requests WHERE student_name = ?");
        $stmt_total->bind_param("s", $student_name);
        $stmt_total->execute();
        $res_total = $stmt_total->get_result()->fetch_assoc();
        $total_requests = $res_total['total'] ?? 0;
        $stmt_total->close();
        $stmt_pending = $conn->prepare("SELECT COUNT(*) AS pending FROM psychological_requests WHERE student_name = ? AND status = 'قيد المراجعة'");
        $stmt_pending->bind_param("s", $student_name);
        $stmt_pending->execute();
        $res_pending = $stmt_pending->get_result()->fetch_assoc();
        $pending_requests = $res_pending['pending'] ?? 0;
        $stmt_pending->close();
        $stmt_completed = $conn->prepare("SELECT COUNT(*) AS completed FROM psychological_requests WHERE student_name = ? AND status = 'مكتملة'");
        $stmt_completed->bind_param("s", $student_name);
        $stmt_completed->execute();
        $res_completed = $stmt_completed->get_result()->fetch_assoc();
        $completed_requests = $res_completed['completed'] ?? 0;
        $stmt_completed->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - منصة توازن</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .welcome-banner {
            text-align: right;
        }

        .main-header {
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">

        <header class="main-header">
            <div class="logo-area" style="align-items: center; gap: 12px;">
                <img src="../assets/images/logo.png" alt="شعار توازن" style="height: 65px; width: auto; mix-blend-mode: multiply; object-fit: contain;">
                <div class="logo-text" style="display: flex; flex-direction: column; justify-content: center;">
                    <p style="font-size: 1.15rem; color: var(--primary-color); font-weight: 800; margin: 0; padding-top: 8px;">للصحة النفسية</p>
                </div>
            </div>

            <div class="user-area" style="display: flex; gap: 20px; align-items: center;">
                <button class="icon-btn notification-btn" aria-label="الإشعارات">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
                <div class="user-profile" style="color: #14b8a6; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-regular fa-user user-avatar" style="background: transparent; padding: 0; font-size: 1.2rem;"></i>
                    <span class="user-name">
                        <?php echo htmlspecialchars($_SESSION['name'] ?? 'الطالب'); ?>
                    </span>
                </div>
                <a href="../auth/login.php" aria-label="تسجيل الخروج" style="transform: rotate(180deg); display: inline-flex; align-items: center; justify-content: center; text-decoration: none; color: inherit;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </header>

        <div class="welcome-banner">
            <h2>مرحباً بك، <?php echo htmlspecialchars($_SESSION['name'] ?? 'الطالب'); ?></h2>
            <p>نحن هنا لدعمك ومساعدتك على تحقيق التوازن النفسي</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">إجمالي الطلبات</span>
                    <strong class="stat-number"><?php echo $total_requests; ?></strong>
                </div>
                <div class="stat-icon blue-icon">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">قيد المراجعة</span>
                    <strong class="stat-number"><?php echo $pending_requests; ?></strong>
                </div>
                <div class="stat-icon orange-icon">
                    <i class="fa-regular fa-clock"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">مكتملة</span>
                    <strong class="stat-number"><?php echo $completed_requests; ?></strong>
                </div>
                <div class="stat-icon green-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="quick-actions-section">
            <h3 class="section-title">الإجراءات السريعة</h3>
            <div class="actions-grid">
                <a href="submit_request.php" class="action-card teal-bg">
                    <div class="action-content">
                        <h4>إرسال مشكلة</h4>
                        <p>شارك ما تشعر به معنا</p>
                    </div>
                    <div class="action-icon">
                        <i class="fa-solid fa-comment-medical"></i>
                    </div>
                </a>

                <a href="my_requests.php" class="action-card blue-bg">
                    <div class="action-content">
                        <h4>طلباتي</h4>
                        <p>تتبع حالة طلباتك</p>
                    </div>
                    <div class="action-icon">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </a>

                <a href="tasks.php" class="action-card purple-bg">
                    <div class="action-content">
                        <h4>مهامي العلاجية</h4>
                        <p>تمارين وجلسات</p>
                    </div>
                    <div class="action-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </a>

                <a href="guidance.php" class="action-card pink-bg">
                    <div class="action-content">
                        <h4>إرشادات نفسية</h4>
                        <p>نصائح وموارد مفيدة</p>
                    </div>
                    <div class="action-icon">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                </a>

                <a href="chat.php" class="action-card orange-bg full-width">
                    <div class="action-content">
                        <h4>محادثة مع الطبيب</h4>
                        <p>تواصل مباشر مع مختص</p>
                    </div>
                    <div class="action-icon">
                        <i class="fa-regular fa-comment-dots"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="useful-resources-section">
            <h3 class="section-title">موارد مفيدة</h3>
            <div class="resources-list">
                <a href="guidance.php" class="resource-item">
                    <div class="resource-icon">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="resource-details" style="text-align: right;">
                        <h4>نصائح للصحة النفسية</h4>
                        <p>اقرأ مقالات مفيدة</p>
                    </div>
                </a>
                <a href="#" class="resource-item">
                    <div class="resource-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="resource-details" style="text-align: right;">
                        <h4>موارد تعليمية</h4>
                        <p>دليلك للتوازن النفسي</p>
                    </div>
                </a>
                <a href="#" class="resource-item">
                    <div class="resource-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="resource-details" style="text-align: right;">
                        <h4>خط الطوارئ</h4>
                        <p>متاح على مدار الساعة</p>
                    </div>
                </a>
            </div>
        </div>

        <footer class="main-footer">
            <div class="footer-links">
                <a href="#">مركز المساعدة</a>
                <a href="../pages/privacy.php">سياسة الخصوصية</a>
                <a href="../pages/terms.php">شروط الاستخدام</a>
            </div>
            <p class="copyright">© 2026 منصة توازن - جميع الحقوق محفوظة</p>
        </footer>

    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>