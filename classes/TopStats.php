<?php
require_once("DBGetter.php");
require_once("MKSystemUtil.php");
require_once("RaceResults.php");

class TopStatsCreate {
	private $all_riders_stats;

	function __construct() {
	} 

	public function saveAllRidersIds($min_id){
		// in fact it gets only riders with ids: min_id <= id < min_id + 400
		$ids = DBGetter::getAllRidersIds($min_id);

		for ($i = 0; $i < count($ids); $i++){
			$this->all_riders_stats[$i] = array('id' => $ids[$i], 
				'races' => 0, 
				'kms' => 0,
				'time' => 0);
		}
	}

	public function saveAllRidersStats(){
		// for ($i = 0; $i < 20; $i++){
		for ($i = 0; $i < count($this->all_riders_stats); $i++){
			$results = TopStatsCreateUtils::getCyclistResultsForStats(
				$this->all_riders_stats[$i]['id']);
			$this->all_riders_stats[$i]['races'] = count($results);
			$this->all_riders_stats[$i]['kms'] = TopStatsCreateUtils::getSumOfKms($results);
			$this->all_riders_stats[$i]['time'] = TopStatsCreateUtils::getSumOfTime($results);
		}
	}

	public function insertAllRidersStats(){
		// for ($i = 0; $i < 20; $i++){
		for ($i = 0; $i < count($this->all_riders_stats); $i++){
			$this->insertOneRiderStats($i);
		}
	}

	public function insertOneRiderStats($i){
		$db = DBGetter::connectDB();
		$a_rider_stats = $this->all_riders_stats[$i];

		$sql_query = "INSERT INTO top_stats  VALUES(" . $a_rider_stats['id'] . ", " 
			. $a_rider_stats['races'] . ", " . $a_rider_stats['kms'] . ", '"
			. $a_rider_stats['time'] . "');";
		
		$result = $db->query($sql_query);
		if ($result) {
			echo "<p> Wykonano: " . $sql_query . "</p>";
		} else {
			echo "<p>Fail! :(</p>";
		}

		$db->close();
	}

	public function getAllRiders(){
		return $this->all_riders_stats;
	}

}

class TopStatsUpdate {
	private $race_results;
	private $race_id;
	private $type_id;

	function __construct($a_race_id, $a_type_id){
		$this->race_id = $a_race_id;
		$this->type_id = $a_type_id;
	}

	public function updateTopStats(){
		$this->race_results = RaceResults::getRaceResults(
			$this->race_id, 
			$this->type_id);
		for ($i = 0; $i < count($this->race_results); $i++){
			if ($this->isRiderInTable($this->race_results[$i]['user_id'])){
				$this->updateOneCyclist(
					$this->race_id, 
					$this->type_id, 
					$this->race_results[$i]);
			} else {
				$this->addOneCyclist(
					$this->race_id, 
					$this->type_id, 
					$this->race_results[$i]);
			}
		}
	}

	public function updateOneCyclist($race_id, $type_id, $result){
		$races = $this->getNewRaces($result['user_id']);
		$kms = $this->getNewKms($result['user_id'], $race_id, $type_id);
		$time_sum = $this->getNewTime($result['user_id'], $result['time']);

		$db = DBGetter::connectDB();
		$sql_query = "UPDATE top_stats SET races = ". $races . ", kms = " . $kms;
		$sql_query .= ", time_sum = \"" . $time_sum ."\"";
		$sql_query .= " WHERE user_id = " . $result['user_id'] . ";";
		$db->query($sql_query);
		$db->close();
	}

	public function addOneCyclist($race_id, $type_id, $result){
		$db = DBGetter::connectDB();

		$new_date_time = explode(' ', $result['time']);
		$new_time = $new_date_time[1];

		$sql_query = "INSERT INTO top_stats  VALUES(" . $result['user_id'] 
			. ", 1, " . floatval(DBGetter::getRaceDistance($race_id, $type_id)) . ", '"
			. $new_time . "');";
		$result = $db->query($sql_query);

		if ($result) {
			echo "<p> Wykonano: " . $sql_query . "</p>";
		} else {
			echo "<p>Fail! :(</p>";
		}

		$db->close();
	}

	public function isRiderInTable($id){
		$db = DBGetter::connectDB();
		$sql_query = "SELECT * FROM top_stats WHERE user_id = " . $id . ";";
		$res = $db->query($sql_query);
		return $res->num_rows;
	}

	public function getNewRaces($user_id){
		$races = intval(TopStatsDBGetter::getValue('races', $user_id));
		$races += 1;
		return $races;
	}

	public function getNewKms($user_id, $race_id, $type_id){
		$kms = floatval(TopStatsDBGetter::getValue('kms', $user_id));
		$new_kms = floatval(DBGetter::getRaceDistance($race_id, $type_id));
		return $kms + $new_kms;
	}

	public function getNewTime($user_id, $time){
		$time_sum = timeStringToSeconds(TopStatsDBGetter::getValue('time_sum', $user_id));
		$new_date_time = explode(' ', $time);
		$new_time = $new_date_time[1];
		$seconds = timeStringToSeconds($new_time);
		$time_sum += $seconds;
		return secondsToTimeString($time_sum);
	}
}

class TopStatsPresent {
	static function getTopOnColumn($column){
		$db = DBGetter::connectDB();
		$results = array();

		$sql_query = "SELECT u.id, u.name, u.surname, u.country_iso3, ts." . $column . " "; 
		$sql_query .= "FROM top_stats ts, users2 u ";
		$sql_query .= "WHERE u.id = ts.user_id ";
		$sql_query .= "ORDER BY ts." . $column . " DESC LIMIT 20;";
		$res = $db->query($sql_query);

		for ($i = 0; $i < $res->num_rows; $i++){
			$a_result = $res->fetch_assoc();
			$results[$i] = array('id' => $a_result['id'],
				'name' => $a_result['name'], 
				'surname' =>$a_result['surname'], 
				'country' => $a_result['country_iso3'],
				'value' => $a_result["$column"]);
		}

		$db->close();
		return $results;
	}

}


class TopStatsCreateUtils {
	static function getCyclistResultsForStats($id){ //modified
		$db = DBGetter::connectDB();
		$results = array();

		$sql_query = "SELECT res.time, res.race_id, res.type_id ";
		$sql_query .= "FROM results2 res, races2 rac "; 
		$sql_query .= "WHERE res.user_id = " . $id . " AND res.points > 0.0 ";
		$sql_query .= "AND rac.generalclass = 1 AND res.race_id = rac.id;";
		$res = $db->query($sql_query);

		for ($i = 0; $i < $res->num_rows; $i++){
			$a_result = $res->fetch_assoc();
			$results[$i] = array('time' => $a_result['time'], 
				'race_id' =>$a_result['race_id'], 
				'type_id' => $a_result['type_id']);
		}

		$db->close();
		return $results;
	}

	static function getSumOfKms($cyclist_results){
		$sum_of_kms = 0.;
		for ($i = 0; $i < count($cyclist_results); $i++){
			$sum_of_kms += floatval(DBGetter::getRaceDistance(
				$cyclist_results[$i]['race_id'], 
				$cyclist_results[$i]['type_id']));
		}
		return $sum_of_kms;
	}

	static function getSumOfTime($cyclist_results){
		$sum_of_seconds = 0;
		for ($i = 0; $i < count($cyclist_results); $i++){
			$date_time = explode(' ', $cyclist_results[$i]['time']);
			$time = $date_time[1];
			$seconds = timeStringToSeconds($time); // MKSystemUtil.php
			$sum_of_seconds += $seconds;
		}
		return secondsToTimeString($sum_of_seconds); // MKSystemUtil.php
	}

}

class TopStatsDBGetter {
	static function getValue($column, $user_id){
		$db = DBGetter::connectDB();

		$sql_query = "SELECT $column FROM top_stats WHERE user_id = " . $user_id .";";
		$result = $db->query($sql_query);
		$res = $result->fetch_assoc();
		$db->close();
		return $res["$column"];
	}
}

?>