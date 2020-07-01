<form method="post" action="{$_base_url}index.php" class="jValidate login_form">
    
	<input type="hidden" name="module" value="users">
	<input type="hidden" name="action" value="resetuj_haslo">
	<fieldset>
		<p class="h2">Resetowanie hasła</p>
		
		<label for="email">Email:</label>
		<input name="email" id="email" type="text" value="{$email}" disabled>
		
		<label for="haslo">Nowe hasło</label>
		<input name="haslo" id="haslo" type="password" class="jEqual jMinLength jRequired" data-min-length="8"  data-equal-to="haslo_repeat" data-equal-txt="Hasła nie są identyczne">
		
		<label for="haslo_repeat">Powtórz nowe hasło</label>
		<input name="haslo_repeat" id="haslo_repeat" type="password" class="jEqual jMinLength jRequired" data-min-length="8" data-equal-to="haslo" data-equal-txt="Hasła nie są identyczne">
		
		<input value="Resetuj" type="submit">
	</fieldset>
</form>
