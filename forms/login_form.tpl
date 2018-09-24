<form action=<?php echo "\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\"";?> method="post" class="form-horizontal" role="form">
<div class="form-group">
<label class="control-label col-sm-1" for="login">Email:</label>
<div class="col-sm-4">
<input type="text" name="login" id="login" class="form-control" placeholder="Wpisz e-mail/ Enter e-mail" value= <?php echo "\"" . $login_typed . "\""; ?> >
</div>
</div>

<div class="form-group">
<label class="control-label col-sm-1" for="password">Hasło/ Password/ пароль/ slaptažodis:</label>
<div class="col-sm-4">
<input type="password" name="password" id="password" class="form-control" placeholder="Wpisz hasło/ Enter password">
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-1 col-sm-10">
<input type="submit" name="submit_find_user2" value="Zaloguj się/ Log in" class="btn btn-success">	</div>
</div>
</form>
<p class="text-danger"> <?php echo $message_not_ok; ?></p>
<a href="register.php" id="rejestracja" class="text-center"><strong>Nie masz konta? Zarejestruj się/ Create an account/ создать аккаунт/ Pridėkite paskyrą.</strong></a>