<?php
function show_data($row){
	echo "<p>Imię: " . stripslashes($row['name']) . "</p>";
	echo "<p>Nazwisko: " . stripslashes($row['surname']) . "</p>";
	echo "<p>Rok urodzenia: " . stripslashes($row['birthyear']) . "</p>";
	echo "<p>Płeć: " . stripslashes($row['sex']) . "</p>";
	echo "<p>Licencja: " . stripslashes($row['license']) . "</p>";
	echo "<p>Kraj: " . stripslashes($row['country_iso3']) . "</p>";
	echo "<p>Kod pocztowy: " . stripslashes($row['zip_code']) . "</p>";
	echo "<p>Miejscowość: " . stripslashes($row['city']) . "</p>";
	echo "<p>Email: " . stripslashes($row['email']) . "</p>";
	echo "<p>Telefon: " . stripslashes($row['phone']) . "</p>";
}

?>