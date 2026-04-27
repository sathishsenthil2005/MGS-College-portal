<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

/* ===== GET STUDENT DEPARTMENT ===== */
$stu = mysqli_query($conn,"SELECT department FROM students WHERE id=$sid");
$stuData = mysqli_fetch_assoc($stu);

if(!$stuData){
    die("Student not found");
}

$dept = $stuData['department'];

/* ===== WORKING DAYS ===== */
$days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];

/* ===== TODAY ===== */
$todayName = date("l");

/* ===== NEXT WORKING DAY (STRONG LOGIC) ===== */
$todayIndex = array_search($todayName, $days);

if($todayIndex === false){
    // Sunday or unknown → Monday
    $nextDay = "Monday";
} else {
    $nextIndex = $todayIndex + 1;

    if($nextIndex >= count($days)){
        $nextIndex = 0; // Saturday → Monday
    }

    $nextDay = $days[$nextIndex];
}

/* =====================================================
   MODE LOGIC
   ===================================================== */

$mode = isset($_GET['mode']) ? $_GET['mode'] : 'today';

/* 🔥 DEFAULT DAY */
if($mode === 'upcoming'){
    $day = $nextDay;      // ✅ next working day
} else {
    // if today is Sunday → show Monday
    if(!in_array($todayName,$days)){
        $day = "Monday";
    } else {
        $day = $todayName;
    }
}

/* 🔥 Manual override (dropdown select) */
if(isset($_GET['day']) && in_array($_GET['day'],$days)){
    $day = $_GET['day'];
}

/* ===== SANITIZE ===== */
$day  = mysqli_real_escape_string($conn,$day);
$dept = mysqli_real_escape_string($conn,$dept);
?>
<!DOCTYPE html>
<html>
<head>
<title>My Timetable</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f6f9;
}

.container{
    margin-left:220px;
    padding:25px;
}

table{
    border-collapse:collapse;
    background:#fff;
    width:100%;
    box-shadow:0 6px 15px rgba(0,0,0,.08);
}

th,td{
    padding:12px;
    text-align:center;
}

th{
    background:#1976d2;
    color:#fff;
}

select{
    padding:8px;
    margin-bottom:15px;
}

.badge{
    background:#ff9800;
    color:#fff;
    padding:4px 10px;
    border-radius:6px;
    font-size:12px;
    margin-left:8px;
}

@media(max-width:900px){
    .container{margin-left:0;}
}
</style>
</head>

<body>

<?php include("dashboard.php"); ?>

<div class="container">

<h2>
My Timetable
<?php if($mode=='upcoming') echo "<span class='badge'>Upcoming</span>"; ?>
</h2>

<form method="get">
<input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode); ?>">
<select name="day" onchange="this.form.submit()">
<?php
foreach($days as $d){
    $sel = ($day==$d) ? "selected" : "";
    echo "<option value='$d' $sel>$d</option>";
}
?>
</select>
</form>

<p>
<b>Department:</b> <?php echo htmlspecialchars($dept); ?> |
<b>Showing Day:</b> <?php echo htmlspecialchars($day); ?>
</p>

<table border="1">
<tr>
<th>Time</th>
<th>Subject</th>
</tr>

<?php
$q = mysqli_query($conn,"
    SELECT subject,time_slot 
    FROM timetable
    WHERE department='$dept' AND day='$day'
    ORDER BY time_slot
");

if($q && mysqli_num_rows($q) > 0){
    while($r = mysqli_fetch_assoc($q)){
        echo "<tr>
                <td>".htmlspecialchars($r['time_slot'])."</td>
                <td>".htmlspecialchars($r['subject'])."</td>
              </tr>";
    }
}else{
    echo "<tr><td colspan='2' style='color:red'>No timetable found</td></tr>";
}
?>

</table>

</div>

</body>
</html>