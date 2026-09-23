<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (file_exists('../config/db_connection.php')) {
  require_once '../config/db_connection.php';
}
if (isset($_SESSION['user_id']) && isset($conn)) {
  $user_id = $_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT name FROM doctor_login WHERE id = ?");
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
  <title>منصة توازن - تفاصيل الطلب</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
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
    }

    body {
      font-family: 'Tajawal', sans-serif;
      background: var(--bg);
      color: var(--text-dark);
      direction: rtl;
      padding-bottom: 30px;
      padding-left: 16px;
      padding-right: 16px;
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
      box-shadow: 0 2px 8px rgba(45, 155, 138, 0.08);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar-logo {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .brand-name {
      font-size: 16px;
      font-weight: 800;
      color: var(--teal);
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
      padding: 6px 12px;
      font-size: 13px;
      font-weight: 600;
      color: var(--teal);
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
      padding: 12px 20px;
      background: var(--white);
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: flex-start;
    }

    .back-link {
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--teal);
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
    }

    .main {
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .card {
      background: var(--white);
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 18px;
      box-shadow: 0 2px 8px rgba(45, 155, 138, 0.05);
    }

    .card-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: flex-end;
    }

    .card-title svg {
      width: 18px;
      height: 18px;
      color: var(--teal);
    }

    .req-header {}

    .req-title {
      font-size: 22px;
      font-weight: 800;
      color: var(--text-dark);
      text-align: right;
      margin-bottom: 10px;
    }

    .badges {
      display: flex;
      gap: 6px;
      justify-content: flex-end;
      margin-bottom: 10px;
      flex-wrap: wrap;
    }

    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .badge-new {
      background: #E3F0FF;
      color: #2D6BB5;
    }

    .badge-high {
      background: #FFE8E8;
      color: #C0392B;
    }

    .badge-review {
      background: #F0F0FF;
      color: #6B52A8;
    }

    .badge-topic {
      background: var(--teal-light);
      color: var(--teal);
    }

    .req-meta {
      font-size: 12px;
      color: var(--text-light);
      display: flex;
      align-items: center;
      gap: 6px;
      justify-content: flex-end;
    }

    .req-meta svg {
      width: 13px;
      height: 13px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .info-item {}

    .info-label {
      font-size: 11px;
      color: var(--text-light);
      margin-bottom: 3px;
    }

    .info-value {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-dark);
    }

    .desc-text {
      font-size: 14px;
      color: var(--text-mid);
      line-height: 1.8;
      text-align: right;
    }

    .reply-textarea {
      width: 100%;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 14px;
      font-family: 'Tajawal', sans-serif;
      font-size: 14px;
      color: var(--text-dark);
      resize: vertical;
      min-height: 120px;
      outline: none;
      text-align: right;
      background: var(--bg);
      transition: border-color 0.2s;
    }

    .reply-textarea:focus {
      border-color: var(--teal);
      background: var(--white);
    }

    .reply-textarea::placeholder {
      color: var(--text-light);
    }

    .checkboxes-title {
      font-size: 13px;
      color: var(--text-mid);
      margin: 12px 0 8px;
      text-align: right;
    }

    .checkbox-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 0;
      cursor: pointer;
      flex-direction: row-reverse;
      border-bottom: 1px solid var(--border);
    }

    .checkbox-item:last-child {
      border-bottom: none;
    }

    .checkbox-item input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: var(--teal);
      cursor: pointer;
    }

    .checkbox-item label {
      font-size: 14px;
      color: var(--text-dark);
      cursor: pointer;
      flex: 1;
      text-align: right;
    }

    .btn-row {
      display: flex;
      gap: 10px;
      margin-top: 14px;
    }

    .btn-send {
      flex: 1;
      background: var(--teal);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 14px;
      font-size: 15px;
      font-weight: 700;
      font-family: 'Tajawal', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background 0.2s;
    }

    .btn-send:hover {
      background: #1E8070;
    }

    .btn-draft {
      background: var(--white);
      color: var(--text-mid);
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 14px 18px;
      font-size: 14px;
      font-weight: 600;
      font-family: 'Tajawal', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }

    .btn-draft:hover {
      border-color: var(--teal);
      color: var(--teal);
    }

    .btn-chat {
      width: 100%;
      background: var(--teal);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 15px;
      font-size: 15px;
      font-weight: 700;
      font-family: 'Tajawal', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 10px;
      transition: background 0.2s;
    }

    .btn-chat:hover {
      background: #1E8070;
    }

    .btn-attach {
      width: 100%;
      background: var(--white);
      color: var(--text-mid);
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 14px;
      font-size: 14px;
      font-weight: 600;
      font-family: 'Tajawal', sans-serif;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s;
    }

    .btn-attach:hover {
      border-color: var(--teal);
      color: var(--teal);
    }

    .prev-req {}

    .prev-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
    }

    .prev-item:last-child {
      border-bottom: none;
    }

    .prev-name {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-dark);
    }

    .prev-date {
      font-size: 12px;
      color: var(--text-light);
      margin-top: 2px;
    }

    .badge-done {
      background: #E8F8F0;
      color: var(--teal);
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }

    .notice {
      background: var(--teal-light);
      border-radius: 10px;
      padding: 12px 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: var(--text-mid);
      margin-top: 4px;
    }

    .notice svg {
      width: 16px;
      height: 16px;
      color: var(--teal);
      flex-shrink: 0;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
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
        <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?>
      </div>
    </div>
    <div class="navbar-brand">
      <div>
        <div class="brand-name">منصة توازن</div>
        <div class="brand-sub">بوابة الأخصائيين</div>
      </div>
      <img src="../assets/images/logo.png" alt="توازن" class="navbar-logo">
    </div>
  </nav>

  <!-- BACK -->
  <div class="back-bar">
    <a href="requests.php" class="back-link">
      العودة إلى الطلبات
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg>
    </a>
  </div>

  <div class="main">

    <!-- REQUEST TITLE CARD -->
    <div class="card req-header">
      <div class="req-title">ضغوط الدراسة الشديدة</div>
      <div class="badges">
        <span class="badge badge-topic">ضغوط الدراسة</span>
        <span class="badge badge-review">قيد المراجعة</span>
        <span class="badge badge-high">أولوية عالية</span>
      </div>
      <div class="req-meta">
        <span>10:30 ص</span>
        <span>•</span>
        <span>2026-02-25</span>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
    </div>

    <!-- STUDENT INFO -->
    <div class="card">
      <div class="card-title">
        معلومات الطالب
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <div class="info-grid">
        <div class="info-item">
          <div class="info-label">الاسم</div>
          <div class="info-value">محمد أحمد</div>
        </div>
        <div class="info-item">
          <div class="info-label">الرقم الجامعي</div>
          <div class="info-value">STU-2024-001</div>
        </div>
        <div class="info-item" style="grid-column:1/-1">
          <div class="info-label">الطلبات السابقة</div>
          <div class="info-value">2 طلبات</div>
        </div>
      </div>
    </div>

    <!-- CASE DESCRIPTION -->
    <div class="card">
      <div class="card-title">
        وصف الحالة
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <p class="desc-text">
        أشعر بضغط شديد بسبب كثرة الواجبات والامتحانات. لا أستطيع التركيز بشكل جيد وأشعر بالقلق المستمر من عدم قدرتي على إنهاء كل المطلوب مني. أحياناً أجد صعوبة في النوم بسبب التفكير الزائد في الدراسة. أشعر بالإرهاق المستمر وفقدان الحماس للدراسة. كما أنني بدأت أتجنب الأنشطة الاجتماعية لأنني أشعر بأنني لا أملك الوقت الكافي.
      </p>
    </div>

    <!-- REPLY -->
    <div class="card">
      <div class="card-title">
        الرد على الطالب
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
      </div>
      <textarea class="reply-textarea" id="replyText" placeholder="اكتب ردك وتوصياتك للطالب..."></textarea>

      <div class="checkboxes-title">التوصيات العلاجية</div>
      <div class="checkbox-item">
        <input type="checkbox" id="c1">
        <label for="c1">تمرين التنفس العميق (10 دقائق يومياً)</label>
      </div>
      <div class="checkbox-item">
        <input type="checkbox" id="c2">
        <label for="c2">جدولة الوقت بفعالية</label>
      </div>
      <div class="checkbox-item">
        <input type="checkbox" id="c3">
        <label for="c3">تمارين اليقظة الذهنية</label>
      </div>
      <div class="checkbox-item">
        <input type="checkbox" id="c4">
        <label for="c4">تحديد جلسة متابعة</label>
      </div>

      <div class="btn-row">
        <button class="btn-draft">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
          </svg>
          حفظ مسودة
        </button>
        <button class="btn-send" onclick="sendReply()">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
          إرسال الرد
        </button>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="card">
      <div class="card-title">
        إجراءات سريعة
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
      </div>
      <button class="btn-chat" onclick="location.href='chat.php'">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        المحادثة مع الطالب
      </button>
      <button class="btn-attach">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
        </svg>
        إرفاق ملفات
      </button>
    </div>

    <!-- PREVIOUS REQUESTS -->
    <div class="card">
      <div class="card-title">
        الطلبات السابقة
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div class="prev-req">
        <div class="prev-item">
          <span class="badge-done">مكتملة</span>
          <div style="text-align:right">
            <div class="prev-name">قلق الامتحانات</div>
            <div class="prev-date">2026-01-15</div>
          </div>
        </div>
        <div class="prev-item">
          <span class="badge-done">مكتملة</span>
          <div style="text-align:right">
            <div class="prev-name">صعوبة التركيز</div>
            <div class="prev-date">2025-12-10</div>
          </div>
        </div>
      </div>
    </div>

    <!-- NOTICE -->
    <div class="notice">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      في الحالات الطارئة، يرجى التواصل مع فريق الدعم الفوري على الرقم 920000000
    </div>

  </div>

  <script>
    function sendReply() {
      const text = document.getElementById('replyText').value.trim();
      if (!text) {
        alert('يرجى كتابة رد للطالب أولاً');
        return;
      }
      alert('تم إرسال الرد بنجاح!');
      document.getElementById('replyText').value = '';
    }
  </script>
</body>

</html>