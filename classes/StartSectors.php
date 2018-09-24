<?php
require_once("DBGetter.php");

class StartSectors {
	static function getStartSectorTable($type){
		//$type: 2 = maraton, 3 = mini, 1 = polmaraton
		$db = DBGetter::connectDB();
		$results = array();

		$sql_query = "SELECT surname, name, points_sum, start_sector "; 
		$sql_query .= "FROM start_sectors2 ";
		$sql_query .= "WHERE type_id=" . $type;
		$sql_query .= " AND year=" . date("Y");
		$sql_query .= " ORDER BY surname;";
		$res = $db->query($sql_query);

		for ($i = 0; $i < $res->num_rows; $i++){
			$a_result = $res->fetch_assoc();
			$results[$i] = array(
				'surname' =>$a_result['surname'], 
				'name' => $a_result['name'], 
				'points_sum' => $a_result['points_sum'],
				'start_sector' => $a_result["start_sector"]);
		}

		$db->close();
		return $results;

	}
}

?>