<?php
	function get_messages()
	{
		//query database for all messages,
		// sorted newest to oldest
		$id = $_SESSION["user"]["id"];
		$query = "SELECT *, CONCAT(MONTHNAME(created_at), ' ', DAYOFMONTH(created_at), ' ', YEAR(created_at)) as display_date FROM messages ORDER BY created_at DESC";
		return fetch_all($query);
	}

	function display_messages($messages)
	{
		foreach($messages as $message)
		{
			$author_name = get_author_name($message);
			$date = $message["display_date"];
			//can this be improved? Seems clunky...
			echo "<div class='message_box left_padding'>";
			echo "<h4>{$author_name} - {$date}</h4>";
			echo "<p class='well'>{$message['message']}</p>";
			$comments = get_comments($message);
			display_comments($comments);
			comment_form($message);
			echo "</div>"; //this is closing the div above
		}
	}

	function get_author_name($item)
	{
		$query = "SELECT CONCAT(first_name, ' ', last_name) as full_name FROM users WHERE id={$item['user_id']}";
		$author = fetch_record($query);
		return "{$author['full_name']}";
	}

	function get_comments($message)
	{
		//query database for all comments for this particular
		// message, sorted oldest to newest
		$message_id = $message["id"];
		$query = "SELECT *, CONCAT(MONTHNAME(created_at), ' ', DAYOFMONTH(created_at), ' ', YEAR(created_at)) as display_date FROM comments WHERE message_id = {$message['id']} ORDER BY created_at ASC";
		return fetch_all($query);
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