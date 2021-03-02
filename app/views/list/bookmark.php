<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">	
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Bookmarks</title>
	</head>
	<body>
		<div class='container'>
			<h1>Bookmarks</h1>
			<table style='width:100%'>
				<tr>
                    <div class='btn-group'>
                    <td><a href='/public/list/create' class='btn btn-info btn-block' style='width:150px;'>New List</a></td>
                    <td align='center'><a href='/public/list/index' class='btn btn-info btn-block' style='width:150px;'>All Lists</a></td>
                    <?php 
                        if ($_SESSION['newLists'] > 0) {
                            $newLists = $_SESSION['newLists'];
                            echo "<td align='center'><a href='/public/list/pending' class='btn btn-info btn-block' 
                            style='width:150px;'>Invitations <span class='badge badge-pill badge-warning'>$newLists</span></a></td>";                           
                        }
                        else {
                            echo "<td align='center'><a href='/public/list/pending' class='btn btn-info btn-block' style='width:150px;'>Invitations</a></td>";
                        }
                        unset($_SESSION['newLists']);
                    ?>
                    <td align='right'><a href='/public/profile/index' class='btn btn-info btn-block' style='width:150px;'>Back to Profile</a></td>
                    </div>
				</tr>
			</table>
			<br/><br/>
            <?php
                if (!is_array($data)) {
                    echo "<div class='alert alert-info' role='alert'>$data</div> <br/>";
                }
            ?>
            <br/><br/>
			<table class='table table-striped'>
                <?php 
                    date_default_timezone_set("Canada/Eastern");
                    $daysToAdd = strtotime("+7 day");                  
                    if (is_array($data) && !empty($data)) {
                        echo '<thead class="thead-dark"><tr><th width="45%">List Name</th><th>Deadline</th><th>Actions</th></tr></thead>';
                        echo '<tbody><tr></tr>';
                        foreach($data['lists'] as $list) {
                            if ($list->is_complete) {
                                echo "<tr style='border:3pt solid green;'>";
                            }           
                            elseif ($list->deadline != NULL && date("Y-m-d h:i:s", $daysToAdd) > $list->deadline) {
                                echo "<tr style='border:3pt solid red;'>";
                            }
                            else {
                                echo "<tr>";
                            }  
                            if (strlen($list->list_name) >= 27) {
                                $name = substr($list->list_name, 0, 26) . '...';
                            }
                            else {
                                $name = $list->list_name;
                            }
                            echo "<td>$name</td>";
                            echo "<td>$list->deadline</td>";
                            echo "<td>
                            <a href='/public/list/setBookmark/$list->list_id/1/1' class='btn btn-default'>&#11088;</a>
                            <a href='/public/list/display/$list->list_id' class='btn btn-success'>View</a>
                            <a href='/public/list/leave/$list->list_id/1' class='btn btn-danger'>Leave</a>
                            </td>";                                                        
                            echo "</tr>";
                        }
                        echo '</tbody>';
                    }
				?>
			</table> 
		</div>		
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>		
	</body>
</html>