<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">		
		<title>Welcome to your profile</title>
	</head>
	<body>
		<div class='container'>
			<h1>Welcome to your profile</h1>
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
                    <?php
                        echo "<a href='/public/message/index' class='btn btn-dark btn-block'>Messages " . 
                        ($data->newMsg > 0 ? "<span class='badge badge-pill badge-danger'> $data->newMsg New</span>" : "") . "</a>";
                    ?>                
                </div>
                <div class='p-2 flex-fill'>
                    <?php
                        echo "<a href='/public/friendlink/index' class='btn btn-dark btn-block'>Friends " . 
                        ($data->newFL > 0 ? "<span class='badge badge-pill badge-danger'> $data->newFL New</span>" : "") . "</a>";
                    ?>                
                </div>
                <div class='p-2 flex-fill'>
                <?php
                        echo "<a href='/public/list/index' class='btn btn-dark btn-block'>Lists " . 
                        ($data->pendingLists > 0 ? "<span class='badge badge-pill badge-danger'> $data->pendingLists </span>" : "") . "</a>";
                    ?>                  
                </div>
                <div class='p-2 flex-fill'>
                    <a href='/public/home/logout' class='btn btn-dark btn-block'>Log out</a>
                </div>
            </div>    
            <br/>
            <div class='d-flex mb-3' align='center'>
                <div class='p-2 flex-fill'>
                    <?php
                        if (!empty($data->picture_path) && file_exists('../public/' . $data->picture_path)) {
                            $path = '../' . $data->picture_path;
                        }
                        else {
                            $path = '../../public/img/user.png';
                        }
                        echo "<img src=\"$path\" width=\"250px\" height=\"250px\" class='rounded-circle'/>";
                    ?> 
                </div>
                <div class='p-2 flex-fill'>
                    <br/><br/><br/>
                    <form action='/public/profile/setPicture' method='post' enctype='multipart/form-data'>
                        <input type='file' name='file' id='file' class='hidden' style='display:none;' onchange='form.submit()'/>
                        <label for='file' class='btn btn-primary'>Choose Picture</label>
                    </form>
                    <br/>
                    <a href='/public/profile/deletePicture' class='btn btn-danger'>Remove Picture</a>
                </div>
            </div>                   
			<a href='/public/profile/edit' class='btn btn-success' style='width: 8em;'>Edit</a>	
			&nbsp
			<a href='/public/home/password' class='btn btn-primary'>Change Password</a>
			<br/><br/>	
            <table class='table table-striped'>
                <tr><th scope='row'>Name: </th><td><?=$data->first_name?> <?=$data->last_name?></td></tr>
                <tr><th scope='row'>Gender: </th><td><?=$data->gender?></td></tr>
                <tr><th scope='row'>Privacy: </th><td><?=$data->privacy()?></td></tr>
                <tr><th scope='row'>Phone: </th><td><?=$data->phone?></td></tr>
                <tr><th scope='row'>Email: </th><td><?=$data->email?></td></tr>
                <tr><th scope='row'>Last Login: </th><td><?=$data->last_login?></td></tr>
            </table>
			<br/>
			<a href='/public/profile/delete' class='btn btn-danger btn-block'>Delete Profile</a>
			<br/>			
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>