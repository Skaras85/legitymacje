<section class="formularz_migracji">
	<form method="POST">
		<input type="hidden" name="module" value="migracja">
		<input type="hidden" name="action" value="zapisz_dane">
		<fieldset class="step chosenStep jValidate formSection">
	        <div class="left h1">{$a_migracja.title}</div>
	        <div class="right h1">Krok 1 z 6</div>
	        <div class="clear"></div>
	        {if !session::who('admin')}
	    		{$a_migracja.text}
	    	{/if}
	    	<div class="left">
		    	<h2>Twoje dane</h2>
		    	
    			<input type="hidden" name="a_user[id_users_old]" value="{$a_placowka.id_placowki}">
		    	
		    	<label for="imie">Imię</label>
		    	<input type="text" name="a_user[imie]" placeholder="Tu wpisz swoje imię" value="{$a_placowka.imie}" class="jRequired jAlfaNum" id="imie">
		    	
		    	<label for="nazwisko">Nazwisko</label>
		    	<input type="text" name="a_user[nazwisko]" placeholder="Tu wpisz swoje nazwisko" value="{$a_placowka.nazwisko}" class="jRequired jAlfaNum" id="nazwisko">
		    	
		    	<label for="telefon">Telefon</label>
		    	<input type="text" name="a_user[telefon]" placeholder="Tu wpisz swój numer telefonu" value="{$a_placowka.telefon}" class="jRequired jAlfanum" id="telefon">
		    	
		    	<label for="email">Email</label>
		    	<input type="text" name="a_user[email]" placeholder="Tu wpisz swój adres e-mail"  value="{$a_placowka.email}" class="jRequired jEMail migracja_email" id="email">
		    	
		    	{if !session::who('admin')}
			    	<label for="form_haslo">Hasło</label>
			        <input type="password" name="a_user[haslo]" placeholder="Tu wpisz swoje hasło, min. 8 dowolnych znaków" id="form_haslo" class="jEqual jMinLength jRequired" data-min-length="8" data-equal-to="form_powtorz_haslo" data-equal-txt="Hasła nie są identyczne">
			        
			        <label for="form_powtorz_haslo">Powtórz hasło</label>
			        <input type="password" name="a_user[haslo_powtorzone]" placeholder="Tu ponownie wpisz swoje hasło" id="form_powtorz_haslo" class="jRequired jEqual" data-equal-to="form_haslo" data-equal-txt="Hasła nie są identyczne">
	        	{/if}
	        	
		        <label for="form_typ_konta">Typ konta:</label>
				<select id="form_typ_konta" name="a_user[typ]" class="jRequired">
					<option value="placowka">Placówka oświaty</option>
					<option value="agencja">Agencja</option>
				</select>	
        	</div>
	        <div class="hidden left" id="dane_agencji">
	        	
	        	<h2>Dane agencji</h2>
	        	
		        <label for="form_user_nazwa">Nazwa:</label>
				<input type="text" name="a_user[nazwa]" id="form_user_nazwa" value="" class="jAlfaNum">
			
				<label for="form_ulica">Ulica i numer:</label>
				<input type="text" name="a_user[ulica]" id="form_ulica" value="" class="jAlfaNum">
			
				<label for="form_user_kod_pocztowy">Kod pocztowy:</label>
				<input type="text" name="a_user[kod_pocztowy]" id="form_user_kod_pocztowy" value="" class="jPostal">
				
				<label for="form_nip">NIP:</label>
				<input type="text" name="a_user[nip]" id="form_nip" value="" class="jNIP">

			</div>
	    	<div class="clear"></div>
	    	<input type="submit" value="Dalej ->" class="button nextStep right green">
	    	
		</fieldset>
		<fieldset class="step jValidate hidden">
			<div class="left h1">Regulaminy i zgody</div>
	        <div class="right h1">Krok 2 z 6</div>
			<div class="marginTop20 clear">
				<input type="checkbox" name="regulamin" id="regulamin" class="iCheck jChecked" checked> <label for="regulamin">Akceptuję <a href="{$_base_url}strony/regulamin,11" class="modal">regulamin</a></label>
				<div></div>
				<input type="checkbox" name="polityka_prywatnosci" id="polityka_prywatnosci" class="iCheck jChecked" checked> <label for="polityka_prywatnosci">Akceptuję <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">politykę prywatności</a></label>
			</div>
			<div class="marginTop20">
				<input type="checkbox" name="zgoda1" id="zgoda1" class="iCheck jChecked" checked> <label for="zgoda1">Wyrażam zgodę na przejście praw i obowiązków z poprzednio zawartej między Placówką oświaty, w imieniu której działam, umowy powierzenia przetwarzania danych osobowych w aplikacji https://legitymacje.loca.pl , z Mariuszem Kociędą prowadzącym działalność gospodarczą pod firmą Mariusz Kocięda "Grupa Loca" (ze stałym adresem wykonywania działalności gospodarczej przy ul. Stefana Żeromskiego 6,13-200 Działdowo), na spółkę Grupa LOCA sp. z o.o. z siedzibą w Działdowie przy ul. Stefana Żeromskiego 6, 13-200 Działdowo</label>
				<input type="checkbox" name="zgoda2" id="zgoda2" class="iCheck jChecked" checked> <label for="zgoda2">Oświadczam, że jestem uprawniony do składania w/w oświadczeń w imieniu Placówki oświaty, którą reprezentuję</label>
			</div>
			<div class="marginTop20">
            	<input type="checkbox" name="a_user[czy_newsletter]" id="newsletter" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter"> <label for="newsletter"  class="">( Opcjonalnie - nie jest wymagane do założenia konta )
				Wyrażam zgodę na otrzymywanie od firmy  Grupa LOCA Sp. Z o.o. informacji handlowych drogą elektroniczną, zgodnie z art. 10 ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną (t.j. Dz.U. z 2017 r., poz. 1219 z późn. zm.)</label>
				<input type="checkbox" name="a_user[czy_newsletter2]" id="newsletter2" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter"> <label for="newsletter2" class="">(Opcjonalnie - nie jest wymagane do założenia konta)
				Wyrażam zgodę na przetwarzanie przez firmę Grupa LOCA Sp. Z o.o.  moich danych osobowych na potrzeby otrzymywania newslettera. Oświadczam, że zapoznałem/am się z obowiązkiem informacyjnym administratora danych, który administrator spełnia w II. 13 dokumentu „Polityka prywatności” dostępnego na <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">{$_base_url}strony/polityka-prywatnosci,22</a>.</label>
			</div>
			<input type="submit" value="<- Powrót" class="button prevStep left red">
	    	<input type="submit" value="Dalej ->" class="button nextStep right green">
		</fieldset>
		<fieldset class="step jValidate hidden">
	        <div class="left h1">{$a_migracja.title}</div>
	        <div class="right h1">Krok 3 z 6</div>
	        <div class="clear"></div>
	        <h2>Dane placówki</h2>
	        
	        <label for="id_placowki">Numer</label>
	        <input type="text" id="id_placowki" value="{$a_placowka.id_placowki}" disabled>
	        
	    	<label for="typ_szkoly">Typ szkoły</label>
			<select name="a_placowka[id_typy_szkol]" id="typ_szkoly" class="jRequired">
				<option value="">Wybierz typ szkoły</option>
				{foreach $a_typy_szkol as $a_typ_szkoly}
					<option value="{$a_typ_szkoly.id_typy_szkol}">{$a_typ_szkoly.nazwa}</option>
				{/foreach}
			</select>
			
			<label for="form_nazwa">Pełna nazwa:</label>
			<input type="text" name="a_placowka[nazwa]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_nazwa" value="{$a_placowka.nazwa_placowki}" class="jRequired jAlfaNum">
			
			<label for="form_regon">Regon:</label>
			<input type="text" name="a_placowka[regon]" placeholder="Wpisz REGON placówki" id="form_regon" value="" class="jRequired jRegon">
			
			{if session::who('admin')}
				<a href="#" class="button szukaj_regon">Szukaj REGON</a>
			{/if}
			
			<label for="form_nazwa_skrocona" {if session::who('admin')}class="marginTop20"{/if}>Nazwa skrócona:</label>
			<input type="text" name="a_placowka[nazwa_skrocona]" placeholder="np. SP 12" id="form_nazwa_skrocona" value="" class="jRequired jAlfaNum jMaxLength" data-max-length="10">
			
			<label for="form_adres">Adres:</label>
			<input type="text" name="a_placowka[adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres" value="{$a_placowka.ulica}" class="jRequired jAlfaNum">
			
			<label for="form_kod_pocztowy">Kod pocztowy:</label>
			<input type="text" name="a_placowka[kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy" value="{$a_placowka.kod_pocztowy}" class="jRequired jPostal">
			
			<label for="form_poczta">Poczta:</label>
			<input type="text" name="a_placowka[poczta]" placeholder="np. Działdowo" id="form_poczta" value="{$a_placowka.miasto}" class="jRequired jAlfaNum">
			
			<label for="form_dyrektor">Imię i nazwisko Dyrektora Placówki:</label>
			<input type="text" name="a_placowka[dyrektor]" placeholder="Aktualnie obejmujący stanowisko" id="form_dyrektor" value="" class="jRequired jAlfaNum">

			<input type="submit" value="<- Powrót" class="button prevStep left red">
	    	<input type="submit" value="Dalej ->" class="button nextStep right green">
	    	
		</fieldset>
		<fieldset class="step jValidate hidden">
	        <div class="left h1">{$a_migracja.title}</div>
	        <div class="right h1">Krok 4 z 6</div>
	        <div class="clear"></div>
	        <h2>Dane do rozliczenia</h2>
	        
        	<label for="form_typ_dokumentu">Typ dokumentu:</label>
			<select id="form_typ_dokumentu" name="a_placowka[dokument_sprzedazy]" class="jRequired">
				<option value=""></option>
				<option value="faktura" selected>Faktura</option>
				<option value="paragon">Paragon</option>
			</select>
        	
        	<div id="nabywcaWrapper">
	        	<label for="form_nazwa_nabywcy">Nazwa nabywcy:</label>
				<input type="text" name="a_placowka[nabywca_nazwa]" placeholder="np. Urząd Miasta lub Szkoła Podstawowa itd." id="form_nazwa_nabywcy" value="{$a_placowka.faktura_nazwa_placowki}" class="jAlfaNum Required">
			
				<label for="form_adres_nabywcy">Adres nabywcy:</label>
				<input type="text" name="a_placowka[nabywca_adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres_nabywcy" value="{$a_placowka.faktura_ulica}" class="jAlfaNum jRequired">
			
				<label for="form_kod_pocztowy_nabywcy">Kod pocztowy nabywcy:</label>
				<input type="text" name="a_placowka[nabywca_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_nabywcy" value="{$a_placowka.faktura_kod_pocztowy}" class="jPostal jRequired">
			
				<label for="form_poczta_nabywcy">Poczta nabywcy:</label>
				<input type="text" name="a_placowka[nabywca_poczta]" placeholder="np. Działdowo" id="form_poczta_nabywcy" value="{$a_placowka.faktura_miasto}" class="jAlfaNum jRequired">
			
				<label for="form_nip_nabywcy">NIP nabywcy:</label>
				<input type="text" name="a_placowka[nabywca_nip]" placeholder="wpisz numer NIP nabywcy np. 571-146-21-11" id="form_nip_nabywcy" value="{$a_placowka.faktura_nip}" class="jNIP jRequired">
			</div>
			
			<div id="odbiorcaWrapper">
				<h2>Odbiorca / płatnik</h2>
	        	<div style="margin-top: 10px">
		        	<a href="#" class="button kopiuj_dane_placowki">Kopiuj dane placówki</a>
		        	<a href="#" class="button kopiuj_dane_nabywcy">Kopiuj dane nabywcy</a>
				</div>

	        	<label for="form_nazwa_platnika" style="margin-top: 25px;">Nazwa odbiorcy/płatnika:</label>
				<input type="text" name="a_placowka[platnik_nazwa]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_nazwa_platnika" value="{$a_placowka.platnik_nazwa}" class="jAlfaNum">
			
				<label for="form_adres_platnika">Adres odbiorcy/płatnika:</label>
				<input type="text" name="a_placowka[platnik_adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres_platnika" value="{$a_placowka.platnik_adres}" class="jAlfaNum">
			
				<label for="form_kod_pocztowy_platnika">Kod pocztowy odbiorcy/płatnika:</label>
				<input type="text" name="a_placowka[platnik_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_platnika" value="{$a_placowka.platnik_kod_pocztowy}" class="jPostal">
			
				<label for="form_poczta_platnika">Poczta odbiorcy/płatnika:</label>
				<input type="text" name="a_placowka[platnik_poczta]" placeholder="np. Działdowo" id="form_poczta_platnika" value="{$a_placowka.platnik_poczta}" class="jAlfaNum">
       	 	</div>
       	 	
       	 	<h2>Adres wysyłki</h2>
        		
    		<div style="margin-top: 46px">
    			<a href="#" class="button kopiuj_dane_placowki_do_wysylki">Kopiuj dane placówki</a>
    		</div>
    		
    		<label for="form_nazwa_wysylki" style="margin-top: 25px;">Nazwa:</label>
			<input type="text" name="a_placowka[wysylka_nazwa]" placeholder="np. Urząd Miasta lub Szkoła Podstawowa itd." id="form_nazwa_wysylki" value="" class="jAlfaNum jRequired">
		
			<label for="form_adres_wysylka">Adres wysyłki:</label>
			<input type="text" name="a_placowka[wysylka_adres]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_adres_wysylka" value="" class="jAlfaNum jRequired">
		
			<label for="form_kod_pocztowy_wysylka">Kod pocztowy wysyłki:</label>
			<input type="text" name="a_placowka[wysylka_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_wysylka" value="" class="jPostal jRequired">

			<label for="form_poczta_wysylka">Poczta wysyłki:</label>
			<input type="text" name="a_placowka[wysylka_poczta]" placeholder="np. Działdowo" id="form_poczta_wysylka" value="" class="jAlfaNum jRequired">

    		<label for="form_uwagi_wysylka">Uwagi dla kuriera:</label>
			<input type="text" name="a_placowka[uwagi_dla_kuriera]" id="form_uwagi_wysylka" value="" class="">
			
	        
	        <input type="submit" value="<- Powrót" class="button prevStep left red">
	    	<input type="submit" value="Dalej ->" class="button nextStep right green">
        </fieldset>
        <fieldset class="step jValidate pracodawca_form formSection hidden">
        	<div class="left h1">{$a_migracja.title}</div>
	        <div class="right h1">Krok 5 z 6</div>
	        <div class="clear"></div>
	        <h2 class="red">Pracodawcy</h2>
	        {foreach $a_pracodawcy as $index=>$a_pracodawca}
	        	<div class="migracja_pracodawca left">
		        	<h3>Pracodawca {$index+1}:</h3>
		        	{if $a_pracodawcy|count>1}
		        		<!--<input type="button" class="red button migracja_usun_pracodawce" value="Usuń tego pracodawcę">-->
		        	{/if}
		        	
		        	<input type="hidden" name="a_pracodawca[{$a_pracodawca.id_pracodawcy}][id_pracodawcy]"  value="{$a_pracodawca.id_pracodawcy}">
					
		        	<!--
		        	<label for="form_nazwa_{$index+1}" class="marginTop20">Nazwa: (znaków <span>{strlen($a_pracodawca.nazwa)}</span> z 30)</label>
					<input type="text" name="a_pracodawca[{$a_pracodawca.id_pracodawcy}][nazwa]" id="form_nazwa_{$index+1}" value="{$a_pracodawca.linia1}" class="jRequired  jMinLength jMaxLength strtoupper" data-min-length="3" data-max-length="30">
					-->
					<label for="form_dane1_{$index+1}" class="marginTop20">Dane 1: (znaków <span>{strlen($a_pracodawca.linia1)}</span> z 30)</label>	
					<input type="text" name="a_pracodawca[{$a_pracodawca.id_pracodawcy}][dane1]" id="form_dane1_{$index+1}" value="{$a_pracodawca.linia1}" class="jMaxLength jAlfaNum strtoupper" data-max-length="30">
					
					<label for="form_dane2_{$index+1}">Dane 2: (znaków <span>{strlen($a_pracodawca.linia2)}</span> z 30)</label>	
					<input type="text" name="a_pracodawca[{$a_pracodawca.id_pracodawcy}][dane2]" id="form_dane2_{$index+1}" value="{$a_pracodawca.linia2}" class="jMaxLength jAlfaNum strtoupper" data-max-length="30">
					
					<label for="form_dane3_{$index+1}">Dane 3: (znaków <span>{strlen($a_pracodawca.linia3)}</span> z 30)</label>	
					<input type="text" name="a_pracodawca[{$a_pracodawca.id_pracodawcy}][dane3]" id="form_dane3_{$index+1}" value="{$a_pracodawca.linia3}" class="jMaxLength jAlfaNum strtoupper" data-max-length="30">
					
					<label for="form_dane4_{$index+1}">Dane 4: (znaków <span>{strlen($a_pracodawca.linia4)}</span> z 30)</label>	
					<input type="text" name="a_pracodawca[{$a_pracodawca.id_pracodawcy}][dane4]" id="form_dane4_{$index+1}" value="{$a_pracodawca.linia4}" class="jMaxLength jAlfaNum strtoupper" data-max-length="30">
					{if $index+1<$a_pracodawcy|count}<hr>{/if}
				</div>
	        {/foreach}
	        
        	<div class="clear"></div>
        	<input type="submit" value="<- Powrót" class="button prevStep left red">
	    	<input type="submit" value="Dalej ->" class="button nextStep right green">
    	</fieldset>
    	<fieldset class="step jValidate hidden">
        	<div class="left h1">{$a_migracja.title}</div>
	        <div class="right h1">Krok 6 z 6</div>
	        <div class="clear"></div>
	        <h2 class="left">Nauczyciele</h2>
	        <p class="right">Pomiń nauczycieli, którzy już nie pracują w placówce</p>
	        
	        {include file='legitymacje/import_nauczycieli.tpl'}
	        
	        <div class="marginTop20"></div>
	        
	        {if session::who('admin')}
	        	<input type="checkbox" name="czy_wyslac_powiadomienie" class="iCheck" id="czy_wyslac_powiadomienie"> <label for="czy_wyslac_powiadomienie">Czy wysłać powiadomienie?</label>
	        {/if}
	        
	        <input type="submit" value="<- Powrót" class="button prevStep left red">
	    	<input type="submit" value="Zapisz dane" class="button right green">
    	</fieldset>
	</form>
</section>