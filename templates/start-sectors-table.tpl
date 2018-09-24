<table class="table table-striped table-hover table-condensed table-nonfullwidth">
	<colgroup>
        <!-- <col class="col-xs-2 col-sm-1"> -->
        <col class="col-xs-3 col-sm-2">
        <col class="col-xs-3 col-sm-2">
        <col class="col-xs-1">
        <col class="col-xs-2 col-sm-1">
    </colgroup>
	<thead>
		<tr>
			<!-- <th>Numer - Number</th> -->
			<th>Nazwisko - Surname</th>
			<th>Imię - Name</th>
			<th>Punkty - Points</th>
			<th>Sektor - Sector</th>
		</tr>
	</thead>
	<tbody>
	<?php
		for ($i = 0; $i < count($results); $i++) {
			echo "<tr>";
			// echo "<td>" . ($i + 1) . "</td>";
			echo "<td>" . $results[$i]['surname'] . "</td>";
			echo "<td>" . $results[$i]['name'] . "</td>";
			echo "<td>" . $results[$i]['points_sum'] . "</td>";
			echo "<td>" . $results[$i]['start_sector'] . "</td>";
			echo "</tr>";
		}
	?>
	</tbody>
</table>