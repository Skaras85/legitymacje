<section>
	
	{if !isset($a_zamowienie)}
		<h2>{$a_karta.nazwa} {if $a_karta.status=='oczekujaca'}(Oczekuje na potwierdzenie){/if} - <a href="{$_base_url}strony/{$a_karta.sludge},{$a_karta.id_sites}" class="modal">pokaż opis</a><br>{$a_placowka.nazwa} <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="78"></h2>
	{else}
		<h2>Zamówienie nr {$a_zamowienie.numer_zamowienia}<br>{$a_karta.nazwa}<br>{$a_placowka.nazwa}</h2>
	{/if}
	
	{if $a_karta.id_karty!=1 && !$czy_zamawianie_wlaczone}
		<p class="communicat_info">Możliwość składania zamówień chwilowo nie jest możliwa ze względów technicznych</p>
	{/if}
	
	{if $a_placowka.status!='aktywna' && !session::get('czy_zdalny')}
		<p class="communicat_info">Zamówienia dla tej placówki będą możliwe dopiero po otrzymaniu przez nas podpisanego formularza personalizacji, <a href="{$_base_url}images/placowki/{$a_placowka.id_placowki}/{$a_placowka.uniqid_placowki}.pdf" download>POBIERZ TUTAJ</a></p>
	{/if}

	{if !$czy_moze_zamawiac && empty($a_zamowienie)}
		<p class="communicat_info">Zamówienie będzie możliwe dopiero po przesłaniu do nas podpisanej umowy współpracy i powierzenia danych osobowych., <a href="{$_base_url}umowy/lista-umow">Przejdź do umów</a></p>
	{/if}
	
	{if !session::who('admin')}
		<div class="marginTop20"></div>
		<a href="{$_base_url}placowki/placowka/id/{$a_placowka.id_placowki}" class="button buttonIcon wsteczButton">Wstecz</a>
	{/if}
	
	
	<form method="POST" aciotn="index.php" class="marginTop20">
		<input type="hidden" name="module" value="legitymacje">
		<input type="hidden" name="action" value="lista_osob_legitymacji">
		<input type="hidden" name="id_karty" id="id_karty" value="{$a_karta.id_karty}">
		<input type="hidden" name="id_placowki" value="{$a_placowka.id_placowki}">
		
		{if !isset($a_zamowienie)}
		
			{if $a_karta.status=='oczekujaca' && (session::who('admin') || session::get('czy_zdalny'))}
				<a href="{$_base_url}legitymacje/aktywuj_karte/id_placowki/{$a_placowka.id_placowki}/id_karty/{$a_karta.id_karty}" class="button">Aktywuj karte</a>
			{/if}
		
			<a href="{$_base_url}legitymacje/formularz-osoby/id_karty/{$a_karta.id_karty}" class="button green modal buttonIcon dodajButton">Dodaj</a>
			<a href="{$_base_url}legitymacje/formularz-importu-osob/id_karty/{$a_karta.id_karty}" class="button green buttonIcon downloadButton">Import z CSV</a>
			<a href="{$_base_url}images/szablony_importu_legitymacji/{if $a_karta.id_karty==1}szablon_importu_ln.csv{else}szablon_importu_els.csv{/if}" class="button green buttonIcon uploadButton" download>Pobierz szablon CSV</a>
			
			<input type="submit" name="pdf" name="Druk" class="green button buttonIcon drukujButton" value="Drukuj formularz">
			
			{if session::who('admin') || ($a_karta.id_karty==1 && $czy_formularze_zaakceptowane!=1 || $czy_zamawianie_wlaczone)}
				{if ($a_placowka.status=='aktywna' && $a_karta.status!='oczekujaca' && $czy_moze_zamawiac) || session::get('czy_zdalny')}
					<a href="#" class="zamow_legitymacje button green">Dodaj do koszyka</a>
				{/if}
			{/if}
			
			<a href="#" class="button green przenies_osoby_do_karty">Przenieś osoby</a>
			
			
			{if !$czy_moze_zamawiac}
				
			{/if}
			
			{if session::get('czy_zdalny')}
				<a href="{$_base_url}migracja/formularz_importu_nauczycieli" class="button">Importuj</a>
				
				{if $a_karta.id_karty==1}
					<a href="{$_base_url}users/lista_formularzy" class="button">Formularze</a>
				{/if}
			{/if}
			
			{if session::who('admin') || session::get('czy_zdalny')}
				<a href="{$_base_url}karty/formularz-cennika/id_karty/{$a_karta.id_karty}" class="button">Zarządzaj cennikami</a>
			{/if}
		{else}
			{if session::get('czy_zdalny')}
				<input type="hidden" name="id_zamowienia" value="{$a_zamowienie.id_zamowienia}">
				<input type="submit" value="Eksport do CSV" name="csv">
			{/if}
		{/if}

		{$tabela}
	</form>
</section>

