<?php
session_start();
if (isset($_SESSION['logged_in']))
{
?>
<?php include("template_header.php"); ?>
		<script src="js/search_vehicles_owner.js"></script>
		<title>
			Search Plates
		</title>
<?php include("template_menu.php"); ?>
		<div id="content">
		<form action="" method="post">
		<b>Start typing owner name into the box to begin searching </b><br><br>
		Search: <input type="text" name="search" id="search" onkeyup="searchUser(this.value)">
		</form>
		<br>
		<div id="searchresult" name="searchresult"> Search results ...</div>   
		</div>
<?php include("template_footer.php");
}
else 
{
echo "You are not logged in.  	Redirecting..........";
echo '<meta http-equiv="refresh" content="1;url=login.php">';
}
?>