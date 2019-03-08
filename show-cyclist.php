<?php
session_start();

require_once("classes/Cyclist.php");
require_once("classes/Results.php");
?>

<html>
<head>
	<title>Maratony Kresowe - znajdź zawodnika</title>
	<?php include("templates/head.tpl"); ?>
</head>

<body>

<?php
include('templates/header.tpl');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['cyclist'])){
	$id = $_GET['cyclist'];

?>


	<h2>Info o zawodniku</h2>
	<?php

	$cyclist_to_show = new Cyclist();

	$cyclist_to_show->getDataFromDB($id);
	$cyclist_to_show->setId($id);

	$a_cyclist_results = new Results();
	$a_cyclist_results->saveCyclistResults($cyclist_to_show);
	$a_cyclist_results->saveRaceResults($cyclist_to_show->getSex());
	$a_cyclist_results->saveCyclistResultsNice();
	$a_cyclist_results->saveTotalStats($cyclist_to_show);
	$results = $a_cyclist_results->getCyclistResultsNice();
	
	include("templates/cyclist_info.tpl");
	?>
	<h2>Wyniki zawodnika</h2>
	<?php
	

	include("templates/cyclist_results.tpl");

}
include("templates/footer.tpl");
?>

</body>
</html>