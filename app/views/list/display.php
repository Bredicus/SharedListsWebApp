<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
        <style>		
            .card {
                box-shadow: 0 6px 10px 0 rgba(0,0,0,0.4);
                transition: 0.3s;
				padding: 4px 20px;
                width: 685px;
				margin: 0 auto;
                border-radius: 5px;
                display:inline-block;
            }
        </style>		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<title>List</title>
	</head>
	<body>
		<div class='container'>
			<h1>List</h1>
            <h3><?=$data->list_name?></h3>
			<table style='width:100%'>
				<tr>
                    <div class='btn-group'>
                    <td><a href='/public/list/index' class='btn btn-info btn-block' style='width:150px;'>Back to Lists</a></td>
                    <td align='center'><a href='/public/list/members/<?=$data->list_id?>' class='btn btn-info btn-block' style='width:150px;'>Members</a></td>
					<?php
						if ($data->is_owner) {
							echo "<td align='center'><a href='/public/list/edit/$data->list_id' class='btn btn-info btn-block' style='width:150px;'>Edit</a></td>";
							echo "<td align='right'><a href='/public/list/delete/$data->list_id' class='btn btn-danger btn-block' style='width:150px;'>Delete</a></td>";
						}
					?>
                    </div>
				</tr>
			</table>
			<br/>
			<a href='/public/list/conversation/<?=$data->list_id?>' class='btn btn-primary'>View Conversation</a><br/><br/>
			<?php
				date_default_timezone_set("Canada/Eastern");
				$daysToAdd = strtotime("+7 day");  
				if ($data->is_owner) {
					if ($data->is_complete) {
						echo "<h5>Mark as Incomplete? <a href='/public/list/setComplete/$data->list_id/0'>&#10062;</a></h5>";
					}
					else {
						echo "<h5>Mark as Complete? <a href='/public/list/setComplete/$data->list_id/1'>&#9989;</a></h5>";
					}
					echo "<br/><div class='card alert alert-info'>
						<form action='' method='post'>
							<div class='form-group'>
								<label><strong>Item Data:</strong><input type='text' name='item_data' class='form-control' size='100%'/></label>
							</div>
							<div class='form-group'>
								<label><strong>Priority:</strong> <input type='number' name='priority' class='form-control' step='1'/></label> &nbsp;
								<label><strong>Deadline:</strong> <input type='datetime-local' name='deadline' class='form-control'/></label> &nbsp;
								<input type='submit' name='action' value='Add New Item' class='btn btn-primary float-right mt-4' formaction='/public/item/create/$data->list_id'/>	
							</div>		
						</form>		
					</div><br/>";
				}

                if ($data->noItems) {
                    echo "<div class='alert alert-info' role='alert'>When you add items to the list, they will be displayed here</div> <br/>";
				}
				else {
					echo "<table class='table table-striped'>";
					echo '<thead class="thead-dark"><tr><th>Priority</th><th width="35%">Item</th><th>Deadline</th><th></th></tr></thead>';
					echo '<tbody><tr></tr>';
					foreach($data->items as $item) {
						if ($item->is_complete) {
							echo "<tr style='border:3pt solid green;'>";
						}           
						elseif ($item->deadline != NULL && date("Y-m-d h:i:s", $daysToAdd) > $item->deadline) {
							echo "<tr style='border:3pt solid red;'>";
						}
						else {
							echo "<tr>";
						}         						
						echo "<td>$item->priority</td>";
						if ($item->is_complete) {
							echo "<td><i><s>&nbsp; $item->item_data &nbsp;</s></i></td>";
						}
						else {
							echo "<td>$item->item_data</td>";
						}
						echo "<td>$item->deadline</td>";
						if ($data->is_owner) {
							echo "<td>";
							if ($item->is_complete) {
								echo "<a href='/public/item/setComplete/$data->list_id/$item->item_id/0'><span style='font-size:larger;'>&#10062;</span></a>&nbsp;";
							}
							else {
								echo "<a href='/public/item/setComplete/$data->list_id/$item->item_id/1'><span style='font-size:larger;'>&#9989;</span></a>&nbsp;";
							}
							echo "<a href='/public/item/edit/$data->list_id/$item->item_id' class='btn btn-primary'>Edit</a>
							<a href='/public/item/delete/$data->list_id/$item->item_id' class='btn btn-danger'>Delete</a>
							</td>";
						}
						else {
							echo '<td></td>';
						}
						echo '</tr>';
					}
					echo '</tbody></table>';
				}
            ?>
            <br/><br/>
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>