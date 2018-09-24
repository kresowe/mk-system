<?php


if (!isset($_GET['page']))
{
?>
	<h3>Witaj w Panelu zawodnika!/ Welcome to!</h3>
	<h4>Wybierz opcję z menu po lewej stronie.</h4>
<?php
}
else 
{
	$page->setContent($_GET['page']);
	$page->getContent();
}


?>
