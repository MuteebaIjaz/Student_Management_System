<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "admin") {
    header("location:../Login.php");
    exit();
}
if (isset($_POST['assign'])) {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];

    $check = mysqli_query($conn, "
        SELECT * FROM class_subject_teacher
        WHERE class_id='$class_id'
        AND subject_id='$subject_id'
    ");

    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "This subject is already assigned to this class!";
        header("Location:assignment.php");
        exit();
    } else {

        mysqli_query($conn, "
            INSERT INTO class_subject_teacher
            (class_id, subject_id, teacher_id)
            VALUES
            ('$class_id', '$subject_id', '$teacher_id')
        ");

        $_SESSION['success'] = "Successful!";
        header("Location:assignment.php");
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
    <title>Dashboard</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico" />
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

        <div class="registration-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="registration-table-card">
                            <div class="registration-table-header">
                                <h4>Assign:</h4>
                            </div>

                            <div class="card mb-4  mx-4 mx-sm-0 position-relative">

                                <div class="card-body p-sm-5">
                                    <?php
                                    if (isset($_SESSION['error'])) {
                                        echo "<div class='w-100 mt-4 pt-2 text-danger'>" . $_SESSION['error'] . "</div>";
                                        unset($_SESSION['error']);
                                    }
                                    if (isset($_SESSION['success'])) {
                                        echo "<div class='w-100 mt-4 pt-2 text-success'>" . $_SESSION['success'] . "</div>";
                                        unset($_SESSION['success']);
                                    }

                                    ?>

                                    <form method="POST">

                                        <div class="mb-3">
                                            <label>Select Class</label>
                                            <select name="class_id" class="form-control" required>
                                                <option disabled selected>Select Class</option>
                                                <?php
                                                $classes = mysqli_query($conn, "SELECT * FROM classes");
                                                while ($row = mysqli_fetch_assoc($classes)) {
                                                    echo "<option value='{$row['class_id']}'>
                        {$row['class_name']} - {$row['section']}
                      </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Select Subject</label>
                                            <select name="subject_id" class="form-control" required>
                                                <option disabled selected>Select Subject</option>
                                                <?php
                                                $subjects = mysqli_query($conn, "SELECT * FROM subject");
                                                while ($row = mysqli_fetch_assoc($subjects)) {
                                                    echo "<option value='{$row['subject_id']}'>
                        {$row['subject_name']}
                      </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Select Teacher</label>
                                            <select name="teacher_id" class="form-control" required>
                                                <option disabled selected>Select Teacher</option>
                                                <?php
                                                $teachers = mysqli_query($conn, "SELECT user_id, Name FROM users WHERE Role='teacher'");
                                                while ($row = mysqli_fetch_assoc($teachers)) {
                                                    echo "<option value='{$row['user_id']}'>
                        {$row['Name']}
                      </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <button type="submit" name="assign" class="btn btn-primary">
                                            Assign
                                        </button>
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