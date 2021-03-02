<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
        <title>Messages</title>
	</head>
	<body>
		<div class='container'>
			<h1>Messages</h1>
			<div class='d-flex mb-3'>
				<div class='p-2'>
					<a href='/public/profile/index' class='btn btn-dark'>Back to Profile</a>
				</div>
				<div class='p-2'>
					<a href='/public/home/logout' class='btn btn-dark'>Log out</a>
				</div>
			</div>
            <br/>
            <?php
                if (!is_array($data)) {
                    echo "<div class='alert alert-info' role='alert'>$data</div> <br/>";
                }
            ?>		
            <a href='/public/friendlink/index' class='btn btn-secondary'>View Friends</a>
            <br/><br/>
            <div class='overflow-auto' style='max-height: 650px;'> 
                <table class='table table-striped'>
                    <?php 
                        if (is_array($data) && !empty($data)) {
                            echo '<thead class="thead-dark"><tr><th>Image</th><th>Name</th><th>Timestamp</th><th width="65%">Message</th></tr></thead>';
                            echo '<tbody><tr></tr>';
                            foreach($data['conversations'] as $conversations) {
                                if (strlen($conversations->message_text) >= 85) {
                                    $msg = substr($conversations->message_text, 0, 85) . '...';
                                }
                                else {
                                    $msg = $conversations->message_text;
                                }

                                echo "<tr><td><img src='" . 
                                (isset($conversations->picture_path) ? "/public/$conversations->picture_path" : "/public/img/user.png'") . 
                                "' width='50px' class='rounded-circle mt-2'/></td></td>";

                                echo "<td><a href='/public/profile/display/$conversations->profile_id' style='height:65px' class='btn btn-info btn-block text-left " . 
                                ($conversations->isFriend ? "" : "disabled") . "'>" . $conversations->name . "</a></td/>";
                                
                                echo "<td>$conversations->sent_on</td>";

                                echo "<td>";
                                echo "<div class='d-none d-md-block'>" . ($conversations->profile_id == -1 ?
                                "<a href='/public/message/display/-$conversations->message_id'" : "<a href='/public/message/new/$conversations->profile_id'") . 
                                "class='btn btn-success btn-block text-left' style='height:65px'>$msg" .
                                (!$conversations->is_read && !$conversations->isSender ? "<span class='badge badge-pill badge-danger'> New</span>" : "") . 
                                "</a></div>";

                                echo "<div class='d-block d-md-none'>" . ($conversations->profile_id == -1 ?
                                "<a href='/public/message/display/-$conversations->message_id'" : "<a href='/public/message/new/$conversations->profile_id'") . 
                                "class='btn btn-success btn-block text-left' style='height:65px'>View Conversation" .
                                (!$conversations->is_read && !$conversations->isSender ? "<span class='badge badge-pill badge-danger'> New</span>" : "") . 
                                "</a></div>";
                                echo "</td>";

                                echo "</tr>";
                            }
                            echo '</tbody>';
                        }
                    ?>
                </table>    
            </div>        
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>