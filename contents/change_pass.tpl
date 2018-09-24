<?php
include(__DIR__ . "/../../../mk_dane/secret.php");
require_once(__DIR__ . "/../classes/Cyclist.php");

$typed = false;
$accepted = true; 
?>

<h3>Zmiana hasła</h3>

<?

if (isset($_SESSION["user"]))
{

	$user_login = $_SESSION["user"];
	$err_password = $err_pass_repeat = "";
	$new_password = $new_pass_repeat =  "";

	$db = new mysqli($host, $db_user, $db_pass, $db_name);
	if (mysqli_connect_errno()) 
	{
		$message_not_ok .= "Nie można połączyć się z bazą danych :(";
		exit;
	}

	//walidacja
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
		$typed = false;
		$accepted = true;  

		ValidateForm::validatePassword($new_password, $err_password, $accepted);
		ValidateForm::validatePassword2($new_pass_repeat, $err_pass_repeat, $accepted, $new_password);
		if (!empty($_POST["pass"]))
			$typed = true;
	} // end :if(request_method[post])

	//zmieniamy hasło
	if ($accepted && $typed)
	{ 
		$cyclist = new Cyclist();
		$cyclist->setLogin($_SESSION['user']);
		$cyclist->setPass($new_password);
		if ($cyclist->updatePass())
		{ ?>
			<p>Hasło zostało zmienione.</p>
			<?php
		}
		else
		{ ?>
			<p>Zmiana hasła nie powiodła się. Proszę, spróbuj później.</p>
			<?php
		}
	} //end if (accepted...)
	else 
	{	//wyswietlamy formularz.
		include(__DIR__ . "/../forms/change_pass_form.tpl");
	}
	if (!($accepted) && $typed)
		echo "<p>Zmiana hasła nie powiodła się.</p>";


} //end: if (isset sesssion)
else
{
	?>
	<p>Aby oglądać tę stronę musisz się najpierw zalogować!</p>
	<?php
}

?>
