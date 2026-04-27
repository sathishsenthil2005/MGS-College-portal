<?php
include("../config/db.php");

if(isset($_GET['department'])){

    $dept = mysqli_real_escape_string($conn,$_GET['department']);

    $q = mysqli_query($conn,"
        SELECT roll_no,name
        FROM students
        WHERE department='$dept'
        ORDER BY roll_no
    ");

    if(mysqli_num_rows($q)>0){

        echo "<option value=''>Select Roll Number</option>";

        while($r=mysqli_fetch_assoc($q)){
            echo "<option value='".$r['roll_no']."'>"
                .$r['roll_no']." - ".$r['name'].
                "</option>";
        }

    }else{
        echo "<option value=''>No students found</option>";
    }
}
?>