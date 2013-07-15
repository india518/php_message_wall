
<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Wall Assignment</title>
		<!-- stylesheets -->
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="css/wall.css" rel="stylesheet" type="text/css">
		<!-- javascript -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/wall.js"></script>
	</head>
	<body>
		<div class="navbar">
			<div class="navbar-inner">
				<ul class="nav">
					<li class="brand">CodingDojo Wall</li>
				</ul>
				<div class="navbar-text pull-right">Welcome <?= $_SESSION["user"]["first_name"] ?><span class="divider-vertical"></span>
			 		<a href="process.php">Log Out</a>
				</div>
			</div>
		</div>
