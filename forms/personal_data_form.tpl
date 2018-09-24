<?php
echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"post\" id=\"form_personal_data\">";
?>

<table class="table table-striped table-condensed">
<tr><td>Imię/ First name/ Pavarde/ Имя:</td>
	<td><input type="text" name="name" id="name" value= <?php echo "\"" . $name . "\""; ?> >
		<span class="text-danger">*</span><span class="text-danger"> <?php echo $err_name; ?> </span>
	</td>
</tr>

<tr><td>Nazwisko/ Last name/ Vardas/ Фамилия:</td>
<td><input type="text" name="surname" id="surname" value= <?php echo "\"" . $surname . "\""; ?> >
<span class="text-danger">*</span>
<span class="text-danger"> <?php echo $err_surname; ?> </span> </td>
</tr>

<tr><td>Rok urodzenia/ Year of birth/ Gimimo metai/ Год рождения:</td>
<td><input type="text" name="birthyear" id="birthyear" value= <?php echo "\"" . $birthyear . "\""; ?> 
	maxlength="4">
<span class="text-danger">*</span>
<span class="text-danger"> <?php echo $err_birthyear; ?> </span> </td>
</tr>

<tr> <td>Płeć/ Sex/ Lytis/ Cекс: </td>
	<td>
		<?php
		echo "<input type=\"radio\" name = \"sex\" value=\"M\"";
		if ($sex == "M")
			echo " checked";
		echo "> Mężczyzna/ Male / мужчина/ Vyras"; ?>
		<span class="text-danger">* <?php echo $err_sex; ?> </span> <br />
		<?php
		echo "<input type=\"radio\" name = \"sex\" value=\"K\"";
		if ($sex == "K")
			echo " checked";
		echo "> Kobieta/ Female / женщина/ Moteris";
		?>
	</td>
</tr>
<!--
<tr><td>Numer startowy (jeśli już posiadasz):</td>
<td><input type="text" name="starting_no" id="starting_no" value= <?php //echo "\"" . $starting_no . "\""; ?> >
<span class="text-danger"><?php //echo $err_starting_no; ?> </span>	
</td></tr>
-->
<tr><td>Kraj/ Country/ Šalis/ Страна (POL, BLR, LTU, etc.):</td>
<td><input type="text" name="country" id="country" maxlength="3" value= <?php echo "\"" . $country . "\""; ?> >
<span class="text-danger">* <?php echo $err_country; ?> </span>	
</td></tr>

<tr> <td>Kod pocztowy (POL only): </td>
<td><input type="text" name="zip_code" maxlength="6" value= <?php echo "\"" . $zip_code . "\""; ?> > 
<span class="text-danger"><?php echo $err_zip_code; ?> </span>	
</td></tr>

<tr><td>Miejscowość/ Town/ Miestas/ Город: </td>
<td><input type="text" name="town" value= <?php echo "\"" . $town . "\""; ?> > 
<span class="text-danger">* <?php echo $err_town; ?> </span>
</td></tr>

<tr><td>Telefon/ Phone number (np.: '+48123456789'):</td>
<td><input type="text" name="phone" value= <?php echo "\"" . $phone . "\""; ?> > 
<span class="text-danger"><?php echo $err_phone; ?> </span>
</td></tr>

<tr><td>E-mail = Login:</td>
<td><input type="text" name="login" id="login" 
	value= <?php echo "\"" . $login . "\""; ?> >
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
<!-- 
<tr><td>Przepisz znaki z obrazka/ Enter characters from the image:</td>
<td>
<img id="captchaImage" src="<?php //echo $rainCaptcha->getImage(); ?>" />
                        <br />
                        <input name="captcha" id="captcha" type="text" />
                        <br />
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('captchaImage').src = '<?php //echo $rainCaptcha->getImage(); ?>&morerandom=' + Math.floor(Math.random() * 10000);">Inny obrazek/ Another image</button>
                        <br />
                        <span class="text-danger">*</span> <span class="text-danger"> <?php //echo $err_recaptcha; ?></span>
</td></tr> -->

</table>
<input type="submit" name="submit_personal_data" value="Zatwierdź/Submit" class="btn btn-success">
</form> 