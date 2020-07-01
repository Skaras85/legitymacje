<a href="panel"><h2 id="panelTitle" title="Powrót">Zarządzaj reklamami</h2></a>
<span class="tooltip" title="<p>Kolejność reklam można zmieniać przesuwając je między sobą</p>">Pomoc</span>
<form method="post" action="index.php">

    	<table class="panelTabela ads trHover">
    		<thead>
    		    <tr>
    		        <th colspan="5">Bannery</th>
    		    </tr>
    			<tr>
    				<th>L.p</th>
    				<th>Zdjęcie</th>
    				<th>Edycja</th>
    				<th>Czy widoczny?</th>
    				<th>Usuń</th>
    			</tr>
    		</thead>
    		<tbody>
    		    {if $a_ads_banner_edit}
        			{foreach $a_ads_banner_edit as $a_ad}
        			<tr class="sortElement" data-ad-id="{$a_ad.id_ads}">
        				<td>{counter}</td>
        				<td><img src="{$a_ad.img}" style="width: 100px"></td>
        				<td><a href="panel/formularz-edycji-reklamy/{$a_ad.id_ads}">{$a_ad.title}</a></td>
        				<td><input type="checkbox" class="zmien_widocznosc_reklamy" {if $a_ad.is_visible==1}checked="checked"{/if}></td>
        				<td><a href="#" class="czyUsunacReklame">Usuń</a></td>
        			</tr>
                    {/foreach}	
                {/if}
    		</tbody>
    		<thead>
                <tr>
                    <th colspan="5">Floatery</th>
                </tr>
                <tr>
                    <th>L.p</th>
                    <th>Zdjęcie</th>
                    <th>Edycja</th>
                    <th>Czy widoczny?</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody>
                {if $a_ads_floater_edit}
                    {foreach $a_ads_floater_edit as $a_ad}
                    <tr class="sortElement" data-ad-id="{$a_ad.id_ads}">
                        <td>{counter}</td>
                        <td>{if isset($a_ad.img)}<img src="{$a_ad.img}" style="width: 100px">{/if}</td>
                        <td><a href="panel/formularz-edycji-reklamy/{$a_ad.id_ads}">{$a_ad.title}</a></td>
                        <td><input type="checkbox" class="zmien_widocznosc_reklamy" {if $a_ad.is_visible==1}checked="checked"{/if}></td>
                        <td><a href="#" class="czyUsunacReklame">Usuń</a></td>
                    </tr>
                    {/foreach}  
                {/if}
            </tbody>
    	</table>

</form>