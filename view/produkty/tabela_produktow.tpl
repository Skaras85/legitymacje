{if $a_produkty}
	<table>
		<tr>
			<th>Nazwa produktu</th>
			<th>Ilość</th>
			<th>cena jednostkowa brutto</th>
			<th>wartość netto</th>
			<th>wartość brutto</th>
			{if !isset($czy_zamowienie)}
				<th><input type="checkbox" id="zaznaczProdukty" class="checkAll"><label for="zaznaczProdukty" class="inline">Zaznacz</label></th>
			{/if}
		</tr>
		{foreach $a_produkty as $a_produkt}
			<tr>
				<td>{$a_produkt.nazwa}</td>
				<td>{$a_produkt.ilosc}</td>
				<td>{$a_produkt.cena|price}</td>
				<td>{($a_produkt.cena/1.23)|price}</td>
				<td>{($a_produkt.cena*$a_produkt.ilosc)|price}</td>
				{if !isset($czy_zamowienie)}
					<td class="center"><input type="checkbox" name="a_produkty[{$a_produkt.id}]" class="checkAllTarget user" data-check-id="zaznaczProdukty"></td>
				{/if}
			</tr>
		{/foreach}
	</table>
{/if}