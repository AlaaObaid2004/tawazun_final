<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (file_exists('../config/db_connection.php')) {
  require_once '../config/db_connection.php';
}
if (isset($_SESSION['user_id']) && isset($conn)) {
  $user_id = $_SESSION['user_id'];

  $stmt = $conn->prepare("SELECT name FROM admin_login WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    $_SESSION['name'] = $user['name'];
  }
  $stmt->close();
}
$current_year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
$current_month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$prev_month = $current_month - 1;
$prev_year = $current_year;
if ($prev_month == 0) {
  $prev_month = 12;
  $prev_year--;
}
$next_month = $current_month + 1;
$next_year = $current_year;
if ($next_month == 13) {
  $next_month = 1;
  $next_year++;
}
$months_arabic = [
  1 => "يناير",
  2 => "فبراير",
  3 => "مارس",
  4 => "أبريل",
  5 => "مايو",
  6 => "يونيو",
  7 => "يوليو",
  8 => "أغسطس",
  9 => "سبتمبر",
  10 => "أكتوبر",
  11 => "نوفمبر",
  12 => "ديسمبر"
];
function toArabicNumbers($num)
{
  $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
  $ar = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
  return str_replace($en, $ar, $num);
}
$num_of_days = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
$first_day_timestamp = mktime(0, 0, 0, $current_month, 1, $current_year);
$first_day_of_week = intval(date('w', $first_day_timestamp));
$events = [];
if (isset($conn)) {
  $month_filter = $current_year . '-' . str_pad($current_month, 2, '0', STR_PAD_LEFT) . '-%';
  $query = "SELECT 
                DAY(created_at) as day_num,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = 'مكتملة' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'ملغاة' THEN 1 ELSE 0 END) as canceled,
                SUM(CASE WHEN status = 'جديد' THEN 1 ELSE 0 END) as new_requests
              FROM psychological_requests 
              WHERE created_at LIKE ? 
              GROUP BY DAY(created_at)";

  $stmt_cal = $conn->prepare($query);
  $stmt_cal->bind_param("s", $month_filter);
  $stmt_cal->execute();
  $res_cal = $stmt_cal->get_result();
  while ($row = $res_cal->fetch_assoc()) {
    $events[$row['day_num']] = $row;
  }
  $stmt_cal->close();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>منصة توازن - أرشيف الجلسات</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --purple: #7B2FBE;
      --purple-light: #F3E8FF;
      --purple-mid: #9B59D6;
      --teal: #2D9B8A;
      --orange: #E8833A;
      --red: #E05252;
      --green: #4CAF80;
      --blue: #4A90C4;
      --text-dark: #1A1A2E;
      --text-mid: #4A4A6A;
      --text-light: #9090B0;
      --bg: #F8F5FF;
      --white: #FFFFFF;
      --border: #E0D0F0;
    }

    body {
      font-family: 'Tajawal', sans-serif;
      background: var(--bg);
      color: var(--text-dark);
      min-height: 100vh;
      direction: rtl;
      padding: 0 16px;
      max-width: 600px;
      margin: 0 auto;
    }

    .navbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 8px rgba(123, 47, 190, 0.08);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .navbar-logo {
      width: 44px;
      height: 44px;
      object-fit: contain;
    }

    .brand-name {
      font-size: 16px;
      font-weight: 800;
      color: var(--purple);
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
      background: var(--purple-light);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 6px 12px;
      font-size: 13px;
      font-weight: 600;
      color: var(--purple);
    }

    .notif-btn {
      position: relative;
      background: none;
      border: none;
      cursor: pointer;
      padding: 6px;
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
    }

    .back-bar {
      padding: 10px 16px;
      background: var(--white);
      border-bottom: 1px solid var(--border);
    }

    .back-link {
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--purple);
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
    }

    .page-header {
      padding: 20px 20px 0;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
    }

    .page-icon {
      width: 52px;
      height: 52px;
      background: var(--purple-light);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .page-icon svg {
      width: 26px;
      height: 26px;
      color: var(--purple);
    }

    .page-title {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-dark);
      text-align: right;
    }

    .page-sub {
      font-size: 13px;
      color: var(--text-light);
      text-align: right;
    }

    .main {
      padding: 16px;
    }

    .top-btns {
      display: flex;
      gap: 10px;
      justify-content: flex-start;
      margin-bottom: 16px;
    }

    .outline-btn {
      background: var(--white);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 9px 14px;
      font-family: 'Tajawal', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--text-mid);
    }

    .view-toggle {
      display: flex;
      gap: 0;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      overflow: hidden;
      margin-bottom: 16px;
    }

    .view-btn {
      flex: 1;
      padding: 9px;
      background: var(--white);
      border: none;
      font-family: 'Tajawal', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      color: var(--text-mid);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .view-btn.active {
      background: var(--purple);
      color: white;
    }

    .calendar-wrap {
      background: var(--white);
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 16px;
      margin-bottom: 16px;
    }

    .cal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }

    .cal-month {
      font-size: 18px;
      font-weight: 800;
      color: var(--text-dark);
    }

    .cal-nav {
      display: flex;
      gap: 8px;
    }

    .cal-nav-btn {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: var(--purple-light);
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--purple);
    }

    .cal-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
    }

    .cal-day-name {
      text-align: center;
      font-size: 11px;
      font-weight: 700;
      color: var(--text-light);
      padding: 4px 0;
    }

    .cal-cell {
      border-radius: 8px;
      padding: 6px 4px;
      min-height: 64px;
      cursor: pointer;
      position: relative;
    }

    .cal-cell.empty {
      background: none;
      cursor: default;
    }

    .cal-cell.has-sessions {
      background: var(--purple-light);
      border: 1px solid var(--border);
    }

    .cal-date {
      font-size: 11px;
      font-weight: 700;
      color: var(--text-dark);
      text-align: left;
      margin-bottom: 3px;
    }

    .cal-badge {
      font-size: 10px;
      font-weight: 700;
      color: white;
      background: var(--purple);
      border-radius: 4px;
      padding: 1px 4px;
      text-align: center;
      margin-bottom: 2px;
    }

    .cal-info {
      font-size: 9px;
      color: var(--green);
    }

    .cal-info.red {
      color: var(--red);
    }

    .stats-footer {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 10px;
    }

    .footer-stat {
      border-radius: 14px;
      padding: 16px;
      text-align: center;
    }

    .footer-stat.purple {
      background: var(--purple);
    }

    .footer-stat.teal {
      background: var(--teal);
    }

    .footer-stat.blue {
      background: var(--blue);
    }

    .footer-stat-val {
      font-size: 28px;
      font-weight: 800;
      color: white;
    }

    .footer-stat-label {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.8);
      margin-top: 4px;
    }

    .footer-stat-sub {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.65);
      margin-top: 2px;
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="navbar-actions">
      <button class="logout-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
      </button>
      <button class="notif-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="notif-dot"></span>
      </button>
      <div class="nav-profile">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        أ.<?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?>
      </div>
    </div>
    <div class="navbar-brand">
      <div>
        <div class="brand-name">منصة توازن</div>
        <div class="brand-sub">بوابة المشرفين</div>
      </div>
    </div>
  </nav>

  <div class="back-bar">
    <a href="../admin/admin-dashboard.php" class="back-link">
      <span>العودة إلى لوحة التحكم</span>
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </a>
  </div>

  <div class="page-header">
    <div class="page-icon">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
      </svg>
    </div>
    <div>
      <div class="page-title">أرشيف الجلسات</div>
      <div class="page-sub">سجل شامل لجميع الجلسات السابقة</div>
    </div>
  </div>

  <div class="main">
    <div class="top-btns">
      <button class="outline-btn">تصدير</button>
      <button class="outline-btn">تصفية</button>
    </div>

    <div class="view-toggle">
      <button class="view-btn">عرض القائمة</button>
      <button class="view-btn active">عرض التقويم</button>
    </div>

    <div class="calendar-wrap">
      <div class="cal-header">
        <div class="cal-nav">
          <a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="cal-nav-btn">&gt;</a>
          <a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="cal-nav-btn">&lt;</a>
        </div>
        <div class="cal-month"><?php echo $months_arabic[$current_month] . " " . toArabicNumbers($current_year); ?></div>
      </div>

      <div class="cal-grid">
        <div class="cal-day-name">الأحد</div>
        <div class="cal-day-name">الإثنين</div>
        <div class="cal-day-name">الثلاثاء</div>
        <div class="cal-day-name">الأربعاء</div>
        <div class="cal-day-name">الخميس</div>
        <div class="cal-day-name">الجمعة</div>
        <div class="cal-day-name">السبت</div>

        <?php
        for ($i = 0; $i < $first_day_of_week; $i++) {
          echo '<div class="cal-cell empty"></div>';
        }
        for ($day = 1; $day <= $num_of_days; $day++) {

          if (isset($events[$day])) {
            $session_info = $events[$day];
        ?>
            <div class="cal-cell day-item has-sessions">
              <div class="cal-date"><?php echo $day; ?></div>

              <div class="cal-badge"><?php echo toArabicNumbers($session_info['total_sessions']); ?> جلسات</div>

              <?php if ($session_info['new_requests'] > 0): ?>
                <div class="cal-info blue">● <?php echo toArabicNumbers($session_info['new_requests']); ?> جديدة</div>
              <?php endif; ?>

              <?php if ($session_info['completed'] > 0): ?>
                <div class="cal-info">✓ <?php echo toArabicNumbers($session_info['completed']); ?> مكتملة</div>
              <?php endif; ?>

              <?php if ($session_info['canceled'] > 0): ?>
                <div class="cal-info red">X <?php echo toArabicNumbers($session_info['canceled']); ?> ملغاة</div>
              <?php endif; ?>
            </div>
          <?php
          } else {
          ?>
            <div class="cal-cell day-item">
              <div class="cal-date"><?php echo $day; ?></div>
            </div>
        <?php
          }
        }
        ?>
      </div>
    </div>
  </div>
  <div style="display:flex;align-items:center;justify-content:center;gap:16px;margin-bottom:16px;font-size:12px;color:var(--text-mid);">
    <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;background:var(--purple);border-radius:3px;display:inline-block;"></span> جلسات محجوزة</span>
    <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;background:var(--green);border-radius:3px;display:inline-block;"></span> جلسات مكتملة</span>
    <span style="display:flex;align-items:center;gap:4px;"><span style="width:12px;height:12px;background:var(--red);border-radius:3px;display:inline-block;"></span> جلسات ملغاة</span>
  </div>
  <div class="stats-footer">
    <div class="footer-stat purple">
      <div class="footer-stat-val">156</div>
      <div class="footer-stat-label">إجمالي الجلسات</div>
      <div class="footer-stat-sub">هذا الشهر</div>
    </div>
    <div class="footer-stat teal">
      <div class="footer-stat-val">92%</div>
      <div class="footer-stat-label">معدل الإكمال</div>
      <div class="footer-stat-sub">من الجلسات المحجوزة</div>
    </div>
    <div class="footer-stat blue">
      <div class="footer-stat-val">48</div>
      <div class="footer-stat-label">متوسط المدة</div>
      <div class="footer-stat-sub">دقيقة لكل جلسة</div>
    </div>
  </div>
  </div>

</body>

</html>