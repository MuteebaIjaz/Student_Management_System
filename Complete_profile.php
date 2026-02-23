<?php
session_start();
require_once "includes/conn.php";
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
                                <input type="text" class="form-control" placeholder="Roll Number" name="Roll_No" required>
                            </div>
                            <div class="mb-4">
                                <label for="">Gender</label>
                                <br>
                               <select name="Class" id="">
<option value="">A</option>

</select>
                            </div>
                           <div class="mb-4">
    <label for="Class">Class</label>
    <br>
    <select name="Class" id="Class" required>
        <option value="">Select Class</option>
        <?php
        $query="SELECT * FROM `classes` ORDER BY `classes`.`section` ASC";
        $result= mysqli_query($conn,$query);
        while($row=mysqli_fetch_assoc($result)){
            $display = $row['class_name'] . " - " . $row['section'];
            echo '<option value="'.$row['id'].'">'.$display.'</option>';
        }
        ?>
    </select>
</div>
                           
                            <div class="mb-4">
                                <input type="Date" class="form-control" placeholder="DOB" name="DOB" required>
                            </div>
                            <div class="mb-4">
                                <label for="">Phone Number</label>
                                <br>
                                 
       
      <input type="text"  class="form-control" name="phone" placeholder="03001234567" maxlength="10" pattern="[0-9]{10}" required style="margin-left:5px;">
    </div>
                          
                             <div class="mb-4">
                                <input type="text" class="form-control" placeholder="Address" name="Address" required>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" name="Save_Profile">Complete Profile</button>
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