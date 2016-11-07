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


echo $vehicle_query_result['id'];
while($plates_results = mysqli_fetch_assoc($plates_query))
{
$plates_results_timestamp = $plates_results['unix_timestamp'];
$converted_date = date('d-m-Y H:i:s', $plates_results_timestamp/1000);  
$plates_results_uuid = $plates_results['uuid'];

echo "<br>".$converted_date;
echo "<td><a href=plate_images_link/$plates_results_uuid.jpg><img src='plate_images_link/$plates_results_uuid.jpg' width ='200' height='100'></a></td>";
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