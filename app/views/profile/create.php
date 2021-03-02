<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title>Create your profile</title>
	</head>
	<body>
		<div class='container'>
			<h1>Create your pofile</h1>
            <a href='/public/home/logout' class='btn btn-dark'>Log out</a>
			<br/><br/>
            <?php
                if (!is_array($data)) {
                    echo "<div class='alert alert-danger' role='alert'>$data</div>";
                }
            ?>
			<form action='' method='post'>
				<div class='form-group'>
					<label><strong>First Name:</strong> <input type='text' name='first_name' class='form-control' minlength='2' maxlength='50' required/></label>
				</div>
				<div class='form-group'>
					<label><strong>Last Name:</strong> <input type='text' name='last_name' class='form-control' minlength='2' maxlength='50' required/></label>
				</div>         
				<div class='form-group'>
					<label><strong>Gender:</strong> <input type='text' name='gender' class='form-control' maxlength='15'/></label>
				</div>   
                <div class='form-group'>
					<label><strong>Private:</strong> <input type='checkbox' name='privacy_flag' value='off' class='form-control'/></label>
				</div>               
                <div class='form-group'>
					<label><strong>Phone:</strong> <input type='text' name='phone' class='form-control' minlength='5' maxlength='25'/></label>
				</div>           
                <div class='form-group'>
					<label><strong>Email:</strong> <input type='text' name='email' class='form-control' minlength='5' maxlength='50' required/></label>
				</div>    
				<input type='submit' name='action' value='Register' class='btn btn-primary'/>				
			</form>				
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>