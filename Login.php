<?php

require_once "includes/conn.php";
session_start();

if (isset($_POST['Login'])) {

    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $query = "SELECT * FROM `users` WHERE Email = '$Email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($Password, $user['Password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['Name'];
            $_SESSION['user_role'] = $user['Role'];
            if ($user['Role'] == "admin") {
                header("location:admin/admin.php");
                exit();
            } else if ($user['Role'] == "teacher" ) {
                header("location:../teacher.php");
exit();
            } else if($user['Role'] == "student" ){
                 if ($user['Status'] == "Approved") {
                    header("Location: student/student.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Your account is not approved yet!";
                    header("Location: Pending.php");
                    exit();
                }

            }
                   } else {
            $_SESSION['error'] = "Invalid Password!";
            header("Location: ../Login.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Invalid Email!";
        header("Location: ../Login.php");
        exit();
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
  
    <title>Login</title>

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
                        <h4 class="fs-13 fw-bold mb-2 text-center">Login to your account</h4>
                        <form action="" class="w-100 mt-4 pt-2" method="post">
                            <div class="mb-4">
                                <input type="email" class="form-control" placeholder="Email "  name="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Password"  name="Password" required>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="rememberMe">
                                        <label class="custom-control-label c-pointer" for="rememberMe">Remember Me</label>
                                    </div>
                                </div>
                                <div>
                                    <a href="auth-reset-minimal.html" class="fs-11 text-primary">Forget password?</a>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" name="Login">Login</button>
                            </div>
                        </form>
                        <div class="w-100 mt-4 text-center mx-auto">
                            <div class="mb-4 border-bottom position-relative"><span class="small py-1 px-3 text-uppercase text-muted bg-white position-absolute translate-middle">or</span></div>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login with Facebook">
                                    <i class="feather-facebook"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login with Twitter">
                                    <i class="feather-twitter"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login with Github">
                                    <i class="feather-github text"></i>
                                </a>
                            </div>
                        </div>
                        <div class="mt-4 text-muted">
                            <span> Don't have an account?</span>
                            <a href="Register.php" class="fw-bold">Create an Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
   
    <!--! BEGIN: Vendors JS !-->
    <script src="assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="assets/js/common-init.min.js"></script>
    <!--! END: Apps Init !-->
   
</body>

</html>