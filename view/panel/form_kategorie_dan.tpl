<a href="{if isset($a_kategoria)}panel/lista-kategorii-dan{else}panel{/if}"><h2 id="panelTitle">{if isset($a_kategoria)}Edycja kategorii z karty dania{else}Dodaj kategorię do karty dań{/if}</h2></a>

<form method="post" action="index.php" class="jValidate">
    
    <input type="hidden" name="module" value="panel">
    <input type="hidden" name="action" value="{if isset($a_kategoria)}edytuj_kategorie_dan{else}dodaj_kategorie_dan{/if}">
    {if isset($a_kategoria)}<input type="hidden" name="a_kategoria[id_dishes_categories]" value="{$a_kategoria.id_dishes_categories}">{/if}
    
    <fieldset class="fullWidth">
        <label for="nazwa" class="tooltip" title="<p>Np. Przystawki, Dania główne, Wina</p>">Wpisz nazwę</label>
        <input type="text" class="jRequired sludgeSource" id="tytul" name="a_kategoria[title]" value="{if isset($a_kategoria)}{$a_kategoria.title}{/if}{if isset($smarty.session.form.a_kategorie)}{$smarty.session.form.a_kategorie.title}{/if}">
        
        <label for="sludge" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. sites/artykul/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
        <input class="jRequired jAlfanumStrict sludge" name="a_kategoria[sludge]" id="sludge" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria.sludge}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.sludge}{/if}">
        
        <input type="submit" value="{if isset($a_kategoria)}Zapisz{else}Dodaj{/if}">
    </fieldset>
</form>
