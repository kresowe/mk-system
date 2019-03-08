<?php
session_start();

require_once("classes/Search.php");
require_once("classes/Cyclist.php");
require_once("classes/MKSystemUtil.php");
require_once("classes/ValidateForm.php");
require_once("const_definitions.php");

?>

<html>
<head>
	<title>Maratony Kresowe - znajdź zawodnika</title>
	<?php include("templates/head.tpl"); ?>
</head>

<body>

<?php
include('templates/header.tpl');

$path_to_file = '';
include("contents/search_cyclist.tpl");

include("templates/footer.tpl");
?>

</body>
</html>