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
  <title>منصة توازن - الطوارئ النفسية</title>
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
      --teal: #2D9B8A;
      --orange: #E8833A;
      --red: #E05252;
      --red-light: #FEF2F2;
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
      background: var(--red-light);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .page-icon svg {
      width: 26px;
      height: 26px;
      color: var(--red);
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

    .alert-banner {
      background: var(--red-light);
      border: 1.5px solid #FCA5A5;
      border-radius: 12px;
      padding: 14px 16px;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      justify-content: flex-end;
    }

    .alert-num {
      font-size: 32px;
      font-weight: 800;
      color: var(--red);
      flex-shrink: 0;
    }

    .alert-text {
      text-align: right;
    }

    .alert-text strong {
      font-size: 14px;
      color: var(--red);
      display: block;
    }

    .alert-text span {
      font-size: 12px;
      color: #B91C1C;
    }

    .tabs {
      display: flex;
      gap: 6px;
      margin-bottom: 14px;
      overflow-x: auto;
      padding-bottom: 4px;
    }

    .tab {
      padding: 7px 14px;
      border-radius: 20px;
      font-family: 'Tajawal', sans-serif;
      font-size: 13px;
      font-weight: 600;
      border: 1.5px solid var(--border);
      background: var(--white);
      color: var(--text-mid);
      cursor: pointer;
      white-space: nowrap;
    }

    .tab.active {
      background: var(--red);
      color: white;
      border-color: var(--red);
    }

    .filter-row {
      display: flex;
      gap: 6px;
      margin-bottom: 14px;
      flex-wrap: wrap;
    }

    .filter-label {
      font-size: 12px;
      color: var(--text-mid);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .severity-btn {
      padding: 5px 12px;
      border-radius: 16px;
      font-family: 'Tajawal', sans-serif;
      font-size: 12px;
      font-weight: 600;
      border: 1.5px solid var(--border);
      background: var(--white);
      cursor: pointer;
    }

    .severity-btn.active {
      background: var(--text-dark);
      color: white;
      border-color: var(--text-dark);
    }

    .severity-btn.critical {
      border-color: var(--red);
      color: var(--red);
    }

    .severity-btn.high {
      border-color: var(--orange);
      color: var(--orange);
    }

    .severity-btn.mid {
      border-color: var(--blue);
      color: var(--blue);
    }

    .cases-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .case-card {
      background: var(--white);
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 16px;
    }

    .case-card.urgent {
      border-color: #FCA5A5;
      background: #FFFAFA;
    }

    .case-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 8px;
    }

    .case-title {
      font-size: 15px;
      font-weight: 800;
      color: var(--text-dark);
      text-align: right;
    }

    .case-badges {
      display: flex;
      gap: 6px;
      align-items: center;
    }

    .badge {
      padding: 3px 8px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
    }

    .badge.waiting {
      background: #FEF3C7;
      color: #D97706;
    }

    .badge.treating {
      background: #DCFCE7;
      color: #16A34A;
    }

    .badge.solved {
      background: #F1F5F9;
      color: #64748B;
    }

    .severity {
      padding: 3px 8px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
    }

    .severity.critical {
      background: #FEE2E2;
      color: #DC2626;
    }

    .severity.high {
      background: #FEF3C7;
      color: #D97706;
    }

    .severity.mid {
      background: #DBEAFE;
      color: #2563EB;
    }

    .case-student {
      font-size: 12px;
      color: var(--text-mid);
      text-align: right;
      margin-bottom: 6px;
    }

    .case-desc {
      font-size: 13px;
      color: var(--text-dark);
      text-align: right;
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .case-meta {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      font-size: 11px;
      color: var(--text-light);
      margin-bottom: 10px;
    }

    .case-actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }

    .action-btn {
      padding: 8px 14px;
      border-radius: 8px;
      font-family: 'Tajawal', sans-serif;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      border: none;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .action-btn.green {
      background: var(--green);
      color: white;
    }

    .action-btn.blue {
      background: var(--blue);
      color: white;
    }

    .action-btn.purple {
      background: var(--purple);
      color: white;
    }

    .action-btn.outline {
      background: var(--white);
      border: 1.5px solid var(--border);
      color: var(--text-mid);
    }

    .action-btn svg {
      width: 14px;
      height: 14px;
    }

    .time-tag {
      font-size: 11px;
      color: var(--text-light);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .emergency-footer {
      background: var(--red);
      border-radius: 14px;
      padding: 16px;
      margin-top: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .footer-text strong {
      font-size: 14px;
      color: white;
      display: block;
    }

    .footer-text span {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.8);
    }

    .call-btn {
      width: 44px;
      height: 44px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: none;
      flex-shrink: 0;
    }

    .call-btn svg {
      width: 22px;
      height: 22px;
      color: var(--red);
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="navbar-actions">
      <button class="logout-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg></button>
      <button class="notif-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg><span class="notif-dot"></span></button>
      <div class="nav-profile"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>أ. <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?></div>
    </div>
    <div class="navbar-brand">
      <div>
        <div class="brand-name">منصة توازن</div>
        <div class="brand-sub">بوابة المشرفين</div>
      </div>
      <img src="../assets/images/logo.png" alt="توازن" class="navbar-logo">
    </div>
  </nav>
  <div class="back-bar" style="display:flex;gap:16px;"><a href="admin-sessions.php" class="back-link" style="color:var(--purple);">إدارة الجلسات <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg></a><a href="admin-dashboard.php" class="back-link">العودة إلى لوحة التحكم <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg></a></div>
  <div class="page-header">
    <div class="page-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg></div>
    <div>
      <div class="page-title">الطوارئ النفسية</div>
      <div class="page-sub">حالات تحتاج لتدخل فوري ومتابعة عاجلة</div>
    </div>
  </div>
  <div class="main">
    <div class="alert-banner">
      <div class="alert-text"><strong>لديك 2 حالات طارئة قيد الانتظار</strong><span>يرجى المراجعة والتدخل الفوري للحالات الحرجة</span></div>
      <div class="alert-num">2</div>
    </div>
    <div class="tabs">
      <button class="tab active">الكل (5)</button>
      <button class="tab">قيد الانتظار (2)</button>
      <button class="tab">جاري المعالجة (2)</button>
      <button class="tab">تم الحل (1)</button>
    </div>
    <div class="filter-row">
      <span class="filter-label"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg> تصفية حسب الخطورة:</span>
      <button class="severity-btn active">الكل</button>
      <button class="severity-btn critical">جرح جداً</button>
      <button class="severity-btn high">عالي</button>
      <button class="severity-btn mid">متوسط</button>
    </div>
    <div class="cases-list">
      <div class="case-card urgent">
        <div class="case-card-top">
          <div class="time-tag"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>منذ 10 دقائق</div>
          <div>
            <div class="case-title">أفكار انتحارية</div>
            <div class="case-badges"><span class="badge waiting">قيد الانتظار</span><span class="severity critical">جرح جداً</span></div>
          </div>
        </div>
        <div class="case-student">أحمد محمود • STU-2024-045</div>
        <div class="case-desc">الطالب يعاني من أفكار انتحارية ويحتاج لتدخل فوري. أبلغ عن شعوره بفقدان الأمل.</div>
        <div class="case-meta"><span>2026-02-25 • 11:30 ص</span></div>
        <div class="case-actions">
          <button class="action-btn purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>تعيين طبيب</button>
          <button class="action-btn blue" onclick="location.href='../doctor/chat.php'"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>محادثة</button>
          <button class="action-btn green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>اتصال فوري</button>
        </div>
      </div>
      <div class="case-card">
        <div class="case-card-top">
          <div class="time-tag"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>منذ 30 دقيقة</div>
          <div>
            <div class="case-title">نوبة هلع شديدة</div>
            <div class="case-badges"><span class="badge treating">جاري المعالجة</span><span class="severity critical">جرح جداً</span></div>
          </div>
        </div>
        <div class="case-student">طالبة مجهولة • ANONYMOUS-089</div>
        <div class="case-desc">طالبة تعاني من نوبات هلع متكررة وتشعر بعدم القدرة على التنفس.</div>
        <div class="case-meta"><span>مع د. سارة أحمد</span><span>2026-02-25 • 10:15 ص</span></div>
        <div class="case-actions">
          <button class="action-btn blue" onclick="location.href='../doctor/chat.php'"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>محادثة</button>
          <button class="action-btn outline"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>وضع علامة كمحلول</button>
        </div>
      </div>
      <div class="case-card">
        <div class="case-card-top">
          <div class="time-tag"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>منذ ساعة</div>
          <div>
            <div class="case-title">اكتئاب حاد</div>
            <div class="case-badges"><span class="badge treating">جاري المعالجة</span><span class="severity high">عالي</span></div>
          </div>
        </div>
        <div class="case-student">خالد عبدالله • STU-2024-078</div>
        <div class="case-desc">أعراض اكتئاب حاد مع عزلة اجتماعية وفقدان الاهتمام بالأنشطة اليومية.</div>
        <div class="case-meta"><span>مع د. أحمد خالد</span><span>2026-02-25 • 09:00 ص</span></div>
        <div class="case-actions">
          <button class="action-btn green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>اتصال فوري</button>
          <button class="action-btn blue" onclick="location.href='../doctor/chat.php'"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>محادثة</button>
          <button class="action-btn outline"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>وضع علامة كمحلول</button>
        </div>
      </div>
      <div class="case-card urgent">
        <div class="case-card-top">
          <div class="time-tag"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>منذ ساعتين</div>
          <div>
            <div class="case-title">قلق شديد مع أعراض جسدية</div>
            <div class="case-badges"><span class="badge waiting">قيد الانتظار</span><span class="severity high">عالي</span></div>
          </div>
        </div>
        <div class="case-student">فاطمة سالم • STU-2024-012</div>
        <div class="case-desc">قلق شديد مصحوب بأعراض جسدية (خفقان، تعرق، دوخة).</div>
        <div class="case-meta"><span>2026-02-24 • 08:45 م</span></div>
        <div class="case-actions">
          <button class="action-btn purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>تعيين طبيب</button>
          <button class="action-btn blue" onclick="location.href='../doctor/chat.php'"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>محادثة</button>
          <button class="action-btn green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>اتصال فوري</button>
        </div>
      </div>
      <div class="case-card" style="opacity:0.7;">
        <div class="case-card-top">
          <div class="time-tag"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>منذ 4 ساعات</div>
          <div>
            <div class="case-title">اضطراب نوم حاد</div>
            <div class="case-badges"><span class="badge solved">تم الحل</span><span class="severity mid">متوسط</span></div>
          </div>
        </div>
        <div class="case-student">عمر حسن • STU-2024-033</div>
        <div class="case-desc">أرق شديد لمدة أسبوع كامل مع تأثير على الأداء الدراسي.</div>
        <div class="case-meta"><span>مع د. سارة أحمد</span><span>2026-02-24 • 03:30 م</span></div>
        <div class="case-actions">
          <button class="action-btn blue" onclick="location.href='../doctor/chat.php'"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>محادثة</button>
        </div>
      </div>
    </div>
    <div class="emergency-footer">
      <button class="call-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg></button>
      <div class="footer-text"><strong>خط الطوارئ النفسي</strong><span>للحالات الطارئة جداً، اتصل فوراً على: 920000000 (متاح 24/7)</span></div>
    </div>
  </div>
</body>

</html>