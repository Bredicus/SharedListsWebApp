<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Pending List Invitations</title>
	</head>
	<body>
		<div class='container'>
			<h1>Pending List Invitations</h1>
			<table style='width:100%'>
				<tr>
                    <div class='btn-group'>
                    <td><a href='/public/list/create' class='btn btn-info btn-block' style='width:150px;'>New List</a></td>
                    <td align='center'><a href='/public/list/bookmark' class='btn btn-info btn-block' style='width:150px;'>Bookmarks &#11088;</a></td>
                    <td align='center'><a href='/public/list/index' class='btn btn-info btn-block' style='width:150px;'>Back to Lists</a></td>
                    <td align='right'><a href='/public/profile/index' class='btn btn-info btn-block' style='width:150px;'>Back to Profile</a></td>
                    </div>
				</tr>
			</table>
			<br/><br/>
            <?php
                if (!is_array($data)) {
                    echo "<div class='alert alert-info' role='alert'>$data</div>";
                }
                elseif (is_array($data)) {
                    echo "<table class='table table-striped'>";           
                    echo '<thead class="thead-dark"><tr><th>List Name</th><th>Sender</th><th>Action</th></tr></thead>';
                    echo '<tbody><tr></tr>';
                    foreach($data['allPending'] as $pending) {
                        echo "<tr><td>$pending->list_name</td>";
                        echo "<td>$pending->sent_by</td>";
                        echo "<td><a href='/public/list/join/$pending->list_id' class='btn btn-success'>Join List</a></td></tr>";
                    }
                    echo '</tbody></table> ';
                }
            ?>	
            <br/><br/>
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>