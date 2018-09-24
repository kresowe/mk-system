<h4>Wypełnij poniższy formularz:/ Fill in the following form:</h4>
<?php
echo "<form action=\"" . $action_apply_form2 . "\" method=\"post\" class=\"form-horizontal\">";
?>

<?php
$race_types = DBGetter::getRaceTypes($marathon);
$payments = DBGetter::getPaymentTypes();

?>
<div class="form-group">
<label class="control-label col-sm-2" for="distance">Dystans/ Distance/ дистанция/ Distancija: 
</label>
<div class="col-sm-4">
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
</div>
<div class="col-sm-3"><span class="text-danger">* <?php echo $err_distance; ?></span></div>
</div>

<div class="form-group">
<label class="control-label col-sm-2" for="payment">Sposób płatności/ Payment: 
</label>
<div class="col-sm-4">
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
</div>
<div class="col-sm-3">
  <span class="text-danger">* <?php echo $err_payment; ?></span>
</div>
</div>

<!-- <div class="form-group">
<label class="control-label col-sm-2" for="payment">Licencja PZKol (only Polish riders): 
</label>
<div class="col-sm-4">
  <div class="radio">
  <label>
  <input type="radio" name="license" id="license" value="T" <?php /*
  if ($license == 'T')
    echo " checked";
  echo ">" . "mam";*/
  ?>
  </label>
  </div>
  <div class="radio">
  <label>
  <input type="radio" name="license" id="license" value="F" <?php /*
  if ($license == 'F')
    echo " checked";
  echo ">" . "nie mam";*/
  ?>
  </label>
  </div>
</div>
</div>
 -->

<div class="row mk_row">
<div class="col-sm-2">
  <p class="mk_right"><strong>Uwagi/ Remarks:</strong></p> 
</div>
<div class="col-sm-4">
  <textarea name="notes" rows="8" cols="35"> </textarea>
</div>
</div>

<?php
if (!$start_number) { ?>
  <div class="form-group">
    <label class="control-label col-sm-2" for="starting_no">Numer posiadanego czipa/ chip number:
    </label>
    <div class="col-sm-4">
      <input type="text" class="form-control" name="starting_no" id="starting_no" maxlength="4">
    </div>
    <div class="col-sm-3"><span class="text-danger"><?php echo $err_note_start_number; ?></span> </div>
  </div>
<?php  
}
?>

<div class="form-group">
  <div class="col-sm-1">
<input type="checkbox" name="accept_rules" value="yes" <?php if ($accept_rules) echo "checked"; ?> 
  > </div>
  <div class="col-sm-5">Oświadczam, że akceptuję/ I accept rules
 <a href="http://maratonykresowe.pl/?page_id=3822" target="_blank">Regulamin Maratonów Kresowych</a> 
 
</div>
<div class="col-sm-3"><span class="text-danger">* <?php echo $err_accept_rules; ?> </span> </div>
</div>

<div class="form-group">
<div class="col-sm-offset-1 col-sm-10">
<input type="submit" name="submit_apply_2" value="Zgłaszam się!/ I apply!" class="btn btn-success">  </div>
</div>
</form>
