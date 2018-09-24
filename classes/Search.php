<?php

require_once("DBGetter.php");

class Search {
	static function parseSearchFields($search_fields){
		$sql = '';
		$sql_surname = '';
		$sql_starting_no = '';

		if (strlen($search_fields['surname']) > 2) {
			$sql_surname = " u.surname LIKE \"%" . $search_fields['surname'] .  "%\" ";
			$sql .= $sql_surname;
		}
		if (strlen($search_fields['starting_no']) > 0) {
			if (strlen($sql_surname) > 0) {
				$sql .= " AND ";
			}
			$sql_starting_no = " sn.start_number = " . $search_fields['starting_no'] .
				" AND sn.dateto >= (SELECT NOW()) ";
			$sql .= $sql_starting_no;
			$sql .= " AND u.id = sn.user_id ";
		}
		
		return $sql;
	}

	static function findCyclists($search_fields) {
		$db = DBGetter::connectDB();

		$sql_query = "SELECT u.id, u.name, u.surname, u.country_iso3 FROM " .
			 " users2 u, start_numbers2 sn WHERE ";
		$sql_to_add = self::parseSearchFields($search_fields);
		$sql_query .= $sql_to_add;
		$sql_query .= " GROUP BY u.id;";
		// echo "<p>" . $sql_query . "</p>"; //test

		$result = $db->query($sql_query);

		$cyclists = array();

		for ($i = 0; $i < $result->num_rows; $i++){
			$a_cyclist = $result->fetch_assoc();
			$cyclists[$i] = $a_cyclist;
		}
		//echo "<pre>" . var_dump($cyclists) . "</pre>";
		return $cyclists;
	}
}

?>