<?php
include("../config/db.php");

$dept = $_GET['department'] ?? '';

$q = mysqli_query($conn,"
SELECT id,name,roll_no
FROM students
WHERE department='$dept'
ORDER BY name
");

if(mysqli_num_rows($q)==0){
    echo "No students found";
    exit;
}

echo "<table>";
echo "<tr>
<th>Name</th>
<th>Roll</th>
<th>Attendance</th>
</tr>";

while($r=mysqli_fetch_assoc($q)){

echo "<tr>
<td>{$r['name']}</td>
<td>{$r['roll_no']}</td>
<td>

<input type='hidden' name='status[{$r['id']}]' id='status_{$r['id']}' value='Absent'>

<button type='button' class='present'
onclick=\"markStatus({$r['id']},'Present',this)\">Present</button>

<button type='button' class='absent'
onclick=\"markStatus({$r['id']},'Absent',this)\">Absent</button>

</td>
</tr>";
}

echo "</table>";
?>