<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "teacher") {
    header("location:../Login.php");
    exit();
}
$teacher_id = $_SESSION['user_id'];

$query="SELECT 
    classes.class_name,
    classes.section,
    subject.subject_name,
    subject.code,
    subject.type,
    class_subject_teacher.id AS cst_id
FROM class_subject_teacher
JOIN subject ON subject.subject_id = class_subject_teacher.subject_id
JOIN classes ON classes.class_id = class_subject_teacher.class_id
WHERE class_subject_teacher.teacher_id=$teacher_id";

$result=mysqli_query($conn, $query);
$classes=mysqli_fetch_assoc($result);
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
                                <h4>My Classes:</h4>
                            </div>
  <div class="table-responsive-wrapper">
                                <table class="table registration-table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">SNO</th>
                                            <th scope="col">Class Name</th>
                                            <th scope="col">Class Section</th>
                                            <th scope="col">Subject Name</th>
                                            <th scope="col">Subject Type</th>


                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                     $count=1;
                                      if(mysqli_num_rows($result)>0){
                                      while($class_row=mysqli_fetch_array($result)){
                                            
                                           
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><strong><?php  echo $class_row['class_name']; ?></strong></td>
                                        <td><strong><?php echo $class_row['section']?></strong></td>
                                        <td><strong><?php echo $class_row['subject_name']?></strong></td>
                                        <td><strong>  <?php echo ucfirst($class_row['type']); ?></strong></td>


                                          
                                            <td>
                                            
                                            </td>
                                        </tr>
                                        <?php
                                                $count++;
                                            }
                                        }else {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted-sm">
                                                 No Classes found
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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