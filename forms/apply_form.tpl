<h4>Wypełnij poniższy formularz:/ Fill in the following form</h4>
<h5>Zapisać można się na miesiąc przed danym maratonem./ You can register one month before marathon.</h5>
<?php
echo "<form action=\"$action_apply_form\" method=\"post\" class=\"form-horizontal\">";
?>
<?php
$marathons = DBGetter::getAvailableMarathons();
?>
<div class="form-group">
<label class="control-label col-sm-2" for="marathon">Wybierz edycję maratonu/ Select marathon.: </label>
<div class="col-sm-4">
<select class="form-control" name="marathon">
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
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-1 col-sm-10">
<input type="submit" name="submit_apply_1" value="Dalej/ Enter" class="btn btn-success">  </div>
</div>
</form>
