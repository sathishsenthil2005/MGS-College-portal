<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

/* ---------- CLEAR PAYMENT HISTORY ---------- */
if(isset($_GET['clear_history'])){
    mysqli_query($conn,"DELETE FROM payments WHERE admin_confirmed=1");
    header("Location: reports.php?cleared=1");
    exit;
}

/* ---------- DATE FILTER ---------- */
$filter_date = $_GET['filter_date'] ?? "";
$safe_date = ($filter_date!="") ? mysqli_real_escape_string($conn,$filter_date) : "";

/* ---------- PDF DOWNLOAD ---------- */
if(isset($_GET['export']) && $_GET['export']=="pdf"){

require('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Student Fees Payment Report',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(60,10,'Student Name',1);
$pdf->Cell(30,10,'Amount',1);
$pdf->Cell(50,10,'Transaction ID',1);
$pdf->Cell(50,10,'Date',1);
$pdf->Ln();

$where = "WHERE f.admin_confirmed=1";

if($safe_date!=""){
$where .= " AND DATE(f.pay_date)='$safe_date'";
}

$q = mysqli_query($conn,"
SELECT s.name,f.amount,f.transaction_id,f.pay_date
FROM payments f
LEFT JOIN students s ON s.id=f.student_id
$where
ORDER BY f.pay_date DESC
");

$pdf->SetFont('Arial','',10);

if(mysqli_num_rows($q)>0){

while($row=mysqli_fetch_assoc($q)){

$pdf->Cell(60,10,$row['name'],1);
$pdf->Cell(30,10,"₹".$row['amount'],1);
$pdf->Cell(50,10,$row['transaction_id'],1);
$pdf->Cell(50,10,$row['pay_date'],1);
$pdf->Ln();

}

}else{

$pdf->Cell(190,10,"No Data",1,1,'C');

}

$pdf->Output("D","fees_report.pdf");
exit;

}

/* ---------- SUMMARY COUNTS ---------- */
$att_q = mysqli_query($conn,"SELECT id FROM attendance");
$att = $att_q ? mysqli_num_rows($att_q) : 0;

$res_q = mysqli_query($conn,"SELECT id FROM results");
$res = $res_q ? mysqli_num_rows($res_q) : 0;

$fees_q = mysqli_query($conn,"SELECT id FROM payments WHERE admin_confirmed=1");
$fees_count = $fees_q ? mysqli_num_rows($fees_q) : 0;

/* ---------- DAY WISE ATTENDANCE ---------- */
$att_where = ($safe_date!="") ? "WHERE DATE(date)='$safe_date'" : "";

$day_att = mysqli_query($conn,"
SELECT DATE(date) as att_date, COUNT(*) as total
FROM attendance
$att_where
GROUP BY DATE(date)
ORDER BY DATE(date) DESC
");

/* ---------- DAY WISE FEES ---------- */
$fees_where = "WHERE admin_confirmed=1";

if($safe_date!=""){
$fees_where .= " AND DATE(pay_date)='$safe_date'";
}

$day_fees = mysqli_query($conn,"
SELECT DATE(pay_date) as pay_day,
COALESCE(SUM(amount),0) as total_amount,
COUNT(*) as total_students
FROM payments
$fees_where
GROUP BY DATE(pay_date)
ORDER BY DATE(pay_date) DESC
");

/* ---------- PAYMENT HISTORY ---------- */
$history_where = "WHERE f.admin_confirmed=1";

if($safe_date!=""){
$history_where .= " AND DATE(f.pay_date)='$safe_date'";
}

$fees_history = mysqli_query($conn,"
SELECT 
f.student_id,
COALESCE(f.amount,0) as amount,
f.pay_date,
f.transaction_id,
s.name
FROM payments f
LEFT JOIN students s ON s.id=f.student_id
$history_where
ORDER BY f.pay_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>

<title>Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
margin:0;
font-family:Segoe UI;
background:#f4f6fb;
}

.container{
margin-left:220px;
padding:20px;
}

.cards{
display:flex;
flex-wrap:wrap;
gap:15px;
}

.card{
flex:1;
min-width:220px;
background:linear-gradient(135deg,#667eea,#764ba2);
color:#fff;
padding:20px;
border-radius:12px;
}

.filter-box{
background:#fff;
padding:15px;
border-radius:10px;
margin:20px 0;
box-shadow:0 5px 15px #ddd;
}

.table-box{
background:#fff;
margin-top:25px;
padding:20px;
border-radius:12px;
box-shadow:0 5px 15px #ddd;
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#667eea;
color:#fff;
padding:10px;
}

td{
padding:10px;
border-bottom:1px solid #eee;
text-align:center;
}

.btn{
padding:8px 14px;
background:#10b981;
color:#fff;
border:none;
border-radius:6px;
cursor:pointer;
text-decoration:none;
}

.clear-btn{
padding:8px 14px;
background:#ef4444;
color:#fff;
border-radius:6px;
text-decoration:none;
margin-left:10px;
}

.no-data{
text-align:center;
color:#dc2626;
font-weight:600;
padding:15px;
}

@media(max-width:768px){
.container{
margin-left:0;
}
}

</style>
</head>

<body>

<?php include("dashboard.php"); ?>

<div class="container">

<h2>📊 Reports Dashboard</h2>

<?php if(isset($_GET['cleared'])){ ?>
<p style="color:red;font-weight:bold;">🗑 Payment History Cleared Successfully</p>
<?php } ?>

<div class="filter-box">

<form method="get">

<b>Select Date:</b>

<input type="date" name="filter_date" value="<?php echo $filter_date; ?>">

<button type="submit">Filter</button>

<a href="reports.php"><button type="button">Reset</button></a>

<a href="reports.php?export=pdf&filter_date=<?php echo $filter_date; ?>" class="btn">
⬇ Download PDF
</a>

<a href="reports.php?clear_history=1"
onclick="return confirm('Are you sure to clear all payment history?')"
class="clear-btn">
🗑 Clear History
</a>

</form>

</div>

<!-- ATTENDANCE -->
<div class="table-box">

<h3>📆 Day Wise Attendance</h3>

<table>
<tr>
<th>Date</th>
<th>Total Present</th>
</tr>

<?php
if($day_att && mysqli_num_rows($day_att)>0){
while($d=mysqli_fetch_assoc($day_att)){
?>

<tr>
<td><?php echo $d['att_date']; ?></td>
<td><?php echo $d['total']; ?></td>
</tr>

<?php } } else { ?>

<tr>
<td colspan="2" class="no-data">No attendance records</td>
</tr>

<?php } ?>

</table>

</div>

<!-- FEES -->
<div class="table-box">

<h3>💰 Day Wise Fees Collection</h3>

<table>

<tr>
<th>Date</th>
<th>Students Paid</th>
<th>Total Amount</th>
</tr>

<?php
if($day_fees && mysqli_num_rows($day_fees)>0){
while($f=mysqli_fetch_assoc($day_fees)){
?>

<tr>

<td><?php echo $f['pay_day']; ?></td>
<td><?php echo $f['total_students']; ?></td>
<td>₹<?php echo number_format((float)$f['total_amount'],2); ?></td>

</tr>

<?php } } else { ?>

<tr>
<td colspan="3" class="no-data">No fees data</td>
</tr>

<?php } ?>

</table>

</div>

<!-- HISTORY -->
<div class="table-box">

<h3>🧾 Student Fees Payment History</h3>

<table>

<tr>
<th>Student</th>
<th>Amount</th>
<th>Transaction ID</th>
<th>Date</th>
<th>Status</th>
</tr>

<?php
if($fees_history && mysqli_num_rows($fees_history)>0){
while($h=mysqli_fetch_assoc($fees_history)){
?>

<tr>

<td><?php echo $h['name'] ?? 'Unknown'; ?></td>
<td>₹<?php echo number_format((float)$h['amount'],2); ?></td>
<td><?php echo $h['transaction_id']; ?></td>
<td><?php echo $h['pay_date']; ?></td>
<td style="color:green;font-weight:bold;">Paid</td>

</tr>

<?php } } else { ?>

<tr>
<td colspan="5" class="no-data">No payment history</td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>