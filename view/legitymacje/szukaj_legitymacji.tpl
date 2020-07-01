<section>
	<h1>Szukaj legitymacji</h1>
	<form method="GET" aciotn="index.php" class="marginTop20">
		<input type="hidden" name="module" value="legitymacje">
		<input type="hidden" name="action" value="szukaj_legitymacji">

		<select name="id_karty" class="autoWidth">
			<option value="0">Wszystkie</option>
			{foreach $a_karty as $a_karta}
				<option value="{$a_karta.id_karty}" {if $a_karta.id_karty==$id_karty}selected{/if}>{$a_karta.nazwa}</option>
			{/foreach}
		</select>

		<input type="text" name="fraza" value="{$fraza}" class="autoWidth">
		<input type="submit" value="Szukaj" name="szukaj">
		
	</form>
	
	{$tabela}
</section>

