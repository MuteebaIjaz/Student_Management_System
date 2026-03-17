<?php
require_once "../includes/conn.php";

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$student_query="SELECT `student_id` , `class_id` FROM students WHERE `user_id` = '$user_id'";
$student_result=mysqli_query($conn, $student_query);
$student_row=mysqli_fetch_assoc($student_result);
$student_id = $student_row['student_id'];
$class_id = $student_row['class_id'];

$query = "SELECT 
    s.subject_name,
    s.code,
    s.type,
    u.Name AS teacher_name,
    COUNT(a.attendance_id) AS total_classes,
    SUM(a.status = 'Present') AS attended,
    ROUND((SUM(a.status = 'Present') / COUNT(a.attendance_id)) * 100, 1) AS percentage
FROM attendance a
JOIN subject s ON s.subject_id = a.subject_id
JOIN users u ON u.user_id = a.teacher_id
WHERE a.student_id = $student_id
GROUP BY a.subject_id";
$result = mysqli_query($conn, $query);
$attendance_per_subject = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}


$total_held=array_sum(array_column($attendance_per_subject,"total_classes"));
$total_attended=array_sum(array_column($attendance_per_subject,"attended"));
$total_percentage = $total_held > 0 ? round(($total_attended / $total_held) * 100, 1) : 0;
$overall_color    = $total_percentage >= 75 ? '#1D9E75' : ($total_percentage >= 65 ? '#BA7517' : '#D85A30');
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="attendance.css">
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

        </div>

        <div class="container mb-3 mt-3 bg-white">

            <div class="row">
                <div class="col-lg-12">
                    <div class="attendance-section">
                        <div class="attendance-header text-center">
                            <h1>Attendance</h1>
                        </div>


                        <div class="attendance-body">
                           <div class="top-bar">
    <h5>Overall Attendance:
        <span style="color:<?php echo $overall_color; ?>;font-weight:600;">
            <?php echo $total_percentage; ?>%
        </span>
        <?php if ($total_percentage < 75): ?>
            <span style="background:#FAEEDA;color:#633806;font-size:12px;padding:3px 10px;border-radius:6px;margin-left:8px;">
                Below 75% threshold
            </span>
        <?php endif; ?>
    </h5>
    <p>Total Classes Held: <?php echo $total_held; ?></p>
    <p>Total Classes Attended: <?php echo $total_attended; ?></p>
</div>

                            <div class="attendance-middle">

                                <?php foreach ($attendance_per_subject as $row): ?>
                                    <?php
                                        $words = explode(' ', $row['teacher_name']);
                                        $initials = strtoupper($words[0][0] . (isset($words[1]) ? $words[1][0] : ''));
                                        $pct     = $row['percentage'];
    $color   = $pct >= 75 ? '#1D9E75' : ($pct >= 65 ? '#BA7517' : '#D85A30');
    $bg      = $pct >= 75 ? '#E1F5EE' : ($pct >= 65 ? '#FAEEDA' : '#FAECE7');
    $status  = $pct >= 75 ? 'Safe'    : ($pct >= 65 ? 'At Risk' : 'Critical');
                                    ?>
                                    <div class="attendance-card">

                                        <div class="card-top">
                                            <div class="attendance-icon" 
                                            style="background:#E6F1FB;color:#0C447C;">
                                                <?php echo $row['subject_name'][0]; ?>
                                            </div>
                                         <span class="tag" style="background:<?php echo $bg; ?>;color:<?php echo $color; ?>;">
            <?php echo $status; ?>
        </span>
                                        </div>

                                        <div class="subject-name">
                                            <?php echo $row['subject_name']; ?>
                                        </div>

                                        <div class="teacher-row">
                                                    <div class="avatar" style="background:#B5D4F4;color:#0C447C;"><?php echo $initials; ?></div>

                                            <span class="teacher-name">
                                                <?php echo $row['teacher_name']; ?>
                                            </span>
                                        </div>

                                        <div class="class-attended">
                                            Class Attended: <?php echo $row['attended']; ?> / <?php echo $row['total_classes']; ?>
                                        </div>
         <div class="progress-bar">
        <div class="progress-fill" style="width:<?php echo $pct; ?>%;background:<?php echo $color; ?>;"></div>
    </div>

    <div class="status-badge" style="color:<?php echo $color; ?>;font-weight:500;margin-top:6px;">
        <?php echo $pct; ?>%
    </div>
 
      </div>
     
                                    </div>

                                <?php endforeach; ?>

                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>

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