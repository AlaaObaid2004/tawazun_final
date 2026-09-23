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
    <title>مهامي العلاجية - منصة توازن</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">

        <!-- الرأس (Header) -->
        <header class="main-header">
            <div class="logo-area" style="align-items: center; gap: 12px;">
                <img src="../assets/images/logo.png" alt="شعار توازن" style="height: 65px; width: auto; mix-blend-mode: multiply; object-fit: contain;">
                <div class="logo-text" style="display: flex; flex-direction: column; justify-content: center;">
                    <p style="font-size: 1.15rem; color: var(--primary-color); font-weight: 800; margin: 0; padding-top: 8px;">للصحة النفسية</p>
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

        <div class="page-header right-header">
            <div class="page-icon teal-icon"><i class="fa-solid fa-bullseye"></i></div>
            <div class="header-content">
                <h2>مهامي العلاجية</h2>
                <p>تتبع تمارينك وجلساتك العلاجية لتحقيق التوازن النفسي</p>
            </div>
        </div>

        <div class="stats-cards small-stats">
            <div class="stat-box">
                <div class="stat-box-icon blue-icon"><i class="fa-solid fa-bullseye"></i></div>
                <div class="stat-box-info">
                    <span>المهام القادمة</span>
                    <strong>3</strong>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-box-icon teal-icon"><i class="fa-solid fa-check-circle"></i></div>
                <div class="stat-box-info">
                    <span>المهام المكتملة</span>
                    <strong>2</strong>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-box-icon purple-icon"><i class="fa-solid fa-video"></i></div>
                <div class="stat-box-info">
                    <span>الجلسات القادمة</span>
                    <strong>1</strong>
                </div>
            </div>
        </div>

        <h3 class="section-subtitle mt-4">المهام القادمة</h3>
        <div class="tasks-list">
            <div class="task-card">
                <div class="task-radio"><i class="fa-regular fa-circle"></i></div>
                <div class="task-content">
                    <h4>تمرين التنفس العميق</h4>
                    <div class="task-meta">
                        <span class="badge blue-badge"><i class="fa-solid fa-lungs"></i> تمرين</span>
                        <span><i class="fa-regular fa-calendar"></i> 2026-02-25</span>
                        <span><i class="fa-regular fa-clock"></i> 10 دقائق</span>
                    </div>
                    <p>مارس تمرين التنفس العميق لمدة 10 دقائق يومياً. استنشق من الأنف لمدة 4 ثوان، احبس النفس 4 ثوان، ثم أخرج الزفير من الفم لمدة 6 ثوان.</p>
                    <button class="btn btn-sm btn-primary"><i class="fa-solid fa-play"></i> بدء المهمة</button>
                </div>
            </div>

            <div class="task-card">
                <div class="task-radio"><i class="fa-regular fa-circle"></i></div>
                <div class="task-content">
                    <h4>جلسة استشارية مع د. سارة أحمد</h4>
                    <div class="task-meta">
                        <span class="badge purple-badge"><i class="fa-solid fa-video"></i> جلسة</span>
                        <span><i class="fa-regular fa-calendar"></i> 2026-02-26</span>
                        <span><i class="fa-regular fa-clock"></i> 45 دقيقة</span>
                    </div>
                    <p>جلسة متابعة لمناقشة التقدم في التعامل مع ضغوط الدراسة وتطوير استراتيجيات جديدة.</p>
                    <button class="btn btn-sm btn-primary"><i class="fa-solid fa-play"></i> بدء المهمة</button>
                </div>
            </div>

            <div class="task-card">
                <div class="task-radio"><i class="fa-regular fa-circle"></i></div>
                <div class="task-content">
                    <h4>كتابة يومية</h4>
                    <div class="task-meta">
                        <span class="badge blue-badge"><i class="fa-solid fa-book"></i> تمرين</span>
                        <span><i class="fa-regular fa-calendar"></i> 2026-02-25</span>
                        <span><i class="fa-regular fa-clock"></i> 20 دقيقة</span>
                    </div>
                    <p>اكتب عن مشاعرك وأفكارك اليومية. ركز على الأشياء الإيجابية التي حدثت اليوم.</p>
                    <button class="btn btn-sm btn-primary"><i class="fa-solid fa-play"></i> بدء المهمة</button>
                </div>
            </div>
        </div>

        <h3 class="section-subtitle mt-4">المهام المكتملة</h3>
        <div class="tasks-list">
            <div class="task-card completed-task">
                <div class="task-radio completed-radio"><i class="fa-solid fa-check-circle"></i></div>
                <div class="task-content">
                    <h4>قراءة: إدارة القلق بفعالية</h4>
                    <div class="task-meta">
                        <span class="badge teal-badge"><i class="fa-solid fa-book-open"></i> قراءة</span>
                        <span><i class="fa-regular fa-calendar"></i> 2026-02-25</span>
                    </div>
                    <p>اقرأ المقال المرفق حول تقنيات إدارة القلق وكيفية التعامل مع الأفكار السلبية.</p>
                </div>
            </div>

            <div class="task-card completed-task">
                <div class="task-radio completed-radio"><i class="fa-solid fa-check-circle"></i></div>
                <div class="task-content">
                    <h4>تمرين اليقظة الذهنية</h4>
                    <div class="task-meta">
                        <span class="badge blue-badge"><i class="fa-solid fa-brain"></i> تمرين</span>
                        <span><i class="fa-regular fa-calendar"></i> 2026-02-24</span>
                    </div>
                    <p>خصص 15 دقيقة للتأمل واليقظة الذهنية. ركز على اللحظة الحالية دون الحكم على أفكارك.</p>
                </div>
            </div>
        </div>

    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>