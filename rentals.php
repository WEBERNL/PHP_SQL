<?php
require "html_begin.php";
require "database_connection.php";
?>


<?php

// specify SQL SELECT query for rentals
$getRental = "SELECT CONCAT(P.last_name, ', ', P.first_name) AS name, R1.reservation_date, B.bicycle_description, COUNT(R2.rental_ID) AS number_rented
			  FROM reservation R1
			  INNER JOIN person P ON R1.person_ID = P.person_ID
			  INNER JOIN rental R2 ON R1.reservation_ID = R2.reservation_ID
			  INNER JOIN bicycle B ON R2.bicycle_ID = B.bicycle_ID
			  GROUP BY name, reservation_date, bicycle_description
			  ORDER BY name, reservation_date, bicycle_description";

// query database for rentals
$queryRentalResult = $connection->query($getRental);



echo
	"<h1>Bicycle Rental History</h1>" .
	"<table>" .
		"<tr>" .
			"<th>Guest Name</th>" .
			"<th>Reservation Date</th>" .
			"<th>Bicycle Description</th>" .
			"<th>Number Rented</th>" .
		"</tr>" ;
	
while ($rentalRow = $queryRentalResult->fetch(PDO::FETCH_ASSOC)){
	echo 
		"<tr>" .
			"<td>" . $rentalRow['name'] . "</td>" .
			"<td>" . $rentalRow['reservation_date'] . "</td>" .
			"<td>" . $rentalRow['bicycle_description'] . "</td>" .
			"<td>" . $rentalRow['number_rented'] . "</td>" .
		"</tr>" ;
}

echo
	"</table>";
	
?>

	

<?php
require "html_end.php";
?>
