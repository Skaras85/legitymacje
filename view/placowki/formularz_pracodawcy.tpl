<section class="">

    <h1>{if isset($a_pracodawca)}Edytuj dane {$a_pracodawca.nazwa}{else}Dodaj {if $czy_szkoly}nazwę i adres szkoły na potrzeby druku e-legitymacji{else}nazwę pracodawcy na potrzeby druku legitymacji nauczyciela{/if}{/if}</h1>
    
   
	<form action="{$_base_url}" method="POST" class="jValidate pracodawca_form">
		<input type="hidden" name="module" value="placowki">
		<input type="hidden" name="action" value="zapisz_pracodawce">
		{if $czy_szkoly}<input type="hidden" name="a_pracodawca[czy_szkoly]" id="czy_szkoly" value="1">{/if}
		{if $czy_dodawanie_placowki}<input type="hidden" name="a_pracodawca[czy_dodawanie_placowki]" value="1">{/if}

		<input type="submit" value="Zapisz" class="green button buttonIcon takButton autoWidth">

		{if $czy_szkoly && $czy_dodawanie_placowki}
			<!--<input type="submit" value="Przejdź dalej" name="przejdz_dalej" class="button buttonIcon dalejButton">-->
			<!--
			<a href="{$_base_url}placowki/formularz_pracodawcy/czy_dodawanie_placowki/1" class="button buttonIcon dalejButton">Pomiń</a>
			-->
		{/if}
		
		{if !$czy_szkoly && $czy_dodawanie_placowki}
			<!--
			<a href="{$_base_url}umowy/lista-umow" class="button buttonIcon dalejButton">Przejdź do umów</a>
			--->
		{/if}
		
		{if isset($id_karty)}
	    	<p class="communicat_info">Zanim zaczniesz dodawać {if $czy_szkoly}uczniów{else}nauczycieli{/if}, zdefiniuj nazwę {if $czy_szkoly}szkoły{else}pracodawcy{/if} która będzie nadrukowana na legitymacji. Rozporządzenie przewiduje na ten cel 4 wiersze do 30 znaków każdy. Rozporządzenie nie określa jednak jakie dane należy wpisać, sugerujemy więc wpisanie nazwy i adresu placówki.</p>
			<input type="hidden" name="id_karty" value="{$id_karty}">
		{/if}
	    
	    {if isset($a_pracodawca)}
	        <input type="hidden" name="a_pracodawca[id_pracodawcy]" value="{$a_pracodawca.id_pracodawcy}">
	    {/if}
	    
	    <fieldset class="clearfix">
	    	<div class="hidden">
			    <label for="form_nazwa">Nazwa na potrzeby wewnętrzne programu: (znaków <span>{if isset($a_pracodawca)}strlen($a_pracodawca.nazwa)}{else}0{/if}</span> z 30)</label>
				<input type="text" name="a_pracodawca[nazwa]" id="form_nazwa" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.nazwa}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.nazwa}{/if}" class="jRequiredd  jMinLength jMaxLength strtoupper" data-min-length="3" data-max-length="30">
			</div>
			
			<h2>Treść drukowana na legitymacji:</h2>
			
			<label for="form_dane1">Dane 1: (znaków <span>{if isset($a_pracodawca)}{strlen($a_pracodawca.dane1)}{else}0{/if}</span> z {if $czy_szkoly}45{else}30{/if})</label>	
			<input type="text" name="a_pracodawca[dane1]" id="form_dane1" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.dane1}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.dane1}{/if}" class="jMaxLength jMinLength jRequired jAlfaNum strtoupper copySource" data-copy-to="#form_nazwa" data-max-length="{if $czy_szkoly}45{else}30{/if}" data-min-length="3">
			
			<label for="form_dane2">Dane 2: (znaków <span>{if isset($a_pracodawca)}{strlen($a_pracodawca.dane2)}{else}0{/if}</span> z {if $czy_szkoly}45{else}30{/if})</label>	
			<input type="text" name="a_pracodawca[dane2]" id="form_dane2" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.dane2}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.dane2}{/if}" class="jMaxLength jAlfaNum strtoupper" data-max-length="{if $czy_szkoly}45{else}30{/if}">
			
			<label for="form_dane3">Dane 3: (znaków <span>{if isset($a_pracodawca)}{strlen($a_pracodawca.dane3)}{else}0{/if}</span> z {if $czy_szkoly}45{else}30{/if})</label>	
			<input type="text" name="a_pracodawca[dane3]" id="form_dane3" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.dane3}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.dane3}{/if}" class="jMaxLength jAlfaNum strtoupper" data-max-length="{if $czy_szkoly}45{else}30{/if}">
			
			<label for="form_dane4">Dane 4: (znaków <span>{if isset($a_pracodawca)}{strlen($a_pracodawca.dane4)}{else}0{/if}</span> z {if $czy_szkoly}45{else}30{/if})</label>	
			<input type="text" name="a_pracodawca[dane4]" id="form_dane4" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.dane4}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.dane4}{/if}" class="jMaxLength jAlfaNum strtoupper" data-max-length="{if $czy_szkoly}45{else}30{/if}">
			
			{if $czy_szkoly}
				<!--
				<label for="form_dane5">Dane 5: (znaków <span>{if isset($a_pracodawca)}{strlen($a_pracodawca.dane5)}{else}0{/if}</span> z 30)</label>	
				<input type="text" name="a_pracodawca[dane5]" id="form_dane5" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.dane5}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.dane5}{/if}" class="jMaxLength jAlfaNum strtoupper" data-max-length="30">
			
				<label for="form_dane6">Dane 6: (znaków <span>{if isset($a_pracodawca)}{strlen($a_pracodawca.dane6)}{else}0{/if}</span> z 30)</label>	
				<input type="text" name="a_pracodawca[dane6]" id="form_dane6" value="{if isset($a_pracodawca) && !isset($smarty.session.form.a_pracodawca)}{$a_pracodawca.dane6}{/if}{if isset($smarty.session.form.a_pracodawca)}{$smarty.session.form.a_pracodawca.dane6}{/if}" class="jMaxLength jAlfaNum strtoupper" data-max-length="30">
				-->
			{/if}
			
			
		
		</fieldset>
	</form>
</section>
