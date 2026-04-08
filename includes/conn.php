<?php

<<<<<<< HEAD
$conn = mysqli_connect("localhost","root","","student_management_system","3306");
=======
$db_host = getenv('DB_HOST') ?: "localhost";
$db_user = getenv('DB_USER') ?: "root";
$db_pass = getenv('DB_PASS') ?: "";
$db_name = getenv('DB_NAME') ?: "student_management_system";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, "3306");
>>>>>>> f574924799af43f062b3888e2829df8feb9439bc
if(!$conn){
    die("DB Connection Failed".mysqli_connect_error());
}
?>