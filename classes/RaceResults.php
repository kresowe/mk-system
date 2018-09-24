<?php
require_once("DBGetter.php");

class RaceResults {
	static function getRaceResults($race_id, $type_id){
		$db = DBGetter::connectDB();
		$results = array();

		$sql_query = "SELECT user_id, time FROM results2 WHERE ";
		$sql_query .= "race_id=" . $race_id . " AND type_id = " . $type_id;
		$sql_query .= " AND points > 0.0;";
		$res = $db->query($sql_query);

		for ($i = 0; $i < $res->num_rows; $i++){
			$a_result = $res->fetch_assoc();
			$results[$i] = array('user_id' => $a_result['user_id'], 
				'time' =>$a_result['time']);
		}

		$db->close();
		return $results;
	}
}

?>