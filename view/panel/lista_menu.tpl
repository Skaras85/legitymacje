<a href="panel/panell"><h2 id="panelTitle" title="Powrót">Zarządzaj menu</h2></a>
<span class="tooltip" title="<p>Kolejność wyświetlania pozycji w menu można zmieniać przesuwając je między sobą</p>"></span>
<form method="post" action="index.php">
    {if $a_menu!=false}
    <table class="panelTabela menus trHover">
        <thead>
            <tr>
                <th>L.p</th>
                <th>Edycja</th>
                <th>Czy widoczny?</th>
                <!--<th>Usuń</th>-->
            </tr>
        </thead>
        <tbody>
            {foreach $a_menu as $a_element}
                <tr class="sortElement" data-menu-id="{$a_element.id_menu}">
                    <td>{counter}</td>
                    <td><a href="panel/formularz-menu/id/{$a_element.id_menu}/rodzaj_menu/menu">{$a_element.name}</a></td>
                    <td><input type="checkbox" class="zmien_widocznosc_menu" {if $a_element.is_visible==1}checked="checked"{/if}></td>
                    <!--<td><a href="#" class="czyUsunacMenu">Usuń</a></td>-->
                </tr>
            {/foreach}
        </tbody>
    </table>
    {else}
        <p class="communicat_error">Brak menu</p>
    {/if}
</form>