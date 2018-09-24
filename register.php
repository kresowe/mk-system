<?php
/*
Główny skrypt decydujący o sterowaniu rejestracją.
Uwaga: zawiera aż 3 różne formularze, które mają się wyświetlać w stosownych momentach.
*/
session_start();
// $_SESSION['mode'] = 'online';
$_SESSION['mode'] = 'offline';

if ($_SESSION['mode'] == 'offline') {
header('Location: index.php');
}

include(__DIR__ . "/../../mk_dane/secret.php"); //powinno byc poza public_html z powodow bezpieczenstwa
include("const_definitions.php");
include("classes/ValidateForm.php");
require_once("classes/Cyclist.php");
// require_once("libs/RainCaptcha-1.1.0.phps");

// $rainCaptcha = new \RainCaptcha();

?>
<html>
<head>
	<title>Maratony Kresowe - rejestracja</title>
	<?php include("templates/head.tpl"); ?>
</head>
<body>
<?php
//zalogowany nie moze sie rejestrowac!
if (isset($_SESSION["user"]) && isset($_SESSION["role"]))
{
	echo "<p>Jesteś już zalogowany. Aby utworzyć konto dla innego zawodnika, musisz się 
	<a href=\"wyloguj.php\" > wylogować </a></p>";
	exit;
}

//walidacja formularza próbującego zidentyfikować zawodnika.
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_find_user2'])) 
{
	ValidateForm::validateName($name, $err_name, $accepted);
	ValidateForm::validateSurname($surname, $err_surname, $accepted);
	ValidateForm::validateBirthyear($birthyear, $err_birthyear, $accepted);

}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_new_in_mk'])) 
{
	ValidateForm::validateNewInMk($new_in_mk, $err_new_in_mk, $accepted_new_in_mk);
}

/*walidacja formularza 2 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_create_account'])) 
{
	ValidateForm::validateLogin($login, $err_login, $accepted_account_form);
	ValidateForm::validatePassword($pass, $err_pass, $accepted_account_form);
	ValidateForm::validatePassword2($pass2, $err_pass2, $accepted_account_form, $pass);
	// ValidateForm::validateReCaptcha($err_recaptcha, $accepted_account_form);
}

/*walidacja formularza 3 */
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['submit_personal_data'])) 
{
	ValidateForm::validateName($name, $err_name, $accepted_personal_form);
	ValidateForm::validateSurname($surname, $err_surname, $accepted_personal_form);
	ValidateForm::validateBirthyear($birthyear, $err_birthyear, $accepted_personal_form);
	ValidateForm::validateSex($sex, $err_sex, $accepted_personal_form);
	//ValidateForm::validateStartingNo($starting_no, $err_starting_no, $accepted_personal_form);
	ValidateForm::validateCountry($country, $err_country, $accepted_personal_form);
	ValidateForm::validateZipCode($zip_code, $err_zip_code, $accepted_personal_form);
	ValidateForm::validateTown($town, $err_town, $accepted_personal_form);
	ValidateForm::validatePhone($phone, $err_phone, $accepted_personal_form);
	ValidateForm::validateLicense($license, $err_license, $accepted_personal_form);
	ValidateForm::validateLogin($login, $err_login, $accepted_personal_form);
	ValidateForm::validatePassword($pass, $err_pass, $accepted_personal_form);
	ValidateForm::validatePassword2($pass2, $err_pass2, $accepted_personal_form, $pass);
	// ValidateForm::validateReCaptcha($err_recaptcha, $accepted_personal_form);
}

?>
<h1>System Maratonów Kresowych</h1>
	<h3>Rejestracja/ Registration</h3>

<?php
//polaczenie z baza danych
$db = new mysqli($host, $db_user, $db_pass, $db_name);
// echo "<p>" . $host . ", " . $db_user . ", " . $db_pass . ", " . $db_name . "</p>";
if (mysqli_connect_errno()) 
	echo "<p>Nie można połączyć z bazą danych: " . mysql_connect_error() . "| Error</p>";
	
$db->query("SET NAMES 'utf8';");
$db->query("SET CHARACTER SET 'utf8';");

$cyclist = new Cyclist();

//etap 1: uzytkownik nie wypelnil poprawnie formularza 1 => wyswietlamy mu go
//Formularz 1 ma sie pokazywac, jesli nie zostal zaakceptowany lub wypelniony 
// i jesli nie zostal submitowany inny formularz.
if ((!$accepted || empty($name)) && empty($_POST['submit_create_account']) && empty($_POST['submit_personal_data']) 
	&& empty($_POST['submit_new_in_mk'])) {
	include("forms/register_form.tpl");
}
else if ($accepted && empty($_POST['submit_create_account']) && empty($_POST['submit_personal_data'])
	&& empty($_POST['submit_new_in_mk']))
{ //etap 2: uzytkownik tylko co wypelnil poprawnie formularz 1
	$sql_query = "SELECT * FROM users2 WHERE name = '$name' AND surname = '$surname' AND birthyear = '$birthyear';";
//	echo "<p>". $sql_query . "</p>";
	$result = $db->query($sql_query);
	$found_number = $result->num_rows;
//	echo "<p>". $found_number . "</p>";

	if ($found_number == 1) //przypadek: zawodnik jest w bazie
	{
		//sprawdz, czy nie ma juz konta dla tego zawodnik
		$row = $result->fetch_assoc();
		$cyclist->saveCyclistData($row);
		$_SESSION['cyclist'] = $row['id'];
		$sql_query_2 = "SELECT l.login FROM logins l, users2 u WHERE u.id = " . $_SESSION['cyclist'] .
			" AND u.email = l.login;";
		$result_2 = $db->query($sql_query_2);
		if ($result_2->num_rows > 0) { //istnieje konto
			$row_2 = $result_2->fetch_assoc();
			echo "<h4>Twój login/ Your login: <strong>" . $row_2['login'] . "</strong></h4>";
			echo "<h4><strong>Zmień login lub hasło</strong> albo <a href=\"/mk-system/\">zaloguj się/ log in</a></h4>";
		}
		else {
			echo "<h4>Wygląda na to, że startowałeś już u nas. Sprawdź poniższe dane i utwórz konto:</h4>";	
			echo "<h4>You've already started in Maratony Kresowe. Create an account:</h4>";	
			echo "<h4>Вы уже принимали участие в МК. Создать аккаунт:</h4>";
			echo "<h4>Pridėkite paskyrą</h4>";
		}

		
		$cyclist->output();
		include("forms/create_account_form.tpl");
		
	}
	else //przypadek: zawodnika nie ma w bazie
	{
		include("forms/new_in_mk_form.tpl");
	}	
}
else if (!$accepted_new_in_mk && empty($_POST['submit_create_account']) && empty($_POST['submit_personal_data'])){
	include("forms/new_in_mk_form.tpl");
}
else if ($accepted_new_in_mk && empty($_POST['submit_create_account']) && empty($_POST['submit_personal_data'])){
	// die("aaa");
    if ($new_in_mk == "yes"){
		echo "<h4>Nowy zawodnik - rejestracja/ New rider - registration/ новый велосипедист/ Naujas dviratininkas</h4>";
		// echo "<h4 class=\"text-danger\"><strong>Jeśli jednak już startowałeś, proszę spróbuj jeszcze raz wypełnić 
			// podstawowe dane <a href=\"register.php\">tutaj</a>.</strong></h4>";
		include("forms/personal_data_form.tpl");
	}
	else if ($new_in_mk == "no"){
		include("forms/register_form.tpl");
	}
}
else if ((!$accepted_account_form || empty($pass)) && empty($_POST['submit_find_user2'])  && empty($_POST['submit_personal_data'])) 
{ //etap 3: uzytkownik nie wypelnil poprawnie formularza 2
	$loaded = file_get_contents('data/cyclist_data.txt');
	echo "<h4>Wygląda na to, że startowałeś już u nas. Sprawdź poniższe dane i utwórz konto:</h4>";
	echo "<h4>You've already started in Maratony Kresowe. Create an account:</h4>";	
	echo "<h4>Вы уже принимали участие в МК. Создать аккаунт:</h4>";
	echo "<h4>Pridėkite paskyrą</h4>";
	$cyclist->getDataFromDB($_SESSION['cyclist']); //przy kazdorazowym ladowaniu strony musimy na nowo wczytac dane zawodnika, bo inaczej one gina
	$cyclist->output();
	include("forms/create_account_form.tpl");
}
else if ($accepted_account_form && empty($_POST['submit_find_user2']) && empty($_POST['submit_personal_data']))
{ //etap 4: uzytkownik tylko co poprawnie wypelnil formularz 2 => mamy gotowe konto dla istniejacego w bazie zawodnika!
	$cyclist->getDataFromDB($_SESSION['cyclist']);
	$cyclist->setLogin($login);
	$cyclist->setPass($pass); 
	//dodajemy login kolarza do bazy danych, do tabeli logins, ale jak active=false!
	$cyclist->insertLoginInto();
}
else if ((!$accepted_personal_form || empty($sex)) && empty($_POST['submit_find_user2'])  && empty($_POST['submit_create_account']))
{ //etap 3a: uzytkownik nie wypelnil poprawnie formularza dla nowych zawodnikow
	echo "<h4>Nowy zawodnik - rejestracja/ New rider - registration/ новый велосипедист/ Naujas dviratininkas</h4>";
		// echo "<h4>Jeśli to prawda, wypełnij poniższy formularz</h4>";
		// echo "<p>Jeśli jednak już startowałeś, proszę spróbuj jeszcze raz wypełnić 
		// 	podstawowe dane <a href=\"register.php\">tutaj</a>.</p>";
	include("forms/personal_data_form.tpl");
}
else if ($accepted_personal_form && empty($_POST['submit_find_user2']) && empty($_POST['submit_create_account']))
{
	$cyclist->setName($name);
	$cyclist->setSurname($surname);
	$cyclist->setBirthyear($birthyear);
	$cyclist->setLogin($login);
	$cyclist->setPass($pass);
	$cyclist->setSex($sex);
	$cyclist->setCountry($country);
	$cyclist->setZipCode($zip_code);
	$cyclist->setTown($town);
	$cyclist->setPhone($phone);
	$cyclist->setLicense($license);
	$cyclist->insertNewCyclistInto();
}



include("templates/footer.tpl");

?>
</body>