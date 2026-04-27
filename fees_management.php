<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Fees Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    margin:0;
    font-family:Segoe UI,Arial;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}
.container{
    margin-left:220px;
    padding:25px;
}
.card{
    background:#fff;
    border-radius:16px;
    padding:25px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
.page-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:20px;
    background:linear-gradient(90deg,#4f46e5,#06b6d4);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}
.table-box{overflow:auto;}
table{width:100%;border-collapse:collapse;}
th{
    background:#4f46e5;
    color:#fff;
    padding:14px;
}
td{
    padding:13px;
    border-bottom:1px solid #eef2f7;
}
.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}
.processing{background:#fff7ed;color:#ea580c;}
.success{background:#dcfce7;color:#16a34a;}
.confirm-btn{
    background:linear-gradient(90deg,#22c55e,#16a34a);
    border:none;
    color:#fff;
    padding:7px 14px;
    border-radius:8px;
    cursor:pointer;
    font-size:13px;
    font-weight:600;
}
@media(max-width:768px){
.container{margin-left:0}
}
</style>
</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container">
<div class="card">

<div class="page-title">💳 Fees Payment Monitoring</div>

<div class="table-box">
<table>
<tr>
<th>Student</th>
<th>Department</th>
<th>Total</th>
<th>Paid</th>
<th>Due</th>
<th>Transaction</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT 
    s.name,
    s.department,
    f.*,
    p.transaction_id,
    p.admin_confirmed,
    p.id AS pay_id
FROM fees f
JOIN students s ON s.id=f.student_id
LEFT JOIN payments p ON p.id = (
    SELECT id FROM payments
    WHERE student_id=f.student_id
    ORDER BY id DESC
    LIMIT 1
)
ORDER BY f.id DESC
");

while($r=mysqli_fetch_assoc($q)){

$statusBadge="—";
$btn="—";

if(!empty($r['transaction_id'])){

    if($r['admin_confirmed']==0){

        $statusBadge="<span class='badge processing'>Processing</span>";

        $btn="<button class='confirm-btn'
              onclick='confirmPay({$r['pay_id']})'>
              ✔ Confirm
              </button>";

    }else{

        $statusBadge="<span class='badge success'>Success</span>";
    }
}

echo "<tr>
<td><b>{$r['name']}</b></td>
<td>{$r['department']}</td>
<td>₹{$r['total_fees']}</td>
<td>₹{$r['paid_fees']}</td>
<td style='color:#dc2626;font-weight:700'>₹{$r['due_fees']}</td>
<td>".($r['transaction_id']??'—')."</td>
<td>$statusBadge</td>
<td>$btn</td>
</tr>";
}
?>
</table>
</div>
</div>
</div>

<script>
function confirmPay(id){

Swal.fire({
title:'Confirm this payment?',
text:'This will mark payment as SUCCESS',
icon:'question',
showCancelButton:true,
confirmButtonColor:'#16a34a'
}).then((r)=>{

if(r.isConfirmed){

fetch('confirm_payment.php?id='+id)
.then(()=>{

Swal.fire(
'Confirmed!',
'Payment marked as successful',
'success'
).then(()=>location.reload());

});

}

});
}
</script>

</body>
</html>