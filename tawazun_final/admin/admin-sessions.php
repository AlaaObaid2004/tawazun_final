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
  <title>منصة توازن - إدارة الجلسات</title>
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

    .new-btn {
      background: var(--purple);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 10px 16px;
      font-family: 'Tajawal', sans-serif;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .main {
      padding: 16px;
    }

    .stats-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 16px;
    }

    .mini-stat {
      background: var(--white);
      border-radius: 12px;
      padding: 12px;
      border: 1px solid var(--border);
      text-align: center;
    }

    .mini-stat-val {
      font-size: 22px;
      font-weight: 800;
    }

    .mini-stat-val.blue {
      color: var(--blue);
    }

    .mini-stat-val.purple {
      color: var(--purple);
    }

    .mini-stat-val.green {
      color: var(--green);
    }

    .mini-stat-val.red {
      color: var(--red);
    }

    .mini-stat-label {
      font-size: 11px;
      color: var(--text-light);
      margin-top: 2px;
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
      background: var(--purple);
      color: white;
      border-color: var(--purple);
    }

    .search-box {
      background: var(--white);
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 10px 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
    }

    .search-box input {
      flex: 1;
      border: none;
      outline: none;
      font-family: 'Tajawal', sans-serif;
      font-size: 14px;
      color: var(--text-dark);
      background: none;
      text-align: right;
    }

    .search-box svg {
      width: 18px;
      height: 18px;
      color: var(--text-light);
      flex-shrink: 0;
    }

    .sessions-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .session-card {
      background: var(--white);
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 16px;
      box-shadow: 0 2px 6px rgba(123, 47, 190, 0.04);
    }

    .session-card-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .session-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--text-dark);
      text-align: right;
    }

    .badge {
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
    }

    .badge.booked {
      background: #EEF2FF;
      color: #4F46E5;
    }

    .badge.done {
      background: #DCFCE7;
      color: #16A34A;
    }

    .badge.live {
      background: #FEF9C3;
      color: #CA8A04;
    }

    .badge.cancelled {
      background: #FEE2E2;
      color: #DC2626;
    }

    .session-meta {
      display: flex;
      flex-direction: column;
      gap: 4px;
      margin-bottom: 10px;
    }

    .session-meta-row {
      display: flex;
      align-items: center;
      gap: 6px;
      justify-content: flex-end;
      font-size: 12px;
      color: var(--text-mid);
    }

    .session-meta-row svg {
      width: 14px;
      height: 14px;
      color: var(--text-light);
      flex-shrink: 0;
    }

    .session-desc {
      font-size: 12px;
      color: var(--text-light);
      text-align: right;
      margin-bottom: 10px;
      border-right: 3px solid var(--purple-light);
      padding-right: 8px;
    }

    .session-actions {
      display: flex;
      gap: 8px;
      justify-content: flex-end;
    }

    .act-btn {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      border: 1.5px solid var(--border);
      background: var(--white);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .act-btn.cancel {
      border-color: #FCA5A5;
      background: #FEF2F2;
    }

    .act-btn svg {
      width: 16px;
      height: 16px;
      color: var(--text-mid);
    }

    .act-btn.cancel svg {
      color: var(--red);
    }

    .calendar-btn {
      width: 100%;
      background: var(--white);
      border: 1.5px solid var(--purple);
      color: var(--purple);
      border-radius: 12px;
      padding: 12px;
      font-family: 'Tajawal', sans-serif;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 16px;
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
  <div class="back-bar"><a href="admin-dashboard.php" class="back-link">العودة إلى لوحة التحكم <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg></a></div>
  <div class="page-header">
    <div class="page-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg></div>
    <div>
      <div class="page-title">إدارة الجلسات</div>
      <div class="page-sub">جدولة ومتابعة جميع الجلسات الاستشارية والعلاجية</div>
    </div>
  </div>
  <div class="main">
    <div style="display:flex;justify-content:flex-start;margin-bottom:16px;">
      <button class="new-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>+ جلسة جديدة</button>
    </div>
    <div class="stats-row">
      <div class="mini-stat">
        <div class="mini-stat-val blue">3</div>
        <div class="mini-stat-label">جلسات محجوزة</div>
      </div>
      <div class="mini-stat">
        <div class="mini-stat-val green">1</div>
        <div class="mini-stat-label">جلسات مكتملة</div>
      </div>
      <div class="mini-stat">
        <div class="mini-stat-val purple">1</div>
        <div class="mini-stat-label">جلسات جارية</div>
      </div>
      <div class="mini-stat">
        <div class="mini-stat-val red">1</div>
        <div class="mini-stat-label">جلسات ملغاة</div>
      </div>
    </div>
    <div class="tabs">
      <button class="tab active">الكل (6)</button>
      <button class="tab">محجوزة (3)</button>
      <button class="tab">جارية (1)</button>
      <button class="tab">مكتملة (1)</button>
    </div>
    <div class="search-box"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg><input type="text" placeholder="ابحث عن جلسة أو طالب أو طبيب..."></div>
    <div class="sessions-list">
      <div class="session-card">
        <div class="session-card-header"><span class="badge booked">محجوزة</span>
          <div class="session-title">استشارة متابعة</div>
        </div>
        <div class="session-meta">
          <div class="session-meta-row"><span>سارة محمود • STU-2024-001</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>مع د. سارة أحمد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>2026-02-26 • 10:00 ص • 45 دقيقة • عن بُعد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
        <div class="session-desc">جلسة متابعة النفسية للحالة بعد الجلسة الأولية</div>
        <div class="session-actions"><button class="act-btn cancel"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg></button><button class="act-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg></button></div>
      </div>
      <div class="session-card">
        <div class="session-card-header"><span class="badge booked">محجوزة</span>
          <div class="session-title">جلسة علاجية</div>
        </div>
        <div class="session-meta">
          <div class="session-meta-row"><span>عمر حسن • STU-2024-002</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>مع د. أحمد خالد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>2026-02-26 • 02:00 م • 60 دقيقة • في المكتب</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
        <div class="session-desc">جلسة علاج معرفي سلوكي</div>
        <div class="session-actions"><button class="act-btn cancel"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg></button><button class="act-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg></button></div>
      </div>
      <div class="session-card">
        <div class="session-card-header"><span class="badge done">مكتملة</span>
          <div class="session-title">جلسة استشارية</div>
        </div>
        <div class="session-meta">
          <div class="session-meta-row"><span>محمد أحمد • STU-2024-004</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>مع د. فاطمة علي</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>2026-02-25 • 09:00 ص • 45 دقيقة • عن بُعد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
        <div class="session-desc">تم مناقشة التقدم في التعامل مع القلق</div>
        <div class="session-actions"><button class="act-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg></button></div>
      </div>
      <div class="session-card" style="border-color:#FEF9C3;background:#FFFDF0;">
        <div class="session-card-header"><span class="badge live">جارية الآن</span>
          <div class="session-title">جلسة علاجية</div>
        </div>
        <div class="session-meta">
          <div class="session-meta-row"><span>فاطمة سالم • STU-2024-005</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>مع د. أحمد خالد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>2026-02-25 • 03:00 م • 60 دقيقة • في المكتب</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
        <div class="session-actions"><button class="act-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg></button></div>
      </div>
      <div class="session-card">
        <div class="session-card-header"><span class="badge cancelled">ملغاة</span>
          <div class="session-title">استشارة متابعة</div>
        </div>
        <div class="session-meta">
          <div class="session-meta-row"><span>خالد محمد • STU-2024-006</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>مع د. سارة أحمد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>
          <div class="session-meta-row"><span>2026-02-24 • 01:00 م • 45 دقيقة • عن بُعد</span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg></div>
        </div>
        <div class="session-desc">أُلغيت بطلب من الطالب</div>
        <div class="session-actions"><button class="act-btn"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg></button></div>
      </div>
    </div>
    <button class="calendar-btn" onclick="location.href='admin-archive (1).php'">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      عرض التقويم
    </button>
  </div>
</body>

</html>