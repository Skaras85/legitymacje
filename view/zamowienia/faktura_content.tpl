<p class="tytul">LEGITYMACJE DLA OŚWIATY</p>

<img src="{$_base_url}images/site/logo-loca.jpg" id="logo">

<table class="tabela_tytulowa">
    <tr>
        <th colspan="4">
            {if $a_zamowienie.id_dokumenty_sprzedazy==0}Paragon{else}Faktura{/if} nr {$a_zamowienie.numer_faktury}
       </th>
    </tr>
    <tr>
        <td><nobr>Data wystawienia:</nobr></td>
        <td><nobr>{$a_zamowienie.data_realizacji|substr:0:10}</nobr></td>
        <td><nobr>Data sprzedaży:</nobr></td>
        <td><nobr>{$a_zamowienie.data_realizacji|substr:0:10}</nobr></td>
    </tr>
    <tr>
        <td><nobr>Termin płatności:</nobr></td>
        <td><nobr>{$data_platnosci}</nobr></td>
        <td><nobr>Metoda płatności:</nobr></td>
        <td><nobr class="smallText">{$a_zamowienie.nazwa_sposobu_platnosci}</nobr></td>
    </tr>
</table>

<div id="osoby_wrapper">
    <div id="sprzedawca">
        <strong>Sprzedawca</strong><br>
        {$dane_sprzedawcy}
    </div>
	{if $a_zamowienie.id_dokumenty_sprzedazy!=0}
	    <div id="nabywca">
	    	<strong>Nabywca</strong><br>
	        {if $a_zamowienie.id_dokumenty_sprzedazy!=0}
	        	{$a_zamowienie.nabywca_nazwa}<br>
		        {$a_zamowienie.nabywca_adres}<br>
		        {$a_zamowienie.nabywca_kod_pocztowy} {$a_zamowienie.nabywca_poczta}<br>
		        NIP: {$a_zamowienie.nabywca_nip}
	        {else}
	        	{$a_zamowienie.placowka_nazwa}<br>
	        	{$a_zamowienie.placowka_adres}<br>
		        {$a_zamowienie.placowka_kod_pocztowy} {$a_zamowienie.placowka_poczta}<br>
	        {/if}
	    </div>
    {/if}
    
    <div id="odbiorca">
		<strong>Odbiorca</strong><br>
        {if $a_zamowienie.id_dokumenty_sprzedazy!=0}
        	{$a_zamowienie.platnik_nazwa|lam_dlugi_tekst:25}<br>
	        {$a_zamowienie.platnik_adres}<br>
	        {$a_zamowienie.platnik_kod_pocztowy} {$a_zamowienie.platnik_poczta}<br>
        {else}
        	{$a_zamowienie.placowka_nazwa|lam_dlugi_tekst:25}<br>
        	{$a_zamowienie.placowka_adres}<br>
	        {$a_zamowienie.placowka_kod_pocztowy} {$a_zamowienie.placowka_poczta}<br>
        {/if}
    </div>

</div>

<img src="{$_base_url}images/pdfy/linia.png" id="linia">

<table class="tabela">
    <tr>
        <th>Lp</th>
        <th>Nazwa</th>
        <th>Jedn</th>
        <th>Ilość</th>
        <th><nobr>Cena brutto</nobr></th>
        <th>Stawka</th>
        <th><nobr>Wartość netto</nobr></th>
        <th><nobr>Wartość brutto</nobr></th>
    </tr>
    {assign var="licznik" value=1}
    {if isset($a_karta) && $a_karta}
	    <tr>
	        <td class="toRight">{$licznik++}</td>
	        <td><nobr>{$a_karta.nazwa}</nobr></td>
	        <td>szt.</td>
	        <td class="toRight">{$liczba_kart}</td>
	        <td class="toRight">{($a_zamowienie.cena_legitymacji)|price}</td>
	        <td class="toRight">23%</td>
	        <td class="toRight">{($a_zamowienie.cena_legitymacji*100*$liczba_kart/123)|price}</td>
	        <td class="toRight">{($a_zamowienie.cena_legitymacji*100*$liczba_kart/100)|price}</td>
	    </tr>
    {/if}
    {if isset($a_produkty) && $a_produkty}
    	{foreach $a_produkty as $a_produkt}
    		<tr>
		        <td class="toRight">{$licznik++}</td>
		        <td><nobr>{$a_produkt.nazwa}</nobr></td>
		        <td>szt.</td>
		        <td class="toRight">{$a_produkt.ilosc}</td>
		        <td class="toRight">{($a_produkt.cena)|price}</td>
		        <td class="toRight">23%</td>
		        <td class="toRight">{($a_produkt.cena*100*$a_produkt.ilosc/123)|price}</td>
		        <td class="toRight">{($a_produkt.cena*100*$a_produkt.ilosc/100)|price}</td>
		    </tr>
    	{/foreach}
    {/if}
    <tr>
    	
        <td class="toRight">{$licznik}</td>
        <td><nobr>Koszt przesyłki - {$a_zamowienie.nazwa_sposobu_wysylki}</nobr></td>
        <td>szt.</td>
        <td class="toRight">1</td>
        <td class="toRight">{($a_zamowienie.cena_przesylki)|price}</td>
        <td class="toRight">23%</td>
        <td class="toRight">{($a_zamowienie.cena_przesylki/1.23)|price}</td>
        <td class="toRight">{$a_zamowienie.cena_przesylki|price}</td>
    </tr>
</table>

<div class="wrapper">
    <table class="tabela podsumowanie">
        <tr>
            <th>Stawka VAT</th>
            <th>Wartość netto</th>
            <th>Kwota VAT</th>
            <th>Wartość brutto</th>
        </tr>
        <tr>
        	{assign var="cena_brutto" value=$a_zamowienie.cena_legitymacji*$liczba_kart+$a_zamowienie.cena_przesylki+$cena_produktow}
        	{assign var="cena_netto" value=$cena_brutto/1.23}
            <td class="center">23%</td>
            <td class="toRight">{$cena_netto|price}</td>
            <td class="toRight">{(($cena_brutto*100-$cena_netto*100)/100)|price}</td>
            <td class="toRight">{$cena_brutto|price}</td>
        </tr>
        <tr>
            <td class="center">Razem</td>
            <td class="toRight">{$cena_netto|price}</td>
            <td class="toRight">{(($cena_brutto*100-$cena_netto*100)/100)|price}</td>
            <td class="toRight">{$cena_brutto|price}</td>
        </tr>
    </table>
    
    <table class="tabelka_blank">
        <tr>
            <td>Zapłacono</td>
            <td>{if $a_zamowienie.data_oplacenia=='0000-00-00 00:00:00'}0,00{else}{$cena_brutto|price}{/if} PLN</td>
        </tr>
        <tr>
            <td>Do zapłaty</td>
             <td>{if $a_zamowienie.data_oplacenia!='0000-00-00 00:00:00'}0,00{else}<strong>{$cena_brutto|price}{/if}</strong> PLN</td>
        </tr>
        <tr>
            <td>Razem</td>
            <td>{$cena_brutto|price} PLN</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>słownie:<br>{hlp_functions::kwotaslownie($cena_brutto)}</td>
        </tr>
    </table>
</div>
<p class="uwagi">

        Zamówienie: {$a_zamowienie.numer_zamowienia}

</p>

<div class="uwagi_faktura">
	{$a_zamowienie.uwagi_faktura}
</div>

<div id="komunikat">
	<p>Uwaga:</p>
	<p>Od 1 sierpnia  2018 r. wykonawcą legitymacji jest powstała na bazie jednoosobowej działalności gospodarczej spółka z o.o. Aktualnie wykonawcą legitymacji jest <strong>Grupa LOCA Sp. z o.o. NIP: 571-171-80-88</strong></p>
	<p>Zmiana spowodowała również zmianę numeru konta bankowego:</p>
	<p class="bigFont">43 1090 1753 0000 0001 3684 3718</p>
</div>

{if $a_zamowienie.id_dokumenty_sprzedazy!=0}
    <div id="podpis_osoby_wystawiajacej">
        <p class="center"></p>
        <p class="smallText center">
            Nazwa podmiotu uprawnionego<br>
            do wystawiania faktury
        </p>
    </div>
    <div id="podpis_osoby_odbierajacej">
        <p class="center"></p>
        <p class="smallText center">
            Nazwa podmiotu uprawnionego<br>
            do odbioru faktury
        </p>
    </div>
{/if}
<p class="stopka clear">tel. 23 696 90 00, E-mail: biuro@loca.pl, www.loca.pl</p>
