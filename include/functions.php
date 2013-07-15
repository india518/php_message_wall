<?php
	function get_messages()
	{
		//query database for all messages,
		// sorted newest to oldest
		$id = $_SESSION["user"]["id"];
		$find_messages_query = "SELECT *, CONCAT(MONTHNAME(created_at), ' ', DAYOFMONTH(created_at), ' ', YEAR(created_at)) as display_date FROM messages ORDER BY created_at DESC";
		return fetch_all($find_messages_query);
	}

	function display_messages($messages)
	{
		foreach($messages as $message)
		{
			$author_name = get_author_name($message);
			$date = $message["display_date"];
			//can this be improved? Seems clunky...
			//display message:
			echo "<div class='message_box left_padding'>";
			echo "<h4>{$author_name} - {$date}</h4>";
			echo "<div class='well'>{$message['message']}</div>";
			//display delete option, if applicable:
			if ( $message["user_id"] == $_SESSION["user"]["id"] )
			{	//This message belongs to us, the logged_in user
				create_delete_form($message);
			}
			//display comments:
			$comments = get_comments($message);
			display_comments($comments);
			comment_form($message);
			//close the div class='message_box left_padding'
			echo "</div>";
		}
	}

	function get_author_name($item)
	{
		$find_author_query = "SELECT CONCAT(first_name, ' ', last_name) as full_name FROM users WHERE id={$item['user_id']}";
		$author = fetch_record($find_author_query);
		return "{$author['full_name']}";
	}

	function create_delete_form($message)
	{
		echo "<form action='process.php' method='post'>";
		echo "<input type='hidden' name='action' value='delete_message' />";
		echo "<input type='hidden' name='message_id' value='{$message['id']}' />";
		echo "<button type='submit' class='btn btn-danger bottom-margin'>Delete Message</button>";
		echo "</form>";
	}

	function get_comments($message)
	{
		//query database for all comments for this particular
		// message, sorted oldest to newest
		$message_id = $message["id"];
		$find_comments_query = "SELECT *, CONCAT(MONTHNAME(created_at), ' ', DAYOFMONTH(created_at), ' ', YEAR(created_at)) as display_date FROM comments WHERE message_id = {$message['id']} ORDER BY created_at ASC";
		return fetch_all($find_comments_query);
	}

	function display_comments($comments)
	{
		foreach($comments as $comment)
		{
			echo "<div class='comment_box left_padding'>";
			$author_name = get_author_name($comment);
			$date = $comment["display_date"];
			echo "<h4>{$author_name} - {$date}</h4>";
			echo "<p class='well'>{$comment['comment']}</p>";
			echo "</div>";
		}
	}

	function comment_form($message)
	{	?>
		<form class="post_a_comment left_padding" action="process.php" method="post">
			<label for="comment">Post a comment:</label>
			<textarea class="input-block-level" rows="3" name="comment" id="comment"></textarea>
			<input type="hidden" name="action" value="post_comment" />
			<input type="hidden" name="message_id" value="<?= $message['id'] ?>" />
			<button type="submit" class="btn pull-right">Post Comment</button>
		</form>
		<div class="clearfix"></div>
<?php
 	}
 ?>