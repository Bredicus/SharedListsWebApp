<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
		<title>Display Message</title>
	</head>
	<body>
		<div class='container'>
			<h1>Display Message to <?=$_SESSION['message_name']?></h1>
			<br/>
			<a href='/public/profile/index' class='btn btn-dark'>Back to Profile</a>	
			&nbsp
			<a href='/public/message/index' class='btn btn-dark'>Back to Messages</a>
			<br/><br/>
			<?php 
				unset($_SESSION['message_name']);
				
				if (is_array($data) && !empty($data)) {
					echo '<table class="table table-striped">';
					foreach($data['messages'] as $message) {
						echo '<tr><th>Sender: ' . $message->name . '</th><th>Sent: ' . $message->sent_on . '</th><th>Read: ' . $message->isRead() . '</th><th></th></tr>';
						if ($message->currentIsSender) {
							echo "<tr><td></td><td colspan='2' style='color: #086BBD'>$message->message_text</td>
							<td><a href='/public/message/delete/$message->message_id'>&#x274C;</a></td></tr>";
						}
						else {
							echo "<tr><td colspan='2' style='color: #0B6850'>$message->message_text</td><td></td><td></td></tr>";
						}
					}
					echo '</table>';
				}
			?>
            <br/><br/>
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>