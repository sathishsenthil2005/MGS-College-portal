<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = $_SESSION['student_id'];

/* student details */
$stuQ = mysqli_query($conn,"SELECT * FROM students WHERE id='$sid'");
$stu  = mysqli_fetch_assoc($stuQ);

/* counts */
$att = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM attendance WHERE student_id='$sid'"));
$res = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM results WHERE student_id='$sid'"));

$photo = (!empty($stu['photo']))
        ? "../uploads/students/".$stu['photo']
        : "../uploads/students/default.png";

/* ===== UPCOMING DAY LOGIC ===== */
$nextDate = new DateTime();
do {
    $nextDate->modify('+1 day');
} while($nextDate->format('N') == 7); // skip Sunday

$up_date = $nextDate->format("d M Y");
$up_day  = $nextDate->format("l");
?>
<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f3f6fb;
}

/* ===== APP PAGE ANIMATION ===== */
.page-transition{
    opacity:1;
    transform:translateX(0);
    transition:all .35s ease;
}
.page-transition.fade-out{
    opacity:0;
    transform:translateX(30px);
}

/* ===== SIDEBAR ===== */
.sidebar{
    width:230px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:#fff;
    border-right:1px solid #ddd;
    transition:.3s;
    z-index:999;
}
.sidebar h2{
    text-align:center;
    padding:15px;
    background:#1976d2;
    color:#fff;
    margin:0;
}
.sidebar a{
    display:block;
    padding:13px 18px;
    color:#333;
    text-decoration:none;
    transition:.25s;
}
.sidebar a:hover{background:#f1f4fb}

/* ===== TOPBAR ===== */
.topbar{
    margin-left:230px;
    background:#1976d2;
    color:#fff;
    padding:15px 20px;
    font-size:20px;
    font-weight:bold;
    display:flex;
    align-items:center;
    gap:15px;
}

/* hamburger */
.menu-btn{
    font-size:22px;
    cursor:pointer;
    display:none;
}

/* ===== MAIN ===== */
.main{
    margin-left:230px;
    padding:25px;
}

/* PROFILE */
.profile{
    background:#fff;
    padding:20px;
    border-radius:12px;
    display:flex;
    align-items:center;
    gap:20px;
    box-shadow:0 6px 15px rgba(0,0,0,.1);
}
.profile img{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #1976d2;
}

/* QUICK */
.section{margin-top:25px}
.quick{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
    gap:15px;
}
.qbox{
    background:#fff;
    padding:18px;
    border-radius:12px;
    text-align:center;
    text-decoration:none;
    color:#333;
    box-shadow:0 6px 15px rgba(0,0,0,.08);
    transition:.25s;
}
.qbox:hover{transform:translateY(-4px)}

/* RIGHT */
.right-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 15px rgba(0,0,0,.1);
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
}

/* ===== MOBILE ===== */
@media(max-width:900px){

    .menu-btn{display:block;}

    .sidebar{left:-230px;}
    .sidebar.show{left:0;}

    .topbar,
    .main{margin-left:0;}

    .grid{grid-template-columns:1fr;}
}
</style>
</head>

<body class="page-transition">

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
<h2>MGS COLLEGE PORTAL</h2>

<a href="dashboard.php" onclick="goPage(event,'dashboard.php')">🏠 Home</a>
<a href="attendance.php" onclick="goPage(event,'attendance.php')">📊 Attendance</a>
<a href="fees.php" onclick="goPage(event,'fees.php')">💰 My Fees</a>
<a href="timetable.php" onclick="goPage(event,'timetable.php')">📅 Time Table</a>
<a href="hallticket.php" onclick="goPage(event,'hallticket.php')">🎫 Hall Ticket</a>
<a href="results.php" onclick="goPage(event,'results.php')">📑 Results</a>
<a href="profile.php" onclick="goPage(event,'profile.php')">👤 Profile</a>
<a href="logout.php" onclick="goPage(event,'logout.php')" style="color:red;">🚪 Logout</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
<span class="menu-btn" onclick="toggleMenu()">☰</span>
Dashboard
</div>

<div class="main">

<div class="grid">

<!-- LEFT -->
<div>

<div class="profile">
    <img src="<?php echo $photo; ?>">
    <div>
        <h2><?php echo $stu['name']; ?></h2>
        <div><?php echo $stu['roll_no']; ?></div>
        <div><?php echo $stu['department']; ?></div>
    </div>
</div>

<div class="section">
<h3>QUICK ACTIONS</h3>

<div class="quick">

<a href="attendance.php" class="qbox" onclick="goPage(event,'attendance.php')">
Attendance
</a>

<a href="fees.php" class="qbox" onclick="goPage(event,'fees.php')">
My Fees
</a>
<a href="timetable.php" class="qbox" onclick="goPage(event,'timetable.php')">
Time Table
</a>
<a href="hallticket.php" class="qbox" onclick="goPage(event,'hallticket.php')">
Hall Ticket
</a>
<a href="results.php" class="qbox" onclick="goPage(event,'results.php')">
Results
</a>

<a href="profile.php" class="qbox" onclick="goPage(event,'profile.php')">
My Profile
</a>

</div>
</div>

</div>

<!-- RIGHT -->
<div class="right-card">
<h3>UPCOMING TIME TABLE</h3>
<p><b><?php echo $up_date; ?></b></p>
<p>Day <?php echo $up_day; ?></p>
<a href="timetable.php?mode=upcoming"
onclick="goPage(event,'timetable.php?mode=upcoming')"
style="background:#1976d2;color:#fff;padding:6px 14px;border-radius:6px;text-decoration:none;">
View →
</a>
</div>

</div>

<div style="margin-top:40px;text-align:center;color:#777;font-size:13px;">
© <?php echo date("Y"); ?> MGS ARTS AND SCIENCE COLLEGE (AUTONOMOUS)
</div>

</div>

<script>
function toggleMenu(){
    document.getElementById("sidebar").classList.toggle("show");
}

/* 🚀 APP LIKE NAVIGATION */
function goPage(e, url){
    e.preventDefault();
    document.getElementById("sidebar").classList.remove("show");
    document.body.classList.add("fade-out");

    setTimeout(function(){
        window.location.href = url;
    }, 300);
}

window.addEventListener("pageshow", function(){
    document.body.classList.remove("fade-out");
});
</script>

</body>
</html>