<?php
session_start();
include(__DIR__ . "/../../mk_dane/secret.php");
include("classes/MKSystemUtil.php");
require_once("classes/Cyclist.php");
include("classes/MailUtil.php");

?>
<html>
<head>
	<title>Maratony Kresowe - lista zgłoszonych</title>
	<?php include("templates/head.tpl"); ?>
</head>
<body>
<?php
if (isset($_SESSION["prev_login"]))
{
	$email_to_sent = $_SESSION["prev_login"];
	

	$cyclist = new Cyclist();

	$db = new mysqli($host, $db_user, $db_pass, $db_name);
	if (mysqli_connect_errno()) {
		$message_not_ok .= "Nie można połączyć się z bazą danych :(";
		exit;
	}


	include('templates/header.tpl');
?>
		<h3>Logowanie</h3>
		<h4>Zapomniałeś hasła?</h4>

<?php

	$email_to_sent = $db->real_escape_string($email_to_sent);

	$sql_query = "SELECT * FROM logins WHERE login = '$email_to_sent';";
	$result = $db->query($sql_query);
	$found_number = $result->num_rows;

	if ($found_number == 1) //przypadek: zawodnik jest w bazie
	{
		$_SESSION['cyclist'] = $email_to_sent;
			
		$new_password = MKSystemUtil::createRandomPassword();
		$cyclist->setLogin($_SESSION['cyclist']);
		$cyclist->setPass($new_password);

		if ($cyclist->updatePass())
		{
			echo "<p>Na Twój adres e-mail: " . $email_to_sent . " wysyłamy nowe hasło, za pomocą którego możesz się zalogować.</p>";
		}
		else
			echo "<p>Niestety. Nie udało się zmienić Twojego hasła (to nasz błąd). Proszę, spróbuj później.</p>";
		//zapisanie nowego hasla w bazie
		
		//wysyłanie maila:
		MailUtil::newPassMail($email_to_sent, $new_password);
	}
	else 
	{
		?>
		<p>Wygląda na to, że nie mamy w bazie nikogo z takim loginem.</p>
		<p>Najpierw <a href="register.php">utwórz konto</a>.</p>
		<?php
	}	
	
}
?>
<p><a href="index.php">Powrót do strony głównej</a></p>
<?
include("templates/footer.tpl");
echo "</body></html>";
?>