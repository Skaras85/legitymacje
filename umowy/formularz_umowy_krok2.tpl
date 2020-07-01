<section>
	<h1>Formularz umowy</h1>
	
	{if !$czy_szkoly_lub_pracodawcy}
		<p class="communicat_info">W celu wygenerowanie umowy musisz najpierw dodać {if $a_umowa.id_umowy_typy==2}<a href="{$_base_url}placowki/formularz-pracodawcy/czy_szkoly/1" class="button green  buttonIcon dodajButton">nazwę szkoły</a>{else}<a href="{$_base_url}placowki/formularz-pracodawcy" class="button green buttonIcon dodajButton">pracodawcę</a>{/if}.</p>
	{else}
		<form id="form" method="post" action="{$_base_url}" class="jValidate">
			<input type="hidden" name="action" value="podglad_umowy">
			<input type="hidden" name="module" value="umowy">
			<input type="hidden" name="a_umowa[id_umowy_typy]" value="{$a_umowa.id_umowy_typy}">
			<input type="hidden" name="a_umowa[id_umowy_naglowki]" value="{$a_umowa.id_umowy_naglowki}">
			<input type="hidden" name="module" value="umowy">
			{if $hash}
				<input type="hidden" name="hash" value="{$hash}">
			{/if}
			{if isset($a_umowa.id_umowy)}<input name="a_umowa[id_umowy]" value="{$a_umowa.id_umowy}" type="hidden">{/if}
			
			{if isset($a_umowa.id_umowy_naglowki) && $a_umowa.id_umowy_naglowki==1 || !isset($a_umowa.id_umowy_naglowki) && isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_naglowki==1}
				
				{include file='umowy/formularz_umowy_dane_firmy.tpl'}
				
				<p class="noMargin h2">Reprezentowanym przez:</p>
				{include file='umowy/formularz_umowy_reprezentant.tpl'}
				
				<p class="noMargin h2">Reprezentującego na podstawie pełnomocnictwa:</p>
				
				<label for="form_umowy_nr_pelnomocnistwa">Numer pełnomocnictwa (pole nie jest obowiązkowe)</label>
				<input type="text" class="" name="a_umowa[nr_pelnomocnictwa]" id="form_umowy_nr_pelnomocnictwa" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.nr_pelnomocnictwa)}{$a_umowa.nr_pelnomocnistwa}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.nr_pelnomocnictwa}{/if}">
			
				<p class=" h2">Działającemu na rzecz Placówki oświaty:</p>
				
				{include file='umowy/formularz_umowy_dane_placowki.tpl'}
				
				
			{elseif $a_umowa.id_umowy_naglowki==2 || isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_naglowki==2}
				
				{include file='umowy/formularz_umowy_dane_placowki.tpl'}
				
				<p class="noMargin h2">Reprezentowanym przez:</p>
				{include file='umowy/formularz_umowy_reprezentant.tpl'}
				
				<label for="form_umowy_email">E-mail do powiadomień dla umowy powierzenia danych osobowych</label>
				<input type="text" class="jRequired jEMail" name="a_umowa[email]" id="form_umowy_email" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.email)}{$a_umowa.email}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.email}{/if}">
	
				<label for="form_umowy_email_naruszenia">E-mail do powiadomień ws. naruszeń przetwarzania danych osobowych</label>
				<input type="text" class="jRequired jEMail" name="a_umowa[email_naruszenia]" id="form_umowy_email_naruszenia" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.email_naruszenia)}{$a_umowa.email_naruszenia}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.email_naruszenia}{/if}">
	
				
	
			{elseif $a_umowa.id_umowy_naglowki==3 || isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_naglowki==3}
			
				<p class="noMargin h2">Umowa zawarta z:</p>
				{include file='umowy/formularz_umowy_reprezentant.tpl'}
				
				<p class="noMargin h2">prowadzącym/prowadzącą działalność gospodarczą pod nazwą:</p>
				<div class="marginTop20"></div>
				{include file='umowy/formularz_umowy_dane_firmy.tpl'}
				<label for="form_umowy_regon">REGON</label>
				<input type="text" class="jRequired jRegon" name="a_umowa[regon]" id="form_umowy_regon" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.regon)}{$a_umowa.regon}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.regon}{/if}">
	
				{include file='umowy/formularz_umowy_dane_placowki.tpl'}
	
			{elseif $a_umowa.id_umowy_naglowki==4 || isset($smarty.session.a_umowa) && $smarty.session.a_umowa.id_umowy_naglowki==4}
			
				{include file='umowy/formularz_umowy_dane_firmy.tpl'}
				<label for="form_umowy_regon">REGON</label>
				<input type="text" class="jRequired jRegon" name="a_umowa[regon]" id="form_umowy_regon" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.regon)}{$a_umowa.regon}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.regon}{/if}">
	
				<p class="noMargin h2">Reprezentowanym przez:</p>
				{include file='umowy/formularz_umowy_reprezentant.tpl'}
				
				{include file='umowy/formularz_umowy_dane_placowki.tpl'}
			
			{/if}
			
			{if $a_umowa.id_umowy_typy==2}
				<label for="form_umowy_dyrektor">Imię i Nazwisko Dyrektora do umieszczenia na Legitymacji szkolnej</label>
				<input type="text" class="jRequired strtoupper " name="a_umowa[dyrektor]" id="form_umowy_dyrekor" value="{if !isset($smarty.session.a_umowa) && isset($a_umowa.dyrekor)}{$a_umowa.dyrekor}{elseif isset($smarty.session.a_umowa)}{$smarty.session.a_umowa.dyrekor}{/if}">
			{/if}
			
			{if !isset($a_umowa)}
	       		<input type="submit" value="Krok 2">
	        {else}
	        	<a href="{$_base_url}umowy/{if !isset($a_karty)}formularz_umowy_krok1{else}formularz_umowy_wybor_elegitymacji/id_umowy_typy/{$a_umowa.id_umowy_typy}/id_umowy_naglowki/{$a_umowa.id_umowy_naglowki}{/if}{if $hash}/hash/{$hash}{/if}" class="button red buttonIcon wsteczButton">Powrót</a>
				<input type="submit" value="Podejrzyj i generuj" name="submit" class="green buttonIcon takButton">
	        {/if}
	     </form>  
     {/if}
</section>