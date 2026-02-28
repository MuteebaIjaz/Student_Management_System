<?php
session_start();
require_once "includes/conn.php";


if (!isset($_SESSION['user_id'])) {
    header("Location:Login.php");
    exit();
}

if (isset($_POST['Save_Password'])) {
    $newPassword = $_POST['new_password'];
    $user_id = $_SESSION['user_id'];
    $hashedpassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $confirmPassword = $_POST['confirm_password'];
    if (strlen($newPassword) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters!";
        header("Location: change_password.php");
        exit();
    }
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Passwords donot match!";
        header("Location:change_password.php");
        exit();
    }

    $updateQuery = "UPDATE `users` SET `is_first_login` = 0,password = '$hashedpassword' WHERE user_id = $user_id";
    $updateResult = mysqli_query($conn, $updateQuery);
    if ($updateResult) {
        header("Location:teacher/teacher.php");
        exit();
    } else {
        $_SESSION['error'] = "There is an error in changing password!";
        header("Location:change_password.php");
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
                        <h2 class="fs-20 fw-bolder mb-4 text-center">
                            Enter New Password:
                        </h2>
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
                        <form action="" method="post" class="w-100 mt-4 pt-2">

                            <div class="mb-4">
                                <input type="password" class="form-control" placeholder="Change Password"
                                    name="new_password" required>
                            </div>
                            <div class="mb-4">
                                <input type="password" class="form-control" placeholder="Confirm Password"
                                    name="confirm_password" required>
                            </div>


                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" name="Save_Password">
                                    Change Password</button>
                            </div>
                        </form>


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