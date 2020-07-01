<a href="panel"><h2 id="panelTitle" title="Powrót">Zarządzaj kategoriami artykułów</h2></a>

<form method="post" action="index.php">
    {if $a_kategorie!=false}
    <table class="panelTabela article_categories trHover">
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
                <tr class="" data-category-id="{$a_kategoria.id_article_categories}">
                    <td>{counter}</td>
                    <td><a href="panel/formularz-edycji-kategorii-artykulu/id/{$a_kategoria.id_article_categories}">{$a_kategoria.title}</a></td>
                    <td><input type="checkbox" class="zmien_widocznosc_kategorii_artykulu" {if $a_kategoria.is_visible==1}checked="checked"{/if}></td>
                    <td><a href="#" class="czyUsunacKategorieArtykulu">Usuń</a></td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    {else}
        <p class="communicat_error">Brak kategorii</p>
    {/if}
</form>