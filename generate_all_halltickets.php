<?php

session_start();
include("../config/db.php");

require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if(!isset($_SESSION['admin_id'])){
header("Location: login.php");
exit;
}

$message="";

if(isset($_POST['generate'])){

$uploadDir="../uploads/halltickets/";

if(!is_dir($uploadDir)){
mkdir($uploadDir,0777,true);
}

$students=mysqli_query($conn,"SELECT * FROM students");

$count=0;

while($stu=mysqli_fetch_assoc($students)){

$student_id=$stu['id'];
$name=$stu['name'];
$roll=$stu['roll_no'];
$dept=$stu['department'];

/* ===== student photo ===== */

$photoFile="../uploads/students/".$stu['photo'];

if(file_exists($photoFile)){
$photoData=base64_encode(file_get_contents($photoFile));
$photo="data:image/jpeg;base64,".$photoData;
}else{
$photo="";
}

/* ===== already generated check ===== */

$chk=mysqli_query($conn,"SELECT id FROM halltickets WHERE student_id='$student_id'");

if(mysqli_num_rows($chk)>0){
continue;
}

/* ===== FETCH EXAM SCHEDULE ===== */

$subjects=mysqli_query($conn,"
SELECT subject,exam_date,exam_time
FROM exam_schedule
WHERE department='$dept'
ORDER BY exam_date
");

$subjectRows="";

while($sub=mysqli_fetch_assoc($subjects)){

$subjectRows.='
<tr>
<td>'.$sub['subject'].'</td>
<td>'.$sub['exam_date'].'</td>
<td>'.$sub['exam_time'].'</td>
</tr>
';

}

/* ===== college logo ===== */

$logoFile="../assets/logo.jpg";

if(file_exists($logoFile)){
$logoData=base64_encode(file_get_contents($logoFile));
$logo="data:image/jpeg;base64,".$logoData;
}else{
$logo="";
}

/* ===== hallticket html ===== */

$html='

<style>

body{font-family:Arial;}

.header{
text-align:center;
border-bottom:2px solid #000;
padding-bottom:10px;
margin-bottom:15px;
}

.logo{width:80px;}

.title{
text-align:center;
font-size:22px;
font-weight:bold;
margin:10px 0;
}

table{
border-collapse:collapse;
width:100%;
}

td,th{
border:1px solid #000;
padding:8px;
text-align:center;
}

.info td{
border:none;
text-align:left;
font-size:14px;
}

.photo{
text-align:right;
}

.rules{
font-size:12px;
margin-top:20px;
}

</style>

<div class="header">

<img src="'.$logo.'" class="logo">

<h2>MGS ARTS AND SCIENCE COLLEGE</h2>

<p>Rajapalayam</p>

</div>

<div class="title">EXAMINATION HALL TICKET</div>

<table class="info">

<tr>

<td>

<b>Name :</b> '.$name.' <br><br>

<b>Roll Number :</b> '.$roll.' <br><br>

<b>Department :</b> '.$dept.'

</td>

<td class="photo">

<img src="'.$photo.'" width="120" height="140">

</td>

</tr>

</table>

<br>

<h3 style="text-align:center;">Exam Time Table</h3>

<table>

<tr>
<th>Subject</th>
<th>Date</th>
<th>Time</th>
</tr>

'.$subjectRows.'

</table>

<br><br>

<table class="info">

<tr>

<td>

Candidate Signature<br><br>
____________________

</td>

<td style="text-align:right">

Controller of Examination<br><br>
____________________

</td>

</tr>

</table>

<div class="rules">

<h4>Rules & Regulations</h4>

<ol>

<li>Students must bring this hall ticket to the examination hall.</li>

<li>Students should be present in the exam hall 30 minutes before the exam.</li>

<li>Mobile phones and electronic gadgets are strictly prohibited.</li>

<li>Students must carry valid college ID card.</li>

<li>Malpractice during examination will lead to disciplinary action.</li>

</ol>

</div>

';

/* ===== CREATE PDF ===== */

$options=new Options();
$options->set('isRemoteEnabled', true);

$dompdf=new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4');

$dompdf->render();

$fileName="hallticket_".$roll.".pdf";

$path=$uploadDir.$fileName;

file_put_contents($path,$dompdf->output());

/* ===== SAVE DB ===== */

mysqli_query($conn,"
INSERT INTO halltickets
(student_id,roll_no,department,file_path)
VALUES
('$student_id','$roll','$dept','$fileName')
");

$count++;

}

$message="$count Halltickets Generated Successfully";

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Generate Halltickets</title>

<style>

body{
font-family:Arial;
background:#f5f5f5;
text-align:center;
padding-top:100px;
}

button{
padding:12px 25px;
font-size:16px;
background:#2ecc71;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

.success{
margin-top:20px;
color:green;
font-size:18px;
}

</style>

</head>

<body>

<h2>Generate All Student Halltickets</h2>

<form method="post">

<button type="submit" name="generate">
Generate Halltickets
</button>

</form>

<?php
if($message!=""){
echo "<div class='success'>$message</div>";
echo "<br><a href='send_hallticket.php'><button>Back to Dashboard</button></a>";
}
?>

</body>
</html>