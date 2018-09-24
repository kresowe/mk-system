<?php

//date1 and date2 must be in the same year!
function dates_difference($date1, $date2) {
	$day_of_year1 = intval($date1->format("z"));	//The day of the year (starting from 0)
	$day_of_year2 = intval($date2->format("z"));
	return $day_of_year2 - $day_of_year1;
}

function compare_riders($r1, $r2){
	if (strtoupper($r1['last_name']) < strtoupper($r2['last_name']))
		return -1;
	else if (strtoupper($r1['last_name']) > strtoupper($r2['last_name']))
		return 1;
	else
		return 0;
}

function validate_first_char_in_row($ch) {
	$pattern = "/^[A-Za-z]$/";
		if (!preg_match($pattern, $ch))
			return true;
		else
			return false;
}

function textWithoutPolishChars($text) {	
	$converts = array(
		'Ó' => 'O',
		'Ś' => 'S',
		'Ł' => 'L',
		'Ż' => 'Z',
		'Ź' => 'Z',
		'Ć' => 'C',
		'ę' => 'e',
		'ó' => 'o',
		'ą' => 'a',
		'ś' => 's',
		'ł' => 'l',
		'ż' => 'z',
		'ź' => 'z',
		'ć' => 'c',
		'ń' => 'n'
	);
	$text = strtr($text, $converts);
	return $text;
}

function timeStringToHours($timeString){
	$timeHoursMinsSecs = explode(':', $timeString);
	return floatval($timeHoursMinsSecs[0]) + 
			floatval($timeHoursMinsSecs[1])/ 60.0 + 
			floatval($timeHoursMinsSecs[2])/ 3600.0; 
}

function timeStringToSeconds($timeString){
	$timeHoursMinsSecs = explode(':', $timeString);
	return intval($timeHoursMinsSecs[0])*3600 + 
			intval($timeHoursMinsSecs[1])*60 + 
			intval($timeHoursMinsSecs[2]);
}

function secondsToTimeString($seconds){
	$hours = intval($seconds / 3600);
	$mins = intval(($seconds - $hours*3600)/60);
	$secs = $seconds - $hours*3600 - $mins*60;
	return strval($hours) . ':' . strval($mins) . ':' . strval($secs);
}



class MKSystemUtil {
	static function createRandomPassword() {
		$length_of_password = 8;
		$pass = "";
		$characters = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
		$max_index = count($characters) - 1;
		for ($i = 0; $i < $length_of_password; $i++)
		{
			$rand = mt_rand(0, $max_index);
			$pass .= $characters[$rand];
		}
		return $pass;
	}

	static function saveTextChanges($text){
		$log_file_name = __DIR__ . "/../../../mk_dane/log_changes_text.sql";
		$file = fopen($log_file_name, "a") or die("Problem z zapisem do pliku!/ Error");
		$header = "-- " . date("Y-m-d H:i:s\n");
		fwrite($file, $header);
		$text .= "\n\n";
		fwrite($file, $text);
		fclose($file);
	}

	static function getApplyListFromFile($path){
		$fin = fopen($path,  "r");	
		$riders = array();

		while (!feof($fin) && strlen($row = fgets($fin)) > 0){
			if (validate_first_char_in_row($row[0])){
				$row = html_entity_decode($row);
				$rider = explode(';', $row);
				for ($i = 0; $i < count($rider); $i++){
					if ($rider[$i][0] == '"' ){
						$rider[$i] = substr($rider[$i], 1, count($rider[$i]) - 2);
					}
				}
				array_push($riders, array(
					'nr' => $rider[0], 
					'first_name' => $rider[2],
					'last_name' => $rider[1],
					'birthyear' => substr($rider[4], 0, 4),
					'country' => $rider[6],
					'city' => $rider[8],
					'team' => $rider[10],
					'distance' => $rider[12],
					));
			}	
		}
		fclose($fin);
		usort($riders, 'compare_riders');
		return $riders;
	}

	static function printApplicationToCsvNoLogin($application_form, $marathon){
		$filename = __DIR__ . "/../../../mk_dane/zgloszenia/" . $marathon['date'] . 
			substr(textWithoutPolishChars($marathon['place']), 0, 3) . ".csv";
		$file_out = fopen($filename, "a") or die("Problem z plikiem zapisu!| Error");

		foreach ($application_form as $i) {
			fputcsv($file_out, $i, ";");
		}
		
		fclose($file_out);
	}

	static function calculateSpeed($distance, $time) {
		return number_format((floatval($distance) / timeStringToHours($time)), 1);
	}

}

?>