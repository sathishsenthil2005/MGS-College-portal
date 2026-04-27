<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit;
}

$sid = (int)$_SESSION['student_id'];

$q = mysqli_query($conn,"
    SELECT * FROM results
    WHERE student_id='$sid'
    ORDER BY exam_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>My Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    margin:0;
    font-family:Segoe UI, Arial;
    background:#eef2f7;
}

/* ===== HEADER ===== */
.header{
    background:linear-gradient(135deg,#4f46e5,#06b6d4);
    color:#fff;
    padding:18px;
    font-size:20px;
    font-weight:bold;
    display:flex;
    align-items:center;
    gap:12px;
    position:sticky;
    top:0;
}

/* back button */
.back-btn{
    background:rgba(255,255,255,.2);
    border:none;
    color:#fff;
    padding:6px 12px;
    border-radius:8px;
    cursor:pointer;
    font-size:14px;
}
.back-btn:hover{background:rgba(255,255,255,.35);}

/* ===== CONTAINER ===== */
.container{
    padding:18px;
    max-width:1000px;
    margin:auto;
}

/* ===== CARD (Mobile) ===== */
.card{
    background:#fff;
    border-radius:16px;
    padding:16px;
    margin-bottom:14px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
    animation:fadeUp .4s ease;
}

.subject{
    font-weight:bold;
    font-size:17px;
    color:#4f46e5;
}

.row{
    display:flex;
    justify-content:space-between;
    margin-top:6px;
    font-size:14px;
}

/* ===== GRADE ===== */
.grade{
    padding:3px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    color:#fff;
}

.gA{background:#22c55e;}
.gB{background:#3b82f6;}
.gC{background:#f59e0b;}
.gF{background:#ef4444;}

/* ===== TABLE (Desktop) ===== */
.table-wrap{
    display:none;
}

.result-table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.result-table th{
    background:#4f46e5;
    color:#fff;
    padding:12px;
    text-align:left;
}

.result-table td{
    padding:12px;
    border-bottom:1px solid #eee;
}

.result-table tr:hover{
    background:#f1f5f9;
}

/* empty */
.empty{
    text-align:center;
    background:#fff;
    padding:40px;
    border-radius:16px;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

/* animation */
@keyframes fadeUp{
    from{opacity:0;transform:translateY(15px);}
    to{opacity:1;transform:translateY(0);}
}

/* ===== DESKTOP SWITCH ===== */
@media(min-width:768px){
    .card{display:none;}
    .table-wrap{display:block;}
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
<button class="back-btn" onclick="history.back()">← Back</button>
My Results
</div>

<div class="container">

<?php if(mysqli_num_rows($q)>0){ ?>

    <!-- ✅ MOBILE CARD VIEW -->
    <?php 
    mysqli_data_seek($q,0);
    while($r=mysqli_fetch_assoc($q)){ 

        $gradeClass="gC";
        if($r['grade']=="A") $gradeClass="gA";
        elseif($r['grade']=="B") $gradeClass="gB";
        elseif($r['grade']=="F") $gradeClass="gF";
    ?>
    <div class="card">
        <div class="subject">
            <?php echo htmlspecialchars($r['subject']); ?>
        </div>

        <div class="row">
            <div>Marks: <b><?php echo htmlspecialchars($r['marks']); ?></b></div>
            <div class="grade <?php echo $gradeClass; ?>">
                <?php echo htmlspecialchars($r['grade']); ?>
            </div>
        </div>

        <div class="row">
            <div style="color:#6b7280;">
                Exam Date: <?php echo htmlspecialchars($r['exam_date']); ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- ✅ DESKTOP TABLE VIEW -->
    <div class="table-wrap">
        <table class="result-table">
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Date</th>
            </tr>

            <?php 
            mysqli_data_seek($q,0);
            while($r=mysqli_fetch_assoc($q)){ 

                $gradeClass="gC";
                if($r['grade']=="A") $gradeClass="gA";
                elseif($r['grade']=="B") $gradeClass="gB";
                elseif($r['grade']=="F") $gradeClass="gF";
            ?>
            <tr>
                <td><?php echo htmlspecialchars($r['subject']); ?></td>
                <td><?php echo htmlspecialchars($r['marks']); ?></td>
                <td>
                    <span class="grade <?php echo $gradeClass; ?>">
                        <?php echo htmlspecialchars($r['grade']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($r['exam_date']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

<?php } else { ?>

    <div class="empty">
        📭 No results published yet
    </div>

<?php } ?>

</div>
</body>
</html>