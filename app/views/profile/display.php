<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
		<title>View Profile</title>
	</head>
	<body>
		<div class='container'>
			<h1>View Profile</h1>
			<form action='' method='post'>
				<div class='input-group mb-3'>
					<div class='input-group-append'>
						<button type='submit' name='action' class='btn btn-secondary' type='button'>Search Profiles</button>
					</div>
					<input type='text' class='form-control' name='search'>
				</div>
			</form>	
			<div class='d-flex mb-3'>
				<div class='p-2'>
					<a href='/public/profile/index' class='btn btn-dark'>Back to Profile</a>
				</div>
				<div class='p-2'>
					<a href='/public/home/logout' class='btn btn-dark'>Log out</a>
				</div>
			</div>
			<br/><br/>		
			<table style='width: 100%'>
				<tr>
					<td style='width: 50%'>
						<table class='table table-striped'>
							<tr>
								<div class='d-flex mb-4'>
									<div class='p-2 flex-fill'>
										<a href="/public/message/new/$profile->profile_id" class="btn btn-success">Send Message</a>
									</div>
									<div class='p-2 flex-fill'>
										<?php
											if ($data->friendShip == 1) {
												echo '<a href="/public/friendlink/remove/' . $data->profile_id . '/1/none" class="btn btn-danger">Remove Friend</a>';
											}
											elseif ($data->friendShip == 2) {
												echo '<a href="/public/friendlink/remove/' . $data->profile_id . '/1/none" class="btn btn-warning">Revoke Request</a>';
											}
											elseif ($data->friendShip == 3) {
												echo '<a href="/public/friendlink/approve/' . $data->profile_id . '/1/none" class="btn btn-warning">Approve Request</a>';
											}
											elseif ($data->friendShip == 0) {
												echo '<a href="/public/friendlink/add/' . $data->profile_id . '/1/none" class="btn btn-warning">Add Friend</a>';
											}
										?>
									</div>
								</div>
							</tr>
							<tr></tr>
							<tr><th scope='row'>Name: </th><td><?=$data->first_name?> <?=$data->last_name?></td></tr>
						</table>
					</td>
					<td align='center' style='width: 50%'>
						<?php
							echo "<img src=" . (isset($data->picture_path) ? "../../$data->picture_path" : "../../../public/img/user.png") . " ";
							if (!empty($data->picture_path) && file_exists('../public/' . $data->picture_path)) {
								$path = '../../' . $data->picture_path;
							}
							else {
								$path = '../../../public/img/user.png';
							}
							echo "<img src=\"$path\" width=\"180\" height=\"180\" class='rounded-circle'/>";
						?>		
					</td>
				</tr>			
			</table>	
			<br/><br/>			
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>