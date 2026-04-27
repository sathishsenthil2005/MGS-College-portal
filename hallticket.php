<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

/* ===== CLEAR FROM STUDENT VIEW ===== */
if(isset($_GET['clear'])){
    $hid = (int)$_GET['clear'];

    mysqli_query($conn,"
        UPDATE halltickets
        SET is_cleared = 1
        WHERE id='$hid'
        AND student_id='$sid'
    ");
}

/* ===== FETCH ONLY NOT CLEARED ===== */
$q = mysqli_query($conn,"
    SELECT * FROM halltickets
    WHERE student_id='$sid'
    AND (is_cleared IS NULL OR is_cleared=0)
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>My Hall Ticket</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f1f5f9;
    margin:0;
    padding:20px;
}
.container{
    max-width:600px;
    margin:auto;
}
.card{
    background:#fff;
    border-radius:16px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    animation:fadeInUp .5s ease;
}
.card h2{margin-top:0;color:#2563eb;}
.info{margin:6px 0;font-size:15px;}

.hall-img{
    width:100%;
    border-radius:12px;
    margin-top:12px;
    border:1px solid #e5e7eb;
}

.download-btn,
.clear-btn{
    display:inline-block;
    margin-top:12px;
    padding:10px 16px;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
}

.download-btn{background:#2563eb;}
.download-btn:hover{background:#1e40af;}

.clear-btn{background:#dc3545;margin-left:8px;}
.clear-btn:hover{background:#b91c1c;}

.empty{
    text-align:center;
    padding:40px;
    background:#fff;
    border-radius:16px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

@keyframes fadeInUp{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:translateY(0);}
}
</style>
</head>

<body>

<div class="container">
<h2>🎫 My Hall Ticket</h2>

<?php if(mysqli_num_rows($q) > 0){ ?>

<?php while($r=mysqli_fetch_assoc($q)){ ?>

<div class="card">


<div class="ticket-info">

<h3>🎫 Hall Ticket Available</h3>

<p><b>Roll Number :</b> <?php echo $r['roll_no']; ?></p>

</div>

<br>

<a class="download-btn"
href="../uploads/halltickets/<?php echo $r['file_path']; ?>"
download>
⬇ Download
</a>

<a class="clear-btn"
href="?clear=<?php echo $r['id']; ?>"
onclick="return confirm('Clear this hall ticket from your view?')">
🧹 Clear
</a>

</div>

<?php } ?>

<?php } else { ?>

<div class="empty">
❌ Hall ticket not available
</div>

<?php } ?>

</div>
</body>
</html>