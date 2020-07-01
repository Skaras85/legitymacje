<section class="formSection">
	<h1>Statystyki odwiedzin na dzień {$data}</h2>

	<form action="{$_base_url}users/statystyki_odwiedzin">
		<input type="text" name="data" class="datepicker autoWidth inline" value="{$data}">
		<input type="submit" value="Wybierz" class="autoWidth inline">
	</form>

	{if $a_statystyki_odwiedzin}
		<table class="statystyki tablesorter">
			<thead>
				<tr>
					<th>Lokal</th>
					<th>Odwiedziny unikalne</th>
					<th>Odwiedziny</th>
					<th>Wyświetlenia unikalne</th>
					<th>Wyświetlenia</th>
				</tr>
			</thead>
			<tbody>
			{foreach $a_statystyki_odwiedzin as $a_statystyka}
				
				<tr>
					<td>{$a_statystyka.nazwa}</td>
					<td>{$a_statystyka.unikalne}</td>
					<td>{$a_statystyka.odwiedziny}</td>
					<td>{$a_statystyka.wyswietlenia_unikalne}</td>
					<td>{$a_statystyka.wyswietlenia}</td>
				</tr>
				
			{/foreach}
			</tbody>
		</table>
		
	{/if}

</section>
