<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "admin") {
    header("location:../Login.php");
    exit();
}
if (isset($_POST['Add'])) {
    $className = $_POST['className'];
    $Section = $_POST['Section'];
    // $teacher_id = $_POST['teacher_id'];
    $select_query = "SELECT * FROM classes 
                 WHERE class_name = '$className' 
                 AND section = '$Section'";
    $select_result = mysqli_query($conn, $select_query);

    if (mysqli_num_rows($select_result) > 0) {
        $_SESSION['error'] = "This class and section already exists.";
        header("Location:add_classes.php");
        exit();
    }
    $query = "INSERT INTO `classes`
    ( `class_name`, `section`) 
    VALUES
     ('$className','$Section')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: add_classes.php");
        exit();
    }

}


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
    <title>Add Classes | SMS</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png?v=11" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php
    include "../includes/navbar/admin_navbar.php";
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
                        <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
                        <li class="breadcrumb-item">Add Class</li>
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

        <div class="registration-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="registration-table-card">
                            <div class="registration-table-header">
                                <h4>Add Class:</h4>
                            </div>

                            <div class="card mb-4  mx-4 mx-sm-0 position-relative">

                                <div class="card-body p-sm-5">


                                    <form action="" class="w-50  pt-2" method="post">
                                        <div class="mb-4">
                                            <input type="text" class="form-control" placeholder=" Class Name"
                                                name="className" required>
                                        </div>
                                        <div class="mb-4">
                                            <select name="Section" class="form-control" required>
                                                <option value="" disabled selected>Select Section</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                        <!-- <div class="mb-4">
                                            <select class="form-control" name="teacher_id" required>
                                                <option value="" selected disabled>Assign Teacher</option>
                                                <?php

                                                $query = "SELECT `user_id` , `Name` FROM `users` WHERE Role = 'teacher'";
                                                $result = mysqli_query($conn, $query);
                                                while ($teacher = mysqli_fetch_assoc($result)) {

                                                    echo "<option value='{$teacher['user_id']}'>{$teacher['Name']}</option>";

                                                }
                                                ?>
                                            </select>
                                        </div> -->

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-lg btn-primary w-100"
                                                name="Add">Add</button>
                                        </div>
                                    </form>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
