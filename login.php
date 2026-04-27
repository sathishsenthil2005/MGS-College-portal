<?php
include("../config/db.php");
$error="";

if(isset($_POST['login'])){
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $pass=$_POST['password'];

    $q=mysqli_query($conn,"SELECT * FROM students WHERE email='$email'");
    if(mysqli_num_rows($q)==1){
        $r=mysqli_fetch_assoc($q);
        if(password_verify($pass,$r['password'])){
            $_SESSION['student_id']=$r['id'];
            header("Location: dashboard.php"); exit;
        } else $error="Wrong Password";
    } else $error="Student Not Found";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Login - MGS</title>

<style>
*{
    box-sizing:border-box;
    font-family:Segoe UI, Arial;
}
body{
    margin:0;
    height:100vh;
    background:linear-gradient(120deg,#1e78d6,#64b5f6);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* Card */
.card{
    background:#fff;
    width:360px;
    padding:30px;
    border-radius:14px;
    box-shadow:0 15px 30px rgba(0,0,0,.25);
    text-align:center;
}

college-logo{
    width:70px;
    height:70px;
    border-radius:50%;
    margin:0 auto 10px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 4px 10px rgba(0,0,0,.2);
}
.college-logo img{
    width:55px;
    height:auto;
}


/* Student icon */
.student-icon{
    font-size:36px;
    margin:10px 0;
}

/* Title */
h2{
    margin:5px 0 5px;
    font-size:20px;
}
.sub{
    font-size:12px;
    color:#666;
    margin-bottom:20px;
}

/* Inputs */
input{
    width:100%;
    padding:11px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
}

/* Button */
button{
    width:100%;
    padding:11px;
    margin-top:10px;
    background:#1e78d6;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
}
button:hover{
    background:#155fa0;
}

/* Error */
.error{
    color:red;
    font-size:13px;
    margin-top:10px;
}

/* Footer */
.footer{
    margin-top:20px;
    font-size:12px;
    color:#777;
}
</style>
</head>

<body>

<div class="card">

    <div class="college-logo">
    <img src="../assets/logo.jpg" alt="ANJAC Logo">
</div>


    <!-- Student Logo -->
    <div class="student-icon">🎓</div>

    <h2>Student Login</h2>
    <div class="sub">
        MGS ARTS AND SCIENCE COLLEGE<br>
        (AUTONOMOUS), RAJAPALAYAM.
    </div>

    <form method="post">
        <input name="email" placeholder="Student Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <?php if($error!=""){ ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <div class="footer">
        © <?php echo date("Y"); ?> MGS College Portal
    </div>

</div>

</body>
</html>
