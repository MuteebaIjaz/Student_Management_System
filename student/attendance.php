<?php
require_once "../includes/conn.php";

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$student_query = "SELECT `student_id` , `class_id` FROM students WHERE `user_id` = '$user_id'";
$student_result = mysqli_query($conn, $student_query);

if ($student_result && mysqli_num_rows($student_result) > 0) {
    $student_row = mysqli_fetch_assoc($student_result);
    $student_id = $student_row['student_id'];
    $class_id = $student_row['class_id'];
} else {
    die("Student record not found for this user.");
}

$query = "SELECT 
    s.subject_name,
    s.code,
    s.type,
    ANY_VALUE(u.Name) AS teacher_name,
    COUNT(a.attendance_id) AS total_classes,
    SUM(a.status = 'Present') AS attended,
    ROUND((SUM(a.status = 'Present') / COUNT(a.attendance_id)) * 100, 1) AS percentage
FROM attendance a
JOIN subject s ON s.subject_id = a.subject_id
JOIN users u ON u.user_id = a.teacher_id
WHERE a.student_id = $student_id
GROUP BY a.subject_id, s.subject_name, s.code, s.type";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

$attendance_per_subject = mysqli_fetch_all($result, MYSQLI_ASSOC);


$total_held = array_sum(array_column($attendance_per_subject, "total_classes"));
$total_attended = array_sum(array_column($attendance_per_subject, "attended"));
$total_percentage = $total_held > 0 ? round(($total_attended / $total_held) * 100, 1) : 0;
$overall_color = $total_percentage >= 75 ? '#1D9E75' : ($total_percentage >= 65 ? '#BA7517' : '#D85A30');
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Attendance Page for the Student Management System. Track attendance for each subject." />
    <meta name="keywords" content="Student Management System, SMS, Attendance, Subject Attendance, Attendance Records" />
    <meta name="author" content="Student Management System" />
    <title>Attendance | Student Management System</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="attendance.css">
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
                    <h1><b>My Attendance</b></h1>
                </div>

                <!-- ── Page header row ──────────────────────────── -->
                <div class="result-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 pt-0">
                    <div>
                        <div class="class-chip">
                            <span class="chip-dot"></span>
                            Attendance Records
                        </div>
                        <p class="page-sub">
                            <?php echo count($attendance_per_subject); ?>
                            subject<?php echo count($attendance_per_subject) !== 1 ? 's' : ''; ?>
                            &middot;
                            <?php echo $total_held; ?> class<?php echo $total_held !== 1 ? 'es' : ''; ?> totally
                            recorded
                        </p>
                    </div>
                </div>

                <!-- ── Summary cards ────────────────────────────── -->
                <div class="summary-grid">
                    <div class="stat-card">
                        <div class="stat-label">Overall Attendance</div>
                        <div class="stat-value" style="color: <?php echo $overall_color; ?>">
                            <?php echo $total_percentage; ?>%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Classes Attended</div>
                        <div class="stat-value"><?php echo $total_attended; ?> <span
                                style="font-size:16px;color:var(--ink3);">/ <?php echo $total_held; ?></span></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Subjects Tracked</div>
                        <div class="stat-value"><?php echo count($attendance_per_subject); ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Status</div>
                        <div class="stat-value" style="line-height:2;">
                            <span class="grade-pill"
                                style="background:<?php echo $total_percentage >= 75 ? '#E1F5EE' : ($total_percentage >= 65 ? '#FAEEDA' : '#FAECE7'); ?>;color:<?php echo $overall_color; ?>;">
                                <?php echo $total_percentage >= 75 ? 'Good Standing' : ($total_percentage >= 65 ? 'At Risk' : 'Critical'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="section-label">Per-subject breakdown</div>
                <div class="subjects-grid">

                    <?php foreach ($attendance_per_subject as $row): ?>
                        <?php
                        $teacher_name = trim($row['teacher_name']);
                        $words = array_values(array_filter(explode(' ', $teacher_name))); // break into parts ignoring multiple spaces
                        $initials = '';
                        if (!empty($words[0])) $initials .= strtoupper($words[0][0]);
                        if (!empty($words[1])) $initials .= strtoupper($words[1][0]);
                        
                        $pct = $row['percentage'];
                        $color = $pct >= 75 ? '#1D9E75' : ($pct >= 65 ? '#BA7517' : '#D85A30');
                        $bg = $pct >= 75 ? '#E1F5EE' : ($pct >= 65 ? '#FAEEDA' : '#FAECE7');
                        $status = $pct >= 75 ? 'Safe' : ($pct >= 65 ? 'At Risk' : 'Critical');
                        ?>
                        <div class="result-card">

                            <div class="card-top">
                                <div class="subj-icon" style="background:#E6F1FB;color:#0C447C;">
                                    <?php echo $row['subject_name'][0]; ?>
                                </div>
                                <span class="grade-pill" style="background:<?php echo $bg; ?>;color:<?php echo $color; ?>;">
                                    <?php echo $status; ?>
                                </span>
                            </div>

                            <div class="subj-name">
                                <?php echo $row['subject_name']; ?>
                            </div>
                            <div class="subj-meta">
                                <?php echo htmlspecialchars($row['code']); ?>
                                <span class="type-tag" style="background:#eeebfb;color:#4a2fa0;">
                                    <?php echo ucfirst(htmlspecialchars($row['type'])); ?>
                                </span>
                            </div>

                            <div class="teacher-row">
                                <div class="t-avatar"><?php echo $initials; ?></div>
                                <span class="t-name">
                                    <?php echo $row['teacher_name']; ?>
                                </span>
                            </div>

                            <hr class="card-divider">

                            <div class="pct-row">
                                <span class="pct-label">Attended: <?php echo $row['attended']; ?> /
                                    <?php echo $row['total_classes']; ?></span>
                                <span class="pct-value" style="color:<?php echo $color; ?>;"><?php echo $pct; ?>%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill"
                                    style="width:<?php echo $pct; ?>%;background:<?php echo $color; ?>;"></div>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>
            </div><!-- /.page -->

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