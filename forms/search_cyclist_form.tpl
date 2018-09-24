<form action=<?php echo $action;?> method="post" class="form-horizontal" role="form">
<div class="form-group">
<label class="control-label col-sm-2" for="surname">Nazwisko - Last name:</label>
<div class="col-sm-4">
<input type="text" name="surname" id="surname" class="form-control" value= <?php echo "\"" . $surname . "\""; ?> >
</div>
<div class="col-sm-4">
<p class="text-danger"><?php echo $err_surname; ?></p>
</div>
</div>

<div class="form-group">
<label class="control-label col-sm-2" for="starting_no">Numer startowy - Start number:</label>
<div class="col-sm-4">
<input type="text" name="starting_no" id="starting_no" class="form-control" maxlength="4" value= <?php echo "\"" . $starting_no . "\""; ?> >
</div>
<div class="col-sm-4">
<p class="text-danger"><?php echo $err_starting_no; ?></p>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-1 col-sm-10">
<input type="submit" name="submit_search_cyclist" value="Szukaj! - Search!" class="btn btn-success">	</div>
</div>
</form>