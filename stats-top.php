<?php
session_start();
require_once("classes/TopStats.php");

function topStatsPrepare($column){
	$results = TopStatsPresent::getTopOnColumn($column);
	include("templates/top-stats-table.tpl");
}

?>

<html>
<head>
	<title>Maratony Kresowe - TOP wszech czasów</title>
	<?php include("templates/head.tpl"); ?>
</head>

<body>
<header class="mk_margin_bottom">
<h1>System Maratonów Kresowych</h1>
</header>
<div class="container-fluid">
<a href="index.php" class="mk_btn_link">
<button type="button" class="btn btn-primary btn-md">Powrót na główną - Back to home</button>
</a>

<h2>Maratony Kresowe - TOP wszech czasów</h2>

<h3>Najwięcej ukończonych wyścigów - Most finished races</h3>
<?php
topStatsPrepare('races');
?>

<h3>Najwięcej przejechanych kilometrów na wyścigach - Most race kilometers ridden</h3>
<?php 
topStatsPrepare('kms');
?>

<h3>Najdłużej w siodle - The longest time in a saddle</h3>
<?php 
topStatsPrepare('time_sum');

include("templates/footer.tpl");
?>

</body>
</html>