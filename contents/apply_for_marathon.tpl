<h3>Zgłoszenie na maraton</h3>
<?php
require_once(__DIR__ . "/../classes/Cyclist.php");
require_once(__DIR__ . "/../classes/DBGetter.php");
require_once(__DIR__ . "/../classes/Payment.php");
require_once(__DIR__ . "/../classes/MailUtil.php");
require("const_vars_apply.php");

$cyclist = new Cyclist();
$cyclist->setLogin($_SESSION['user']);

$cyclist->getDataFromDB_LoggedUser();

//walidacja
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_apply_1'])){
	$marathon = $_POST['marathon'];
	$_SESSION['apply_marathon'] = $marathon;
	//echo "<p>" . $marathon . "</p>"; //test
	//include(__DIR__ . "/../forms/apply_form2.tpl");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_apply_2'])){
	$marathon = $_SESSION['apply_marathon'];
	ValidateForm::validateDistance($distance, $err_distance, $accepted);
	ValidateForm::validatePayment($payment, $err_payment, $accepted);
	// ValidateForm::validateLicensePosession($license, $err_license, $accepted);
	ValidateForm::validateNotes($notes, $err_notes, $accepted);
	ValidateForm::validateStartingNo($note_start_number, $err_note_start_number, $accepted);
	ValidateForm::validateOldEnough($distance, $err_distance, $accepted, 
		$cyclist->getBirthyear(), $cyclist->getSex());
	ValidateForm::validateAcceptRules($accept_rules, $err_accept_rules, $accepted);
	$_SESSION['apply_distance'] = $distance;
	$_SESSION['apply_payment'] = $payment;
	// $_SESSION['apply_license'] = $license;
	$_SESSION['apply_notes'] = $notes;
	$_SESSION['apply_note_start_number'] = $note_start_number;
	//echo "<p>" . $distance . "</p>"; //test
	//include(__DIR__ . "/../forms/apply_form2.tpl");
}

//co wyswietlamy
if (empty($_POST['submit_apply_1']) && empty($_POST['submit_apply_2'])) {
	$action_apply_form = "index.php?page=apply_for_marathon";
	include(__DIR__ . "/../forms/apply_form.tpl");
}
else if (empty($_POST['submit_apply_2'])) {
	$start_number = $cyclist->getStartNumber();
	if ($start_number) {
		$_SESSION['apply_start_number'] = $start_number;
	}
	$action_apply_form2 = "index.php?page=apply_for_marathon";
	include(__DIR__ . "/../forms/apply_form2.tpl");
}
else if ((!$accepted || empty($distance)) && empty($_POST['submit_apply_1'])){
	$start_number = $cyclist->getStartNumber();
	if ($start_number) {
		$_SESSION['apply_start_number'] = $start_number;
	}
	$action_apply_form2 = "index.php?page=apply_for_marathon";
	include(__DIR__ . "/../forms/apply_form2.tpl");
}
else if ($accepted && !empty($_POST['submit_apply_2'])){
	// test
	// echo "<p>" . $_SESSION['apply_marathon'] . "," . $_SESSION['apply_distance'] . "," .  $_SESSION['apply_payment'] . "," . $_SESSION['apply_notes'] . "," . $_SESSION['apply_note_start_number'] . "," . $cyclist->getSurname() . "</p>";
	$notes_from_form = $_SESSION['apply_notes'];
	if (isset($_SESSION['apply_note_start_number']) && !empty($_SESSION['apply_note_start_number'])) {
		$notes_from_form .= ' |nr: ' . $_SESSION['apply_note_start_number'];
	}

	$application_form_inputs = array(
		'marathon' => $_SESSION['apply_marathon'],
		'distance' => $_SESSION['apply_distance'],
		'payment' => $_SESSION['apply_payment'],
		// 'license' => $_SESSION['apply_license'],
		'notes' => $notes_from_form
		);

	// var_dump($application_form_inputs);


	if ($cyclist->hasAppliedForMarathon($application_form_inputs)){
		?>
		<h4>Jesteś już zgłoszony na ten maraton! Nie możesz się zgłosić ponownie.</h4>
		<h5>You are already registered for this marathon! You cannot apply again.</h4>
		<p>W razie potrzeby proszę napisać maila na adres: 
			<a href="mailto:rejestracja@maratonykresowe.pl">rejestracja@maratonykresowe.pl</a>
		</p>
		<p>If needed, please write an email to us: 
			<a href="mailto:rejestracja@maratonykresowe.pl">rejestracja@maratonykresowe.pl</a>
		</p>
		<?php
	}
	else {
		$cyclist->printApplicationIntoCsv($application_form_inputs);
		if ($cyclist->applyForMarathon($application_form_inputs)) {
			?>
			<h4>Twoje zgłoszenie na maraton zostało przyjęte!</h4>
			<h5>We received your application for marathon!</h5>
			<?php
			Payment::countPayment(
				$_SESSION['apply_marathon'], 
				$_SESSION['apply_payment'], 
				$_SESSION['apply_distance'],
				$cyclist->getCountry(),
				$cyclist->getBirthyear(),
				$_SESSION['apply_license']
			);
			
			echo "<p>Jako potwierdzenie wysłania zgłoszenia otrzymasz mail. </p>";
			echo "<p>You will receive an email as a confirmation.</p>";
			MailUtil::applyOrgMail($application_form_inputs, $cyclist);
			MailUtil::applyCyclistMail($application_form_inputs, $cyclist);

		} else {
			?>
			<h4>Wystąpił błąd przy zapisywaniu zgłoszenia. Przepraszamy. Spróbuj jeszcze raz później!</h4>
			<h4>Error! Please try again later!</h4>
	<?php
		}
	}


}
?>
<!--
<h4>Twoje dane: </h4>
<?php
$cyclist->output();
?>
-->