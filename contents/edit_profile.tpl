<h3>Edycja danych</h3>
<?php
require_once(__DIR__ . "/../classes/Cyclist.php");
require_once(__DIR__ . "/../classes/DBGetter.php");
require_once(__DIR__ . "/../const_definitions.php");

$cyclist = new Cyclist();
if (!$cyclist->setLogin($_SESSION['user'])) {
	echo "<h2>Wystąpił błąd!/ Error occured!</h2>";
	exit;
}
$cyclist->getDataFromDB_LoggedUser();
$teamName = $cyclist->getTeam();
$allTeams = DBGetter::getTeams();


//walidacja formularza
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$sent_edit_form = true;
	ValidateForm::validateLicense($license, $err_license, $accepted);
	ValidateForm::validateCountry($country, $err_country, $accepted);
	ValidateForm::validateProvince($province, $err_province, $accepted);
	ValidateForm::validateZipCode($zip_code, $err_zip_code, $accepted);
	ValidateForm::validateTown($town, $err_town, $accepted);
	ValidateForm::validatePhone($phone, $err_phone, $accepted);
	ValidateForm::validateCreateTeam($create_team, $err_create_team, $accepted);
	if ($create_team){
		ValidateForm::validateNewTeam($new_team, $err_new_team, $accepted);
	}
	else {
		ValidateForm::validateTeam($team, $err_team, $accepted);
	}
}

//jesli formularz wyslany i poprawnie wypelniony
if ($accepted && $sent_edit_form){
	$cyclist->update('license', $license);
	$cyclist->update('country_iso3', $country);
	$cyclist->update('province', $province);
	$cyclist->update('zip_code', $zip_code);
	$cyclist->update('city', $town);
	$cyclist->update('phone', $phone);
	if ($create_team){
		try {
			DBGetter::createTeam($new_team);
			$cyclist->updateTeam($new_team);
		}
		catch (Exception $e){
			echo "<p>" . $e->getMessage() . "</p>";
		}
	}
	else {
		if ($team != $cyclist->getTeam()){
			if (!$cyclist->updateTeam($team)) {
				echo "<p>Nie udało się zaktualizować przypisania do drużyny!</p>";
			}
		}
	}
	
	echo "<h4>Zmiany będą widoczne po zaakceptowaniu przez admina</h4>";
	echo "<h4>Changes will be active after being accepted by admin.</h4>";
}
else {
	include(__DIR__ . "/../forms/edit_profile_form.tpl");
}
?>
