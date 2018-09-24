<?php
require_once("DBGetter.php");
require(__DIR__ . "/../defines/payment_defs.php");


class Payment {
	static function countPayment($marathon_id, $payment_id, $distance_id, $country, 
			$birthyear, $license){
		$marathon = DBGetter::getRaceById($marathon_id);
		$distance = DBGetter::getDistanceName($distance_id);
		$payment_name = DBGetter::getPaymentName($payment_id);

		$marathon_date = date_create($marathon['date']);
		$today_date = date_create();
		$date_difference = self::dayDifference($marathon_date, $today_date);

		//tworzymy zmienne dla nazw sposobow platnosci i dystansow dla code readability w ponizszej czesci.
		$payments = array(
			'cash' => DBGetter::getPaymentNameByAbbrev("got"),
			'transfer' => DBGetter::getPaymentNameByAbbrev("prz"),
			'multi' => DBGetter::getPaymentNameByAbbrev("pak")
			);

		$distances = array(
			'mikro' => DBGetter::getDistanceNameByAbbrev("mik"),
			'mini' => DBGetter::getDistanceNameByAbbrev("min"),
			'polmar' => DBGetter::getDistanceNameByAbbrev("pol"),
			'maraton' => DBGetter::getDistanceNameByAbbrev("mar")
			);

		$to_print = '';

		//logic
		//zawodnicy z Polski
		//platnosc gotowka
		if ($payment_name == $payments['cash'] && $country == "POL") {
			$to_print .= self::printCashMessage();

			if ($distance == $distances['mikro']){
				$to_print .= self::printToPay(MIKRO_NOR);
			}
			if ($distance == $distances['mini']){
				$to_print .= self::printToPay(MINI_NOR);
			}
			else if($distance == $distances['polmar'] || $distance == $distances['maraton']){
				$to_print .= self::printToPay(MAR_NOR);
			}
			if ($distance != $distances['mikro']){
				$to_print .= self::printFirstStartMessage();
			}
		}
		//przelewem
		else if($payment_name == $payments['transfer'] && $country == "POL"){
			//mini
			// echo "<p>date difference = " .  $date_difference . "</p>";
			if ($distance == $distances['mini']){
				if ($date_difference >= DNI_PRZED_KONIEC_ULGI) {
					$to_print .= self::printInAdvancePayMessage($date_difference, 
						DNI_PRZED_KONIEC_ULGI, MINI_ULG, MINI_NOR);
					$to_print .= self::printTransferInstruction();
				}
				else {
					$to_print .= self::printLatePayMessage(MINI_NOR);
				}
			}
			// mikro
			else if ($distance == $distances['mikro']){
				if ($date_difference >= DNI_PRZED_KONIEC_ULGI) {
					$to_print .= self::printInAdvancePayMessage($date_difference, 
						DNI_PRZED_KONIEC_ULGI, MIKRO_ULG, MIKRO_NOR);
					$to_print .= self::printTransferInstruction();
				}
				else {
					$to_print .= self::printLatePayMessage(MIKRO_NOR);
				}
			}
			//polmaraton i maraton
			else if ($distance == $distances['polmar'] || $distance == $distances['maraton']){
				//ile placa
				if ($date_difference >= DNI_PRZED_KONIEC_ULGI){
					$to_pay_before = MAR_ULG;
					$to_pay_after = MAR_NOR;
				} else {
					$to_pay_after = MAR_NOR;
				}

				//odpowiednie komunikaty
				if ($date_difference >= DNI_PRZED_KONIEC_ULGI) {
					$to_print .= self::printInAdvancePayMessage($date_difference, DNI_PRZED_KONIEC_ULGI, $to_pay_before, $to_pay_after);
					$to_print .= self::printTransferInstruction();
				} else {
					$to_print .= self::printLatePayMessage($to_pay_after);
				}
			}

			if ($distance != $distances['mikro']) {
				$to_print .= self::printFirstStartMessage();
			}
			if ($license == 'T'){
				$to_print .= self::printLicenseMessage();
			}
		}

		//Niemenczyn - zyczenia Mariana
		else if (($country == "LTU" || $country == 'LT') && 
			$marathon_date == date_create('2017-05-14') && $payment_name != $payments['multi']) {
			$to_print = self::printNemencineLtuMessage();
		}

		//zawodnicy z zagranicy
		else if ($country != "POL" && $payment_name != $payments['multi']) {
			// ulgowo
			if ($date_difference >= DNI_PRZED_KONIEC_ULGI){
				//ile placa
				if ($distance == $distances['mini']) {
					$to_print .= self::printToPayInt(MINI_ULG, MINI_ULG_EUR);
				}
				else if ($distance == $distances['polmar'] || $distances['maraton']){
					$to_print .= self::printToPayInt(MAR_ULG, MAR_ULG_EUR);
				}
				else if ($distance == $distances['mikro']){
					$to_print .= self::printToPayInt(MIKRO_ULG, MIKRO_EUR);
				}
			}
			else {
				if ($distance == $distances['mini']) {
					$to_print .= self::printLatePayMessageInt(MINI_NOR, MINI_NOR_EUR);
				}
				else if ($distance == $distances['polmar'] || $distances['maraton']){
					$to_print .= self::printToPayInt(MAR_NOR, MAR_NOR_EUR);
				}
				else if ($distance == $distances['mikro']){
					$to_print .= self::printToPayInt(MIKRO_NOR, MAR_NOR_EUR);
				}
			}
			//sposob platnosci
			if ($payment_name == $payments['cash']){
				$to_print .= self::printCashMessageInt();
			}
			else if ($payment_name == $payments['transfer']) {
				$to_print .= self::printTransferInstructionInt();
			}

			if ($distance != $distances['mikro']){
				$to_print .= self::printFirstStartMessageInt();
			}
		}


		//return $to_print;
	}

	static function dayDifference($date1, $date2){
		$day_of_date1 = intval($date1->format("z"));	//The day of the year (starting from 0)
		$day_of_date2 = intval($date2->format("z"));
		return $day_of_date1 - $day_of_date2;
	}

	static function printCashMessage(){
		$text = "<p>Możesz zapłacić gotówką w biurze zawodów w dniu zawodów, przed maratonem.";
		$text .= " Polecamy opłaty przelewem - można je wykonać wcześniej i wtedy stosujemy zniżki.</p>";
		echo $text;
		return $text;
	}

	static function printCashMessageInt(){
		$text = "<p>You can pay in cash in marathon's office in marathon's";
		$text .= " day.</p>";
		$text .= "<p>We advise you to pay earlier than 5 days before race next time ";
		$text .= "- you will get discount.</p>";
		echo $text;
		return $text;
	}

	static function printToPay($how_much){
		$text = "<p>Do zapłaty: " . $how_much . "zł.</p>";
		echo $text;
		return $text;
	}

	static function printToPayInt($how_much, $how_much_eur) {
		$text = "<p>To pay: " . $how_much . "zl (" . $how_much_eur . " EUR).</p>";
		echo $text;
		return $text;
	}

	static function printFirstStartMessage(){
		$text = "<p>Jeśli to Twój pierwszy maraton w sezonie i nie masz chipa, musisz jeszcze zapłacić za chip i numery startowe: ";
		$text .= PAKIET . "zł.</p>";
		$text .= "<p>Jeśli zwrócisz numery startowe i chip tego samego dnia do ";
		$text .= "biura zawodów, otrzymasz:";
		$text .= ZWROT . " zł.</p>";
		$text .= "<p>Jeśli masz już chip z poprzedniego sezonu, ale to Twój pierwszy start w sezonie,";
		$text .= " to musisz zapłacić tylko za numery startowe " . NR_START . " zł.</p>";
		echo $text;
		return $text;
	}

	static function printFirstStartMessageInt(){
		$text = "If it is your first marathon in this season and you don't have a chip, ";
		$text .= "you have to pay for ";
		$text .= "chip and starting numbers additionally: ";
		$text .= PAKIET . "zł (" . PAKIET_EUR . " EUR).</p>";
		$text .= "<p>If you give starting numbers and chip back to the office the ";
		$text .= "same day, you will get ". ZWROT . " zl (" . ZWROT_EUR . " EUR) change.</p>";
		$text .= "<p>If you have your chip from the previous season, but it's your first race in ";
		$text .= "this season, you have to pay only for starting numbers: " . NR_START . " zł (";
		$text .= NR_START_EUR . " EUR).</p>";
		echo $text;
		return $text;
	}

	static function printInAdvancePayMessage($date_difference, $days_discount, $discount_price, $normal_price){
		$text = "<p>Jeśli Twój przelew dotrze w ciągu " . ($date_difference - $days_discount) . " dni, to płacisz " . $discount_price ." zł.";
		$text .= "Później płacisz " . $normal_price . " zł.</p>";
		echo $text;
		return $text;
	}

	static function printPayMessage($date_difference, $days, $price, $cash_price){
		$text = "<p>Jeśli Twój przelew dotrze w ciągu " . ($date_difference - $days) . " dni, to płacisz " . $price ." zł.";
		$text .= "Później płacisz " . $cash_price . " zł w biurze zawodów w dniu maratonu.</p>";
		echo $text;
		return $text;
	}

	static function printLatePayMessage($price){
		$text = "<p>Do zapłaty " . $price;
		$text .= " zł. Płacąc za maraton wcześniej niż 5 dni przed, załapiesz się na ceny zniżkowe!</p>";
		echo $text;
		return $text;
	}

	static function printLatePayMessageInt($price, $price_eur){
		$text = "<p>To pay: " . $price . "zl (" . $price_eur;
		$text .= " EUR). We advise you to pay earlier than 5 days before race next time ";
		$text .= "- you will get discount.</p>";
		echo $text;
		return $text;
	}

	static function printTransferInstruction(){
		$text = "<p>Na przelewie bankowym lub pocztowym należy koniecznie zaznaczyć " . 
			"tytuł opłaty, np.: \"Wpłata na Maraton Kresowy - Supraśl\"" . 
			". W przypadku nie podania " . 
			"tytułu wpłaty zostanie ona zaksięgowana na poczet najbliższego terminu " . 
			"maratonu zgodnie z obowiązującą taryfą. Przelewów należy dokonywać na " . 
			"minimum 3 dni przed terminem zawodów.</p>";
		$text .= "Fundacja \"Maratony Kresowe\"<br>"; 
		$text .= "ul. Ciołkowskiego 157<br>";
		$text .= "15-545 Białystok<br>";
		$text .= "Konto: 70 1140 2004 0000 3402 7478 4775<br>";
		echo $text;
		return $text;
	}

	static function printTransferInstructionInt(){
		$text = "<p>The subject of cash transfer should be, i.e. ";
		$text .= "\"Wplata na Maraton Kresowy - Suprasl\"."; 
		$text .= " Otherwise, it will be for the ";
		$text .= "closest marathon. Cash transfers can be done at least ";
		$text .= "3 days before the marathon.</p>";
		$text .= "Fundacja \"Maratony Kresowe\"<br>"; 
		$text .= "ul. Ciołkowskiego 157<br>";
		$text .= "15-545 Białystok<br>";
		$text .= "Account: 70 1140 2004 0000 3402 7478 4775<br>";
		$text .= "See: <a href=\"http://maratonykresowe.pl/?page_id=2\" target=\"_blank\">international transfer info</a>.<br>";
		echo $text;
		return $text;
	}

	static function printLicenseMessage(){
		$text = "<p>Jeśli masz jedną z licencji PZKol: Młodzik, Junior Młodszy, Junior, U23, Elita,";
		$text .= " to przysługuje Ci zniżka 50%. Szczegóły w pkcie 6. w ";
		$text .= " <a href=\"http://maratonykresowe.pl/?page_id=3822\" target=\"_blank\">regulaminie</a>";
		echo $text;
		return $text;
	}

	static function printNemencineLtuMessage(){
		$text = "<h3>MTB Dviračių maratono „Wilia“ NUOSTATAI - Nemenčinė 2016-05-14</h3>";
		$text .= "<p><strong>5. Starto mokesčiai. </strong></p>";
		$text .= "<p>MIKRO:</p>";
		$text .= "<p>Nemokamai – 4-6 metų vaikai (2011-2013 gim. m.)</p>";
		$text .= "<br \>";
		$text .= "<p>AUTHOR MINI MARATON:</p>";
		$text .= "<p>5,00 € - starto mokestis (registracija ir pavedimas 2 dienos iki varžybų)</p>";
		$text .= "<p>10,00 € - starto mokestis grynaisiais varžybų dieną. </p>";
		$text .= "<p>3,00 € - vienkartinis mokestis už globėjo starto numerį Author Mini Maraton distancijoje. </p>";
		$text .= "<br \><p>PUSĖ MARATONO IR KELLYS MARATONAS: </p>";
		$text .= "<p>10,00 € - starto mokestis (registracija ir pavedimas 2 dienos iki varžybų)</p>";
		$text .= "<p>15,00 € - starto mokestis grynaisiais varžybų dieną.</p>";
		$text .= "<br />";
		$text .= "<p>Varžybų dalyviai sumoka 10 € užstatą už starto numerį bei čipą. Užstatas yra grąžinamas atiduodant komplektą. PASTABA. Starto mokestį galima apmokėti Lenkijos zlotais.</p>";
		echo $text;
		return $text;
	}

	// static function printMikroMessage() {
	// 	$text = "<p>Udział w dystansie Mikro jest darmowy.</p>";
	// 	echo $text;
	// 	return $text;
	// }

	// static function printMikroMessageInt(){
	// 	$text = "<p>Mikro distance is for free!</p>";
	// 	echo $text;
	// 	return $text;
	// }


}

?>