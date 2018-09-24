<?php
session_start();

include("const_definitions.php");
require_once("classes/DBGetter.php");
require_once("classes/MKSystemUtil.php");
require_once("classes/ValidateForm.php");
require_once("classes/MailUtil.php");
require_once("contents/const_vars_apply.php");



if (isset($_SESSION['user'])) {
	//if logged in then redirect to home.
	header("Location:index.php");
} else {
?>

	<html>
	<head>
		<title>Maratony Kresowe - zgłoszenie bez logowania - apply without login</title>
		<?php include("templates/head.tpl"); ?>
	</head>

	<body>
<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		//validate form
		$marathon = $_POST['marathon'];
		$_SESSION['apply_marathon'] = $marathon;
		ValidateForm::validateName($name, $err_name, $accepted_apply_no_login);
		ValidateForm::validateSurname($surname, $err_surname, $accepted_apply_no_login);
		ValidateForm::validateSex($sex, $err_sex, $accepted_apply_no_login);
		ValidateForm::validateBirthyear($birthyear, $err_birthyear, $accepted_apply_no_login);
		ValidateForm::validateStartingNo($starting_no, $err_starting_no, $accepted_apply_no_login);
		ValidateForm::validateCountry($country, $err_country, $accepted_apply_no_login);
		ValidateForm::validateZipCode($zip_code, $err_zip_code, $accepted_apply_no_login);
		ValidateForm::validateTown($town, $err_town, $accepted_apply_no_login);
		ValidateForm::validateCreateTeam($create_team, $err_create_team, $accepted_apply_no_login);
		if ($create_team){
			ValidateForm::validateNewTeam($new_team, $err_new_team, $accepted_apply_no_login);
			if ($accepted_apply_no_login){
				$team = $new_team;
			}
		}
		else {
			ValidateForm::validateTeam($team, $err_team, $accepted_apply_no_login);
		}

		//ValidateForm::validateLicensePosession($license, $err_license, $accepted_apply_no_login);
		// ValidateForm::validateLicenseType($license_type, $err_license_type, $accepted); ///
		// ValidateForm::validateLicense($license, $err_license, $accepted); ///
		ValidateForm::validateDistance($distance, $err_distance, $accepted_apply_no_login);
		ValidateForm::validateEmail($login, $err_login, $accepted_apply_no_login);
		ValidateForm::validatePhone($phone, $err_phone, $accepted_apply_no_login);
		ValidateForm::validatePayment($payment, $err_payment, $accepted_apply_no_login);
		ValidateForm::validateNotes($notes, $err_notes, $accepted_apply_no_login);
		ValidateForm::validateOldEnough($distance, $err_distance, $accepted_apply_no_login, 
			$birthyear, $sex);
		ValidateForm::validateAcceptRules($accept_rules, $err_accept_rules, $accepted_apply_no_login);

		if ($marathon == 134 && $distance != 2 && $distance != 3){
			$err_distance = 'W Mistrzostwach Polski XCT w Chełmie można wybrać tylko ';
			$err_distance .= 'Maraton albo Mini. <br />';
			$err_distance .= 'For this race (XCT Poland Championships) you can only choose ';
			$err_distance .= 'Maraton or Mini.';
			$accepted_apply_no_login = false;
		}
	}	
?>
	<header class="mk_margin_bottom">
	<h1>System Maratonów Kresowych</h1>
	</header>
	<div class="container-fluid">
	<a href="index.php" class="mk_btn_link">
	<button type="button" class="btn btn-primary btn-md">Powrót na główną - Back to home</button>
	</a>
	<h2>Zgłoszenie na maraton bez logowania - Apply for marathon without login</h2>
<?php 
	$db = DBGetter::connectDB();

	if (!$accepted_apply_no_login || empty($_POST['submit_apply_no_login'])) {
		// render a form
		$allTeams = DBGetter::getTeams();
		$marathons = DBGetter::getAvailableMarathons();
		// $marathons = DBGetter::getFutureMarathons();
		$race_types = DBGetter::getRaceTypes();
		$payments = DBGetter::getPaymentTypes();
		include("forms/apply_no_login_form.tpl");
	} else {
		if ($marathon == ''){
			echo "<h3>Nie można się w tej chwili zapisać na żaden maraton. Spróbuj później!</h3>";
			echo "<h3>Currently it's not possible to apply for any marathon. Try again later!</h3>";
		}
		else {
		$notes1 = $notes;
		if(!empty($starting_no)) {
			$notes1 = html_entity_decode($notes1);
			$notes1 = str_replace(';', ':', $notes1);
			$notes1 = htmlentities($notes1);
			$notes1 = $notes1 . '| nr: ' . $starting_no;
		}
			$application_form = array(array(
				'',
				$surname,
				$name,
				$sex,
				$birthyear,
				'',
				$country,
				$zip_code,
				$town,
				DBGetter::getPaymentName($payment),
				$team,
				$notes1,
				DBGetter::getDistanceName($distance),
				$login,
				$phone//,
				// $license_type, 
				// $license, 
			));
			try {
				MKSystemUtil::printApplicationToCsvNoLogin($application_form, 
					DBGetter::getRaceById($marathon));

			?>
				<h4>Twoje zgłoszenie na maraton zostało przyjęte!</h4>
				<h5>We received your application for marathon!</h5>
			<?php
				Payment::countPayment($marathon, $payment, $distance, $country, $birthyear, $license);
				echo "<p>Jako potwierdzenie wysłania zgłoszenia otrzymasz mail. </p>";
				echo "<p>You will receive an email as a confirmation.</p>";

				$application_for_mail = array(
					'starting_no' => $starting_no,
					'surname' => $surname,
					'name' => $name,
					'sex' => $sex,
					'birthyear' => $birthyear,
					'country' => $country,
					'zip_code' => $zip_code,
					'town' => $town,
					'payment' => $payment,
					'team' => $team,
					'license' => $license,
					'notes' => $notes,
					'distance' => $distance,
					'mail' => $login,
					'phone' => $phone,
					'marathon' => $marathon
				);

				MailUtil::applyNoLoginOrgMail($application_for_mail);
				MailUtil::applyNoLoginCyclistMail($application_for_mail);


			} catch (Exception $e) {
				echo "<p>Wystąpił błąd przy zapisie! Przepraszamy. Spróbuj jeszcze raz później.</p>";
				echo "<p>Error occured when saving application! Please, try again later.</p>";
			}
		}

	}

} //end: else
include("templates/footer.tpl");
?>