<?php
if (count($cyclists_found) > 0) {
	?>
	<table class="table table-striped table-hover table-condensed">
	<colgroup>
        <col class="col-xs-3">
        <col class="col-xs-3">
        <col class="col-xs-1">
        <col class="col-xs-3">
        <col class="col-xs-2">
    </colgroup>
	<thead>
		<tr>
			<th>Imię - First name</th>
			<th>Nazwisko - Last name</th>
			<th>Kraj - Country</th>
			<th>Grupa - Team</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
	for ($i = 0; $i < count($cyclists_found); $i++) {
		$a_cyclist_found = new Cyclist();
		$a_cyclist_found->getDataFromDB($cyclists_found[$i]['id']);
		$a_cyclist_found->setId($cyclists_found[$i]['id']);
	?>
		<tr>
			<td><?php echo $a_cyclist_found->getName(); ?></td>
			<td><?php echo $a_cyclist_found->getSurname(); ?></td>
			<td><?php echo $a_cyclist_found->getCountry(); ?></td>
			<td><?php echo $a_cyclist_found->getTeam(); ?></td>
			<td><?php echo "<a href=\"" . $link_in_cycl_found . $cyclists_found[$i]['id'] . "\" 
				class=\"mk_btn_link\">
				<button type=\"button\" class=\"btn btn-primary btn-md\">" . $button_text . "</button>
				</a>";
				?></td>
		</tr>
	<?php
	}
	?>
	</tbody>
	</table>

<?php
} else {
	?>
	<h3>Nie znaleziono zawodników. - No cyclists found</h3>
<?php
}
?>