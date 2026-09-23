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
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>منصة توازن - لوحة المشرف</title>
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

    .hero {
      background: linear-gradient(135deg, var(--purple) 0%, var(--purple-mid) 100%);
      color: white;
      padding: 28px 20px;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .hero-icon {
      width: 52px;
      height: 52px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .hero-icon svg {
      width: 26px;
      height: 26px;
    }

    .hero-text h1 {
      font-size: 22px;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .hero-text p {
      font-size: 13px;
      opacity: 0.85;
    }

    .main {
      padding: 20px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--white);
      border-radius: 14px;
      padding: 16px;
      border: 1px solid var(--border);
      box-shadow: 0 2px 8px rgba(123, 47, 190, 0.06);
    }

    .stat-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .stat-trend {
      font-size: 11px;
      color: var(--green);
    }

    .stat-trend.urgent {
      color: var(--red);
    }

    .stat-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-icon svg {
      width: 20px;
      height: 20px;
      color: white;
    }

    .stat-icon.blue {
      background: var(--blue);
    }

    .stat-icon.purple {
      background: var(--purple);
    }

    .stat-icon.green {
      background: var(--green);
    }

    .stat-icon.red {
      background: var(--red);
    }

    .stat-label {
      font-size: 12px;
      color: var(--text-mid);
      margin-bottom: 4px;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 800;
      color: var(--text-dark);
      line-height: 1;
    }

    .stat-sub {
      font-size: 11px;
      color: var(--text-light);
      margin-top: 4px;
    }

    .section {
      margin-bottom: 24px;
    }

    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
    }

    .section-title {
      font-size: 17px;
      font-weight: 700;
      color: var(--text-dark);
    }

    .section-link {
      font-size: 13px;
      color: var(--purple);
      text-decoration: none;
      font-weight: 600;
    }

    .actions-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .action-card {
      border-radius: 14px;
      padding: 16px;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 6px;
      transition: transform 0.2s, box-shadow 0.2s;
      text-decoration: none;
    }

    .action-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .action-card.purple {
      background: var(--purple);
    }

    .action-card.blue {
      background: var(--blue);
    }

    .action-card.teal {
      background: var(--teal);
    }

    .action-card.red {
      background: var(--red);
    }

    .action-card.orange {
      background: var(--orange);
    }

    .action-card.dark {
      background: #2D3748;
    }

    .action-card svg {
      width: 22px;
      height: 22px;
      color: rgba(255, 255, 255, 0.8);
    }

    .action-card-title {
      font-size: 14px;
      font-weight: 700;
      color: white;
      text-align: right;
    }

    .action-card-sub {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.75);
      text-align: right;
    }

    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .activity-item {
      background: var(--white);
      border-radius: 12px;
      padding: 14px;
      border: 1px solid var(--border);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      justify-content: flex-end;
    }

    .activity-content {
      flex: 1;
      text-align: right;
    }

    .activity-title {
      font-size: 14px;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 2px;
    }

    .activity-sub {
      font-size: 12px;
      color: var(--text-mid);
      margin-bottom: 4px;
    }

    .activity-time {
      font-size: 11px;
      color: var(--text-light);
    }

    .activity-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .activity-icon svg {
      width: 18px;
      height: 18px;
      color: white;
    }

    .activity-icon.blue {
      background: var(--blue);
    }

    .activity-icon.red {
      background: var(--red);
    }

    .activity-icon.green {
      background: var(--green);
    }

    .activity-icon.purple {
      background: var(--purple);
    }

    .sessions-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .session-item {
      background: var(--white);
      border-radius: 12px;
      padding: 14px;
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .session-time {
      font-size: 16px;
      font-weight: 800;
      color: var(--purple);
    }

    .session-date {
      font-size: 11px;
      color: var(--text-light);
    }

    .session-name {
      font-size: 14px;
      font-weight: 700;
      text-align: right;
    }

    .session-type {
      font-size: 12px;
      color: var(--text-mid);
      text-align: right;
    }

    .session-doctor {
      font-size: 11px;
      color: var(--purple);
      font-weight: 600;
      text-align: right;
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <div class="navbar-actions">
       <a href="../auth/login.php">
      <button class="logout-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg></button>
        </a>
      <button class="notif-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg><span class="notif-dot"></span></button>
      <div class="nav-profile"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>أ.
        <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?>
      </div>
    </div>
    <div class="navbar-brand">
      <div>
        <div class="brand-name">منصة توازن</div>
        <div class="brand-sub">بوابة المشرفين</div>
      </div>
      <img src="../assets/images/logo.png" alt="توازن" class="navbar-logo">
    </div>
  </nav>
  <div class="hero">
    <div class="hero-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg></div>
    <div class="hero-text">
      <h1>مرحباً، أ. <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?>
      </h1>
      <p>لوحة التحكم الشاملة لإدارة منصة توازن</p>
    </div>
  </div>
  <div class="main">
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-trend">↑ +12%</div>
          <div class="stat-icon blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg></div>
        </div>
        <div class="stat-label">إجمالي الحالات</div>
        <div class="stat-value">248</div>
        <div class="stat-sub">من الشهر الماضي</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-trend">↑ +8%</div>
          <div class="stat-icon purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
        <div class="stat-label">الجلسات الشهرية</div>
        <div class="stat-value">156</div>
        <div class="stat-sub">من الشهر الماضي</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-trend">150+ جديد</div>
          <div class="stat-icon green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg></div>
        </div>
        <div class="stat-label">الطلاب النشطون</div>
        <div class="stat-value">189</div>
        <div class="stat-sub">طالب نشط</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-trend urgent">⚠ عاجل</div>
          <div class="stat-icon red"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg></div>
        </div>
        <div class="stat-label">حالات طارئة</div>
        <div class="stat-value">3</div>
        <div class="stat-sub">تحتاج متابعة فورية</div>
      </div>
    </div>
    <div class="section">
      <div class="section-title">الإجراءات السريعة</div>
      <div class="actions-grid">
        <a href="admin-sessions.php" class="action-card purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <div class="action-card-title">إدارة الجلسات</div>
          <div class="action-card-sub">جدولة ومتابعة الجلسات</div>
        </a>
        <a href="../doctor/requests.php" class="action-card blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <div class="action-card-title">إدارة الحالات</div>
          <div class="action-card-sub">عرض ومتابعة جميع الحالات</div>
        </a>
        <a href="../doctor/chat.php" class="action-card teal"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <div class="action-card-title">إدارة المحادثات</div>
          <div class="action-card-sub">مراقبة المحادثات النشطة</div>
        </a>
        <a href="admin-emergency.php" class="action-card red"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div class="action-card-title">الطوارئ النفسية</div>
          <div class="action-card-sub">حالات تحتاج متابعة عاجلة</div>
        </a>
        <a href="../doctor/index.php" class="action-card orange"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <div class="action-card-title">إدارة المستخدمين</div>
          <div class="action-card-sub">طلاب وأطباء</div>
        </a>
        <a href="admin-archive (1).php" class="action-card dark"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <div class="action-card-title">أرشيف الجلسات</div>
          <div class="action-card-sub">سجل الجلسات السابقة</div>
        </a>
      </div>
    </div>
    <div class="section">
      <div class="section-title">النشاطات الأخيرة</div>
      <div class="activity-list">
        <div class="activity-item">
          <div class="activity-content">
            <div class="activity-title">حالة جديدة من محمد أحمد</div>
            <div class="activity-sub">ضغوط الدراسة • أولوية عالية</div>
            <div class="activity-time">منذ 5 دقائق</div>
          </div>
          <div class="activity-icon blue"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg></div>
        </div>
        <div class="activity-item">
          <div class="activity-content">
            <div class="activity-title">حالة طارئة تحتاج متابعة</div>
            <div class="activity-sub">طالب مجهول • قلق شديد</div>
            <div class="activity-time">منذ 15 دقيقة</div>
          </div>
          <div class="activity-icon red"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg></div>
        </div>
        <div class="activity-item">
          <div class="activity-content">
            <div class="activity-title">جلسة مكتملة مع د. سارة أحمد</div>
            <div class="activity-sub">جلسة استشارية • فاطمة علي</div>
            <div class="activity-time">منذ ساعة</div>
          </div>
          <div class="activity-icon green"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg></div>
        </div>
        <div class="activity-item">
          <div class="activity-content">
            <div class="activity-title">جلسة جديدة محجوزة</div>
            <div class="activity-sub">10:00 ص • د. أحمد خالد</div>
            <div class="activity-time">منذ ساعتين</div>
          </div>
          <div class="activity-icon purple"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="section-header"><a href="admin-sessions.php" class="section-link">عرض الكل</a>
        <div class="section-title">الجلسات القادمة</div>
      </div>
      <div class="sessions-list">
        <div class="session-item">
          <div>
            <div class="session-time">10:00 ص</div>
            <div class="session-date">2026-02-26</div>
          </div>
          <div>
            <div class="session-name">سارة محمود</div>
            <div class="session-type">استشارة متابعة</div>
            <div class="session-doctor">د. سارة أحمد</div>
          </div>
        </div>
        <div class="session-item">
          <div>
            <div class="session-time">02:00 م</div>
            <div class="session-date">2026-02-26</div>
          </div>
          <div>
            <div class="session-name">عمر حسن</div>
            <div class="session-type">جلسة علاجية</div>
            <div class="session-doctor">د. أحمد خالد</div>
          </div>
        </div>
        <div class="session-item">
          <div>
            <div class="session-time">11:00 ص</div>
            <div class="session-date">2026-02-27</div>
          </div>
          <div>
            <div class="session-name">ليلى أحمد</div>
            <div class="session-type">جلسة أولية</div>
            <div class="session-doctor">د. سارة أحمد</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>