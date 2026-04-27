<?php require_once("config/db.php"); ?>
<!DOCTYPE html>
<html>
<head>
<title>ANJAC College Portal</title>

<style>
*{
    box-sizing:border-box;
    font-family:Segoe UI, Arial;
}
body{
    margin:0;
    background:#f5f6f8;
}

/* Top Bar */
.topbar{
    background:#1e78d6;
    color:#fff;
    padding:14px 30px;
    font-size:20px;
    font-weight:bold;
    box-shadow:0 3px 8px rgba(0,0,0,.2);
}

/* Main */
.main{
    max-width:800px;
    margin:40px auto;
    text-align:center;
}

/* Logo */
.logo{
    width:100px;
    height:100px;
    margin:0 auto 15px;
}
.logo img{
    width:100%;
    height:100%;
    object-fit:contain;
}

/* College name */
.college{
    font-size:20px;
    font-weight:bold;
}
.place{
    font-size:14px;
    color:#555;
    margin-bottom:35px;
}

/* Login cards */
.login-box{
    display:flex;
    justify-content:center;
    gap:25px;
    flex-wrap:wrap;
}

.login-card{
    background:#fff;
    width:280px;
    padding:18px 20px;
    border-radius:12px;
    box-shadow:0 8px 18px rgba(0,0,0,.15);
    display:flex;
    align-items:center;
    justify-content:space-between;
    text-decoration:none;
    color:#333;
    transition:.3s;
}
.login-card:hover{
    transform:translateY(-4px);
}

/* Icons */
.icon{
    width:45px;
    height:45px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    color:#fff;
}
.student-icon{ background:#ff5a8a; }
.staff-icon{ background:#7c4dff; }

/* Text */
.text{
    text-align:left;
    flex:1;
    margin-left:15px;
}
.text h4{
    margin:0;
    font-size:16px;
}
.text p{
    margin:2px 0 0;
    font-size:12px;
    color:#666;
}

/* Arrow */
.arrow{
    font-size:20px;
    color:#aaa;
}

/* Footer */
.footer{
    margin-top:45px;
    font-size:13px;
    color:#666;
}
.footer a{
    color:#1e78d6;
    text-decoration:none;
    margin:0 8px;
}
.footer a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="topbar">MGS Arts and Science College</div>

<div class="main">

    <!-- College Logo -->
    <div class="logo">
        <img src="assets/logo.jpg" alt="ANJAC Logo">
    </div>

    <div class="college">
        MGS ARTS AND SCIENCE COLLEGE (AUTONOMOUS)
    </div>
    <div class="place">RAJAPALAYAM</div>

    <!-- Login -->
    <div class="login-box">

        <a href="student/login.php" class="login-card">
            <div class="icon student-icon">👨‍🎓</div>
            <div class="text">
                <h4>Student Login</h4>
                <p>Attendance • Results • Fees</p>
            </div>
            <div class="arrow">›</div>
        </a>

        <a href="staff/login.php" class="login-card">
            <div class="icon staff-icon">👨‍🏫</div>
            <div class="text">
                <h4>Staff Login</h4>
                <p>Attendance • Marks</p>
            </div>
            <div class="arrow">›</div>
        </a>

    </div>

    <!-- Footer -->
    <div class="footer">
        <a href="terms.php">Terms & Conditions</a> |
        <a href="privacy.php">Privacy Policy</a><br><br>
        © <?php echo date("Y"); ?> MGS College Portal
    </div>

</div>

</body>
</html>
