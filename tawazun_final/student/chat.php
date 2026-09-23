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
    <title>محادثة مع الطبيب - منصة توازن</title>
    <!-- خط تجوال للغة العربية -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- مكتبة الأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <div class="container chat-page-container">

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
                    <span class="user-name"> <?php echo htmlspecialchars($_SESSION['name'] ?? 'المستخدم'); ?></span>
                    <i class="fa-regular fa-user user-avatar"></i>
                </div>
                <button class="icon-btn notification-btn" aria-label="الإشعارات">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
            </div>
        </header>

        <div class="page-navigation">
            <a href="index.php" class="back-link">
                <i class="fa-solid fa-arrow-right"></i> العودة إلى لوحة التحكم
            </a>
        </div>

        <!-- واجهة المحادثة -->
        <div class="chat-wrapper">
            <!-- رأس المحادثة -->
            <div class="chat-header">
                <div class="chat-header-right">
                    <div class="chat-avatar-wrapper">
                        <i class="fa-regular fa-user"></i>
                        <span class="online-dot"></span>
                    </div>
                    <div class="chat-user-details">
                        <h3>د. سارة أحمد</h3>
                        <p>أخصائية نفسية - متصل</p>
                    </div>
                </div>
                <div class="chat-header-left">
                    <button class="chat-icon-btn"><i class="fa-solid fa-phone"></i></button>
                    <button class="chat-icon-btn"><i class="fa-solid fa-video"></i></button>
                    <button class="chat-icon-btn"><i class="fa-regular fa-file-lines"></i></button>
                    <button class="chat-icon-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <!-- رسائل المحادثة -->
            <div class="chat-messages" id="chatMessagesContainer">

                <!-- رسالتي (الطالب) -->
                <div class="message-row my-message-row">
                    <div class="message-bubble my-message">
                        <p>مرحباً دكتورة، شكراً لردك على طلبي.</p>
                    </div>
                    <span class="message-time">10:30 ص</span>
                </div>

                <!-- رسالة الطبيب -->
                <div class="message-row doctor-message-row">
                    <div class="message-bubble doctor-message">
                        <p>أهلاً محمد، يسعدني مساعدتك. كيف تشعر اليوم؟ هل بدأت بممارسة تمارين التنفس التي أرسلتها لك؟</p>
                    </div>
                    <span class="message-time">10:32 ص</span>
                </div>

                <!-- رسالتي (الطالب) -->
                <div class="message-row my-message-row">
                    <div class="message-bubble my-message">
                        <p>نعم دكتورة، بدأت بممارسة التمارين. أشعر ببعض التحسن، لكن لا زال لدي بعض القلق.</p>
                    </div>
                    <span class="message-time">10:35 ص</span>
                </div>

                <!-- رسالة الطبيب -->
                <div class="message-row doctor-message-row">
                    <div class="message-bubble doctor-message">
                        <p>هذا جيد جداً! التحسن التدريجي هو ما نهدف إليه. القلق الذي تشعر به طبيعي، وسنعمل معاً على تقليله. أخبرني، ما هي المواقف التي تثير قلقك أكثر؟</p>
                    </div>
                </div>

            </div>

            <!-- منطقة إدخال الرسالة -->
            <div class="chat-input-area">
                <div class="chat-input-wrapper">
                    <input type="text" placeholder="اكتب رسالتك للطبيب..." class="chat-input" id="chatInputField">
                    <div class="chat-input-actions">
                        <button><i class="fa-regular fa-face-smile"></i></button>
                        <button><i class="fa-solid fa-paperclip"></i></button>
                        <button><i class="fa-regular fa-image"></i></button>
                    </div>
                </div>
                <button class="chat-send-btn" id="sendMsgBtn"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
            <div class="chat-hint">
                اضغط Enter للإرسال • Shift + Enter لسطر جديد
            </div>
        </div>

    </div>

    <script src="../assets/js/script.js"></script>

    <!-- كود إرسال الرسائل التفاعلي والرد الشرطي التلقائي -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatInput = document.getElementById('chatInputField');
            const sendBtn = document.getElementById('sendMsgBtn');
            const chatMessages = document.getElementById('chatMessagesContainer');

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            scrollToBottom();

            function handleSendMessage() {
                const messageText = chatInput.value.trim();
                if (messageText === '') return;
                const now = new Date();
                let hours = now.getHours();
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const ampm = hours >= 12 ? 'م' : 'ص';
                hours = hours % 12;
                hours = hours ? hours : 12;
                const currentTimeStr = `${hours}:${minutes} ${ampm}`;
                const userRow = document.createElement('div');
                userRow.className = 'message-row my-message-row';
                userRow.innerHTML = `
                <div class="message-bubble my-message">
                    <p>${escapeHtml(messageText)}</p>
                </div>
                <span class="message-time">${currentTimeStr}</span>
            `;
                chatMessages.appendChild(userRow);
                chatInput.value = '';
                scrollToBottom();
                setTimeout(() => {
                    const doctorRow = document.createElement('div');
                    doctorRow.className = 'message-row doctor-message-row';
                    doctorRow.innerHTML = `
                    <div class="message-bubble doctor-message">
                        <p>سأتواصل معك في أقرب وقت ممكن.</p>
                    </div>
                    <span class="message-time">${currentTimeStr}</span>
                `;
                    chatMessages.appendChild(doctorRow);
                    scrollToBottom();
                }, 1000);
            }

            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
            sendBtn.addEventListener('click', handleSendMessage);
            chatInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    handleSendMessage();
                }
            });
        });
    </script>
</body>

</html>