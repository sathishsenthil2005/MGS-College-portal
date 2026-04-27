<?php
include("../config/db.php");

$id=(int)$_GET['id'];

$p=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM payments WHERE id=$id"
));

if($p){

mysqli_query($conn,"
UPDATE payments SET admin_confirmed=1 WHERE id=$id
");

mysqli_query($conn,"
UPDATE fees
SET paid_fees=total_fees,
    due_fees=0
WHERE student_id=".$p['student_id']."
");
}

echo "done";
?>