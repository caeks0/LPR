		<script src="js/menu.js"	language="javascript" type="text/JavaScript"></script>
		<link rel="stylesheet"	type="text/css" href="template.css" media="screen" />
		<link rel="stylesheet"	type="text/css" href="menu.css" />
		
		
</head>
	<body>
		<div id="top">
			<?php include("menu.php"); 
			if (isset($_SESSION['first_name'])) {
			$name = $_SESSION['first_name'];
			echo"<div id='logged_in'><p align='right'>Logged in as : ". $name ."</p></div>";
			}
			?>
		</div>
		
