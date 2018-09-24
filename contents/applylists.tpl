<h2>Lista zgłoszonych przez system internetowy</h2>
<h2>List of riders who applied via Internet system.</h2>
<?php
if (isset($_SESSION["user"])){
	$action = 'index.php?page=applylists';
	require_once('const_definitions.php');
	require_once('classes/Cyclist.php');
} else {
	$action = 'applylists.php';
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["submit_applylists_form"])) {
	$race_id = $_POST['marathon'];
}
include("forms/applylists_form.tpl");


if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["submit_applylists_form"])) {
	$race_id = $_POST['marathon'];
	$race = DBGetter::getRaceById($race_id);

	//TODO: in future only filename2!
	// $filename = substr($race['place'], 0, 3) . date("Y") . ".csv"; ///
	$filename2 = $race['date'] . 
		substr(textWithoutPolishChars($race['place']), 0, 3) . ".csv";
	// $path = __DIR__ . "/../../../mk_dane/zgloszenia/" . $filename; ///
	$path2 = __DIR__ . "/../../../mk_dane/zgloszenia/" . $filename2;
	// echo "<p>" . $path2 . "</p>";
	if (file_exists($path2)){
		// $riders = MKSystemUtil::getApplyListFromFile($path);
		$riders = MKSystemUtil::getApplyListFromFile($path2);
		// for ($i = 0; $i < count($riders2); $i++){
		// 	array_push($riders, $riders2[$i]);
		// }
		// usort($riders, 'compare_riders');
		include("templates/applylists_table.tpl");
	}
	else {
		echo  "<h3>Brak zgłoszeń na ten maraton.</h3>";
		echo  "<h3>No riders applied for this marathon yet.</h3>";
	}
	
}
