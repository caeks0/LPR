<?php
//set debug on/off
$debug = 0;
if($debug==1){
echo "Debugging mode enabled<br><br>";
}

//Get my connects
require ("functions/connect.php");

require_once('phpmailer/PHPMailerAutoload.php');
//get plate number from URL
$plate_number = $_GET['plate_number'];
$timestamp = $_GET['unix_timestamp'];



//get vehicle details
$vhcl_dtls_qry = mysqli_query($conn,"SELECT * FROM tbl_vehicles WHERE plate_number='".$plate_number."' LIMIT 1");
$vhcl_dtls_qry_rslt = mysqli_fetch_assoc($vhcl_dtls_qry);

//check and define vehicle owner if set
//firstname
if(isset($vhcl_dtls_qry_rslt['owner_firstname'])){
	$owner_firstname = $vhcl_dtls_qry_rslt['owner_firstname'];
	//echo "$owner_firstname";
}
//lastname
if(isset($vhcl_dtls_qry_rslt['owner_lastname'])){
	$owner_lastname = $vhcl_dtls_qry_rslt['owner_lastname'];
	//echo "$owner_lastname";	
}	
//check and define vehicle details
//make
if(isset($vhcl_dtls_qry_rslt['vehicle_make'])){
	$vehicle_make = $vhcl_dtls_qry_rslt['vehicle_make'];
	//echo "$vehicle_make";
}
//model
if(isset($vhcl_dtls_qry_rslt['vehicle_model'])){
	$vehicle_model = $vhcl_dtls_qry_rslt['vehicle_model'];
	//echo "$vehicle_model";
}
//color
if(isset($vhcl_dtls_qry_rslt['vehicle_color'])){
	$vehicle_color = $vhcl_dtls_qry_rslt['vehicle_color'];
	//echo "$vehicle_color";
}
//notes
if(isset($vhcl_dtls_qry_rslt['vehicle_notes'])){
	$vehicle_notes = $vhcl_dtls_qry_rslt['vehicle_notes'];
	//echo "$vehicle_notes";
}

//send that shit
if($debug == 0){
 $mail             = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = false;
$mail->SMTPSecure = "none";
$mail->Username   = "";
$mail->Password   = "";          
$mail->Host       = "mail.iinet.net.au";
$mail->Port       = 25;           

$mail->SetFrom('lpralerts@caeks.net', 'Caeks');
$mail->Subject    = "Flagged Plate $plate_number Detected";
$mail->Body    = "Flagged plate detected at $timestamp
Plate number : $plate_number
Vehicle owner : $owner_firstname $owner_lastname
Vehicle Make : $vehicle_make
 Vehicle Model : $vehicle_model
Vehicle Color : $vehicle_color
Vehicle Notes : $vehicle_notes";
				  

$mail->AddAddress("mjmitch1991@gmail.com", "Recipient name");

$mail->Send();   

echo "Email sent out.<br>"; 
}

//Get my disconnects
require ("functions/disconnect.php");
?>