<?php
echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"post\" id=\"form_apply_no_login\">";
?>
<table class="table table-striped table-condensed">
<tr><td>Imię/ First name/ Vardas / Имя:</td>
	<td><input type="text" name="name" id="name" value= <?php echo "\"" . $name . "\""; ?> >
		<span class="text-danger">*</span><span class="text-danger"> <?php echo $err_name; ?> </span>
	</td>
</tr>

<tr><td>Nazwisko/ Last name/ Pavarde/ Фамилия:</td>
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


<tr><td>Numer startowy (jeśli już posiadasz)/ Starting number (if you have it):</td>
<td><input type="text" name="starting_no" id="starting_no" value= <?php //echo "\"" . $starting_no . "\""; ?> >
<span class="text-danger"><?php echo $err_starting_no; ?> </span>	
</td></tr>

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

<tr><td>Drużyna/ Team/ Komanda/ Команда:</td>
<td>
<select class="form-control" name="team">
<?php
for ($i = 0; $i < count($allTeams); $i++)
{
  echo "<option value=\"". $allTeams[$i][1] . "\"";
  if ($teamName == $allTeams[$i][1])
    echo " selected "; 
  echo ">";
  if ($allTeams[$i][1] == ''){
  	echo "--Wybierz ekipę-- </option>";
  }
  else  {
  	echo $allTeams[$i][1] . "</option>";
  }
}
echo "</select>";
?>

<br />

<input type="checkbox" class="new_team_check" name="create_team">Dodaj nową ekipę/Add new team


</td>
<td>
</td></tr>

<tr class="my_invisible" id="new_team"><td>Nowa drużyna (której zostaniesz członkiem)/ New team:</td>
<td><input type="text" name="new_team" ></td> 
<td><span class="text-danger"><?php echo $err_new_team; ?> </span>
</td></tr>

<!--<tr> <td>Licencja PZKol (only Polish riders): </td>
   <td>
    <div class="radio">
    <label>
    <?php
    /*echo "<input type=\"radio\" name = \"license\" value=\"T\"";
    if ($license == 'T')
      echo " checked";
    echo "> mam";*/ ?>
    <br />
    </label>
    </div>
    <div class="radio">
    <label>
    <?php /*
    echo "<input type=\"radio\" name = \"license\" value=\"F\"";
    if ($license == 'F')
      echo " checked";
    echo "> nie mam";*/
    ?>
    </label>
    </div>
  </td> 
  <td>
    <?php
    /*for ($i = 0; $i < count($license_types); $i++) {
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
    }*/
    ?>
  </td>
</tr>

<tr><td>Kod UCI (jeśli masz licencję) (Poland only):</td>
  <td> <input type="text" name="license" id="license" value= <?php //echo "\"" . $license . "\""; ?> maxlength="11">
    <span class="text-danger"> <?php //echo $err_license; ?> </span>
  </td>
</tr> -->

<tr><td>Edycja maraton - Marathon stage</td>
<td><select class="form-control" name="marathon">
<?php
for ($i = 0; $i < count($marathons); $i++)
{
  echo "<option value=\"". $marathons[$i][0] . "\"";
  if ($marathon == $marathons[$i][0])
    echo " selected "; 
  echo ">" . $marathons[$i][1] . ", " . $marathons[$i][2] . "</option>";
}
echo "</select>";
?>
</td>
</tr>

<tr><td>Dystans/ Distance/ дистанция/ Distancija</td>
<td>
<?php
for ($i = 0; $i < count($race_types); $i++)
{ ?>
  <div class="radio">
  <label>
  <input type="radio" name="distance" id= <?php echo "\"distance" . $i . "\" value=\"" . $race_types[$i][0] . "\""; 
  if ($race_types[$i][0] == $distance)
    echo " checked";
  echo ">" . $race_types[$i][1];
  ?>
  </label>
  </div>
<?php
}
?>
<span class="text-danger">* <?php echo $err_distance; ?> </span>
</td>
</tr>

<tr><td>E-mail:</td>
<td><input type="text" name="login" id="login" 
	value= <?php echo "\"" . $login . "\""; ?> >
<span class="text-danger">*</span> <span class="text-danger"> <?php echo $err_login; ?></span>
</td></tr>


<tr><td>Telefon/ Phone number (np.: '+48123456789'):</td>
<td><input type="text" name="phone" value= <?php echo "\"" . $phone . "\""; ?> > 
<span class="text-danger"><?php echo $err_phone; ?> </span>
</td></tr>

<tr><td>Sposób płatności - Payment:</td>
<td>
<?php
for ($i = 0; $i < count($payments); $i++)
{ ?>
  <div class="radio">
  <label>
  <input type="radio" name="payment" id= <?php echo "\"payment" . $i . "\" value=\"" . $payments[$i]['id'] . "\""; 
  if ($payments[$i]['id'] == $payment)
    echo " checked";
  echo ">" . $payments[$i]['name'];
 ?>
  </label>
  </div>

  <?php
}
?>
<span class="text-danger">* <?php echo $err_payment; ?> </span>
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
 <a href="http://maratonykresowe.pl/?page_id=6734" target="_blank">Regulamin Maratonów Kresowych</a> 
 <span class="text-danger">*</span><span class="text-danger"> <?php echo $err_accept_rules; ?> </span>
</h4>
 
<h4> 
  <span class="text-danger"> <?php echo $err_chelmxct; ?> </span><span class="text-danger"> 
</h4>

<input type="submit" name="submit_apply_no_login" value="Zatwierdź/Submit" class="btn btn-success">
</form>