<dl class="dl-horizontal">
	<dt class="mk_dt">Imię - Name</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getName(); ?> </dd>

	<dt class="mk_dt">Nazwisko - Surname</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getSurname(); ?> </dd>

	<dt class="mk_dt">Rok urodzenia - Birthyear</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getBirthyear(); ?> </dd>

	<dt class="mk_dt">Płeć - Sex</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getSex(); ?> </dd>

	<dt class="mk_dt">Kraj - Country</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getCountry(); ?> </dd>

	<dt class="mk_dt">Miejscowość - Town</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getTown(); ?> </dd>

	<dt class="mk_dt">Numer startowy - Start number</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getStartNumber(); ?> </dd>

	<dt class="mk_dt">Drużyna - Team</dt>
	<dd class="mk_dd"> <?php echo $cyclist_to_show->getTeam(); ?> </dd>

	<dt class="mk_dt">Liczba startów - Number of races</dt>
	<dd class="mk_dd"> <?php echo $a_cyclist_results->getTotalNumberOfResults(); ?> </dd>

	<dt class="mk_dt">Całkowity dystans- Total distance</dt>
	<dd class="mk_dd"> <?php echo $a_cyclist_results->getTotalDistance(); ?> km </dd>

	<dt class="mk_dt">Całkowity czas- Total time (hh:mm:ss)</dt>
	<dd class="mk_dd"> <?php echo $a_cyclist_results->getTotalTime(); ?> </dd>

</dl>