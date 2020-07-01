<section>
	{include file="wiadomosci/wiadomosci_menu.tpl"}
	
	<div class="left wiadomosciWrapper">
		<p class="h2">Wiadomości {if $typ=='wyslane'}wysłane{else}{$typ}{/if}</p>
		
		<table id="wiadomosciTabel">
		    <thead>
		        <tr>
		            <th class="firstColumn"><input type="checkbox" class="checkAll"></th>
		            <th>{if isset($wyslane) || isset($robocze)}Do{else}Od{/if}:</th>
		            <th>Temat:</th>
		            {if isset($robocze)}
		                <th class="lastColumn">Utworzono:</th>
		            {else}
		                <th class="lastColumn">Wysłano:</th>
		            {/if}
		            {if session::who('admin') && $typ=='odebrane'}
		            	<th>Zaloguj</th>
		            {/if}
		        </tr>
		    </thead>
		    <tbody>
		        {if $a_wiadomosci!=NULL}
		            {foreach $a_wiadomosci as $a_wiadomosc}
		                <tr>
		                    <td class="firstColumn"><input type="checkbox" class="check" data-id="{$a_wiadomosc.id_wiadomosci}"></td>
		                    <td {if $a_wiadomosc.data_przeczytania=='0000-00-00 00:00:00'}class="nowa"{/if}><a href="{$_base_url}wiadomosci/{if isset($robocze)}edytuj{else}czytaj{/if}/id/{$a_wiadomosc.uniqid_wiadomosci}">{if $a_wiadomosc.imie=='' && $a_wiadomosc.nazwisko==''}Administracja{else}{$a_wiadomosc.imie} {$a_wiadomosc.nazwisko}{/if}</a></td>
		                    <td {if $a_wiadomosc.data_przeczytania=='0000-00-00 00:00:00'}class="nowa"{/if}><a href="{$_base_url}wiadomosci/{if isset($robocze)}edytuj{else}czytaj{/if}/id/{$a_wiadomosc.uniqid_wiadomosci}">{if $a_wiadomosc.temat!=''}{$a_wiadomosc.temat}{else}brak tematu{/if}</a></td>
		                    <td class="lastColumn">{if isset($robocze)}{$a_wiadomosc.data_utworzenia|substr:0:16}{else}{$a_wiadomosc.data_wyslania|substr:0:16}{/if}</td>
		                	{if session::who('admin') && $typ=='odebrane'}
			                	<td>
			                		{if $a_wiadomosc.id_nadawcy!=0}
				                		<form action="https://realizacja.loca.pl" method="POST" target="_blank">
				                            <input type="hidden" name="module" value="users">
				                            <input type="hidden" name="action" value="zaloguj_na_usera">
				                            <input type="hidden" name="id_users" value="{$a_wiadomosc.id_nadawcy}">
				                            <input type="hidden" name="id_pracownika" value="{session::get_id()}">
				                            <input type="submit" value="Zaloguj" class="autoWidth">
				                        </form>
			                        {/if}
		                        </td>
	                        {/if}
		                </tr>
		            {/foreach}
		        {/if}
		    </tbody>
		    <tfoot>
		        <tr>
		            <td colspan="5" class="firstColumn center">
		                <a href="#" class="button red buttonIcon koszButton" id="deleteMessages">Usuń</a>
		            </td>
		        </tr>
		    </tfoot>
		</table>
	</div>
</section>