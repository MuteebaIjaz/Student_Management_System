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
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

  .page { padding: 1.5rem 0; }
      .class-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--color-background-secondary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-md);
    padding: 5px 12px; font-size: 13px;
    color: var(--color-text-secondary); margin-bottom: 10px;
  }
  .dot { width: 7px; height: 7px; border-radius: 50%; background: #1D9E75; display: inline-block; }
  h1 { font-size: 22px; font-weight: 500; color: var(--color-text-primary); margin-bottom: 4px; }
  .subtitle { font-size: 14px; color: var(--color-text-secondary); margin-bottom: 1.5rem; }

  .summary-row {
    display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 1.5rem;
  }
  .stat-card {
    background: var(--color-background-secondary);
    border-radius: var(--border-radius-md); padding: 14px 16px;
  }
  .stat-label { font-size: 12px; color: var(--color-text-secondary); margin-bottom: 5px; }
  .stat-value { font-size: 22px; font-weight: 500; color: var(--color-text-primary); }
  .stat-value.green { color: #0F6E56; }
  .stat-value.amber { color: #854F0B; }

  .subjects-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 14px;
  }

  .subject-card {
    background: var(--color-background-primary);
    border: 0.5px solid var(--color-border-tertiary);
    border-radius: var(--border-radius-lg);
    padding: 1rem 1.25rem;
    transition: border-color 0.15s;
  }
  .subject-card:hover { border-color: var(--color-border-primary); }

  .card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }

  .subject-icon {
    width: 40px; height: 40px; border-radius: var(--border-radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 500;
  }

  .tag {
    font-size: 11px; font-weight: 500; padding: 3px 9px;
    border-radius: var(--border-radius-md); letter-spacing: 0.3px;
  }
  .tag.core { background: #E1F5EE; color: #085041; }
  .tag.elective { background: #EEEDFE; color: #3C3489; }

  .subject-name { font-size: 15px; font-weight: 500; color: var(--color-text-primary); margin-bottom: 2px; }
  .subject-code { font-size: 12px; color: var(--color-text-tertiary); }

  .teacher-row { display: flex; align-items: center; gap: 8px; margin: 12px 0; }
  .avatar {
    width: 26px; height: 26px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 500; flex-shrink: 0;
  }
  .teacher-name { font-size: 13px; color: var(--color-text-secondary); }

  .divider { border: none; border-top: 0.5px solid var(--color-border-tertiary); margin: 12px 0; }

  .metrics-row { display: flex; gap: 16px; }
  .metric { flex: 1; }
  .metric-label { font-size: 11px; color: var(--color-text-tertiary); margin-bottom: 4px; }
  .metric-value { font-size: 14px; font-weight: 500; margin-bottom: 5px; }
  .progress-bar { height: 4px; border-radius: 2px; background: var(--color-background-secondary); overflow: hidden; }
  .progress-fill { height: 100%; border-radius: 2px; }

  .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 14px; }
  .pending { font-size: 12px; color: var(--color-text-secondary); display: flex; align-items: center; gap: 5px; }
  .pending-dot { width: 6px; height: 6px; border-radius: 50%; background: #BA7517; display: inline-block; }
  .no-pending { font-size: 12px; color: var(--color-text-tertiary); }

  .view-btn {
    font-size: 12px; padding: 5px 12px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: transparent; color: var(--color-text-secondary); cursor: pointer;
  }
  .view-btn:hover { background: var(--color-background-secondary); }
</style>

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
      




<br>  


<div class="container-fluid">
<div class="page">

  <div style="margin-bottom:1.5rem;">
    <?php
    $class_query="SELECT * FROM classes WHERE class_id='$class_id'";
    $class_result=mysqli_query($conn, $class_query);
    $class=mysqli_fetch_assoc($class_result);
    ?>
    <div class="class-badge"><span class="dot"></span><?php echo $class['class_name']; ?> — <?php echo $class['section']; ?></div>
    <h1><b>My Subjects</b></h1>
    <p class="subtitle"><?php echo mysqli_num_rows($result); ?> subjects assigned to your class this semester</p>
  </div> 

  <!-- <div class="summary-row">
    <div class="stat-card">
      <div class="stat-label">Total Subjects</div>
      <div class="stat-value">6</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Avg. Attendance</div>
      <div class="stat-value green">81%</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Avg. Grade</div>
      <div class="stat-value">74%</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Pending Tasks</div>
      <div class="stat-value amber">3</div>
    </div>
  </div> -->

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
        <span class="teacher-name"><?php echo $subjects['teacher_name']?></span>
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
