<?php
if (file_exists('../admin/config.php')) {
    require_once '../admin/config.php';
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id']) && isset($con)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $con->prepare("SELECT name FROM register WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $_SESSION['name'] = $user['name'];
    }
    $stmt->close();
}
$current_user = $_SESSION['name'] ?? 'الأدمن';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي - منصة توازن</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">

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
                    <span class="user-name">
                        <?php echo htmlspecialchars($current_user); ?>
                    </span>
                    <i class="fa-regular fa-user user-avatar"></i>
                </div>
                <button class="icon-btn notification-btn" aria-label="الإشعارات">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
            </div>
        </header>

        <div class="page-navigation">
            <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-right"></i> العودة إلى لوحة التحكم</a>
        </div>

        <div class="page-header right-header">
            <div class="page-icon teal-icon"><i class="fa-solid fa-file-invoice"></i></div>
            <div class="header-content">
                <h2>طلباتي</h2>
                <p>تتبع جميع طلباتك وحالتها</p>
            </div>
        </div>

        <div class="stats-cards requests-stats">

            <div class="stat-box active">
                <div class="stat-box-icon blue-icon"><i class="fa-solid fa-file-invoice"></i></div>
                <div class="stat-box-info">
                    <span>الكل</span>
                    <strong>
                        <?php
                        $sql_all = "SELECT COUNT(*) AS total FROM `psychological_requests` WHERE `student_name` = '$current_user'";
                        $res_all = mysqli_query($con, $sql_all);
                        $row_all = mysqli_fetch_assoc($res_all);
                        echo $row_all['total'] ?? 0;
                        ?>
                    </strong>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon orange-icon"><i class="fa-solid fa-file-lines"></i></div>
                <div class="stat-box-info">
                    <span>قيد المراجعة</span>
                    <strong>
                        <?php
                        $sql_pending = "SELECT COUNT(*) AS total FROM `psychological_requests` WHERE `student_name` = '$current_user' AND `status` = 'جديد'";
                        $res_pending = mysqli_query($con, $sql_pending);
                        $row_pending = mysqli_fetch_assoc($res_pending);
                        echo $row_pending['total'] ?? 0;
                        ?>
                    </strong>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-box-icon teal-icon"><i class="fa-solid fa-file-circle-check"></i></div>
                <div class="stat-box-info">
                    <span>مكتملة</span>
                    <strong>
                        <?php
                        $sql_comp = "SELECT COUNT(*) AS total FROM `psychological_requests` WHERE `student_name` = '$current_user' AND `status` = 'مكتملة'";
                        $res_comp = mysqli_query($con, $sql_comp);
                        $row_comp = mysqli_fetch_assoc($res_comp);
                        echo $row_comp['total'] ?? 0;
                        ?>
                    </strong>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="البحث في الطلبات...">
        </div>

        <div class="requests-detailed-list">

            <?php
            $sql = "SELECT `problem_type`, `priority_level`, `title`, `description`, `is_anonymous`, `status`, `created_at` FROM `psychological_requests` WHERE `student_name`='$current_user' ORDER BY `id` DESC";
            $result = mysqli_query($con, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($item = mysqli_fetch_assoc($result)) {
                    $status_class = ($item['status'] == 'جديد') ? 'status-review' : 'status-completed';
                    $status_text = ($item['status'] == 'جديد') ? 'قيد المراجعة' : 'مكتملة';
                    $icon_class = ($item['status'] == 'جديد') ? 'fa-regular fa-clock orange-text' : 'fa-solid fa-check-circle teal-text';
                    $priority_class = 'orange-text';
                    if ($item['priority_level'] == 'عالية') $priority_class = 'red-text';
                    if ($item['priority_level'] == 'منخفضة') $priority_class = 'green-text';
                    echo '
                    <div class="request-detailed-card">
                        <div class="request-header-row">
                            <div class="request-title-area">
                                <i class="' . $icon_class . '"></i>
                                <h3>' . htmlspecialchars($item['title']) . '</h3>
                            </div>
                            <div class="request-status-area">
                                <span class="badge ' . $status_class . '">' . $status_text . '</span>
                            </div>
                        </div>
                        <div class="request-meta">
                            <span><i class="fa-regular fa-folder"></i> ' . htmlspecialchars($item['problem_type']) . '</span>
                            <span><i class="fa-regular fa-calendar"></i> ' . $item['created_at'] . '</span>
                            <span class="' . $priority_class . '"><i class="fa-solid fa-minus"></i> أولوية ' . $item['priority_level'] . '</span>
                        </div>';
                    if ($item['status'] == 'مكتملة') {
                        echo '
                            <div class="request-reply-box">
                                <strong>رد المختصين:</strong>
                                <p>شكراً لثقتك بنا. ننصحك بالانضمام للأنشطة الطلابية والتواصل مع زملائك. يمكنك أيضاً الاستفادة من جلسات الدعم الجماعي المتوفرة في الجامعة.</p>
                            </div>';
                    }

                    echo '</div>';
                }
            } else {
                echo '<p style="text-align:center; padding: 20px; color: #666;">لا توجد لديك أي طلبات حالياً.</p>';
            }
            ?>

        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>

</html>