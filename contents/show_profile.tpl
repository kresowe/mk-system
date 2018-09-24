<h3>Profil zawodnika</h3>
<?php
require_once(__DIR__ . "/../classes/Cyclist.php");
require_once(__DIR__ . "/../classes/DBGetter.php");

$cyclist = new Cyclist();
$cyclist->setLogin($_SESSION['user']);
$cyclist->getDataFromDB_LoggedUser();

$cyclist->showCyclistProfile();
?>