<?php
$q=($_REQUEST["q"]);
require("connect.php");

$plates = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE owner_firstname LIKE '%$q%' order by plate_number");



//start table generation
echo "<table border='1'>
<tr>
<th>Plate number</th>
<th>Make</th>
<th>Model</th>
<th>Owner name</th>
<th>Details</th>
<th>Times Detected</th>
<th>View</th>
</tr>";

//loop to generate table results
//change the str length check to determine how many characters need to be entered to initiate search
while($result = mysqli_fetch_assoc($plates))
{
$plate_number=$result['plate_number'];	
$plates_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$plate_number'");
if((strlen($q)>0)){
echo "<tr>";
echo "<td>" . $result['plate_number'] . "</td>";
echo "<td>" . $result['vehicle_make'] . "</td>";
echo "<td>" . $result['vehicle_model'] . "</td>";
echo "<td>" . $result['owner_firstname']." ".$result['owner_lastname'] . "</td>";
echo "<td>" . $result['vehicle_notes'] . "</td>";
echo "<td>" . mysqli_num_rows($plates_query)  . "</td>";
echo "<td><a href='../LPR/view_vehicle.php?plt=$plate_number	'>View</a></td>";

echo "</tr>";
}
}
echo "</table>";

require("disconnect.php");
?>