<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitBtn'])) {
  include "config.php";
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  if (empty($email) || empty($password)) {
    $error_message = 'الرجاء إدخال البريد الإلكتروني وكلمة المرور';
  } else {
    $sql = "SELECT `id`, `name`, `email` FROM `register` WHERE `email` = '$email' AND `password` = '$password'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['name']    = $user_data['name'];
      header("Location: ../student/index.php");
      exit();
    } else {
      $error_message = 'عذراً، البريد الإلكتروني أو كلمة المرور غير صحيحة';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>توازن – تسجيل الدخول</title>
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <style>
    .options-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: .8rem 0;
    }

    .options-row .check-row {
      margin-top: 0;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .options-row a {
      font-size: .85rem;
      color: var(--teal);
      text-decoration: none;
    }

    .options-row a:hover {
      text-decoration: underline;
    }

    .separator {
      display: flex;
      align-items: center;
      text-align: center;
      color: var(--text-sub);
      font-size: .85rem;
      margin: 1.5rem 0;
    }

    .separator::before,
    .separator::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid var(--border);
    }

    .separator::before {
      margin-left: 1rem;
    }

    .separator::after {
      margin-right: 1rem;
    }

    .btn-outline {
      width: 100%;
      padding: .88rem 1rem;
      background: transparent;
      border: 1px solid var(--teal);
      color: var(--teal);
      border-radius: var(--radius);
      font-family: var(--font);
      font-size: 1.05rem;
      font-weight: 500;
      cursor: pointer;
      margin-bottom: .8rem;
      transition: all var(--trans);
      text-decoration: none;
      display: block;
      text-align: center;
    }

    .btn-outline:hover {
      background: var(--teal-light);
    }

    .btn-outline-purple {
      border-color: #7b2cbf;
      color: #7b2cbf;
    }

    .btn-outline-purple:hover {
      background: #f8f0ff;
    }

    /* تنسيق زر تسجيل الدخول الأساسي ليطابق هوية المنصة */
    .btn-submit {
      width: 100%;
      padding: .88rem 1rem;
      background-color: rgba(0, 118, 96, 0.85);
      color: white;
      border: none;
      border-radius: var(--radius);
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 1rem;
    }

    .btn-submit:hover {
      background-color: rgba(0, 118, 96, 1);
    }

    .shake {
      animation: shake-animation 0.5s ease-in-out;
    }

    @keyframes shake-animation {

      0%,
      100% {
        transform: translateX(0);
      }

      20%,
      60% {
        transform: translateX(-6px);
      }

      40%,
      80% {
        transform: translateX(6px);
      }
    }
  </style>
</head>

<body>
  <div class="logo-wrap">
    <div class="logo-box">
      <img src="../assets/images/logo.png" alt="شعار توازن" style="width: 100%; height: 100%; object-fit: contain;" />
    </div>
  </div>

  <div class="card" role="main">
    <h1 class="card-title">تسجيل الدخول</h1>
    <p class="card-sub">مرحباً بك في منصة توازن</p>

    <form id="loginForm" action="" method="POST" novalidate autocomplete="off">
      <div class="form-grid">
        <div class="field full">
          <label for="email">البريد الإلكتروني</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" autocomplete="email" style="text-align:right;" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
          </div>
        </div>

        <div class="field pw-field full">
          <label for="password">كلمة المرور</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" autocomplete="current-password" />
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
        </div>
      </div>

      <div class="options-row">
        <a href="#">نسيت كلمة المرور؟</a>
        <div class="check-row">
          <label for="remember">تذكرني</label>
          <input type="checkbox" id="remember" name="remember" />
        </div>
      </div>

      <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger rounded-4 shake text-center" role="alert" style="margin: 1rem 0 0 0;">
          <p style="margin:0; font-size:0.95rem;"><?php echo $error_message; ?></p>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn-submit" name="submitBtn" id="submitBtn">
        <span class="btn-text">تسجيل الدخول</span>
      </button>

      <div class="separator">أو</div>

      <a href="doctor_login.php" class="btn-outline">تسجيل دخول كطبيب</a>
      <a href="admin_login.php" class="btn-outline btn-outline-purple">تسجيل دخول كمشرف</a>

      <p class="login-link" style="text-align: center; margin-top: 1rem;">ليس لديك حساب؟ <a href="register.php" style="color: var(--teal); text-decoration: none; font-weight: bold;">سجل الآن</a></p>
    </form>
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
</body>

</html>