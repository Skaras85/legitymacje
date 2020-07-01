{assign var=czy_przenoszenie value=isset($czy_przenoszenie)}
{assign var=czy_subkonto value=isset($czy_subkonto)}
{assign var=konto_wewnetrzne value=isset($konto_wewnetrzne)}
{assign var=czy_edycja_subkonta value=isset($czy_edycja_subkonta)}

<section class="formSection">
	<form action="{$_base_url}" method="POST" class="jValidate">
		{if session::who('admin')}
		    <h1 class="left autoWidth"><a href="{$_base_url}">{if isset($a_user) && !$konto_wewnetrzne}Edycja danych konta{elseif isset($a_user) && $konto_wewnetrzne}Edycja konta wewnętrznego{elseif !isset($a_user) && $konto_wewnetrzne}Dodaj konto wewnętrzne{else}Rejestracja konta{/if}</a></h1>
		{else}
		    <h1 class="left autoWidth">
		    	{if !$czy_przenoszenie}
		    		{if isset($a_user)}Edytuj dane {$a_user.nazwa}{else}Rejestracja {if $czy_subkonto}sub{/if}konta{/if} <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="75"></h1>
	    		{else}
	    			Uzyskanie dostępu do konta
	    		{/if}
	    	</h1>
		{/if}
		
		{if $a_user.czy_haslo_losowe}
			<p class="communicat_info clear bold red">ZMIEŃ HASŁO TYMCZASOWE NA WŁASNE</p>
		{/if}
	
		<input type="submit" class="button green autoWidth buttonH1 left buttonIcon takButton" value="{if isset($a_user)}Zapisz{else}Dodaj{/if}">
		{if isset($a_user) && !$czy_ma_placowki}<a href="#" class="button red buttonH1 buttonIcon nieButton usunKonto" data-id="{$a_user.id_users}">Usuń konto</a>{/if}
		<div class="clear"></div>

		<input type="hidden" name="module" value="users">
		<input type="hidden" name="action" value="{if isset($a_user)}edytuj_dane{else}zarejestruj{/if}">
		{if $czy_subkonto}
			<input type="hidden" name="czy_subkonto" value="1">
			{if isset($a_user)}
		        <input type="hidden" name="a_user[id_users]" value="{$a_user.id_users}">
		    {/if}
		{/if}
		{if $konto_wewnetrzne}
			<input type="hidden" name="konto_wewnetrzne" value="1">
		{/if}
		{if $czy_edycja_subkonta}
			<input type="hidden" name="czy_edycja_subkonta" value="1">
		{/if}
	    
	    {if isset($a_user)}
	        <input type="hidden" name="a_user[email_old]" value="{$a_user.email}">
	    {/if}
	
	    <fieldset class="clearfix">
	    	<div class="left">
	        	
	        	<h2>Dane logowania</h2>
	        	
	        	<label for="form_email">Email:</label>
				<input type="text" name="a_user[email]" placeholder="Tu wpisz swój adres e-mail" id="form_email" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.email}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.email}{/if}" class="jRequired jEMail check_email" {if isset($a_user)}disabled{/if}>
				
				<label for="form_email_repeat">Powtórz email :</label>
		        <input type="text" name="a_user[email_repeat]" placeholder="Tu wpisz ponownie ten sam adres e-mail" id="form_email_repeat" data-equal-to="form_email" data-equal-txt="{lang::get('rejestracja-msg-emails')}" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.email}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.email_repeat}{/if}" class="jRequired jEMail jEqual" {if isset($a_user)}disabled{/if}>

				
				{if isset($a_user) && $a_user.haslo && !$czy_przenoszenie}
		            <label for="form_old_haslo" {if $a_user.czy_haslo_losowe}class="red bold"{/if}>{if $a_user.czy_haslo_losowe}Tymczasowe{else}Stare{/if} hasło:</label>
		            <input type="password" name="a_user[haslo_old]" id="form_old_haslo">
		        {/if}
				
				{if !$konto_wewnetrzne && !$czy_subkonto}
					<div class="password_wrapper">
			        	<label for="form_haslo">{if isset($a_user) && !$czy_przenoszenie}Nowe hasło{else}Hasło{/if}:</label>
			        	<input type="password" name="a_user[haslo]" placeholder="Tu wpisz swoje hasło, min. 8 dowolnych znaków" id="form_haslo" class="jEqual jMinLength {if !isset($a_user) && !session::who('admin')}jRequired{/if}" data-min-length="8" data-equal-to="form_powtorz_haslo" data-equal-txt="Hasła nie są identyczne">
			        	<img src="images/core/eye_icon.png" class="show_password">
			        	<div id="pass_strength" class="clear"><div></div></div>
			        </div>
			        
			        <div class="password_wrapper clearfix">
			        	<label for="form_powtorz_haslo">{if isset($a_user) && !$czy_przenoszenie}Powtórz nowe hasło{else}Powtórz hasło{/if}:</label>
			        	<input type="password" name="a_user[haslo_powtorzone]" placeholder="Tu ponownie wpisz swoje hasło" id="form_powtorz_haslo" class="{if !isset($a_user) && !session::who('admin')}jRequired{/if} jEqual"  data-equal-to="form_haslo" data-equal-txt="Hasła nie są identyczne">
		        		<img src="images/core/eye_icon.png" class="show_password">
		        	</div>
	        	{/if}
	        	
        	</div>
			<div class="left">
				<h2>Dane konta</h2>
				
				<label for="form_imie">Imię:</label>
				<input type="text" name="a_user[imie]" placeholder="Tu wpisz swoje imię" id="form_imie" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.imie}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.imie}{/if}" class="jRequired jAlfaNum" {if isset($a_user)}disabled{/if}>
				
				<label for="form_nazwisko">Nazwisko:</label>
				<input type="text" name="a_user[nazwisko]" placeholder="Tu wpisz swoje nazwisko" id="form_nazwisko" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.nazwisko}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.nazwisko}{/if}" class="jRequired jAlfaNum" {if isset($a_user)}disabled{/if}>
				
				<label for="form_telefon_kontaktowy">Telefon:</label>
				<input type="text" name="a_user[telefon]" placeholder="Tu wpisz swój numer telefonu" id="form_telefon_kontaktowy" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.telefon}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.telefon}{/if}" class="jRequired jAlfaNum">
				
				<p class="smallFont">Numer telefonu jest niezbędny do przyśpieszenia i ułatwienia kontaktu Usługodawcy z Usługobiorcą, w celu optymalizacji i przyśpieszenia realizacji zamówień składanych w Systemie.</p>
				
				{if !$czy_subkonto && !isset($a_user) && !$czy_edycja_subkonta}
					{if isset($a_user)}
						<label for="form_typ_konta">Typ konta:</label>
						<select id="form_typ_konta" name="a_user[typ]" class="jRequired">
							<option value="placowka" {if isset($a_user) && $a_user.typ=='placowka'}selected{/if}>Placówka oświaty</option>
							<option value="agencja" {if isset($a_user) && $a_user.typ=='agencja'}selected{/if}>Agencja</option>
						</select>
					{else}
						<input type="hidden" name="a_user[typ]" value="placowka">
					{/if}
				{else}
					<input type="hidden" name="a_user[typ]" value="{$a_user.typ}">
				{/if}
				
				{if $czy_subkonto}
					<h2>Dostęp</h2>
				
					{if $a_placowki}
						<ul>
							{foreach $a_placowki as $a_placowka}
								<li><input type="checkbox" class="iCheck" name="a_placowki[{$a_placowka.id_placowki}]" id="placowka_{$a_placowka.id_placowki}" {if $a_placowki_usera}{foreach $a_placowki_usera as $a_placowka_usera}{if $a_placowka_usera.id_placowki==$a_placowka.id_placowki}checked{/if}{/foreach}{else}{if !isset($a_user)}checked{/if}{/if}> <label for="placowka_{$a_placowka.id_placowki}" class="inline">{$a_placowka.nazwa_skrocona}</label></li>
							{/foreach}
						</ul>
					{/if}
				{/if}
				
				 <div class="{if $czy_subkonto || $czy_edycja_subkonta || isset($a_user) && $a_user.typ=='placowka' || !isset($a_user)}hidden{/if}" id="dane_agencji">
	        	
		        	<h2>Dane agencji</h2>
		        	
			        <label for="form_nazwa">Nazwa:</label>
					<input type="text" name="a_user[nazwa]" id="form_nazwa" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.nazwa}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.nazwa}{/if}" class="jAlfaNum {if isset($a_user) && $a_user.typ=='agencja'}jRequired{/if}" {if isset($a_user) && $a_user.typ=='agencja'}disabled{/if}>
				
					<label for="form_poczta">Miasto:</label>
					<input type="text" name="a_user[miasto]" id="form_poczta" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.miasto}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.miasto}{/if}" class="jAlfaNum {if isset($a_user) && $a_user.typ=='agencja'}jRequired{/if}" {if isset($a_user) && $a_user.typ=='agencja'}disabled{/if}>
				
				
					<label for="form_adres">Ulica i numer:</label>
					<input type="text" name="a_user[ulica]" id="form_adres" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.ulica}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.ulica}{/if}" class="jAlfaNum {if isset($a_user) && $a_user.typ=='agencja'}jRequired{/if}" {if isset($a_user) && $a_user.typ=='agencja'}disabled{/if}>
				
					<label for="form_kod_pocztowy">Kod pocztowy:</label>
					<input type="text" name="a_user[kod_pocztowy]" id="form_kod_pocztowy" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.kod_pocztowy}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.kod_pocztowy}{/if}" class="jPostal {if isset($a_user) && $a_user.typ=='agencja'}jRequired{/if}" {if isset($a_user) && $a_user.typ=='agencja'}disabled{/if}>
					
					<label for="form_nip">NIP:</label>
					<input type="text" name="a_user[nip]" id="form_nip" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.nip}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.nip}{/if}" class="jNIP {if isset($a_user) && $a_user.typ=='agencja'}jRequired{/if}" {if isset($a_user) && $a_user.typ=='agencja'}disabled{/if}>
	
					<h2 class="marginTop30">Odbrioca / płatnik</h2>
					<input type="button" value="Kopiuj dane agencji" class="button kopiuj_dane_placowki">

		        	<label for="form_nazwa_platnika" style="margin-top: 25px;">Nazwa odbiorcy/płatnika:</label>
					<input type="text" name="a_user[platnik_nazwa]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_nazwa_platnika" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.platnik_nazwa}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.platnik_nazwa}{/if}" class="jAlfaNum">
				
					<label for="form_adres_platnika">Adres odbiorcy/płatnika:</label>
					<input type="text" name="a_user[platnik_adres]" placeholder="np. ul. Stefana Żeromskiego 6" id="form_adres_platnika" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.platnik_adres}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.platnik_adres}{/if}" class="jAlfaNum">
				
					<label for="form_kod_pocztowy_platnika">Kod pocztowy odbiorcy/płatnika:</label>
					<input type="text" name="a_user[platnik_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_platnika" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.platnik_kod_pocztowy}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.platnik_kod_pocztowy}{/if}" class="jPostal">
				
					<label for="form_poczta_platnika">Poczta odbiorcy/płatnika:</label>
					<input type="text" name="a_user[platnik_poczta]" placeholder="np. Działdowo" id="form_poczta_platnika" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.platnik_poczta}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.platnik_poczta}{/if}" class="jAlfaNum">
					
					<h2 class="marginTop30">Adres wysyłki</h2>
					<input type="button" value="Kopiuj dane agencji" class="button kopiuj_dane_placowki_do_wysylki">
	
	        		<label for="form_nazwa_wysylki" class="marginTop20">Nazwa:</label>
					<input type="text" name="a_user[wysylka_nazwa]" placeholder="np. Urząd Miasta lub Szkoła Podstawowa itd." id="form_nazwa_wysylki" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.wysylka_nazwa}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.wysylka_nazwa}{/if}" class="jAlfaNum">
				
					<label for="form_adres_wysylka">Adres wysyłki:</label>
					<input type="text" name="a_user[wysylka_adres]" placeholder="np. Szkoła Podstawowa nr 12 im. Stefana Żeromskiego" id="form_adres_wysylka" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.wysylka_adres}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.wysylka_adres}{/if}" class="jAlfaNum">
				
					<label for="form_poczta_wysylka">Poczta wysyłki:</label>
					<input type="text" name="a_user[wysylka_poczta]" placeholder="np. Działdowo" id="form_poczta_wysylka" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.wysylka_poczta}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.wysylka_poczta}{/if}" class="jAlfaNum">
				
				
					<label for="form_kod_pocztowy_wysylka">Kod pocztowy wysyłki:</label>
					<input type="text" name="a_user[wysylka_kod_pocztowy]" placeholder="np. 13-200" id="form_kod_pocztowy_wysylka" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.wysylka_kod_pocztowy}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.wysylka_kod_pocztowy}{/if}" class="jPostal">
	
	        		<label for="form_uwagi_wysylka">Telefon i uwagi dla kuriera:</label>
					<input type="text" name="a_user[uwagi_dla_kuriera]" placeholder="np. XXX XXX XXX , Kadry" id="form_uwagi_wysylka" value="{if isset($a_user) && !isset($smarty.session.form.a_user)}{$a_user.uwagi_dla_kuriera}{/if}{if isset($smarty.session.form.a_user)}{$smarty.session.form.a_user.uwagi_dla_kuriera}{/if}" class="">
					
	
				</div>

	        	
	        </div>
	        
	       <div class="left">
	       		{if !$czy_subkonto}
		       		{if (!isset($a_user) && !$konto_wewnetrzne || $czy_przenoszenie || isset($a_user) && !$a_user.czy_aktywny)}
						<input type="checkbox" name="regulamin" id="regulamin" class="iCheck jChecked"> <label for="regulamin">Akceptuję <a href="{$_base_url}strony/regulamin,11" class="modal">regulamin</a></label>
						<div></div>
						<input type="checkbox" name="polityka_prywatnosci" id="polityka_prywatnosci" class="iCheck jChecked"> <label for="polityka_prywatnosci">Akceptuję <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">politykę prywatności</a></label>
					{/if}

					<div class="marginTop20">
		            	<input type="checkbox" name="a_user[czy_newsletter]" id="newsletter" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter" {if isset($a_user) && !isset($smarty.session.form.a_user) && $a_user.czy_newsletter || !isset($a_user) && isset($smarty.session.form.a_user) && $smarty.session.form.a_user.czy_newsletter}checked{/if}> <label for="newsletter"  class="smallFont">( Opcjonalnie - nie jest wymagane do założenia konta )
						Wyrażam zgodę na otrzymywanie od firmy  Grupa LOCA Sp. Z o.o. informacji handlowych drogą elektroniczną, zgodnie z art. 10 ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną (t.j. Dz.U. z 2017 r., poz. 1219 z późn. zm.)</label>
						<input type="checkbox" name="a_user[czy_newsletter2]" id="newsletter2" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter" {if isset($a_user) && !isset($smarty.session.form.a_user) && $a_user.czy_newsletter || !isset($a_user) && isset($smarty.session.form.a_user) && $smarty.session.form.a_user.czy_newsletter2}checked{/if}> <label for="newsletter2" class="smallFont">(Opcjonalnie - nie jest wymagane do założenia konta)
						Wyrażam zgodę na przetwarzanie przez firmę Grupa LOCA Sp. Z o.o.  moich danych osobowych na potrzeby otrzymywania newslettera. Oświadczam, że zapoznałem/am się z obowiązkiem informacyjnym administratora danych, który administrator spełnia w II. 13 dokumentu „Polityka prywatności” dostępnego na <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">{$_base_url}strony/polityka-prywatnosci,22</a>.</label>
					
					</div>

					{if !isset($a_user) && !$konto_wewnetrzne || $czy_przenoszenie}	
						<div id="captchWrapper">
			                <div class="g-recaptcha" data-sitekey="6LdhOFcUAAAAAPULido3DWdWhm0_6MnLnK-BIVew"></div>
			            </div>
		            {/if}
	            {/if}
	       </div>
		
		</fieldset>
	</form>
</section>
