<?php
include("../config/db.php");
$msg="";

if(isset($_POST['register'])){
    $name=mysqli_real_escape_string($conn,$_POST['name']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $roll=mysqli_real_escape_string($conn,$_POST['roll']);
    $dept=mysqli_real_escape_string($conn,$_POST['dept']);
    $pass=password_hash($_POST['password'],PASSWORD_DEFAULT);

    mysqli_query($conn,"INSERT INTO students(name,email,roll_no,department,password)
    VALUES('$name','$email','$roll','$dept','$pass')");
    $msg="Registration Successful. Login Now.";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>
<style>
body{
background:linear-gradient(135deg,#1d976c,#93f9b9);
font-family:Arial;height:100vh;
display:flex;justify-content:center;align-items:center;
}
.card{
background:#fff;padding:30px;border-radius:12px;width:350px;
box-shadow:0 10px 25px rgba(0,0,0,.3);
}
input,button{width:100%;padding:10px;margin:8px 0;}
button{background:#1d976c;color:#fff;border:none;}
.msg{color:green;text-align:center;}
</style>
</head>
<body>

<div class="card">
<h2>Student Register</h2>
<form method="post">
<input name="name" placeholder="Name" required>
<input name="email" placeholder="Email" required>
<input name="roll" placeholder="Roll No" required>
<input name="dept" placeholder="Department" required>
<input type="password" name="password" placeholder="Password" required>
<button name="register">Register</button>
</form>
<p class="msg"><?php echo $msg; ?></p>
<a href="login.php">Already Registered? Login</a>
</div>

</body>
</html>
