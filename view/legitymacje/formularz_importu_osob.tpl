<section id="importowanie_zdjec_wrapper" class="jValidate">
	<input type="hidden" id="id_karty" value="{$id_karty}">

	<h1>Importowanie osób  <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="79"></h1>
	<label for="import_osob_plik">Wybierz plik CSV</label>
	<input type="file" id="import_osob_plik" value="Wybierz plik">
	
	<input type="submit" class="button hidden buttonIcon green takButton" value="Importuj dane" id="importuj_dane_confirm">


	<div></div>
	<label for="id_pracodawcy" class="hidden">{if $id_karty==1}Pracodawca{else}Szkoła{/if}</label>
	<select id="id_pracodawcy" class="hidden">
		{if $a_pracodawcy}
			{foreach $a_pracodawcy as $a_pracodawca}
				<option value="{$a_pracodawca.id_pracodawcy}">{$a_pracodawca.nazwa}</option>
			{/foreach}
		{/if}
	</select>

	<div id="import_danych_dane"></div>
</section>
