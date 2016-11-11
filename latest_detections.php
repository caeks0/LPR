<?php
session_start();
if (isset($_SESSION['logged_in']))
{
?>
<?php include("template_header.php"); ?>
		<title>
			View Latest Detections
		</title>
<?php include("template_menu.php"); ?>
		<div id="content">
<?php
//set debug on/off
$debug = 0;

//Get my connects
require ("functions/connect.php");


echo "
<div id='left'>
Showing the latest 100 Detections
</div>
";
//Generate results table
$plates = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE matches_pattern=1 ORDER BY unix_timestamp DESC LIMIT 100");
date_default_timezone_set('Australia/Perth');
?>
<div id='right'>
<table border='1' class='plate_table'>
<tr>

<th><Center>Plate ID</th>
<th><Center>Timestamp</th>
<th><Center>Plate Number</th>
<th><Center>Confidence</th>
<th><Center>Vehicle detections</th>
<th><Center>Image</th>
<th><Center>View</th>

</tr>


<?php

while($result = mysqli_fetch_assoc($plates))
  {
echo "<font size=72><tr height=30>";
echo "<td width=50>" . $result['plate_id'] . "</td>";

$result_timestamp = $result['unix_timestamp'];
$result_platenumber = $result['plate_number'];
$result_confidence = $result['confidence'];
$result_uuid = $result['uuid'];

$converted_date = date('d-m-Y H:i:s', $result_timestamp/1000);  

$plates_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$result_platenumber'");

echo "<td>$converted_date</td>";
echo "<td width=200>$result_platenumber</td>";
echo "<td width=150>$result_confidence</td>";
echo "<td><center>".mysqli_num_rows($plates_query)."</center></td>";
echo "<td><a href=plate_images_link/$result_uuid.jpg><img src='plate_images_link/$result_uuid.jpg' width ='200' height='100'></a></td>";
 echo "<td> <a href='../LPR/view_vehicle.php?plt=$result_platenumber'>View</a></td>";
echo "</tr>";
  }
echo "</font></table></div>";

?>
</div>
<?php include("template_footer.php");
}
else 
{
echo "You are not logged in.  	Redirecting..........";
echo '<meta http-equiv="refresh" content="1;url=login.php">';
}
?>


