<table class="table table-striped table-hover table-condensed table-nonfullwidth">
	<colgroup>
        <col class="col-xs-2 col-sm-1">
        <col class="col-xs-3 col-sm-2">
        <col class="col-xs-3 col-sm-2">
        <col class="col-xs-1">
        <col class="col-xs-2 col-sm-1">
    </colgroup>
	<thead>
		<tr>
			<th>Miejsce</th>
			<th>Imię</th>
			<th>Nazwisko</th>
			<th>Kraj</th>
			<th>Wynik</th>
		</tr>
	</thead>
	<tbody>
	<?php
		for ($i = 0; $i < count($results); $i++) {
			echo "<tr>";
			echo "<td>" . ($i + 1) . "</td>";
			echo "<td><a href=\"show-cyclist.php?cyclist=" . $results[$i]['id'] . "\">" . 
				$results[$i]['name'] . "</a></td>";
			echo "<td><a href=\"show-cyclist.php?cyclist=" . $results[$i]['id'] . "\">" .  
				$results[$i]['surname'] . "</a></td>";
			echo "<td>" . $results[$i]['country'] . "</td>";
			echo "<td>" . $results[$i]['value'] . "</td>";
			echo "</tr>";
		}
	?>
	</tbody>
</table>