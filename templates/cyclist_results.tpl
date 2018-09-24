<table class="table table-striped table-hover table-condensed">
	<colgroup>
        <col class="col-xs-2 col-sm-1">
        <col class="col-xs-3 col-sm-2">
        <col class="col-xs-2">
        <col class="hidden-xs col-sm-1 ">
        <col class="hidden-xs col-sm-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
        <col class="col-xs-1">
    </colgroup>
	<thead>
		<tr>
			<th>Data - Date</th>
			<th>Miejscowość - Town</th>
			<th>Rodzaj- Type</th>
			<th>Dystans - distance [km]</th>
			<th>Kategoria wiekowa - Age category</th>
			<th>Prędkość średnia - Average speed [km/h]</th>
			<th>Czas - Time [hh:mm:ss]</th>
			<th>Czas zwycięzcy - Winner's time</th>
			<th>Miejsce open - Standing open</th>
			<th>Miejsce w kategorii - Standing in agecategory</th>
		</tr>
	</thead>
	<tbody>
	<?php
		for ($i = 0; $i < count($results); $i++) {
			$time = explode(' ', $results[$i]['time']);
			$race = DBGetter::getRaceById($results[$i]['race_id']);

			MKSystemUtil::calculateSpeed(
				DBGetter::getRaceDistance($results[$i]['race_id'], $results[$i]['type_id']),
				$time[1] 
			);

			echo "<tr>";
			echo "<td>" . $race['date']. "</td>";
			echo "<td>" . $race['place'] . "</td>";
			echo "<td>" . DBGetter::getDistanceName($results[$i]['type_id']) . "</td>";
			echo "<td>" . number_format((DBGetter::getRaceDistance($results[$i]['race_id'], $results[$i]['type_id'])), 1) . "</td>";
			echo "<td>" . $results[$i]['category'] . "</td>";
			echo "<td>" . MKSystemUtil::calculateSpeed(
					DBGetter::getRaceDistance($results[$i]['race_id'], $results[$i]['type_id']),
					$time[1] 
				) . "</td>";
			echo "<td>" . $time[1] . "</td>";
			echo "<td>" . $results[$i]['winner_time'] . "</td>";
			echo "<td>" . $results[$i]['place_open'] . "/" . 
					$results[$i]['participants'] . "</td>";
			echo "<td> " . $results[$i]['place_category'] . "</td>";
			echo "</tr>";
		}
	?>
	</tbody>
</table>