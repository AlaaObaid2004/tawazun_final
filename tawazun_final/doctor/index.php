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
$new_requests = 0;
$pending_requests = 0;
$completed_requests = 0;
$total_students = 0;

if (isset($conn)) {
  if (isset($_SESSION['user_id'])) {
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
  $res_new = $conn->query("SELECT COUNT(*) AS total FROM psychological_requests WHERE status = 'جديد'");
  if ($res_new) {
    $row_new = $res_new->fetch_assoc();
    $new_requests = $row_new['total'] ?? 0;
  }
  $res_pending = $conn->query("SELECT COUNT(*) AS total FROM psychological_requests WHERE status = 'قيد المراجعة'");
  if ($res_pending) {
    $row_pending = $res_pending->fetch_assoc();
    $pending_requests = $row_pending['total'] ?? 0;
  }
  $res_completed = $conn->query("SELECT COUNT(*) AS total FROM psychological_requests WHERE status = 'مكتملة'");
  if ($res_completed) {
    $row_completed = $res_completed->fetch_assoc();
    $completed_requests = $row_completed['total'] ?? 0;
  }
  $res_students = $conn->query("SELECT COUNT(*) AS total FROM register");
  if ($res_students) {
    $row_students = $res_students->fetch_assoc();
    $total_students = $row_students['total'] ?? 0;
  }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>منصة توازن - لوحة التحكم</title>
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
      --yellow: #F5A623;
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

    /* NAVBAR */
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
      gap: 8px;
    }

    .navbar-logo {
      width: 44px;
      height: 44px;
      object-fit: contain;
      border-radius: 8px;
    }

    .navbar-brand-text {
      text-align: right;
    }

    .navbar-brand-text .brand-name {
      font-size: 16px;
      font-weight: 800;
      color: var(--teal);
      line-height: 1.2;
    }

    .navbar-brand-text .brand-sub {
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
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      color: var(--teal);
    }

    .nav-profile svg {
      width: 16px;
      height: 16px;
    }

    .notif-btn {
      position: relative;
      background: none;
      border: none;
      cursor: pointer;
      padding: 6px;
    }

    .notif-btn svg {
      width: 22px;
      height: 22px;
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
    }

    .logout-btn svg {
      width: 20px;
      height: 20px;
    }

    /* HERO */
    .hero {
      background: linear-gradient(135deg, var(--teal) 0%, var(--teal-mid) 100%);
      color: white;
      padding: 28px 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 0;
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
      color: white;
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

    /* MAIN */
    .main {
      padding: 20px;
    }

    /* STATS GRID */
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
      box-shadow: 0 2px 8px rgba(45, 155, 138, 0.06);
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
      display: flex;
      align-items: center;
      gap: 2px;
    }

    .stat-trend svg {
      width: 12px;
      height: 12px;
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

    .stat-icon.orange {
      background: var(--orange);
    }

    .stat-icon.blue {
      background: var(--blue);
    }

    .stat-icon.purple {
      background: var(--purple);
    }

    .stat-icon.teal {
      background: var(--teal);
    }

    .stat-label {
      font-size: 12px;
      color: var(--text-mid);
      margin-bottom: 4px;
      text-align: left;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 800;
      color: var(--text-dark);
      text-align: left;
      line-height: 1;
    }

    .stat-sub {
      font-size: 11px;
      color: var(--text-light);
      margin-top: 4px;
      text-align: left;
    }

    /* SECTIONS */
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
      color: var(--teal);
      text-decoration: none;
      font-weight: 600;
    }

    /* REQUEST CARDS */
    .request-card {
      background: var(--white);
      border-radius: 12px;
      padding: 14px;
      border: 1px solid var(--border);
      margin-bottom: 10px;
      cursor: pointer;
      transition: box-shadow 0.2s, transform 0.2s;
    }

    .request-card:hover {
      box-shadow: 0 4px 16px rgba(45, 155, 138, 0.12);
      transform: translateY(-1px);
    }

    .request-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 8px;
    }

    .request-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--text-dark);
    }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      margin-left: 4px;
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

    .request-student {
      font-size: 12px;
      color: var(--text-mid);
      display: flex;
      align-items: center;
      gap: 4px;
      margin-bottom: 6px;
    }

    .request-student svg {
      width: 13px;
      height: 13px;
    }

    .request-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .request-tag {
      display: inline-block;
      background: var(--teal-light);
      color: var(--teal);
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 11px;
      font-weight: 600;
    }

    .request-time {
      font-size: 11px;
      color: var(--text-light);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .request-time svg {
      width: 12px;
      height: 12px;
    }

    /* SESSIONS */
    .sessions-list {
      background: var(--white);
      border-radius: 14px;
      border: 1px solid var(--border);
      overflow: hidden;
    }

    .session-item {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .session-item:last-child {
      border-bottom: none;
    }

    .session-name {
      font-size: 14px;
      font-weight: 700;
      color: var(--text-dark);
    }

    .session-type {
      font-size: 12px;
      color: var(--text-light);
      margin-top: 2px;
    }

    .session-time {
      font-size: 12px;
      color: var(--teal);
      font-weight: 600;
      text-align: left;
    }

    .session-date {
      font-size: 11px;
      color: var(--text-light);
      text-align: left;
    }

    /* QUICK ACTIONS */
    .quick-actions {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .btn-primary {
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
      transition: background 0.2s;
    }

    .btn-primary:hover {
      background: #1E8070;
    }

    .btn-secondary {
      width: 100%;
      background: var(--orange);
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
      transition: background 0.2s;
    }

    .btn-secondary:hover {
      background: #CC6A28;
    }

    .view-all-btn {
      width: 100%;
      background: none;
      border: 1.5px solid var(--teal);
      color: var(--teal);
      border-radius: 12px;
      padding: 13px;
      font-size: 14px;
      font-weight: 700;
      font-family: 'Tajawal', sans-serif;
      cursor: pointer;
      margin-top: 6px;
      transition: all 0.2s;
    }

    .view-all-btn:hover {
      background: var(--teal-light);
    }

    .svgg {
      width: 10px;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="navbar-actions">
      <a href="../auth/login.php">
      <button  class="logout-btn" >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
      </button>
      </a>
      <button class="notif-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="notif-dot"></span>
      </button>
      <div class="nav-profile">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        د. <?php echo htmlspecialchars($_SESSION['name'] ?? 'المستخدم'); ?>
      </div>
    </div>
    <div class="navbar-brand">
      <div class="navbar-brand-text">
        <div class="brand-name">منصة توازن</div>
        <div class="brand-sub">بوابة الأخصائيين</div>
      </div>
      <img src="../assets/images/logo.png" alt="توازن" class="navbar-logo">
    </div>
  </nav>

  <!-- HERO -->
  <div class="hero">
    <div class="hero-icon">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
    </div>
    <div class="hero-text">
      <h1>مرحباً، د.
        <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?> </h1>
    </div>
  </div>


  <!-- STATS -->
  <div class="main">

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon orange">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
        </div>
        <div class="stat-label">طلبات جديدة</div>
        <div class="stat-value"><?php echo $new_requests; ?></div>
      </div>

      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <div class="stat-label">قيد المراجعة</div>
        <div class="stat-value"><?php echo $pending_requests; ?></div>
      </div>

      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon purple">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </div>
        <div class="stat-label">مكتملة</div>
        <div class="stat-value"><?php echo $completed_requests; ?></div>
      </div>

      <div class="stat-card">
        <div class="stat-card-header">
          <div class="stat-icon teal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
          </div>
        </div>
        <div class="stat-label">إجمالي الطلاب</div>
        <div class="stat-value"><?php echo $total_students; ?></div>
      </div>
    </div>



  </div>

  <!-- RECENT REQUESTS -->
  <div class="section">
    <div class="section-header">
      <a href="requests.php" class="section-link">عرض الكل</a>
      <div class="section-title">الطلبات الأخيرة</div>
    </div>

    <?php
    $host = 'localhost';
    $dbname = 'tawazun_final_db';
    $username = 'root';
    $password = '';
    try {
      $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("فشل الاتصال: " . $e->getMessage());
    }
    $status_filter = $_GET['status'] ?? 'الكل';

    if ($status_filter !== 'الكل') {
      $sql = "SELECT * FROM psychological_requests WHERE status = :status ORDER BY created_at DESC";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([':status' => $status_filter]);
    } else {
      $sql = "SELECT * FROM psychological_requests ORDER BY created_at DESC";
      $stmt = $pdo->query($sql);
    }

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- واجهة العرض البرمجية داخل كود الـ HTML الخاص بالبطاقات (Cards) -->
    <div class="requests-container">
      <?php if (empty($requests)): ?>
        <p>لا توجد طلبات حالياً.</p>
      <?php else: ?>
        <?php foreach ($requests as $request): ?>
          <div class="request-card">
            <div class="card-header">
              <h3 class="rc-title request-title"><?php echo htmlspecialchars($request['title']); ?></h3>
              <span class="badge badge-new"><?php echo htmlspecialchars($request['status']); ?></span>
              <span class="badge badge-high" <?php echo $request['priority_level'] === 'عالية' ? 'high' : 'medium'; ?>">
                <span><?php echo htmlspecialchars($request['priority_level']); ?></span>
              </span>
            </div>

            <div class="card-body">
              <!-- كود فحص الخصوصية وإخفاء الهوية بناءً على اختيار الطالب -->
              <p class="student-name">
                <svg class="svgg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>

                <?php
                if ($request['is_anonymous'] == 1) {
                  echo "<span class='anonymous-text'>طالب مجهول</span>";
                } else {
                  echo htmlspecialchars($request['student_name']);
                }
                ?>
                <span class="problem-tag rc-student request-tag">
                  (<?php echo htmlspecialchars($request['problem_type']); ?>)</span>
              </p>

            </div>

            <div class="card-footer rc-time">
              <svg class="svgg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="date"><?php echo date('Y-m-d H:i', strtotime($request['created_at'])); ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>







      <button class="view-all-btn" onclick="location.href='requests.php'">عرض جميع الطلبات</button>
    </div>

    <!-- UPCOMING SESSIONS -->
    <div class="section">
      <div class="section-header">
        <a href="../admin/admin-sessions.php" class="section-link">عرض الكل</a>
        <div class="section-title">الجلسات القادمة</div>
      </div>
      <div class="sessions-list">
        <div class="session-item">
          <div>
            <div class="session-time">10:00 ص</div>
            <div class="session-date">2026-02-26</div>
          </div>
          <div style="text-align:right">
            <div class="session-name">سارة محمود</div>
            <div class="session-type">استشارة متابعة</div>
          </div>
        </div>
        <div class="session-item">
          <div>
            <div class="session-time">02:00 م</div>
            <div class="session-date">2026-02-26</div>
          </div>
          <div style="text-align:right">
            <div class="session-name">عمر حسن</div>
            <div class="session-type">جلسة علاجية</div>
          </div>
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="section">
      <div class="section-header">
        <div class="section-title">إجراءات سريعة</div>
      </div>
      <div class="quick-actions">
        <button class="btn-primary" onclick="location.href='requests.php'">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          الطلبات الجديدة
        </button>
        <button class="btn-secondary" onclick="location.href='chat.php'">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          قيد المراجعة
        </button>
      </div>
    </div>

  </div>
</body>

</html>