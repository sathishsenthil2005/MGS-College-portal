<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$conn = mysqli_connect("localhost","root","","college_portal");

if(!$conn){
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
