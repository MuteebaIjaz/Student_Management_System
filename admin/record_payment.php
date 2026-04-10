<?php
require_once "../includes/conn.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "admin") {
    header("location:../Login.php");
    exit();
}


if (isset($_POST['Record'])) {
    $student_id  = $_POST['student_id'];
    $fee_type_id = $_POST['fee_type_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $status      = $_POST['status'];
    $remarks     = $_POST['remarks'];
    $recorded_by = $_SESSION['user_id'];
if($pa){

}
    $query = "INSERT INTO `fee_payments` (`student_id`, `fee_type_id`, `amount_paid`, `payment_date`, `status`, `remarks`, `recorded_by`) VALUES ('$student_id', '$fee_type_id', '$amount_paid', '$payment_date', '$status', '$remarks', '$recorded_by')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['success'] = "Payment recorded successfully!";
    } else {
        $_SESSION['error'] = "Failed to record payment.";
    }
    header("Location: record_payment.php"); 
    exit();
}

$students = mysqli_query($conn,
    "SELECT s.student_id, u.Name, s.Roll_no 
     FROM students s JOIN users u ON s.user_id = u.user_id");

$fee_types = mysqli_query($conn, "SELECT * FROM fee_types");
?>

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
    <title>Fee Payment | SMS</title>
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
                        <li class="breadcrumb-item">Fee Payment</li>
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
                                <h4>Record Fee Payments:</h4>
                            </div>
                            <div>

                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="mb-4 w-50">
                        <select name="student_id" class="form-control" required>
                            <option value="" disabled selected>Select Student</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($students)) {
                                echo '<option value="' . $row['student_id'] . '">' . $row['Name'] . ' (' . $row['Roll_no'] . ')</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4 w-50">
                        <select name="fee_type_id" class="form-control" required>
                            <option value="" disabled selected>Select Fee Type</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($fee_types)) {
                                echo '<option value="' . $row['fee_type_id'] . '">' . $row['name'] . ' (Rs ' . $row['amount'] . ')</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4 w-50">
                        <input type="number" name="amount_paid" class="form-control" placeholder="Amount Paid" required>
                    </div>
                    <div class="mb-4 w-50">
                        <input type="date" name="payment_date" class="form-control" placeholder="Payment Date" required>
                    </div>
                    <div class="mb-4 w-50">
                        <select name="status" class="form-control" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Partial">Partial</option>
                            
                        </select>
                    </div>
                    <div class="mb-4 w-50">
                        <textarea name="remarks" class="form-control" placeholder="Remarks (Optional)" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mb-4" name="Record">Record Fee Payment</button>
                </form>
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
