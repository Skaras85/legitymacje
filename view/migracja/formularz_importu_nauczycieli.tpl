<section>

	<form method="POST" action="index.php" class="jValidate">
		
	<h1>Importowanie osób ze starego systemu</h1>
	<input type="hidden" name="module" value="migracja">
	<input type="hidden" name="action" value="formularz_importu_nauczycieli">
	<label for="id_users">ID placówki</label>
	<input type="text" name="id_placowki" value="{$id_placowki}">
	<p>lub</p>
	<label for="id_users">ID nauczyciela</label>
	<input type="text" name="id_nauczyciela" value="{$id_nauczyciela}">
	<input type="submit" value="Sprawdź">
	</form>
	{if $a_nauczyciele}
		<form method="POST" action="index.php" class="marginTop20">
			
			<input type="hidden" name="module" value="migracja">
			<input type="hidden" name="action" value="importuj_nauczycieli">
			<label for="wybierz_pracodawce">Pracodawca</label>
			<select id="wybierz_pracodawce" name="id_pracodawcy">
				{if $a_pracodawcy}
					{foreach $a_pracodawcy as $a_pracodawca}
						<option value="{$a_pracodawca.id_pracodawcy}">{$a_pracodawca.nazwa}</option>
					{/foreach}
				{/if}
			</select>
			
			{include file='legitymacje/import_nauczycieli.tpl'}
			<input type="submit" value="Importuj">
		
		</form>
	{/if}
	
</section>
