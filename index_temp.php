<?php
	session_start();
	require("connection.php");
?>
<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<title>Login and Registration</title>
		<link rel="stylesheet" type="text/css" href="css/login_registration.css">
	</head>
	<body>
		<div id="wrapper">

			<div id="login">
				<div
				<?php
					if ( isset( $_SESSION["login_success_message"]) )
					{	?>
						class="display_messages success_border">
						<!-- printout both classes together for better readability! -->
				<?php	echo "<h3>{$_SESSION['login_success_message']}</h3>";

					}
					else if ( isset( $_SESSION["login_error_messages"]) )
					{	?>
						class="display_messages error_border">
				<?php	foreach( $_SESSION["login_error_messages"] as $message )
						{
							echo "<h3>{$message}</h3>";
						}
					}
					else //no border to display, so just put closing bracket for tag
					{	?>><?php }	?>
				</div>
				<h2>Log In to an Existing Account: </h2>
				<form id="login_form" class="form" action="process.php" method="post">
					<div>
						<label for="email">Email Address: </label>
						<input type="text" name="email" id="email" placeholder="Email Address" <?= ( isset($_SESSION["login_error_messages"]["email"]) ) ? " class='highlight'" : "" ?> />
					</div>
					<div>
						<label for="password">Password: </label>
						<input type="password" name="password" id="password" placeholder="password" <?= ( isset($_SESSION["login_error_messages"]["password"]) ) ? " class='highlight'" : "" ?> />
					</div>
					<input type="hidden" name="action" value="login" />
					<input type="submit" value="Log In" />
				</form>
			</div>

			<div id="registration">
				<div 
				<?php
					if ( isset( $_SESSION["registration_success_message"]) )
					{	?>
						class="display_messages success_border">
				<?php	echo "<h3>{$_SESSION['registration_success_message']}</h3>";

					}
					else if ( isset( $_SESSION["registration_error_messages"]) )
					{	?>
						class="display_messages error_border">
				<?php	foreach( $_SESSION["registration_error_messages"] as $message )
						{
							echo "<h3>{$message}</h3>";
						}
					}
					else //no border to display, so just put closing bracket for tag
					{	?>><?php }	?>
				</div>
				<h2>Register a New User:</h2>
				<form id="registration_form" class="form" action="process.php" method="post">
					<div>
						<label for="first_name">* First Name: </label>
						<input type="text" name="first_name" id="first_name" placeholder="First Name" <?= ( isset($_SESSION["registration_error_messages"]["first_name"]) ) ?
								" class='highlight'" : "" ?> />
					</div>
					<div>
						<label for="last_name">* Last Name: </label>
						<input type="text" name="last_name" id="last_name" placeholder="Last Name" <?= ( isset($_SESSION["registration_error_messages"]["last_name"]) ) ?
								" class='highlight'" : "" ?> />
					</div>
					<div>
						<label for="email">* Email Address: </label>
						<input type="text" name="email" id="email" placeholder="Email Address" <?= ( isset($_SESSION["registration_error_messages"]["email"]) ) ?
								" class='highlight'" : "" ?> />
					</div>
					<div>
						<label for="password">* Password: </label>
						<input type="password" name="password" id="password" placeholder="password" <?= ( isset($_SESSION["registration_error_messages"]["password"]) ) ? " class='highlight'" : ""	?> />
					</div>
					<div>
						<label for="confirm_password">* Confirm Password: </label>
						<input type="password" name="confirm_password" id="confirm_password" placeholder="confirm password" <?= ( isset($_SESSION["registration_error_messages"]["confirm_password"]) ) ? " class='highlight'" : "" ?> />
					</div>
					<input type="hidden" name="action" value="register" />
					<input type="submit" value="Register" />
				</form>
			</div>
		</div>
	</body>
</html>
<?php
	unset($_SESSION["login_success_message"]);
	unset($_SESSION["login_error_messages"]);
	unset($_SESSION["registration_success_message"]);
	unset($_SESSION["registration_error_messages"]);
?>