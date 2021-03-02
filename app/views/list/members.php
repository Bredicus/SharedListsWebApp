<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Members</title>
	</head>
	<body>
		<div class='container'>
			<h1>Members</h1>
            <h3><?=$data->list_name?></h3>
			<table style='width:100%'>
				<tr>
                    <div class='btn-group'>
                    <td><a href='/public/list/display/<?=$data->list_id?>' class='btn btn-info btn-block' style='width:150px;'>To List</a></td>
                    <td><a href='/public/list/index' class='btn btn-info btn-block' style='width:150px;'>Back to Lists</a></td>
                    <?php 
                        if ($data->currentIsOwner) {
                            echo "<td><a href='/public/list/invite/$data->list_id' class='btn btn-success btn-block' style='width:150px;'>Invite Users</a></td>";
                        }
                    ?>
                    </div>
				</tr>
			</table>
			<br/><br/>
			<table class='table table-striped'>
                <?php 
                    date_default_timezone_set("Canada/Eastern");
                    $daysToAdd = strtotime("+7 day");                 
                    echo '<thead class="thead-dark"><tr><th width="18%">Name</th><th width="25%">Joined</th><th width="10%">Profile</th><th width="45%"></th></tr></thead>';
                    echo '<tbody><tr></tr>';
                    foreach($data->members as $member) {
                        echo "<tr><td>$member->name</td><td>$member->accepted_on</td>";
                        
                        if ($member->canView == 2) {
                            echo "<td><a href='/public/profile/index' class='btn btn-primary'>View</a></td>";
                        } 
                        elseif ($member->canView == 1) {
                            echo "<td><a href='/public/profile/display/$member->profile_id' class='btn btn-primary'>View</a></td>";
                        }                                 
                        elseif ($member->canView == 0) {
                            echo "<td><button class='btn btn-primary disabled'>View</button></td>";
                        }              
                        
                        if ($data->currentIsOwner) {
                            if ($member->is_owner) {
                                echo "<td><a href='/public/list/setOwner/$member->list_id/$member->profile_id/0' class='btn btn-warning'>Remove Owner</a>";
                            }
                            else {
                                echo "<td><a href='/public/list/setOwner/$member->list_id/$member->profile_id/1' class='btn btn-warning'>Make Owner</a>";
                            }
                            echo "&nbsp;<a href='/public/list/removeMember/$member->list_id/$member->profile_id' class='btn btn-danger'>Remove Member</a></td>";
                        }
                        else {
                            echo "<td></td>";
                        }

                        echo "</tr>";
                    }
                    echo '</tbody>';
				?>
			</table> 
            <br/><br/>
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>