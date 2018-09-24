<?php
echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"post\" id=\"form_apply_no_login\">";
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

<tr> <td>Płeć/ Sex/ Lytis/ Cекс: </td>
	<td>
		<div class="radio">
		<label>
		<?php
		echo "<input type=\"radio\" name = \"sex\" value=\"M\"";
		if ($sex == "M")
			echo " checked";
		echo "> Mężczyzna/ Male / мужчина/ Vyras"; ?>
		<span class="text-danger">* <?php echo $err_sex; ?> </span> <br />
		</label>
		</div>
		<div class="radio">
		<label>
		<?php
		echo "<input type=\"radio\" name = \"sex\" value=\"K\"";
		if ($sex == "K")
			echo " checked";
		echo "> Kobieta/ Female / женщина/ Moteris";
		?>
		</label>
		</div>
	</td>
</tr>

<tr><td>Rok urodzenia/ Year of birth/ Gimimo metai/ Год рождения:</td>
<td><input type="text" name="birthyear" id="birthyear" value= <?php echo "\"" . $birthyear . "\""; ?> 
	maxlength="4">
<span class="text-danger">*</span>
<span class="text-danger"> <?php echo $err_birthyear; ?> </span> </td>
</tr>

<tr><td>Rodzaj licencji - License type: </td>
  <td>
    <?php
    for ($i = 0; $i < count($license_types); $i++) {
      echo "<div class=\"radio\"><label>";
      echo "<input type=\"radio\" name=\"license_type\" value=\"" . $license_types[$i] . "\"";
      if ($license_type == $license_types[$i]){
        echo " checked";
      }
      echo ">" . $license_types[$i]; 
      if ($i == 0) {
        echo "<span class=\"text-danger\">*</span><span class=\"text-danger\">" . $err_license_type . "</span>";
      }
      echo "</label></div>";
    }
    ?>
  </td>
</tr>

<tr><td>Kod UCI - UCI code:</td>
  <td> <input type="text" name="license" id="license" value= <?php echo "\"" . $license . "\""; ?> maxlength="11">
    <span class="text-danger"> <?php echo $err_license; ?> </span>
  </td>
</tr>

<tr><td>Kraj/ Country/ Šalis/ Страна (POL, BLR, LTU, etc.):</td>
<td><input type="text" name="country" id="country" maxlength="3" value= <?php echo "\"" . $country . "\""; ?> >
<span class="text-danger">* <?php echo $err_country; ?> </span>	
</td></tr>

<tr><td>Województwo (POL only):</td>
<td>
  <select class="form-control" name="province">
<option value="" <?php
if ($province == '') {
    echo " selected ";
}
?> >--wybierz województwo--</option>
<?php
for ($i = 0; $i < count($voivodeships); $i++)
{
  echo "<option value=\"". $voivodeships[$i] . "\"";
  if ($province == $voivodeships[$i]){
    echo " selected "; 
  }

  echo ">" . $voivodeships[$i] . "</option>";
}
?>
</select>
</td>
</tr>

<tr> <td>Kod pocztowy (POL only): </td>
<td><input type="text" name="zip_code" maxlength="6" value= <?php echo "\"" . $zip_code . "\""; ?> > 
<span class="text-danger"><?php echo $err_zip_code; ?> </span>	
</td></tr>

<tr><td>Miejscowość/ Town/ Miestas/ Город: </td>
<td><input type="text" name="town" value= <?php echo "\"" . $town . "\""; ?> > 
<span class="text-danger">* <?php echo $err_town; ?> </span>
</td></tr>

<tr><td>Drużyna/ Team/ Komanda/ Команда:</td>
<td><input type="text" name="team" value= <?php echo "\"" . $team . "\""; ?> > 
<span class="text-danger"><?php echo $err_team; ?> </span>
</td>
<td>
</td></tr>


<tr><td>E-mail:</td>
<td><input type="text" name="login" id="login" 
	value= <?php echo "\"" . $login . "\""; ?> >
<span class="text-danger">*</span> <span class="text-danger"> <?php echo $err_login; ?></span>
</td></tr>

<tr><td>Sposób płatności - Payment: </td>
<td>
  zobacz <a href="http://kresowe.az.pl/mk14/wp-content/uploads/2016/10/Regulamin-Mistrzostw-Wojew%C3%B3dztwa-Podlaskiego-w-Prze%C5%82ajach-Bia%C5%82ystok-06.11.2016..doc" target="_blank">regulamin</a>.
</td>
</tr>

<tr>
<td>Uwagi - Remarks:</td>
<td>
	<textarea name="notes" rows="8" cols="35"> </textarea>
</td>
</tr>
</table>
<h4>
<input type="checkbox" name="accept_rules" value="yes" <?php if ($accept_rules) echo "checked"; ?> 
  > Oświadczam, że akceptuję/ I accept rules
 <a href="http://kresowe.az.pl/mk14/wp-content/uploads/2016/10/Regulamin-Mistrzostw-Wojew%C3%B3dztwa-Podlaskiego-w-Prze%C5%82ajach-Bia%C5%82ystok-06.11.2016..doc" target="_blank">Regulamin Mistrzostw Województwa Podlaskiego w kolarstwie przełajowym</a> 
 <span class="text-danger">*</span><span class="text-danger"> <?php echo $err_accept_rules; ?> </span>
</h4>

<input type="submit" name="submit_cyclocross" value="Zatwierdź/Submit" class="btn btn-success">
</form>