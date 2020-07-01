<div class="marginTop20"></div>

<a href="#" class="button umowy_kopiuj_dane_placowki">Kopiuj dane placówki</a>
			
<div id="umowa_dane_placowki">
	<label for="form_umowy_placowka_nazwa" class="marginTop20">Nazwa placówki</label>
	<input type="text" class="jRequired" data-dane="{$a_placowka.nazwa}" name="a_umowa[placowka_nazwa]" id="form_umowy_nazwa_placowki" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nazwa_placowki)}{$a_umowa.placowka_nazwa}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_nazwa}{/if}">

	<label for="form_umowy_placowka_kod">Kod pocztowy</label>
	<input type="text" class="jRequired jPostal" data-dane="{$a_placowka.kod_pocztowy}" name="a_umowa[placowka_kod_pocztowy]" id="form_umowy_placowka_kod" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_kod_pocztowy)}{$a_umowa.placowka_kod_pocztowy}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_kod_pocztowy}{/if}">

	<label for="form_umowy_placowka_miasto">Miasto</label>
	<input type="text" class="jRequired" data-dane="{$a_placowka.poczta}" name="a_umowa[placowka_miasto]" id="form_umowy_placowka_miasto" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_miasto)}{$a_umowa.placowka_miasto}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_miasto}{/if}">

	<label for="form_umowy_placowka_adres">Adres</label>
	<input type="text" class="jRequired" data-dane="{$a_placowka.adres}" name="a_umowa[placowka_adres]" id="form_umowy_placowka_adres" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_adres)}{$a_umowa.placowka_adres}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_adres}{/if}">

	<label for="form_umowy_placowka_regon">REGON</label>
	<input type="text" class="jRequired jRegon" data-dane="{$a_placowka.regon}" name="a_umowa[placowka_regon]" id="form_umowy_placowka_regon" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_regon)}{$a_umowa.placowka_regon}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_regon}{/if}">
</div>