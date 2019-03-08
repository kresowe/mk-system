<?php
session_start();
$_SESSION['mode'] = 'offline';
// $_SESSION['mode'] = 'online';

include(__DIR__ . "/../../mk_dane/secret.php");
include("classes/PageContent.php");
require("classes/ValidateForm.php");
require_once("classes/DBGetter.php");
require_once("classes/MailUtil.php");

if ($_SESSION['mode'] == 'offline') {
	if(isset($_SESSION['user'])){
		header('Location: logout.php');
	}
}

?>

<html>
<head>
	<?php include("templates/head.tpl"); ?>
	<title>Maratony Kresowe - MK System</title>
</head>
<body>


<?php 
$page = new PageContent();
$page->setHeader("Logowanie/ Log in");

$message_ok = "";
$message_not_ok = "";
$login_typed = "";
$password_typed = "";

if (!isset($_SESSION["user"]) && $_SERVER["REQUEST_METHOD"] == "POST") 
{
	$login_typed = $_POST["login"];
	$password_typed = $_POST["password"];
	
	if (!empty($login_typed) && !empty($password_typed))
	{
		$password_typed = sha1($password_typed);

		$db = new mysqli($host, $db_user, $db_pass, $db_name);
		if (mysqli_connect_errno()) {
			$message_not_ok .= "Nie można połączyć się z bazą danych :( | Error.";
			exit;
		}

		$login_typed = $db->real_escape_string($login_typed);

		$sql_query = "SELECT * FROM logins WHERE login = '$login_typed' AND password = '$password_typed' AND active = true";
		$result = $db->query($sql_query);
		$rows = $result->num_rows;

		if ($rows == 1)
		{
			$_SESSION["user"] = $login_typed;
			DBGetter::updateLastLoginDate($login_typed);
			$user = $result->fetch_assoc();
			$_SESSION["role"] = $user['roles_id'];
			if (!DBGetter::checkAccount($_SESSION["user"])){
				// $message_not_ok .= "<h4 class=\"text-danger\">Konto nie jest jeszcze aktywne. Spróbuj później!</h4>";////
				// $message_not_ok .= "<h4 class=\"text-danger\">Profile is not active yet. Please, try again later!</h4>";////
				$message_not_ok .= "<h3 class=\"text-danger\">Wystąpił problem z Twoim kontem. Administracja systemu naprawi go jak najszybciej. Dostaniesz email " .
					"gdy problem zostanie naprawiony.</h3>";
				$message_not_ok .= "<h3 class=\"text-danger\">There is a problem with your account. System admin will solve it as soon as possible. You will receive " .
					"an email when problem is solved.</h3>";
				MailUtil::errorAccountMail($login_typed);
				unset($_SESSION["user"]);
				unset($_SESSION["role"]);
				session_destroy();
			}
			else {
				$page->setHeader("Panel zawodnika/ Rider panel");
			}
		}
		else
		{
			$_SESSION["prev_login"] = $login_typed;
			$message_not_ok .= "Niepoprawny login lub hasło./ Invalid login or password";
			$message_not_ok .= "</p><p><a href=\"forgot.php\" >Zapomniałeś hasła?/ Forgot your password?</a>";
		}
	}
	else 
		$message_not_ok = "Niewpisany login lub hasło./ Enter your login and password.";
}
		 
if (!isset($_SESSION["user"]))
{
	include('templates/header.tpl');
	?>

	<div class="container-fluid mk_margin_bottom">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h3 style="display:inline">Zgłoszenie na maraton - Register for marathon </h3>
		</div>
		<div class="col-md-2 col-xs-12">
			<a href="apply-no-login.php" target="_blank">
			<button type="button" class="btn btn-primary btn-md mk_btn">Zgłoś się - Apply for race!</button>
			</a>
		</div>
		<div class="col-md-6"></div>
	</div>
	</div>

	<div class="container-fluid mk_margin_bottom">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<?php
			if ($_SESSION['mode'] == 'offline') { ?>
			<!-- <h3>Logowanie/ Log in</h3>
			<h4>Logowanie będzie dostępne po najbliższym maratonie. 
				Wciąż możesz się <a href="apply-no-login.php" target="_blank">
				zgłosić bez logowania.</a></h4>
			<h4>Log in will be available after next marathon. You can still 
				<a href="apply-no-login.php" target="_blank"> apply	without login</a></h4> -->
				<?php
			}
			?>

				<?php 
				if ($_SESSION['mode'] != 'offline') {
					include("forms/login_form.tpl");
				}
				?>
		</div>
		<div class="col-md-4"></div>
	</div>
	</div>

	<?php 
	if ($_SESSION['mode'] != 'offline') { ?>
	<div class="container-fluid mk_margin_bottom">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h3 style="display:inline">Nowy użytkownik/ new user:</h3>
		</div>
		<div class="col-md-2 col-xs-12">
			<a href="register.php" class="mk_btn_link">
			<button type="button" class="btn btn-primary btn-md mk_btn">Zarejestruj się - Register!</button>
			</a>
		</div>
	</div>
	</div>

	<?php 
 	}
 	?>
	


	<div class="container-fluid mk_margin_bottom">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h3 style="display:inline">Lista zgłoszonych - List of riders who applied:</h3>
		</div>
		<div class="col-md-2 col-xs-12">
			<a href="applylists.php" class="mk_btn_link">
			<button type="button" class="btn btn-primary btn-md mk_btn">Wyświetl - Show!</button>
			</a>
		</div>
	</div>
	</div>

	<div class="container-fluid mk_margin_bottom">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h3 style="display:inline">Wyszukiwanie zawodnika - Search cyclist:</h3>
		</div>
		<div class="col-md-2 col-xs-12">
			<a href="search-cyclist.php" class="mk_btn_link">
			<button type="button" class="btn btn-primary btn-md mk_btn">Szukaj - Search!</button>
			</a>
		</div>
	</div>
	</div>

	<div class="container-fluid mk_margin_bottom">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h3 style="display:inline">Sektory startowe - Start sectors:</h3>
		</div>
		<div class="col-md-2 col-xs-12">
			<a href="start-sectors.php" class="mk_btn_link">
			<button type="button" class="btn btn-primary btn-md mk_btn">Zobacz - Check!</button>
			</a>
		</div>
	</div>
	</div>
	
	<?php 
}
else
{
	$page->setHeader("Panel zawodnika/ Rider panel");
 
	include('templates/header.tpl');

	echo "<h3>" . $page->getHeader() . "</h4>";
	 ?> 
	</header>
	<div id="login"> <?php include("templates/login.tpl"); ?> </div> 

	<div id="main_part">
	<div class="row">
	 <nav> <?php include("templates/menu.tpl"); ?> </nav>
	<article>
		<div class="col-sm-9 hidden-xs">
			<?php 
			include("content.php"); ?>
		</div>
		<div class="col-xs-12 hidden-sm hidden-md hidden-lg">
			<div class="row">
			<?php 
			include("content.php"); ?>
			</div>
		</div>
	</article>   
	</div>
	</div>

<?php
	/*
	echo "<p> Jesteś zalogowany jako: " . $_SESSION['user'] ."</p>";
	echo "<a href=\"logout.php\" id=\"wylogowanie\" >Wyloguj się</a>";*/
} 
include("templates/footer.tpl");
?>
</body>
</html>