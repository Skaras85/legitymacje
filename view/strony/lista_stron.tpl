<a href="{$_base_url}panel/panell"><h2 id="panelTitle" title="Powrót">Zarządzaj stronami</h2></a>
<span class="tooltip" title="<p>Kolejność wyświetlania stron można zmieniać przesuwając je między sobą</p>">Pomoc</span>
<form method="post" action="index.php">
	{foreach $a_kategorie as $a_kategoria}
	<table class="panelTabela sites trHover">
		<thead>
		    <tr>
		        <th colspan="5">
                    {if $a_kategoria.id_article_categories==0}
                      Nieprzypisane
                    {else}
                      {$a_kategoria.title}
                    {/if}
		        </th>
		    </tr>
			<tr>
				<th>L.p</th>
				<th>Podgląd</th>
				<th>Edycja</th>
				<th>Czy widoczna?</th>
				<!--<th>Usuń</th>-->
			</tr>
		</thead>
		<tbody>
			{foreach $a_sites as $a_site}
			 {if $a_site.id_article_categories==$a_kategoria.id_article_categories}
    			<tr class="sortElement" data-site-id="{$a_site.id_sites}">
    				<td>{counter}</td>
    				<td><a href="{$_base_url}strony/{$a_site.sludge},{$a_site.id_sites}">Podgląd</a></td>
    				<td><a href="{$_base_url}strony/formularz-edycji-strony/id/{$a_site.id_sites}">{$a_site.title}</a></td>
    				<td><input type="checkbox" class="zmien_widocznosc_strony" {if $a_site.is_visible==1} checked="checked"{/if}></td>
    				<!--<td>
    				    {if $a_site.id_sites!=1 && $a_site.id_sites!=6}
    				        <a href="{$_base_url}#" class="czyUsunacStrone">Usuń</a>
    				    {/if}
    				</td>-->
    			</tr>
			{/if}
		  {/foreach}
		</tbody>
	</table>
	{/foreach}
</form>