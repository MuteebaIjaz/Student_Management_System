<?php
require_once "../includes/conn.php";
require_once "../includes/send_email.php";
session_start();
if(isset($_GET['approve'])){
    $id = $_GET['approve'];

    $fetch_query="SELECT * FROM `users` WHERE Role='student';";
    $fetch_result=mysqli_query($conn,$fetch_query);
    $student=mysqli_fetch_assoc($fetch_result);
$query="UPDATE `users` SET Status = 'approved' WHERE user_id = '$id'";
$result=mysqli_query($conn,$query);
 $subject = "Account Approved - SMS";
    $message = "
        <h3>Hello {$student['Name']},</h3>
        <p>Your account has been <b style='color:green;'>APPROVED</b>.</p>
        <p>You can now login to the system.</p>
        <br>
        <a href='http://localhost/STMS/Login.php' class='btn btn-primary'>Login Now</a>
    ";

    sendEmail($student['Email'], $student['Name'], $subject, $message);

    $_SESSION['success'] = "Student approved & email sent!";
    header("Location: ../admin/registration_request.php");
    exit();

}

if(isset($_GET['reject'])){
      $id = $_GET['reject'];

    $fetch_query="SELECT * FROM `users` WHERE Role='student';";
    $fetch_result=mysqli_query($conn,$fetch_query);
    $student=mysqli_fetch_assoc($fetch_result);
$query="UPDATE `users` SET Status = 'Rejected' WHERE user_id = '$id'";
$result=mysqli_query($conn,$query);
 $subject = "Account Rejected - SMS";
    $message = "
        <h3>Hello {$student['Name']},</h3>
        <p>Your account has been <b style='color:red;'>REJECTED</b>.</p>
        <p>There must be some mistake. Please contact admin.</p>
        <br>
    ";

    sendEmail($student['Email'], $student['Name'], $subject, $message);

    $_SESSION['error'] = "Student request rejected & email sent!";
    header("Location: ../admin/registration_request.php");
    exit();

}
?>