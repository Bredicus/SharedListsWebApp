<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title>Conversation</title>
	</head>
	<body>
		<div class='container'>
			<h1>Conversation</h1>
            <h3><?=$data->list_name?></h3>
			<br/>
			<a href='/public/list/display/<?=$data->list_id?>' class='btn btn-info'>To List</a>	
			&nbsp
			<a href='/public/list/index' class='btn btn-success'>Back to Lists</a>
			<br/><br/>
			<?php
				if (!empty($data->conversation) && count($data->conversation) > 0) {
					echo '<table class="table table-striped">';
					foreach($data->conversation as $message) {
						echo '<tr><th>Sender: ' . $message->sender_name . '</th><th>Sent: ' . $message->sent_on . '</th><th></th></tr>';
						echo "<tr><td colspan='2' style='color: #228B22'>$message->message_text</td>";
						if ($message->currentIsSender) {
							echo "<td><a href='/public/list/deleteMsg/$message->message_id'>&#x274C;</a></td></tr>";
						}
						else {
							echo "<td></td></tr>";
						}
					}
					echo '</table>';
				}
			?>
			<br/>
			<form align='center' action='' method='post'>
				<div class='form-group'>
					<label><b>Message:</b> <textarea name='message' class='form-control' cols='80' rows='8'></textarea></label>
				</div>                                
				<input type='submit' name='action' value='Submit' class='btn btn-primary'/>				
			</form>		
            <br/><br/>
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>