<?php
require_once(__DIR__ . "/../classes/Payment.php");

echo Payment::countPayment(
		$application_form['marathon'], 
		$application_form['payment'], 
		$application_form['distance'],
		$application_form['country'],
		$application_form['birthyear'],
		$application_form['license']
	);

?>