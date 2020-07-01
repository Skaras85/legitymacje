<section>
	<h1>Formularz umowy</h1>
	<form id="form" method="post" action="{$_base_url}" class="jValidate">
		<input type="hidden" name="action" value="{if !isset($a_umowa) || session::who('admin') && isset($a_umowa.id_umowy)}formularz_umowy{else}podglad_umowy{/if}">
		<input type="hidden" name="module" value="umowy">
		{if isset($a_umowa.id_umowy)}<input name="a_umowa[id_umowy]" value="{$a_umowa.id_umowy}" type="hidden">{/if}
		
		
		<div class="{if isset($a_umowa.id_umowy) || isset($a_umowa.id_umowy_naglowki)}hidden{/if}">
			<label for="form_umowy_typy">Typ umowy</label>
			<select name="a_umowa[id_umowy_typy]" id="form_umowy_typy" class="jRequired">
				<option value="0">Wybierz</option>
				{foreach $a_umowy_typy as  $a_typ_umowy}
					<option value="{$a_typ_umowy.id_umowy_typy}" {if !isset($smarty.session.a_umowa) && isset($a_umowa) && $a_umowa.id_umowy_typy==$a_typ_umowy.id_umowy_typy || isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_typy==$a_typ_umowy.id_umowy_typy}selected{/if}>{$a_typ_umowy.nazwa}</option>
				{/foreach}
			</select>
			
			<label for="form_umowy_naglowek">Nagłówek</label>
			<select name="a_umowa[id_umowy_naglowki]" id="form_umowy_naglowek" class="jRequired">
				<option value="0">Wybierz</option>
				{foreach $a_umowy_naglowki as  $a_naglowek}
					<option value="{$a_naglowek.id_umowy_naglowki}" {if !isset($smarty.session.a_umowa) && isset($a_umowa) && $a_umowa.id_umowy_naglowki==$a_naglowek.id_umowy_naglowki || isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_naglowki==$a_naglowek.id_umowy_naglowki}selected{/if}>{$a_naglowek.nazwa}</option>
				{/foreach}
			</select>
		</div>
		
		{if isset($a_umowa) && $a_umowa.id_umowy_naglowki==1 || isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_naglowki==1}
			<label for="form_umowy_nazwa">Nazwa</label>
			<input type="text" class="jRequired" name="a_umowa[nazwa]" id="form_umowy_nazwa" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nazwa)}{$a_umowa.nazwa}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nazwa}{/if}">
		
			<label for="form_umowy_kod">Kod</label>
			<input type="text" class="jRequired jPostal" name="a_umowa[kod_pocztowy]" id="form_umowy_kod" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.kod_pocztowy)}{$a_umowa.kod_pocztowy}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.kod_pocztowy}{/if}">
		
			<label for="form_umowy_miasto">Miasto</label>
			<input type="text" class="jRequired" name="a_umowa[miasto]" id="form_umowy_miasto" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.miasto)}{$a_umowa.miasto}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.miasto}{/if}">
		
			<label for="form_umowy_adres">Adres</label>
			<input type="text" class="jRequired" name="a_umowa[adres]" id="form_umowy_adres" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.adres)}{$a_umowa.adres}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.adres}{/if}">
		
			<label for="form_umowy_nip">NIP</label>
			<input type="text" class="jRequired jNip" name="a_umowa[nip]" id="form_umowy_nip" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nip)}{$a_umowa.nip}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nip}{/if}">
		
			<p class="noMargin h2">Reprezentowanym przez:</p>
			
			<label for="form_umowy_imie">Imię</label>
			<input type="text" class="jRequired" name="a_umowa[imie]" id="form_umowy_imie" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.imie)}{$a_umowa.imie}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.imie}{/if}">
		
			<label for="form_umowy_nazwisko">Nazwisko</label>
			<input type="text" class="jRequired" name="a_umowa[nazwisko]" id="form_umowy_nazwisko" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nazwisko)}{$a_umowa.nazwisko}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nazwisko}{/if}">
		
			<p class="noMargin h2">Reprezentującego na podstawie pełnomocnictwa:</p>
			
			<label for="form_umowy_nr_pelnomocnistwa">Numer pełnomocnictwa</label>
			<input type="text" class="jRequired" name="a_umowa[nr_pelnomocnistwa]" id="form_umowy_nr_pelnomocnistwa" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nr_pelnomocnistwa)}{$a_umowa.nr_pelnomocnistwa}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nr_pelnomocnistwa}{/if}">
		
			<p class="noMargin h2">Działającemu na rzecz  Placówki oświaty:</p>
			
			<label for="form_umowy_placowka_nazwa">Nazwa placówki</label>
			<input type="text" class="jRequired" name="a_umowa[placowka_nazwa]" id="form_umowy_nazwa_placowki" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nazwa_placowki)}{$a_umowa.placowka_nazwa}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_nazwa}{/if}">
		
			<label for="form_umowy_placowka_kod">Kod pocztowy</label>
			<input type="text" class="jRequired jPostal" name="a_umowa[placowka_kod_pocztowy]" id="form_umowy_placowka_kod" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_kod_pocztowy)}{$a_umowa.placowka_kod_pocztowy}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_kod_pocztowy}{/if}">
		
			<label for="form_umowy_placowka_miasto">Miasto</label>
			<input type="text" class="jRequired" name="a_umowa[placowka_miasto]" id="form_umowy_placowka_miasto" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_miasto)}{$a_umowa.placowka_miasto}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_miasto}{/if}">
		
			<label for="form_umowy_placowka_adres">Adres</label>
			<input type="text" class="jRequired" name="a_umowa[placowka_adres]" id="form_umowy_placowka_adres" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_adres)}{$a_umowa.placowka_adres}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_adres}{/if}">
		
			<label for="form_umowy_placowka_regon">REGON</label>
			<input type="text" class="jRequired jRegon" name="a_umowa[placowka_regon]" id="form_umowy_placowka_regon" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.placowka_regon)}{$a_umowa.placowka_regon}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.placowka_regon}{/if}">
		
		{/if}
		
		{if !isset($a_umowa)}
       		<input type="submit" value="Krok 2">
        {else}
        	<a href="{$_base_url}umowy/formularz_umowy" class="button"><- Powrót</a>
        	<input type="submit" value="Generuj" name="submit">
        {/if}
     </form>  
</section>