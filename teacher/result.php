<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "teacher") {
    header("location:../Login.php");
    exit();
}
$teacher_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $exam_type = $_POST['exam_type'];
    $date = $_POST['date'];

    $marks_array = $_POST['marks'];
    $total_marks_array = $_POST['total_marks'];

    foreach ($marks_array as $student_id => $marks) {

        $marks = (int)$marks;
        $total_marks = (int)$total_marks_array[$student_id];

        $check_query = mysqli_query($conn, "SELECT marks_id FROM marks WHERE student_id='$student_id' AND subject_id='$subject_id' AND exam_type='$exam_type' AND date='$date'");
        if (mysqli_num_rows($check_query) > 0) {
            $query = "UPDATE marks SET `marks`='$marks', `total_marks`='$total_marks' WHERE student_id='$student_id' AND subject_id='$subject_id' AND exam_type='$exam_type' AND date='$date'";
        } else {
            $query = "INSERT INTO marks (`marks`,`subject_id`,`student_id`,`exam_type`,`date`,`total_marks`) 
                      VALUES ('$marks','$subject_id','$student_id','$exam_type','$date','$total_marks')";
        }

        mysqli_query($conn, $query);
    }

    if (isset($_POST['edit_marks'])) {
        $_SESSION['success'] = "Marks Updated Successfully";
    } else {
        $_SESSION['success'] = "Marks Uploaded Successfully";
    }
    header("Location: result.php");
    exit();
}
//for class selection
if(isset($_GET['class_id'])){
    $selected_class_id=$_GET['class_id'];
}else{
    $selected_class_id=null;
}
//for subject selection
if(isset($_GET['subject_id'])){
$selected_subject_id=$_GET['subject_id'];
}else{
    $selected_subject_id=null;
}
//for exam type selection
if(isset($_GET['exam_type'])){
$selected_exam_type=$_GET['exam_type'];
}else{
    $selected_exam_type='Quiz';
}

//for date selection
if(isset($_GET['date'])){
$selected_date=$_GET['date'];
}else{
    $selected_date=date('Y-m-d');
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
    <title>Result | SMS</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png?v=11" />
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
                        <li class="breadcrumb-item"><a href="teacher.php">Home</a></li>
                        <li class="breadcrumb-item">Result</li>
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
                                <h4>Upload Result:</h4>
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
                                    <form method="GET" class="mb-4">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Select Class</label>
                                                <select name="class_id" class="form-control" required
                                                    onchange="this.form.submit()">
                                                    <option disabled selected>Select Class</option>
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

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Select Subject</label>
                                                <select name="subject_id" class="form-control" required <?php echo isset($_GET['class_id']) ? "" : "disabled"; ?>>
                                                    <option disabled selected>Select Subject</option>
                                                    <?php
                                                    if (isset($_GET['class_id'])) {
                                                        $class_id = $_GET['class_id'];
                                                        $subjects = mysqli_query($conn, "SELECT s.subject_id,s.subject_name 
                                                             FROM subject s 
                                                             JOIN class_subject_teacher cst ON cst.subject_id = s.subject_id 
                                                             WHERE cst.teacher_id = '$teacher_id' 
                                                             AND cst.class_id = '$class_id'");
                                                        while ($row = mysqli_fetch_assoc($subjects)) {
                                                            $selected = (isset($_GET['subject_id']) && $_GET['subject_id'] == $row['subject_id']) ? "selected" : "";
                                                            echo "<option value='{$row['subject_id']}' $selected>{$row['subject_name']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
 <div class="col-md-4 mb-3">
                                            <label class="form-label">Exam Type</label>
                                            <select name="exam_type" class="form-control">
                                                <?php foreach (['Quiz','Mid','Final','Assignment'] as $type): ?>
                                                    <option value="<?php echo $type; ?>"
                                                        <?php echo $selected_exam_type === $type ? 'selected' : ''; ?>>
                                                        <?php echo $type; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                          <div class="col-md-4 mb-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" name="date" class="form-control"
                                                value="<?php echo htmlspecialchars($selected_date); ?>">
                                        </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Load Students</button>
                                    </form>

                                    <?php
                                    if ($selected_class_id && $selected_subject_id) {
                                     

                                        $students = mysqli_query($conn, 
                                        "SELECT st.student_id, st.Roll_no, u.Name AS student_name,
                                        m.marks,m.total_marks
                                        FROM students st
                                        JOIN users u ON u.user_id = st.user_id
                                        LEFT JOIN marks m
                                        ON m.student_id = st.student_id
                                        AND m.subject_id='$selected_subject_id'
                                        AND m.exam_type='$selected_exam_type'
AND m.date='$selected_date'
WHERE st.class_id='$selected_class_id' ORDER BY st.Roll_no 
                                        "
                                    
                                    );


                                        if (mysqli_num_rows($students) > 0) {
                                          

                                            echo '<form method="POST">';
                                            echo '<input type="hidden" name="date" value="' . ($selected_date) . '">';
echo '<input type="hidden" name="exam_type" value="' . ($selected_exam_type) . '">';
                                            echo "<input type='hidden' name='class_id' value='$selected_class_id'>";
                                            echo "<input type='hidden' name='subject_id' value='$selected_subject_id'>";
                                            echo '<div class="table-responsive">';
                                            echo '<table class="table table-bordered">';
                                            echo '<thead><tr><th>S.No</th><th>Roll Number</th><th>Total Marks</th><th>
                                         Marks:  </th><th>Status</th>
                                          
                                          
                                          </tr></thead><tbody>';

                                            $count = 1;
                                            $any_marks_entered = false;
                                            while ($student = mysqli_fetch_assoc($students)) {
                                                $student_id = $student['student_id'];
                                                $entered = $student['marks'] !== null;
                                                if ($entered) {
                                                    $any_marks_entered = true;
                                                }

                                                $current_total_marks = $entered ? htmlspecialchars($student['total_marks']) : '';
                                                $current_marks = $entered ? htmlspecialchars($student['marks']) : '';

                                                echo '<tr>';
                                                echo '<td>' . $count++ . '</td>';
                                                echo '<td>' . htmlspecialchars($student['Roll_no']) . '</td>';
                                                echo '<td><input type="number" name="total_marks[' . $student_id . ']" class="form-control mb-3" value="' . $current_total_marks . '" required></td>';
                                                echo '<td><input type="number" min="0" required name="marks[' . $student_id . ']" class="form-control mb-3" value="' . $current_marks . '"></td>';
                                              
                                                echo '<td>';
                                                if ($entered) {
                                                    echo '<span style="background:#E1F5EE;color:#085041;
                                    padding:3px 10px;border-radius:6px;
                                    font-size:12px;font-weight:500;">
                                    Entered
                                </span>';
                                                } else {
                                                    echo '<span style="background:#FAEEDA;color:#633806;
                                    padding:3px 10px;border-radius:6px;
                                    font-size:12px;font-weight:500;">
                                    Pending
                                </span>';
                                                }
                                                echo '</td>';
                                                echo '</tr>';
                                            }

                                            echo '</tbody></table></div>';

                                            if ($any_marks_entered) {
                                                echo '<button type="submit" name="edit_marks" class="btn btn-warning mb-3 mt-3">Edit Marks</button>';
                                            } else {
                                                echo '<button type="submit" name="save_result" class="btn btn-primary mb-3 mt-3">Save Result</button>';
                                            }

                                            echo '</form>';
                                        } else {
                                            echo '<div class="alert alert-info">No students found in this class.</div>';
                                        }
                                    }
                                    ?>
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
