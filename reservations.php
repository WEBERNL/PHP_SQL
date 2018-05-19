<?php
require "html_begin.php";
require "database_connection.php";
?>


<?php

// specify SQL SELECT query for reservations
$getReservation = "SELECT R.reservation_ID, R.person_ID, CONCAT(P.last_name, ', ', P.first_name) AS name, R.number_adults, R.number_children, R.reservation_date FROM reservation R INNER JOIN person P ON R.person_ID = P.person_ID ORDER BY name, reservation_date";

// query database for reservations
$queryReservationResult = $connection->query($getReservation);



echo
	"<h1>Reservation History</h1>" .
	"<table>" .
		"<tr>" .
			"<th>Guest Name</th>" .
			"<th>Adults</th>" .
			"<th>Children</th>" .
			"<th>Reservation Date</th>" .
			"<th>Reservation ID</th>" .
		"</tr>" ;
	
while ($reservationRow = $queryReservationResult->fetch(PDO::FETCH_ASSOC)){
	echo 
		"<tr>" .
			"<td>" . $reservationRow['name'] . "</td>" .
			"<td>" . $reservationRow['number_adults'] . "</td>" .
			"<td>" . $reservationRow['number_children'] . "</td>" .
			"<td>" . $reservationRow['reservation_date'] . "</td>" .
			"<td>" . $reservationRow['reservation_ID'] . "</td>" .
		"</tr>" ;
}

echo
	"</table>";
	
?>

	

<?php
require "html_end.php";
?>
