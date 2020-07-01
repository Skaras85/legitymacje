<section>
	<h1>Formularz umowy zewnętrznej</h1>
	<form method="POST" action="index.php" enctype="multipart/form-data" class="jValidate">
		<input type="hidden" name="module" value="umowy">
		<input type="hidden" name="action" value="zapisz_umowe_zew">
		{if !empty($a_umowa)}
			<input type="hidden" name="a_umowa[id_umowy]" value="{$a_umowa.id_umowy2}">
		{/if}
		
		<label for="numer_umowy">Numer umowy:</label>
		<input type="text" name="a_umowa[numer_umowy]" class="jRequired" id="numer_umowy">

		<label for="email_naruszenia">E-mail do powiadomień ws. naruszeń przetwarzania danych osobowych:</label>
		<input type="text" name="a_umowa[email_naruszenia]" class="jRequired jEMail" id="email_naruszenia">
		
		<label for="email">E-mail do powiadomień dla umowy powierzenia danych osobowych:</label>
		<input type="text" name="a_umowa[email]" class="jRequired jEMail" id="email">
		
		<label for="typ">Typ umowy:</label>
		<select name="a_umowa[id_umowy_typy]" id="typ">
			{foreach $a_typy as $a_typ}
				<option value="{$a_typ.id_umowy_typy}">{$a_typ.nazwa}</option>
			{/foreach}
		</select>
		
		<div>
			<input type="radio" name="a_umowa[czas_umowy]" value="nieokreslony" class="czas_umowy" id="czas_nieokreslony" {if $a_umowa.okres_obowiazywania=='0000-00-00'}checked{/if}> <label for="czas_nieokreslony" class="inline">na czas nieokreślony</label>
		</div>
		<div>
			<input type="radio" name="a_umowa[czas_umowy]" value="okreslony" id="czas_okreslony" class="czas_umowy" {if $a_umowa.okres_obowiazywania!='0000-00-00'}checked{/if}> <label for="czas_okreslony" class="inline">na czas określony</label>
		</div>
		
		<div class="okres_obowiazywania {if $a_umowa.okres_obowiazywania=='0000-00-00'}hidden{/if}">
			<input type="text" name="a_umowa[okres_obowiazywania]" class="datepicker" value="{$a_umowa.okres_obowiazywania}" autocomplete="off">
		</div>
		<br>
		
		<label for="wersja">Wersja:</label>
		<select name="a_umowa[wersja]" class="autoWidth" id="wersja">
			<option value="0" {if $a_umowa.wersja==0}selected{/if}>umowa własna</option>
			
			{foreach $a_wersje_umowy as $a_wersja}
				<option value="{$a_wersja.wersja}" {if $a_umowa.wersja==$a_wersja.wersja}selected{/if}>{$a_wersja.wersja}</option>
			{/foreach}
			
		</select>
		
		<label for="uwagi">Uwagi:</label>
		<input type="text" name="a_umowa[uwagi]" id="uwagi" value="{$a_umowa.uwagi}">
		
		<label for="data_zawarcia">Data zawarcia:</label>
		<input type="text" name="a_umowa[data_zawarcia]" class="datepicker" id="data_zawarcia" autocomplete="off">
		
		<input type="file" name="umowa" class="jExtension" data-extensions="pdf"><br><br>
		<input type="submit" value="Dodaj" class="autoWidth buttonIcon dodajButton green">
	</form>
</section>