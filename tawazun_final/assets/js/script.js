// دالة بسيطة للتعامل مع النقر على الإجراءات السريعة
function handleAction(actionName) {
    if (actionName === 'إرسال مشكلة') {
        window.location.href = 'submit_request.php';
    } else if (actionName === 'طلباتي') {
        window.location.href = 'my_requests.php';
    } else if (actionName === 'مهامي العلاجية') {
        window.location.href = 'tasks.php';
    } else if (actionName === 'إرشادات نفسية') {
        window.location.href = 'guidance.php';
    } else if (actionName === 'محادثة مع الطبيب') {
        window.location.href = 'chat.php';
    } else {
        alert('قمت باختيار: ' + actionName);
    }
}

// إضافة مستمعي أحداث للعناصر الأخرى إذا لزم الأمر
document.addEventListener('DOMContentLoaded', () => {
    // التفاعل مع زر الإشعارات
    const notificationBtn = document.querySelector('.notification-btn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', () => {
            alert('لا توجد إشعارات جديدة حالياً.');
        });
    }

    // التفاعل مع زر تسجيل الخروج
    const logoutBtns = document.querySelectorAll('.user-area .icon-btn[aria-label="تسجيل الخروج"]');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault(); // To prevent inline onclick if any
            const confirmLogout = confirm('هل تريد تسجيل الخروج؟');
            if (confirmLogout) {
                window.location.href = '../auth/login.php';
            }
        });
    });

    // التفاعل مع موارد مفيدة
    const resources = document.querySelectorAll('.resource-item');
    resources.forEach(resource => {
        resource.addEventListener('click', function() {
            const title = this.querySelector('h4').innerText;
            console.log('تم النقر على مورد: ' + title);
            // منطق لفتح المورد
        });
    });
});
