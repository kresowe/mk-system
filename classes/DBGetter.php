<?php
require_once("MKSystemUtil.php");
require_once("Search.php");

class DBGetter {
	static function connectDB() {
		include(__DIR__ . "/../../../mk_dane/secret.php");
		try {
			$db = new mysqli($host, $db_user, $db_pass, $db_name);
			if (mysqli_connect_errno()) 
			{
				throw new Exception("Nie można połączyć z bazą danych!");
				
			}
		} catch (Exception $e) {
			echo "<p class=\"text-danger\">" . $e->getMessage() . "</p>";
			exit;
		}
		$db->query("SET NAMES 'utf8';");
		$db->query("SET CHARACTER SET 'utf8';");
		return $db;
	}

	static function getAvailableMarathons() {
		$db = self::connectDB();
		$currentDate = new DateTime();
		$current_year = date('Y');
		$races = array();

		$sql_query = "SELECT * FROM races2 WHERE year=" . $current_year . " ORDER BY date;";
		$result = $db->query($sql_query);
		$count = 0;
		for ($i = 0; $i < $result->num_rows; $i++)
		{
			$race = $result->fetch_assoc();
			$marathonDate = new DateTime($race['date']);
			if ($marathonDate->format("z") > $currentDate->format("z") && 
				dates_difference($currentDate, $marathonDate) < 31 &&
				dates_difference($currentDate, $marathonDate) > 2)
				  $races[$count++] = array($race['id'], $race['date'], $race['place']);
		}
		
		$result->free();
		$db->close();
		return $races;
	}

	static function getFutureMarathons() {
		$db = self::connectDB();
		$currentDate = new DateTime();
		$current_year = date('Y');
		$races = array();

		$sql_query = "SELECT * FROM races2 WHERE year=" . $current_year . " ORDER BY date;";
		$result = $db->query($sql_query);
		$count = 0;
		for ($i = 0; $i < $result->num_rows; $i++)
		{
			$race = $result->fetch_assoc();
			$marathonDate = new DateTime($race['date']);
			if ($marathonDate->format("z") > $currentDate->format("z") && 
				dates_difference($currentDate, $marathonDate) < 31 &&
				dates_difference($currentDate, $marathonDate) >= 0 )
				  $races[$count++] = array($race['id'], $race['date'], $race['place']);
		}
		
		$result->free();
		$db->close();
		return $races;
	}

	static function getRaceTypes($id = 0) {
		$db = self::connectDB();
		$race_types = array();

		if ($id != 0) { // dystanse przypisane dla konkretnego wyscigu
			$sql_query = "SELECT t.id, t.name  FROM race_types2 t, races_details2 d 
				WHERE t.active = 1 AND d.type_id = t.id 
				AND d.race_id = " . $id . " GROUP BY t.id;";
		} else {
			$sql_query = "SELECT id, name FROM race_types2 WHERE active = 1 GROUP BY id;";
		}
		$result = $db->query($sql_query);
		for ($i = 0; $i < $result->num_rows; $i++) {
			$race_type = $result->fetch_assoc();
			$race_types[$i] = array($race_type['id'], $race_type['name']);
		}
		
		$result->free();
		$db->close();
		return $race_types;
	}

	static function getTeams(){
		$db = self::connectDB();
		$teams = array();

		$sql_query = "SELECT *  FROM teams2 ORDER BY name;";
		//echo "<p>" . $sql_query . "</p>"; //test
		$result = $db->query($sql_query);
		for ($i = 0; $i < $result->num_rows; $i++)
		{
			$team = $result->fetch_assoc();
			$teams[$i] = array($team['id'], $team['name']);
		}
		
		$result->free();
		$db->close();
		return $teams;
	}

	static function getPaymentTypes(){
		$db = self::connectDB();
		$payments = array();

		$sql_query = "SELECT *  FROM payment_types2;";
		//echo "<p>" . $sql_query . "</p>"; //test
		$result = $db->query($sql_query);
		for ($i = 0; $i < $result->num_rows; $i++){
			$pay = $result->fetch_assoc();
			$payments[$i] = array('id' => $pay['id'], 'name' => $pay['name']);
		}
		
		$result->free();
		$db->close();
		return $payments;
	}

	static function getTeamByName($name){
		$db = self::connectDB();
		$teams = array();

		$sql_query = "SELECT *  FROM teams2 WHERE name = \"" . $name . "\";";
		//echo "<p>" . $sql_query . "</p>"; //test
		$result = $db->query($sql_query);
		for ($i = 0; $i < $result->num_rows; $i++)
		{
			$team = $result->fetch_assoc();
			$teams[$i] = array($team['id'], $team['name']);
		}
		
		$result->free();
		$db->close();
		return $teams;
	}

	static function createTeam($name){
		$db = self::connectDB();

		$sql_query = "INSERT INTO teams2 (name) VALUES (\"" . $name . "\");";
		//// self::saveChangesInSqlScript($sql_query);////
		MKSystemUtil::saveTextChanges("New team");////
		MKSystemUtil::saveTextChanges("Team name: " . $name);////
		//// return true; ////
		if ($db->query($sql_query)){
			self::saveChangesInSqlScript($sql_query);
			$db->close();
			return true;
		}
		else {
			throw new Exception("Nie udało się utworzyć nowej drużyny!", 1);
		}

	}

	static function getRaceById($id){
		$db = self::connectDB();

		$sql_query = "SELECT place, date FROM races2 WHERE id = " . $id .";";
		$result = $db->query($sql_query);
		if ($result->num_rows > 0){
			$race = $result->fetch_assoc();
			$db->close();
			return $race;
		} else {
			echo "<h3>Brak wyników do wyświetlenia. / No results to show.</h3>";
		}
	}

	static function getDistanceName($id){
		$db = self::connectDB();

		$sql_query = "SELECT name FROM race_types2 WHERE id = " . $id .";";
		$result = $db->query($sql_query);
		if ($result->num_rows > 0){
			$type = $result->fetch_assoc();
			$db->close();
			return $type['name'];
		} else {
			echo "<h3>Brak wyników do wyświetlenia. / No results to show.</h3>";
		}
	}

	static function getDistanceNameByAbbrev($abbrev){
		$db = self::connectDB();

		$sql_query = "SELECT name FROM race_types2 WHERE abbrev = \"" . $abbrev ."\";";
		$result = $db->query($sql_query);
		$type = $result->fetch_assoc();
		$db->close();
		return $type['name'];
	}

	static function getDistanceIdByAbbrev($abbrev){
		$db = self::connectDB();

		$sql_query = "SELECT id FROM race_types2 WHERE abbrev = \"" . $abbrev ."\";";
		$result = $db->query($sql_query);
		$type = $result->fetch_assoc();
		$db->close();
		return $type['id'];
	}

	static function getPaymentName($id){
		$db = self::connectDB();

		$sql_query = "SELECT name FROM payment_types2 WHERE id = " . $id .";";
		$result = $db->query($sql_query);
		if ($result->num_rows > 0){
			$type = $result->fetch_assoc();
			$db->close();
			return $type['name'];
		} else {
			echo "<h3>Brak wyników do wyświetlenia. / No results to show.</h3>";
		}
	}

	static function getPaymentNameByAbbrev($abbrev){
		$db = self::connectDB();

		$sql_query = "SELECT name FROM payment_types2 WHERE abbrev = \"" . $abbrev ."\";";
		// echo "<p>" . $sql_query . "</p>";
		$result = $db->query($sql_query);
		$result = $result->fetch_assoc();
		$db->close();
		return $result['name'];
	}

	static function saveChangesInSqlScript($command){
		$log_file_name = __DIR__ . "/../../../mk_dane/log_db_changes.sql";
		$file = fopen($log_file_name, "a") or die("Problem z zapisem do pliku!/ Error");
		$text = "-- " . date("Y-m-d H:i:s\n");
		fwrite($file, $text);
		$text = $command . "\n\n";
		fwrite($file, $text);
		fclose($file);
	}

	static function updateLastLoginDate($login){
		$db = self::connectDB();

		$sql_query = "UPDATE logins SET last_login = (SELECT NOW()) WHERE login = '" . $login . "';";
		$db->query($sql_query);
		
		$db->close();
	}

	static function checkAccount($mail){
		$db = self::connectDB();

		$sql_query = "SELECT COUNT(*) AS countit FROM logins l, users2 u WHERE l.login = u.email AND l.login = '" .
			$mail . "';";
		$result = $db->query($sql_query);
		$result = $result->fetch_assoc();
		return $result['countit'];
	}

	static function getRaceDistance($id, $type_id) {
		$db = self::connectDB();

		$column_name =  '';

		if ($type_id == 2) {
			$column_name = "maratondist";
		} else if ($type_id == 3) {
			$column_name = "minidist";
		} else if ($type_id == 4) {
			$column_name = "polmaratondist";
		} else {
			$db->close();
			return 0.0;
		}

		$sql_query = "SELECT " . $column_name . " FROM races2 WHERE id = " . $id . ";";
		$result = $db->query($sql_query);
		$distance = $result->fetch_assoc();
		return $distance["$column_name"];
	}

	static function getAllRidersIds($min_id) {
		$db = self::connectDB();
		$riders_ids = array();

		$max_id = $min_id + 200;

		$sql_query = "SELECT id from users2 WHERE id >= " . $min_id . 
			" AND id < " . $max_id . ";";
		// echo "<p>" . $sql_query . "</p>";
		$result = $db->query($sql_query);

		for ($i = 0; $i < $result->num_rows; $i++){
			$row = $result->fetch_assoc();
			$riders_ids[$i] = $row['id'];
		}
		
		$result->free();

		$db->close();
		return $riders_ids;
	}

	


}

?>