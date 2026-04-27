<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

/* latest payment */
$pq = mysqli_query($conn,"
SELECT payments.*, students.name, students.roll_no, students.department
FROM payments
JOIN students ON students.id = payments.student_id
WHERE payments.student_id = $sid
ORDER BY payments.id DESC
LIMIT 1
");

$p = mysqli_fetch_assoc($pq);

if(!$p){
    echo "No payment found";
    exit;
}

/* receipt number */
$receipt_no = "RCPT" . str_pad($p['id'], 5, "0", STR_PAD_LEFT);

/* ================= PDF DOWNLOAD ================= */
if(isset($_GET['download'])){

    require("../fpdf/fpdf.php");

    $pdf = new FPDF();
    $pdf->AddPage();

    // Title
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(0,10,'MGS Arts and Science College Rajapalayam',0,1,'C');

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,'Official Payment Receipt',0,1,'C');

    $pdf->Ln(5);
    $pdf->Cell(0,0,'','T',1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',11);

    $dateShow = !empty($p['pay_date'])
        ? date("d-m-Y h:i A", strtotime($p['pay_date']))
        : date("d-m-Y h:i A");

    $pdf->Cell(60,8,'Receipt No',0);
    $pdf->Cell(0,8,$receipt_no,0,1);

    $pdf->Cell(60,8,'Student Name',0);
    $pdf->Cell(0,8,$p['name'],0,1);

    $pdf->Cell(60,8,'Roll Number',0);
    $pdf->Cell(0,8,$p['roll_no'],0,1);

    $pdf->Cell(60,8,'Department',0);
    $pdf->Cell(0,8,$p['department'],0,1);

    $pdf->Cell(60,8,'Transaction ID',0);
    $pdf->Cell(0,8,$p['transaction_id'],0,1);

    $pdf->Cell(60,8,'Payment Date',0);
    $pdf->Cell(0,8,$dateShow,0,1);

    $pdf->Ln(10);

    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,12,'Amount Paid: Rs '.number_format((float)$p['amount'],2),1,1,'C');

    $pdf->Ln(8);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,'Payment Successfully Completed',0,1,'C');

    // 🔥 AUTO DOWNLOAD
    $pdf->Output('D','payment_receipt.pdf');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Payment Receipt</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f6f9;
}
.receipt-box{
    width:700px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.15);
}
.header{
    text-align:center;
    border-bottom:2px solid #007bff;
    padding-bottom:10px;
    margin-bottom:20px;
}
.college-name{
    font-size:26px;
    font-weight:bold;
    color:#007bff;
}
.subtitle{
    font-size:14px;
    color:#555;
}
.table{
    width:100%;
    margin-top:20px;
    border-collapse:collapse;
}
.table td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
.amount-box{
    background:#e8f5e9;
    padding:15px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
    color:#2e7d32;
    border-radius:8px;
    margin-top:20px;
}
.footer{
    text-align:center;
    margin-top:30px;
    font-size:13px;
    color:#666;
}
.btns{
    text-align:center;
    margin-top:25px;
}
.btn{
    padding:10px 22px;
    margin:5px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:#fff;
    font-weight:bold;
}
.print{ background:#007bff; }
.pdf{ background:#28a745; }
.finish{ background:#ff9800; }

@media(max-width:768px){
    .receipt-box{ width:95%; }
}
@media print{
    .btns{ display:none; }
    body{ background:white; }
}
</style>
</head>

<body>

<div class="receipt-box">

<div class="header">
<div class="college-name">MGS ARTS AND SCIENCE COLLEGE (AUTONOMOUS)</div>
<div class="subtitle">Payment Receipt</div>
</div>

<table class="table">
<tr><td><b>Receipt No</b></td><td><?php echo $receipt_no; ?></td></tr>
<tr><td><b>Student Name</b></td><td><?php echo htmlspecialchars($p['name']); ?></td></tr>
<tr><td><b>Roll Number</b></td><td><?php echo htmlspecialchars($p['roll_no']); ?></td></tr>
<tr><td><b>Department</b></td><td><?php echo htmlspecialchars($p['department']); ?></td></tr>
<tr><td><b>Transaction ID</b></td><td><?php echo htmlspecialchars($p['transaction_id']); ?></td></tr>
<tr>
<td><b>Payment Date</b></td>
<td><?php echo !empty($p['pay_date']) ? date("d-m-Y h:i A", strtotime($p['pay_date'])) : date("d-m-Y h:i A"); ?></td>
</tr>
</table>

<div class="amount-box">
₹ <?php echo number_format((float)$p['amount'],2); ?>
<div style="font-size:14px;color:#555;">Amount Paid</div>
</div>

<div class="footer">
✅ Payment Successfully Completed<br>
This is computer generated receipt — no signature required.
</div>

<div class="btns">
<button class="btn print" onclick="window.print()">🖨 Print</button>

<a href="?download=1">
<button class="btn pdf">⬇ Download PDF</button>
</a>

<a href="dashboard.php">
<button class="btn finish">✅ Finish</button>
</a>
</div>

</div>
</body>
</html>