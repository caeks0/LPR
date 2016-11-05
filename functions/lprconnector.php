<?php
//set debug on/off
$debug = 0;

//Get my connects
require ("connect.php");

//Get and decode JSON
$json = file_get_contents('php://input');
$jsonarray = json_decode($json, true);

//Define variables
if($debug==1){
$site_id = "site id";
$uuid = "uuid";
$unix_timestamp = "timestamp";
$camera_id = "camera id";
$plate_number = "1HELGYHELG";
$confidence = "confidence";
}
else{
$site_id = $jsonarray["site_id"];
$uuid = $jsonarray["uuid"];
$unix_timestamp = $jsonarray["epoch_time"];
$camera_id = $jsonarray["camera_id"];
$plate_number = $jsonarray["results"][0]["plate"];
$confidence = $jsonarray["results"][0]["confidence"];
}

//Alert settings
$alert_checking = 0;
$email_alerts = 1;

//define functions
function addplate()
{

	if ($debug == 0)
	{
		$insert = "INSERT INTO tbl_plates 
				(site_id, 
					uuid,
						unix_timestamp,
							camera_id,
								plate_number,
									confidence)
			VALUES 
				('$site_id',
					'$uuid',
						'$unix_timestamp',
							'$camera_id',
								'$plate_number',
									'$confidence')";

		if ($conn->query($insert) === TRUE) 
		{
    		echo "Plate added";
		}
 		else 
 		{
    		echo "Error: " . $insert . "<br>" . $conn->error;
		}	
	}
}


//do some pattern matching



///////////////////////

//check if plate is in vehicles database and add it if it does not
 $vehicle_existance_query = mysqli_query($conn, "SELECT * FROM tbl_vehicles WHERE plate_number='".$plate_number."' LIMIT 1");
if(mysqli_num_rows($vehicle_existance_query)==0)
{
	$add_vehicle_query = "INSERT INTO tbl_vehicles (plate_number) VALUES ('$plate_number')";
	if ($conn->query($add_vehicle_query) === TRUE) 
	{
    	echo "New vehicle added to database";
	} 
	else 
	{
   		echo "Error: " . $add_vehicle_query . "<br>" . $conn->error;
	}

	addplate(); //add plate to plates database


}
else //vehicle already exists, just record the detection to plates database
{
	
	//Mutiple capture prevention, Check if plate has been added in the last 30 seconds
	$plate_existance_query = mysqli_query($conn, "SELECT * FROM tbl_plates WHERE plate_number='".$plate_number."' LIMIT 1");
	$plate_existance = mysqli_fetch_assoc($plate_existance_query);
	
	$existing_plat_timestamp = $plate_existance['unix_timestamp'];

	//perform the check
	if(($existing_plat_timestamp - $unix_timestamp) > 30 )
	{
		addplate(); //add plate to plates database
	}
	else
	{
		echo  "Duplicate capture - plate not added";
		//do nothing
	}
}








//check alert flag
if ($alert_checking = 1){
	$check_flags_query = mysqli_query($conn, "SELECT * FROM tbl_vehicles WHERE plate_number='".$plate_number."' && alert_flag=1 LIMIT 1");
if(mysqli_num_rows($check_flags_query) > 0){
	if ($email_alerts = 1){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, '192.168.1.17/LPR/sendmail.php?plate_number='.$plate_number.'&&unix_timestamp='.$unix_timestamp.'');
		curl_exec($ch);
		curl_close($ch);
	//echo "Sent to sendmail.php for processing...<br>";
		}
	}
}

//debug
if ($debug == 1){
echo "Plate number = $plate_number <br>";
echo "uuid = $uuid<br>";
echo "Confidence = $confidence <br>";
};

//Get my disconnects
require ("disconnect.php");
?>

