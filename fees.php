<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

/* ===== FETCH FEES ===== */
$r = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM fees WHERE student_id=$sid"
));

/* ✅ IMPORTANT — AMOUNT */
$amount = $r['due_fees'] ?? 0;

/* ===== FETCH LATEST PAYMENT ===== */
$pay = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT *
    FROM payments
    WHERE student_id=$sid
    ORDER BY id DESC
    LIMIT 1
"));

$paidStatus=false;
$processingStatus=false;

if($pay && $r){
    if($pay['admin_confirmed']==1 && $r['due_fees']<=0){
        $paidStatus=true;
    } elseif($pay['admin_confirmed']==0){
        $processingStatus=true;
    }
}

/* ===== UPI LINK ===== */
$upi_link = "upi://pay?pa=sathishsenthil2005@okicici&pn=CollegeFees&am=".$amount."&cu=INR";
?>
<!DOCTYPE html>
<html>
<head>
<title>Fees Payment</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
body{
margin:0;
font-family:Segoe UI,Arial;
background:linear-gradient(135deg,#4f46e5,#06b6d4);
}
.container{
margin-left:220px;
padding:30px;
}
.card{
background:#fff;
padding:28px;
width:440px;
border-radius:20px;
box-shadow:0 18px 45px rgba(0,0,0,.25);
}
.title{
font-size:24px;
font-weight:700;
margin-bottom:15px;
}
.fee-row{
display:flex;
justify-content:space-between;
padding:8px 0;
font-size:15px;
}
.due{
color:#dc2626;
font-weight:700;
}
.paybtn{
background:linear-gradient(90deg,#22c55e,#16a34a);
color:#fff;
border:none;
padding:13px;
width:100%;
border-radius:10px;
font-size:15px;
font-weight:600;
margin-top:14px;
cursor:pointer;
}
.paybtn:disabled{
background:#9ca3af;
cursor:not-allowed;
}
.status-paid{
background:#dcfce7;
color:#166534;
padding:12px;
border-radius:10px;
font-weight:700;
text-align:center;
}
.status-processing{
background:#fff7ed;
color:#c2410c;
padding:12px;
border-radius:10px;
font-weight:700;
text-align:center;
}
.qrbox{
display:none;
text-align:center;
margin-top:20px;
}
.amount-lock{
background:#f1f5f9;
padding:10px;
border-radius:8px;
font-weight:700;
margin-top:10px;
}
input[type="text"]{
width:100%;
padding:11px;
border-radius:8px;
border:1px solid #d1d5db;
margin-top:12px;
font-size:14px;
}
.terms-box{
text-align:left;
background:#f8fafc;
border:1px solid #e5e7eb;
border-radius:10px;
padding:12px;
margin-top:12px;
font-size:13px;
line-height:1.6;
max-height:140px;
overflow:auto;
}
.checkbox-row{
display:flex;
gap:8px;
align-items:flex-start;
margin-top:10px;
font-size:13px;
}
@media(max-width:768px){
.container{margin-left:0;padding:15px;}
.card{width:100%;}
}
</style>
</head>

<body>

<?php include("dashboard.php"); ?>

<div class="container">

<?php if($r){ ?>
<div class="card">

<div class="title">💳 Fees Payment</div>

<div class="fee-row">
<span>Total Fees</span>
<span>₹<?php echo $r['total_fees']; ?></span>
</div>

<div class="fee-row">
<span>Paid Fees</span>
<span>₹<?php echo $r['paid_fees']; ?></span>
</div>

<div class="fee-row due">
<span>Due Fees</span>
<span>₹<?php echo $r['due_fees']; ?></span>
</div>

<?php if($paidStatus){ ?>

<div class="status-paid">
✅ Payment Successfully Completed
</div>

<button class="paybtn" onclick="downloadReceipt()">Download Receipt</button>

<?php } elseif($processingStatus){ ?>

<div class="status-processing">
⏳ Payment Submitted — Waiting for Admin Confirmation
</div>

<?php } elseif($amount>0){ ?>

<button class="paybtn" onclick="showQR()">Pay ₹<?php echo $amount; ?></button>

<?php } ?>

<!-- QR SECTION -->
<div class="qrbox" id="qrbox">

<h3>Scan & Pay</h3>

<div class="amount-lock">

</div>

<input type="hidden" id="payAmount" value="<?php echo $amount; ?>">

<img id="qrimg"
src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?php echo urlencode($upi_link); ?>"
style="cursor:pointer;margin-top:12px;width:220px;">

<p style="font-size:13px;color:#666;">Tap QR to open GPay / PhonePe</p>

<input type="text" id="txn" placeholder="Enter Transaction ID">

<div class="terms-box">
<b>Terms & Conditions</b><br>
• Fees once paid cannot be refunded.<br>
• Ensure correct UPI payment,Enter correct Transaction number.<br>
• Fake transaction will block account.<br>
• Admin verification required for success.<br>
</div>

<div class="checkbox-row">
<input type="checkbox" id="agreeTerms">
<label for="agreeTerms">I agree to the Terms & Conditions</label>
</div>

<button id="submitBtn" class="paybtn" onclick="submitPayment()" disabled>
Submit Payment
</button>

</div>

</div>
<?php } else { echo "<b>Fees not assigned</b>"; } ?>

</div>

<script>

function showQR(){
document.getElementById("qrbox").style.display="block";
}

document.getElementById("qrimg")?.addEventListener("click",function(){
window.location.href="<?php echo $upi_link; ?>";
});

document.getElementById("agreeTerms")?.addEventListener("change",function(){
document.getElementById("submitBtn").disabled=!this.checked;
});

function submitPayment(){

let txn=document.getElementById("txn").value.trim();
let amount=document.getElementById("payAmount").value;

if(txn===""){
Swal.fire("Enter Transaction ID");
return;
}

Swal.fire({
title:"Submitting...",
text:"Waiting for admin verification",
icon:"info",
allowOutsideClick:false,
didOpen:()=>{

Swal.showLoading();

setTimeout(()=>{

$.post("save_payment.php", {
    txn: txn,
    amount: amount
}, function(res){

Swal.fire(
"Submitted!",
"Waiting for admin confirmation",
"success"
).then(()=>location.reload());

});

},1200);

}
});
}

function downloadReceipt(){
window.open("receipt.php","_blank");
}

</script>

</body>
</html>