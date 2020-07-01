<section class="formSection">

    <h1>{if isset($a_cennik)}Edytuj dane {$a_cennik.nazwa}{else}Dodaj cennik{/if}</h1>

	<form action="{$_base_url}" method="POST" class="jValidate">
		<input type="hidden" name="module" value="cenniki">
		<input type="hidden" name="action" value="zapisz_cennik">
		
		{if !isset($a_cennik) && session::get('czy_zdalny') || isset($a_cennik) && $a_cennik.id_placowki!=0}
	    	<input type="hidden" name="a_cennik[czy_placowki]" value="1">
	    {/if}
	    
	    {if isset($a_cennik)}
	        <input type="hidden" name="a_cennik[id_cenniki]" value="{$a_cennik.id_cenniki}">
	    {/if}
	    
	    <fieldset class="clearfix">
		    <label for="form_nazwa">Nazwa:</label>
			<input type="text" name="a_cennik[nazwa]" id="form_nazwa" autofocus="" value="{if isset($a_cennik) && !isset($smarty.session.form.a_cennik)}{$a_cennik.nazwa}{/if}{if isset($smarty.session.form.a_cennik)}{$smarty.session.form.a_cennik.nazwa}{/if}" class="jRequired jAlfaNum">
			
			<table>
				<tr>
					<th>Od</th>
					<th>Do</th>
					<th>Cena</th>
					<th>Usuń</th>
				</tr>
				{if isset($a_przedzialy_cenowe)}
					{foreach $a_przedzialy_cenowe as $index=>$a_przedzial}
						<tr>
							<td><input type="text" name="a_przedzialy[{$index}][od]" value="{$a_przedzial.od}"></td>
							<td><input type="text" name="a_przedzialy[{$index}][do]" value="{$a_przedzial.do}"></td>
							<td><input type="text" name="a_przedzialy[{$index}][cena]" value="{$a_przedzial.cena}"></td>
							<td><a href="#" class="button usun_przedzia_cenowy">Usuń</a></td>
						</tr>
					{/foreach}
				{/if}
				<tr>
					<td><input type="text" name="a_przedzialy[{if isset($a_przedzialy_cenowe)}{$a_przedzialy_cenowe|count}{else}0{/if}][od]"></td>
					<td><input type="text" name="a_przedzialy[{if isset($a_przedzialy_cenowe)}{$a_przedzialy_cenowe|count}{else}0{/if}][do]"></td>
					<td><input type="text" name="a_przedzialy[{if isset($a_przedzialy_cenowe)}{$a_przedzialy_cenowe|count}{else}0{/if}][cena]"></td>
					<td><a href="#" class="button dodaj_przedzia_cenowy">Dodaj kolejny</a></td>
				</tr>
			</table>
				
			<input type="submit" value="Zapisz">
		
		</fieldset>
	</form>
</section>
