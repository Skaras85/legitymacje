{if $a_przesylki}
	{if isset($czy_koszyk)}
		<div class="marginTop20"></div>
		<h2 class="jMore inline" data-jmore-txt="Przesyłki - schowaj">Przesyłki - pokaż</h2>
	{/if}
	<table class="marginTop20 {if !isset($czy_koszyk)}dataTables{else}hidden{/if}" id="przesylki">
		<thead>
			<tr>
				<th>L.p.</th>
				<th>Nr przesyłki</th>
				<th>Data otrzymania</th>
				{if session::who('admin')}<th>ID placówki</th>{/if}
				<th>Rodzaj przesyłki</th>
				<th>Ilość legitymacji</th>
				{if session::who('admin')}<th>ID zamówienia</th>{/if}
				<th>Status zamówienia</th>
				{if session::who('admin')}
					{if !isset($czy_koszyk)}
						<th>Edytuj</th>
						<th>Zaloguj</th>
					{else}
						<th>Przypisz do zamówienia</th>
					{/if}
				{/if}
			</tr>
		</thead>
		<tbody>
			{foreach $a_przesylki as $a_przesylka}
				<tr>
					<td>{counter}</td>
					<td><a href="{$_base_url}przesylki/pokaz_przesylke/id_przesylki/{$a_przesylka.id_przesylki}" class="modal">{$a_przesylka.numer_przesylki}</td>
					<td>{$a_przesylka.data_otrzymania}</td>
					{if session::who('admin')}<td>{$a_przesylka.id_placowki}</td>{/if}
					<td>{$a_przesylka.rodzaj}</td>
					<td>{$a_przesylka.liczba_legitymacji}</td>
					{if session::who('admin')}<td>{$a_przesylka.id_zamowienia}</td>{/if}
					<td>{if $a_przesylka.id_zamowienia!=0}{$a_przesylka.status_zamowienia}{else}nowe{/if}</td>
					{if session::who('admin')}
						{if !isset($czy_koszyk)}
							<td><a href="{$_base_url}przesylki/formularz_przesylki/id_przesylki/{$a_przesylka.id_przesylki}" class="modall">Edytuj</a></td>
							<td>
								<form action="https://realizacja.loca.pl" method="POST" target="_blank">
		                            <input type="hidden" name="module" value="users">
		                            <input type="hidden" name="action" value="zaloguj_na_usera">
		                            <input type="hidden" name="id_users" value="{$a_przesylka.id_placowki}">
		                            <input type="hidden" name="id_pracownika" value="{session::get_id()}">
		                            <input type="submit" value="Zaloguj" class="autoWidth">
		                        </form>
							</td>
						{else}
							<td class="center"><input type="radio" name="id_przesylki" value="{$a_przesylka.id_przesylki}"></td>
						{/if}
					{/if}
				</tr>
			{/foreach}
		</tbody>
	</table>
{/if}