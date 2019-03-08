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
<?php
include('templates/header.tpl');

include('contents/applylists.tpl');

include("templates/footer.tpl");
?>
</div>
</body>
</html>