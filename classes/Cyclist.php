<?php
require_once("DBGetter.php");
require_once("MKSystemUtil.php");
/**
* 
*/
class Cyclist {
	private $id;
	private $name;
	private $name2;
	private $surname;
	private $sex;
	private $birthyear;
	private $zip_code;
	private $city;
	private $province;
	private $country;
	private $country_iso3;
	private $email;
	private $notes;
	private $phone;
	private $license;
	private $pass;

	function __construct() {
	} 

	public function saveCyclistData($row){
		$this->name = stripslashes($row['name']);
		$this->name2 = stripslashes($row['name2']);
		$this->surname = stripslashes($row['surname']);
		$this->sex = stripslashes($row['sex']);
		$this->birthyear = stripslashes($row['birthyear']);
		$this->zip_code = stripslashes($row['zip_code']);
		$this->city = stripslashes($row['city']);
		$this->province = stripslashes($row['province']);
		$this->country = stripslashes($row['country']);
		$this->country_iso3 = stripslashes($row['country_iso3']);
		$this->email = stripslashes($row['email']);
		$this->notes = stripslashes($row['notes']);
		$this->phone = stripslashes($row['phone']);
		// $this->license = stripslashes($row['license']);
	}

	public function output() {
		echo "<p>Imię/ First name/ Pavarde/ Имя: <strong>" . $this->name . " " . $this->name2 . "</strong></p>";
		echo "<p>Nazwisko/ Last name/ Vardas/ Фамилия: <strong>" . $this->surname . "</strong></p>";
		echo "<p>Rok urodzenia/ Year of birth/ Gimimo metai/ Год рождения: <strong>" . $this->birthyear . "</strong></p>";
		echo "<p>Płeć/ Sex/ Lytis/ Cекс: <strong>" . $this->sex . "</strong></p>";
		// echo "<p>Licencja: <strong>" . $this->license . "</strong></p>";
		echo "<p>Kraj/ Country/ Šalis/ Страна (POL, BLR, LTU, etc.): <strong>" . $this->country_iso3 . "</strong></p>";
		echo "<p>Województwo (POL only): <strong>" . $this->province . "</strong></p>";
		echo "<p>Kod pocztowy (POL only): <strong>" . $this->zip_code . "</strong></p>";
		echo "<p>Miejscowość/ Town/ Miestas/ Город: <strong>" . $this->city . "</strong></p>";
		echo "<p>Email: <strong>" . $this->email . "</strong></p>";
		echo "<p>Telefon/ Phone number: <strong>" . $this->phone . "</strong></p>";
	}

	public function getDataFromDB($id){
		$db = DBGetter::connectDB();
		$sql_query = "SELECT * FROM users2 WHERE id='" . $id . "';";
		$result = $db->query($sql_query);
		$row = $result->fetch_assoc();
		$this->saveCyclistData($row);
		$result->free();
	}

	public function getDataFromDB_LoggedUser(){
		$db = DBGetter::connectDB();
		$sql_query = "SELECT * FROM users2 WHERE email='" . $this->email . "';";
		$result = $db->query($sql_query);
		$row = $result->fetch_assoc();
		$this->setId($row['id']);
		$this->saveCyclistData($row);
		$result->free();
	}

	public function insertLoginInto(){
		$db = DBGetter::connectDB();

		//deaktywuj istniejace konta dla tego uzytkownika (jesli istnieja)
		$this->deactivateLogin();

		////
		//ustawiamy active = false, bo nie mamy my sql >> ms sql 
		$sql_query = "INSERT INTO logins VALUES ('" . $this->email . "', 2, '" . $this->pass . "', TRUE, (SELECT NOW()));";////
		$result = $db->query($sql_query);

		DBGetter::saveChangesInSqlScript($sql_query);
		

		$sql_query_2 = "UPDATE users2 SET email = '" . $this->email . "' WHERE id = " . $_SESSION['cyclist'] . ";";
		//echo "<p>" . $sql_query_2 . "</p>";
		//// DBGetter::saveChangesInSqlScript($sql_query_2);////
		MKSystemUtil::saveTextChanges("Update user");////
		MKSystemUtil::saveTextChanges("User: " . $_SESSION['cyclist'] . ", name: " . $this->name . 
			", surname: " . $this->surname . "| update email: ". $this->email);////
		MKSystemUtil::saveTextChanges("New login: activate!");////
		MKSystemUtil::saveTextChanges("login: " . $this->email);////

		$result_2 = $db->query($sql_query_2);////
		if ($result && $result_2) ////
		{
			DBGetter::saveChangesInSqlScript($sql_query_2);
			echo "<h4>Rejestracja zakończona. Możesz się <a href=\"/mk-system/\">zalogować</a>.</h4>";
			echo "<h4>Registration completed. You can <a href=\"/mk-system/\">log in now</a>.</h4>";
			echo "<h4>регистрация завершена. <a href=\"/mk-system/\">Вы можете войти </a>.</h4>";
			echo "<h4>Registracija baigta. <a href=\"/mk-system/\">log in </a>.</h4>";
			//// echo "<h4>Rejestracja zakończona. Będziesz mógł się zalogować, gdy admin zatwierdzi Twoje konto.</h4>";////
			//// echo "<h4>Registration completed. You will be able to log in as soon as admin accepted your account.</h4>";////
		}
		else
		{
			echo "<h4>Rejestracja nie powiodła się :( Spróbuj ponownie później!</h4>";
			echo "<h4>Registration unsuccessfully :( Please try again later!</h4>";
		}
			
	}

	public function updatePass(){
		$db = DBGetter::connectDB();

		$sql_query = "UPDATE logins SET password = '" . $this->pass . "' WHERE login = '" . $this->email . "';";
		if ($db->query($sql_query)) {
			DBGetter::saveChangesInSqlScript($sql_query);
			return true;
		}
		else
			return false;
	}

	public function insertNewCyclistInto(){
		$db = DBGetter::connectDB();

		////ustawiamy active = TRUE/ FALSE, bo nie mamy my sql >> ms sql 
		//// $sql_query = "INSERT INTO logins VALUES ('" . $this->email . "', 2, '" . $this->pass . "', FALSE, (SELECT NOW()));";////
		$sql_query = "INSERT INTO logins VALUES ('" . $this->email . "', 2, '" . 
			$this->pass . "', TRUE, (SELECT NOW()));";////
		$result = $db->query($sql_query);
		DBGetter::saveChangesInSqlScript($sql_query);

		$sql_query2 = "INSERT INTO users2 (name, surname, sex, birthyear, zip_code, city, country_iso3,
			email, phone) VALUES 
			('" . $this->name . "', '" . $this->surname . "', '" . $this->sex . "', '" . $this->birthyear 
			. "', '" . $this->zip_code . "', '" . $this->city . "', '" . $this->country_iso3
			. "', '" . $this->email . "', '" . $this->phone . "');";
		//echo "<p>" . $sql_query2 . "</p>";
		$result2 = $db->query($sql_query2); ////
		//// DBGetter::saveChangesInSqlScript($sql_query_2);
		MKSystemUtil::saveTextChanges("New user");////
		MKSystemUtil::saveTextChanges($this->name . "', '" . $this->surname . "', '" . $this->sex . "', '" . $this->birthyear 
			. "', '" . $this->zip_code . "', '" . $this->city . "', '" . $this->country_iso3
			. "', '" . $this->email . "', '" . $this->phone . "'");////
		MKSystemUtil::saveTextChanges("New login: activate!");////
		MKSystemUtil::saveTextChanges("login: " . $this->email);////

		if ($result && $result2) ////
		{
			DBGetter::saveChangesInSqlScript($sql_query_2); ////
			echo "<h4>Rejestracja przebiegła pomyślnie. Możesz się <a href=\"/mk-system/\">zalogować</a>.</h4>";
			echo "<h4>Registration completed. You can <a href=\"/mk-system/\">log in now</a>.</h4>";
			echo "<h4>регистрация завершена. <a href=\"/mk-system/\">Вы можете войти </a>.</h4>";
			echo "<h4>Registracija baigta. <a href=\"/mk-system/\">log in </a>.</h4>";
			//// echo "<h4>Rejestracja zakończona. Będziesz mógł się zalogować, gdy admin zatwierdzi Twoje konto.</h4>";////
			//// echo "<h4>Registration completed. You will be able to log in as soon as admin accepted your account.</h4>";////
		}
		else
		{
			//echo "<p>" . $result . ", " . $result2 . "</p>";
			echo "<h4>Rejestracja nie powiodła się :( Spróbuj ponownie później!</h4>";
			echo "<h4>Registration unsuccessfully :( Please try again later!</h4>";
		}

	}

	public function showCyclistProfile() {
		$this->output();
		echo "<p>Numer startowy/ Starting number/ стартовый номер/ pradedant numeris: <strong>". $this->getStartNumber() . "</strong></p>";
		echo "<p>Drużyna/ Team/ Komanda/ Команда: <strong>". $this->getTeam() . "</strong></p>";
	}

	public function getStartNumber() {
		$db = DBGetter::connectDB();
		$sql_query = "SELECT * FROM start_numbers2 WHERE user_id='" . $this->getId() . "' AND 
			dateto >= (SELECT NOW());";
		$result = $db->query($sql_query);
		$row = $result->fetch_assoc();
		return $row['start_number'];
	}

	public function getTeam() {
		$db = DBGetter::connectDB();
		$sql_query = "SELECT t.name FROM teams2 t, teams_members2 m WHERE m.user_id='" . $this->getId() . /*"' AND 
			m.team_id = t.id AND dateto >= (SELECT DATE_SUB(NOW(), INTERVAL 1 YEAR));";*/
			"' AND m.team_id = t.id AND dateto >= (SELECT NOW());";
		$result = $db->query($sql_query);
		$row = $result->fetch_assoc();
		return $row['name'];
	}

	public function update($field, $value){
		$db = DBGetter::connectDB();

		$sql_query = "UPDATE users2 SET " . $field ." = '" . $value . "' WHERE email = '" . $this->email . "';";
		//// DBGetter::saveChangesInSqlScript($sql_query);
		MKSystemUtil::saveTextChanges("Update user");////
		MKSystemUtil::saveTextChanges("User: " . $this->email . ", " . $this->surname . ", " . $this->email . "|| Update: " .
			$field . ":  " . $value);////
		//// return true;////
		if ($db->query($sql_query)){ ////
			DBGetter::saveChangesInSqlScript($sql_query); ////
			return true; ////
		} ////
		else ////
			return false; ////
	}

	public function updateTeam($value){
		$this->endPreviousTeamAssociation();

		$db = DBGetter::connectDB();

		$a_team = DBGetter::getTeamByName($value);
		$team_id = $a_team[0][0];

		$sql_query = "INSERT INTO teams_members2 (user_id, team_id, datefrom, dateto) VALUES (" . $this->getId() . 
			", " . $team_id . ", (SELECT NOW()), (SELECT DATE_FORMAT(NOW(), '%Y-12-31 23:59:59')));" ;
		//// DBGetter::saveChangesInSqlScript($sql_query);////
		MKSystemUtil::saveTextChanges("Update team_members");////
		MKSystemUtil::saveTextChanges("User: " . $this->name . ", " . $this->surname . ", " . $this->email . ", " . 
			$this->getId() . "| New Team: " . $value . ", " . $team_id);////
		//// return true;////
		if ($db->query($sql_query)){
			DBGetter::saveChangesInSqlScript($sql_query);
			return true;
		}
		else
			return false;
	}

	public function endPreviousTeamAssociation(){
		$db = DBGetter::connectDB();

		$sql_query = "UPDATE teams_members2 SET dateto = (SELECT(NOW())) WHERE user_id = " . $this->getId() . 
			" AND dateto >= (SELECT NOW());";
		//// DBGetter::saveChangesInSqlScript($sql_query);////
		MKSystemUtil::saveTextChanges("Update team_members");////
		MKSystemUtil::saveTextChanges("User: " . $this->name . ", " . $this->surname . ", " . $this->email . ", " . 
			$this->getId() . "| end previous team association." );////
		//// return true;////
		if ($db->query($sql_query)){
			DBGetter::saveChangesInSqlScript($sql_query);
			return true;
		}
		else
			return false;
	}

	public function printApplicationIntoCsv($application_form){
		$marathon = DBGetter::getRaceById($application_form['marathon']);
		$distance = DBGetter::getDistanceName($application_form['distance']);
		$payment = DBGetter::getPaymentName($application_form['payment']);

		$filename = __DIR__ . "/../../../mk_dane/zgloszenia/" . $marathon['date'] . 
			substr(textWithoutPolishChars($marathon['place']), 0, 3) . ".csv";

		// $filename = "/home/uey6417/mk_dane/zgloszenia/" . substr($marathon['place'], 0, 3) . 
		// // $filename = "/home/uey6417/mk_dane/zgloszenia/" . substr($marathon['place'], 0, 3) . 
		// 	substr($marathon['date'], 0, 4) . ".csv";

		$file_out = fopen($filename, "a") or die("Problem z plikiem zapisu!| Error");

		// $application_form['notes'] = str_replace(";", ":", $application_form['notes']);
		$data = array(array(
			$this->getStartNumber(),
			$this->getSurname(),
			$this->getName(),
			$this->getSex(),
			$this->getBirthyear(),
			// $this->getLicense(),
			'', //$pp_kategoria
			$this->getCountry(),
			$this->getZipCode(),
			$this->getTown(),
			$payment,
			$this->getTeam(),
			$application_form['notes'],
			$distance,
			$this->getEmail(),
			$this->getPhone(),
			$application_form['license']
			));

		// var_dump($data);

		foreach ($data as $i) {
			fputcsv($file_out, $i, ";");
		}
		fclose($file_out);
	}

	public function hasAppliedForMarathon($application_form){
		$db = DBGetter::connectDB();

		$user = $this->getId();
		$race = $application_form['marathon'];
		$type = $application_form['distance'];

		$sql_query = "SELECT * FROM apply_lists WHERE user_id = " . $user . " AND race_id = " . $race . ";";
		$result = $db->query($sql_query);
		if ($result->num_rows > 0) {
			$db->close();
			return true;
		}
		else {
			$db->close();
			return false;
		}
	}

	public function applyForMarathon($application_form){
		$db = DBGetter::connectDB();

		//prepare data for insert
		$user = $this->getId();
		$race = $application_form['marathon'];
		$type = $application_form['distance'];
		$start_number = $this->getStartNumber(); //e
		$team_from_db = DBGetter::getTeamByName($this->getTeam()); 
		$team = $team_from_db[0][0]; //e
		$payment = $application_form['payment'];
		$description = $application_form['notes']; //e

		//prepare sql query
		$sql_query = "INSERT INTO apply_lists (user_id, race_id, type_id ";
		if (!empty($start_number)){
			$sql_query .= ", start_number";
		}
		if (!empty($team)){
			$sql_query .= ", team_id";
		}
		$sql_query .= ", payment_id";
		if (!empty($description)) {
			$sql_query .= ", description";
		}
		$sql_query .= ", apply_date) VALUES (" . $user . ", " . $race . ", " . $type . ", "; 
		if (!empty($start_number)){
			$sql_query .= $start_number . ", ";
		}
		if (!empty($team)){
			$sql_query .= $team . ", ";
		}
		$sql_query .= $payment;
		if (!empty($description)){
			$sql_query .= ", \"" . $description . "\"";
		}
		$sql_query .= ", (SELECT NOW()));";

		//execute it.
		// echo "<p>" . $sql_query . "</p>";
		$result = $db->query($sql_query);
		if ($result) {
			DBGetter::saveChangesInSqlScript($sql_query);
			$db->close();
			return true;
		}
		else {
			$db->close();
			return false;
		}
	}

	private function deactivateLogin(){
		$db = DBGetter::connectDB();

		$sql_query_deact1 = "SELECT l.login FROM logins l, users2 u WHERE u.id = " . $_SESSION['cyclist'] .
			" AND u.email = l.login;";
		$result_deact1 = $db->query($sql_query_deact1);
		if ($result_deact1->num_rows > 0) { //istnieje konto
			$row = $result_deact1->fetch_assoc();
			$sql_query_deact2 = "DELETE FROM logins WHERE login = '" . $row['login'] ."';";
			$result_deact2 = $db->query($sql_query_deact2);
			if (!$result_deact2) {
				echo "<p>Niepowodzenie przy zmianie loginu.| Error. Try again.</p>";
			}
			else {
				DBGetter::saveChangesInSqlScript($sql_query);
			}
		}
	}

	public function getEmail(){
		return $this->email;
	}

	public function setId($val){
		$this->id = $val;
	}

	public function getId(){
		return $this->id;
	}

	public function setName($val) {
		$this->name = $val;
	}

	public function getName(){
		return $this->name;
	}

	public function setSurname($val) {
		$this->surname = $val;
	}

	public function getSurname(){
		return $this->surname;
	}

	public function setBirthyear($val) {
		$this->birthyear = $val;
	}

	public function getBirthyear(){
		return $this->birthyear;
	}

	public function setLogin($val) {
		if (strlen($val) > 0){
			$this->email = $val;
			return true;
		}
		return false;
	}

	public function getLogin(){
		return $this->email;
	}

	public function setPass($val) {
		$this->pass = sha1($val);
	}

	public function setSex($val) {
		$this->sex = $val;
	}

	public function getSex(){
		return $this->sex;
	}

	public function setCountry($val) {
		$this->country_iso3 = $val;
	}

	public function getCountry(){
		return $this->country_iso3;
	}

	public function getProvince(){
		return $this->province;
	}

	public function setZipCode($val) {
		$this->zip_code = $val;
	}

	public function getZipCode(){
		return $this->zip_code;
	}

	public function setTown($val) {
		$this->city = $val;
	}

	public function getTown(){
		return $this->city;
	}

	public function setPhone($val) {
		$this->phone = $val;
	}

	public function getPhone(){
		return $this->phone;
	}

	public function setLicense($val) {
		$this->license = $val;
	}

	public function getLicense(){
		return $this->license;
	}

}


?>