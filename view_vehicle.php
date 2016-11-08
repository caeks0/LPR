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
require("functions/connect.php");
$plate_number= $_GET['plt'];

$vehicle_query = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE plate_number='$plate_number'");
$plates_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$plate_number'");
$vehicle_query_result = mysqli_fetch_assoc($vehicle_query);

$wide_FOV_1_prefix = "HIK1-";
$wide_FOV_2_prefix = "HIK2-";

date_default_timezone_set('Australia/Perth');
echo "Showing details for vehicle with plate number : <b>$plate_number</b>";

echo "
<tr>
<table border='2'>

<th><center>Date</center></th>
<th><center>Plate Capture</center></th>
<th><center>Wide FOV capture 1</center></th>
<th><center>Wide FOV capture 2</center></th>

</tr>
";
while($plates_results = mysqli_fetch_assoc($plates_query))
{
$plates_results_timestamp = $plates_results['unix_timestamp'];
$converted_date = date('d-m-Y H:i:s', $plates_results_timestamp/1000);  
$plates_results_uuid = $plates_results['uuid'];




echo " 
<font size=72><tr height=30>
<td>$converted_date</td>

<td><a href=plate_images_link/$plates_results_uuid.jpg><img src='plate_images_link/$plates_results_uuid.jpg' width ='200' height='100'></a></td>";


if (file_exists("functions/$wide_FOV_1_prefix$plates_results_uuid.jpg")) 
echo "<td><a href=functions/$wide_FOV_1_prefix$plates_results_uuid.jpg><img src='functions/$wide_FOV_1_prefix$plates_results_uuid.jpg' width ='200' height='100'></a></td>";
else
echo "<td>Wide FOV capture unavaliable</td>";
if (file_exists("functions/$wide_FOV_2_prefix$plates_results_uuid.jpg")) 
echo "<td><a href=functions/$wide_FOV_2_prefix$plates_results_uuid.jpg><img src='functions/$wide_FOV_2_prefix$plates_results_uuid.jpg' width ='200' height='100'></a></td>";
else
echo "<td>Wide FOV capture unavaliable</td>";

echo "</tr>";


}

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