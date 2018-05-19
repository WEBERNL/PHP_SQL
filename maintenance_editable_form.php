<?php
require "html_begin.php";
require "database_connection.php";
?>

<?php
// note that when this php document is called (from maintenance.php), the $_GET autoglobal includes a value at index 'taskID' that refers to a specific maintenance task ID
// this php document then displays a form that includes the maintenance data for that specific maintenance task ID
// this form only allows edits to the maintenance category ID for the specified maintenance task ID
// once this form is submitted, the $_GET autoglobal includes a value at index 'categoryID'
// upon initial display of this php document, then, $_GET['categoryID'] doesn't exist  

if (!isset($_GET['categoryID'])) {
	// specify SQL SELECT query for maintenance data for specific maintenance task ID
	$taskID = $_GET['taskID'];
	$getMaintenance = "SELECT B.bicycle_ID, B.SN, B.bicycle_description, O.maintenance_order_ID, T.maintenance_task_ID, T.maintenance_category_ID, C.category_name
					  FROM maintenance_order O
					  INNER JOIN bicycle B ON O.bicycle_ID = B.bicycle_ID
					  INNER JOIN maintenance_task T ON O.maintenance_order_ID = T.maintenance_order_ID
					  INNER JOIN maintenance_category C ON T.maintenance_category_ID = C.maintenance_category_ID
					  WHERE T.maintenance_task_ID = $taskID";
					  

	// query database for maintenance data
	$queryMaintenanceResult = $connection->query($getMaintenance);
?>
		<!-- display query results in a form that allows edits to the maintenance category only -->
		<h1>Edit Bicycle Maintenance Description</h1>
		
		<form id='bicycleMaintenanceEdits' method='get' action='maintenance_editable_form.php'>				
				
<?php		
		while ($maintenanceRow = $queryMaintenanceResult->fetch(PDO::FETCH_ASSOC)){
			echo 
				"<label for='orderID'>Maintenance Order: </label>" . "<input id='orderID' name='maintenanceOrderID' value='" . $maintenanceRow['maintenance_order_ID'] . "' disabled='disabled'></input><br /><br />" .
				"<label for='taskID'>Maintenance Task ID: </label>" . "<input id='taskID' name='maintenanceTaskID' value='"  . $maintenanceRow['maintenance_task_ID'] . "' disabled='disabled'></input><br /><br />" ;
					
				// specify SQL SELECT query for maintenance categories
				$getCategory = "SELECT category_name, maintenance_category_ID FROM maintenance_category ORDER BY category_name";
				
				// query database for maintenance category data
				$queryCategoryResult = $connection->query($getCategory);
				
				// display query results as options within a select element on the form
				echo
				"<label>Maintenance Category: </label>" . "<select name='categoryID' size='5'>";
				
					while ($categoryRow = $queryCategoryResult->fetch(PDO::FETCH_ASSOC)){
						echo "<option value='" . $categoryRow['maintenance_category_ID'] . "'";
							if($categoryRow['maintenance_category_ID'] == $maintenanceRow['maintenance_category_ID']) {
									echo "selected='selected'";
							}
						echo ">" . $categoryRow['category_name'] . "</option><br />";
					}
					
				echo "</select><br /><br /><br /><br /><br />";
			
			
			
			echo		
				"<label for='bicycleID'>Bicycle ID: </label>" . "<input id='bicycleID' name='bicycleID' value='" . $maintenanceRow['bicycle_ID'] . "' disabled='disabled'></input><br /><br />" .
				"<label for='bicycleSN'>Bicycle Serial Number: </label>" . "<input id='bicycleSN' name='bicycleSN' value='" . $maintenanceRow['SN'] . "' disabled='disabled'></input><br /><br />" .	
				"<label for='bicycleDesc'>Bicycle Description: </label>" . "<input id='bicycleDesc' name='bicycleDescription' value='" . $maintenanceRow['bicycle_description'] . "' disabled='disabled'></input><br /><br />";
			
			echo 
				"<input type='submit' name='submitUpdate' value='Submit'></input><br /><br />" . 
				"<input type='hidden' name='taskID' value='"  . $maintenanceRow['maintenance_task_ID'] . "'></input><br /><br />" ;
		}
?>

		</form>
		<br /><br />
		
<?php
}		
?>

<?php
// once this form is submitted (and the server navigates to the URL specified in the action attribute of the form), the previous $_GET autoglobal elements are cleared...since this form specifies a method attribute of "get", the $_GET autoglobal is then updated to include a value at index 'categoryID'
// note that since most of the input elements are disabled, their names/values won't be included in the $_GET autoglobal upon form submission
// since the maintenance task ID and the maintenance category ID are necessary for updates to the database, a hidden input is included in the form, and its name/value refers to the maintenance task ID...note that the hidden input name/value information is included in the $_GET autoglobal upon form submission
if (isset($_GET['categoryID'])) {
	$categoryID = $_GET['categoryID'];
	$taskID = $_GET['taskID'];

	// specify SQL UPDATE statement to update the maintenance category ID for the specific maintenance task ID
	$updateTaskCategory = "UPDATE maintenance_task SET maintenance_category_ID = $categoryID  WHERE maintenance_task_ID = $taskID";
	
	// update the database
	$updateResult = $connection->exec($updateTaskCategory);
	
		// display alerts indicating if database update succeeded/failed and redirect to maintenance.php
		if($updateResult == 1) {
?>			
			<script>
			window.alert("The database was successfully updated.");
			
			window.setTimeout(transition, 1000);
			
			function transition(){			
			window.location.href="maintenance.php";
			}			
			</script>
					
<?php		}else{
?>
			<script>
			window.alert("The update couldn't be processed at this time.");
			
			window.setTimeout(transition, 1000);
			
			function transition(){			
			window.location.href="maintenance.php";
			}			
			</script>
					
<?php		}
	
}
?>

<?php
require "html_end.php";
?>
