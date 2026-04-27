<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

$q = mysqli_query($conn,"SELECT * FROM students WHERE id=$sid");
$r = mysqli_fetch_assoc($q);

if(!$r){
    echo "Student not found";
    exit;
}

/* photo path */
$photo = !empty($r['photo'])
    ? "../uploads/students/".$r['photo']
    : "https://via.placeholder.com/120x120?text=No+Photo";
?>
<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{margin:0;font-family:Segoe UI;background:#f4f6f9;}
.container{margin-left:220px;padding:30px;}
.card{max-width:850px;margin:auto;background:#fff;border-radius:15px;
box-shadow:0 8px 25px rgba(0,0,0,0.08);overflow:hidden;}
.header{background:linear-gradient(135deg,#007bff,#00c6ff);
color:#fff;text-align:center;padding:40px 20px;}
.avatar{
    width:120px;height:120px;border-radius:50%;
    object-fit:cover;border:4px solid #fff;
}
.body{padding:25px 30px;}
.row{display:flex;justify-content:space-between;
padding:14px 0;border-bottom:1px solid #eee;}
.label{color:#666;font-weight:600;}
.value{font-weight:500;}
.actions{text-align:center;margin-top:25px;}
.btn{
    padding:10px 22px;border:none;border-radius:6px;
    background:#007bff;color:#fff;cursor:pointer;font-weight:600;
}
@media(max-width:768px){
    .container{margin-left:0;padding:15px;}
    .row{flex-direction:column;gap:5px;}
}
</style>
</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container">
<div class="card">

<div class="header">
    <img src="<?php echo $photo; ?>" class="avatar">
    <h2><?php echo htmlspecialchars($r['name']); ?></h2>
    <p><?php echo htmlspecialchars($r['department']); ?></p>
</div>

<div class="body">

<div class="row">
    <div class="label">📧 Email</div>
    <div class="value"><?php echo htmlspecialchars($r['email']); ?></div>
</div>

<div class="row">
    <div class="label">🎓 Roll No</div>
    <div class="value"><?php echo htmlspecialchars($r['roll_no']); ?></div>
</div>

<div class="row">
    <div class="label">🏫 Department</div>
    <div class="value"><?php echo htmlspecialchars($r['department']); ?></div>
</div>

<div class="actions">
    <button class="btn"
        onclick="window.location.href='edit_profile.php'">
        ✏️ Edit Profile
    </button>
</div>

</div>
</div>
</div>
</body>
</html>