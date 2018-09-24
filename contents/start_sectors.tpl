<?php
require_once("classes/StartSectors.php");

function startSectorsPrepare($type){
	$results = StartSectors::getStartSectorTable($type);
	include("templates/start-sectors-table.tpl");
}
?>

<h2>Maratony Kresowe - Sektory startowe - start sectors</h2>

<h4>Wybierz dystans - Choose distance</h3>
<h3><a href="#polmaraton">Półmaraton</a>  <a href="#maraton">Maraton</a></h3>

<h3 id="polmaraton">Półmaraton</h3>
<?php
startSectorsPrepare(4);
?>
<h4> Pozostali zawodnicy: sektor 4.</h4>
<h4> Other riders: sector 4.</h4>

<h3 id="maraton">Maraton</h3>
<?php
startSectorsPrepare(2);
?>
<h4> Pozostali zawodnicy: sektor 4.</h4>
<h4> Other riders: sector 4.</h4>
