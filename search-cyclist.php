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
<header class="mk_margin_bottom">
<h1>System Maratonów Kresowych</h1>
</header>
<div class="container-fluid">
<a href="index.php" class="mk_btn_link">
<button type="button" class="btn btn-primary btn-md">Powrót na główną - Back to home</button>
</a>

<?php
$path_to_file = '';
include("contents/search_cyclist.tpl");

include("templates/footer.tpl");
?>

</body>
</html>