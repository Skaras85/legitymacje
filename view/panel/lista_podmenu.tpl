<a href="panel"><h2 id="panelTitle" title="Powrót">Zarządzaj podmenu</h2></a>
<span class="tooltip" title="<p>Kolejność wyświetlania pozycji w podmenu można zmieniać przesuwając je między sobą</p>"></span>
<form method="post" action="index.php">
	{foreach $a_menu as $a_glowne}
		{if $a_glowne.submenu!=0}
        	<table class="panelTabela menus trHover">
        		<thead>
        			<tr>
        				<th colspan="4" class="tableTrMainMenu">{$a_glowne.name}</th>
        			</tr>
        			<tr>
        				<th>L.p</th>
        				<th>Edycja</th>
        				<th>Czy widoczny?</th>
        				<!--<th>Usuń</th>-->
        			</tr>
        		</thead>
        		<tbody>
    				{foreach $a_submenu as $a_element}
    				    {if $a_element.parent_id==$a_glowne.id_menu}
                			<tr class="sortElement" data-menu-id="{$a_element.id_menu}">
                				<td>{counter}</td>
                				<td><a href="panel/formularz-menu/id/{$a_element.id_menu}/rodzaj_menu/podmenu">{$a_element.name}</a></td>
                				<td><input type="checkbox" class="zmien_widocznosc_menu" {if $a_element.is_visible==1}checked="checked"{/if}></td>
                				<!--<td><a href="#" class="czyUsunacMenu">Usuń</a></td>-->
                			</tr>
                        {/if}
                    {/foreach}
        		</tbody>
        	</table>
	   {/if}
    {/foreach}
</form>