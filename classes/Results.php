<?php
require_once("DBGetter.php");

class Results {
	private $cyclist_results;
	private $race_results;
	private $cyclist_results_nice;
	private $number_of_results;
	private $total_stats;

	function __construct() {
		$this->cyclist_results = array();
		$this->race_results = array();
		$this->cyclist_results_nice = array();
	} 

	public function saveCyclistResults($cyclist){
		/* 
		get all the $cyclist's results and save them in field $cyclist_results
		results are in form of results2 table and saved as assoc table.
		$cyclist - instance of Cyclist class
		*/
		$db = DBGetter::connectDB();

		$sql_query = "SELECT res.*, races.date FROM results2 res, races2 races WHERE " .  
			" res.user_id = " . $cyclist->getId() . " AND res.race_id = races.id " .
			" AND res.points > 0.0 ORDER BY races.date DESC;";
		$res = $db->query($sql_query);

		$this->number_of_results = $res->num_rows;

		for ($i = 0; $i < $res->num_rows; $i++) {
			$this->cyclist_results[$i] = $res->fetch_assoc();
		}

		$res->free();
		$db->close();
	}

	public function saveTotalStats($cyclist){
		$db = DBGetter::connectDB();

		$sql_query = "SELECT * FROM top_stats WHERE user_id = " . $cyclist->getId() . ";";
		$res = $db->query($sql_query);
		$this->total_stats = $res->fetch_assoc();

		$res->free();
		$db->close();
	}

	public function saveRaceResults($cyclist_sex){
		/*
		get full results of each race of the cyclist (his/her type of race (e.g. Polmaraton) and sex) and
		save them in field $cyclist_results. This is array of assoc arrays.
		This method should be called after saveCyclistResults()
		*/
		$db = DBGetter::connectDB();
		for ($i = 0; $i < count($this->cyclist_results); $i++) {
			$this->race_results[$i] = array();
			$sql_query = "SELECT r.user_id, r.time, r.points FROM results2 r, users2 u " . 
						" WHERE r.race_id = " . $this->cyclist_results[$i]['race_id'] . 
						" AND r.type_id = " . $this->cyclist_results[$i]['type_id'] .
						" AND r.points > 0 AND u.sex = '" . $cyclist_sex . "'" .
						" AND u.id = r.user_id ORDER BY r.points DESC;";
			//echo "<p>" . $sql_query . "</p>";
			$race_res = $db->query($sql_query);
			
			for ($j = 0; $j < $race_res->num_rows; $j++) {
				$this->race_results[$i][$j] = $race_res->fetch_assoc();
			}
			
		}

		//echo "<pre>" . var_dump($this->race_results) . "</pre>";

		$db->close();
	}

	public function saveCyclistResultsNice(){
		/*
		modify results in form of $cyclist_results by adding further fields: winner_time, place_open and 
		participants which are taken from private methods.
		*/
		for ($i = 0; $i < count($this->cyclist_results); $i++) {
			$a_race_result = array();
			foreach ($this->cyclist_results[$i] as $key => $val) {
				$a_race_result[$key] = $val;
			}
			$a_race_result['winner_time'] = $this->getRaceWinnerTime($i);
			$a_race_result['place_open'] = $this->getPlace($i, $a_race_result['user_id']);
			$a_race_result['participants'] = $this->getNumberOfParticipants($i);
			$this->cyclist_results_nice[$i] = $a_race_result;
		}

		//echo "<pre>" . 
	}


	private function getRaceWinnerTime($race_number){
		$race_full_results = $this->race_results[$race_number];
		$time_string = explode(' ', $race_full_results[0]['time']);
		return $time_string[1];
	}

	private function getPlace($race_number, $cyclist_id){
		$race_full_results = $this->race_results[$race_number];
		for ($i = 0; $i < count($race_full_results); $i++) {
			if ($race_full_results[$i]['user_id'] == $cyclist_id) {
				return ($i + 1);
			}
		}
		return 0; //fail
	}

	private function getNumberOfParticipants($race_number){
		return count($this->race_results[$race_number]);
	}

	public function getCyclistResultsNice(){
		return $this->cyclist_results_nice;
	}

	public function getNumberOfResults(){
		return $this->number_of_results;
	}

	public function getTotalNumberOfResults(){
		return $this->total_stats['races'];
	}

	public function getTotalDistance(){
		return $this->total_stats['kms'];
	}

	public function getTotalTime(){
		return $this->total_stats['time_sum'];
	}
}
?>