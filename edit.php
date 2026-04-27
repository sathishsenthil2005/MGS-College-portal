<?php
include("../config/db.php");

if(!isset($_GET['id'])){
die("Invalid Request");
}

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM admissions WHERE id=$id"));

if(isset($_POST['update'])){

$name = $_POST['name'];
$phone = $_POST['phone'];
$dept = $_POST['department'];
$status = $_POST['status'];

// update query
mysqli_query($conn,"
UPDATE admissions SET
name='$name',
phone='$phone',
department='$dept',
status='$status'
WHERE id=$id
");

header("Location: dashboard.php");
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>

<style>
body{font-family:Segoe UI;background:#f3f4f6;padding:20px;}
.box{
background:#fff;
padding:20px;
max-width:400px;
margin:auto;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
input,select{
width:100%;
padding:10px;
margin:8px 0;
}
.back{
display:inline-block;
margin-top:15px;
padding:8px 12px;
background:#111827;
color:#fff;
border-radius:6px;
text-decoration:none;
}
button{
padding:10px;
background:#2563eb;
color:#fff;
border:none;
width:100%;
}
</style>
</head>

<body>

<div class="box">

<h2>Edit Student</h2>

<form method="post">

<input type="text" name="name" value="<?php echo $data['name']; ?>" required>

<input type="text" name="phone" value="<?php echo $data['phone']; ?>" required>

<select name="department">
<option <?php if($data['department']=="BCA") echo "selected"; ?>>BCA</option>
<option <?php if($data['department']=="BBA") echo "selected"; ?>>BBA</option>
<option <?php if($data['department']=="B.Com") echo "selected"; ?>>B.Com</option>
<option <?php if($data['department']=="B.Sc Computer Science") echo "selected"; ?>>B.Sc Computer Science</option>
</select>

<select name="status">
<option value="pending" <?php if($data['status']=="pending") echo "selected"; ?>>Pending</option>
<option value="approved" <?php if($data['status']=="approved") echo "selected"; ?>>Approved</option>
</select>

<button name="update">Update</button>

</form>
<a class="back" href="index.php">⬅ Back</a>
</div>

</body>
</html>