<?php
require_once "../includes/conn.php";

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}
$user_id = $_SESSION["user_id"];
$query = "SELECT s.*, u.Email, u.Name
          FROM students s 
          JOIN users u ON s.user_id = u.user_id 
          WHERE s.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico" />
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


            <div class="page">
                <div class="class-badge">
                    <h1><b>My Profile</b></h1>
                </div>

                <div class="fade-up" style="padding: 20px 0; text-align: center;">
                    <div class="avatar-wrap">
                        <img src="../student_images/<?php echo $student['Profile_Image'] ?>" alt="Profile Picture">
                    </div>

                    <div class="mt-3">
                        <h1 style="font-size: 28px; font-weight: 600; color: #333; margin-top: 10px;"><?php echo $student['Name'] ?></h1>
                        <span class="profile-role">Student</span>

                        <div class="stat-pills" style="justify-content: center;">
                            <div class="stat-pill">Class: <span><?php echo $student['class_id'] ?></span></div>
                            <div class="stat-pill">Roll No: <span><?php echo $student['Roll_no'] ?></span></div>
                        </div>
                    </div>

                    <div class="mt-5 fade-up" style="text-align: left;">
                        <h5 style="margin-bottom: 20px; font-weight: 600; color: #333;">Personal Information</h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon text-primary"><i class="feather-mail"></i></div>
                                <div class="info-label">Email Address</div>
                                <div class="info-value"><?php echo $student['Email'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon text-success"><i class="feather-phone"></i></div>
                                <div class="info-label">Phone Number</div>
                                <div class="info-value"><?php echo $student['phone'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon text-warning"><i class="feather-calendar"></i></div>
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value"><?php echo $student['dob'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon text-info"><i class="feather-user"></i></div>
                                <div class="info-label">Gender</div>
                                <div class="info-value"><?php echo $student['gender'] ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon text-danger"><i class="feather-map-pin"></i></div>
                                <div class="info-label">Address</div>
                                <div class="info-value"><?php echo $student['address'] ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4" style="text-align: left;">
                        <a href="profile_edit.php" class="btn btn-primary">
                            <i class="feather-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>

            </div></div>







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