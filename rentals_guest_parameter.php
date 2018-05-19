<?php
require "html_begin.php";
require "database_connection.php";
?>

<?php
if (!isset($_GET['personID'])) {
	echo	
		"<form method='get' action='rentals_guest_parameter.php' >" .
			"<h3>Search rental history by selecting a guest name and phone number</h3>" .	
			"<select name='personID' size='3' >";
				
		// specify SQL SELECT query for guests
		$getGuest = "SELECT person_ID, last_name, first_name, CONCAT(address1, ', ', city, ', ', state, ', ', zip) AS address, phone1, email FROM person ORDER BY last_name, first_name, phone1";

		// query database for guests
		$queryGuestResult = $connection->query($getGuest);
				
		while ($guestRow = $queryGuestResult->fetch(PDO::FETCH_ASSOC)){
			echo "<option value='" . $guestRow['person_ID'] . "'>" . $guestRow['last_name'] . ", " . $guestRow['first_name'] . " @ " . $guestRow['phone1'] . "</option><br />";
		}
					

	echo 	
			"</select>" .
			"<br /><br />" .
			"<input type='submit' name='submit' value='Search'></input>" .
		"</form>" .
		"<br /><br />";
}
?>

<?php
if (isset($_GET['personID'])) {
	
	// specify preliminary SQL SELECT query for rentals to determine if any rows exist for the specified person_ID
	$getRental = "SELECT COUNT(R2.rental_ID) FROM reservation R1
				  INNER JOIN person P ON R1.person_ID = P.person_ID
				  INNER JOIN rental R2 ON R1.reservation_ID = R2.reservation_ID
				  INNER JOIN bicycle B ON R2.bicycle_ID = B.bicycle_ID
				  WHERE P.person_ID = ?";
	
	// query database for preliminary rental information
	$queryRentalResult = $connection->prepare($getRental);
	$queryRentalResult->execute(array($_GET['personID']));

	// find out how many entries in the rental data structure are associated with the specified person_ID by calling the fetchColumn method... 
	// note that there is only a single column in the query result, and that it tallies the number of instances of rental_ID for the person_ID specified 
	// if that tally > 0, query the database for additional information about the rentals; else, alert user that there isn't any rental information for that person at this time
	if ($queryRentalResult->fetchColumn()>0) {
	
		// specify SQL SELECT query for rentals
		$getRental = "SELECT P.person_ID, P.last_name, P.first_name, R1.reservation_date, B.bicycle_description, COUNT(R2.rental_ID) AS number_rented
					  FROM reservation R1
					  INNER JOIN person P ON R1.person_ID = P.person_ID
					  INNER JOIN rental R2 ON R1.reservation_ID = R2.reservation_ID
					  INNER JOIN bicycle B ON R2.bicycle_ID = B.bicycle_ID
					  WHERE P.person_ID = ?
					  GROUP BY person_ID, last_name, first_name, reservation_date, bicycle_description
					  ORDER BY reservation_date, bicycle_description";

		// query database for rentals
		$queryRentalResult = $connection->prepare($getRental);
		$queryRentalResult->execute(array($_GET['personID']));


			echo
				"<h3>Bicycle Rental History</h3>" .
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
						"<td>" . $rentalRow['last_name'] . ", " . $rentalRow['first_name'] . "</td>" .
						"<td>" . $rentalRow['reservation_date'] . "</td>" .
						"<td>" . $rentalRow['bicycle_description'] . "</td>" .
						"<td>" . $rentalRow['number_rented'] . "</td>" .
					"</tr>" ;
			}

			echo
				"</table>" .
				"<br /><br />";
			
			unset($_GET['personID']);

			echo
				"<form action='rentals_guest_parameter.php' method='get'>" .
				"<input type='submit' name='reset' value='Reset'></input>" .
				"</form>";	
	}else{
		echo "<h3>There isn't any rental information at this time.</h3><br /><br />";
		
		unset($_GET['personID']);

		echo
			"<form method='get' action='rentals_guest_parameter.php'>" .
			"<input type='submit' name='reset' value='Reset'></input>" .
			"</form>";	
		
	}
	
}
?>

<?php
require "html_end.php";
?>
