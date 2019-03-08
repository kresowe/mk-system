<?

require("Validating.php");
require_once("DBGetter.php");
require_once(__DIR__ . "/../libs/RainCaptcha-1.1.0.phps");
include(__DIR__ . "/../../../mk_dane/secret.php"); 

/**
* 
*/
class ValidateForm
{
	static function validateName(&$text, &$err, &$accepted) {
		if (empty($_POST["name"])) 
		{
			$err= "Musisz podac imię./ You must write your first name";
			$accepted = false;
		} 
		else 
		{
			$text = Validating::adjustInput($_POST["name"]);
			$text = Validating::makeSafeForSql($text);
			if (!Validating::validText($text)) 
			{
				$err = "Imie zawiera niedopuszczalne znaki./ There are invalid characters in first name";
				$accepted = false;
			}
		}
	}

	static function validateSurname(&$text, &$err, &$accepted){
		if (empty($_POST["surname"])) 
		{
			$err = "Musisz podac nazwisko./ You must write your last name";
			$accepted = false;
		} 
		else 
		{
			$text = Validating::adjustInput($_POST["surname"]);
			$text = Validating::makeSafeForSql($text);
			if (!Validating::validText($text)) 
			{
				$err = "Nazwisko zawiera niedopuszczalne znaki./ There are invalid characters in last name ";
				$accepted = false;
			}
		}
	}

	static function validateBirthyear(&$text, &$err, &$accepted){
		if (empty($_POST["birthyear"])) 
		{
			$err = "Musisz podac rocznik./ You must write year of your birth";
			$accepted = false;
		} 
		else 
		{
			$text = Validating::adjustInput($_POST["birthyear"]);
			$text = Validating::makeSafeForSql($text);
			if (!Validating::validBirthYear($text)) 
			{
				$err =  "Niepoprawny rocznik - wpisz rocznik w formacie yyyy (np. 1992)";
				$err .= "/Invalid year - write in format of yyyy (i.e. 1992)";
				$accepted = false;
			}
		}
	}

	static function validateLogin(&$login, &$err, &$accepted) {
		if (empty($_POST["login"])) 
		{
			$err = "Musisz podać adres mailowy jako login!";
			$accepted = false;
		}
		else
		{
			$login = Validating::adjustInput($_POST["login"]);
			$login = Validating::makeSafeForSql($login);
			if (!Validating::validEmail($login))
			{
				$err = "Niepoprawny adres email./ Invalid email address";
				$accepted = false;
			}
			
			include(__DIR__ . "/../../../mk_dane/secret.php"); 
			$db = new mysqli($host, $db_user, $db_pass, $db_name);
			if (mysqli_connect_errno()) 
				echo "<p>Nie można połączyć z bazą danych: " . mysql_connect_error() . "</p>";

			$sql_query = "SELECT * FROM logins WHERE login = '$login';";
			$result = $db->query($sql_query);
			if (!$result)
				echo "<p>Błąd komunikacji z bazą danych.</p>";
			else 
			{
				if ($result->num_rows > 0)
				{
					$err = "Wybrany login jest już zajęty!";
					$accepted = false;
				}
			}
			
		}
	}

	static function validateEmail(&$email, &$err, &$accepted){
		if (empty($_POST["login"])) 
		{
			$err = "Musisz podać adres mailowy! - You must type email address!";
			$accepted = false;
		}
		else
		{
			$email = Validating::adjustInput($_POST["login"]);
			$email = Validating::makeSafeForSql($email);
			if (!Validating::validEmail($email))
			{
				$err = "Niepoprawny adres email./ Invalid email address";
				$accepted = false;
			}
		}
	}

	//walidacja hasla
	static function validatePassword(&$password, &$err, &$accepted) {
		if (empty($_POST["pass"]))
		{
			$err = "Musisz podać hasło!";
			$accepted = false;
		}
		else
		{
			$password = Validating::adjustInput($_POST["pass"]);
			if (!preg_match("/^[a-zA-Z]\S*\d\S*$/", $password ))
			{
				$err = "Hasło musi zaczynać się od litery, nie może zawierać znaków białych i musi zawierać choć jedną cyfrę.";
				$accepted = false;
			}
			if (strlen($password) < 8)
			{
				$err = "Hasło musi mieć co najmniej 8 znaków";
				$accepted = false;
			}
		}
	}

	//walidacja hasla powtorzonego
	//$pass1 == pass, $pass2 == pass2
	static function validatePassword2(&$pass2, &$err, &$accepted, $pass1) {
		if (empty($_POST["pass2"]))
		{
			$err = "Musisz powtórzyć hasło!";
			$accepted = false;
		}
		else
		{
			$pass2 = Validating::adjustInput($_POST["pass2"]);
			if ($pass2 != $pass1)
			{
				$err = "Hasło powtórzone nie jest takie samo jak wpisane wyżej!";
				$accepted = false;
			}
		}
	}
	
	
	static function validateReCaptcha(&$err, &$accepted){
		if (empty($_POST["captcha"])){
			$err = "Nieuzupełnione CAPTCHA.";
			$accepted = false; 
		}
		$captcha = new RainCaptcha();
		if (!$captcha->checkAnswer($_POST["captcha"])) {
			$err = "Niepoprawnie uzupełnione CAPTCHA!";
			$accepted = false;
		}
		
	}
	
	static function validateSex(&$sex, &$err, &$accepted) {
		if (empty($_POST["sex"]))
		{
			$err = "Musisz zaznaczyć płeć.";
			$accepted = false;
		} 
		else
		{
			$sex = $_POST["sex"];
			if ($sex != "M" && $sex != "K")
			{
				$err = "Musisz poprawnie zaznaczyć płeć.";
				$accepted = false;
			}
		}
	}

	static function validateStartingNo(&$starting_no, &$err, &$accepted) {
		if (!empty($_POST["starting_no"]))
		{
			$starting_no = Validating::adjustInput($_POST["starting_no"]);
			$starting_no = Validating::makeSafeForSql($starting_no);
			if (!Validating::validStartingNo($starting_no))
			{
				$err = "Niepoprawny numer startowy.";
				$accepted = false;
			}
		}
	}

	static function validateCountry(&$country, &$err, &$accepted) {
		if (empty($_POST["country"])) 
		{
			$err = "Musisz podac kraj./ You must write country.";
			$accepted = false;
		} 
		else 
		{
			$country = Validating::adjustInput($_POST["country"]);
			$country = Validating::makeSafeForSql($country);
			$country = strtoupper($country);
			if (!Validating::validCountry($country)) 
			{
				$err =  "Niepoprawny format kraju - wpisz np. POL";
				$err .= "/Invalid country - write for example BLR or LTU";
				$accepted = false;
			}
		}

	}

	static function validateZipCode(&$zip_code, &$err, &$accepted) {
		if (!empty($_POST["zip_code"])) 
		{
			$zip_code = Validating::adjustInput($_POST["zip_code"]);
			$zip_code = Validating::makeSafeForSql($zip_code);
			if (!Validating::validZipCode($zip_code)) 
			{
				$err = "Niepoprawny kod pocztowy. Dla Polski wpisz w formacie np. 12-345, dla innych krajów nie wpisuj.";
				$accepted = false;
			}
		}
	}

	static function validateTown(&$town, &$err, &$accepted) {
		if (empty($_POST["town"])) 
		{
			$err = "Musisz podac miejscowość./ You must write name of your town/city";
			$accepted = false;
		} 
		else 
		{
			$town = Validating::adjustInput($_POST["town"]);
			$town = Validating::makeSafeForSql($town);
			if (!Validating::validText($town)) 
			{
				$err= "Nazwa miejscowości zawiera niedopuszczalne znaki./ There are invalid characters in town's name";
				$accepted = false;
			}
		}	
	}

	static function validatePhone(&$phone, &$err, &$accepted) {
		if (!empty($_POST["phone"])) 
		{
			$phone = Validating::adjustInput($_POST["phone"]);
			$phone = Validating::makeSafeForSql($phone);
			if (!Validating::validPhone($phone)) 
			{
				$err = "Niepoprawny format numeru telefonu. / Invalid form of phone number.";
				$accepted = false;
			}
		}
	}

	static function validateLicensePosession(&$license, &$err, &$accepted){
		if (!empty($_POST['license'])){
			$license = $_POST['license'];
		}
	}

	static function validateLicenseType(&$type, &$err, &$accepted) {
		if (empty($_POST["license_type"])) {
			$err= "Musisz wybrać rodzaj licencji - You must choose license type.";
			$accepted = false;
		} 
		else {
			$type = $_POST["license_type"];
		}
	}

	static function validateLicense(&$license, &$err, &$accepted) {
		if (!empty($_POST['license']))
		{
			$license = Validating::adjustInput($_POST['license']);
			$license = Validating::makeSafeForSql($license);
			if (!Validating::validUCICode($license))
			{
				$err = "Niepoprawny format kodu UCI (format np.: POL19901231)";
				$accepted = false;
			}
		}
	}

	static function validateDistance(&$distance, &$err, &$accepted) {
		if (empty($_POST['distance']))
		{
			$err = "Musisz wybrać dystans. / Please choose distance.";
			$accepted = false;
		}
		else
			$distance = $_POST['distance'];
	}

	static function validatePayment(&$payment, &$err, &$accepted) {
		if (empty($_POST['payment']))
		{
			$err = "Musisz wybrać sposób płatności. / Please choose payment method.";
			$accepted = false;
		}
		else
			$payment = $_POST['payment'];
	}

	static function validateProvince(&$province, &$err, &$accepted){
		if (!empty($_POST['province'])){
			$province = $_POST['province'];
		}
		else {
			$province = '';
		}
	}

	static function validateCreateTeam(&$create_team, &$err, &$accepted){
		if (isset($_POST['create_team'])){
			$create_team = true;
		}
		else {
			$create_team = false;
		}
	}

	static function validateTeam(&$team, &$err, &$accepted){
		if (!empty($_POST['team'])){
			$team = $_POST['team'];
		}
		else {
			$team = false;
		}
	}

	static function validateNewTeam(&$text, &$err, &$accepted){
		if (!empty($_POST['new_team'])){
			$text = Validating::adjustInput($_POST["new_team"]);
			$text = Validating::makeSafeForSql($text);

			$pattern = "/^[a-zA-Z0-9\p{Cyrillic}\s\-\'\"\.ęłóąśŚŁżŻźŹćĆ]+$/";
			if (!preg_match($pattern, $text)){
				$err = "Nazwa drużyny zawiera niedopuszczalne znaki! / Team name includes invalid characters!";
				$accepted = false;
			}
		}
		else {
			$text = '';
		}
	}

	static function validateNotes(&$notes, &$err, &$accepted){
		if (!empty($_POST['notes'])) {
			$notes = Validating::adjustInput($_POST['notes']);
			$notes = Validating::makeSafeForSql($notes);
			// $notes = wordwrap($notes, 70);
		}
	}

	static function validateAcceptRules(&$accept_rules, &$err, &$accepted){
		if (empty($_POST["accept_rules"])) {
			$err = "Aby się zarejestrować na maraton musisz zaakceptować regulamin. / In order to register for marathon, you must accept rules.";
			$accepted = false;
		} 
	}

	static function validateOldEnough(&$distance, &$err, &$accepted, $birthyear, $sex){
		if ($distance == DBGetter::getDistanceIdByAbbrev("mik") && ($birthyear < MAX_BIRTH_YEAR_MIKRO || $birthyear > MINIMAL_BIRTH_YEAR) ) {
			$accepted = false;
			$err .= " Regulamin nie pozwala na start na Mikro osobie z tego rocznika. / According to rules, a person of this age can't start in Mikro.";
		}
		else if ($distance == DBGetter::getDistanceIdByAbbrev("min") && ($birthyear >MIN_BIRTH_YEAR_MINI || $birthyear < MAX_BIRTH_YEAR_MINI) ) {
			$accepted = false;
			$err .= " Regulamin nie pozwala na start na Mini osobie z tego rocznika. / According to rules, a person of this age can't start in Mini.";
		} else if ($distance == DBGetter::getDistanceIdByAbbrev("fit") && $birthyear >MIN_BIRTH_YEAR_FIT) {
			$accepted = false;
			$err .= " Regulamin nie pozwala na start na Fit osobie z tego rocznika. / According to rules, a person of this age can't start in Fit.";
		}
		else if ($distance == DBGetter::getDistanceIdByAbbrev("pol") 
			&& $sex == "M" 
			&& $birthyear > MIN_BIRTH_YEAR_POLMAR_M) {
				$accepted = false;
				$err .= " Regulamin nie pozwala na start na Półmaratonie osobie z tego rocznika. / According to rules, a person of this age can't start in Polmaraton.";
		}
		else if ($distance == DBGetter::getDistanceIdByAbbrev("pol") 
			&& $sex == "K" 
			&& $birthyear > MIN_BIRTH_YEAR_POLMAR_K){
				$accepted = false;
				$err .= " Regulamin nie pozwala na start na Półmaratonie osobie z tego rocznika. / According to rules, a person of this age can't start in Polmaraton.";
		}
		else if ($distance == DBGetter::getDistanceIdByAbbrev("mar") 
			&& $sex == "M" 
			&& $birthyear > MIN_BIRTH_YEAR_MAR_M){
				$accepted = false;
				$err .= " Regulamin nie pozwala na start na Maratonie osobie z tego rocznika. / According to rules, a person of this age can't start in Maraton.";
		}
		else if ($distance == DBGetter::getDistanceIdByAbbrev("mar") 
			&& $sex == "K" 
			&& $birthyear > MIN_BIRTH_YEAR_MAR_K){
			$accepted = false;
			$err .= " Regulamin nie pozwala na start na Maratonie osobie z tego rocznika. / According to rules, a person of this age can't start in Maraton.";
		}
	}

	static function validateNewInMk(&$new_in_mk, &$err, &$accepted) {
		if (empty($_POST["new_in_mk"])) {
			$err = "Musisz zaznaczyć odpowiedź./ Select answer";
			$accepted = false;
		} 
		else{
			$new_in_mk = $_POST["new_in_mk"];
		}
	}

	static function validateTextNotRequired(&$text, &$err, &$accepted, $field_name){
		if (!empty($_POST[$field_name])){
			$text = Validating::adjustInput($_POST[$field_name]);
			$text = Validating::makeSafeForSql($text);
			if (!Validating::validText($text)) {
				$err = "Tekst zawiera niedopuszczalne znaki./ There are invalid characters in text.";
				$accepted = false;
			}
		}
	}





	
	
}

?>