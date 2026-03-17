<?php
require_once "../includes/conn.php";

session_start();
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}
$student_id=$_SESSION['user_id'];
$select_query="SELECT `class_id` FROM `students` WHERE `user_id`= $student_id";
$select_result=mysqli_query($conn,$select_query);
$student=mysqli_fetch_assoc($select_result);
$class_id=$student['class_id'];
$query="SELECT subject.* , users.name AS teacher_name, class_subject_teacher.id 
FROM class_subject_teacher
JOIN  subject ON subject.subject_id= class_subject_teacher.subject_id
JOIN users ON users.user_id= class_subject_teacher.teacher_id
WHERE class_subject_teacher.class_id=$class_id";

$result=mysqli_query($conn, $query);

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
    <link rel="stylesheet" href="profile.css">
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
      
<div class="container mt-3 mb-3 bg-white">

<div class="row">
<div class="col-lg-12">
    <div class="subject-card">
    <?php
    $class_query="SELECT * FROM classes WHERE class_id='$class_id'";
    $class_result=mysqli_query($conn, $class_query);
    $class=mysqli_fetch_assoc($class_result);
    ?>
    <div class="class-badge">
    <h1><b>My Subjects</b></h1>
  </div> 
 <div class="subjects-grid">
<?php
while($subjects=mysqli_fetch_assoc($result)){
    $words = explode(' ', $subjects['teacher_name']);
$initials = strtoupper($words[0][0] . (isset($words[1]) ? $words[1][0] : ''));

?>
    <!-- Mathematics -->
    <div class="subject-card">
      <div class="card-top">
        <div class="subject-icon" style="background:#E6F1FB;color:#0C447C;"><?php echo $subjects['subject_name'][0]; ?></div>
        <span class="tag core"><?php echo ucfirst($subjects['type'])?></span>
      </div>
      <div class="subject-name"><?php echo $subjects['subject_name']; ?></div>
      <div class="subject-code"><?php echo $subjects['code']?></div>
      <div class="teacher-row">
        <div class="avatar" style="background:#B5D4F4;color:#0C447C;"><?php echo $initials; ?></div>
        Teacher:<span class="teacher-name"><?php echo $subjects['teacher_name']?></span>
      </div>
      <hr class="divider">
      <!-- <div class="metrics-row">
        <div class="metric">
          <div class="metric-label">Attendance</div>
          <div class="metric-value" style="color:#1D9E75;">88%</div>
          <div class="progress-bar"><div class="progress-fill" style="width:88%;background:#1D9E75;"></div></div>
        </div>
        <div class="metric">
          <div class="metric-label">Latest grade</div>
          <div class="metric-value" style="color:#BA7517;">79%</div>
          <div class="progress-bar"><div class="progress-fill" style="width:79%;background:#BA7517;"></div></div>
        </div>
      </div>
      <div class="card-footer">
        <span class="pending"><span class="pending-dot"></span>1 task pending</span>
        <button class="view-btn">View details</button>
      </div> -->
    </div>

   
  
 
<?php
}
?>
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
