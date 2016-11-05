<?php
session_start();
if (isset($_SESSION['logged_in']))
{
?>
<?php include("template_header.php"); ?>
		<title>
			Template :D
		</title>
<?php include("template_menu.php"); ?>
		<div id="content">
<?php
//set debug on/off
$debug = 0;

//Get my connects
require ("functions/connect.php");

//Generate results table
$plates = mysqli_query($conn,"SELECT * FROM tbl_plates LIMIT 10");

echo "<table border='1'>
<tr>

<th><Center>Plate ID</th>
<th><Center>Timestamp</th>
<th><Center>Plate Number</th>
<th><Center>Confidence</th>
<th><Center>Image</th>

</tr>";

while($result = mysqli_fetch_assoc($plates))
  {
echo "<font size=72><tr height=30>";
echo "<td width=50>" . $result['plate_id'] . "</td>";
 
$result_timestamp = $result['unix_timestamp'];
$result_platenumber = $result['plate_number'];
$result_confidence = $result['confidence'];
$result_uuid = $result['uuid'];

$converted_date = date('d-m-Y H:i:s', $result_timestamp/1000);    


echo "<td>$converted_date</td>";
echo "<td width=200>$result_platenumber</td>";
echo "<td width=150>$result_confidence</td>";
echo "<td><a href=plate_images/$result_uuid.jpg><img src='plate_images/$result_uuid.jpg' width ='200' height='100'></a></td>";
 
echo "</tr>";
  }
echo "</font></table>";

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


