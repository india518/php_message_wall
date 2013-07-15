<?php
	session_start();
	require("connection.php");

	//if we're not logged in, redirect us to the login/registration page
	if ( !isset($_SESSION["logged_in"]) )
		header("location: index.php");
	else
	{
		require("include/header.php");
		require("include/functions.php");
	}
?>

		<div id="display_error_messages"
		<?php
			if ( isset( $_SESSION["posting_error_message"]) )
			{	?>
				class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<p><strong>Post or Comment creation failed!</strong></p>
				<p><?= $_SESSION["posting_error_message"] ?></p>
			<?php
				unset($_SESSION["posting_error_message"]);
			}
			else //just print out closing <div>-carrot
				{?>><?php } ?>
		</div>

		<div class="container">
			<form class="post_a_message left_padding" action="process.php" method="post">
				<label for="message">Post a message:</label>
				<textarea class="input-block-level" rows="3" name="message" id="message"></textarea>
				<input type="hidden" name="action" value="post_message" />
				<button type="submit" class="btn pull-right">Post Message</button>
			</form>
			<div class="clearfix"></div>
			<div id="message_list">
				<?php
					$messages = get_messages();
					display_messages($messages);
				?>
			</div>
		</div>
	</body>
</html>
