<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    exit("login required");
}

$sid = (int)$_SESSION['student_id'];
$txn = mysqli_real_escape_string($conn, $_POST['txn'] ?? '');
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;

if($txn=='' || $amount<=0){
    exit("invalid");
}

/* prevent duplicate pending */
$check=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT id FROM payments
WHERE student_id=$sid AND admin_confirmed=0
"));

if(!$check){
    mysqli_query($conn,"
    INSERT INTO payments
    (student_id, transaction_id, amount, admin_confirmed, pay_date)
    VALUES
    ('$sid','$txn','$amount',0,NOW())
    ");
}

echo "ok";
?>