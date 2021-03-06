<?php
//set debug on/off
$debug = 0;

//Get my connects
require ("connect.php");
require ("passwords.php");

//Set time, just incase its not done server side
date_default_timezone_set('Australia/Perth');

//Get and decode JSON
$json = file_get_contents('php://input');
$jsonarray = json_decode($json, true);

//Define variables
if($debug==1){
$site_id = "site id";
$uuid = "uuid";
$unix_timestamp = "1478398100000";
$camera_id = "camera id";
$plate_number = "1CPW612";
$confidence = "confidence";
}
else{
$site_id = $jsonarray["site_id"];
$uuid = $jsonarray["uuid"];
$unix_timestamp = $jsonarray["epoch_time"];
$camera_id = $jsonarray["camera_id"];
$plate_number = $jsonarray["results"][0]["plate"];
$confidence = $jsonarray["results"][0]["confidence"];
$pattern_match = $jsonarray["results"][0]["matches_template"];
}


//Alert settings
$alert_checking = 0;
$email_alerts = 1;

//check for duplicates
$plate_existance_query = mysqli_query($conn, "SELECT * FROM tbl_plates WHERE plate_number='".$plate_number."' ORDER BY unix_timestamp DESC LIMIT 1");
$plate_existance = mysqli_fetch_assoc($plate_existance_query);
$existing_plate_timestamp = $plate_existance['unix_timestamp'];
$timestamp_difference = ($unix_timestamp - $existing_plate_timestamp);
$duplicate_plate_flag=0;

if($timestamp_difference < 15000)
{
	$duplicate_plate_flag=1;
	echo "Duplicate plate | $plate_number | Flag : $duplicate_plate_flag | ";
}
echo "\033[31m Unix timestamp = $unix_timestamp | Existing timestamp = $existing_plate_timestamp | Difference = $timestamp_difference | Flag = $duplicate_plate_flag | \033[0m ";

//END Duplicate checks



//Get snapshot from wide FOV camera 1
if($duplicate_plate_flag==0)
{
	$content = file_get_contents("http://admin:$hik1@192.168.1.108/Streaming/channels/1/picture");
	//Store in the filesystem.
	$fp = fopen("HIK1-$uuid.jpg", "w");
	fwrite($fp, $content);
	fclose($fp);
}
if($duplicate_plate_flag==0)
{
	//Get snapshot from wide FOV camera 2
	$content = file_get_contents("http://admin:$hik2@192.168.1.106/Streaming/channels/1/picture");
	//Store in the filesystem.
	$fp = fopen("HIK2-$uuid.jpg", "w");
	fwrite($fp, $content);
	fclose($fp);
}




//do some pattern matching



///////////////////////

//check if plate is in vehicles database and add it if it does not
 $vehicle_existance_query = mysqli_query($conn, "SELECT * FROM tbl_vehicles WHERE plate_number='".$plate_number."' LIMIT 1");
 echo mysqli_num_rows($vehicle_existance_query);
if(mysqli_num_rows($vehicle_existance_query)==0)
{
	$add_vehicle_query = "INSERT INTO tbl_vehicles (plate_number, matches_pattern, date_added, camera_id, site_id) VALUES ('$plate_number','$pattern_match','$unix_timestamp','camera_id','$site_id')";
	if ($conn->query($add_vehicle_query) === TRUE) 
	{
    	echo "\033[36m Vehicle has not been seen before! Added \033[32m $plate_number\033[36m to database \033[0m | ";
	} 
	else 
	{
   		echo "Error: " . $add_vehicle_query . "<br>" . $conn->error;
	}

	$insert = "INSERT INTO tbl_plates 
			(site_id, 
				uuid,
					unix_timestamp,
						camera_id,
							plate_number,
								confidence,
									matches_pattern)
		VALUES 
			('$site_id',
				'$uuid',
					'$unix_timestamp',
						'$camera_id',
							'$plate_number',
								'$confidence',
									'$pattern_match')";

	if ($conn->query($insert) === TRUE) 
	{
    	echo "\033[36mPlate \033[32m $plate_number\033[36m added\033[0m | ";
	}
 	else 
 	{
    	echo "Error: " . $insert . "<br>" . $conn->error;
	}	
}
else //vehicle already exists, just record the detection to plates database
{
	
	//Mutiple capture prevention, Check if plate has been added in the last 30 seconds
	if($duplicate_plate_flag==0)
	{
		$insert = "INSERT INTO tbl_plates 
			(site_id, 
				uuid,
					unix_timestamp,
						camera_id,
							plate_number,
								confidence,
									matches_pattern)
		VALUES 
			('$site_id',
				'$uuid',
					'$unix_timestamp',
						'$camera_id',
							'$plate_number',
								'$confidence',
									'$pattern_match')";

		if ($conn->query($insert) === TRUE) 
		{
			echo "\033[35mVehicle \033[32m $plate_number\033[35m has been seen before, Plate added | \033[0m";
		}
	 	else 
	 	{
	    	echo "Error: " . $insert . "<br>" . $conn->error;
		}	
	}
	else
	{
		echo  "\033[36mVehicle \033[32m $plate_number\033[36m has been seen in the last 15 seconds, Plate not added | \033[0m";
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

