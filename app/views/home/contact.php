<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title>Contact us</title>
	</head>
	<body>
		<div class='container'>
			<h1>Contact us</h1>
            <?php
                if (!is_array($data)) {
                    echo "<div class='alert alert-danger' role='alert'>$data</div>";
                }
            ?>
			<form action='' method='post'>
				<div class='form-group'>
					<label><strong>Email:</strong> <input type='text' name='email' class='form-control' minlength='5' maxlength='50' required/></label>
				</div>
				<div class='form-group'>
					<label><strong>Message:</strong> <textarea name='message' class='form-control' cols='80' rows='8' minlength='15' maxlength='250' required></textarea></label>
				</div>                                
				<input type='submit' name='action' value='Submit' class='btn btn-primary'/>				
			</form>		
            <br/>Already registered? <a href='/public/home/index'>Login</a>
			<br/>No account? <a href='/public/home/register'>Register</a>
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>