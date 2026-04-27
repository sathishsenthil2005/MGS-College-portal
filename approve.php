<?php
include("../config/db.php");

$id=$_GET['id'];

$message = "Your application has been approved come with college Earlier🎉";

mysqli_query($conn,"
UPDATE admissions 
SET status='approved', message='$message' 
WHERE id=$id
");

header("Location:index.php");
?>