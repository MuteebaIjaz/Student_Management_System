<?php
session_start();

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
                        <h2 class="fs-20 fw-bolder mb-4 text-center">
                            Student Management System
                        </h2>
                        <h4 class="fs-13 fw-bold mb-2 text-center">
                            Your request for registration has been received. 
                            Kindly wait till admin approve your request.
                        </h4>
                        <p>We will notify you by sending you an email. Thankyou!</p>
                     
                       
                        <div class="mt-4 text-center">
                            
                            <a href="Login.php" class="fw-bold btn btn-primary" > Back to Login</a>
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