<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

/* fetch */
$q = mysqli_query($conn,"SELECT * FROM students WHERE id=$sid");
$r = mysqli_fetch_assoc($q);

/* UPDATE */
if(isset($_POST['update'])){

    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $roll = mysqli_real_escape_string($conn,$_POST['roll_no']);
    $dept = mysqli_real_escape_string($conn,$_POST['department']);

    mysqli_query($conn,"
        UPDATE students SET
        name='$name',
        email='$email',
        roll_no='$roll',
        department='$dept'
        WHERE id=$sid
    ");

    echo "<script>alert('Profile Updated');window.location='profile.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{font-family:Segoe UI;background:#f4f6f9;margin:0;}
.container{margin-left:220px;padding:30px;}
.form-box{
    max-width:600px;margin:auto;background:#fff;
    padding:25px;border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}
.input{
    width:100%;padding:10px;margin:8px 0;
    border:1px solid #ccc;border-radius:6px;
}
.btn{
    width:100%;padding:12px;border:none;
    background:#007bff;color:#fff;border-radius:6px;
    font-weight:bold;cursor:pointer;
}
</style>
</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container">
<div class="form-box">

<h2>✏️ Edit Profile</h2>

<form method="post">

<label>Name</label>
<input type="text" name="name" class="input"
value="<?php echo htmlspecialchars($r['name']); ?>" required>

<label>Email</label>
<input type="email" name="email" class="input"
value="<?php echo htmlspecialchars($r['email']); ?>" required>

<label>Roll No</label>
<input type="text" name="roll_no" class="input"
value="<?php echo htmlspecialchars($r['roll_no']); ?>" required>

<label>Department</label>
<input type="text" name="department" class="input"
value="<?php echo htmlspecialchars($r['department']); ?>" required>

<button type="submit" name="update" class="btn">
💾 Save Changes
</button>

</form>

</div>
</div>
</body>
</html>