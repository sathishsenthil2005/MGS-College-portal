<?php
session_start();
include("../config/db.php"); // ✅ FIXED

$result = "";

if(isset($_POST['track'])){
$app = $_POST['app_no'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM admissions WHERE application_no='$app'"));

if($data){
$result = $data;
}else{
$msg = "❌ Application Not Found";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Track Application</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{font-family:Segoe UI;background:#2563eb;color:#fff;text-align:center;padding:30px;}
.box{background:#fff;color:#000;padding:20px;border-radius:10px;max-width:400px;margin:auto;}
input{width:100%;padding:10px;margin:10px 0;}
button{padding:10px;background:#2563eb;color:#fff;border:none;width:100%;}
.back{
display:inline-block;
margin-top:15px;
padding:8px 12px;
background:#111827;
color:#fff;
border-radius:6px;
text-decoration:none;
}
</style>
</head>

<body>

<div class="box">

<h2>🔍 Track Application</h2>

<form method="post">
<input type="text" name="app_no" placeholder="Enter Application No" required>
<button name="track">Check Status</button>
</form>

<?php if(isset($result) && $result){ ?>

<hr>

<p><b>Name:</b> <?php echo $result['name']; ?></p>
<p><b>Status:</b> <?php echo $result['status']; ?></p>

<?php if($result['status']=="approved"){ ?>
<p style="color:green;">🎉 Your Application Approved!</p>
<?php } ?>

<?php if(!empty($result['message'])){ ?>
<p><?php echo $result['message']; ?></p>
<?php } ?>

<?php } ?>

<?php if(isset($msg)) echo "<p>$msg</p>"; ?>

</div>
<a class="back" href="apply.php">⬅ Back</a>
</body>
</html>