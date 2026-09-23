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
    $sql = "SELECT `id`, `name`, `email` FROM `doctor_login` WHERE `email` = '$email' AND `password` = '$password'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['name']    = $user_data['name'];
      $_SESSION['email']   = $user_data['email'];
      header("Location: ../doctor/index.php");
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
  <title>توازن – تسجيل دخول الطبيب</title>
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  <style>
    :root {
      --teal: #007660;
      --teal-light: #e6f2f0;
      --text-sub: #6c757d;
      --border: #dee2e6;
      --radius: 12px;
      --trans: 0.3s;
    }

    .header-titles {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .shake {
      animation: shake-animation 0.5s ease-in-out;
    }

    @keyframes shake-animation {

      0%,
      100% {
        transform: translateX(0);
      }

      10%,
      30%,
      50%,
      70%,
      90% {
        transform: translateX(-5px);
      }

      20%,
      40%,
      60%,
      80% {
        transform: translateX(5px);
      }
    }

    .header-titles h1 {
      font-size: 1.5rem;
      color: var(--teal);
      margin-bottom: 0.2rem;
      font-weight: 800;
    }

    .header-titles p {
      font-size: 0.95rem;
      color: var(--text-sub);
    }

    .options-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: .8rem 0;
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

    /* Submit Button Style */
    .btn-submit {
      width: 100%;
      padding: .88rem 1rem;
      background: var(--teal);
      color: white;
      border: none;
      border-radius: var(--radius);
      font-size: 1.05rem;
      font-weight: bold;
      cursor: pointer;
      transition: background var(--trans);
    }

    .btn-submit:hover {
      background: #005c4b;
    }

    .btn-outline {
      width: 100%;
      padding: .88rem 1rem;
      background: transparent;
      border: 1px solid var(--teal);
      color: var(--teal);
      border-radius: var(--radius);
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
      color: var(--teal);
    }

    .card-header-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 1.5rem;
    }

    .card-header-icon h2 {
      font-size: 1.4rem;
      color: #212529;
      font-weight: 700;
    }

    .icon-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: #d4f0ec;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--teal);
    }

    .footer-link {
      margin-top: 2rem;
      text-align: center;
      font-size: .9rem;
      color: var(--text-sub);
    }

    .footer-link a {
      color: var(--teal);
      text-decoration: none;
      font-weight: 600;
    }

    .footer-link a:hover {
      text-decoration: underline;
    }

    .input-wrap input {
      padding-right: 2.6rem;
      padding-left: 1rem;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap .icon {
      position: absolute;
      top: 50%;
      right: 0.85rem;
      transform: translateY(-50%);
      color: var(--text-sub);
    }

    .pw-field input {
      padding-left: 2.6rem;
    }

    .toggle-pw {
      position: absolute;
      top: 50%;
      left: 0.85rem;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-sub);
      cursor: pointer;
    }
  </style>
</head>

<body>

  <div class="logo-wrap text-center" style="margin-bottom: 0.5rem;">
    <div class="logo-box mx-auto" style="width: 70px; height: 70px; border-radius: 16px; overflow: hidden;">
      <img src="../assets/images/logo.png" alt="شعار توازن" style="width: 100%; height: 100%; object-fit: contain;" />
    </div>
  </div>

  <div class="header-titles">
    <h1>منصة توازن</h1>
    <p>بوابة الأخصائيين النفسيين</p>
  </div>

  <div class="card mx-auto" style="max-width: 500px; padding: 2rem; border-radius: var(--radius);" role="main">
    <div class="card-header-icon">
      <h2>تسجيل دخول الطبيب</h2>
      <div class="icon-circle">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="8.5" cy="7" r="4"></circle>
          <line x1="20" y1="8" x2="20" y2="14"></line>
          <line x1="23" y1="11" x2="17" y2="11"></line>
        </svg>
      </div>
    </div>

    <?php if (!empty($error_message)): ?>
      <div class="alert alert-danger rounded-4 text-center shake" role="alert">
        <p class="m-0"><?php echo $error_message; ?></p>
      </div>
    <?php endif; ?>

    <form method="POST" action="" novalidate autocomplete="off">
      <div class="form-grid">
        <div class="mb-3">
          <label for="email" class="form-label">البريد الإلكتروني</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" class="form-control" placeholder="doctor@example.com" autocomplete="email" style="direction:ltr; text-align:right;" required />
            <span class="icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16" rx="2" />
                <path d="m2 7 10 7 10-7" />
              </svg>
            </span>
          </div>
        </div>

        <div class="mb-3 pw-field">
          <label for="password" class="form-label">كلمة المرور</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••" autocomplete="current-password" style="direction:ltr; text-align:right;" required />
            <span class="icon">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </span>
            <button type="button" class="toggle-pw" id="togglePassword" aria-label="إظهار/إخفاء كلمة المرور">
              <svg id="eyeOpen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="options-row mb-4">
        <a href="#">نسيت كلمة المرور؟</a>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember" />
          <label class="form-check-label" for="remember">تذكرني</label>
        </div>
      </div>

      <button type="submit" name="submitBtn" class="btn-submit">
        <span>تسجيل الدخول</span>
      </button>

      <div class="separator">أو</div>

      <a href="login.php" class="btn-outline">تسجيل دخول كطالب</a>
    </form>
  </div>

  <p class="footer-link">هل تحتاج للمساعدة؟ <a href="#">تواصل مع الدعم الفني</a></p>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function(e) {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
    });
  </script>
</body>

</html>