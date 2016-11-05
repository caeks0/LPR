<?php
session_start();
require ("connect.php");
require ("ceb20772e0c9d240c75eb26b0e37abee.php");
//set debug on/off
$debug = 0;

//post data from input page and escape
$pre_escape_user_login_name = $_POST["user_login_name"];	
$pre_escape_password = $_POST["user_password"];

$login_name = mysqli_real_escape_string($conn, $pre_escape_user_login_name);
$password = mysqli_real_escape_string($conn, $pre_escape_password);

//get users row
$get_login_details_query = mysqli_query($conn, "SELECT * from tbl_users WHERE login_name='".$login_name."' LIMIT 1")or die(mysqli_error($conn));
$get_login_details = mysqli_fetch_assoc($get_login_details_query);

//convert password to hash for comparison
$md5_password = md5(sha1($ceb20772e0c9d240c75eb26b0e37abee.$password));

//create flags
if($get_login_details['login_name']==$login_name){
	$name_flag=1;
}
else{
	$name_flag=0;
}
if($get_login_details['password']==$md5_password){
	$pass_flag=1;
}
else{
	$pass_flag=0;
}
if($get_login_details['active']==1){
	$active_flag=1;
}
else{
	$active_flag=0;
}

//flag check and session creation
if ($name_flag == 1 && $pass_flag == 1 && $active_flag == 1){
$_SESSION['id'] = $get_login_details['id'];
$_SESSION['first_name'] = $get_login_details['firstname'];
$_SESSION['last_name'] = $get_login_details['lastname'];
$_SESSION['user_permissions'] = $get_login_details['permissions'];
$_SESSION['logged_in'] = 1; 
echo "<font color='#00b300'>Logged in! Redirecting..........</font>";
echo '<meta http-equiv="refresh" content="1; url=../index.php">';
}
else{
echo "<font color='#e60000'>Login details incorrect</font>";
echo '<meta http-equiv="refresh" content="1; url=../login.php">';		
}

//debug
if($debug==1){
echo "database login name : " .$get_login_details['login_name']."<br>";
echo "database password : " .$get_login_details['password']."<br>";
echo "database active flag : " .$get_login_details['active']."<br>";
echo "Name flag = " .$name_flag. "<br>";
echo "Pass flag = " .$pass_flag. "<br>";
echo "Active flag = " .$active_flag. "<br>";

}


?>