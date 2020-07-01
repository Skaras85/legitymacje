<h2 id="panelTitle" title="Powrót">Zarządzaj użytkownikami</h2>
    <table class="tablesorter">
        <thead>
            <tr>
                <td colspan="11">
                    <form action="{$_base_url}users/admin_lista_userow" method="GET">
                        <label for="form_szukaj" class="hidden">Szukaj użytkownika: </label>
                        <input type="text" name="nazwa" id="form_szukaj" class="inline" placeholder="Nazwa lokalu" value="{if $nazwa}{$nazwa}{/if}">
						<!--<input type="text" name="data" value="{$wybrana_data}" class="jDate datepicker autoWidth inline">-->
                        <input type="submit" value="Szukaj" class="inline">
                        <a href="{$_base_url}users/formularz-uzytkownika" class="button">Dodaj lokal</a>
                        <a href="{$_base_url}users/statystyki_odwiedzin" class="button">Statystyki odwiedzin</a>
                    </form>
                </td>
            </tr>
            <tr>
            	<th>L.p.</th>
            	<th>Nazwa lokalu</th>
            	<!--<th>Dzielnica</th>-->
            	<th>Data ważności</th>
            	<th>Czy klient</th>
            	<th>Uwagi</th>
            	<th>FB</th>
            	<th>Www</th>
                <th>Edycja</th>
                <th>Dodaj posiłek</th>
                <th>Chat</th>
            </tr>
        </thead>
        <tbody>
        	{if $a_users!=false}
	            {foreach $a_users as $a_user}
	                <tr class="{if $a_user['data_waznosci_konta']!='0000-00-00' && strtotime($a_user['data_waznosci_konta'])<strtotime(date('Y-m-d'))}koniec_waznosci_konta{elseif strtotime($a_user['data_waznosci_konta'])>=strtotime(date('Y-m-d'))}konto_wazne{/if}">
	                	<td>{counter}</td>
	                	<td><a href="users/profil/id/{$a_user.id_users}" target="_blank">{$a_user.nazwa}</a></td>
	                	<!--<td>{$a_user.dzielnica}</td>-->
	                	<td>{$a_user.data_waznosci_konta}</td>
	                	<td>{if $a_user.czy_klient==1}TAK{else}<span class="strong">NIE</span>{/if}</td>
	                	<td><textarea class="localUwagi" data-id_users="{$a_user.id_users}">{$a_user.uwagi}</textarea></td>
	                	<td class="center"><a href="{$a_user.facebook}" target="_blank">Fb</a></td>
	                	<td class="center"><a href="{$a_user.www}" target="_blank">Www</a></td>
	                    <td><a href="users/formularz-uzytkownika/id/{$a_user.id_users}" target="_blank">Edytuj dane</a></td>
	                    <td><a href="lunch/formularz-lunchu/id_users/{$a_user.id_users}" target="_blank">Dodaj posiłek</a></td>
	                    <td><a href="#" class="pokaz_chat" data-id="{$a_user.id_users}">{if $a_user.czy_nowe_wiadomosci}<img src="{$_base_url}images/core/info_icon.png"><span class="bold">{/if} Pokaż{if $a_user.czy_nowe_wiadomosci}</span>{/if}</a></td>
	                </tr>
	            {/foreach}
		    {/if}
        </tbody>
    </table>