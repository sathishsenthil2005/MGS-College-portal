<?php
include("../config/db.php");

if(!isset($_GET['id'])){
die("Invalid Request");
}

$id = $_GET['id'];

$q = mysqli_query($conn,"SELECT * FROM admissions WHERE id=$id");

if(mysqli_num_rows($q)==0){
die("No Data Found");
}

$data = mysqli_fetch_assoc($q);

// File paths
$photo = "../uploads/".$data['photo'];
$tenth = "../uploads/".$data['tenth_file'];
$twelfth = "../uploads/".$data['twelfth_file'];
$community = "../uploads/".$data['community_file'];

// check image
function isImage($file){
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
return in_array($ext,['jpg','jpeg','png','gif','webp']);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>View Student</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
font-family:Segoe UI;
background:#f1f5f9;
margin:0;
padding:15px;
}

.card{
background:#fff;
padding:20px;
border-radius:12px;
max-width:700px;
margin:auto;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
text-align:center;
color:#2563eb;
margin-bottom:10px;
}

h3{
color:#374151;
margin-top:15px;
}

p{
font-size:14px;
margin:5px 0;
}

img{
display:block;
margin:10px auto;
border-radius:8px;
border:1px solid #ccc;
}

.file-box{
background:#f9fafb;
padding:10px;
margin-top:8px;
border-radius:8px;
text-align:center;
}

.btn{
display:inline-block;
padding:6px 10px;
background:#2563eb;
color:#fff;
border-radius:6px;
text-decoration:none;
font-size:13px;
}

.back{
display:inline-block;
margin-top:15px;
padding:8px 12px;
background:#111827;
color:#fff;
border-radius:6px;
text-decoration:none;
}

.status{
font-weight:bold;
}

.pending{color:orange;}
.approved{color:green;}

hr{
margin:15px 0;
}

</style>

</head>

<body>

<div class="card">

<h2>🎓 Student Details</h2>

<p><b>Application No:</b> <?php echo $data['application_no']; ?></p>
<p><b>Name:</b> <?php echo $data['name']; ?></p>
<p><b>DOB:</b> <?php echo $data['dob']; ?></p>
<p><b>Gender:</b> <?php echo $data['gender']; ?></p>
<p><b>Phone:</b> <?php echo $data['phone']; ?></p>
<p><b>Email:</b> <?php echo $data['email']; ?></p>
<p><b>Address:</b> <?php echo $data['address']; ?></p>
<p><b>Parent:</b> <?php echo $data['parent_name']; ?></p>

<p><b>Course:</b> <?php echo $data['course_type']; ?></p>
<p><b>Department:</b> <?php echo $data['department']; ?></p>
<p><b>Community:</b> <?php echo $data['community']; ?></p>

<p><b>10th Mark:</b> <?php echo $data['tenth_mark']; ?></p>
<p><b>12th Mark:</b> <?php echo $data['twelfth_mark']; ?></p>

<p>
<b>Status:</b> 
<span class="status <?php echo $data['status']; ?>">
<?php echo ucfirst($data['status']); ?>
</span>
</p>

<hr>

<!-- PHOTO -->
<h3>📷 Photo</h3>

<div class="file-box">
<?php if(!empty($data['photo']) && file_exists($photo)){ ?>
<img src="<?php echo $photo; ?>" width="150">
<?php } else { echo "❌ Photo not found"; } ?>
</div>

<hr>

<h3>📄 10th Marksheet</h3>

<div class="file-box">
<?php if(!empty($data['tenth_file'])){ ?>

<?php if(isImage($tenth)){ ?>
<img src="<?php echo $tenth; ?>" width="200">
<?php } else { ?>
<a class="btn" href="<?php echo $tenth; ?>" target="_blank">View / Download</a>
<?php } ?>

<?php } else { echo "❌ File not uploaded"; } ?>
</div>

<hr>

<h3>📄 12th Marksheet</h3>

<div class="file-box">
<?php if(!empty($data['twelfth_file'])){ ?>

<?php if(isImage($twelfth)){ ?>
<img src="<?php echo $twelfth; ?>" width="200">
<?php } else { ?>
<a class="btn" href="<?php echo $twelfth; ?>" target="_blank">View / Download</a>
<?php } ?>

<?php } else { echo "❌ File not uploaded"; } ?>
</div>
<hr>

<h3>📄 Community Certificate</h3>

<div class="file-box">
<?php if(!empty($data['community_file'])){ ?>

<?php if(isImage($community)){ ?>
<img src="<?php echo $community; ?>" width="200">
<?php } else { ?>
<a class="btn" href="<?php echo $community; ?>" target="_blank">View / Download</a>
<?php } ?>

<?php } else { echo "❌ File not uploaded"; } ?>
</div>

<br>

<a class="back" href="index.php">⬅ Back</a>

</div>

</body>
</html>