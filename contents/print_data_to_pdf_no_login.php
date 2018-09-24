<?php


$prt_surname = "Nazwisko/ Last name: " . $application_form['surname'];

//echo "<p>" . $prt_nazwisko . "</p>"; //test

$prt_data = array("Imię/ First name: " . $application_form['name'], 
	"Numer startowy/ Starting number: " . $application_form['starting_no'], 
	"Data urodzenia/ Date of birth: " . $application_form['birthyear'],
	"Płeć/ Sex: " . $application_form['sex'],
	"Miejscowość/ Residence: " . $application_form['town'],
	"Kraj/ Country: " . $application_form['country'],
	"Drużyna/ Team: " . $application_form['team'],
	"Adres (ulica, nr domu)/ Address (street, number): ..........................................",
	"Kod pocztowy/ Zip code: " . $application_form['zip_code'],
	"Email: " . $application_form['login'],
	"Telefon/ Phone: " . $application_form['phone'],
	"Osoba do kontaktu w razie wypadku/ Emergency contact person: ..........................................",
	"Telefon do osoby kontaktowej/ Emergency contact person phone: ...............................");

//zapisujemy do pliku dane, ktore maja byc wypisane do pdfa.
try {
	if (!($temp_file = @fopen(__DIR__ . "/../temp/temp_zgloszenia.txt", "w")))
		throw new Exception("Niepowodzenie przy otwarciu tymczasowego pliku. Spróbuj później!");

	flock($temp_file, LOCK_EX); //blokada zapisu dla innych uzytkownikow
	if (!(fwrite($temp_file, $prt_surname . "\n")))
		throw new Exception("Niepowodzenie przy zapisie do tymczasowego pliku. Spróbuj później!");

	for ($i = 0; $i < count($prt_data); $i++)
		fwrite($temp_file, $prt_data[$i] . "\n");
	flock($temp_file, LOCK_UN); //odblokowanie
	fclose($temp_file);
} catch (Exception $e) {
	echo "<p>" . $e->getMessage() . "</p>";
}

$last_name = $application_form['surname'];
require_once(__DIR__ . "/../classes/MyPDF.php");

$pdf = new MyPDF();
$pdf->printDoc(__DIR__ . "/../../../mk_dane/zgloszenia/tresc.txt", 'Formularz zgłoszeniowy');
$pdf->Output(__DIR__ . "/../../../mk_dane/zgloszenia/formularz_mk.pdf", "F");


?>