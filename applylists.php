<?php
session_start();

include(__DIR__ . "/../../mk_dane/secret.php");
require_once("classes/DBGetter.php");
require_once("classes/MKSystemUtil.php");
?>

<html>
<head>
	<title>Maratony Kresowe - lista zgłoszonych</title>
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
include('contents/applylists.tpl');

include("templates/footer.tpl");
?>
</div>
</body>
</html>