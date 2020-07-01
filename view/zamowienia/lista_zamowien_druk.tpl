<section>
	{if !isset($html)}
		<h1>Zamówienia</h1>
	
		<form action="{$_base_url}zamowienia/lista_zamowien_druk" method="POST" class="marginTop20">
	
			<select name="id_karty" id="karta" class="autoWidth">
				<option value="0">Wszystkie</option>
				{foreach $a_karty as $a_karta}
					<option value="{$a_karta.id_karty}" {if $a_karta.id_karty==$id_karty}selected{/if}>{$a_karta.nazwa}</option>
				{/foreach}
			</select>
			<input type="hidden" id="id_karty" value="{$id_karty}">
			
			<input type="submit" value="Wybierz" class="autoWidth">
			
			
			{if $id_karty}
				<br>
				<select name="typ_druku" class="autoWidth">
					<option>awers bez zdjęcia</option>
					<option>awers i rewers bez zdjęcia</option>
					<option>rewers bez zdjęcia</option>
					<option>zdjęcie</option>
					<option>awers i zdjęcie</option>
					<option>awers, rewers i zdjęcie</option>
				</select>
				<input type="submit" value="Drukuj" class="autoWidth" name="submit">
			{/if}
			
			{$tabela}
	
		</form>
	{else}
		<a href="#" class="button print">Drukuj</a>
		<div class="marginTop20 printHide"></div>
		{$html}
	{/if}
</section>