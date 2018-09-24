<?php


$prt_surname = "Nazwisko/ Last name: " . $cyclist->getSurname();

//echo "<p>" . $prt_nazwisko . "</p>"; //test

$prt_data = array("Imię/ First name: " . $cyclist->getName(), 
	"Numer startowy/ Starting number: " . $cyclist->getStartNumber(), 
	"Data urodzenia/ Date of birth: " . $cyclist->getBirthyear(),
	"Płeć/ Sex: " . $cyclist->getSex(),
	"Miejscowość/ Residence: " . $cyclist->getTown(),
	"Kraj/ Country: " . $cyclist->getCountry(),
	"Drużyna/ Team: " . $cyclist->getTeam(),
	// "Kod UCI/ UCI code: " . $licencja,
	// "Rodzaj licencji/ Typ of license: " . $pp_kategoria,
	"Adres (ulica, nr domu)/ Address (street, number): ..........................................",
	"Kod pocztowy/ Zip code: " . $cyclist->getZipCode(),
	"Email: " . $cyclist->getEmail(),
	"Telefon/ Phone: " . $cyclist->getPhone(),
	"Osoba do kontaktu w razie wypadku/ Emergency contact person: ..........................................",
	"Telefon do osoby kontaktowej/ Emergency contact person phone: ...............................");

//zapisujemy do pliku dane, ktore maja byc wypisane do pdfa.
try {
	if (!($temp_file = @fopen(__DIR__ . "/../temp/temp_zgloszenia.txt", "w")))
		throw new Exception("qqNiepowodzenie przy otwarciu tymczasowego pliku. Spróbuj później!");

	flock($temp_file, LOCK_EX); //blokada zapisu dla innych uzytkownikow
	if (!(fwrite($temp_file, $prt_surname . "\n")))
		throw new Exception("wwNiepowodzenie przy zapisie do tymczasowego pliku. Spróbuj później!");

	for ($i = 0; $i < count($prt_data); $i++)
		fwrite($temp_file, $prt_data[$i] . "\n");
	flock($temp_file, LOCK_UN); //odblokowanie
	fclose($temp_file);
} catch (Exception $e) {
	echo "<p>" . $e->getMessage() . "</p>";
}

$last_name = $cyclist->getSurname();
require_once(__DIR__ . "/../classes/MyPDF.php");

$pdf = new MyPDF();
$pdf->printDoc(__DIR__ . "/../../../mk_dane/zgloszenia/tresc.txt", 'Formularz zgłoszeniowy');
$pdf->Output(__DIR__ . "/../../../mk_dane/zgloszenia/formularz_mk.pdf", "F");




?>