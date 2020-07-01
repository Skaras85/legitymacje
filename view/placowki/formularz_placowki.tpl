<section class="formSection">
	<form action="{$_base_url}" method="POST" class="jValidate">
		<h1 class="left autoWidth">{if isset($a_placowka)}Edytuj dane {$a_placowka.nazwa}{else}Dodaj placówkę{/if}</h1>
		<input type="submit" value="{if isset($a_placowka)}Zapisz{else}Dodaj{/if}" class="button green buttonH1 buttonIcon takButton autoWidth">
	    <div class="clear"></div>
		
		<input type="hidden" name="module" value="placowki">
		<input type="hidden" name="action" value="zapisz_placowke_podglad">
	    
	    {if isset($a_placowka)}
	        <input type="hidden" name="a_placowka[id_placowki]" id="id_placowki" value="{$a_placowka.id_placowki}">
	    {/if}
		<fieldset class="clearfix">
			<div class="left">
				
				<h2>Dane placówki</h2>
				
				<label for="form_regon">Regon:</label>
				<input type="text" name="a_placowka[regon]" placeholder="Wpisz REGON placówki" id="form_regon" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.regon}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.regon}{/if}" class="jRequired jRegon">

				{if session::who('admin')}
					<a href="#" class="button szukaj_regon">Szukaj REGON</a>
				{/if}
				
				<label for="typ_szkoly" {if session::who('admin')}class="marginTop20"{/if}>Typ szkoły</label>
				<select name="a_placowka[id_typy_szkol]" id="typ_szkoly">
					{foreach $a_typy_szkol as $a_typ_szkoly}
						<option value="{$a_typ_szkoly.id_typy_szkol}" {if isset($smarty.session.form.a_placowka) && $smarty.session.form.a_placowka.id_typy_szkol==$a_typ_szkoly.id_typy_szkol || !isset($smarty.session.form.a_placowka) && isset($a_placowka) && $a_placowka.id_typy_szkol==$a_typ_szkoly.id_typy_szkol}selected{/if}>{$a_typ_szkoly.nazwa}</option>
					{/foreach}
				</select>
				
				<label for="form_nazwa">Pełna nazwa:</label>
				<input type="text" name="a_placowka[nazwa]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_nazwa" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.nazwa}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.nazwa}{/if}" class="jRequired jAlfaNum">
				
				<label for="form_nazwa_skrocona">Nazwa skrócona:</label>
				<input type="text" name="a_placowka[nazwa_skrocona]" placeholder="np. SP 12" id="form_nazwa_skrocona" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.nazwa_skrocona}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.nazwa_skrocona}{/if}" class="jRequired jAlfaNum jMaxLength" data-max-length="10">
				
				<label for="form_adres">Adres:</label>
				<input type="text" name="a_placowka[adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.adres}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.adres}{/if}" class="jRequired jAlfaNum">
				
				<label for="form_kod_pocztowy">Kod pocztowy:</label>
				<input type="text" name="a_placowka[kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.kod_pocztowy}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.kod_pocztowy}{/if}" class="jRequired jPostal">
				
				<label for="form_poczta">Poczta:</label>
				<input type="text" name="a_placowka[poczta]" placeholder="np. Działdowo" id="form_poczta" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.poczta}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.poczta}{/if}" class="jRequired jAlfaNum">
				<!--
				<label for="form_dyrektor">Imię i nazwisko Dyrektora Placówki:</label>
				<input type="text" name="a_placowka[dyrektor]" placeholder="Aktualnie obejmujący stanowisko" id="form_dyrektor" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.dyrektor}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.dyrektor}{/if}" class="jRequired jAlfaNum">
	        -->
	        </div>
	        
	        {if session::get_user('typ')=='placowka'}
		        <div class="left" id="dokument_sprzedazy">
		        	<h2>Dokument sprzedaży</h2>
		        	<div  style="margin-top: 35px">
		        		<a href="#" class="button kopiuj_dane_placowki_dokument_sprzedazy">Kopiuj dane placówki</a>
		        		{if isset($a_placowka) && $a_placowka.status=='aktywna'}
		        			<a href="#" class="button dodaj_dokument_sprzedazy">Dodaj nowy</a>
		        		{/if}
		        	</div>
		        	
		        	{if isset($a_placowka) && $a_placowka.status=='aktywna'}
		        	
		        	{/if}
	
		        	<label for="form_typ_dokumentu" class="marginTop20">Typ dokumentu:</label>
					<select id="form_typ_dokumentu" name="a_placowka[dokument_sprzedazy]" class="jRequired">
						<option value=""></option>
						{if !isset($a_placowka)}
							<option value="faktura" {if isset($smarty.session.form.a_placowka) && $smarty.session.form.a_placowka.dokument_sprzedazy=='faktura' || !isset($smarty.session.form.a_placowka)}selected{/if}>Faktura</option>
						{/if}
						{if isset($a_dokumenty_sprzedazy) && $a_dokumenty_sprzedazy}
							{foreach $a_dokumenty_sprzedazy as $a_dokument_sprzedazy}
								<option value="{$a_dokument_sprzedazy.id_dokumenty_sprzedazy}" {if $a_dokument_sprzedazy.id_dokumenty_sprzedazy==$a_placowka.id_dokumenty_sprzedazy}selected{/if}>Faktura - {$a_dokument_sprzedazy.nabywca_nazwa}</option>
							{/foreach}
						{/if}
						<option value="paragon" {if isset($a_placowka) && !isset($smarty.session.form.a_placowka) && $a_placowka.id_dokumenty_sprzedazy==0}selected{/if}>Paragon</option>
					</select>
		        	
		        	{if isset($a_dokument) && $a_dokument}
		        		<input type="hidden" name="a_dokument[id_dokumenty_sprzedazy]" value="{$a_dokument.id_dokumenty_sprzedazy}" id="id_dokumenty_sprzedazy">
		        	{/if}
		        	
		        	<div {if isset($a_placowka) && !isset($smarty.session.form.a_placowka) && $a_placowka.id_dokumenty_sprzedazy==0}class="hidden"{/if} id="nabywcaWrapper">
			        	<label for="form_nazwa_nabywcy">Nazwa nabywcy:</label>
						<input type="text" name="a_dokument[nabywca_nazwa]" placeholder="np. Urząd Miasta lub Szkoła Podstawowa itd." id="form_nazwa_nabywcy" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.nabywca_nazwa}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.nabywca_nazwa}{/if}" class="jAlfaNum {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy!=0}jRequired{/if}">
					
						<label for="form_adres_nabywcy">Adres nabywcy:</label>
						<input type="text" name="a_dokument[nabywca_adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres_nabywcy" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.nabywca_adres}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.nabywca_adres}{/if}" class="jAlfaNum {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy!=0}jRequired{/if}">
					
						<label for="form_kod_pocztowy_nabywcy">Kod pocztowy nabywcy:</label>
						<input type="text" name="a_dokument[nabywca_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_nabywcy" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.nabywca_kod_pocztowy}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.nabywca_kod_pocztowy}{/if}" class="jPostal {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy!=0}jRequired{/if}">
					
						<label for="form_poczta_nabywcy">Poczta nabywcy:</label>
						<input type="text" name="a_dokument[nabywca_poczta]" placeholder="np. Działdowo" id="form_poczta_nabywcy" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.nabywca_poczta}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.nabywca_poczta}{/if}" class="jAlfaNum {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy!=0}jRequired{/if}">
					
						<label for="form_nip_nabywcy">NIP nabywcy:</label>
						<input type="text" name="a_dokument[nabywca_nip]" placeholder="wpisz numer NIP nabywcy np. 571-146-21-11" id="form_nip_nabywcy" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.nabywca_nip}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.nabywca_nip}{/if}" class="jNIP {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy!=0}jRequired{/if}">
					</div>
					
					<div {if isset($a_placowka) && !isset($smarty.session.form.a_placowka) && $a_placowka.id_dokumenty_sprzedazy==0}class="hidden"{/if} id="odbiorcaWrapper">
						<h2>Odbiorca / płatnik</h2>
			        	<div style="margin-top: 10px">
				        	<a href="#" class="button kopiuj_dane_placowki">Kopiuj dane placówki</a>
				        	<a href="#" class="button kopiuj_dane_nabywcy {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy==0}hidden{/if}">Kopiuj dane nabywcy</a>
						</div>
		
			        	<label for="form_nazwa_platnika" style="margin-top: 25px;">Nazwa odbiorcy/płatnika:</label>
						<input type="text" name="a_dokument[platnik_nazwa]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_nazwa_platnika" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.platnik_nazwa}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.platnik_nazwa}{/if}" class="jAlfaNum">
					
						<label for="form_adres_platnika">Adres odbiorcy/płatnika:</label>
						<input type="text" name="a_dokument[platnik_adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres_platnika" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.platnik_adres}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.platnik_adres}{/if}" class="jAlfaNum">
					
						<label for="form_kod_pocztowy_platnika">Kod pocztowy odbiorcy/płatnika:</label>
						<input type="text" name="a_dokument[platnik_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_platnika" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.platnik_kod_pocztowy}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.platnik_kod_pocztowy}{/if}" class="jPostal">
					
						<label for="form_poczta_platnika">Poczta odbiorcy/płatnika:</label>
						<input type="text" name="a_dokument[platnik_poczta]" placeholder="np. Działdowo" id="form_poczta_platnika" value="{if isset($a_dokument) && !isset($smarty.session.form.a_dokument)}{$a_dokument.platnik_poczta}{/if}{if isset($smarty.session.form.a_dokument)}{$smarty.session.form.a_dokument.platnik_poczta}{/if}" class="jAlfaNum">
		       	 	</div>
	        	</div>
		        <div class="left">
	
	        		<h2>Adres wysyłki</h2>
	        		
	        		<div  style="margin-top: 35px">
	        			<a href="#" class="button kopiuj_dane_placowki_do_wysylki">Kopiuj dane placówki</a>
	        			<a href="#" class="button kopiuj_dane_nabywcy_wysylka {if isset($a_placowka) && $a_placowka.id_dokumenty_sprzedazy==0}hidden{/if}">Kopiuj dane nabywcy</a>
	        		</div>
	        		
	        		<label for="form_nazwa_wysylki" class="marginTop20">Nazwa:</label>
					<input type="text" name="a_placowka[wysylka_nazwa]" placeholder="np. Urząd Miasta lub Szkoła Podstawowa itd." id="form_nazwa_wysylki" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.wysylka_nazwa}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.wysylka_nazwa}{/if}" class="jAlfaNum jRequired">
				
					<label for="form_adres_wysylka">Adres wysyłki:</label>
					<input type="text" name="a_placowka[wysylka_adres]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_adres_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.wysylka_adres}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.wysylka_adres}{/if}" class="jAlfaNum jRequired">
				
					<label for="form_poczta_wysylka">Poczta wysyłki:</label>
					<input type="text" name="a_placowka[wysylka_poczta]" placeholder="np. Działdowo" id="form_poczta_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.wysylka_poczta}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.wysylka_poczta}{/if}" class="jAlfaNum jRequired">
				
				
					<label for="form_kod_pocztowy_wysylka">Kod pocztowy wysyłki:</label>
					<input type="text" name="a_placowka[wysylka_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.wysylka_kod_pocztowy}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.wysylka_kod_pocztowy}{/if}" class="jPostal jRequired">
	
	        		<label for="form_uwagi_wysylka">Telefon i uwagi dla kuriera:</label>
					<input type="text" name="a_placowka[uwagi_dla_kuriera]" placeholder="np. XXX XXX XXX , Kadry" id="form_uwagi_wysylka" value="{if isset($a_placowka) && !isset($smarty.session.form.a_placowka)}{$a_placowka.uwagi_dla_kuriera}{/if}{if isset($smarty.session.form.a_placowka)}{$smarty.session.form.a_placowka.uwagi_dla_kuriera}{/if}" class="">
				
					{if session::who('admin')}
						<input type="checkbox" name="a_placowka[status]" class="iCheck" {if isset($a_placowka) && $a_placowka.status=='aktywna'}checked{/if} id="czy_aktywna"> <label for="czy_aktywna">Czy aktywna?</label>
					{/if}
	        	</div>
        	{/if}
		
		</fieldset>
	</form>
</section>
