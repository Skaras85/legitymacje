<section>
	<h1>Wpłaty</h1>

	{if $a_wplaty}
		<table>
			<thead>
				<tr>
					<th>Data wpłaty</th>
					<th>Kwota wpłaty</th>
					<th>Sposób wpłaty</th>
					<th>Dodał</th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_wplaty as $a_wplata}
					<tr>
						<td><nobr>{$a_wplata.data_wplaty}</nobr></td>
						<td>{$a_wplata.kwota_wplaty|price}</td>
						<td>{$a_wplata.sposob_wplaty}</td>
						<td>{$a_wplata.nazwisko} {$a_wplata.imie}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
	
</section>