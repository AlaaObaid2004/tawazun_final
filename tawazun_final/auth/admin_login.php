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
    $sql = "SELECT `id`, `name`, `email` FROM `admin_login` WHERE `email` = '$email' AND `password` = '$password'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['email']   = $user_data['email'];
      $_SESSION['name']    = $user_data['name'];
      header("Location: ../admin/admin-dashboard.php");
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
  <title>توازن – تسجيل دخول المشرف</title>
  <link rel="stylesheet" href="../assets/css/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <style>
    .header-titles {
      text-align: center;
      margin-bottom: 1.5rem;
      animation: fadeDown .6s ease both;
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
      color: #8a2be2;
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

    .card-header-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 1.5rem;
    }

    .card-header-icon h2 {
      font-size: 1.4rem;
      color: var(--text-main);
      font-weight: 700;
    }

    .icon-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: #f3e8ff;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #8a2be2;
    }

    .footer-link {
      margin-top: 2rem;
      text-align: center;
      font-size: .9rem;
      color: var(--text-sub);
    }

    .footer-link a {
      color: #8a2be2;
      text-decoration: none;
      font-weight: 600;
    }

    .footer-link a:hover {
      text-decoration: underline;
    }

    .input-wrap input {
      padding-right: 2.6rem;
      padding-left: 1rem;
      width: 100%;
      height: 45px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap .icon {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      right: 0.85rem;
      color: #8a2be2;
    }

    .pw-field input {
      padding-left: 2.6rem;
    }

    .btn-submit-purple {
      width: 100%;
      padding: .88rem 1rem;
      border: none;
      color: white;
      border-radius: 8px;
      font-weight: bold;
      background: #8a2be2;
      box-shadow: 0 4px 18px rgba(138, 43, 226, .3);
      transition: all 0.3s ease;
    }

    .btn-submit-purple:hover {
      background: #7a1cd2;
      box-shadow: 0 6px 24px rgba(138, 43, 226, .4);
    }

    .check-row {
      display: flex;
      align-items: center;
      gap: 5px;
    }
  </style>
</head>

<body>

  <div class="logo-wrap" style="margin-bottom: 0.5rem; display: flex; justify-content: center;">
    <div class="logo-box" style="width: 70px; height: 70px; border-radius: 16px;">
      <img src="../assets/images/logo.png" alt="شعار توازن" style="width: 100%; height: 100%; object-fit: contain;" />
    </div>
  </div>

  <div class="header-titles">
    <h1>منصة توازن</h1>
    <p>بوابة المشرفين</p>
  </div>

  <div class="container" style="max-width: 450px;">
    <div class="card p-4 shadow-sm rounded-4" role="main">
      <div class="card-header-icon">
        <h2>تسجيل دخول المشرف</h2>
        <div class="icon-circle">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
        </div>
      </div>

      <form method="POST" action="" novalidate autocomplete="off">
        <div class="form-grid">
          <div class="field full mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <div class="input-wrap">
              <input type="email" id="email" name="email" placeholder="admin@example.com" autocomplete="email" style="direction:ltr; text-align:right;" required />
              <span class="icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="4" width="20" height="16" rx="2" />
                  <path d="m2 7 10 7 10-7" />
                </svg>
              </span>
            </div>
          </div>

          <div class="field commissions-field full mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <div class="input-wrap">
              <input type="password" name="password" id="password" placeholder="••••••" autocomplete="current-password" style="direction:ltr; text-align:right;" required />
              <span class="icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
            </div>
          </div>
        </div>

        <div class="options-row mb-3">
          <a href="#">نسيت كلمة المرور؟</a>
          <div class="check-row">
            <label for="remember">تذكرني</label>
            <input type="checkbox" id="remember" />
          </div>
        </div>

        <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger rounded-4 text-center shake" role="alert">
            <p style="margin:0; font-size: 14px;"><?php echo $error_message; ?></p>
          </div>
        <?php endif; ?>

        <button type="submit" name="submitBtn" class="btn-submit-purple" id="submitBtn">
          تسجيل الدخول
        </button>

        <div class="separator">أو</div>

        <a href="login.php" class="btn-outline">تسجيل دخول كطالب</a>
        <a href="doctor_login.php" class="btn-outline">تسجيل دخول كطبيب</a>
      </form>
    </div>
  </div>

  <p class="footer-link">هل تحتاج للمساعدة؟ <a href="#">تواصل مع الدعم الفني</a></p>

</body>

</html>