<?php
echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"post\" id=\"form_find_users2\">";
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

</table>
<input type="submit" name="submit_find_user2" value="Zatwierdź/Submit" class="btn btn-success">
</form>
