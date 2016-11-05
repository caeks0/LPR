<?php 
session_start();
include("template_header.php"); 

//session check
if (isset($_SESSION['logged_in']))
$login_status = "<font color='#30bc44'>You are logged in</font>";
else
$login_status = "<font color='#ff3333'>You are not logged in</font>";
?>
<script language="javascript" type="text/JavaScript">
function validate_form ( )
{
   	$valid_login_name = false;
	$valid_password = false;
	
	$valid_login_name_check = false;
	$valid_password_check = false;
	
	$login_validation_text = "<font color='#ff3333'> Please enter a login name!</font>";
	$password_validation_text = "<font color='#ff3333'> Please enter a password!</font>";
	
			
    if ( document.form_login.user_login_name.value == "" )
    {
        $valid_login_name = false;
    }
	else
	{
		$valid_login_name= true;
	}
	
	if ( document.form_login.user_password.value == "" )
    {
        $valid_password = false;
    }
	else
	{
		$valid_password = true;
	}
		
	if($valid_login_name == true)
	{
	document.form_login.user_login_name.style.background='#FFFFFF';
	document.getElementById("login_validation_text").innerHTML="";
	$valid_login_name_check = true;
	}
	else
	{
	document.form_login.user_login_name.style.background='#ff3333';
	document.getElementById("login_validation_text").innerHTML=$login_validation_text;
	$valid_login_name_check == false;
	}
	
	if($valid_password == true)
	{
	document.form_login.user_password.style.background='#FFFFFF';
	document.getElementById("password_validation_text").innerHTML="";
	$valid_password_check = true;
	}
	else
	{
	document.form_login.user_password.style.background='#ff3333';
	document.getElementById("password_validation_text").innerHTML=$password_validation_text;
	$valid_password_check == false;
	}
			
	if($valid_login_name_check == true && $valid_password_check == true)
	{
    return true;
	}
	else 
	{
	return false;
	}
	
}
</script>
<title>
Login
</title>

<?php include("template_menu.php"); ?>

<div id="content">
<?php
echo $login_status;
?>
<p id="login_validation_text"></p>
<p id="password_validation_text"></p>
<form name="form_login" action="functions/process_login.php" method="post" onsubmit="return validate_form ( );">
Username : <input type="text" name="user_login_name">
Password : <input type="password" name="user_password">
<input type="submit" value="Submit" />



<br>
<a href="logout.php">Logout</a><br>
<a href="login.php">Login</a>
</div>

<?php include("template_footer.php"); ?>
