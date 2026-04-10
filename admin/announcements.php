<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "admin") {
    header("location:../Login.php");
    exit();
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
    <title>Announcements | SMS</title>
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
                        <li class="breadcrumb-item">Announcements</li>
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
                    <div class="col-12">
                        <div class="registration-table-card">
                            <div class="registration-table-header">
                                <h4>Announcements by Class:</h4>
                            </div>

                            <div class="card mb-4 mx-4 mx-sm-0 position-relative">
                                <div class="card-body p-sm-5">
                                    <form method="GET" action="">
                                        <div class="mb-4 w-50">
                                            <select name="class_id" class="form-control" required>
                                                <option value="" disabled selected>Select Class</option>
                                                <?php
                                                $query = "SELECT * FROM `classes` ORDER BY `classes`.`section` ASC";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $display = $row['class_name'] . " - " . $row['section'];
                                                    $selected = (isset($_GET['class_id']) && $_GET['class_id'] == $row['class_id']) ? "selected" : "";
                                                    echo '<option value="' . $row['class_id'] . '" ' . $selected . '>' . $display . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-4">View Announcements</button>
                                    </form>

                                    <?php
                                    if (isset($_GET['class_id'])) {
                                        $class_id = intval($_GET['class_id']);
                                        $query = "SELECT a.*, u.Name as sender_name 
                                                  FROM announcements a 
                                                  LEFT JOIN users u ON a.sender_id = u.user_id
                                                  WHERE a.target_audience = $class_id
                                                  ORDER BY a.created_at DESC";
                                        $result = mysqli_query($conn, $query);
                                        if (mysqli_num_rows($result) > 0) {
                                            echo '<div class="table-responsive-wrapper">';
                                            echo ' <table class="table registration-table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">SNO</th>
                                                                        <th scope="col">Title</th>
                                                                        <th scope="col">Message</th>
                                                                        <th scope="col">Announcement By</th>
                                                                        <th scope="col">Date</th>
                                                                        <th scope="col">Time</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';

                                            $count = 1;
                                            while ($announcement = mysqli_fetch_assoc($result)) {
                                                $date = date('Y-m-d', strtotime($announcement['created_at']));
                                                $time = date('h:i A', strtotime($announcement['created_at']));
                                                $sender = !empty($announcement['sender_name']) ? ($announcement['sender_name']) . ' (' . ucfirst($announcement['sender_role']) . ')' : 'System';
                                                echo '<tr>';
                                                echo '<td>' . $count++ . '</td>';
                                                echo '<td>' . ($announcement['title']) . '</td>';
                                                echo '<td>' . ($announcement['message']) . '</td>';
                                                echo '<td>' . $sender . '</td>';
                                                echo '<td>' . $date . '</td>';
                                                echo '<td>' . $time . '</td>';
                                                echo '</tr>';
                                            }

                                            echo '</tbody></table></div>';
                                        } else {
                                            echo '<div class="alert alert-info">No announcements found in this class.</div>';
                                        }
                                    }
                                    ?>
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