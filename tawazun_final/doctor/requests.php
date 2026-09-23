<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "tawazun_final_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
$res_all = $conn->query("SELECT COUNT(*) as total FROM psychological_requests");
$count_all = ($res_all) ? $res_all->fetch_assoc()['total'] : 0;
$res_new = $conn->query("SELECT COUNT(*) as total FROM psychological_requests WHERE status = 'جديد'");
$count_new = ($res_new) ? $res_new->fetch_assoc()['total'] : 0;
$res_review = $conn->query("SELECT COUNT(*) as total FROM psychological_requests WHERE status = 'قيد المراجعة'");
$count_review = ($res_review) ? $res_review->fetch_assoc()['total'] : 0;
$res_completed = $conn->query("SELECT COUNT(*) as total FROM psychological_requests WHERE status = 'مكتملة'");
$count_completed = ($res_completed) ? $res_completed->fetch_assoc()['total'] : 0;
$filter = isset($_GET['status']) ? $_GET['status'] : 'all';
if ($filter == 'new') {
    $sql = "SELECT * FROM psychological_requests WHERE status = 'جديد' ORDER BY id DESC";
} elseif ($filter == 'review') {
    $sql = "SELECT * FROM psychological_requests WHERE status = 'قيد المراجعة' ORDER BY id DESC";
} elseif ($filter == 'completed') {
    $sql = "SELECT * FROM psychological_requests WHERE status = 'مكتملة' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM psychological_requests ORDER BY id DESC";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <title>منصة توازن - طلبات الطلاب</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --teal: #2D9B8A;
            --teal-light: #E8F5F3;
            --teal-mid: #4DB8A4;
            --orange: #E8833A;
            --purple: #7B5EA7;
            --blue: #4A90C4;
            --text-dark: #1A2E2A;
            --text-mid: #4A6260;
            --text-light: #8AADA8;
            --bg: #F5FAFA;
            --white: #FFFFFF;
            --border: #D4EDEA;
            --red: #E05252;
            --green: #4CAF80;
            --yellow: #F5A623;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            min-height: 100vh;
            direction: rtl;
            padding-bottom: 40px;
        }

        /* حاوية لتوسيط وضبط العرض للمحتوى الداخلي بشكل مريح وتجاوبي */
        .main-wrapper {
            max-width: 640px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* هيدر الصفحة الرئيسي */
        .navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(45, 155, 138, 0.06);
        }

        .navbar .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--teal);
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--text-light);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--teal-light);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 6px 14px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: var(--teal);
        }

        .nav-profile svg,
        .notif-btn svg,
        .logout-btn svg {
            width: 16px;
            height: 16px;
            display: block;
        }

        .notif-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            color: var(--text-mid);
        }

        .notif-dot {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 8px;
            height: 8px;
            background: var(--red);
            border-radius: 50%;
            border: 2px solid var(--white);
        }

        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            padding: 6px;
            transition: color 0.2s;
        }

        .logout-btn:hover {
            color: var(--red);
        }

        /* شريط العودة للوحة التحكم */
        .back-bar {
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: flex-start;
            margin-bottom: 16px;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--teal);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        .back-link svg {
            width: 16px;
            height: 16px;
        }

        /* رأس قائمة الطلبات */
        .page-header {
            padding: 16px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: right;
            margin-bottom: 12px;
        }

        .page-icon {
            width: 48px;
            height: 48px;
            background: var(--teal-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .page-icon svg {
            width: 24px;
            height: 24px;
            color: var(--teal);
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .page-sub {
            font-size: 13px;
            color: var(--text-light);
        }

        /* شريط التبويبات المتجاوب */
        .tabs-row {
            display: flex;
            gap: 8px;
            padding-bottom: 16px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .tabs-row::-webkit-scrollbar {
            display: none;
        }

        /* إخفاء شريط السحب المزعج */
        .tab-btn {
            flex-shrink: 0;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-mid);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .tab-btn.active {
            background: var(--teal);
            color: white;
            border-color: var(--teal);
        }

        /* شريط البحث المتقدم والتصفية */
        .search-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box {
            flex: 1;
            display: flex;
            align-items: center;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 14px;
            gap: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
        }

        .search-box svg {
            width: 16px;
            height: 16px;
            color: var(--text-light);
            flex-shrink: 0;
        }

        .search-box input {
            border: none;
            background: none;
            flex: 1;
            font-family: 'Tajawal', sans-serif;
            font-size: 13px;
            color: var(--text-dark);
            outline: none;
            text-align: right;
        }

        .search-box input::placeholder {
            color: var(--text-light);
        }

        .filter-btn {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-mid);
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-btn:hover {
            background: var(--bg);
        }

        .filter-btn svg {
            width: 16px;
            height: 16px;
        }

        /* كروت عرض طلبات المشاكل مع معالجة فيضان الأسطر */
        .requests-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .request-card {
            background: var(--white);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(45, 155, 138, 0.04);
            text-align: right;
            overflow: hidden;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .request-card:hover {
            box-shadow: 0 4px 14px rgba(45, 155, 138, 0.1);
            transform: translateY(-1px);
        }

        .rc-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            gap: 12px;
        }

        .rc-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            flex: 1;
            word-break: break-word;
            line-height: 1.4;
        }

        .badges {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            flex-shrink: 0;
            align-items: center;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-new {
            background: #E3F0FF;
            color: #2D6BB5;
        }

        .badge-high {
            background: #FFE8E8;
            color: #C0392B;
        }

        .badge-mid {
            background: #FFF3E0;
            color: #E67E22;
        }

        .badge-low {
            background: #E8F8F0;
            color: #27AE60;
        }

        .badge-review {
            background: #F0F0FF;
            color: #6B52A8;
        }

        .badge-done {
            background: #E8F8F0;
            color: var(--teal);
        }

        .rc-student {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-mid);
            margin-bottom: 10px;
            flex-wrap: wrap;
            word-break: break-word;
        }

        .rc-student svg {
            width: 14px;
            height: 14px;
            color: var(--text-light);
            flex-shrink: 0;
        }

        .student-tag {
            background: var(--teal-light);
            color: var(--teal);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .rc-desc {
            font-size: 13px;
            color: var(--text-mid);
            margin-bottom: 12px;
            line-height: 1.6;
            word-break: break-word;
        }

        .rc-meta {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            border-top: 1px dotted var(--border);
            padding-top: 8px;
        }

        .rc-time {
            font-size: 11px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rc-time svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }

        /* تنبيه المظهر الفارغ */
        .no-records {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            font-size: 14px;
            border: 1.5px dashed var(--border);
            border-radius: 14px;
            background: var(--white);
        }
    </style>
</head>

<body>


    <div class="main-wrapper">
        <nav class="navbar">
            <div class="main-wrapper nav-container">
                <div class="navbar-actions">
                    <button class="logout-btn" title="تسجيل الخروج">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                    <button class="notif-btn" title="التنبيهات">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="notif-dot"></span>
                    </button>
                    <div class="nav-profile">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>د. <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأخصائي'); ?></span>
                    </div>
                </div>
                <div class="navbar-brand">
                    <div>
                        <div class="brand-name">منصة توازن</div>
                        <div class="brand-sub">بوابة الأخصائيين</div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="back-bar">
            <a href="index.php" class="back-link">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للوحة التحكم
            </a>
        </div>

        <div class="page-header">
            <div class="page-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="page-title">طلبات الطلاب</div>
            <div class="page-sub">إدارة ومتابعة جميع الطلبات المقدمة من الطلاب بشكل آمن ومباشر</div>
        </div>

        <div class="tabs-row">
            <a href="?status=all" class="tab-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">الكل (<?php echo $count_all; ?>)</a>
            <a href="?status=new" class="tab-btn <?php echo $filter == 'new' ? 'active' : ''; ?>">جديد (<?php echo $count_new; ?>)</a>
            <a href="?status=review" class="tab-btn <?php echo $filter == 'review' ? 'active' : ''; ?>">قيد المراجعة (<?php echo $count_review; ?>)</a>
            <a href="?status=completed" class="tab-btn <?php echo $filter == 'completed' ? 'active' : ''; ?>">مكتملة (<?php echo $count_completed; ?>)</a>
        </div>

        <div class="search-filter">
            <div class="search-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="ابحث عن طلب أو طالب...">
            </div>
            <button class="filter-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                تصفية
            </button>
        </div>

        <div class="requests-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>

                    <?php
                    $status_badge_class = 'badge-new';
                    if ($row['status'] == 'قيد المراجعة') {
                        $status_badge_class = 'badge-review';
                    } elseif ($row['status'] == 'مكتملة') {
                        $status_badge_class = 'badge-done';
                    }

                    $priority_badge_class = 'badge-low';
                    if ($row['priority_level'] == 'عالية') {
                        $priority_badge_class = 'badge-high';
                    } elseif ($row['priority_level'] == 'متوسطة') {
                        $priority_badge_class = 'badge-mid';
                    }
                    ?>

                    <div class="request-card">
                        <div class="rc-top">
                            <div class="rc-title"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div class="badges">
                                <span class="badge <?php echo $status_badge_class; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                                <span class="badge <?php echo $priority_badge_class; ?>"><?php echo htmlspecialchars($row['priority_level']); ?></span>
                            </div>
                        </div>

                        <div class="rc-student">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>الطالب: <strong><?php echo ($row['is_anonymous'] == 1) ? "طالب مجهول الهوية" : htmlspecialchars($row['student_name']); ?></strong></span>
                            <span class="student-tag"><?php echo htmlspecialchars($row['problem_type']); ?></span>
                        </div>

                        <div class="rc-desc">
                            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                        </div>

                        <div class="rc-meta">
                            <div class="rc-time">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>تقديم الطلب: <?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-records">
                    لا توجد طلبات تطابق هذا القسم حالياً.
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>

</html>
<?php
$conn->close();
?>