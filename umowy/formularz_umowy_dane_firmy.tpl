{if $a_dane_nabywcy}
	<a href="#" class="button umowy_kopiuj_dane_nabywcy">Kopiuj dane nabywcy</a>
{/if}

<div id="umowa_dane_nabywcy">
	<label for="form_umowy_nazwa" class="marginTop20">Nazwa</label>
	<input type="text" class="jRequired" name="a_umowa[nazwa]" data-dane="{$a_dane_nabywcy.0.nabywca_nazwa}" id="form_umowy_nazwa" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nazwa)}{$a_umowa.nazwa}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nazwa}{/if}">
	
	<label for="form_umowy_kod">Kod</label>
	<input type="text" class="jRequired jPostal" name="a_umowa[kod_pocztowy]" data-dane="{$a_dane_nabywcy.0.nabywca_kod_pocztowy}" id="form_umowy_kod" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.kod_pocztowy)}{$a_umowa.kod_pocztowy}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.kod_pocztowy}{/if}">
	
	<label for="form_umowy_miasto">Miasto</label>
	<input type="text" class="jRequired" name="a_umowa[miasto]" data-dane="{$a_dane_nabywcy.0.nabywca_poczta}" id="form_umowy_miasto" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.miasto)}{$a_umowa.miasto}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.miasto}{/if}">
	
	<label for="form_umowy_adres">Adres</label>
	<input type="text" class="jRequired" name="a_umowa[adres]" data-dane="{$a_dane_nabywcy.0.nabywca_adres}" id="form_umowy_adres" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.adres)}{$a_umowa.adres}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.adres}{/if}">
	
	<label for="form_umowy_nip">NIP</label>
	<input type="text" class="jRequired jNip" name="a_umowa[nip]" data-dane="{$a_dane_nabywcy.0.nabywca_nip}" id="form_umowy_nip" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nip)}{$a_umowa.nip}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nip}{/if}">

	<label for="form_umowy_email">E-mail do powiadomień dla umowy powierzenia danych osobowych</label>
	<input type="text" class="jRequired jEMail" name="a_umowa[email]" id="form_umowy_email" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.email)}{$a_umowa.email}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.email}{/if}">

	<label for="form_umowy_email_naruszenia">E-mail do powiadomień ws. naruszeń przetwarzania danych osobowych</label>
	<input type="text" class="jRequired jEMail" name="a_umowa[email_naruszenia]" id="form_umowy_email_naruszenia" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.email_naruszenia)}{$a_umowa.email_naruszenia}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.email_naruszenia}{/if}">

</div>