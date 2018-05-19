<?php
require "html_begin.php";
require "database_connection.php";
?>

<script>
	function editMaintenance(maintenanceTaskID) {
		window.location.href = "maintenance_editable_form.php?taskID=" + maintenanceTaskID;
	}

</script>

<?php

// specify SQL SELECT query for maintenance orders
$getMaintenance = "SELECT B.bicycle_ID, B.SN, B.bicycle_description, O.maintenance_order_ID, T.maintenance_task_ID, C.category_name
				  FROM maintenance_order O
				  INNER JOIN bicycle B ON O.bicycle_ID = B.bicycle_ID
				  INNER JOIN maintenance_task T ON O.maintenance_order_ID = T.maintenance_order_ID
				  INNER JOIN maintenance_category C ON T.maintenance_category_ID = C.maintenance_category_ID
				  ORDER BY maintenance_order_ID, maintenance_task_ID";

// query database for maintenance orders
$queryMaintenanceResult = $connection->query($getMaintenance);

// display query result
echo
				"<h3>Bicycle Maintenance Orders</h3>" .
				"<table>" .
					"<tr>" .
						"<th>Maintenance Order</th>" .
						"<th>Maintenance Task ID</th>" .
						"<th>Maintenance Category</th>" .
						"<th>Bicycle ID</th>" .
						"<th>Bicycle Serial Number</th>" .
						"<th>Bicycle Description</th>" .
						"<th></th>" .	
					"</tr>" ;
				
			while ($maintenanceRow = $queryMaintenanceResult->fetch(PDO::FETCH_ASSOC)){
				echo 
					"<tr>" .
						"<td>" . $maintenanceRow['maintenance_order_ID'] . "</td>" .
						"<td>" . $maintenanceRow['maintenance_task_ID'] . "</td>" .
						"<td>" . $maintenanceRow['category_name'] . "</td>" .
						"<td>" . $maintenanceRow['bicycle_ID'] . "</td>" .
						"<td>" . $maintenanceRow['SN'] . "</td>" .
						"<td>" . $maintenanceRow['bicycle_description'] . "</td>" .
						"<td><input type='button' name='editMaintenanceTask' value='Edit' onclick='editMaintenance(" . $maintenanceRow['maintenance_task_ID'] . ")'></input></td>" .
					"</tr>";
					
			}

			echo
				"</table>" .
				"<br /><br />";				  
				  
				  

?>

<?php
require "html_end.php";
?>
