<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

$msg="";
$editData=null;

/* =========================
   DEPARTMENT FILTER
========================= */
$deptFilter=$_GET['dept'] ?? "";

$where="";
if($deptFilter!=""){
$where="WHERE department='".mysqli_real_escape_string($conn,$deptFilter)."'";
}
/* =========================
   PDF DOWNLOAD
========================= */

if(isset($_GET['export']) && $_GET['export']=="pdf"){

require("fpdf.php");

$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Students Department Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(50,10,'Name',1);
$pdf->Cell(40,10,'Email',1);
$pdf->Cell(30,10,'Roll No',1);
$pdf->Cell(40,10,'Department',1);
$pdf->Cell(30,10,'Password',1);

$pdf->Ln();

$q=mysqli_query($conn,"SELECT * FROM students $where");

$pdf->SetFont('Arial','',9);

while($r=mysqli_fetch_assoc($q)){

$pdf->Cell(50,10,$r['name'],1);
$pdf->Cell(40,10,$r['email'],1);
$pdf->Cell(30,10,$r['roll_no'],1);
$pdf->Cell(40,10,$r['department'],1);
$pdf->Cell(30,10,$r['roll_no'],1); // 🔥 password column la roll number

$pdf->Ln();

}

$pdf->Output("D","students_Details.pdf");
exit;

}
/* =========================
   FETCH EDIT DATA
========================= */
if(isset($_GET['edit'])){
$eid=intval($_GET['edit']);
$eq=mysqli_query($conn,"SELECT * FROM students WHERE id=$eid");
$editData=mysqli_fetch_assoc($eq);
}

/* =========================
   ADD STUDENT
========================= */
if(isset($_POST['add'])){

$name=mysqli_real_escape_string($conn,$_POST['name']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$roll=mysqli_real_escape_string($conn,$_POST['roll']);
$dept=mysqli_real_escape_string($conn,$_POST['dept']);
$pass=password_hash($_POST['password'], PASSWORD_DEFAULT);

$photoName="";

if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

if(!is_dir("../uploads/students/")){
mkdir("../uploads/students/",0777,true);
}

$ext=pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photoName=time().rand(100,999).".".$ext;

move_uploaded_file(
$_FILES['photo']['tmp_name'],
"../uploads/students/".$photoName
);

}

mysqli_query($conn,"INSERT INTO students
(name,email,roll_no,department,password,photo)
VALUES
('$name','$email','$roll','$dept','$pass','$photoName')");

$msg="✅ Student Added Successfully";

}

/* =========================
   UPDATE STUDENT
========================= */
if(isset($_POST['update'])){

$id=intval($_POST['id']);
$name=mysqli_real_escape_string($conn,$_POST['name']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$roll=mysqli_real_escape_string($conn,$_POST['roll']);
$dept=mysqli_real_escape_string($conn,$_POST['dept']);

$old=mysqli_query($conn,"SELECT photo FROM students WHERE id=$id");
$oldData=mysqli_fetch_assoc($old);
$photoName=$oldData['photo'];

if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

if($photoName!=""){
@unlink("../uploads/students/".$photoName);
}

$ext=pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photoName=time().rand(100,999).".".$ext;

move_uploaded_file(
$_FILES['photo']['tmp_name'],
"../uploads/students/".$photoName
);

}

mysqli_query($conn,"UPDATE students SET
name='$name',
email='$email',
roll_no='$roll',
department='$dept',
photo='$photoName'
WHERE id=$id
");

$msg="✅ Student Updated Successfully";

}

/* =========================
   DELETE STUDENT
========================= */
if(isset($_GET['del'])){
$id=intval($_GET['del']);

$q=mysqli_query($conn,"SELECT photo FROM students WHERE id=$id");

if($q && mysqli_num_rows($q)==1){
$r=mysqli_fetch_assoc($q);
if($r['photo']!=""){
@unlink("../uploads/students/".$r['photo']);
}
}

mysqli_query($conn,"DELETE FROM students WHERE id=$id");
header("Location: students.php");
exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Students</title>

<style>

body{
font-family:Segoe UI;
background:#eef2f7;
}

.container{
margin-left:220px;
padding:20px;
}

.card{
background:#fff;
padding:18px;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
margin-bottom:20px;
}

.form-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:10px;
}

input,select,button{
padding:10px;
border-radius:8px;
border:1px solid #ccc;
}

button{
background:#4f46e5;
color:#fff;
border:none;
cursor:pointer;
}

table{
width:100%;
background:#fff;
border-collapse:collapse;
}

th{
background:#4f46e5;
color:#fff;
padding:12px;
}

td{
padding:10px;
border-bottom:1px solid #eee;
text-align:center;
}

img{
width:50px;
height:50px;
border-radius:50%;
object-fit:cover;
}

.action{
padding:5px 10px;
border-radius:6px;
color:#fff;
text-decoration:none;
font-size:13px;
}

.edit{background:#0ea5e9;}
.delete{background:#ef4444;}

</style>

</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container">

<h2>👨‍🎓 Students Management</h2>

<?php if($msg!=""){ ?>
<p style="color:green"><?php echo $msg; ?></p>
<?php } ?>

<!-- FILTER + PDF -->

<div class="card">

<form method="get">

<select name="dept">

<option value="">All Departments</option>

<?php
$dq=mysqli_query($conn,"SELECT * FROM departments");

while($d=mysqli_fetch_assoc($dq)){

$sel=($deptFilter==$d['dept_name'])?"selected":"";

echo "<option value='".$d['dept_name']."' $sel>".$d['dept_name']."</option>";

}
?>

</select>

<button type="submit">Filter</button>

<a href="students.php?export=pdf&dept=<?php echo $deptFilter; ?>">
<button type="button">⬇ Download PDF</button>
</a>

</form>

</div>

<!-- ADD / EDIT -->

<div class="card">

<form method="post" enctype="multipart/form-data">

<div class="form-grid">

<input name="name" placeholder="Name"
value="<?php echo $editData['name'] ?? ''; ?>" required>

<input name="email" placeholder="Email"
value="<?php echo $editData['email'] ?? ''; ?>" required>

<input name="roll" placeholder="Roll No"
value="<?php echo $editData['roll_no'] ?? ''; ?>" required>

<select name="dept" required>

<option value="">Select Department</option>

<?php
$dq=mysqli_query($conn,"SELECT * FROM departments ORDER BY dept_name");

while($d=mysqli_fetch_assoc($dq)){

$selected="";

if(isset($editData['department']) && $editData['department']==$d['dept_name']){
$selected="selected";
}

echo "<option value='".$d['dept_name']."' $selected>".$d['dept_name']."</option>";

}
?>

</select>

<?php if(!$editData){ ?>
<input type="password" name="password" placeholder="Password" required>
<?php } ?>

<input type="file" name="photo">

<?php if($editData){ ?>

<input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
<button name="update">Update Student</button>

<?php } else { ?>

<button name="add">Add Student</button>

<?php } ?>

</div>

</form>

</div>

<!-- STUDENT LIST -->

<table>

<tr>
<th>Photo</th>
<th>Name</th>
<th>Roll</th>
<th>Dept</th>
<th>Action</th>
</tr>

<?php

$q=mysqli_query($conn,"SELECT * FROM students $where ORDER BY id DESC");

while($r=mysqli_fetch_assoc($q)){

?>

<tr>

<td>

<?php if($r['photo']!=""){ ?>

<img src="../uploads/students/<?php echo $r['photo']; ?>">

<?php } else echo "N/A"; ?>

</td>

<td><?php echo $r['name']; ?></td>
<td><?php echo $r['roll_no']; ?></td>
<td><?php echo $r['department']; ?></td>

<td>

<a class="action edit" href="?edit=<?php echo $r['id']; ?>">Edit</a>

<a class="action delete"
href="?del=<?php echo $r['id']; ?>"
onclick="return confirm('Delete this student?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>