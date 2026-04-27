<?php
session_start();
include("../config/db.php");

/* ---------- LOGIN CHECK ---------- */
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

/* ---------- ADD ---------- */
if(isset($_POST['add'])){
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $msg   = mysqli_real_escape_string($conn,$_POST['msg']);

    mysqli_query($conn,"
        INSERT INTO announcements(title,message,created_at)
        VALUES('$title','$msg',NOW())
    ");
}

/* ---------- DELETE ---------- */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn,"DELETE FROM announcements WHERE id=$id");
    header("Location: announcements.php");
    exit;
}

/* ---------- UPDATE ---------- */
if(isset($_POST['update'])){
    $id    = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $msg   = mysqli_real_escape_string($conn,$_POST['msg']);

    mysqli_query($conn,"
        UPDATE announcements
        SET title='$title', message='$msg'
        WHERE id=$id
    ");
    header("Location: announcements.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Announcements</title>
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
    margin-bottom:25px;
}

/* inputs */
input, textarea{
    width:100%;
    padding:10px;
    margin-top:8px;
    margin-bottom:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:14px;
}

textarea{height:90px;resize:none;}

/* buttons */
.btn{
    padding:8px 14px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    text-decoration:none;
    font-size:13px;
}

.btn-add{background:#1976d2;color:#fff;}
.btn-edit{background:#ff9800;color:#fff;}
.btn-del{background:#e53935;color:#fff;}
.btn-save{background:#2e7d32;color:#fff;}

/* announcement list */
.announce{
    padding:14px;
    border-bottom:1px solid #eee;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.title{font-weight:bold;font-size:16px;}
.msg{color:#555;font-size:14px;margin-top:4px;}
.date{font-size:12px;color:#888;}

.actions a{margin-left:8px;}

/* mobile */
@media(max-width:768px){
    .container{margin-left:0;}
}
</style>
</head>

<body>

<?php include("dashboard.php"); ?>

<div class="container">

<!-- ADD FORM -->
<div class="card">
<h2>📢 Post New Announcement</h2>

<form method="post">
<input name="title" placeholder="Announcement Title" required>
<textarea name="msg" placeholder="Write message..." required></textarea>
<button class="btn btn-add" name="add">Post Announcement</button>
</form>
</div>

<!-- LIST -->
<div class="card">
<h2>📋 All Announcements</h2>

<?php
$q=mysqli_query($conn,"SELECT * FROM announcements ORDER BY id DESC");

if(mysqli_num_rows($q)==0){
    echo "<p>No announcements yet</p>";
}

while($r=mysqli_fetch_assoc($q)){
?>

<div class="announce">

<div>
    <div class="title"><?php echo htmlspecialchars($r['title']); ?></div>
    <div class="msg"><?php echo htmlspecialchars($r['message']); ?></div>
    <div class="date"><?php echo $r['created_at']; ?></div>
</div>

<div class="actions">
    <a class="btn btn-edit"
       href="announcements.php?edit=<?php echo $r['id']; ?>">Edit</a>

    <a class="btn btn-del"
       onclick="return confirm('Delete this announcement?')"
       href="announcements.php?delete=<?php echo $r['id']; ?>">Delete</a>
</div>

</div>

<?php } ?>
</div>

<!-- EDIT POPUP -->
<?php
if(isset($_GET['edit'])){
    $eid=(int)$_GET['edit'];
    $eq=mysqli_query($conn,"SELECT * FROM announcements WHERE id=$eid");
    $ed=mysqli_fetch_assoc($eq);
?>
<div class="container">
<div class="card">
<h2>✏️ Edit Announcement</h2>

<form method="post">
<input type="hidden" name="id" value="<?php echo $ed['id']; ?>">
<input name="title" value="<?php echo htmlspecialchars($ed['title']); ?>" required>
<textarea name="msg" required><?php echo htmlspecialchars($ed['message']); ?></textarea>
<button class="btn btn-save" name="update">Update</button>
</form>
</div>
</div>
<?php } ?>

</div>
</body>
</html>