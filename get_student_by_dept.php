<?php
include("../config/db.php");

if(isset($_GET['department'])){

    $dept = mysqli_real_escape_string($conn,$_GET['department']);

    $q = mysqli_query($conn,"
        SELECT id,name,roll_no
        FROM students
        WHERE department='$dept'
        ORDER BY name
    ");

    if(mysqli_num_rows($q)>0){

        echo "<option value=''>Select Student</option>";

        while($r=mysqli_fetch_assoc($q)){
            echo "<option value='".$r['id']."'>"
                .$r['name']." (".$r['roll_no'].")".
                "</option>";
        }

    }else{
        echo "<option value=''>No students found</option>";
    }
}
?>