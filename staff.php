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
   PDF DOWNLOAD
========================= */

if(isset($_GET['export']) && $_GET['export']=="pdf"){

require("fpdf.php");

$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Staff Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(60,10,'Name',1);
$pdf->Cell(50,10,'Email',1);
$pdf->Cell(40,10,'Subject',1);
$pdf->Cell(40,10,'Password',1);

$pdf->Ln();

$q=mysqli_query($conn,"SELECT * FROM staff ORDER BY id DESC");

$pdf->SetFont('Arial','',10);

while($r=mysqli_fetch_assoc($q)){

$pdf->Cell(60,10,$r['name'],1);
$pdf->Cell(50,10,$r['email'],1);
$pdf->Cell(40,10,$r['subject'],1);
$pdf->Cell(40,10,$r['name'],1); // password column la staff name

$pdf->Ln();

}

$pdf->Output("D","staff_Details.pdf");
exit;

}

/* =========================
   FETCH EDIT DATA
========================= */

if(isset($_GET['edit'])){
    $eid=intval($_GET['edit']);
    $eq=mysqli_query($conn,"SELECT * FROM staff WHERE id=$eid");
    $editData=mysqli_fetch_assoc($eq);
}

/* =========================
   ADD STAFF
========================= */

if(isset($_POST['add'])){

$name=mysqli_real_escape_string($conn,$_POST['name']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$subject=mysqli_real_escape_string($conn,$_POST['subject']);
$pass=password_hash($_POST['password'], PASSWORD_DEFAULT);

$uploadDir="../uploads/staff/";

if(!is_dir($uploadDir)){
mkdir($uploadDir,0777,true);
}

$photoName="";

if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

$ext=pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photoName=time().rand(100,999).".".$ext;

move_uploaded_file(
$_FILES['photo']['tmp_name'],
$uploadDir.$photoName
);

}

mysqli_query($conn,"INSERT INTO staff
(name,email,subject,password,photo)
VALUES
('$name','$email','$subject','$pass','$photoName')");

$msg="✅ Staff Added Successfully";

}

/* =========================
   UPDATE STAFF
========================= */

if(isset($_POST['update'])){

$id=intval($_POST['id']);
$name=mysqli_real_escape_string($conn,$_POST['name']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$subject=mysqli_real_escape_string($conn,$_POST['subject']);

$old=mysqli_query($conn,"SELECT photo FROM staff WHERE id=$id");
$oldData=mysqli_fetch_assoc($old);
$photoName=$oldData['photo'];

if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

if($photoName!=""){
@unlink("../uploads/staff/".$photoName);
}

$ext=pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photoName=time().rand(100,999).".".$ext;

move_uploaded_file(
$_FILES['photo']['tmp_name'],
"../uploads/staff/".$photoName
);

}

mysqli_query($conn,"UPDATE staff SET
name='$name',
email='$email',
subject='$subject',
photo='$photoName'
WHERE id=$id");

$msg="✅ Staff Updated Successfully";

}

/* =========================
   DELETE STAFF
========================= */

if(isset($_GET['del'])){
$id=intval($_GET['del']);

$q=mysqli_query($conn,"SELECT photo FROM staff WHERE id=$id");

if($q && mysqli_num_rows($q)==1){
$r=mysqli_fetch_assoc($q);
if($r['photo']!=""){
@unlink("../uploads/staff/".$r['photo']);
}
}

mysqli_query($conn,"DELETE FROM staff WHERE id=$id");

header("Location: staff.php");
exit;

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Staff Management</title>

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

input,button{
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
width:55px;
height:55px;
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

<h2>👨‍🏫 Staff Management</h2>

<?php if($msg!=""){ ?>
<p style="color:green"><?php echo $msg; ?></p>
<?php } ?>

<!-- PDF DOWNLOAD -->

<div class="card">
<a href="staff.php?export=pdf">
<button>⬇ Download Staff PDF</button>
</a>
</div>

<!-- FORM -->

<div class="card">

<form method="post" enctype="multipart/form-data">

<div class="form-grid">

<input name="name" placeholder="Name"
value="<?php echo $editData['name'] ?? ''; ?>" required>

<input name="email" placeholder="Email"
value="<?php echo $editData['email'] ?? ''; ?>" required>

<input name="subject" placeholder="Subject"
value="<?php echo $editData['subject'] ?? ''; ?>" required>

<?php if(!$editData){ ?>
<input type="password" name="password" placeholder="Password" required>
<?php } ?>

<input type="file" name="photo" accept="image/*">

<?php if($editData){ ?>

<input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
<button name="update">Update Staff</button>

<?php } else { ?>

<button name="add">Add Staff</button>

<?php } ?>

</div>

</form>

</div>

<!-- STAFF LIST -->

<table>

<tr>
<th>Photo</th>
<th>Name</th>
<th>Subject</th>
<th>Action</th>
</tr>

<?php

$q=mysqli_query($conn,"SELECT * FROM staff ORDER BY id DESC");

while($r=mysqli_fetch_assoc($q)){

$img=$r['photo'] ? "../uploads/staff/".$r['photo'] : "../assets/user.png";

?>

<tr>

<td><img src="<?php echo $img; ?>"></td>

<td><?php echo $r['name']; ?></td>

<td><?php echo $r['subject']; ?></td>

<td>

<a class="action edit" href="?edit=<?php echo $r['id']; ?>">Edit</a>

<a class="action delete"
href="?del=<?php echo $r['id']; ?>"
onclick="return confirm('Delete Staff?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>