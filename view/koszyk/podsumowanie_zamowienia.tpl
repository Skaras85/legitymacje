<section class="clearfix">
	
	<h1>Podsumowanie zamówienia</h1>
	
	{if !isset($czy_email)}
		<a href="{$_base_url}koszyk/zloz_zamowienie" class="button left green buttonIcon takButton">Zamawiam z obowiązkiem zapłaty</a>
		<a href="{$_base_url}koszyk/zamow" class="button red right autoWidth buttonIcon wsteczButton">Popraw dane</a>
	{/if}
	
	{if isset($a_zamowienie) && isset($a_zamowienie.numer_zamowienia)}
		<p>
			Nr zamówienia: {$a_zamowienie.numer_zamowienia}<br>
			Data zamówienia: {$a_zamowienie.data_zlozenia}<br>
			Data realizacji: {$a_zamowienie.data_realizacji}
		</p>
	{/if}
	
	<div class="clear"></div>
	<p class="marginTop20">
		{if isset($a_karta) && $a_karta}
			{$a_karta.nazwa}: {$liczba_kart} szt. x {$cena_karty|price} = {($liczba_kart*$cena_karty)|price} PLN brutto
		{/if}
		{if isset($a_produkty) && $a_produkty}
			{foreach $a_produkty as $a_produkt}
				<br>{$a_produkt.nazwa}, {$a_produkt.ilosc} szt. x {$a_produkt.cena|price} = {($a_produkt.ilosc*$a_produkt.cena)|price} PLN brutto
			{/foreach}
		{/if}
	</p>
	<p>Sposób wysyłki: {$a_sposob_wysylki.nazwa}</p>
	<p>Koszt przesyłki: {$cena_za_wysylke|price} PLN</p>
	<p class="bold"><b>WARTOŚĆ ZAMÓWIENIA: {$wartosc_zamowienia|price} PLN brutto</b></p>
	
	<h2>Dokument sprzedaży</h2>
	<p>
		Typ dokumentu: {if $a_user.typ=='placowka'}{if $a_zamowienie.id_dokumenty_sprzedazy==0}Paragon{else}Faktura - {if isset($a_dokument)}{$a_dokument.nabywca_nazwa}{else}{$a_zamowienie.nabywca_nazwa}{/if}{/if}{else}Faktura{/if}<br>
		{if $a_zamowienie.id_dokumenty_sprzedazy!=0 || $a_user.typ=='agencja'}
			Nazwa nabywcy: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.nabywca_nazwa}{else}{$a_zamowienie.nabywca_nazwa}{/if}{else}{$a_user.nazwa}{/if}<br>
			Adres nabywcy: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.nabywca_adres}{else}{$a_zamowienie.nabywca_adres}{/if}{else}{$a_user.ulica}{/if}<br>
			Kod pocztowy nabywcy: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.nabywca_kod_pocztowy}{else}{$a_zamowienie.nabywca_kod_pocztowy}{/if}{else}{$a_user.kod_pocztowy}{/if}<br>
			Poczta nabywcy: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.nabywca_poczta}{else}{$a_zamowienie.nabywca_poczta}{/if}{else}{$a_user.miasto}{/if}<br>
			NIP nabywcy: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.nabywca_nip}{else}{$a_zamowienie.nabywca_nip}{/if}{else}{$a_user.nip}{/if}<br>
		{/if}
	</p>
	{if $a_zamowienie.id_dokumenty_sprzedazy!=0 || $a_user.typ=='agencja'}
		<h2>Odbiorca / płatnik</h2>
		<p>
			Nazwa odbiorcy/płatnika: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.platnik_nazwa}{else}{$a_zamowienie.platnik_nazwa}{/if}{else}{$a_user.platnik_nazwa}{/if}<br>
			Adres odbiorcy/płatnika: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.platnik_adres}{else}{$a_zamowienie.platnik_adres}{/if}{else}{$a_user.platnik_adres}{/if}<br>
			Kod pocztowy odbiorcy/płatnika: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.platnik_kod_pocztowy}{else}{$a_zamowienie.platnik_kod_pocztowy}{/if}{else}{$a_user.platnik_kod_pocztowy}{/if}<br>
			Poczta odbiorcy/płatnika: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_dokument.platnik_poczta}{else}{$a_zamowienie.platnik_poczta}{/if}{else}{$a_user.platnik_poczta}{/if}<br>
		</p>
	{/if}
	
	<h2>Adres wysyłki</h2>
	<p>
		Nazwa: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_zamowienie.wysylka_nazwa}{else}{$a_zamowienie.wysylka_nazwa}{/if}{else}{$a_user.wysylka_nazwa}{/if}<br>
		Adres wysyłki: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_zamowienie.wysylka_adres}{else}{$a_zamowienie.wysylka_adres}{/if}{else}{$a_user.wysylka_adres}{/if}<br>
		Kod pocztowy wysyłki: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_zamowienie.wysylka_kod_pocztowy}{else}{$a_zamowienie.wysylka_kod_pocztowy}{/if}{else}{$a_user.wysylka_kod_pocztowy}{/if}<br>
		Poczta wysyłki: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_zamowienie.wysylka_poczta}{else}{$a_zamowienie.wysylka_poczta}{/if}{else}{$a_user.wysylka_poczta}{/if}<br>
		Uwagi dla kuriera: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_zamowienie.uwagi_dla_kuriera}{else}{$a_zamowienie.uwagi_dla_kuriera}{/if}{else}{$a_user.uwagi_dla_kuriera}{/if}<br>
		Uwagi do zamówienia: {if $a_user.typ=='placowka'}{if isset($a_dokument)}{$a_zamowienie.uwagi}{else}{$a_zamowienie.uwagi}{/if}{else}{$a_user.uwagi}{/if}<br>
	</p>
	
	
</section>