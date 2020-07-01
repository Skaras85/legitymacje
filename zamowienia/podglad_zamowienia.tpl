<section>
	<h1>Zamówienie {$a_zamowienie.numer_zamowienia}</h1>
	
	<div class="left width33">
		<h2>Placówka</h2>
		<p>
			{$a_zamowienie.placowka_nazwa}<br>
			{$a_zamowienie.placowka_adres}<br>
			{$a_zamowienie.placowka_kod_pocztowy} {$a_zamowienie.placowka_poczta}
		</p>
	</div>
	
	<div class="left width33">
		<h2>Nabywca</h2>
		<p>
			{$a_zamowienie.nabywca_nazwa}<br>
			{$a_zamowienie.nabywca_adres}<br>
			{$a_zamowienie.nabywca_kod_pocztowy} {$a_zamowienie.nabywca_poczta}
		</p>
	</div>
	
	<div class="left width33">
		<h2>Wysyłka</h2>
		<p>
			{$a_zamowienie.wysylka_nazwa}<br>
			{$a_zamowienie.wysylka_adres}<br>
			{$a_zamowienie.wysylka_kod_pocztowy}
		</p>
	</div>
	<div class="clear"></div>
	{$tabela}
	
	{include file='produkty/tabela_produktow.tpl'}
	
</section>