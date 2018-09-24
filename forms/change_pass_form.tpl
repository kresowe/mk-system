<form action="index.php?page=change_pass" method="post" class="form-horizontal" role="form">


<div class="form-group">
<label class="control-label col-sm-2" for="password">Wpisz nowe hasło/ Enter new password/ новый пароль/ Naujas slaptažodis:</label>
<div class="col-sm-4">
<input type="password" name="pass" id="new_password" class="form-control" placeholder="Wpisz nowe hasło/ Enter new password">
<span class="text-danger"> <?php echo $err_password; ?></span>
</div>
</div>

<div class="form-group">
<label class="control-label col-sm-2" for="password">Powtórz nowe hasło/ Repeat new password/ повторение/ pakartoti:</label>
<div class="col-sm-4">
<input type="password" name="pass2" id="new_pass_repeat" class="form-control" placeholder="Powtórz nowe hasło/ Repeat new password">
<span class="text-danger"> <?php echo $err_pass_repeat; ?></span>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-1 col-sm-10">
<input type="submit" name="submit_new_pass" value="Zatwierdź/ Submit" class="btn btn-success">	</div>
</div>
</form>