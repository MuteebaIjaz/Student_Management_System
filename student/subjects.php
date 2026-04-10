<?php
require_once "../includes/conn.php";

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}
$student_id = $_SESSION['user_id'];
$select_query = "SELECT `class_id` FROM `students` WHERE `user_id`= $student_id";
$select_result = mysqli_query($conn, $select_query);
$student = mysqli_fetch_assoc($select_result);
$class_id = $student['class_id'];
$query = "SELECT subject.* , users.name AS teacher_name, class_subject_teacher.id 
FROM class_subject_teacher
JOIN  subject ON subject.subject_id= class_subject_teacher.subject_id
JOIN users ON users.user_id= class_subject_teacher.teacher_id
WHERE class_subject_teacher.class_id=$class_id";

$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <title>Subjects | SMS</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png?v=11" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="profile.css">
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
                        <li class="breadcrumb-item"><a href="student.php">Home</a></li>
                        <li class="breadcrumb-item">Subjects</li>
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


            <div class="page">
                <div class="class-badge">
                    <h1><b>My Subjects</b></h1>
                </div>

                <?php
                $class_query = "SELECT * FROM classes WHERE class_id='$class_id'";
                $class_result = mysqli_query($conn, $class_query);
                $class = mysqli_fetch_assoc($class_result);
                $total_subjects = mysqli_num_rows($result);
                ?>
                <div class="result-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 pt-0">
                    <div>
                        <div class="class-chip">
                            <span class="chip-dot"></span>
                            <?php echo ($class['class_name'] . ' — ' . $class['section']); ?>
                        </div>
                        <p class="page-sub">
                            <?php echo $total_subjects; ?> subject<?php echo $total_subjects !== 1 ? 's' : ''; ?> assigned
                        </p>
                    </div>
                </div>

                <div class="subjects-grid">
                    <?php
                    while ($subjects = mysqli_fetch_assoc($result)) {
                        $words = explode(' ', $subjects['teacher_name']);
                        $initials = strtoupper($words[0][0] . (isset($words[1]) ? $words[1][0] : ''));
                        $type = $subjects['type'];
                        $type_bg  = $type === 'core' ? '#e6f5ee' : '#eeebfb';
                        $type_col = $type === 'core' ? '#1a7a56' : '#4a2fa0';
                        $icon_bg  = $type === 'core' ? '#e6eff9' : '#eeebfb';
                        $icon_col = $type === 'core' ? '#1a4f8a' : '#4a2fa0';
                    ?>
                        <div class="result-card">
                            <div class="card-top">
                                <div class="subj-icon" style="background:<?php echo $icon_bg; ?>;color:<?php echo $icon_col; ?>;">
                                    <?php echo $subjects['subject_name'][0]; ?>
                                </div>
                            </div>
                            
                            <div class="subj-name"><?php echo ($subjects['subject_name']); ?></div>
                            <div class="subj-meta">
                                <?php echo ($subjects['code']); ?>
                                <span class="type-tag" style="background:<?php echo $type_bg; ?>;color:<?php echo $type_col; ?>;">
                                    <?php echo ucfirst(($type)); ?>
                                </span>
                            </div>

                            <hr class="card-divider">

                            <div class="teacher-row" style="margin-bottom:0;">
                                <div class="t-avatar"><?php echo $initials; ?></div>
                                <span class="t-name">
                                    Teacher: <?php echo ($subjects['teacher_name']); ?>
                                </span>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div></div>



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
