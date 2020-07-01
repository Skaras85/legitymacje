<section>
	<h1>Placówki</h1>

	<a href="users/lista_kont_wewnetrznych" class="button">Konta wewnętrzne</a>
	<form action="{$_base_url}placowki/admin_lista_placowek" class="marginTop20">
		<select name="czy_mailing">
			<option value="" {if $czy_mailing==''}selected{/if}>Wszyscy</option>
			<option value="1" {if $czy_mailing=='1'}selected{/if}>Chcą newsletter</option>
			<option value="0" {if $czy_mailing=='0'}selected{/if}>Nie chcą newsletter</option>
		</select>
		
		<input type="text" name="search" placeholder="Szukaj" autofocus="true" value="{if !empty($search)}{$search}{/if}">		
		<input type="submit" value="Szukaj"> <a href="#" class="button mailing_wybor_szablonu">Wyślij mailing</a>
		<a href="{$_base_url}placowki/admin_lista_placowek/czy_wszystkie/1" class="button">Wczytaj wszystkie</a>
	</form>

	{if !empty($a_placowki)}
		
		
		<table class="marginTop20 dataTabless" id="placowki">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nazwa</th>
					<th>Adres</th>
					<th>Kod pocztowy</th>
					<!--<th>Status</th>-->
					<th>Miasto</th>
					<th>Typ</th>
					<th>Edytuj</th>
					<th>Usuń</th>
					<th>Zaloguj</th>
					<th>Formularz</th>
					<th>Link aktywacyjny</th>
					{if !$czy_wszystkie}<th>Umowy</th>{/if}
					<th><label for="zaznaczDane">Mailing</label> <input type="checkbox" class="checkAll" id="zaznaczDane"></th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_placowki as $a_placowka}
					<tr data-id="{$a_placowka.id_placowki}" data-id-users="{$a_placowka.id_users}">
						<td>{$a_placowka.id_placowki}</td>
						<td>{$a_placowka.nazwa}</td>
						<td>{$a_placowka.adres}</td>
						<td>{$a_placowka.kod_pocztowy}</td>
						<!--<td><nobr>{$a_placowka.status} {if $a_placowka.status=='nieaktywna'}<a href="#" class="button aktywuj_placowke">Aktywuj</a>{/if}</nobr></td>-->
						<td>{$a_placowka.poczta}</td>
						<td>{$a_placowka.typ}</td>
						<td><a href="{$_base_url}placowki/formularz-placowki/id/{$a_placowka.id_placowki}" class="modall">Edytuj</a></td>
						<td><a href="#" class="czy_usunac_placowke">Usuń</a></td>
						<td>
							<form action="https://realizacja.loca.pl" method="POST" target="_blank">
	                            <input type="hidden" name="module" value="users">
	                            <input type="hidden" name="action" value="zaloguj_na_usera">
	                            <input type="hidden" name="id_users" value="{$a_placowka.id_users}">
	                            <input type="hidden" name="id_pracownika" value="{session::get_id()}">
	                            <input type="submit" value="Zaloguj" class="autoWidth">
	                        </form>
						</td>
						<td class="center">{if $a_placowka.typ=='wewnętrzne'}<a href="#" class="buttonn wyslij_formularz_aktywacyjny">Wyślij</a>{else}&nbsp;{/if}</td>
						<td class="center">
							{if $a_placowka.typ=='wewnętrzne'}
								<form action="{$_base_url}" method="POST" class="jValidate" enctype="multipart/form-data">
		                            <input type="hidden" name="module" value="users">
		                            <input type="hidden" name="action" value="wyslij_link_aktywacyjny_konta_wewnetrznego">
		                            <input type="hidden" name="id_placowki" value="{$a_placowka.id_placowki}">
		                            <input type="file" name="formularz" class="jRequired jExtension" data-exensions="pdf"><br>
		                            <input type="submit" value="Wyślij" class="autoWidth">
		                        </form>
							{else}
								&nbsp;
							{/if}
						</td>
						{if !$czy_wszystkie}
							<td>
								{if $a_placowka.a_umowy}
									{foreach $a_placowka.a_umowy as $a_umowa}
										<a href="{$_base_url}get.php?typ=umowy&id_umowy={$a_umowa.id_umowy2}" class="umowa_{if $a_umowa.status=='oczekująca'}nie{/if}potwierdzona">{$a_umowa.numer_umowy}</a><br>
									{/foreach}
								{/if}
							</td>
						{/if}
						<td class="center"><input type="checkbox" name="a_placowki[{$a_placowka.id_placowki}]" class="checkAllTarget placowka" data-check-id="zaznaczDane"></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
	
</section>