<?php
session_start();
if (isset($_SESSION['logged_in']))
{
require("connect.php");

$plate_number = $_REQUEST['post_plate_number'];

$owner_firstname = $_REQUEST['owner_firstname'];
$owner_lastname = $_REQUEST['owner_lastname'];
$vehicle_make = $_REQUEST['vehicle_make'];
$vehicle_model = $_REQUEST['vehicle_model'];
$vehicle_color = $_REQUEST['vehicle_color'];
$vehicle_notes = $_REQUEST['vehicle_notes'];
//$vehicle_alert_flag = $_REQUEST['vehicle_alert_flag'];
//$flag_yes = $_REQUEST['flag_checkbox_yes'];
//$flag_no = $_REQUEST['flag_checkbox_no']; 


if (strlen($owner_firstname) >= 1 )
{
mysqli_query($conn, "UPDATE tbl_vehicles SET owner_firstname = '$owner_firstname' WHERE plate_number = '$plate_number'");
echo "Updated owner firstname<br>";
}

if (strlen($owner_lastname) >= 1 )
{
mysqli_query($conn, "UPDATE tbl_vehicles SET owner_lastname = '$owner_lastname' WHERE plate_number = '$plate_number'");
echo "Updated owner lastname<br>";
}

if (strlen($vehicle_make) >= 1 )
{
mysqli_query($conn, "UPDATE tbl_vehicles SET vehicle_make = '$vehicle_make' WHERE plate_number = '$plate_number'");
echo "Updated vehicle make<br>";
}

if (strlen($vehicle_model) >= 1 )
{
mysqli_query($conn, "UPDATE tbl_vehicles SET vehicle_model = '$vehicle_model' WHERE plate_number = '$plate_number'");
echo "Updated vehicle make<br>";
}


if (isset($_REQUEST['checkbox_yes']))
{
$yes = $_REQUEST['checkbox_yes'];
mysql_query("UPDATE backups SET client_sla = '$yes' WHERE backup_id = '$backup_id'");
echo "Updated Sla Status<br>";
}

if (isset($_REQUEST['checkbox_no']))
{
$no = $_REQUEST['checkbox_no'];
mysql_query("UPDATE backups SET client_sla = '$no' WHERE backup_id = '$backup_id'");
echo "Updated Sla Status<br>";
}

echo "<meta http-equiv='refresh' content='1;url=../view_vehicle.php?plt=$plate_number'>";
}
else 
{
echo "You are not logged in.  	Redirecting..........";
echo '<meta http-equiv="refresh" content="1;url=login.php">';
}
