<section class="formSection">

	{if $czy_hologramy}
		<h2>{if isset($a_karta)}Edycja danych {$a_karta.nazwa}{else}Dodaj produkt{/if}</h2></a>
	{else}
		<a href="legitymacje/lista-kart"><h2 id="panelTitle" title="Powrót">{if isset($a_karta)}Edycja danych {$a_karta.nazwa}{else}Dodaj kartę{/if}</h2></a>
	{/if}
	
	<form action="{$_base_url}" method="POST" class="jValidate" enctype="multipart/form-data">
		
		{if $czy_hologramy}
			<input type="hidden" name="module" value="produkty">
			<input type="hidden" name="action" value="zapisz_produkt">
			{if isset($a_karta)}
		        <input type="hidden" name="a_karta[id_produkty]" value="{$a_karta.id_produkty}">
		    {/if}
		{else}
			<input type="hidden" name="module" value="karty">
			<input type="hidden" name="action" value="zapisz_karte">
			{if isset($a_karta)}
		        <input type="hidden" name="a_karta[id_karty]" value="{$a_karta.id_karty}">
		    {/if}
		{/if}
	    
	    
	
	    <fieldset class="clearfix">
	    	<div class="left">
	    		{if !$czy_hologramy && isset($a_karta)}
	    			<a href="karty/formularz-pol/id_karty/{$a_karta.id_karty}" class="button">Dodaj pola do karty</a>
	    		{/if}
	    		
		    	<label for="form_nazwa" class="marginTop10">Nazwa:</label>
				<input type="text" name="a_karta[nazwa]" id="form_nazwa" value="{if isset($a_karta) && !isset($smarty.session.form.a_karta)}{$a_karta.nazwa}{/if}{if isset($smarty.session.form.a_karta)}{$smarty.session.form.a_karta.nazwa}{/if}" class="jRequired jAlfaNum">
					
				{if $czy_hologramy}
	    			<label for="form_kategoria">Kategoria:</label>
					<select name="a_karta[id_produkty_kategorie]">
						{if $a_kategorie}
							{foreach $a_kategorie as $a_kategoria}
								<option value="{$a_kategoria.id_produkty_kategorie}" {if isset($a_karta) && $a_kategoria.id_produkty_kategorie==$a_karta.id_produkty_kategorie}selected{/if}>{$a_kategoria.nazwa}</option>
							{/foreach}
						{/if}
					</select>	
	    		{/if}
					
				<label for="form_opis">Opis:</label>
				<select name="a_karta[id_sites]">
					{if $a_strony}
						{foreach $a_strony as $a_strona}
							<option value="{$a_strona.id_sites}" {if isset($a_karta) && $a_strona.id_sites==$a_karta.id_sites}selected{/if}>{$a_strona.title}</option>
						{/foreach}
					{/if}
				</select>	
					
				<label for="form_cennik">Domyślny cennik:</label>
				<select name="a_karta[id_cenniki]">
					{if isset($a_cenniki)}
						{foreach $a_cenniki as $a_cennik}
							<option value="{$a_cennik.id_cenniki}" {if isset($a_karta) && $a_cennik.id_cenniki==$a_karta.id_cenniki}selected{/if}>{$a_cennik.nazwa}</option>
						{/foreach}
					{/if}
				</select>
				
				{foreach $a_sposoby_wysylki as $a_sposob_wysylki}
					<label for="cennik_wysylki_{$a_sposob_wysylki.id_sposoby_wysylki}">Domyślny cennik dla {$a_sposob_wysylki.nazwa}</label>
					<select id="cennik_wysylki_{$a_sposob_wysylki.id_sposoby_wysylki}" name="a_cenniki[{$a_sposob_wysylki.id_sposoby_wysylki}]">
						{foreach $a_cenniki as $a_cennik}
							<option value="{$a_cennik.id_cenniki}" {if isset($a_domyslne_cenniki_karty_wysylki)}{foreach $a_domyslne_cenniki_karty_wysylki as $a_domyslny_cennik}{if $a_domyslny_cennik.id_cenniki==$a_cennik.id_cenniki && $a_domyslny_cennik.id_sposoby_wysylki==$a_sposob_wysylki.id_sposoby_wysylki}selected{/if}{/foreach}{/if}>{$a_cennik.nazwa}</option>
						{/foreach}
					</select>
					
					{if !$czy_hologramy}
						<label for="form_max_ilosc_legitymacji_{$a_sposob_wysylki.id_sposoby_wysylki}">Max. ilość legitymacji dla {$a_sposob_wysylki.nazwa}</label>
						<input type="text" name="a_max_ilosc_legitymacji[{$a_sposob_wysylki.id_sposoby_wysylki}]" id="form_max_ilosc_legitymacji_{$a_sposob_wysylki.id_sposoby_wysylki}" value="{if isset($a_domyslne_cenniki_karty_wysylki)}{foreach $a_domyslne_cenniki_karty_wysylki as $a_domyslny_cennik}{if $a_domyslny_cennik.id_sposoby_wysylki==$a_sposob_wysylki.id_sposoby_wysylki}{$a_domyslny_cennik.max_ilosc_legitymacji}{/if}{/foreach}{/if}" class="jRequired jNumber">
					{/if}
				{/foreach}
					
		    	<label for="form_miniaturka">Miniaturka</label>
		    	<input type="file" name="file" class="jExtension" data-extensiions="jpg jpeg gif png">
		    	
		    	{if isset($a_karta)}
		    		{if $czy_hologramy}
		    			<img src="images/produkty/{$a_karta.img}" alt="">
		    		{else}
		    			<img src="images/karty/{$a_karta.img}" alt="">
		    		{/if}
		    	{/if}
		    	
		    	<input type="submit" value="{if isset($a_karta)}Zapisz{else}Dodaj{/if}">
			</div>
		</fieldset>
	</form>
</section>
