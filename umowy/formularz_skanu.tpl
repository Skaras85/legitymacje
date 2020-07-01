<section>
	<h1>Formularz skanu</h1>
	<form method="POST" action="index.php" enctype="multipart/form-data" class="jValidate">
		<input type="hidden" name="module" value="umowy">
		<input type="hidden" name="action" value="potwierdz_umowe">
		<input type="hidden" name="id_umowy" value="{$a_umowa.id_umowy2}">
		
		<div>
			<input type="radio" name="a_umowa[czas_umowy]" value="nieokreslony" class="czas_umowy" id="czas_nieokreslony" {if $a_umowa.okres_obowiazywania=='0000-00-00'}checked{/if}> <label for="czas_nieokreslony" class="inline">na czas nieokreślony</label>
		</div>
		<div>
			<input type="radio" name="a_umowa[czas_umowy]" value="okreslony" id="czas_okreslony" class="czas_umowy" {if $a_umowa.okres_obowiazywania!='0000-00-00'}checked{/if}> <label for="czas_okreslony" class="inline">na czas określony</label>
		</div>
		
		<div class="okres_obowiazywania {if $a_umowa.okres_obowiazywania=='0000-00-00'}hidden{/if}">
			<input type="text" name="a_umowa[okres_obowiazywania]" class="datepicker" value="{$a_umowa.okres_obowiazywania}">
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
							
		
		<input type="file" name="potwierdzenie" class="jExtension" data-extensions="pdf"><br><br>
		
		<div>
			<input type="checkbox" id="wyslij_potwierdzenie" name="wyslij_potwierdzenie" checked> <label for="wyslij_potwierdzenie">Czy wysłać potwierdzenie?</label>
		</div>
		
		<input type="submit" value="Dodaj" class="autoWidth">
	</form>
</section>