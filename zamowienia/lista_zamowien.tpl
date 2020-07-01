<section>
	<h1>Zamówienia <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="73"></h1>
	
	{if session::who('admin') || session::who('mod')}
		<form action="{$_base_url}zamowienia/lista_zamowien" method="GET" class="marginTop20">
			<select name="status"class="autoWidth">
				<option {if $status=='złożone'}selected{/if}>złożone</option>
				<option {if $status=='w realizacji'}selected{/if}>w realizacji</option>
				<option {if $status=='w druku'}selected{/if}>w druku</option>
				<option {if $status=='wydrukowane'}selected{/if}>wydrukowane</option>
				<option {if $status=='zrealizowane'}selected{/if}>zrealizowane</option>
				<option {if $status=='anulowane'}selected{/if}>anulowane</option>
			</select>
	
			<select name="id_karty" id="karta" class="autoWidth">
				<option value="0">Wszystkie</option>
				{foreach $a_karty as $a_karta}
					<option value="{$a_karta.id_karty}" {if $a_karta.id_karty==$id_karty}selected{/if}>{$a_karta.nazwa}</option>
				{/foreach}
			</select>
			<select name="id_users" class="autoWidth">
				<option value="0">Wszyscy</option>
				{foreach $a_users as $a_user}
					<option value="{$a_user.id_users}" {if $a_user.id_users==$id_pracownika_realizujacego}selected{/if}>{$a_user.nazwisko} {$a_user.imie}</option>
				{/foreach}
			</select>
			
			<input type="submit" value="Wybierz" class="autoWidth">
			{if $status=="złożone"}
				<a href="{$_base_url}zamowienia/formularz_wyboru_pracownika_realizujacego" class="button modal">Przenieś do realizacji</a>
			{elseif $status=="w druku"}
				<a href="#" class="button pobierz_do_druku">Pobierz do druku</a>
				<a href="#" class="button dodaj_do_wydrukowanych">Wydrukowano</a>
			{elseif $status=="w realizacji"}
				<a href="#" class="button dodaj_do_druku">Do druku</a>
			{/if}
		</form>
	{/if}

	{if $a_zamowienia}
		<table class="marginTop20 dataTables" id="cenniki">
			<thead>
				<tr>
					<th>Nr zamówienia</th>
					{if session::who('admin') || session::who('mod')}<th>Id placówki</th>{/if}
					<th>Nazwa placówki</th>
					{if session::who('admin') || session::who('mod')}<th>Miasto</th>{/if}
					<th>Data złożenia</th>
					<th>Nazwa legitymacji</th>
					<th>Ilość legitymacji</th>
					{if session::who('admin') || session::who('mod')}<th>Z</th>{/if}
					{if session::who('admin') || session::who('mod')}<th>P</th>{/if}
					<th>Status płatności</th>
					{if session::who('admin') || session::who('mod')}
						{if $status=="wydrukowane"}
							<th>Data dodania do druku</th>
						{elseif $status=="zrealizowane"}
							<th>Data realizacji</th>
						{elseif $status=="złożone"}
							<th>Anuluj</th>
						{/if}
						{if $status=="zrealizowane"}
							<th>Faktura</th>
						{/if}
						{if $status=="wydrukowane"}
							<th>Zrealizuj</th>
						{else if $status!="zrealizowane"}
							<th><input type="checkbox" id="zaznaczDane" class="checkAll"><label for="zaznaczDane" class="inline">Zaznacz</label></th>
						{/if}
					{else}
						<th>Status</th>
						<th>Data realizacji</th>
						<th>Cena</th>
						<th>Dokument</th>
					{/if}
					<th>Pokaż</th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_zamowienia as $a_zamowienie}
					<tr data-id="{$a_zamowienie.id_zamowienia}" data-numer="{$a_zamowienie.numer_zamowienia}">
						<td><a href="{$_base_url}zamowienia/podglad_zamowienia/id_zamowienia/{$a_zamowienie.id_zamowienia}" class="modal">{$a_zamowienie.numer_zamowienia}</a></td>
						{if session::who('admin') || session::who('mod')}<td>{$a_zamowienie.id_placowki}</td>{/if}
						<td>{$a_zamowienie.nazwa}</td>
						{if session::who('admin') || session::who('mod')}<td>{$a_zamowienie.poczta}</td>{/if}
						<td><nobr>{$a_zamowienie.data_zlozenia|substr:0:10}</nobr></td>
						<td>{$a_zamowienie.nazwa_legitymacji}</td>
						<td>{$a_zamowienie.liczba_kart}</td>
						{if session::who('admin') || session::who('mod')}<td>{$a_zamowienie.liczba_zdjec}</td>{/if}
						{if session::who('admin') || session::who('mod')}<td>{$a_zamowienie.liczba_podpisow}</td>{/if}
						<td>{$a_zamowienie.nazwa_sposobu_platnosci} {if $a_zamowienie.id_sposoby_platnosci==2}({if $a_zamowienie.data_oplacenia!='0000-00-00 00:00:00'}opłacono{else}nieopłacono{/if}){/if}</td>
						{if session::who('admin') || session::who('mod')}
							{if $status=="wydrukowane"}
								<td><nobr>{$a_zamowienie.data_dodania_do_druku|substr:0:10}</nobr></td>
							{elseif $status=="złożone"}
								<td><a href="#" class="czy_anulowac_zamowienie">Anuluj</a></td>
							{elseif $status=="zrealizowane"}
								<td><nobr>{$a_zamowienie.data_realizacji|substr:0:10}</nobr></td>
							{/if}
							{if $status=="zrealizowane"}
								<td><a href="{$_base_url}images/faktury/{str_replace('/','-',$a_zamowienie['numer_faktury'])}.pdf" download target="_blank">pobierz</a></td>
							{/if}
							{if $status=="wydrukowane"}
								<td class="center"><a href="{$_base_url}zamowienia/formularz-realizacji/id_zamowienia/{$a_zamowienie.id_zamowienia}" class="">Zrealizuj</a>
							{else if $status!="zrealizowane"}
								<td class="center"><input type="checkbox" name="a_zamowienia[{$a_zamowienie.id_zamowienia}]" class="checkAllTarget zamowienia" data-check-id="zaznaczDane" {if $a_zamowienie.liczba_zdjec!=$a_zamowienie.liczba_kart || $a_zamowienie.id_karty==1 && $a_zamowienie.liczba_podpisow!=$a_zamowienie.liczba_kart}disabled{/if}></td>
							{/if}
						{else}
							<td>{$a_zamowienie.status}</td>
							<td><nobr>{$a_zamowienie.data_realizacji|substr:0:10}</nobr></td>
							<td><nobr>{$a_zamowienie.wartosc_zamowienia|price} PLN</nobr></td>
							<td>
								{if $a_zamowienie.numer_faktury!=''}
									<a href="{$_base_url}images/faktury/{str_replace('/','-',$a_zamowienie.numer_faktury)}.pdf" download target="_blank">faktura nr {$a_zamowienie.numer_faktury}</a>
								{elseif $a_zamowienie.id_dokumenty_sprzedazy!=0}
									faktura
								{else}
									paragon
								{/if}
							</td>
						{/if}
						<td><a href="{$_base_url}legitymacje/lista-osob-legitymacji/id_zamowienia/{$a_zamowienie.id_zamowienia}">Pokaż</a></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{else}
		<p class="communicat_info">Brak złożonych zamówień</p>
	{/if}
	
</section>