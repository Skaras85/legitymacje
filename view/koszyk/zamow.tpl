<section class="clearfix">
	{if !$czy_zamowienie_zlozone}
		<form action="{$_base_url}" method="POST" class="jValidate clearfix">
			<h1 class="left autoWidth">Dane zamówienia</h1>
			<input type="submit" value="Dalej" class="button green autoWidth buttonH1 left buttonIcon dalejButton">
			
			<div class="clear"></div>
			<input type="hidden" name="module" value="koszyk">
			<input type="hidden" name="action" value="podsumowanie_zamowienia">
			
			<div class="left">
				
				{if $a_user.typ=='placowka'}
					<h2>Dokument sprzedaży</h2>
					<label for="typ_dokumentu">Typ dokumentu:</label>
					<select id="typ_dokumentu" name="a_zamowienie[id_dokumenty_sprzedazy]" class="jRequired">
						{if isset($a_dokumenty)}
							{foreach $a_dokumenty as $a_dokument}
								<option value="{$a_dokument.id_dokumenty_sprzedazy}" {if isset($a_placowka) && !isset($smarty.session.a_zamowienie) && $a_placowka.id_dokumenty_sprzedazy==$a_dokument.id_dokumenty_sprzedazy || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.id_dokumenty_sprzedazy==$a_dokument.id_dokumenty_sprzedazy}selected{/if}>Faktura - {$a_dokument.nabywca_nazwa}</option>
							{/foreach}
						{/if}
						<option value="paragon" {if isset($a_placowka) && !isset($smarty.session.a_zamowienie) && $a_placowka.id_dokumenty_sprzedazy==0 || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.id_dokumenty_sprzedazy=='paragon'}selected{/if}>Paragon</option>
					</select>
				{/if}
				
				<h2>Adres wysyłki</h2>
        		
        		<label for="form_nazwa_wysylki">Nazwa:</label>
				<input type="text" name="a_zamowienie[wysylka_nazwa]" id="form_nazwa_wysylki" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.wysylka_nazwa}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.wysylka_nazwa}{/if}" class="jAlfaNum jRequired">
			
				<label for="form_adres_wysylka">Adres wysyłki:</label>
				<input type="text" name="a_zamowienie[wysylka_adres]" id="form_adres_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.wysylka_adres}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.wysylka_adres}{/if}" class="jAlfaNum jRequired">
			
				<label for="form_kod_pocztowy_wysylka">Kod pocztowy wysyłki:</label>
				<input type="text" name="a_zamowienie[wysylka_kod_pocztowy]" id="form_kod_pocztowy_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.wysylka_kod_pocztowy}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.wysylka_kod_pocztowy}{/if}" class="jPostal jRequired">

				<label for="form_poczta_wysylka">Poczta wysyłki:</label>
				<input type="text" name="a_zamowienie[wysylka_poczta]" id="form_poczta_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.wysylka_poczta}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.wysylka_poczta}{/if}" class="jAlfaNum jRequired">


        		<label for="form_uwagi_wysylka">Uwagi dla kuriera:</label>
				<input type="text" name="a_zamowienie[uwagi_dla_kuriera]" id="form_uwagi_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.uwagi_dla_kuriera}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.uwagi_dla_kuriera}{/if}" class="">
				
				<label for="form_uwagi">Uwagi do zamówienia:</label>
				<input type="text" name="a_zamowienie[uwagi]" id="form_uwagi" value="{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.uwagi}{/if}" class="">
	    	<!--
	    		<div class="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie) && $a_placowka.dokument_sprzedazy=='paragon' || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.dokument_sprzedazy=='paragon'}hidden{/if}" id="dokument_sprzedazy_wrapper">
		        	<label for="form_nazwa_nabywcy">Nazwa nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_nazwa]" id="form_nazwa_nabywcy" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.nabywca_nazwa}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.nabywca_nazwa}{/if}" class="jAlfaNum {if isset($a_placowka) && $a_placowka.dokument_sprzedazy=='faktura'}jRequired{/if}">
				
					<label for="form_adres_nabywcy">Adres nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_adres]" id="form_adres_nabywcy" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.nabywca_adres}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.nabywca_adres}{/if}" class="jAlfaNum {if isset($a_placowka) && $a_placowka.dokument_sprzedazy=='faktura'}jRequired{/if}">
				
					<label for="form_kod_pocztowy_nabywcy">Kod pocztowy nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_kod_pocztowy]" id="form_kod_pocztowy_nabywcy" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.nabywca_kod_pocztowy}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.nabywca_kod_pocztowy}{/if}" class="jPostal {if isset($a_placowka) && $a_placowka.dokument_sprzedazy=='faktura'}jRequired{/if}">
				
					<label for="form_poczta_nabywcy">Poczta nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_poczta]" id="form_poczta_nabywcy" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.nabywca_poczta}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.nabywca_poczta}{/if}" class="jAlfaNum {if isset($a_placowka) && $a_placowka.dokument_sprzedazy=='faktura'}jRequired{/if}">
				
					<label for="form_dokument_sprzedazy">NIP nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_nip]" id="form_dokument_sprzedazy" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.nabywca_nip}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.nabywca_nip}{/if}" class="jNIP {if isset($a_placowka) && $a_placowka.dokument_sprzedazy=='faktura'}jRequired{/if}">

				</div>
				-->
			</div>
			<div class="left formularz_zamowienia_platnosci">
				<!--
				<div class="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie) && $a_placowka.dokument_sprzedazy=='paragon' || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.dokument_sprzedazy=='paragon'}hidden{/if}" id="platnik_wrapper">
					<h2>Odbiorca / płatnik</h2>
		        	<label for="form_nazwa_platnika">Nazwa odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_nazwa]" id="form_nazwa_platnika" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.platnik_nazwa}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.platnik_nazwa}{/if}" class="jAlfaNum">
				
					<label for="form_adres_platnika">Adres odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_adres]" id="form_adres_platnika" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.platnik_adres}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.platnik_adres}{/if}" class="jAlfaNum">
				
					<label for="form_kod_pocztowy_platnika">Kod pocztowy odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_kod_pocztowy]" id="form_kod_pocztowy_platnika" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.platnik_kod_pocztowy}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.platnik_kod_pocztowy}{/if}" class="jPostal">
				
					<label for="form_poczta_platnika">Poczta odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_poczta]" id="form_poczta_platnika" value="{if isset($a_placowka) && !isset($smarty.session.a_zamowienie)}{$a_placowka.platnik_poczta}{/if}{if isset($smarty.session.a_zamowienie)}{$smarty.session.a_zamowienie.platnik_poczta}{/if}" class="jAlfaNum">

        		</div>
        	-->
        		<div class="formularz_zamowienia_platnosci">
					<h2>Sposób płatności</h2>
					{foreach $a_sposoby_platnosci as $index=>$a_sposob}
						{if $a_sposob.id_sposoby_platnosci==1 || $a_sposob.id_sposoby_platnosci==5}
							<div class="przelewy_dni {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy==0}hidden{/if}">
						{/if}
						<input type="radio" name="a_zamowienie[id_sposoby_platnosci]" value="{$a_sposob.id_sposoby_platnosci}" id="sposob_platnosci_{$a_sposob.id_sposoby_platnosci}" class="iCheck sposob_platnosci" {if !isset($smarty.session.a_zamowienie) && $index==0 || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.id_sposoby_platnosci==$a_sposob.id_sposoby_platnosci || isset($a_placowka) && !isset($smarty.session.a_zamowienie) && $a_placowka.id_dokumenty_sprzedazy==0 && $a_sposob.id_sposoby_platnosci==2 || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.id_dokumenty_sprzedazy==0 && $a_sposob.id_sposoby_platnosci==2}checked{/if}> <label for="sposob_platnosci_{$a_sposob.id_sposoby_platnosci}" class="sposob_platnosci_label">{$a_sposob.nazwa}</label>
						{if $a_sposob.id_sposoby_platnosci==1 || $a_sposob.id_sposoby_platnosci==5}
							</div>
						{/if}
					{/foreach}
					
			
					<h2>Sposób wysyłki</h2>
					{foreach $a_sposoby_wysylki as $index=>$a_sposob}
						{if $a_sposob.id_sposoby_wysylki==5}
							<div id="wysylka_pobranie" class="hidden">
						{/if}
							<input type="radio" name="a_zamowienie[id_sposoby_wysylki]" value="{$a_sposob.id_sposoby_wysylki}" id="sposob_wysylki_{$a_sposob.id_sposoby_wysylki}" {if !isset($smarty.session.a_zamowienie) && $index==0 || isset($smarty.session.a_zamowienie) && $smarty.session.a_zamowienie.id_sposoby_wysylki==$a_sposob.id_sposoby_wysylki}checked{/if} class="iCheck sposob_wysylki"> <label for="sposob_wysylki_{$a_sposob.id_sposoby_wysylki}" class="sposob_wysylki_label">{$a_sposob.nazwa} ({$a_sposob.cena_przesylki|price} PLN)</label>
						{if $a_sposob.id_sposoby_wysylki==5}
							</div>
						{/if}
					{/foreach}
					
				</div>
        		
        	</div>
			
			
			
		</form>
	{else}
		<p>Masz już złożone zamówienie z tej placówki. Czy chcesz dopisać karty do zamówienia, czy złożyć nowe zamówienie?</p>
		<a href="{$_base_url}koszyk/zamow/nowe-zamowienie/1" class="button left">Złóż nowe zamówienie</a>
		<a href="{$_base_url}koszyk/dodaj_karty_do_zamowienia" class="button right">Dodaj karty do zamówienia</a>
	{/if}
</section>