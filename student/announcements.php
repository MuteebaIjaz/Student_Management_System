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
      


 
        <div class="registration-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="registration-table-card">
                            <div class="registration-table-header">
                                <h4>All Announcements:</h4>
                            </div>
                            <?php
                            $user_id =$_SESSION['user_id'];
                            $select_query="SELECT `class_id` FROM `students` WHERE `user_id` = '$user_id'";
                            $select_result=mysqli_query($conn,$select_query);
                            $student=mysqli_fetch_assoc($select_result);
                           $count=1;
                            $class_id=$student['class_id'];
                            $query="SELECT * FROM `announcements` WHERE `target_audience` = '$class_id' ORDER BY  `created_at` DESC LIMIT 5";
                            $result=mysqli_query($conn,$query);
                            if(mysqli_num_rows($result) > 0){
                                while($announcement = mysqli_fetch_assoc($result)){
                                    ?>
                                    <div class="card mt-3 mx-3" style="width: 25rem;">
  <div class="card-body">
    <h5 class="card-title"><?php echo $count++?>.<?php echo $announcement['title']?>
</h5>
  
    <p class="card-text"><?php echo $announcement['message']?></p>
      <small class="text-muted">
            <i class="feather-calendar me-1"></i>
            <?= date('F j, Y g:i A', strtotime($announcement['created_at'])) ?>
        </small>
  </div>
</div>
                                    <?php
                                }
                            }
                            ?>
                           
                          
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