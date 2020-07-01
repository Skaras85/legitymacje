<a href="panel"><h2 id="panelTitle" title="Powrót">Zarządzaj daniami</h2></a>
<span class="tooltip" title="<p>Kolejność wyświetlania dań w karcie można zmieniać przesuwając je między sobą</p>"></span>
<form method="post" action="index.php">
    {foreach $a_kategorie as $a_kategoria}
        <table class="dishes trHover">
            <thead>
                <tr>
                    <th colspan="4">{$a_kategoria.title}</th>
                </tr>
                <tr>
                    <th>L.p</th>
                    <th>Edycja</th>
                    <th>Czy widoczny?</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody>
                {foreach $a_dania as $a_danie}
                    {if $a_danie.id_dishes_categories==$a_kategoria.id_dishes_categories}
                        <tr class="sortElement" data-dish-id="{$a_danie.id_dishes}">
                            <td>{counter}</td>
                            <td><a href="panel/formularz-dania/id/{$a_danie.id_dishes}">{$a_danie.name}</a></td>
                            <td><input type="checkbox" class="zmien_widocznosc_dania" {if $a_danie.is_visible==1}checked="checked"{/if}></td>
                            <td><a href="#" class="czyUsunacDanie">Usuń</a></td>
                        </tr>
                    {/if}
                {/foreach}
            </tbody>
        </table>
    {/foreach}
</form>