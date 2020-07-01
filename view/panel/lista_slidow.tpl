<a href="panel"><h2 id="panelTitle" title="Powrót">Zarządzaj slidami</h2></a>
<span class="tooltip" title="Kolejność slidów można zmieniać przesuwając je między sobą">Pomoc</span>
<form method="post" action="index.php">

    	<table id="slides" class="trHover">
    		<thead>
    			<tr>
    				<th>L.p</th>
    				<th>Zdjęcie</th>
    				<th>Tytuł</th>
    				<th>Edycja</th>
    				<th>Czy widoczny?</th>
    				<th>Usuń</th>
    			</tr>
    		</thead>
    		<tbody>
    		    {if $a_slides}
        			{foreach $a_slides as $a_slide}
            			<tr class="sortElement" data-slide-id="{$a_slide.id_slides}">
            				<td>{counter}</td>
            				<td><img src="{$a_slide.img}" style="width: 100px"></td>
            				<td>{$a_slide.title}</td>
            				<td><a href="panel/formularz-edycji-slidu/id/{$a_slide.id_slides}">Edytuj</a></td>
            				<td><input type="checkbox" class="zmien_widocznosc_slidu" {if $a_slide.is_visible==1}checked="checked"{/if}></td>
            				<td><a href="#" class="czyUsunacSlide">Usuń</a></td>
            			</tr>
                    {/foreach}	
                {/if}
    		</tbody>
    	</table>

</form>