<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
header("Location: login.php");
exit;
}

/* ================= DELETE ================= */

if(isset($_GET['delete'])){

$id=(int)$_GET['delete'];

$q=mysqli_query($conn,"SELECT file_path FROM halltickets WHERE id='$id'");
$r=mysqli_fetch_assoc($q);

if($r){

$file="../uploads/halltickets/".$r['file_path'];

if(file_exists($file)){
unlink($file);
}

mysqli_query($conn,"DELETE FROM halltickets WHERE id='$id'");
$msg="🗑 Hallticket deleted";

}

}

/* ================= CLEAR ALL ================= */

if(isset($_POST['clear_all'])){

$q=mysqli_query($conn,"SELECT file_path FROM halltickets");

while($r=mysqli_fetch_assoc($q)){

$file="../uploads/halltickets/".$r['file_path'];

if(file_exists($file)){
unlink($file);
}

}

mysqli_query($conn,"TRUNCATE TABLE halltickets");

$msg="🧹 All halltickets cleared";

}

/* ================= DEPARTMENTS ================= */

$deptQ=mysqli_query($conn,"SELECT DISTINCT department FROM students");

/* ================= SEND ================= */

if(isset($_POST['send'])){

$department=mysqli_real_escape_string($conn,$_POST['department']);
$roll_no=mysqli_real_escape_string($conn,$_POST['roll_no']);

$stuQ=mysqli_query($conn,"
SELECT id,name FROM students
WHERE department='$department'
AND roll_no='$roll_no'
LIMIT 1
");

if(mysqli_num_rows($stuQ)==0){

$msg="❌ Student not found";

}else{

$stu=mysqli_fetch_assoc($stuQ);
$student_id=$stu['id'];

$chk=mysqli_query($conn,"
SELECT id FROM halltickets
WHERE student_id='$student_id'
");

if(mysqli_num_rows($chk)>0){

$msg="⚠️ Already sent";

}else{

$uploadDir="../uploads/halltickets/";

if(!is_dir($uploadDir)){
mkdir($uploadDir,0777,true);
}

if(isset($_FILES['file']) && $_FILES['file']['error']==0){

$fileName=$_FILES['file']['name'];
$tmp=$_FILES['file']['tmp_name'];

$newName=time()."_".preg_replace("/[^a-zA-Z0-9.]/","_",$fileName);

$path=$uploadDir.$newName;

if(move_uploaded_file($tmp,$path)){

mysqli_query($conn,"
INSERT INTO halltickets
(student_id,roll_no,department,file_path)
VALUES
('$student_id','$roll_no','$department','$newName')
");

$msg="✅ Hallticket sent";

}else{

$msg="❌ Upload failed";

}

}else{

$msg="❌ Choose file";

}

}

}

}

/* ================= FILTER ================= */

$where="";

if(isset($_GET['dept']) && $_GET['dept']!=""){

$dept=mysqli_real_escape_string($conn,$_GET['dept']);
$where="WHERE h.department='$dept'";

}

/* ================= LIST ================= */

$list=mysqli_query($conn,"
SELECT h.*,s.name
FROM halltickets h
JOIN students s ON s.id=h.student_id
$where
ORDER BY h.id DESC
");

?>

<!DOCTYPE html>
<html>
<head>

<title>Hall Ticket Manager</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
background:#f4f6f9;
font-family:Arial;
}

.container{
margin-left:230px;
padding:25px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,.08);
max-width:420px;
}

input,select,button{
width:100%;
padding:10px;
margin:8px 0;
}

button{
border:none;
cursor:pointer;
border-radius:6px;
}

.sendBtn{
background:#28a745;
color:white;
}

.generateBtn{
background:#007bff;
color:white;
padding:10px 20px;
margin-bottom:10px;
}

.clearAllBtn{
background:#ff5722;
color:white;
padding:10px 20px;
margin-bottom:20px;
}

.msg{
font-weight:bold;
margin-bottom:10px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:30px;
background:white;
}

th,td{
padding:12px;
border:1px solid #ddd;
text-align:center;
}

th{
background:#007bff;
color:white;
}

.action a{
padding:6px 12px;
text-decoration:none;
border-radius:6px;
font-size:13px;
}

.view{
background:#17a2b8;
color:white;
}

.del{
background:#dc3545;
color:white;
}

.filterBox{
width:250px;
padding:10px;
margin-top:20px;
}

@media(max-width:768px){

.container{
margin-left:0;
padding:15px;
}

.card{
max-width:100%;
}

.filterBox{
width:100%;
}

}

</style>

</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container">

<h2>🎫 Send Hall Ticket</h2>

<a href="generate_all_halltickets.php">
<button class="generateBtn">
⚡ Generate All Halltickets
</button>
</a>

<form method="post">
<button name="clear_all" class="clearAllBtn"
onclick="return confirm('Delete ALL halltickets?')">
🧹 Clear All Halltickets
</button>
</form>

<?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

<div class="card">

<form method="post" enctype="multipart/form-data">

<select name="department" id="department" required>

<option value="">Select Department</option>

<?php
$deptQ2=mysqli_query($conn,"SELECT DISTINCT department FROM students");
while($d=mysqli_fetch_assoc($deptQ2)){
?>

<option value="<?php echo $d['department']; ?>">
<?php echo $d['department']; ?>
</option>

<?php } ?>

</select>

<select name="roll_no" id="roll_no" required>

<option value="">Select Roll Number</option>

</select>

<input type="file" name="file" accept=".jpg,.png,.jpeg,.pdf" required>

<button class="sendBtn" name="send">
Send Hall Ticket
</button>

</form>

</div>

<!-- FILTER -->

<form method="get">

<select name="dept" class="filterBox" onchange="this.form.submit()">

<option value="">🎓 Filter by Department</option>

<?php

$deptQ3=mysqli_query($conn,"SELECT DISTINCT department FROM students");

while($d=mysqli_fetch_assoc($deptQ3)){

$sel=(isset($_GET['dept']) && $_GET['dept']==$d['department']) ? "selected" : "";

echo "<option $sel value='".$d['department']."'>".$d['department']."</option>";

}

?>

</select>

</form>

<!-- TABLE -->

<table>

<tr>
<th>Student</th>
<th>Roll No</th>
<th>Department</th>
<th>Action</th>
</tr>

<?php if(mysqli_num_rows($list)>0){ ?>

<?php while($r=mysqli_fetch_assoc($list)){ ?>

<tr>

<td><?php echo $r['name']; ?></td>

<td><?php echo $r['roll_no']; ?></td>

<td><?php echo $r['department']; ?></td>

<td class="action">

<a class="view"
href="../uploads/halltickets/<?php echo $r['file_path']; ?>"
target="_blank">
View
</a>

<a class="del"
onclick="return confirm('Delete hallticket?')"
href="?delete=<?php echo $r['id']; ?>">
Delete
</a>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="4">No halltickets found</td>
</tr>

<?php } ?>

</table>

</div>

<script>

/* LOAD ROLL NUMBER */

document.getElementById("department").addEventListener("change",function(){

let dept=this.value;

let roll=document.getElementById("roll_no");

roll.innerHTML="<option>Loading...</option>";

fetch("get_students.php?department="+dept)

.then(res=>res.text())

.then(data=>{

roll.innerHTML=data;

});

});

</script>

</body>
</html>