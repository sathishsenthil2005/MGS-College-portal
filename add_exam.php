<?php
session_start();
include("../config/db.php");

$msg="";

if(isset($_POST['save'])){

$department=mysqli_real_escape_string($conn,$_POST['department']);
$subject=mysqli_real_escape_string($conn,$_POST['subject']);
$date=$_POST['exam_date'];
$time=$_POST['exam_time'];

/* duplicate check */

$chk=mysqli_query($conn,"
SELECT * FROM exam_schedule
WHERE department='$department'
AND subject='$subject'
");

if(mysqli_num_rows($chk)>0){

$msg="Subject already scheduled";

}else{

mysqli_query($conn,"
INSERT INTO exam_schedule
(department,subject,exam_date,exam_time)
VALUES
('$department','$subject','$date','$time')
");

$msg="Exam Schedule Added Successfully";

}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Exam Schedule Manager</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
font-family:Arial;
background:linear-gradient(135deg,#3b82f6,#1e3a8a);
margin:0;
padding:30px;
}

.box{
max-width:520px;
margin:auto;
background:#fff;
padding:30px;
border-radius:12px;
box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

h2{
text-align:center;
color:#2563eb;
margin-bottom:25px;
}

label{
font-weight:bold;
}

select,input{
width:100%;
padding:10px;
margin-top:6px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:6px;
}

button{
width:100%;
padding:12px;
background:#2563eb;
color:white;
border:none;
border-radius:8px;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#1e40af;
}

.msg{
text-align:center;
color:green;
margin-bottom:15px;
font-weight:bold;
}

.back{
display:block;
margin-top:15px;
text-align:center;
text-decoration:none;
background:#374151;
color:white;
padding:10px;
border-radius:6px;
}

.back:hover{
background:#111827;
}

</style>

<script>

function loadSubjects(){

var dept=document.getElementById("department").value;

var xhr=new XMLHttpRequest();

xhr.open("GET","get_subjects.php?dept="+dept,true);

xhr.onload=function(){

document.getElementById("subject").innerHTML=this.responseText;

}

xhr.send();

}

</script>

</head>

<body>

<div class="box">

<h2>📅 Add Exam Schedule</h2>

<?php if($msg!=""){ ?>
<div class="msg"><?php echo $msg; ?></div>
<?php } ?>

<form method="post">

<label>Department</label>

<select name="department" id="department" onchange="loadSubjects()" required>

<option value="">Select Department</option>
<option>BA.Tamil</option>
<option>BA.English</option>
<option>B.Sc Computer Science</option>
<option>BCA</option>
<option>B.Com</option>
<option>BBA</option>
<option>B.Sc Mathematics</option>
<option>B.Sc Physics</option>

</select>


<label>Subject</label>

<select name="subject" id="subject" required>

<option>Select Department First</option>

</select>


<label>Exam Date</label>

<input type="date" name="exam_date" required>


<label>Exam Time</label>

<input type="text" name="exam_time" placeholder="10:00 AM - 1:00 PM" required>


<button type="submit" name="save">
Save Exam Schedule
</button>

</form>

<a class="back" href="dashboard.php">
⬅ Back to Dashboard
</a>

</div>

</body>
</html>