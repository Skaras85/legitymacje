<a href="panel"><h2 id="panelTitle" title="Powrót">Zarządzaj kategoriami z karty dań</h2></a>

<form method="post" action="index.php">
    {if $a_kategorie!=false}
    <table class="dishes_categories" class="trHover">
        <thead>
            <tr>
                <th>L.p</th>
                <th>Edycja</th>
                <th>Czy widoczny?</th>
                <th>Usuń</th>
            </tr>
        </thead>
        <tbody>
            {foreach $a_kategorie as $a_kategoria}
                <tr class="sortElement" data-category-id="{$a_kategoria.id_dishes_categories}">
                    <td>{counter}</td>
                    <td><a href="panel/formularz-edycji-kategorii-dan/{$a_kategoria.id_dishes_categories}">{$a_kategoria.title}</a></td>
                    <td><input type="checkbox" class="zmien_widocznosc_kategorii_dan" {if $a_kategoria.is_visible==1}checked="checked"{/if}></td>
                    <td><a href="#" class="czyUsunacKategorieDan">Usuń</a></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    {else}
        <p class="communicat_error">Brak kategorii</p>
    {/if}
</form>