<?php
session_start();

?>

<html>
<head>
	<title>Maratony Kresowe - Sektory</title>
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
include("contents/start_sectors.tpl");
?>


<?php
include("templates/footer.tpl");
?>

</body>
</html>