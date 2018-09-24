<?php 
$path_to_file = __DIR__;
require_once($path_to_file . "/../classes/ValidateForm.php");
require_once($path_to_file . "/../classes/Search.php");
require_once($path_to_file . "/../classes/Cyclist.php");
require_once($path_to_file . "/../classes/Payment.php");
require_once($path_to_file . "/../classes/MailUtil.php");
require_once($path_to_file . '/../const_definitions.php');
$action = 'index.php?page=apply_friend_for_marathon';
$action_apply_form = "index.php?page=apply_friend_for_marathon&cyclist=" . 
		$_GET['cyclist'];
$action_apply_form2 =  "index.php?page=apply_friend_for_marathon&cyclist=" . 
		$_GET['cyclist'];


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	ValidateForm::validateTextNotRequired($surname, $err_surname, $accepted, 'surname');
	ValidateForm::validateStartingNo($starting_no, $err_starting_no, $accepted);
} else if (!isset($_GET['cyclist'])) {
	?>

	<h3>Znajdź zawodnika, którego chcesz zgłosić </h3>
	<h3>Find a rider which you want to apply.</h3>
	<h4>Wpisz jedną lub więcej danych, aby wyszukać zawodnika.</h4>
	<h4>Fill in one or more fields to search for cyclist.</h4>
	<?php
	include($path_to_file . "/../forms/search_cyclist_form.tpl");
}

if ($accepted && !empty($_POST['submit_search_cyclist'])) {
	$cyclists_found = Search::findCyclists(array(
			'surname' => $surname,
			'starting_no' => $starting_no
		));
?>
	<h3>Wybierz zawodnika do zgłoszenia</h3>
	<h3>Choose a rider to apply</h3>
<?php
	$link_in_cycl_found = "index.php?page=apply_friend_for_marathon&cyclist=";
	$button_text = "Wybierz - Choose";

	include($path_to_file . "/../templates/cyclists_found.tpl");
}

if (isset($_GET['cyclist'])){
	$cyclist = new Cyclist();
	$cyclist->setId($_GET['cyclist']);
	$cyclist->getDataFromDB($_GET['cyclist']);
	// test
	?>
	<h4>Zgłaszany zawodnik - rider to apply</h4>
	<p>Zawodnik - rider: <strong><?php echo $cyclist->getName() . " " . $cyclist->getSurname(); ?> </strong></p>
	<p>Rocznik - birth year: <strong><?php echo $cyclist->getBirthyear(); ?> </strong></p>
	<p>Drużyna - team: <strong><?php echo $cyclist->getTeam(); ?> </strong></p>
	<p>Numer startowy - starting number: <strong><?php echo $cyclist->getStartNumber(); ?> </strong></p>

	<?php
	// echo "<p>" . $cyclist->getSurname() . "</p>";
	// echo "<p>" . $cyclist->getId() . "</p>";

	// echo "<p>Cycki</p>";

	// validate forms
	if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_apply_1'])){
		$marathon = $_POST['marathon'];
		$_SESSION['apply_marathon'] = $marathon;
		//echo "<p>" . $marathon . "</p>"; //test
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
	}

	if (empty($_POST['submit_apply_1']) && empty($_POST['submit_apply_2'])) {
		include(__DIR__ . "/../forms/apply_form.tpl");
	}
	else if (empty($_POST['submit_apply_2'])) {
		$start_number = $cyclist->getStartNumber();
		if ($start_number) {
			$_SESSION['apply_start_number'] = $start_number;
		}
		include(__DIR__ . "/../forms/apply_form2.tpl");
	}
	else if ((!$accepted || empty($distance)) && empty($_POST['submit_apply_1'])){
		$start_number = $cyclist->getStartNumber();
		if ($start_number) {
			$_SESSION['apply_start_number'] = $start_number;
		}
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
					$cyclist->getBirthyear() ,
					$_SESSION['apply_license'
					]
				);
				
				$cyclist_logged = new Cyclist();
				$cyclist_logged->setLogin($_SESSION['user']);
				$cyclist_logged->getDataFromDB_LoggedUser();
				echo "<p>Jako potwierdzenie wysłania zgłoszenia otrzymasz mail. </p>";
				echo "<p>You will receive an email as a confirmation.</p>";
				MailUtil::applyOrgMail($application_form_inputs, $cyclist_logged);
				MailUtil::applyCyclistMail($application_form_inputs, $cyclist_logged);

			} else {
				?>
				<h4>Wystąpił błąd przy zapisywaniu zgłoszenia. Przepraszamy. Spróbuj jeszcze raz później!</h4>
				<h4>Error! Please try again later!</h4>
		<?php
			}
		}
	}
}

?>