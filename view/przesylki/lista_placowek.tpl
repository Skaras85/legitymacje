<section>
	<h1>Placówki</h1>
	<a href="{$_base_url}przesylki/lista-przesylek" class="button">Przesyłki</a>
	<a href="{$_base_url}users/lista_kont_wewnetrznych" class="button">Konta wewnętrzne</a>

	<form action="{$_base_url}przesylki/lista_placowek">
		<input type="text" name="fraza" value="{$fraza}" class="inline">
		<input type="submit" value="szukaj">
	</form>

	{if $a_placowki}
		<table class="marginTop20 dataTables" id="placowki">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nazwa</th>
					<th>Adres</th>
					<th>Kod pocztowy</th>
					<th>Dodaj</th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_placowki as $a_placowka}
					<tr>
						<td>{$a_placowka.id_placowki}</td>
						<td>{$a_placowka.nazwa}</td>
						<td>{$a_placowka.adres}</td>
						<td>{$a_placowka.kod_pocztowy}</td>
						<td><a href="{$_base_url}przesylki/formularz-przesylki/id_placowki/{$a_placowka.id_placowki}" class="modall">Dodaj</a></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
	
</section>