<?php
$marathons = DBGetter::getFutureMarathons();
?>

<form action=<?php echo $action;?> method="post" class="form-horizontal" role="form">
<div class="form-group">
<label class="control-label col-sm-2" for="race">Wybierz edycję/ Choose stage:</label>
<div class="col-sm-4">
<select class="form-control" name="marathon">
<?php
for ($i = 0; $i < count($marathons); $i++) {
	echo "<option value=\"". $marathons[$i][0] . "\"";
	if ($race_id == $marathons[$i][0]){
		echo " selected "; 
	}
	echo ">" . $marathons[$i][1] . ", " . $marathons[$i][2] . "</option>";
}
echo "</select>";
?>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-1 col-sm-10">
<input type="submit" name="submit_applylists_form" value="Zastosuj/ Submit" class="btn btn-success">  </div>
</div>
</form>
