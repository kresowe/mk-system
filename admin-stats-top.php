<?php
session_start();
require_once("classes/TopStats.php");

?>

<html>
<head>
	<title>Maratony Kresowe - TOP wszech czasów</title>
	<?php include("templates/head.tpl"); ?>
</head>

<body>
<?php 
include('templates/header.tpl'); 
?>

<h2>Maratony Kresowe - TOP wszech czasów</h2>


<?php
// $limit = ini_get('memory_limit');
// 		echo "<p>Limit = " . $limit . "</p>";
if (isset($_SESSION["user"]) && $_SESSION["role"] == 3){
	if (isset($_GET['min_id']) && intval($_GET['min_id'])%200 == 0) {
		// ini_set('memory_limit', 256);
		$top_stats = new TopStatsCreate();
		$top_stats->saveAllRidersIds($_GET['min_id']);
		$top_stats->saveAllRidersStats();
		$top_stats->insertAllRidersStats();
	} else if (isset($_GET['race_id']) && isset($_GET['type_id'])) {
		$top_st_update = new TopStatsUpdate($_GET['race_id'], $_GET['type_id']);
		$top_st_update->updateTopStats();
		echo "<p>Zakończono :)</p>";
	} else {
		echo "<p>Aby uaktualnić: dopisz do adresu URL '?race_id=N&type_id=k' gdzie N - numer wyścigu w " .
			"bazie danych, k - numer dystansu (maraton/ półmaraton)...</p>";
		echo "<p>Aby wygenerować statystykę od zera: Dopisz do adresu URL '?min_id=N' " .
			"gdzie N będzie podzielne przez 200, " .
			"np. '?min_id=200'.</p>";
	}
} else {
	echo "<p>Nie masz uprawnień do wykonania tego zadania. Musisz być zalogowany jako administrator.</p>";
}

include("templates/footer.tpl");
?>

</body>
</html>