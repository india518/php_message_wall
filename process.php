<?php
	require("connection.php");
	session_start();

	function email_validation()
	{
		$message = NULL;
		if ( empty($_POST["email"]) )
			$message = "Email address cannot be blank.";
		else if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) )
			$message = "Email should be in valid format.";
		return $message;
	}

	function do_login()
	{
		$errors = array();

		//First, we validate the email address format!
		$email_message = email_validation();
		if ( $email_message )
			$errors["email"] = $email_message;
		//I've removed the password format since it would have been
		// validated when the user registered. If the password field
		// is left blank, or is too short, it will not match the password
		// in the database anyway.

		if( count($errors) > 0 )
		{
			$_SESSION["login_error_messages"] = $errors;
			header("location: index.php");
		}
		else
		{
			// Check that user exists
			$query = "SELECT users.* FROM users WHERE users.email = '{$_POST['email']}'";
			$user = fetch_record($query);
			if (! $user )
			{
				$_SESSION["login_error_messages"]["user"] = "There is no account with this email address. Try registering for a new account!";
					header("location: index.php");
			}
			else // we found a user!
			{
				// but is their password valid?
				if ( md5($_POST["password"]) != $user["password"] )
				{
					$_SESSION["login_error_messages"]["password"] = "Incorrect password.";
					header("location: index.php");
				}
				else
				{
					// The password *is* valid!
					// Now, we create a user object (of sorts) in the $_SESSION variable, and log them in!
					$_SESSION["user"]["id"] = intval($user["id"]);
					$_SESSION["user"]["first_name"] = $user["first_name"];
					$_SESSION["user"]["last_name"] = $user["last_name"];
					$_SESSION["user"]["email"] = $user["email"];
					$_SESSION["logged_in"] = true;
					header("location: wall.php");
					//yay!
				}
			}
		}
	} //end do_login()

	function do_registration()
	{
		$min_password_length = 7;
		$errors = array();

		//First name validation
		if ( empty($_POST["first_name"]) )
			$errors["first_name"] = "First Name cannot be blank.";
		else if ( preg_match("#[\d]#", $_POST["first_name"]) )

			//note: is_numeric($_POST["first_name"]) doesn't really work!
			// it will allow a first name like "India518" when it shouldn't!

			$errors["first_name"] = "First Name cannot contain numbers.";
		//Last name validation
		if ( empty($_POST["last_name"]) )
			$errors["last_name"] = "Last Name cannot be blank.";
		else if ( preg_match("#[\d]#", $_POST["last_name"]) )
			$errors["last_name"] = "Last Name cannot contain numbers.";
		//Email validation
		$message = email_validation();
		if ( $message )
			$errors["email"] = $message;
		//Birthdate validation
		// The form uses a datepicker, but let's keep this for reference.
		// if(! empty($_POST["birth_date"]) AND ! preg_match(" '\b(0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2}\b' ", $_POST["birth_date"]) )
		// 	$errors["birth_date"] = "Birth Date should be in valid format.";

		// Password format validation
		if( empty($_POST["password"]) )
			$errors["password"] = "Password cannot be blank.";
		else if ( strlen($_POST["password"]) < $min_password_length )
			$errors["password"] = "Password should be at least {$min_password_length} characters.";
		//Confirm password validation
		if ( empty($_POST["confirm_password"]) )
			$errors["confirm_password"] = "The Confirm Password field cannot be blank.";
		else if ( $_POST["confirm_password"] != $_POST["password"] )
			$errors["confirm_password"] = "Passwords do not match.";

		if( count($errors) > 0 )
		{
			$_SESSION["registration_error_messages"] = $errors;
			header("location: index.php");
		}
		else
		{
			//FIRST - check to see if user already exists
			$query = "SELECT users.* FROM users WHERE users.email = '{$_POST['email']}'";
			$user = fetch_record($query);

			if ( $user )
			{
				$_SESSION["registration_error_messages"]["user"] = "A user with this email already exists. Try logging in!";
				header("location: index.php");
			}
			else
			{
				//NEXT: we have a new, unique user. Stick 'em in the database!
				$first_name = $_POST["first_name"];
				$last_name = $_POST["last_name"];
				$email = $_POST["email"];
				$password = md5($_POST["password"]);
				$query = "INSERT INTO users (first_name, last_name, email, password, created_at) VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$password}', NOW())";
				mysql_query($query);

				//Quick thought to look up later: is there a true/false value
				// associated with a mysql_query() command?
				//Would it be better practice to only product the following
				// message if the mysql_query() command returned true?
				$_SESSION["registration_success_message"] = "Thank you for submitting your information. Your new account has been created!";
				header("location: index.php");
			}
		}
	} //end do_registration()

	function check_post_text($text, $item)
	{
		//$item tells us whether this is a message or a comment
		if (strlen($text) < 1)
		{
			$_SESSION["posting_error_message"] = "You can't post an empty {$item}! Try entering some text and try again.";
			return false;
			//header("location: wall.php");
		}
		return true;
	}

	function do_post_message()
	{
		//Check that message isn't blank
		$message = $_POST["message"];
		$test = check_post_text($message, "message");
		if ($test)
		{	//Store message in database
			$user_id = $_SESSION["user"]["id"];
			$query = "INSERT INTO messages (user_id, message, created_at) VALUES ('{$user_id}', '{$message}', NOW())";
			mysql_query($query);
		}
		header("location: wall.php");
	}

	function do_post_comment()
	{
		//Check that comment isn't blank
		$comment = $_POST["comment"];
		$test = check_post_text($comment, "comment");
		if ($test)
		{	//Store comment in database
			$message_id = $_POST["message_id"];
			$user_id = $_SESSION["user"]["id"];
			$query = "INSERT INTO comments (message_id, user_id, comment, created_at) VALUES ('{$message_id}', '{$user_id}', '{$comment}', NOW())";
			mysql_query($query);
		}
		header("location: wall.php");
	}

	//Here is the actual code!
	if ( isset($_POST['action']) AND $_POST["action"] == "login" )
		do_login();
	else if ( isset($_POST['action']) AND $_POST["action"] == "register" )
		do_registration();
	else if ( isset($_POST['action']) AND $_POST["action"] == "post_message" )
		do_post_message();
	else if ( isset($_POST['action']) AND $_POST["action"] == "post_comment" )
		do_post_comment();
	else
	{
		//We are assuming the user wants to log out
		session_destroy();
		header("location: index.php");
	}

?>