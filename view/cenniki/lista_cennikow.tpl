<section>
	<h1>Cenniki</h1>
	<a href="{$_base_url}cenniki/formularz-cennika" class="button">Dodaj</a>
	{if $a_cenniki || $a_cenniki_placowki}
		<table class="marginTop20" id="cenniki">
			<tr>
				<th>L.p</th>
				<th>Nazwa</th>
				<th>Edytuj</th>
				<th>Usuń</th>
			</tr>
			{if $a_cenniki_placowki}
				{foreach $a_cenniki_placowki as $a_cennik}
					<tr data-id="{$a_cennik.id_cenniki}">
						<td>{counter}</td>
						<td>{$a_cennik.nazwa}</td>
						<td><a href="{$_base_url}cenniki/formularz-cennika/id/{$a_cennik.id_cenniki}" class="modal">edytuj</a></td>
						<td><a href="#" class="czy_usunac_cennik">usuń</a></td>
					</tr>
				{/foreach}
			{/if}
			{foreach $a_cenniki as $a_cennik}
				<tr data-id="{$a_cennik.id_cenniki}">
					<td>{counter}</td>
					<td>{$a_cennik.nazwa}</td>
					<td><a href="{$_base_url}cenniki/formularz-cennika/id/{$a_cennik.id_cenniki}" class="modal">edytuj</a></td>
					<td><a href="#" class="czy_usunac_cennik">usuń</a></td>
				</tr>
			{/foreach}
		</table>
	{/if}
	
</section>