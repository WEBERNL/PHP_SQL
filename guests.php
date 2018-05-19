<?php
require "html_begin.php";
require "database_connection.php";
?>


<?php

// specify SQL SELECT query for guests
$getGuest = "SELECT last_name, first_name, CONCAT(address1, ', ', city, ', ', state, ', ', zip) AS address, phone1, email FROM person ORDER BY last_name, first_name, phone1";

// query database for guests
$queryGuestResult = $connection->query($getGuest);



echo
	"<h1>Guest Data</h1>" .
	"<table>" .
		"<tr>" .
			"<th>Last Name</th>" .
			"<th>First Name</th>" .
			"<th>Address</th>" .
			"<th>Phone Number</th>" .
			"<th>Email Address</th>" .
		"</tr>" ;
	
while ($guestRow = $queryGuestResult->fetch(PDO::FETCH_ASSOC)){
	echo 
		"<tr>" .
			"<td>" . $guestRow['last_name'] . "</td>" .
			"<td>" . $guestRow['first_name'] . "</td>" .
			"<td>" . $guestRow['address'] . "</td>" .
			"<td>" . $guestRow['phone1'] . "</td>" .
			"<td>" . $guestRow['email'] . "</td>" .
		"</tr>" ;
}

echo
	"</table>";
	
?>

	

<?php
require "html_end.php";
?>
