<?php
$action = '';
if (isset($_SESSION["user"])){
	$action = 'index.php?page=search_cyclist';
	require_once('const_definitions.php');
	require_once('classes/Cyclist.php');
} else {
	$action = 'search-cyclist.php';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	ValidateForm::validateTextNotRequired($surname, $err_surname, $accepted, 'surname');
	ValidateForm::validateStartingNo($starting_no, $err_starting_no, $accepted);
}
?>
<h3>Wpisz jedną lub więcej danych, aby wyszukać zawodnika.</h3>
<h3>Fill in one or more fields to search for cyclist.</h3>

<?php
include($path_to_file . "forms/search_cyclist_form.tpl");


if ($accepted && !empty($_POST['submit_search_cyclist'])) {
	$cyclists_found = Search::findCyclists(array(
			'surname' => $surname,
			'starting_no' => $starting_no
		));

	$link_in_cycl_found = "show-cyclist.php?cyclist=";
	$button_text = "Pokaż - Show";

	include($path_to_file . "templates/cyclists_found.tpl");
}
?>