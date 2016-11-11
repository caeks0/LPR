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

//set timezone
date_default_timezone_set('Australia/Perth');

//All the querys
//Find most common car
$most_common_plate_query = mysqli_query($conn, "select plate_number, count(plate_number) c from tbl_plates where matches_pattern=1 group by plate_number order by c desc limit 1");
$most_common_plate_query_result = mysqli_fetch_assoc($most_common_plate_query);
$most_common_plate = $most_common_plate_query_result['plate_number'];
$most_common_plate_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$most_common_plate'");
$most_common_plate_times_detected = mysqli_num_rows($most_common_plate_query);

//find all unique detections made ever
$vehicle_query = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE matches_pattern='1'");
$number_of_detected_vehicles_unique = mysqli_num_rows($vehicle_query);

//find all detections made today
$detections_today_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$most_common_plate'");

//find all detections made ever
$total_plate_detections_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE matches_pattern='1'");
$number_of_detected_vehicles = mysqli_num_rows($total_plate_detections_query);

//find the very first date of detection (when the system started collecting data)
$date_detection_started_query = mysqli_query($conn,"SELECT unix_timestamp FROM tbl_plates WHERE plate_id='2'");
$date_detection_started_result =  mysqli_fetch_assoc($date_detection_started_query);
$date_detection_started_timestamp = $date_detection_started_result['unix_timestamp'];
$date_detection_started = date('d-m-Y', $date_detection_started_timestamp/1000);

//define midnight as epoch time 
$midnight_seconds =  strtotime("00:00");
$midnight_milliseconds = ($midnight_seconds . "000");

//find new vehicles detected today
$todays_vehicles_new = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE date_added >= $midnight_milliseconds && matches_pattern=1");
$today_detected_vehicles_new_amount = mysqli_num_rows($todays_vehicles_new);

//find vehicles detected today
$todays_vehicles= mysqli_query($conn,"SELECT * FROM tbl_plates WHERE unix_timestamp >= $midnight_milliseconds && matches_pattern=1");
$today_detected_vehicles_amount = mysqli_num_rows($todays_vehicles);

//define yesterday midnight as epoch time 
$midnight_seconds =  strtotime("00:00");
$midnight_milliseconds = ($midnight_seconds . "000");
$yesterday_midnight = $midnight_milliseconds-86400000;

//find new vehicles detected yesterday
$since_yesteday_vehicles_new = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE date_added >= $yesterday_midnight  && matches_pattern=1");
$since_yesterday_detected_vehicles_new_amount = mysqli_num_rows($since_yesteday_vehicles_new);
$yesterday_detected_vehicles_new_amount = $since_yesterday_detected_vehicles_new_amount - $today_detected_vehicles_new_amount;

//find vehicles detected yesterday
$since_yesteday_vehicles= mysqli_query($conn,"SELECT * FROM tbl_plates WHERE unix_timestamp >= $yesterday_midnight && matches_pattern=1");
$since_yesterday_detected_vehicles_amount = mysqli_num_rows($since_yesteday_vehicles);
$yesterday_detected_vehicles_amount = $since_yesterday_detected_vehicles_amount - $today_detected_vehicles_amount;

//define 7 days ago as epoch time 
$seven_days_ago_midnight = ($midnight_milliseconds-604800000);

//find new vehicles detected within last 7 days
$last_7days_vehicles_new = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE date_added >= $seven_days_ago_midnight  && matches_pattern=1");
$last_7days_vehicles_new_amount = mysqli_num_rows($last_7days_vehicles_new);

//find vehicles detected within last 7 days
$last_7days_vehicles = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE unix_timestamp >= $seven_days_ago_midnight  && matches_pattern=1");
$last_7days_vehicles_amount = mysqli_num_rows($last_7days_vehicles);

//define 30 days ago as epoch time 
$thirty_days_ago_midnight = ($midnight_milliseconds-2592000000);

//find new vehicles detected within last 30 days
$last_30days_vehicles_new = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE date_added >= $thirty_days_ago_midnight  && matches_pattern=1");
$last_30days_vehicles_new_amount = mysqli_num_rows($last_30days_vehicles_new);

//find vehicles detected within last 30 days
$last_30days_vehicles = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE unix_timestamp >= $thirty_days_ago_midnight  && matches_pattern=1");
$last_30days_vehicles_amount = mysqli_num_rows($last_30days_vehicles);


//echo "<br>".$converted_date;
//stats table
echo "<div id='left'>
<b>All statistics have a 10% error margin</b>
<table border='1'>
<tr>
<th><Center>No of Detections</th>
<th><Center>Today</th>	
<th><Center>Yesterday</th>	
<th><Center>Last 7 days</th>
<th><Center>Last 30 days</th>	
<th><Center>This year</th>	
<th><Center>Ever<br>$date_detection_started</th>	
</tr>
<tr>
<th>
<Center>New Vehicles</center>
</th>
<td>$today_detected_vehicles_new_amount</td>
<td>$yesterday_detected_vehicles_new_amount</td>
<td>$last_7days_vehicles_new_amount</td>
<td>$last_30days_vehicles_new_amount</td>
<td>this year</td>
<td>$number_of_detected_vehicles_unique</td>
</tr>
<tr>
<th>
<Center>All Vehicles</center>
</th>
<td>$today_detected_vehicles_amount</td>
<td>$yesterday_detected_vehicles_amount</td>
<td>$last_7days_vehicles_amount</td>
<td>$last_30days_vehicles_amount</td>
<td>this year</td>
<td>$number_of_detected_vehicles </td>
</tr>


</table>


";

echo "<br>Most detected plate =<a href=view_vehicle.php?plt=$most_common_plate>$most_common_plate</a> with a total of <b>$most_common_plate_times_detected</b> detections";
echo "</div>";

//Generate results table
$plates = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE matches_pattern=1 ORDER BY unix_timestamp DESC LIMIT 10");

echo "<div id='right'><table border='1' class='plate_table'>
<tr>

<th><Center>Plate ID</th>
<th><Center>Timestamp</th>
<th><Center>Plate Number</th>
<th><Center>Confidence</th>
<th><Center>Vehicle detections</th>
<th><Center>Image</th>
<th><Center>View</th>

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

$loop_plates_query = mysqli_query($conn,"SELECT * FROM tbl_plates WHERE plate_number='$result_platenumber'");

echo "<td>$converted_date<td>";
echo "<td width=200>$result_platenumber</td>";
echo "<td width=150>$result_confidence</td>";
echo "<td><center>".mysqli_num_rows($loop_plates_query)."</center></td>";
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


