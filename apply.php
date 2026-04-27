<?php
session_start();
include("../config/db.php");

$msg="";
$step = 1;

if(isset($_POST['step1'])){
$_SESSION['admission']=$_POST;
$step=2;
}

if(isset($_POST['submit'])){

if(!isset($_SESSION['admission'])){
die("Session expired");
}

$d=$_SESSION['admission'];

$app_no="APP".rand(10000,99999);

// 🔥 FIXED UPLOAD FUNCTION
function uploadFile($file,$folder){

if(!is_dir($folder)){
mkdir($folder,0777,true);
}

$filename = $_FILES[$file]['name'];
$tmp = $_FILES[$file]['tmp_name'];

$name = time()."_".$filename;

move_uploaded_file($tmp,$folder.$name);

return $name;
}

// 🔥 IMPORTANT FIX (ALL SAME PATH)
$uploadDir = "../uploads/";

$photo = uploadFile("photo",$uploadDir);
$tenth_file = uploadFile("tenth_file",$uploadDir);
$twelfth_file = uploadFile("twelfth_file",$uploadDir);
$community_file = uploadFile("community_file",$uploadDir);


// INSERT
mysqli_query($conn,"
INSERT INTO admissions
(application_no,name,dob,gender,phone,email,address,parent_name,department,course_type,community,tenth_mark,twelfth_mark,photo,tenth_file,twelfth_file,community_file)
VALUES
('$app_no','".$d['name']."','".$d['dob']."','".$d['gender']."','".$d['phone']."','".$d['email']."','".$d['address']."','".$d['parent']."','".$_POST['department']."','".$_POST['course_type']."','".$_POST['community']."','".$_POST['tenth']."','".$_POST['twelfth']."','$photo','$tenth_file','$twelfth_file','$community_file')
");

unset($_SESSION['admission']);

$_SESSION['last_app'] = $app_no;
header("Location: download.php");
exit;
$step=1;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admission</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
font-family:Segoe UI;
background:linear-gradient(135deg,#2563eb,#7c3aed);
margin:0;
}

/* HEADER */
.header{
text-align:center;
padding:15px;
color:#fff;
}

.header img{
width:70px;
margin-bottom:8px;
}

.header h1{
font-size:20px;
margin:0;
}

/* CONTAINER */
.container{
width:92%;
max-width:650px;
margin:15px auto;
background:#fff;
padding:20px;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,0.2);
}

.title{
text-align:center;
color:#2563eb;
font-size:18px;
margin-bottom:10px;
}

/* STEPS */
.steps{
display:flex;
gap:5px;
margin-bottom:15px;
}

.step{
flex:1;
padding:8px;
text-align:center;
background:#ddd;
border-radius:15px;
font-size:13px;
font-weight:bold;
}

.active{
background:#2563eb;
color:#fff;
}

/* FORM */
input,select,textarea{
width:100%;
padding:10px;
margin:6px 0;
border:1px solid #ccc;
border-radius:6px;
font-size:14px;
}

textarea{
min-height:70px;
}

button{
width:100%;
padding:12px;
background:#2563eb;
color:#fff;
border:none;
border-radius:8px;
font-size:15px;
margin-top:10px;
}

button:active{
background:#1e40af;
}

label{
font-size:14px;
font-weight:600;
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
/* MESSAGE */
.msg{
background:#d1fae5;
padding:10px;
text-align:center;
color:green;
border-radius:6px;
margin-top:10px;
font-size:14px;
}

/* MOBILE EXTRA */
@media(max-width:480px){

.header h1{
font-size:18px;
}

.container{
padding:15px;
}

.step{
font-size:12px;
padding:6px;
}

}

</style>

</head>

<body>

<!-- HEADER -->
<div class="header">
    
<img src="logo.jpg" alt="College Logo">
<h1>MGS Arts and Science College Rajapalayam.</h1>
</div>

<div class="container">

<h2 class="title">🎓 Admission Form</h2>
<a href="track.php" style="display:inline-block;margin-top:10px;padding:8px 12px;background:#fff;color:#2563eb;border-radius:6px;text-decoration:none;font-size:14px;">
🔍 Track Application
</a>
<div class="steps">
<div class="step <?php if($step==1) echo 'active'; ?>">Step 1</div>
<div class="step <?php if($step==2) echo 'active'; ?>">Step 2</div>
</div>

<?php if($step==2){ ?>

<h3 class="title">📘 Academic Details</h3>

<form method="post" enctype="multipart/form-data">

<label>Course Type</label>
<select name="course_type">
<option>UG</option>
</select>

<label>Department</label>
<select name="department">
    <option>BA.Tamil</option>
    <option>BA.English</option>
<option>Bsc</option>
<option>BCA</option>
<option>BBA</option>
<option>B.Com</option>
<option>B.Sc Computer Science</option>
<option>B.Sc Mathematics</option>
<option>B.Sc Physics</option>
</select>

<label>Community</label>
<select name="community">
<option>BC</option>
<option>UR(All)</option>
<option>DNC</option>
<option>MBC</option>
<option>OC</option>
<option>SC/ST</option>
</select>

<input type="text" name="tenth" placeholder="10th %" required>
<input type="text" name="twelfth" placeholder="12th %" required>

<label>Upload Your Photo</label>
<input type="file" name="photo" required>

<label>10th Marksheet</label>
<input type="file" name="tenth_file" required>

<label>12th Marksheet</label>
<input type="file" name="twelfth_file" required>

<label>Community Certificate</label>
<input type="file" name="community_file" required>
<div class="terms-box">
<b>Terms & Conditions</b><br>
• After Submission to Application number(must note the number) to track your application(status) process .<br>
• Applicant must ensure all information entered is correct and complete.<br>
• Admission will be confirmed only after document verification.<br>
• Any false information may lead to rejection of application.<br>
• College rules and regulations must be followed after admission.<br>
</div>
<button name="submit">Submit Application</button>

</form>

<?php } else { ?>

<h3 class="title">👤 Personal Details</h3>

<form method="post">

<input type="text" name="name" placeholder="Full Name" required>
<input type="date" name="dob" required>

<select name="gender">
<option>Male</option>
<option>Female</option>
</select>

<input type="text" name="phone" placeholder="Phone Number" required>
<input type="email" name="email" placeholder="Email Address" required>

<textarea name="address" placeholder="Address"></textarea>

<input type="text" name="parent" placeholder="Father Name" required>

<button name="step1">Next →</button>

</form>

<?php } ?>

<?php if($msg!=""){ echo "<div class='msg'>$msg</div>"; } ?>

</div>

</body>
</html>