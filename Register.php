<?php
require_once "includes/conn.php";
session_start();
if(isset($_POST['Register'])){
    $Name = $_POST['Name'];
    $Email =$_POST['Email'];
    $Password = $_POST['Password'];
    $ConfirmPass = $_POST['ConfirmPass'];
    $Role="student";
    $Status = "pending";

 if($Password !== $ConfirmPass ){
        $_SESSION['error']="Passwords donot match.";
          header("Location:Register.php");
        exit();
    }

    $select_query= "SELECT * FROM `users` WHERE Email = '$Email'";
    $select_result=mysqli_query($conn,$select_query);
    if(mysqli_num_rows($select_result) > 1){
        $_SESSION['error']="Email Already Exists.";
        header("Loaction:Register.php");
        exit();
    }
   
    $hashed_password = password_hash($Password,PASSWORD_DEFAULT);

    $query="INSERT INTO `users` 
    (Name , Email, Password , Role , Status) VALUES 
    ('$Name','$Email','$hashed_password','$Role','$Status') ";
    $result= mysqli_query($conn , $query);
    if($result){
        header("Location: Pending.php");
    }
}

?>



<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="theme_ocean">
    <title>Register</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/theme.min.css">
</head>

<body>
    <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">
                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                   
                    <div class="card-body p-sm-5">
                        <h2 class="fs-20 fw-bolder mb-4 text-center">Student Management System</h2>
                        <h4 class="fs-13 fw-bold mb-2 text-center">Register Yourself</h4>

                        <?php
                       if( isset($_SESSION['error'])){
                        echo "<div class='w-100 mt-4 pt-2 text-danger'>".$_SESSION['error']."</div>";
                         unset($_SESSION['error']);
                        }
                        if( isset($_SESSION['success'])){
                        echo "<div class='w-100 mt-4 pt-2 text-success'>".$_SESSION['success']."</div>";
                         unset($_SESSION['success']);
                        }
                       
                        ?>
                        <form action="" method="post" class="w-100 mt-4 pt-2">
                            <div class="mb-4">
                                <input type="text" class="form-control" placeholder="Full Name" name="Name" required>
                            </div>
                            <div class="mb-4">
                                <input type="email" class="form-control" placeholder="Email" name="Email" required>
                            </div>
                           
                            <div class="mb-4 generate-pass">
                                <div class="input-group field">
                                    <input type="password" name="Password" class="form-control password" id="newPassword" placeholder="Password Confirm">
                                   
                                </div>
                                <div class="progress-bar mt-2">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <input type="password" class="form-control" placeholder="Password again"  name="ConfirmPass" required>
                            </div>
                           
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" name="Register">Register</button>
                            </div>
                        </form>
                        <div class="mt-4 text-muted">
                            <span>Already have an account?</span>
                            <a href="Login.php" class="fw-bold">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
   
    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/vendors/js/lslstrength.min.js"></script>
  
</body>

</html>