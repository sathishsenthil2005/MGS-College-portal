<?php
session_start();
include("../config/db.php");

/* ---------- DB CHECK ---------- */
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* ---------- LOGIN CHECK ---------- */
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

/* ---------- TODAY DATE ---------- */
$today = date('Y-m-d'); // AUTO RESET KEY
?>

<!DOCTYPE html>
<html>
<head>
<title>Today's Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    font-family:Segoe UI, Arial;
    background:#f4f6f9;
    margin:0;
}

/* container */
.container{
    margin-left:220px;
    padding:30px;
}

/* card */
.card{
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    animation:fadeIn .4s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(15px);}
    to{opacity:1; transform:translateY(0);}
}

/* table */
table{
    border-collapse:collapse;
    width:100%;
    margin-top:15px;
}

th,td{
    padding:12px;
    text-align:center;
}

th{
    background:#007bff;
    color:#fff;
}

tr:nth-child(even){
    background:#f9f9f9;
}

/* status colors */
.present{
    color:green;
    font-weight:bold;
}

.absent{
    color:red;
    font-weight:bold;
}

.no-data{
    text-align:center;
    color:red;
    padding:20px;
}

.error{
    color:red;
    font-weight:bold;
    text-align:center;
}

/* mobile */
@media(max-width:768px){
    .container{
        margin-left:0;
        padding:15px;
    }
}
</style>
</head>

<body>

<?php include("dashboard.php"); ?>

<div class="container">
<div class="card">

<h2>📅 Today Attendance (<?php echo $today; ?>)</h2>

<table border="1">
<tr>
    <th>Date</th>
    <th>Subject</th>
    <th>Status</th>
</tr>

<?php
/* ---------- TODAY ONLY QUERY ---------- */
$sql = "SELECT date, subject, status 
        FROM attendance 
        WHERE student_id = $sid 
        AND date = '$today'
        ORDER BY date DESC";

$q = mysqli_query($conn, $sql);

/* ---------- RESULT ---------- */
if (!$q) {
    echo "<tr><td colspan='3' class='error'>Query Error: "
         . mysqli_error($conn) . "</td></tr>";
}
else if (mysqli_num_rows($q) > 0) {

    while ($r = mysqli_fetch_assoc($q)) {

        $statusClass = strtolower($r['status']) == 'present'
            ? 'present'
            : 'absent';

        echo "<tr>
                <td>".htmlspecialchars($r['date'])."</td>
                <td>".htmlspecialchars($r['subject'])."</td>
                <td class='$statusClass'>".htmlspecialchars($r['status'])."</td>
              </tr>";
    }

} else {
    echo "<tr>
            <td colspan='3' class='no-data'>
            No attendance marked for today
            </td>
          </tr>";
}
?>

</table>

</div>
</div>

</body>
</html>