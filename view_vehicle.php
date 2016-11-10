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
$plates_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$plate_number' ORDER BY unix_timestamp DESC");
$vehicle_query_result = mysqli_fetch_assoc($vehicle_query);


$wide_FOV_1_prefix = "HIK1-";
$wide_FOV_2_prefix = "HIK2-";


$owner_firstname = $vehicle_query_result['owner_firstname'];
$owner_lastname = $vehicle_query_result['owner_lastname'];
$vehicle_make = $vehicle_query_result['vehicle_make'];
$vehicle_model = $vehicle_query_result['vehicle_model'];
$vehicle_color = $vehicle_query_result['vehicle_color'];
$vehicle_notes = $vehicle_query_result['vehicle_notes'];
$vehicle_alert_flag = $vehicle_query_result['alert_flag'];

date_default_timezone_set('Australia/Perth');

echo"
<div id='left'>
<br>Vehicle has been detected a total of: ".mysqli_num_rows($plates_query)." times<br>
Showing details for vehicle with plate number : <b>$plate_number</b>
";


echo "
<form action='functions/process_vehicle_edit.php' method='post'>
<table border='1'>
<tr>
<th><b>Owner firstname</b></th>
<td>$owner_firstname</td>
<td>Change to <input type='text' name='owner_firstname'></input></td>
</tr>
<tr>
<th><b>Owner lastname</b></th>
<td>$owner_lastname</td>
<td>Change to <input type='text' name='owner_lastname'></input></td>
</tr>
<tr>
<th><b>Vehicle Make</b></th>
<td>$vehicle_make</td>
<td>Change to <input type='text' name='vehicle_make'></input></td>
<tr>
<tr>
<th><b>Vehicle Model</b></th>
<td>$vehicle_model </td>
<td>Change to <input type='text' name='vehicle_model'></input></td>
<tr>
<th><b>Vehicle Color</b></th>
<td>$vehicle_color</td>
<td>Change to <input type='text' name='vehicle_color'></input></td>
</td>
<tr>
<th><b>Vehicle notes</b></th>
<td>$vehicle_notes</td>
<td>Change to <input type='text' name='vehicle_notes'></input></td>
</td>
<tr>
<th><b>Detection Alert</b></th>
<td>$vehicle_alert_flag</td>
<td>Change to <font color='#00ff00'>YES</font><input name='flag_checkbox_yes' type='checkbox' value='1'><br>
	<font color='#ff0000'>NO</font><input name='flag_checkbox_no' type='checkbox' value='0'></td><br>
</td>
</tr>
</table>
<input type='hidden' name='post_plate_number' value='$plate_number' />
<input type='submit' value='Update'>
</form>






</div>

<div id='right'>
<tr>
<table border='2''>

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
echo "</table></div>"
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