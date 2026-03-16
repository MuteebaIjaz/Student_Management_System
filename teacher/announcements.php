<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "teacher") {
    header("location:../Login.php");
    exit();
}
if (isset($_POST['Add'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $sender_id = $_SESSION['user_id'];
    $sender_role = $_SESSION['user_role'];
    $target_audience = mysqli_real_escape_string($conn, $_POST['class_id']);
    if (strlen($title) > 255) {
        $_SESSION['error'] = "Title is too long (max 255 characters).";
        header("location: announcements.php");
        exit();
    }

    $query = "INSERT INTO `announcements`(`title`, `message`, `sender_id`, `sender_role`, `target_audience`) VALUES ('$title','$message','$sender_id','$sender_role','$target_audience')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION['success'] = "Annoucement added successfully";
        header("location:announcements.php");
        exit();
    } else {
        $_SESSION['error'] = "Annoucement not added";
        header("location:announcements.php");
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
    include "../includes/navbar/teacher_navbar.php";
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
                                <h4>Add Annoucement:</h4>
                            </div>

                            <div class="card mb-4  mx-4 mx-sm-0 position-relative">

                                <div class="card-body p-sm-5">
                                    <?php
                                    if (isset($_SESSION['error'])) {
                                        echo "<div class='w-100 pt-2 text-danger'>" . $_SESSION['error'] . "</div>";
                                        unset($_SESSION['error']);
                                    }
                                    if (isset($_SESSION['success'])) {
                                        echo "<div class='w-100 pt-2 text-success'>" . $_SESSION['success'] . "</div>";
                                        unset($_SESSION['success']);
                                    }
                                    ?>

                                    <form action="" class="w-50  pt-2" method="post">
                                        <div class="mb-4">
                                            <input type="text" class="form-control" placeholder=" Annoucement Title"
                                                name="title" required>
                                        </div>
                                        <div class="mb-4">

                                            <textarea name="message" id="" class="form-control" required
                                                placeholder="Annoucement Message"></textarea>
                                        </div>

                                        <div class="mb-4">
                                            <select name="class_id" class="form-control" required
                                                aria-placeholder="Select Class">
                                                <option value="" disabled selected>Select Class</option>
                                                <?php
                                                $teacher_id = $_SESSION['user_id'];
                                                $classes = mysqli_query($conn, "SELECT DISTINCT c.class_id,c.class_name,c.section 
                                                        FROM classes c 
                                                        JOIN class_subject_teacher cst ON cst.class_id = c.class_id
                                                        WHERE cst.teacher_id = '$teacher_id'");
                                                while ($row = mysqli_fetch_assoc($classes)) {
                                                    $selected = (isset($_GET['class_id']) && $_GET['class_id'] == $row['class_id']) ? "selected" : "";
                                                    echo "<option value='{$row['class_id']}' $selected>{$row['class_name']} - {$row['section']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-lg btn-primary w-100" name="Add">Add
                                                Annoucement</button>
                                        </div>
                                    </form>

                                </div>


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