<?php

$conn = mysqli_connect("localhost","root","","student_management_system","3307");
if(!$conn){
    die("DB Connection Failed".mysqli_connect_error());
}
?>