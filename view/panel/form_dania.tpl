<a href="{if isset($a_danie)}panel/lista-dan{else}panel{/if}"><h2 id="panelTitle">{if isset($a_danie)}Edycja dania{else}Dodaj danię{/if}</h2></a>

<form method="post" action="index.php" enctype="multipart/form-data" class="jValidate">
    <input type="hidden" name="module" value="panel">
    <input type="hidden" name="action" value="{if isset($a_danie)}edytuj_danie{else}dodaj_danie{/if}">
    {if isset($a_danie)}<input type="hidden" name="a_danie[id_dishes]" value="{$a_danie.id_dishes}">{/if}
    <input type="hidden" name="edycja" value="{isset($a_danie)}">
    
    <fieldset class="fullWidth">
        <label for="dish_category">Wybierz do której kategorii dań ma należeć to danie</label>
        <select id="dish_category" name="a_danie[id_dishes_categories]" class="jRequired">
            {foreach $a_kategorie_dan as $a_kategoria}
                <option value="{$a_kategoria.id_dishes_categories}" {if isset($a_danie) && $a_kategoria.id_dishes_categories==$a_danie.id_dishes_categories}selected{/if}{if isset($smarty.session.form.a_danie) && $smarty.session.form.a_danie.id_dishes_categories==$a_kategoria.id_dishes_categories}selected{/if}{if isset($saved_id_dishes_categories) && $saved_id_dishes_categories==$a_kategoria.id_dishes_categories}selected{/if}>{$a_kategoria.title}</option>
            {/foreach}
        </select>
        
        <label for="nazwa">Nazwa dania</label>
        <input type="text" autofocus="autofocus" class="jRequired" id="nazwa" name="a_danie[name]" value="{if isset($a_danie) && !isset($smarty.session.form.a_danie)}{$a_danie.name}{/if}{if isset($smarty.session.form.a_danie)}{$smarty.session.form.a_danie.name}{/if}">
        
        <label for="price">Cena (w przypadku wina cena za kieliszek)</label>
        <input type="text" class="jPrice jRequired" id="price" name="a_danie[price]" value="{if isset($a_danie) && !isset($smarty.session.form.a_danie)}{$a_danie.price}{/if}{if isset($smarty.session.form.a_danie)}{$smarty.session.form.a_danie.price}{/if}">
        
        <div{if !isset($a_danie) || $a_danie.id_dishes_categories!=17} class="hidden"{/if} id="hiddenPriceSecond">
            <label for="price_second">Cena za butelkę (tylko dla wina)</label>
            <input type="text" class="jPrice" id="price_second" name="a_danie[price_second]" value="{if isset($a_danie) && !isset($smarty.session.form.a_danie)}{$a_danie.price_second}{/if}{if isset($smarty.session.form.a_danie)}{$smarty.session.form.a_danie.price_second}{/if}">
        </div>
        
        <label for="pl_description">Opis po polsku (niewymagany)</label>
        <input type="text" id="pl_description" name="a_danie[pl_description]" value="{if isset($a_danie) && !isset($smarty.session.form.a_danie)}{$a_danie.pl_description}{/if}{if isset($smarty.session.form.a_danie)}{$smarty.session.form.a_danie.pl_description}{/if}">
        
        <label for="eng_description">Opis po angielsku (niewymagany)</label>
        <input type="text" id="eng_description" name="a_danie[eng_description]" value="{if isset($a_danie) && !isset($smarty.session.form.a_danie)}{$a_danie.eng_description}{/if}{if isset($smarty.session.form.a_danie)}{$smarty.session.form.a_danie.eng_description}{/if}">
        
        <label for="foto">Zdjęcie dania (niewymagane)</label>
        <input type="file" name="foto" id="foto" class="jExtension" data-extensions="jpg jpeg png gif">
        {if isset($a_danie.img)}<br><img src="{$a_danie.img}" alt="">{/if}
        <input type="submit" value="{if isset($a_danie)}Zapisz{else}Dodaj{/if}">
    </fieldset>

</form>

