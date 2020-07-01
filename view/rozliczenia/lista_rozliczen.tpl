<section>
	<h1>Rozliczenia</h1>

	<form action="{$_base_url}rozliczenia/lista_rozliczen" method="GET" class="marginTop20">
		<select name="dokument" class="autoWidth">
			<option>wszystkie</option>
			<option {if $dokument=='faktury'}selected{/if}>faktury</option>
			<option {if $dokument=='paragony'}selected{/if}>paragony</option>
		</select>

		<select name="platnosci" id="karta" class="autoWidth">
			<option>wszystkie</option>
			<option {if $platnosci=='rozliczone'}selected{/if}>rozliczone</option>
			<option {if $platnosci=='nierozliczone'}selected{/if}>nierozliczone</option>
		</select>
		
		<select name="id_sposoby_platnosci" id="karta" class="autoWidth">
			<option value="0">Wszystkie</option>
			{foreach $a_sposoby_platnosci as $a_sposob}
				<option value="{$a_sposob.id_sposoby_platnosci}" {if $a_sposob.id_sposoby_platnosci==$id_sposoby_platnosci}selected{/if}>{$a_sposob.nazwa}</option>
			{/foreach}
		</select>
		
		<select name="rok" class="autoWidth">
			{if $a_lata}
				{foreach $a_lata as $a_rok}
					<option value="{$a_rok.rok}" {if $a_rok.rok==$wybrany_rok}selected{/if}>{$a_rok.rok}</option>
				{/foreach}
			{/if}
		</select>
		
		{include file='system_view/form_miesiac.tpl'}
		
		<input type="submit" value="Wybierz" class="autoWidth">
		<input type="submit" value="Pobierz xml" name="xml" class="autoWidth">
		<input type="submit" value="Pobierz csv" name="csv" class="autoWidth">
		<input type="submit" value="Pobierz csv2" name="csv2" class="autoWidth">
	</form>

	{if $a_zamowienia}
		{assign var=do_zaplaty_suma value=0}
		<a href="#" class="button pobierz_oplate">Pobierz opłatę</a>
		<table class="marginTop20 dataTables" id="cenniki">
			<thead>
				<tr>
					<th>LP</th>
					<th>Nr dokumentu</th>
					<th>ID placówki</th>
					<th>Nazwa placówki</th>
					<th>Data wystawienia</th>
					<th>Wartość brutto</th>
					<th>Termin płatności</th>
					<th>Pozostało do zapłaty</th>
					<th>Wpłaty</th>
					<th>Sposób płatności</th>
					<th>Sposób wysyłki</th>
					{if session::who('admin')}
						<th>Popraw</th>
						<th>Wyślij ponaglenie</th>
					{/if}
					<th><input type="checkbox" id="zaznaczDane" class="checkAll"><label for="zaznaczDane" class="inline">Zaznacz</label></th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_zamowienia as $a_zamowienie}
					<tr data-id="{$a_zamowienie.id_zamowienia}">
						<td>{counter}</td>
						<td><a href="{$_base_url}get.php?typ=faktury&numer={str_replace('/','-',$a_zamowienie['numer_faktury'])}.pdf" target="_blank">{$a_zamowienie.numer_faktury}</a></td>
						<td>{$a_zamowienie.id_placowki}</td>
						<td>{$a_zamowienie.nazwa}</td>
						<td><nobr>{$a_zamowienie.data_realizacji|substr:0:10}</nobr></td>
						<td class="wartosc_brutto">{$a_zamowienie.wartosc_zamowienia|price}</td>
						<td>{$a_zamowienie.termin_platnosci_faktury}</td>
						<td>
							{assign var=do_zaplaty value=($a_zamowienie.wartosc_zamowienia-$a_zamowienie.wplaty)}
							{assign var=do_zaplaty_suma value=$do_zaplaty_suma+$do_zaplaty}
							{$do_zaplaty|price}
						</td>
						<td>{if $a_zamowienie.wplaty}<a href="{$_base_url}rozliczenia/pokaz_wplaty/id_zamowienia/{$a_zamowienie.id_zamowienia}" class="modal">Pokaż</a>{else}&nbsp;{/if}</td>
						<td>{$a_zamowienie.nazwa_sposobu_platnosci}</td>
						<td>{$a_zamowienie.nazwa_sposobu_wysylki}</td>
						{if session::who('admin')}
							<td><a href="{$_base_url}zamowienia/formularz-realizacji/id_zamowienia/{$a_zamowienie.id_zamowienia}/korekta/1">Popraw</a></td>
							<td><a href="{$_base_url}rozliczenia/formularz-ponaglenia/id_zamowienia/{$a_zamowienie.id_zamowienia}">Wyślij</a></td>
						{/if}
						<td class="center"><input type="checkbox" name="a_zamowienia[{$a_zamowienie.id_zamowienia}]" class="checkAllTarget zamowienia" data-check-id="zaznaczDane"></td>
					</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<td colspan="6">&nbsp;</td>
				<td>{$do_zaplaty_suma|price}</td>
				<td colspan="7">&nbsp;</td>
			</tfoot>
		</table>
	{/if}
	
</section>