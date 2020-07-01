<section>

	<p class="h2">Logi</p>
	
	<form action="{$_base_url}/logi/lista">
		{include file="system_view/form_miesiac.tpl"}
		{include file="system_view/form_rok.tpl"}
		<input type="submit" value="Wybierz">
	</form>
	
	<table class="dataTables">
	    <thead>
	        <tr>
	            <th>Data</th>
	            <th>Akcja</th>
	            <th>Pracownik</th>
	            <th>User</th>
	            <th>IP</th>
	            <th>Opis</th>
	        </tr>
	    </thead>
	    <tbody>
	        {if !empty($a_logi)}
	            {foreach $a_logi as $a_log}
	                <tr>
	                	<td>{$a_log.data}</td>
	                	<td>{$a_log.akcja}</td>
	                	<td>{$a_log.pracownik}</td>
	                	<td>{$a_log.imie} {$a_log.nazwisko}</td>
	                	<td>{$a_log.ip}</td>
	                	<td>{$a_log.opis}</td>
	                </tr>
	            {/foreach}
	        {/if}
	    </tbody>
	</table>
</section>