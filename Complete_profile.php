<?php
session_start();
require_once "includes/conn.php";


if (!isset($_SESSION['user_id'])) {
    header("Location:Login.php");
    exit();
}

if (isset($_POST['Save_Profile'])) {
    $class_id = $_POST['class_id'];
    $user_id = $_SESSION['user_id'];
    $RollNo = $_POST['RollNo'];
    $Gender = $_POST['Gender'];
    $DOB = $_POST['DOB'];
    $PhoneNo = $_POST['PhoneNo'];
    $Address = $_POST['Address'];
    $fileName = $_FILES['Profile_Image']['name'];

    $tmp = $_FILES['Profile_Image']['tmp_name'];
    $fileSize = $_FILES['Profile_Image']['size'];
    $uploadDir = "student_images/";
    $typesAllowed = ['png', 'jpeg', 'jpg'];
    $fileError = $_FILES['Profile_Image']['error'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if ($fileError !== 0) {
        $_SESSION['error'] = "Error uploading file.";
        header("Location: Complete_profile.php");
        exit();
    }
    if (!in_array($fileExt, $typesAllowed)) {
        $_SESSION['error'] = "Only JPG, JPEG & PNG Files are Allowed.";
        header("Location:Complete_profile.php");
        exit();
    }
    $newFileName = uniqid("STD_", true) . "." . $fileExt;

    if ($fileSize > 2 * 1024 * 1024) {
        $_SESSION['error'] = "File size must be less than 2MB.";
        header("Location: Complete_profile.php");
        exit();
    }
    if (!move_uploaded_file($tmp, $uploadDir . $newFileName)) {
        $_SESSION['error'] = "Failed to upload image.";
        header("Location: Complete_profile.php");
        exit();
    }
    if (preg_match('/^03[0-9]{9}$/', $PhoneNo)) {
        $PhoneNo = substr($PhoneNo, 1);
    }
    if (!preg_match('/^3[0-9]{9}$/', $PhoneNo)) {
        $_SESSION['error'] = "Enter valid mobile number (3XXXXXXXXX)";
        header("Location: Complete_profile.php");
        exit();
    }

    $PhoneNo = '+92' . $PhoneNo;
    $checkquery = "SELECT * FROM students WHERE user_id = '$user_id'";
    $check = mysqli_query(
        $conn,
        $checkquery
    );

    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = "Profile already completed!";
        header("Location: student/student.php");
        exit();
    }
    $query = "INSERT INTO `students` (`user_id`, `Roll_no`, `class_id`, `gender`, `dob`, `phone`, `address`, `Profile_Image`)
          VALUES ('$user_id', '$RollNo', '$class_id', '$Gender', '$DOB', '$PhoneNo', '$Address', '$newFileName')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $updateQuery = "UPDATE `users` SET `profile_status` = 1 WHERE user_id = $user_id";
        $updateResult = mysqli_query($conn, $updateQuery);
        header("Location:student/student.php");
        exit();
    } else {
        $_SESSION['error'] = "There is a Mistake in Entering data!";
        header("Location:student/student.php");
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
                            Your Profile:
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
                        <form action="" method="post" class="w-100 mt-4 pt-2" enctype="multipart/form-data">
                            <div class="mb-4">
                                <input type="text" class="form-control" placeholder="Roll Number" name="RollNo"
                                    required>
                            </div>



                            <div class="mb-4">
                                <select name="Gender" class="form-control" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <select name="class_id" class="form-control" required>
                                    <option value="" disabled selected>Select Class</option>
                                    <?php
                                    $query = "SELECT * FROM `classes` ORDER BY `classes`.`section` ASC";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $display = $row['class_name'] . " - " . $row['section'];
                                        echo '<option value="' . $row['class_id'] . '">' . $display . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <input type="Date" class="form-control" placeholder="DOB" name="DOB" required>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text">+92</span>
                                    <input type="text" name="PhoneNo" class="form-control" placeholder="3XXXXXXXXX"
                                        pattern="3[0-9]{9}" maxlength="10" required>
                                </div>
                            </div>


                            <div class="mb-4">
                                <input type="text" class="form-control" placeholder="Address" name="Address" required>
                            </div>
                            <div class="mb-4">
                                <input type="file" name="Profile_Image" id="" class="form-control">
                            </div>
                            <div>

                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" name="Save_Profile">Complete
                                    Profile</button>
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