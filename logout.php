<?php
session_start();
session_destroy();

echo "You are now logged out.  	Redirecting..........";
echo '<meta http-equiv="refresh" content="1;url=login.php">';
?>