		
<label for="form_umowy_imie">Imię</label>
<input type="text" class="jRequired" name="a_umowa[imie]" id="form_umowy_imie" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.imie)}{$a_umowa.imie}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.imie}{/if}">

<label for="form_umowy_nazwisko">Nazwisko</label>
<input type="text" class="jRequired" name="a_umowa[nazwisko]" id="form_umowy_nazwisko" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nazwisko)}{$a_umowa.nazwisko}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nazwisko}{/if}">
		