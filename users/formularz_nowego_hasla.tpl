<h2>Formularz </h2>

<form method="post" action="{$_base_url}index.php" class="jValidate">
	<input type="hidden" name="module" value="users">
		<input type="hidden" name="action" value="zaloguj">
	<fieldset>
		<label for="login">Login:</label>
		<input name="a_user[login]" id="login" autofocus="autofocus" type="text" class="jRequired jAlfanumStrict">
		<label for="haslo">Hasło</label>
		<input class="jRequired" name="a_user[haslo]" id="haslo" type="password" class="jRequired">
		<input value="Zaloguj" type="submit">
		<a href="{$_base_url}users/formularz-nowego-hasla">Nie pamiętam hasła</a>
	</fieldset>
</form>