<section>
	<h1>Formularze</h1>
	
	<input type="hidden" id="email_zalogowanego" value="{session::get_user('email')}">
	<select id="wybor_formularza" class="inline autoWidth">
		<option>Formularz migracji</option>
	</select>
	<input type="submit" id="wyslij_formularz" value="Wyślij">
	
	<table>
		<tr>
			<th>L.p.</th>
			<th>Nazwa</th>
			<th>Imię i nazwisko pracownika</th>
			<th>Podgląd</th>
			<th>Status</th>
			<th>Data akceptacji</th>
			<th>IP</th>
		</tr>
		{if $a_formularze}
			{foreach $a_formularze as $a_formularz}
				<tr>
					<td>{counter}</td>
					<td>{$a_formularz.typ_formularza}</td>
					<td>{$a_formularz.imie} {$a_formularz.nazwisko}</td>
					<td><a href="users/podglad_formularza/id/{$a_formularz.id_logi_formularzy}" class="modal">Podgląd</a></td>
					<td>{$a_formularz.status}</td>
					<td>{$a_formularz.data_akceptacji}</td>
					<td>{$a_formularz.ip}</td>
				</tr>
			{/foreach}
		{/if}
	</table>
</section>