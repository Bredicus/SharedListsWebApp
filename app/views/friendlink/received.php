<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title>Incoming Requests</title>
	</head>
	<body>
		<div class='container'>
			<h1>Incoming Requests</h1>
			<form action='' method='post'>
				<div class='input-group mb-3'>
					<div class='input-group-append'>
						<button type='submit' name='action' class='btn btn-secondary' type='button'>Search Profiles</button>
					</div>
					<input type='text' class='form-control' name='search' required>
				</div>
			</form>	
            <div class='d-flex mb-3'>
                <div class='p-2 flex-fill'>
					<a href='/public/profile/index' class='btn btn-dark btn-block'>Back to Profile</a>           
                </div>
				<div class='p-2 flex-fill'>
					<td><a href='/public/friendlink/index' class='btn btn-dark btn-block'>Friend List</a></td>                 
                </div>
                <div class='p-2 flex-fill'>
					<td><a href='/public/friendlink/sent' class='btn btn-dark btn-block'>Sent Requests</a></td>                 
                </div>
                <div class='p-2 flex-fill'>
                    <td><a href='/public/home/logout' class='btn btn-dark btn-block'>Log out</a></td>
                </div>
            </div> 
			<br/>
            <?php
                if (!is_array($data)) {
                    echo "<div class='alert alert-info' role='alert'>$data</div> <br/>";
                }
            ?>		 
            <br/>
			<div class='overflow-auto' style='max-height: 650px;'> 
				<table class='table table-striped'>
					<?php 
						if (is_array($data) && !empty($data)) {
							echo '<tr><th>Image</th><th>Name</th><th>Actions</th></tr>';
							foreach($data['profiles'] as $profile) {
								echo "<tr><td>"; 
								echo "<img src='" . (isset($profile->picture_path) ? "/public/$profile->picture_path" :
								 "/public/img/user.png'") . "' width='35px' heigth='35px' class='rounded-circle'/></td>";							
								echo "<td>$profile->first_name $profile->last_name" . 
								($profile->isNew ? " <span class='badge badge-pill badge-danger'>New</span>" : "") . "</td/>";

								echo "<td><div class='d-none d-sm-block'><div class='d-flex mb-0'>";
								echo "<div class='flex-fill'><a href='/public/profile/display/$profile->profile_id' class='btn btn-info " . 
								($profile->privacy_flag ? "disabled" : "") . "'>View Profile</a></div>";
								echo "<div class='flex-fill'><a href='/public/message/new/$profile->profile_id' class='btn btn-success " . 
								($profile->privacy_flag ? "disabled" : "") . "'>Send Message</a></div>";
								echo "<div class='flex-fill'><a href='/public/friendlink/approve/$profile->profile_id/4/\"\"' 
								class='btn btn-secondary'>Approve Request</a></div>"; 
								echo "</div></div>";

								echo "<div align='center' class='d-block d-sm-none dropdown'><a class='btn btn-dark dropdown-toggle' 
								href='#' data-toggle='dropdown'>Options</a><div class='dropdown-menu' >";
								if (!$profile->privacy_flag) {
									echo "<a class='dropdown-item' href='/public/profile/display/$profile->profile_id'>View Profile</a>";
									echo "<a class='dropdown-item' href='/public/message/new/$profile->profile_id'>Send Message</a>";
								}
								echo "<a class='dropdown-item' href='/public/friendlink/approve/$profile->profile_id/4/\"\"'>Approve Request</a>";
								echo "</div></div>";

								echo "</td></tr>";
							}
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