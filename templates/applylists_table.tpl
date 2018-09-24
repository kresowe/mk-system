<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Lp - Id</th>
			<th>Nr startowy - Starting no</th>
			<th>Imię - First name</th>
			<th>Nazwisko - Last name</th>
			<th>Rocznik - Birthyear</th>
			<th>Kraj - Country</th>
			<th>Miasto - City</th>
			<th>Drużyna - Team</th>
			<th>Dystans - Distance</th>
		</tr>
	</thead>
	<tbody>
		<?
		for ($i = 0; $i < count($riders); $i++){
			echo "<tr><td>" . ($i + 1) . "</td>";
			echo "<td>" . $riders[$i]['nr'] . "</td>";
			echo "<td>" . $riders[$i]['first_name'] . "</td>";
			echo "<td>" . $riders[$i]['last_name'] . "</td>";
			echo "<td>" . $riders[$i]['birthyear'] . "</td>";
			echo "<td>" . $riders[$i]['country'] . "</td>";
			echo "<td>" . $riders[$i]['city'] . "</td>";
			echo "<td>" . $riders[$i]['team'] . "</td>";
			echo "<td>" . $riders[$i]['distance'] . "</td>";
			echo "</tr>";
		}
		?>
	</tbody>
</table>