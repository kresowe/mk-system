<?php
echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"post\" id=\"form_create_account\">";
?>

<table class="table table-striped table-condensed">
<tr><td>Nowy email/ new email/ новый е-мейл/ naujas email:</td>
<td><input type="text" name="login" id="login" 
	value= <?php 
	$login_to_print = (strlen($cyclist->getEmail()) > 0 && empty($login)) ? $cyclist->getEmail() : $login; 
	echo $login_to_print;
	//gdy zawodnik ma juz mail i nie zatwierdzil jeszcze tego formularza, to 
	//wpisujemy go jako domyslny w to pole - moze on go zmienic
	//else: to co wpisał
	?> >
<span class="text-danger">*</span> <span class="text-danger"> <?php echo $err_login; ?></span>
</td></tr>

<tr><td>Podaj nowe hasło (minimum 8 znaków w tym, co najmniej 1 cyfrę)/ Enter new password (at least 8 chars with 1 digit)/ новый пароль/ Naujas slaptažodis:</td>
<td><input type="password" name="pass" id="pass" >
<span class="text-danger">*</span> <span class="text-danger"> <?php echo $err_pass; ?></span>
</td></tr>

<tr><td>Powtórz hasło/ Repeat password/ повторение паролa/ kartoti slaptažodis:</td>
<td><input type="password" name="pass2" id="pass2" >
<span class="text-danger">*</span> <span class="text-danger"> <?php echo $err_pass2; ?></span>
</td></tr>

<!-- <tr><td>Przepisz znaki z obrazka/ Enter characters from the image:</td>
<td>
<img id="captchaImage" src="<?php //echo $rainCaptcha->getImage(); ?>" />
                        <br />
                        <input name="captcha" id="captcha" type="text" />
                        <br />
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('captchaImage').src = '<?php //echo $rainCaptcha->getImage(); ?>&morerandom=' + Math.floor(Math.random() * 10000);">Inny obrazek/ Another image</button>
                        <br />
                        <span class="text-danger">*</span> <span class="text-danger"> <?php echo $err_recaptcha; ?></span>
</td></tr> -->



</table>
<input type="submit" name="submit_create_account" value="Zatwierdź/Submit" class="btn btn-success">
</form> 