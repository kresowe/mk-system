<?php

/**
* 
*/
class Validating
{
	
	static function adjustInput(&$data) { 
	   $data = trim($data); 
	   $data = stripslashes($data); 
	   $data = htmlspecialchars($data); 
	   return $data;
	}

	static function validText($text) {
		$pattern = "/^[a-zA-Z\p{Cyrillic}\s\-ęłóąśŚŁżŻźŹćĆń]+$/";
		if (!preg_match($pattern, $text))
			return false;
		else
			return true;
	}

	static function validBirthYear($text) {
		if (!preg_match("/^\d{4}$/", $text))
			return false;
		if ($text < 1900 || $text > MAXIMAL_BIRTH_YEAR)
			return false;
		return true;
	}

	static function validEmail($text) {
		$text = filter_var($text, FILTER_SANITIZE_EMAIL);
		if (filter_var($text, FILTER_VALIDATE_EMAIL))
			return true;
		else
			return false;
	}

	static function validStartingNo($text) {
		if (!preg_match("/^[1-9][0-9]{0,3}$/", $text))
			return false;
		return true;
	}

	static function validCountry($text) {
		if (!preg_match("/^[A-Z]{3}$/", $text))
			return false;
		return true;	
	}

	static function validZipCode($text){
		if (!preg_match("/^[0-9]{2}\-[0-9]{3}$/", $text))
			return false;
		return true;
	}

	static function validPhone($text) {
		if (!preg_match("/^\+\d{11,13}$/", $text))
			return false;
		return true;
	}

	static function validUCICode($text) {
		if (!preg_match("/^[A-Z]{3}[1-2][0-9]{7}$/" , $text))
			return false;
		return true;
	}

	static function makeSafeForSql($text){
		include(__DIR__ . "/../../../mk_dane/secret.php"); 
		$db = new mysqli($host, $db_user, $db_pass, $db_name);
		if (mysqli_connect_errno()) 
			echo "<p>Nie można połączyć z bazą danych: " . mysql_connect_error() . "</p>";
		$text = $db->real_escape_string($text);
		return $text;
	}

}



?>