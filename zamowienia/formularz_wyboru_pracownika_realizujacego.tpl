<h1>Wybierz pracowinka realizującego</h1>

{if $a_pracownicy}
	<select id="id_pracownika">
		<option value="{session::get_id()}">{session::get_user('nazwisko')} {session::get_user('imie')}</option>
		{foreach $a_pracownicy as $a_pracownik}
			{if $a_pracownik.id_users!=session::get_id()}
				<option value="{$a_pracownik.id_users}">{$a_pracownik.nazwisko} {$a_pracownik.imie}</option>
			{/if}
		{/foreach}
	</select>
	<input type="submit" value="Wybierz" class="przypisz_pracownika_realizujacego_zamowienia">
{/if}
