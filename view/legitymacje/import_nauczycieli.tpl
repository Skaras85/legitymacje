<table class="clear">
	<tr>
		<th>Imię 1</th>
		<th>Imię 2</th>
		<th>Nazwisko 1</th>
		<th>Nazwisko 2</th>
		<th>Umowa na czas</th>
		<th>Pracodawca</th>
		<th>Data wygaśnięcia umowy</th>
		<th>Grafiki</th>
		<th><input type="checkbox" id="zaznaczDane" class="checkAll"> <label for="zaznaczDane" class="inline">Zaznacz</label></th>
	</tr>
	{if $a_nauczyciele}
		{foreach $a_nauczyciele as $a_nauczyciel}
			<tr>
				<td>{$a_nauczyciel.imie1}</td>
				<td>{$a_nauczyciel.imie2}</td>
				<td>{$a_nauczyciel.nazwisko1}</td>
				<td>{$a_nauczyciel.nazwisko2}</td>
				<td>{$a_nauczyciel.umowa_na_czas}</td>
				<td>{$a_nauczyciel.linia1}</td>
				<td>{if $a_nauczyciel.umowa_na_czas=='okreslony'}{$a_nauczyciel.data_wygasniecia_umowy|substr:0:10}{else}&nbsp;{/if}</td>
				<td><img src="img.php?id={$a_nauczyciel.id_nauczyciela}&czy_migracja=1" width="100"><img src="img.php?id={$a_nauczyciel.id_nauczyciela}&czy_migracja=1&typ=podpis" width="100"></td>
				<td class="center"><input type="checkbox" name="a_legitymacje[{$a_nauczyciel.id_nauczyciela}]" class="checkAllTarget user" data-check-id="zaznaczDane" checked></td>
			</tr>
		{/foreach}
	{/if}
</table>