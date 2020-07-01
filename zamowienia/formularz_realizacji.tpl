<section class="formSection">
	<h1>Sprawdź dane i zapisz</h1>

	<form action="{$_base_url}" method="POST" class="jValidate" target="_blank">
		<input type="hidden" name="module" value="zamowienia">
		<input type="hidden" name="action" value="realizuj_zamowienie">
		<input type="hidden" name="a_zamowienie[id_zamowienia]" value="{$a_zamowienie.id_zamowienia}">
	    
	    <fieldset class="clearfix">
			<div class="left">
				<h2>Dane placówki</h2>
				
				<label for="form_nazwa">Pełna nazwa:</label>
				<input type="text" name="a_zamowienie[placowka_nazwa]" {if !$czy_korekta}disabled{/if} id="form_nazwa" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.placowka_nazwa}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.placowka_nazwa}{/if}" class="jRequired jAlfaNum">
				
				<label for="form_regon">Regon:</label>
				<input type="text" name="a_zamowienie[placowka_regon]" {if !$czy_korekta}disabled{/if} id="form_regon" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.placowka_regon}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.placowka_regon}{/if}" class="jRequired jRegon">
	
				<label for="form_adres">Adres:</label>
				<input type="text" name="a_zamowienie[placowka_adres]" {if !$czy_korekta}disabled{/if} id="form_adres" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.placowka_adres}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.placowka_adres}{/if}" class="jRequired jAlfaNum">
				
				<label for="form_kod_pocztowy">Kod pocztowy:</label>
				<input type="text" name="a_zamowienie[placowka_kod_pocztowy]" {if !$czy_korekta}disabled{/if} id="form_kod_pocztowy" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.placowka_kod_pocztowy}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.placowka_kod_pocztowy}{/if}" class="jRequired jPostal">
				
				<label for="form_poczta">Poczta:</label>
				<input type="text" name="a_zamowienie[placowka_poczta]" {if !$czy_korekta}disabled{/if} id="form_poczta" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.placowka_poczta}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.placowka_poczta}{/if}" class="jRequired jAlfaNum">
				
				<label for="form_dyrektor">Imię i nazwisko Dyrektora Placówki:</label>
				<input type="text" name="a_zamowienie[placowka_dyrektor]" {if !$czy_korekta}disabled{/if} id="form_dyrektor" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.placowka_dyrektor}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.placowka_dyrektor}{/if}" class="jRequired jAlfaNum">

	        	<input type="submit" class="button" value="{if isset($a_zamowienie)}Zapisz{else}Dodaj{/if}">
	        </div>
	        <div class="left">
	        	
	        	<h2>Dokument sprzedaży</h2>
	        	<label for="form_typ_dokumentu">Typ dokumentu:</label>
				<select id="form_typ_dokumentu" name="a_zamowienie[id_dokumenty_sprzedazy]" class="jRequired">
					<option value="0" {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy==0}selected{/if}>Paragon</option>
					{if $a_dokumenty}
						{foreach $a_dokumenty as $a_dokument}
							<option value="{$a_dokument.id_dokumenty_sprzedazy}" {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy==$a_dokument.id_dokumenty_sprzedazy}selected{/if}>Faktura - {$a_dokument.nabywca_nazwa}</option>
						{/foreach}
					{/if}
					
				</select>
	        	
	        	<div {if $a_zamowienie.id_dokumenty_sprzedazy==0}class="hidden"{/if} id="nabywcaWrapper">
		        	<label for="form_nazwa_nabywcy">Nazwa nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_nazwa]" id="form_nazwa_nabywcy" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.nabywca_nazwa}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.nabywca_nazwa}{/if}" class="jAlfaNum {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy!=0}jRequired{/if}">
				
					<label for="form_adres_nabywcy">Adres nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_adres]" id="form_adres_nabywcy" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.nabywca_adres}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.nabywca_adres}{/if}" class="jAlfaNum {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy!=0}jRequired{/if}">
				
					<label for="form_kod_pocztowy_nabywcy">Kod pocztowy nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_kod_pocztowy]" id="form_kod_pocztowy_nabywcy" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.nabywca_kod_pocztowy}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.nabywca_kod_pocztowy}{/if}" class="jPostal {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy!=0}jRequired{/if}">
				
					<label for="form_poczta_nabywcy">Poczta nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_poczta]" id="form_poczta_nabywcy" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.nabywca_poczta}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.nabywca_poczta}{/if}" class="jAlfaNum {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy!=0}jRequired{/if}">
				
					<label for="form_nip_nabywcy">NIP nabywcy:</label>
					<input type="text" name="a_zamowienie[nabywca_nip]" id="form_nip_nabywcy" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.nabywca_nip}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.nabywca_nip}{/if}" class="jNIP {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy!=0}jRequired{/if}">
				</div>
				
				<div {if $a_zamowienie.id_dokumenty_sprzedazy==0}class="hidden"{/if} id="odbiorcaWrapper">
					<h2>Odbiorca / płatnik</h2>
		        	<div style="margin-top: 10px">
			        	<a href="#" class="button kopiuj_dane_placowki">Kopiuj dane placówki</a>
			        	<a href="#" class="button kopiuj_dane_nabywcy {if isset($a_zamowienie) && $a_zamowienie.id_dokumenty_sprzedazy==0 || !isset($a_zamowienie)}hidden{/if}">Kopiuj dane nabywcy</a>
					</div>
	
		        	<label for="form_nazwa_platnika" style="margin-top: 25px;">Nazwa odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_nazwa]" id="form_nazwa_platnika" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.platnik_nazwa}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.platnik_nazwa}{/if}" class="jAlfaNum">
				
					<label for="form_adres_platnika">Adres odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_adres]" id="form_adres_platnika" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.platnik_adres}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.platnik_adres}{/if}" class="jAlfaNum">
				
					<label for="form_kod_pocztowy_platnika">Kod pocztowy odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_kod_pocztowy]" id="form_kod_pocztowy_platnika" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.platnik_kod_pocztowy}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.platnik_kod_pocztowy}{/if}" class="jPostal">
				
					<label for="form_poczta_platnika">Poczta odbiorcy/płatnika:</label>
					<input type="text" name="a_zamowienie[platnik_poczta]" id="form_poczta_platnika" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.platnik_poczta}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.platnik_poczta}{/if}" class="jAlfaNum">
	        	</div>
        	</div>
	        <div class="left">
	        	
	        	
        		<h2>Adres wysyłki</h2>
        		
        		<div  style="margin-top: 46px">
        			<a href="#" class="button kopiuj_dane_placowki_do_wysylki">Kopiuj dane placówki</a>
        		</div>
        		
        		<label for="form_nazwa_wysylki" class="marginTop20">Nazwa:</label>
				<input type="text" name="a_zamowienie[wysylka_nazwa]" id="form_nazwa_wysylki" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.wysylka_nazwa}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.wysylka_nazwa}{/if}" class="jAlfaNum">
			
				<label for="form_adres_wysylka">Adres wysyłki:</label>
				<input type="text" name="a_zamowienie[wysylka_adres]" id="form_adres_wysylka" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.wysylka_adres}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.wysylka_adres}{/if}" class="jAlfaNum">
			
				<label for="form_kod_pocztowy_wysylka">Kod pocztowy wysyłki:</label>
				<input type="text" name="a_zamowienie[wysylka_kod_pocztowy]" id="form_kod_pocztowy_wysylka" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.wysylka_kod_pocztowy}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.wysylka_kod_pocztowy}{/if}" class="jPostal">

				<label for="form_poczta_wysylka">Poczta wysyłki:</label>
				<input type="text" name="a_zamowienie[wysylka_poczta]" id="form_poczta_wysylka" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.wysylka_poczta}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.wysylka_poczta}{/if}" class="">


        		<label for="form_uwagi_wysylka">Uwagi dla kuriera:</label>
				<input type="text" name="a_zamowienie[uwagi_dla_kuriera]" id="form_uwagi_wysylka" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.uwagi_dla_kuriera}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.uwagi_dla_kuriera}{/if}" class="">
			
				<label for="form_uwagi">Uwagi do zamówienia:</label>
				<input type="text" name="a_zamowienie[uwagi]" id="form_uwagi" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.uwagi}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.uwagi}{/if}" class="">
			
				<label for="form_uwagi_faktura">Uwagi do faktury:</label>
				<input type="text" name="a_zamowienie[uwagi_faktura]" id="form_uwagi_faktura" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.uwagi_faktura}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.uwagi_faktura}{/if}" class="">
			
			
				<h2>Sposób płatności</h2>
				{if $a_zamowienie.id_sposoby_platnosci!=2 || $a_zamowienie.id_sposoby_platnosci==2 && $a_zamowienie.data_oplacenia=='0000-00-00 00:00:00'}
					{foreach $a_sposoby_platnosci as $index=>$a_sposob}
						<input type="radio" name="a_zamowienie[id_sposoby_platnosci]" value="{$a_sposob.id_sposoby_platnosci}" id="sposob_platnosci_{$a_sposob.id_sposoby_platnosci}" class="iCheck" {if $a_zamowienie.id_sposoby_platnosci==$a_sposob.id_sposoby_platnosci}checked{/if}> <label for="sposob_platnosci_{$a_sposob.id_sposoby_platnosci}" class="sposob_platnosci_label">{$a_sposob.nazwa} {if $a_sposob.id_sposoby_platnosci==2 && $a_zamowienie.data_oplacenia=='0000-00-00 00:00:00'}(nieopłacone){/if}</label>
					{/foreach}
				{else}
					<p>Opłacone przez przelewy24: {$a_zamowienie.data_oplacenia}</p>
					<input type="hidden" name="a_zamowienie[id_sposoby_platnosci]" value="2">
				{/if}
		
				<h2>Sposób wysyłki</h2>
				{foreach $a_sposoby_wysylki as $index=>$a_sposob}
					<input type="radio" name="a_zamowienie[id_sposoby_wysylki]" value="{$a_sposob.id_sposoby_wysylki}" id="sposob_wysylki_{$a_sposob.id_sposoby_wysylki}" {if $a_zamowienie.id_sposoby_wysylki==$a_sposob.id_sposoby_wysylki}checked{/if} class="iCheck"> <label for="sposob_wysylki_{$a_sposob.id_sposoby_wysylki}" class="sposob_wysylki_label">{$a_sposob.nazwa}</label>
				{/foreach}
				
				<label for="form_faktura_termin_platnosci">Liczba dni do opłacenia faktury:</label>
				<input type="text" name="a_zamowienie[termin_platnosci_faktury]" id="form_faktura_termin_platnosci" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.termin_platnosci_faktury}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.termin_platnosci_faktury}{/if}" class="jID">

				
				<div class="marginTop20">
					<input type="checkbox" id="dopisz_do_listy_wysylkowej" name="a_zamowienie[dopisz_do_listy_wysylkowej]" class="iCheck" checked> <label for="dopisz_do_listy_wysylkowej">Dopisz do listy wysyłkowej</label>
        		</div>

				{if !$czy_korekta}
	        		<div class="marginTop20">
						<input type="checkbox" id="czy_powiadomienie" name="a_zamowienie[czy_powiadomienie]" class="iCheck" checked> <label for="czy_powiadomienie">Czy wysłać powiadomienie?</label>
	        		</div>
        		{/if}
        	</div>
    	</fieldset>
    	{if $czy_korekta}
    		<fieldset class="clearfix">
        	
	        	<div class="left">
	        		<h2>Pozostałe</h2>
	        		
	        		<input type="hidden" name="czy_korekta" value="1">
	        		
	        		<label for="form_cena_legitymacji">Cena legitymacji:</label>
					<input type="text" name="a_zamowienie[cena_legitymacji]" id="form_cena_legitymacji" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.cena_legitymacji}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.cena_legitymacji}{/if}" class="jRequired jPrice">
	
					<label for="form_cena_przesylki">Cena przesylki:</label>
					<input type="text" name="a_zamowienie[cena_przesylki]" id="form_cena_przesylki" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.cena_przesylki}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.cena_przesylki}{/if}" class="jRequired jPrice">
	
	        		<label for="form_status">Status zamówienia:</label>
	        		<select name="a_zamowienie[status]" id="form_status">
	        			<option {if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie) && $a_zamowienie.status=='złożone' || isset($smarty.session.form.a_zamowienie.status) && $smarty.session.form.a_zamowienie.status=='złożone'}selected{/if}>złożone</option>
	        			<option {if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie) && $a_zamowienie.status=='w realizacji' || isset($smarty.session.form.a_zamowienie.status) && $smarty.session.form.a_zamowienie.status=='w realizacji'}selected{/if}>w realizacji</option>
	        			<option {if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie) && $a_zamowienie.status=='w druku' || isset($smarty.session.form.a_zamowienie.status) && $smarty.session.form.a_zamowienie.status=='w druku'}selected{/if}>w druku</option>
	        			<option {if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie) && $a_zamowienie.status=='zrealizowane' || isset($smarty.session.form.a_zamowienie.status) && $smarty.session.form.a_zamowienie.status=='zrealizowane'}selected{/if}>zrealizowane</option>
	        			<option {if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie) && $a_zamowienie.status=='anulowane' || isset($smarty.session.form.a_zamowienie.status) && $smarty.session.form.a_zamowienie.status=='anulowane'}selected{/if}>anulowane</option>
	        			<option {if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie) && $a_zamowienie.status=='wydrukowane' || isset($smarty.session.form.a_zamowienie.status) && $smarty.session.form.a_zamowienie.status=='wydrukowane'}selected{/if}>wydrukowane</option>
	        		</select>
	        		
	        		<label for="form_data_realizacji">Data realizacji:</label>
					<input type="text" name="a_zamowienie[data_realizacji]" id="form_data_realizacji" value="{if isset($a_zamowienie) && !isset($smarty.session.form.a_zamowienie)}{$a_zamowienie.data_realizacji}{/if}{if isset($smarty.session.form.a_zamowienie)}{$smarty.session.form.a_zamowienie.data_realizacji}{/if}" class="jRequired jDate">
					<input type="submit" class="button" value="{if isset($a_zamowienie)}Zapisz{else}Dodaj{/if}">
	        	</div>
			</fieldset>
		{/if}
	</form>
</section>
