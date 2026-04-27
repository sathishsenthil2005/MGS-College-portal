<?php
session_start();
include("../config/db.php");
date_default_timezone_set("Asia/Kolkata");

if(!isset($_SESSION['last_app'])){
    die("No Application Found");
}

$app_no = mysqli_real_escape_string($conn, $_SESSION['last_app']);

$res = mysqli_query($conn,"SELECT * FROM admissions WHERE application_no='$app_no'");
if(mysqli_num_rows($res) == 0){
    die("Application not found");
}
$data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<title>Download Application</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
    font-family:'Segoe UI',sans-serif;
    background:#f3f4f6;
    padding:10px;
}

/* A4 Layout */
#downloadArea{
    width:200mm;
    margin:auto;
    background:#fff;
    padding:10mm;
}

/* Container */
.container{
    width: 100%;
    border: 3px solid #2563eb;
    border-radius: 10px;
    padding: 18px 15px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}
/* Header */
.header{text-align:center;}
.header img{width:65px;height:65px;margin-bottom:10px;}
.header h2{color:#2563eb;font-size:20px;}
.header p{color:#6b7280;font-size:12px;}

/* Title */
.main-title{
    text-align:center;
    color:#2563eb;
    font-size:22px;
    font-weight:bold;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    font-size:12px;
}

td{
    border:1px solid #ddd;
    padding:8px;
}

td:first-child{
    font-weight:bold;
    width:40%;
    background:#f9fafb;
}

/* Photo */
.photo-cell img{
    width:80px;
    height:100px;
    object-fit:cover;
}

/* Footer */
.footer{
    border-top:2px solid #e5e7eb;
    text-align:center;
    font-size:11px;
    padding-top:10px;
}

/* Buttons */
.download-btn{
    text-align:center;
    margin:25px auto;
}

button{
    padding:12px 25px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
    margin:5px;
}

/* Finish Button */
.finish-btn{
    background:#16a34a;
}

.finish-btn:hover{
    background:#15803d;
}
</style>
</head>

<body>

<div id="downloadArea">
<div class="container">

<div class="header">
    <img src="logo.jpg" onerror="this.style.display='none'">
    <h2>MGS Arts & Science College Rajapalayam</h2>
    <p>Accredited by NAAC | Approved by UGC</p>
</div>

<h3 class="main-title">🎓 Application Form</h3>

<table>
<tr><td>Application Number</td><td><?= htmlspecialchars($data['application_no']) ?></td></tr>
<tr><td>Full Name</td><td><?= htmlspecialchars($data['name']) ?></td></tr>
<tr><td>Date of Birth</td><td><?= htmlspecialchars($data['dob']) ?></td></tr>
<tr><td>Gender</td><td><?= htmlspecialchars($data['gender']) ?></td></tr>
<tr><td>Mobile Number</td><td><?= htmlspecialchars($data['phone']) ?></td></tr>
<tr><td>Email Address</td><td><?= htmlspecialchars($data['email']) ?></td></tr>
<tr><td>Department</td><td><?= htmlspecialchars($data['department']) ?></td></tr>
<tr><td>Course Applied</td><td><?= htmlspecialchars($data['course_type']) ?></td></tr>
<tr><td>Community</td><td><?= htmlspecialchars($data['community']) ?></td></tr>
<tr><td>10th (%)</td><td><?= htmlspecialchars($data['tenth_mark']) ?>%</td></tr>
<tr><td>12th (%)</td><td><?= htmlspecialchars($data['twelfth_mark']) ?>%</td></tr>

<tr>
<td>Photo</td>
<td class="photo-cell">
<?php if(!empty($data['photo']) && file_exists("../uploads/".$data['photo'])): ?>
<img src="../uploads/<?= htmlspecialchars($data['photo']) ?>">
<?php else: ?>
No Photo
<?php endif; ?>
</td>
</tr>
</table>

<div class="footer">
<p>Date: <?= date('d-M-Y h:i A'); ?></p>
<p>MGS Arts & Science College Rajapalayam</p>
<p style="font-size: 11px; margin-top: 8px;">Application generated through Online Portal</p>
</div>

</div>
</div>

<div class="download-btn">
    <button onclick="downloadPDF()">⬇️ Download PDF</button>
    <button class="finish-btn" onclick="goHome()">🏠 Finish</button>
</div>

<script>
function downloadPDF() {
    const element = document.getElementById('downloadArea');

    const opt = {
        margin: [0.4, 0.8, 0.4, 0.8], // Inches: Top, Right, Bottom, Left
        filename: '<?php echo htmlspecialchars($data["application_no"]); ?>_Application.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
            scale: 2,
            useCORS: true,
            letterRendering: true,
            backgroundColor: '#ffffff',
            width: 930,   // Approx A4 width at 96 DPI * 2 scale
            height: 1500  // Approx A4 height at 96 DPI * 2 scale
        },
        jsPDF: {
            unit: 'in',
            format: 'a4',
            orientation: 'portrait'
        },
        pagebreak: { mode: ['avoid-all', 'avoid-all'] }
    };

    // Generate PDF
    html2pdf().set(opt).from(element).save();
}

function goHome(){
    window.location.href = "index.php"; // 🔥 redirect home
}
</script>

</body>
</html>