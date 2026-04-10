<?php
require_once "../includes/conn.php";

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "student") {
    header("Location:../Login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "
    SELECT s.student_id, u.Name AS student_name, c.class_name, c.section
    FROM   users u
    JOIN   students  s ON s.user_id  = u.user_id
    JOIN   classes   c ON c.class_id = s.class_id
    WHERE  u.user_id = '$user_id'
";
$query_result = mysqli_query($conn, $query);
$student_info = mysqli_fetch_assoc($query_result);
$student_id = $student_info['student_id'];

$result_query = "
   SELECT ft.name, ft.amount, ft.due_date,
            fp.amount_paid, fp.payment_date, fp.status, fp.remarks
     FROM fee_types ft
     LEFT JOIN fee_payments fp 
       ON ft.fee_type_id = fp.fee_type_id AND fp.student_id = '$student_id'
     ORDER BY ft.due_date ASC
";
$result = mysqli_query($conn, $result_query);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <title>Fee | SMS</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png?v=11" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="student.css">
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
                        <li class="breadcrumb-item"><a href="student.php">Home</a></li>
                        <li class="breadcrumb-item">Fee</li>
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

            <div class="page">

                <div class="class-badge">
                    <h1><b>My Fee</b></h1>
                </div>

                <div class="result-page-header d-flex align-items-center justify-content-between flex-wrap gap-2 pt-0">
                    <div>
                        <div class="class-chip">
                            <span class="chip-dot"></span>
                            <?php echo ($student_info['class_name'] . ' — ' . $student_info['section']); ?>
                        </div>

                    </div>

                </div>

                <div class="table-section">
                    <div class="section-label">Fee Status</div>
                    <div class="table-wrap">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Fee Type</th>
                                    <th>Total Amount</th>
                                    <th>Due Date</th>
                                    <th>Amount Paid</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($fee = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo ($fee['name']); ?></td>
                                        <td><?php echo ($fee['amount']); ?></td>
                                        <td><?php echo $fee['due_date'] ? date('d M Y', strtotime($fee['due_date'])) : 'N/A'; ?>
                                        </td>
                                        <td><?php echo $fee['amount_paid'] ? ($fee['amount_paid']) : '0'; ?></td>
                                        <td><?php echo $fee['payment_date'] ? date('d M Y', strtotime($fee['payment_date'])) : 'Not Paid'; ?>
                                        </td>
                                        <td>
                                            <?php if ($fee['status'] == 'Paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php elseif ($fee['status'] == 'Partial'): ?>
                                                <span class="badge bg-warning">Partial</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Unpaid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo ($fee['remarks'] ?? ''); ?>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>
                            </tbody>


                        </table>
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
