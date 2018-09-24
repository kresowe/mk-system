<?php
session_start();

require_once("const_definitions.php");
require_once("classes/DBGetter.php");
require_once("classes/MKSystemUtil.php");
require_once("classes/ValidateForm.php");
require_once("classes/MailUtil.php");
require_once("contents/const_vars_apply.php");


?>

<html>
<head>
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Maratony Kresowe - zgłoszenie na przełaje - apply for cyclocross</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/my_styles.css" rel="stylesheet">
	<script src="js/my_custom_functions.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>

<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//validate form
	$marathon = $_POST['marathon'];
	$_SESSION['apply_marathon'] = $marathon;
	ValidateForm::validateName($name, $err_name, $accepted);
	ValidateForm::validateSurname($surname, $err_surname, $accepted);
	ValidateForm::validateSex($sex, $err_sex, $accepted);
	ValidateForm::validateBirthyear($birthyear, $err_birthyear, $accepted);
	ValidateForm::validateLicenseType($license_type, $err_license_type, $accepted);
	ValidateForm::validateLicense($license, $err_license, $accepted);
	ValidateForm::validateCountry($country, $err_country, $accepted);
	ValidateForm::validateProvince($province, $err_province, $accepted);
	ValidateForm::validateZipCode($zip_code, $err_zip_code, $accepted);
	ValidateForm::validateTown($town, $err_town, $accepted);
	ValidateForm::validateTeam($team, $err_team, $accepted);
	ValidateForm::validateEmail($login, $err_login, $accepted);
	ValidateForm::validateNotes($notes, $err_notes, $accepted);
	ValidateForm::validateAcceptRules($accept_rules, $err_accept_rules, $accepted);
}	
?>
<header class="mk_margin_bottom">
<h1>System Maratonów Kresowych</h1>
</header>
<div class="container-fluid">
<a href="index.php" class="mk_btn_link">
<button type="button" class="btn btn-primary btn-md">Powrót na główną - Back to home</button>
</a>
<h2>Mistrzostwa Województwa Podlaskiego w Kolarstwie Przełajowym - Białystok 06.11.2016</h2>
<h2>Podlaskie Voivodeship Cyclocross Championships - Białystok 06.11.2016</h2>
<?php 
$db = DBGetter::connectDB();

if (!$accepted || empty($_POST['submit_cyclocross'])) {
	// render a form
	
	include("forms/cyclocross_form.tpl");
} else {
	$marathon = array(
		'date' => '2016-11-06',
		'place' => 'Przelaje-Bialystok'
		);
	$application_form = array(array(
		$surname,
		$name,
		$sex,
		$birthyear,
		$license_type,
		$license,
		$country,
		$province,
		$zip_code,
		$town,
		$team,
		$notes,
		$login
	));
	try {
		MKSystemUtil::printApplicationToCsvNoLogin($application_form, $marathon);

	?>
		<h4>Twoje zgłoszenie na maraton zostało przyjęte!</h4>
		<h5>We received your application for marathon!</h5>
	<?php
		echo "<p>Jako potwierdzenie wysłania zgłoszenia otrzymasz mail. </p>";
		echo "<p>You will receive an email as a confirmation.</p>";

		$application_for_mail = array(
			'surname' => $surname,
			'name' => $name,
			'sex' => $sex,
			'birthyear' => $birthyear,
			'license_type' => $license_type,
			'license' => $license,
			'country' => $country,
			'province' => $province,
			'zip_code' => $zip_code,
			'town' => $town,
			'team' => $team,
			'notes' => $notes,
			'mail' => $login,
			'marathon' => $marathon
		);

		MailUtil::applyCyclocrossOrgMail($application_for_mail);
		MailUtil::applyCyclocrossCyclistMail($application_for_mail);


	} catch (Exception $e) {
		echo "<p>Wystąpił błąd przy zapisie! Przepraszamy. Spróbuj jeszcze raz później.</p>";
		echo "<p>Error occured when saving application! Please, try again later.</p>";
	}
	

}


include("templates/footer.tpl");
?>