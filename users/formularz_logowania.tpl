<section class="center">
	<h1>System zamawiania legitymacji dla oświaty</h1>
	<div class="left terminy_zamawiania_wrapper">
		<h2>{$a_terminy.title}</h2>
		{$a_terminy.text}
	</div>
	
	
	<form method="post" action="{$_base_url}" class="jValidate center_form rejestracja_form">
		<input type="hidden" name="module" value="users">
		<input type="hidden" name="action" value="zaloguj">
		<fieldset>
			<label for="email">Adres logowania e-mail:</label>
			<input name="a_user[email]" id="email" autofocus="autofocus" type="text" class="jRequired">
			<label for="haslo">Hasło </label>
			<input class="jRequired" name="a_user[haslo]" id="haslo" type="password" class="jRequired">
			
			<!--<input type="checkbox" id="zapamietaj" name="a_user[czy_pamietac]" class="iCheck"> <label for="zapamietaj">Zapamiętaj mnie</label>-->
			
			<input value="Zaloguj" name="submit" type="submit">
			<!--<p class="text">{lang::get('logowanie-brak-konta')} <a href="users/formularz-uzytkownika">{lang::get('userbar-zarejestruj-sie')}</a></p>-->
		</fieldset>
	</form>
	
	<form method="post" action="{$_base_url}" class="center_form">
		<input type="hidden" name="module" value="users">
		<input type="hidden" name="action" value="formularz_uzytkownika">
		<fieldset>
			<input type="submit" value="Nowe konto" class="green">
		</fieldset>
	</form>
	<form method="post" action="{$_base_url}" class="jValidate center_form">
	    <fieldset>
	        <p class="jMore text" data-jmore-txt="- Nie pamiętam hasła">+ Nie pamiętam hasła</p>
	        <div class="hidden">
	            <input type="hidden" name="module" value="users">
	            <input type="hidden" name="action" value="wyslij_nowe_haslo">
	            <p class="communicat_info">Nie pamiętasz hasła? Nic straconego! Podaj adres email, na który się logujesz, a nowe hasło wyślemy Ci na pocztę.</p>
	            <label for="email2">E-mail:</label>
	            <input name="email" id="email2" autofocus="autofocus" type="text" class="jEMail">
	            <input value="Wyślij" type="submit">
	        </div>
	    </fieldset>    
	</form>

</section>
