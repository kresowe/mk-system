<?php
require_once(__DIR__ . "/../classes/Payment.php");

echo Payment::countPayment(
		$options['marathon'], 
		$options['payment'], 
		$options['distance'],
		$cyclist->getCountry(),
		$cyclist->getBirthyear(),
		$options['license']
	);

?>