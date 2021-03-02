<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title>Edit List Item</title>
	</head>
	<body>
		<div class='container'>
			<h1>Edit List Item</h1>
            <?php
                echo "<h3>$data->list_name</h3>";
                if (!empty($data->InfoStr)) {
                    echo "<div class='alert alert-warning' role='alert'>$data->InfoStr</div>";
                }
            ?>
			<form action='' method='post'>
				<div class='form-group'>
					<label>Item Data: <input type='text' name='item_data' class='form-control' size='100' value='<?=$data->item->item_data?>'/></label>
				</div>
                <div class='form-group'>
					<label>Priority: <input type='number' name='priority' class='form-control' step='1' value='<?=$data->item->priority?>'/></label>
				</div>
				<div class='form-group'>
                    <?php
                        $pos = strpos($data->item->deadline, ' ');
                        $date = substr($data->item->deadline, 0, $pos);
                        $time = substr($data->item->deadline, $pos + 1);
                        $dateTime = $date . 'T' . $time;
                        echo "<label>Deadline: <input type='datetime-local' name='deadline' class='form-control' value='$dateTime'/></label>";
                    ?>
				</div>        
				<input type='submit' name='action' value='Save' class='btn btn-primary'/>	
				&nbsp;&nbsp;
				<a href='/public/list/display/<?=$data->list_id?>' class='btn btn-danger'>Cancel</a>			
			</form>				
		</div>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>