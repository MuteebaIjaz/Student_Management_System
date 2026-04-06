<?php
session_start();
require_once "../includes/conn.php";


if (!isset($_SESSION['user_id'])) {
    header("Location:Login.php");
    exit();
}
$user_id = $_SESSION["user_id"];
$query = "SELECT s.*, u.Email, u.Name
          FROM students s 
          JOIN users u ON s.user_id = u.user_id 
          WHERE s.user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if (isset($_POST['Update_Profile'])) {
    $class_id = $_POST['class_id'];
    $RollNo = $_POST['RollNo'];
    $Gender = $_POST['Gender'];
    $DOB = $_POST['DOB'];
    $PhoneNo = $_POST['PhoneNo'];
    $Address = $_POST['Address'];

    $update_query = "UPDATE students SET 
        class_id = '$class_id', 
        Roll_no = '$RollNo', 
        gender = '$Gender', 
        dob = '$DOB', 
        phone = '$PhoneNo', 
        address = '$Address' 
        WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "Profile updated successfully!";

    
        if (!empty($_FILES['Profile_Image']['name'])) {
            $fileName = $_FILES['Profile_Image']['name'];
            $tmp = $_FILES['Profile_Image']['tmp_name'];
            $fileError = $_FILES['Profile_Image']['error'];
            
            if ($fileError === 0) {
                $uploadDir = "../student_images/";
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = "user_" . $user_id . "_profile." . $fileExt;
                $typesAllowed = ['png', 'jpeg', 'jpg'];

                if (in_array($fileExt, $typesAllowed)) {
                    if (move_uploaded_file($tmp, $uploadDir . $newFileName)) {
                        mysqli_query($conn, "UPDATE students SET Profile_Image = '$newFileName' WHERE user_id = '$user_id'");
                    }
                }
            }
        }

        header("Location: student.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update profile.";
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

    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/favicon.ico">

    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/vendors/css/vendors.min.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/theme.min.css">

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
                                <input type="text" class="form-control" placeholder="Roll Number" name="RollNo"value="<?php echo $student['Roll_no']; ?>"
                                    required>
                            </div>


  <div class="mb-4">
                                <select name="Gender" class="form-control" required>
                                    <option value="" disabled <?php echo empty($student['gender']) ? 'selected' : ''; ?>>Select Gender</option>
                                    <option value="Male"   <?php echo ($student['gender'] ?? '') === 'Male'   ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($student['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other"  <?php echo ($student['gender'] ?? '') === 'Other'  ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <select name="class_id" class="form-control" required>
                                    <option value="" disabled <?php echo empty($student['class_id']) ? 'selected' : ''; ?>>Select Class</option>
                                    <?php
                                    $classes_query = "SELECT * FROM `classes` ORDER BY `classes`.`class_name` ASC, `classes`.`section` ASC";
                                    $classes_result = mysqli_query($conn, $classes_query);
                                    while ($row = mysqli_fetch_assoc($classes_result)): 
                                    ?>
                                        <option value="<?php echo intval($row['class_id']); ?>"
                                            <?php echo (isset($student['class_id']) && $student['class_id'] == $row['class_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($row['class_name'] . ' - ' . $row['section']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                              <div class="mb-4">
                                <input
                                    type="date"
                                    class="form-control"
                                    name="DOB"
                                    value="<?php echo ($student['dob'] ?? ''); ?>"
                                    required>
                            </div>
 
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text">+92</span>
                                    <input type="text" name="PhoneNo" class="form-control" placeholder="3XXXXXXXXX"value="<?php echo $student['phone']?>"
                                        pattern="3[0-9]{9}" maxlength="10" required>
                                </div>
                            </div>


                            <div class="mb-4">
                                <input type="text" class="form-control" placeholder="Address" value="<?php echo $student['address']?>" name="Address" required>
                            </div>
                            <div class="mb-4">
                                <input type="file" name="Profile_Image" id="" class="form-control" 
                               >
                        <img src="../student_images/<?php echo $student['Profile_Image'] ?>"
                         width="150px" alt="Profile Picture">
                            </div>
                            <div>

                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" name="Update_Profile">Update
                                    Profile</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </main>

    <!--! BEGIN: Vendors JS !-->
    <script src="../assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="../assets/js/common-init.min.js"></script>
    <!--! END: Apps Init !-->

</body>

</html>