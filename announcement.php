<?php
session_start();
include("../config/db.php");

/* ---------- LOGIN CHECK (STAFF) ---------- */
if(!isset($_SESSION['staff_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Staff Announcements</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f3f6fb;
}

/* container */
.container{
    margin-left:230px;
    padding:25px;
}

/* card */
.card{
    background:#fff;
    padding:20px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

/* announcement box */
.announce{
    padding:15px;
    border-bottom:1px solid #eee;
    transition:.2s;
}

.announce:hover{
    background:#f7faff;
}

/* text */
.title{
    font-size:17px;
    font-weight:bold;
    color:#1976d2;
}

.msg{
    margin-top:6px;
    color:#444;
}

.date{
    font-size:12px;
    color:#888;
    margin-top:6px;
}

/* empty */
.empty{
    text-align:center;
    color:#999;
    padding:30px;
}

/* mobile */
@media(max-width:768px){
    .container{margin-left:0;}
}
</style>
</head>

<body>

<?php include("dashboard.php"); ?>

<div class="container">

<div class="card">
<h2>📢 Announcements</h2>

<?php
$q=mysqli_query($conn,"SELECT * FROM announcements ORDER BY id DESC");

if(mysqli_num_rows($q)==0){
    echo "<div class='empty'>No announcements available</div>";
}

while($r=mysqli_fetch_assoc($q)){
?>

<div class="announce">
    <div class="title">
        <?php echo htmlspecialchars($r['title']); ?>
    </div>

    <div class="msg">
        <?php echo nl2br(htmlspecialchars($r['message'])); ?>
    </div>

    <div class="date">
        Posted on: <?php echo date("d M Y, h:i A", strtotime($r['created_at'])); ?>
    </div>
</div>

<?php } ?>

</div>
</div>

</body>
</html>