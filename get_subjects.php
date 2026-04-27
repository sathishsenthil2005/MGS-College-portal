<?php
include("../config/db.php");

$dept = mysqli_real_escape_string($conn,$_GET['department']);

$q = mysqli_query($conn,"
SELECT DISTINCT subject 
FROM timetable 
WHERE department='$dept'
");

echo '<option value="">Select Subject</option>';

while($r=mysqli_fetch_assoc($q)){
echo '<option value="'.$r['subject'].'">'.$r['subject'].'</option>';
}
?>