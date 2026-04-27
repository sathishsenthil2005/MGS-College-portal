<?php
include("../config/db.php");

$dept = $_GET['dept'] ?? '';

echo "<option value=''>Select Student</option>";

$q=mysqli_query($conn,"SELECT id,name FROM students WHERE department='".mysqli_real_escape_string($conn,$dept)."'");

while($r=mysqli_fetch_assoc($q)){
    echo "<option value='{$r['id']}'>{$r['name']}</option>";
}
?>