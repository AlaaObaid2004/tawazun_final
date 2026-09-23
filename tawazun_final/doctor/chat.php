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
  <title>منصة توازن - المحادثة</title>
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
      --text-dark: #1A2E2A;
      --text-mid: #4A6260;
      --text-light: #8AADA8;
      --bg: #F5FAFA;
      --white: #FFFFFF;
      --border: #D4EDEA;
      --red: #E05252;
      --green: #4CAF80;
      --bubble-me: #2D9B8A;
      --bubble-other: #FFFFFF;
    }

    body {
      font-family: 'Tajawal', sans-serif;
      background: var(--bg);
      color: var(--text-dark);
      direction: rtl;
      height: 100vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      max-width: 600px;
      margin: 0 auto;
      width: 100%;
    }

    /* NAVBAR */
    .navbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
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

    /* BACK BAR */
    .back-bar {
      padding: 10px 16px;
      background: var(--white);
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: flex-start;
      flex-shrink: 0;
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

    /* CHAT HEADER */
    .chat-header {
      background: var(--teal);
      padding: 14px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }

    .chat-header-left {
      display: flex;
      gap: 14px;
    }

    .chat-action-btn {
      background: rgba(255, 255, 255, 0.15);
      border: none;
      border-radius: 8px;
      padding: 8px;
      cursor: pointer;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }

    .chat-action-btn:hover {
      background: rgba(255, 255, 255, 0.25);
    }

    .chat-action-btn svg {
      width: 18px;
      height: 18px;
    }

    .chat-more-btn {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      padding: 4px;
    }

    .chat-more-btn svg {
      width: 22px;
      height: 22px;
    }

    .chat-user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .chat-avatar {
      width: 44px;
      height: 44px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .chat-avatar svg {
      width: 22px;
      height: 22px;
      color: white;
    }

    .online-dot {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 10px;
      height: 10px;
      background: #4CAF80;
      border-radius: 50%;
      border: 2px solid var(--teal);
    }

    .chat-user-name {
      font-size: 15px;
      font-weight: 700;
      color: white;
    }

    .chat-user-id {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.75);
    }

    .chat-user-status {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.9);
      margin-top: 1px;
    }

    /* MESSAGES AREA */
    .messages-area {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      scroll-behavior: smooth;
    }

    .messages-area::-webkit-scrollbar {
      width: 4px;
    }

    .messages-area::-webkit-scrollbar-track {
      background: transparent;
    }

    .messages-area::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 4px;
    }

    .msg {
      display: flex;
      gap: 10px;
      max-width: 80%;
    }

    .msg.incoming {
      align-self: flex-end;
      flex-direction: row-reverse;
    }

    .msg.outgoing {
      align-self: flex-start;
    }

    .msg-bubble {
      padding: 12px 16px;
      border-radius: 18px;
      font-size: 14px;
      line-height: 1.6;
      position: relative;
    }

    .msg.incoming .msg-bubble {
      background: var(--teal);
      color: white;
      border-bottom-right-radius: 4px;
    }

    .msg.outgoing .msg-bubble {
      background: var(--white);
      color: var(--text-dark);
      border: 1px solid var(--border);
      border-bottom-left-radius: 4px;
      box-shadow: 0 2px 6px rgba(45, 155, 138, 0.08);
    }

    .msg-time {
      font-size: 11px;
      margin-top: 4px;
      color: var(--text-light);
      align-self: flex-end;
      flex-shrink: 0;
    }

    /* INPUT BAR */
    .input-bar {
      background: var(--white);
      border-top: 1px solid var(--border);
      padding: 12px 16px;
      flex-shrink: 0;
    }

    .input-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .send-btn {
      width: 44px;
      height: 44px;
      background: var(--teal);
      border: none;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: background 0.2s;
    }

    .send-btn:hover {
      background: #1E8070;
    }

    .send-btn svg {
      width: 18px;
      height: 18px;
      color: white;
    }

    .input-wrap {
      flex: 1;
      background: var(--bg);
      border: 1.5px solid var(--border);
      border-radius: 24px;
      display: flex;
      align-items: center;
      padding: 0 12px;
      gap: 8px;
    }

    .input-wrap textarea {
      flex: 1;
      border: none;
      background: none;
      outline: none;
      font-family: 'Tajawal', sans-serif;
      font-size: 14px;
      color: var(--text-dark);
      resize: none;
      max-height: 80px;
      padding: 10px 0;
      line-height: 1.5;
      text-align: right;
    }

    .input-wrap textarea::placeholder {
      color: var(--text-light);
    }

    .input-icon-btn {
      background: none;
      border: none;
      cursor: pointer;
      padding: 4px;
      color: var(--text-light);
    }

    .input-icon-btn svg {
      width: 20px;
      height: 20px;
    }

    .input-hint {
      font-size: 11px;
      color: var(--text-light);
      text-align: center;
      margin-top: 6px;
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
        د. <?php echo htmlspecialchars($_SESSION['name'] ?? 'الأدمن'); ?>
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
  <div class="back-bar" style="display:flex;gap:16px;">
    <a href="request-detail.php" class="back-link" style="color:var(--teal);">
      تفاصيل الحالة
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg>
    </a>
    <a href="requests.php" class="back-link" style="color:var(--teal);">
      الطلبات
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg>
    </a>
    <a href="index.php" class="back-link">
      العودة إلى لوحة التحكم
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
      </svg>
    </a>
  </div>

  <!-- CHAT HEADER -->
  <div class="chat-header">
    <div class="chat-header-left">
      <button class="chat-action-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
        </svg>
      </button>
      <button class="chat-action-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.262a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
        </svg>
      </button>
      <button class="chat-action-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg>
      </button>
    </div>
    <div class="chat-user-info">
      <div style="text-align:right">
        <div class="chat-user-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'المستخدم'); ?></div>
        <div class="chat-user-id">STU-2024-001</div>
        <div class="chat-user-status">متصل</div>
      </div>
      <div class="chat-avatar">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <div class="online-dot"></div>
      </div>
    </div>
  </div>

  <!-- MESSAGES -->
  <div class="messages-area" id="messagesArea">

    <div class="msg outgoing">
      <div class="msg-time">10:30 ص</div>
      <div class="msg-bubble">مرحباً دكتورة، شكراً لردك على طلبي.</div>
    </div>

    <div class="msg incoming">
      <div class="msg-time">10:32 ص</div>
      <div class="msg-bubble">أهلاً محمد، يسعدني مساعدتك. كيف تشعر اليوم؟ هل بدأت بممارسة تمارين التنفس التي أرسلتها لك؟</div>
    </div>

    <div class="msg outgoing">
      <div class="msg-time">10:35 ص</div>
      <div class="msg-bubble">نعم دكتورة، بدأت بممارسة التمارين. أشعر ببعض التحسن، لكن لا زال لدي بعض القلق.</div>
    </div>

    <div class="msg incoming">
      <div class="msg-time">10:37 ص</div>
      <div class="msg-bubble">هذا جيد جداً! التحسن التدريجي هو ما نهدف إليه. القلق الذي تشعر به طبيعي، وسنعمل معاً على تقليله. أخبرني، ما هي المواقف التي تثير قلقك أكثر؟</div>
    </div>

  </div>

  <!-- INPUT BAR -->
  <div class="input-bar">
    <div class="input-row">
      <button class="send-btn" onclick="sendMessage()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
        </svg>
      </button>
      <div class="input-wrap">
        <button class="input-icon-btn">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </button>
        <button class="input-icon-btn">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
          </svg>
        </button>
        <button class="input-icon-btn">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
        <textarea id="msgInput" placeholder="اكتب رسالتك للطالب..." rows="1"
          onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
      </div>
    </div>
    <div class="input-hint">اضغط Enter للإرسال • Shift + Enter لسطر جديد</div>
  </div>

  <script>
    function sendMessage() {
      const input = document.getElementById('msgInput');
      const text = input.value.trim();
      if (!text) return;

      const area = document.getElementById('messagesArea');
      const now = new Date();
      const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + (now.getHours() < 12 ? ' ص' : ' م');

      const msg = document.createElement('div');
      msg.className = 'msg incoming';
      msg.innerHTML = `<div class="msg-time">${time}</div><div class="msg-bubble">${text}</div>`;
      area.appendChild(msg);

      input.value = '';
      input.style.height = 'auto';
      area.scrollTop = area.scrollHeight;
    }

    function handleKey(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    }

    function autoResize(el) {
      el.style.height = 'auto';
      el.style.height = Math.min(el.scrollHeight, 80) + 'px';
    }
  </script>
</body>

</html>