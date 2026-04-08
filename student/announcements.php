<?php
require_once "../includes/conn.php";

session_start();
if(empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student"){
header("Location:../Login.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="description" content="Announcements Page for the Student Management System. View recent updates and notices." />
    <meta name="keywords" content="Student Management System, SMS, Announcements, Notices, Updates" />
    <meta name="author" content="Student Management System" />
    <title>Announcements | Student Management System</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="student.css">
</head>


<body>
   <?php
   include "../includes/navbar/student_navbar.php";
   include "../includes/header.php";

   ?>
   
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Dashboard</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>

                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
          
            <!-- /page-header -->

            <div class="page">
                <div class="class-badge">
                    <h1><b>Announcements</b></h1>
                </div>

                <!-- ── Page header row ──────────────────────────── -->
                <div class="result-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 pt-0">
                    <div>
                        <div class="class-chip">
                            <span class="chip-dot"></span>
                            Recent Updates
                        </div>
                        <p class="page-sub">
                            Stay up to date with the latest notices from your institution
                        </p>
                    </div>
                </div>

                <div class="subjects-grid">
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $select_query = "SELECT `class_id` FROM `students` WHERE `user_id` = '$user_id'";
                    $select_result = mysqli_query($conn,$select_query);
                    $student = mysqli_fetch_assoc($select_result);
                    $count = 1;
                    $class_id = $student['class_id'];
                    $query = "SELECT * FROM `announcements` WHERE `target_audience` = '$class_id' ORDER BY `created_at` DESC LIMIT 5";
                    $result = mysqli_query($conn,$query);
                    
                    if(mysqli_num_rows($result) > 0){
                        while($announcement = mysqli_fetch_assoc($result)){
                            $title = htmlspecialchars($announcement['title']);
                            $message = htmlspecialchars($announcement['message']);
                            $dateStr = strtotime($announcement['created_at']);
                            $is_recent = (time() - $dateStr) < (86400 * 3); // 3 days
                    ?>
                            <div class="result-card" style="display:flex; flex-direction:column;">
                                <div class="card-top">
                                    <div class="subj-icon" style="background:#eeebfb;color:#4a2fa0;font-size:18px;">
                                        <i class="feather-bell"></i>
                                    </div>
                                    <?php if($is_recent): ?>
                                    <span class="grade-pill" style="background:#E1F5EE;color:#1D9E75;">
                                        New
                                    </span>
                                    <?php else: ?>
                                    <span class="grade-pill" style="background:var(--surface2);color:var(--ink3);">
                                        Notice
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="subj-name" style="font-size:16px; margin-bottom:8px;">
                                    <?php echo $count++ . '. ' . $title; ?>
                                </div>

                                <div style="margin-bottom:12px; color:var(--ink2); font-size:13.5px; line-height:1.5;">
                                    <?php echo nl2br($message); ?>
                                </div>

                                <hr class="card-divider" style="margin-top:auto;">

                                <div class="teacher-row" style="margin-bottom:0; justify-content:space-between;">
                                    <div class="pct-label"><i class="feather-calendar me-1"></i> <?php echo date('F j, Y', $dateStr); ?></div>
                                    <div class="pct-label"><i class="feather-clock me-1"></i> <?php echo date('g:i A', $dateStr); ?></div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                    ?>
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <div class="empty-icon">🔔</div>
                            <div class="empty-title">No announcements</div>
                            <div class="empty-sub">We'll alert you here when there are new updates.</div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

            </div><!-- /.page -->
        </div><!-- /.nxl-content -->

        <br>  

      <?php
      include "../includes/footer.php";
      ?>
    </main>



    <script src="../assets/vendors/js/vendors.min.js"></script>
    <script src="../assets/vendors/js/daterangepicker.min.js"></script>
    <script src="../assets/vendors/js/apexcharts.min.js"></script>
    <script src="../assets/vendors/js/circle-progress.min.js"></script>
    <script src="../assets/js/common-init.min.js"></script>
    <script src="../assets/js/dashboard-init.min.js"></script>
    <script src="../assets/js/theme-customizer-init.min.js"></script>
</body>

</html>