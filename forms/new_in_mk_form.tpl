<?php
echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method=\"post\" id=\"new_in_mk\">";
?>

<h4>Nowy w Maratonach Kresowych?/ New in Maratony Kresowe/ новый в MK / Naujas į MK ?</h4>
<br />
<h4><input type="radio" name="new_in_mk" value="yes">&nbsp;&nbsp;&nbsp;Tak/ Yes/ да/ Taip </h4>
<h4><input type="radio" name="new_in_mk" value="no">&nbsp;&nbsp;&nbsp;Nie/ No/ Нет/ Ne </h4>
<br />
<p class="text-danger"><?php echo $err_new_in_mk; ?></p>


<input type="submit" name="submit_new_in_mk" value="Zatwierdź/Submit" class="btn btn-success">
</form> 