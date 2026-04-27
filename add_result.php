<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

$msg = "";

/* ================= FETCH DEPARTMENTS ================= */
$deptQ = mysqli_query($conn,"SELECT DISTINCT department FROM students");

/* ================= DELETE ================= */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn,"DELETE FROM results WHERE id='$id'");
    $msg = "🗑 Result deleted";
}

/* ================= EDIT FETCH ================= */
$editData = null;
if(isset($_GET['edit'])){
    $eid = (int)$_GET['edit'];
    $editQ = mysqli_query($conn,"SELECT * FROM results WHERE id='$eid'");
    $editData = mysqli_fetch_assoc($editQ);
}

/* ================= ADD RESULT ================= */
if(isset($_POST['add'])){

    $student_id = (int)$_POST['student_id'];
    $subject    = mysqli_real_escape_string($conn,$_POST['subject']);
    $marks      = (int)$_POST['marks'];
    $grade      = mysqli_real_escape_string($conn,$_POST['grade']);
    $exam_date  = $_POST['exam_date'];

    mysqli_query($conn,"
        INSERT INTO results(student_id,subject,marks,grade,exam_date)
        VALUES('$student_id','$subject','$marks','$grade','$exam_date')
    ");

    $msg="✅ Result added successfully";
}

/* ================= UPDATE ================= */
if(isset($_POST['update'])){

    $id         = (int)$_POST['id'];
    $student_id = (int)$_POST['student_id'];
    $subject    = mysqli_real_escape_string($conn,$_POST['subject']);
    $marks      = (int)$_POST['marks'];
    $grade      = mysqli_real_escape_string($conn,$_POST['grade']);
    $exam_date  = $_POST['exam_date'];

    mysqli_query($conn,"
        UPDATE results SET
        student_id='$student_id',
        subject='$subject',
        marks='$marks',
        grade='$grade',
        exam_date='$exam_date'
        WHERE id='$id'
    ");

    $msg="✅ Result updated successfully";
    $editData=null;
}

/* ================= RESULTS LIST ================= */
$list = mysqli_query($conn,"
    SELECT r.*, s.name
    FROM results r
    JOIN students s ON s.id=r.student_id
    ORDER BY r.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Results Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
.container{margin-left:230px;padding:25px;font-family:Arial}
form{
    background:#fff;
    padding:20px;
    border-radius:12px;
    width:420px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}
input,select,button{
    width:100%;
    padding:10px;
    margin:8px 0;
}
button{
    background:#1976d2;
    color:#fff;
    border:none;
    cursor:pointer;
}
button:hover{background:#125ea7;}
.msg{color:green;font-weight:bold;margin-bottom:10px;}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
    background:#fff;
}
th,td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}
th{background:#1976d2;color:#fff;}

.action a{
    padding:5px 10px;
    border-radius:6px;
    text-decoration:none;
    color:#fff;
    font-size:13px;
}
.edit{background:#ffc107;color:#000;}
.del{background:#dc3545;}

@media(max-width:768px){
    .container{margin-left:0}
    form{width:100%}
}
</style>
</head>
<body>

<?php include("dashboard.php"); ?>

<div class="container">
<h2>📊 Results Management</h2>

<?php if($msg!="") echo "<div class='msg'>$msg</div>"; ?>

<!-- ================= FORM ================= -->
<form method="post">

<select name="department" id="department" required>
<option value="">Select Department</option>
<?php
mysqli_data_seek($deptQ,0);
while($d=mysqli_fetch_assoc($deptQ)){ ?>
<option value="<?php echo $d['department']; ?>">
<?php echo $d['department']; ?>
</option>
<?php } ?>
</select>

<select name="student_id" id="student_id" required>
<option value="">Select Student</option>
</select>

<input type="text" name="subject" placeholder="Subject"
value="<?php echo $editData['subject'] ?? ''; ?>" required>

<input type="number" name="marks" placeholder="Marks"
value="<?php echo $editData['marks'] ?? ''; ?>" required>

<input type="text" name="grade" placeholder="Grade"
value="<?php echo $editData['grade'] ?? ''; ?>" required>

<input type="date" name="exam_date"
value="<?php echo $editData['exam_date'] ?? ''; ?>" required>

<?php if($editData){ ?>
<input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
<button name="update">Update Result</button>
<?php } else { ?>
<button name="add">Send Result</button>
<?php } ?>

</form>

<!-- ================= TABLE ================= -->
<table>
<tr>
<th>Student</th>
<th>Subject</th>
<th>Marks</th>
<th>Grade</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($r=mysqli_fetch_assoc($list)){ ?>
<tr>
<td><?php echo htmlspecialchars($r['name']); ?></td>
<td><?php echo htmlspecialchars($r['subject']); ?></td>
<td><?php echo htmlspecialchars($r['marks']); ?></td>
<td><?php echo htmlspecialchars($r['grade']); ?></td>
<td><?php echo htmlspecialchars($r['exam_date']); ?></td>
<td class="action">
<a class="edit" href="?edit=<?php echo $r['id']; ?>">Edit</a>
<a class="del" onclick="return confirm('Delete result?')"
href="?delete=<?php echo $r['id']; ?>">Delete</a>
</td>
</tr>
<?php } ?>
</table>

</div>

<script>
document.getElementById("department").addEventListener("change", function(){

    let dept = this.value;
    let stuDropdown = document.getElementById("student_id");

    stuDropdown.innerHTML = "<option>Loading...</option>";

    fetch("get_student_by_dept.php?department=" + dept)
    .then(res => res.text())
    .then(data => {
        stuDropdown.innerHTML = data;
    });
});
</script>

</body>
</html>