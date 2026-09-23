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
    <title>إرشادات نفسية - منصة توازن</title>
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
            <div class="page-icon pink-icon"><i class="fa-regular fa-heart"></i></div>
            <div class="header-content">
                <h2>إرشادات نفسية</h2>
                <p>نصائح وموارد مفيدة لتعزيز صحتك النفسية وتوازنك</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" placeholder="ابحث عن مقالات، نصائح، أو مواضيع...">
            <i class="fa-solid fa-search"></i>
        </div>

        <h3 class="section-subtitle mt-4">مقالات مميزة</h3>
        <div class="resources-list">
            <div class="resource-item">
                <div class="resource-icon" style="background-color: #eff6ff; color: var(--blue);">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div class="resource-details" style="flex: 1;">
                    <h4>كيف تتعامل مع ضغوطات العمل والدراسة؟</h4>
                    <p>استراتيجيات فعالة لإدارة الوقت وتقليل التوتر اليومي.</p>
                </div>
                <button class="btn btn-sm btn-secondary">قراءة</button>
            </div>

            <div class="resource-item">
                <div class="resource-icon" style="background-color: #f0fdfa; color: var(--teal);">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <div class="resource-details" style="flex: 1;">
                    <h4>أهمية اليقظة الذهنية (Mindfulness)</h4>
                    <p>خطوات بسيطة للبدء في ممارسة اليقظة الذهنية في حياتك اليومية.</p>
                </div>
                <button class="btn btn-sm btn-secondary">قراءة</button>
            </div>

            <div class="resource-item">
                <div class="resource-icon" style="background-color: #fef3c7; color: var(--orange);">
                    <i class="fa-solid fa-sun"></i>
                </div>
                <div class="resource-details" style="flex: 1;">
                    <h4>بناء عادات صباحية إيجابية</h4>
                    <p>كيف يؤثر روتينك الصباحي على مزاجك وإنتاجيتك طوال اليوم.</p>
                </div>
                <button class="btn btn-sm btn-secondary">قراءة</button>
            </div>
        </div>

        <h3 class="section-subtitle mt-4">نصائح سريعة</h3>
        <div class="tasks-list">
            <div class="task-card">
                <div class="task-radio completed-radio" style="color: var(--pink);"><i
                        class="fa-solid fa-lightbulb"></i></div>
                <div class="task-content">
                    <h4>قاعدة 5-4-3-2-1 للتعامل مع القلق</h4>
                    <div class="task-meta">
                        <span class="badge pink-badge"><i class="fa-solid fa-star"></i> نصيحة</span>
                    </div>
                    <p>عندما تشعر بالقلق، ابحث عن: 5 أشياء تراها، 4 أشياء تلمسها، 3 أشياء تسمعها، شيئين تشمهما، وشيء
                        واحد تتذوقه.</p>
                </div>
            </div>

            <div class="task-card">
                <div class="task-radio completed-radio" style="color: var(--pink);"><i
                        class="fa-solid fa-lightbulb"></i></div>
                <div class="task-content">
                    <h4>خذ استراحة رقمية</h4>
                    <div class="task-meta">
                        <span class="badge pink-badge"><i class="fa-solid fa-star"></i> نصيحة</span>
                    </div>
                    <p>حاول الابتعاد عن الشاشات ووسائل التواصل الاجتماعي لمدة ساعة قبل النوم لتحسين جودة نومك وتقليل
                        التوتر.</p>
                </div>
            </div>
        </div>

    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>