<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location:Login.php");
    exit();
}
session_unset();
session_destroy();
header("Location:Login.php");
exit();

?>