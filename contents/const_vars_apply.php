<?php

// $payments = array("Gotowka/ Cash", "Przelew/ Cash transfer", 
// 	"Pakiet wielostartowy/ Multistart package", "Drużyna/ Team");

//Ograniczenia wiekowe
define("MINIMAL_BIRTH_YEAR", 2017);
define("MAX_BIRTH_YEAR_MIKRO", 2013);
define("MIN_BIRTH_YEAR_MINI", 2012);
define("MAX_BIRTH_YEAR_MINI", 2007);
define("MIN_BIRTH_YEAR_FIT", 2006);
define("MIN_BIRTH_YEAR_POLMAR_M", 2004);
define("MIN_BIRTH_YEAR_POLMAR_K", 2004);
define("MIN_BIRTH_YEAR_MAR_M", 2001);
define("MIN_BIRTH_YEAR_MAR_K", 2001);

//pola formularza
$marathon = '';
$distance = '';
$payment = '';
$notes = '';
$note_start_number = '';
$accept_rules = '';

//komunikaty o bledach
$err_distance = '';
$err_payment = '';
$err_notes = '';
$err_note_start_number = '';
$err_accept_rules = '';

$accepted = true; //register_form
$filled = false;

?>