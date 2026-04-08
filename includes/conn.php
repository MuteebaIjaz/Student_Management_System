<?php

$conn = mysqli_connect("localhost","root","","student_management_system","3306");
if(!$conn){
    die("DB Connection Failed".mysqli_connect_error());
}
?>