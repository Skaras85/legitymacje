<section class="formSection">

    <h1>{if isset($a_przesylka)}Edytuj dane przesyłki {$a_przesylka.numer_przesylki}{else}Dodaj przesyłkę{/if}</h1>

	
	
	<form action="{$_base_url}" method="POST" class="jValidate clearfix">
		<input type="hidden" name="module" value="przesylki">
		<input type="hidden" name="action" value="zapisz_przesylke">
		<input type="hidden" name="a_przesylka[id_placowki]" value="{$id_placowki}">
	    
	    {if isset($a_przesylka)}
	        <input type="hidden" name="a_przesylka[id_przesylki]" value="{$a_przesylka.id_przesylki}">
	    {/if}
	    
	    <fieldset class=" left">
	    	
	    	<label for="rodzaj">Rodzaj:</label>
	    	<select name="a_przesylka[rodzaj]" id="rodzaj">
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='List zwykły ekonomiczny'}selected{/if}>List zwykły ekonomiczny</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='List zwykły priorytet'}selected{/if}>List zwykły priorytet</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='List polecony ekonomiczny'}selected{/if}>List polecony ekonomiczny</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='List polecony priorytet'}selected{/if}>List polecony priorytet</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='Kurier'}selected{/if}>Kurier</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='Osobiście'}selected{/if}>Osobiście</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='FAX'}selected{/if}>FAX</option>
	    		<option {if isset($a_przesylka) && $a_przesylka.rodzaj=='Email'}selected{/if}>Email</option>
	    	</select>
	    	
		    <label for="numer_listu">Numer listu:</label>
			<input type="text" name="a_przesylka[numer_listu]" id="numer_listu"  value="{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.numer_listu}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.numer_listu}{/if}" class="jRequired">
			
			<label>Data nadania</label>
			<div class="data_wrapper">
				<input type="text" name="a_przesylka[rok]" id="rok" placeholder="YYYY" value="{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.data_nadania|substr:0:4}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.rok}{/if}{if !isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$przesylki_rok}{/if}" class="jRequired jNumber">
				<input type="text" name="a_przesylka[miesiac]" id="miesiac" placeholder="MM" value="{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.data_nadania|substr:5:2}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.miesiac}{/if}{if !isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$przesylki_miesiac}{/if}" class="jRequired jNumber">
				<input type="text" name="a_przesylka[dzien]" id="dzien" placeholder="DD" value="{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.data_nadania|substr:8:2}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.dzien}{/if}" class="jRequired jNumber">
			</div>
			
			<label for="data_otrzymania">Data otrzymania:</label>
			<input type="text" name="a_przesylka[data_otrzymania]" id="data_otrzymania"  value="{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.data_otrzymania}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.data_otrzymania}{/if}{if !isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$przesylki_data_otrzymania}{/if}" class="jRequired jData">
		
			<label for="liczba_legitymacji">Ilość zamówionych legitymacji:</label>
			<input type="text" name="a_przesylka[liczba_legitymacji]" id="liczba_legitymacji"  value="{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.liczba_legitymacji}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.liczba_legitymacji}{/if}" class="jRequired jNumber">
			
		</fieldset>
		<fieldset class="left">
			
			
			<label>Czy kompletne</label>
			<input type="radio" id="czy_kompletne_tak" name="a_przesylka[czy_kompletne]" value="tak" class="iCheck" {if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka) && $a_przesylka.czy_kompletne==1 || isset($smarty.session.form.a_przesylka) && $smarty.session.form.a_przesylka.czy_kompletne==1 || !isset($a_przesylka)}checked{/if}> <label for="czy_kompletne_tak">Tak</label>
			<input type="radio" id="czy_kompletne_nie" name="a_przesylka[czy_kompletne]" value="nie" class="iCheck" {if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka) && $a_przesylka.czy_kompletne==0 || isset($smarty.session.form.a_przesylka) && $smarty.session.form.a_przesylka.czy_kompletne==0}checked{/if}> <label for="czy_kompletne_nie">Nie</label>
				
			<label for="uwagi">Uwagi:</label>
			<textarea name="a_przesylka[uwagi]" id="uwagi">{if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka)}{$a_przesylka.uwagi}{/if}{if isset($smarty.session.form.a_przesylka)}{$smarty.session.form.a_przesylka.uwagi}{/if}</textarea>
			
			<label for="numer_zamowienia">Numer zamówienia:</label>
			<select name="a_przesylka[id_zamowienia]" id="numer_zamowienia">
				<option value="0"></option>
				{if $a_zamowienia}
					{foreach $a_zamowienia as $a_zamowienie}
						<option value="{$a_zamowienie.id_zamowienia}" {if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka) && $a_zamowienie.id_zamowienia==$a_przesylka.id_zamowienia || isset($smarty.session.form.a_przesylka) && $smarty.session.form.a_przesylka.id_zamowienia==$a_zamowienie.id_zamowienia}selected{/if}>{$a_zamowienie.numer_zamowienia}</option>
					{/foreach}
				{/if}
			</select>
			
			<input type="checkbox" name="a_przesylka[czy_wyslac_maila]" id="czy_wyslac_maila" class="iCheck" {if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka) && $a_przesylka.czy_mail==1 || isset($smarty.session.form.a_przesylka) && $smarty.session.form.a_przesylka.czy_wyslac_maila==1 || !isset($a_przesylka)}checked{/if}> <label for="czy_wyslac_maila">Czy wysłać maila?</label>
			<label for="adresat">Adresat:</label>
			<select name="a_przesylka[id_mail_adresat]" id="adresat">
				<option value="{$a_placowka.id_users}">{$a_placowka.nazwisko} {$a_placowka.imie}</option>
				{if $a_adresaci}
					{foreach $a_adresaci as $a_adresat}
						<option value="{$a_adresat.id_users}" {if isset($a_przesylka) && !isset($smarty.session.form.a_przesylka) && $a_adresat.id_users==$a_przesylka.id_mail_adresat || $smarty.session.form.a_przesylka && $smarty.session.form.a_przesylka.id_mail_adresat==$a_adresat.id_users}selected{/if}>{$a_adresat.nazwisko} {$a_adresat.imie}</option>
					{/foreach}
				{/if}
			</select>
				
			<input type="submit" value="Zapisz">
		
		</fieldset>
	</form>
</section>
