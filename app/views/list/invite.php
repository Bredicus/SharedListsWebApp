<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Invite Members</title>
	</head>
	<body>
		<div class='container'>
			<h1>Invite Members</h1>
            <h3><?=$data->list_name?></h3>
			<table style='width:100%'>
				<tr>
                    <div class='btn-group'>
                    <td><a href='/public/list/members/<?=$data->list_id?>' class='btn btn-info btn-block' style='width:150px;'>Members</a></td>
                    <td align='center'><a href='/public/list/display/<?=$data->list_id?>' class='btn btn-info btn-block' style='width:150px;'>To List</a></td>
                    <td align='center'><a href='/public/list/index' class='btn btn-info btn-block' style='width:150px;'>Back to Lists</a></td>
                    <td align='right'><a href='/public/profile/index' class='btn btn-info btn-block' style='width:150px;'>Back to Profile</a></td>
                    </div>
				</tr>
			</table>
			<br/><br/>
            <?php
                if (!empty($data->InfoStr)) {
                    echo "<div class='alert alert-info' role='alert'>$data->InfoStr.<br/>
                    If you have friends that are not listed here, they may already be a member or invited to this list</div><br/>";
                }
                else {
                    echo "<table class='table table-striped'>";           
                    echo '<thead class="thead-dark"><tr><th>Name</th><th>Action</th></tr></thead>';
                    echo '<tbody><tr></tr>';
                    foreach($data->currentOwnerFriends as $friend) {
                        echo "<tr><td>$friend->first_name $friend->last_name</td>";
                        echo "<td><a href='/public/list/inviteMember/$data->list_id/$friend->profile_id' class='btn btn-success'>Invite to List</a></td>";
                        echo "</tr>";
                    }
                    echo '</tbody></table> ';
                    echo "<br/><br/><div class='alert alert-warning' role='alert'>If you have friends that are not listed here, they may already be a member or invited to this list</div> <br/>";
                }
			?>
            <br/><br/>
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>