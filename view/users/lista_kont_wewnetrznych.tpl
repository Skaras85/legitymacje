<section>
	<h1>Konta wewnętrzne</h1>

	<a href="users/formularz-uzytkownika/konto-wewnetrzne/1" class="button">Dodaj konto wewnętrzne</a>
	
	<a href="users/lista_kont_wewnetrznych" class="button">Konta wewnętrzne</a>
	<form action="{$_base_url}users/lista_kont_wewnetrznych" class="marginTop20">
		<input type="text" name="search" placeholder="Szukaj" autofocus="true" value="{if !empty($search)}{$search}{/if}">
		<input type="submit" value="Szukaj">
	</form>

	{if !empty($a_users)}
		<table class="marginTop20 dataTables">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nazwisko i imię</th>
					<th>Email</th>
					<th>Ilość placówek</th>
					<th>Typ</th>
					<th>Zaloguj</th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_users as $a_user}
					<tr data-id="{$a_user.id_users}">
						<td>{$a_user.id_users}</td>
						<td>{$a_user.nazwisko} {$a_user.imie}</td>
						<td>{$a_user.email}</td>
						<td>{$a_user.ilosc_placowek}</td>
						<td class="typ_konta">{if $a_user.czy_konto_wewnetrzne==1}wewnętrzne{else}standardowe <a href="#" class="zmien_konto_standardowe_na_wewnetrzne button">zmień</a>{/if}</td>
						<td>
							<form action="https://realizacja.loca.pl" method="POST" target="_blank">
	                            <input type="hidden" name="module" value="users">
	                            <input type="hidden" name="action" value="zaloguj_na_usera">
	                            <input type="hidden" name="id_users" value="{$a_user.id_users}">
	                            <input type="hidden" name="id_pracownika" value="{session::get_id()}">
	                            <input type="submit" value="Zaloguj" class="autoWidth">
	                        </form>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
	
</section>