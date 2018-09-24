<?php
echo "<form action=\"index.php?page=edit_profile\" method=\"post\" id=\"form_edit_profile\">";
?>

<table class="table table-striped table-condensed">
<tr><td>Imię/ First name/ Pavarde/ Имя:</td>
	<td><?php echo $cyclist->getName(); ?> </td> <td>tego pola nie możesz edytować</td>
</tr>
<tr><td>Nazwisko/ Last name/ Vardas/ Фамилия:</td>
	<td><?php echo $cyclist->getSurname(); ?> </td> <td>tego pola nie możesz edytować</td>
</tr>
<tr><td>Rok urodzenia/ Year of birth/ Gimimo metai/ Год рождения:</td>
	<td><?php echo $cyclist->getBirthyear(); ?> </td> <td>tego pola nie możesz edytować</td>
</tr>
<tr><td>Płeć/ Sex/ Lytis/ Cекс:</td>
	<td><?php echo $cyclist->getSex(); ?> </td> <td>tego pola nie możesz edytować</td>
</tr>


<tr><td>Kraj/ Country/ Šalis/ Страна (POL, BLR, LTU, etc.):</td>
<td><input type="text" name="country" id="country" maxlength="3" value= <?php echo "\"" . $cyclist->getCountry() . "\""; ?> >
</td>
<td><span class="text-danger"><?php echo $err_country; ?> </span>	</td>
</tr>

<tr><td>Województwo (POL only):</td>
<td>
  <select class="form-control" name="province">
<option value="" <?php
if ($cyclist->getProvince() == '') {
    echo " selected ";
}
?> >--wybierz województwo--</option>
<?php
for ($i = 0; $i < count($voivodeships); $i++)
{
  echo "<option value=\"". $voivodeships[$i] . "\"";
  if ($cyclist->getProvince() == $voivodeships[$i]){
    echo " selected "; 
  }

  echo ">" . $voivodeships[$i] . "</option>";
}
?>
</select>
</td>
<td> </td>
</tr>

<tr> <td>Kod pocztowy (POL only): </td>
<td><input type="text" name="zip_code" maxlength="6" value= <?php echo "\"" . $cyclist->getZipCode() . "\""; ?> > </td>
<td><span class="text-danger"><?php echo $err_zip_code; ?> </span>	
</td></tr>

<tr><td>Miejscowość/ Town/ Miestas/ Город: </td>
<td><input type="text" name="town" value= <?php echo "\"" . $cyclist->getTown() . "\""; ?> > </td>
<td><span class="text-danger"> <?php echo $err_town; ?> </span>
</td></tr>

<tr><td>Email:</td>
	<td><?php echo $cyclist->getLogin(); ?> </td> <td>tego pola nie możesz edytować</td>
</tr>

<tr><td>Telefon (postać np.: '+48123456789', tzn. z kierunkowym do kraju i bez spacji):</td>
<td><input type="text" name="phone" value= <?php echo "\"" . $cyclist->getPhone() . "\""; ?> ></td> 
<td><span class="text-danger"><?php echo $err_phone; ?> </span>
</td></tr>

<tr><td>Numer startowy/ Starting number/ стартовый номер/ pradedant numeris:</td>
	<td><?php echo $cyclist->getStartNumber(); ?> </td> <td>tego pola nie możesz edytować</td>
</tr>

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



</table>
<input type="submit" name="submit_personal_data" value="Zatwierdź/Submit" class="btn btn-success">
</form> 
