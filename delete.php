<?php
include("../config/db.php");

if(!isset($_GET['id'])){
die("Invalid Request");
}

$id = $_GET['id'];

// optional: get files to delete
$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM admissions WHERE id=$id"));

if($data){

// delete files from folder
@unlink("../uploads/".$data['photo']);
@unlink("../uploads/".$data['tenth_file']);
@unlink("../uploads/".$data['twelfth_file']);
@unlink("../uploads/".$data['community_file']);

// delete record
mysqli_query($conn,"DELETE FROM admissions WHERE id=$id");

}

header("Location: index.php");
exit;
?>