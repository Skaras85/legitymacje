<section>
	<h1>Parametry</h1>
	
	<form method="post" action="index.php">
	    <input type="hidden" name="module" value="panel">
	    <input type="hidden" name="action" value="zapisz_parametry">
	   
	   		{if $a_parametry}
	   			{foreach $a_parametry as $a_parametr}
			        <label for="par{$a_parametr.id_parametry}">{$a_parametr.nazwa}</label>
			        <input type="text" id="par{$a_parametr.id_parametry}" name="a_parametry[{$a_parametr.id_parametry}]" value="{$a_parametr.wartosc}">
	        	{/foreach}
	        {/if}
	        
	        <input type="submit" value="Zapisz">
	    </fieldset>
	
	</form>
</section>