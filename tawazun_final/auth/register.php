<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>توازن – إنشاء حساب جديد</title>
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet" />
</head>

<body>

  <div class="logo-wrap">
    <div class="logo-box">
      <img src="../assets/images/logo.png" alt="شعار توازن" style="width: 100%; height: 100%; object-fit: contain;" />
    </div>
  </div>

  <div class="card" role="main">
    <h1 class="card-title">إنشاء حساب جديد</h1>
    <p class="card-sub">انضم إلى منصة توازن للصحة النفسية</p>

    <form id="regForm" method="POST">
      <div class="form-grid">

        <div class="field full">
          <label for="fullName">الاسم الكامل</label>
          <div class="input-wrap">
            <input type="text" id="fullName" name="fullName" placeholder="أدخل اسمك الكامل" autocomplete="name" />
            <span class="icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
              </svg>
            </span>
          </div>
          <span class="error-msg" id="fullName-err"></span>
        </div>

        <div class="field">
          <label for="email">البريد الإلكتروني</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="example@mail.com" autocomplete="email" style="direction:ltr;text-align:right;" />
            <span class="icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16" rx="2" />
                <path d="m2 7 10 7 10-7" />
              </svg>
            </span>
          </div>
          <span class="error-msg" id="email-err"></span>
        </div>

        <div class="field">
          <label for="phone">رقم الهاتف</label>
          <div class="input-wrap">
            <input type="tel" id="phone" name="phone" placeholder="05xxxxxxxx" autocomplete="tel" style="direction:ltr;text-align:right;" />
            <span class="icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.63 12 19.79 19.79 0 0 1 1.58 3.18 2 2 0 0 1 3.57 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6.72 6.72l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
              </svg>
            </span>
          </div>
          <span class="error-msg" id="phone-err"></span>
        </div>

        <div class="field full">
          <label for="uniId">الرقم الجامعي</label>
          <div class="input-wrap">
            <input type="text" id="uniId" name="uniId" placeholder="أدخل رقمك الجامعي" />
            <span class="icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="5" y="2" width="14" height="20" rx="2" />
                <line x1="9" y1="7" x2="15" y2="7" />
                <line x1="9" y1="11" x2="15" y2="11" />
                <line x1="9" y1="15" x2="13" y2="15" />
              </svg>
            </span>
          </div>
          <span class="error-msg" id="uniId-err"></span>
        </div>

        <div class="field pw-field">
          <label for="password">كلمة المرور</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" autocomplete="new-password" />
            <button type="button" class="toggle-pw" data-target="password" aria-label="إظهار/إخفاء كلمة المرور">
              <svg class="eye-open" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg class="eye-closed" style="display:none" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
            </button>
          </div>
          <span class="error-msg" id="password-err"></span>
        </div>

        <div class="field pw-field">
          <label for="confirmPw">تأكيد كلمة المرور</label>
          <div class="input-wrap">
            <input type="password" id="confirmPw" name="confirmPw" placeholder="أعد إدخال كلمة المرور" autocomplete="new-password" />
            <button type="button" class="toggle-pw" data-target="confirmPw" aria-label="إظهار/إخفاء تأكيد كلمة المرور">
              <svg class="eye-open" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg class="eye-closed" style="display:none" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                <line x1="1" y1="1" x2="23" y2="23" />
              </svg>
            </button>
          </div>
          <span class="error-msg" id="confirmPw-err"></span>
        </div>

      </div>
      <div class="check-row">
        <label for="terms">أوافق على <a href="../pages/terms.php">الشروط والأحكام</a> و <a href="../pages/privacy.php">سياسة الخصوصية</a></label>
        <input type="checkbox" id="terms" />
      </div>
      <span class="error-msg" id="terms-err" style="text-align:right;display:block;margin-top:.25rem;"></span>

      <button type="submit" class="btn-submit" id="submitBtn" name="submitBtn">
        <span class="btn-text">إنشاء الحساب</span>
        <span class="spinner"></span>
      </button>

      <p class="login-link">لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a></p>
    </form>
  </div>

  <p class="footer">منصة توازن للصحة النفسية للطلاب</p>

  <div class="success-overlay" id="successOverlay" role="dialog" aria-live="polite">
    <div class="success-icon">
      <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="20 6 9 17 4 12" />
      </svg>
    </div>
    <h2>تم إنشاء الحساب</h2>
    <p>أهلاً بك في منصة توازن <a href="login.php">تسجيل الدخول</a></p>
  </div>

  <script>
    document.querySelectorAll('.toggle-pw').forEach(btn => {
      btn.addEventListener('click', () => {
        const inp = document.getElementById(btn.dataset.target);
        const isHidden = inp.type === 'password';
        inp.type = isHidden ? 'text' : 'password';
        btn.querySelector('.eye-open').style.display = isHidden ? 'none' : '';
        btn.querySelector('.eye-closed').style.display = isHidden ? '' : 'none';
      });
    });
  </script>

  <script>
    const show = (id, msg) => {
      document.getElementById(id).textContent = msg;
    };
    const clear = id => show(id, '');
    const mark = (inputEl, hasError) => inputEl.classList.toggle('error-field', hasError);

    function validate() {
      let ok = true;

      const name = document.getElementById('fullName');
      if (!name.value.trim() || name.value.trim().length < 3) {
        show('fullName-err', 'يرجى إدخال الاسم الكامل (3 أحرف على الأقل)');
        mark(name, true);
        ok = false;
      } else {
        clear('fullName-err');
        mark(name, false);
      }

      const email = document.getElementById('email');
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        show('email-err', 'يرجى إدخال بريد إلكتروني صحيح');
        mark(email, true);
        ok = false;
      } else {
        clear('email-err');
        mark(email, false);
      }

      const phone = document.getElementById('phone');
      if (!/^05\d{8}$/.test(phone.value.trim())) {
        show('phone-err', 'رقم الهاتف يجب أن يبدأ بـ 05 ويتكون من 10 أرقام');
        mark(phone, true);
        ok = false;
      } else {
        clear('phone-err');
        mark(phone, false);
      }

      const uni = document.getElementById('uniId');
      if (!uni.value.trim()) {
        show('uniId-err', 'يرجى إدخال الرقم الجامعي');
        mark(uni, true);
        ok = false;
      } else {
        clear('uniId-err');
        mark(uni, false);
      }

      const pw = document.getElementById('password');
      const cpw = document.getElementById('confirmPw');
      if (pw.value.length < 8) {
        show('password-err', 'كلمة المرور يجب أن تكون 8 أحرف على الأقل');
        mark(pw, true);
        ok = false;
      } else {
        clear('password-err');
        mark(pw, false);
      }

      if (cpw.value !== pw.value) {
        show('confirmPw-err', 'كلمتا المرور غير متطابقتين');
        mark(cpw, true);
        ok = false;
      } else if (pw.value.length >= 8) {
        clear('confirmPw-err');
        mark(cpw, false);
      }

      const terms = document.getElementById('terms');
      if (!terms.checked) {
        show('terms-err', 'يجب الموافقة على الشروط والأحكام');
        ok = false;
      } else {
        clear('terms-err');
      }

      return ok;
    }
    document.getElementById('regForm').addEventListener('submit', function(e) {
      if (!validate()) {
        e.preventDefault();
        return;
      }
      const btn = document.getElementById('submitBtn');
      btn.classList.add('loading');
    });
  </script>
  <?php
  if (isset($_POST['submitBtn'])) {
    $name = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $uniId = isset($_POST['uniId']) ? trim($_POST['uniId']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPw = isset($_POST['confirmPw']) ? $_POST['confirmPw'] : '';

    if (empty($name) || empty($email) || empty($phone) || empty($uniId) || empty($password)) {
      echo "<script>alert('خطأ: يرجى ملء كافة الحقول المطلوبة بالبيانات.');</script>";
    } else {
      include 'config.php';
      $sql = "INSERT INTO `register`(`name`, `email`, `phone`, `University`, `password`) VALUES ('$name','$email','$phone','$uniId','$password')";
      $result = mysqli_query($con, $sql);
      if ($result) {
        echo "
              <script>
                document.getElementById('successOverlay').classList.add('show');
              </script>
            ";
      }
    }
  }
  ?>
</body>

</html>