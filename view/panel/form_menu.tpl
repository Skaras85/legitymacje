<a href="{if isset($a_menu) && $rodzaj_menu=='menu'}panel/lista-menu{else if isset($a_menu) && $rodzaj_menu=='podmenu'}panel/lista-podmenu{else}panel{/if}"><h2 id="panelTitle">{if isset($a_menu)}Edycja {$rodzaj_menu}{else}Dodaj {$rodzaj_menu}{/if}</h2></a>

<form method="post" action="index.php" class="jValidate">
    <input type="hidden" name="module" value="panel">
    <input type="hidden" name="action" value="{if isset($a_menu)}edytuj_menu{else}dodaj_menu{/if}">
    {if isset($a_menu)}<input type="hidden" name="a_menu[id_menu]" value="{$a_menu.id_menu}">{/if}
    <input type="hidden" name="rodzaj_menu" value="{$rodzaj_menu}">
    <input type="hidden" name="edycja" value="{isset($a_menu)}">
    

    <fieldset class="fullWidth">
        <label for="nazwa">Wpisz nazwe</label>
        <input type="text" autofocus="autofocus" class="jRequired" id="nazwa" name="a_menu[name]" value="{if isset($a_menu) && !isset($smarty.session.form.a_menu)}{$a_menu.name}{/if}{if isset($smarty.session.form.a_menu)}{$smarty.session.form.a_menu.name}{/if}">
        
        {foreach $a_langs as $i=>$a_lang}
            <label for="nazwa">Wpisz nazwe {$a_lang.short}</label>
            <input type="text" class="jRequired" id="nazwa" name="a_menu[name_{$a_lang.short}]" value="{if isset($a_menu) && !isset($smarty.session.form.a_menu)}{$a_menu["name_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_menu)}{$smarty.session.form.a_menu.name}{/if}">
        {/foreach}
        
        {if $rodzaj_menu=='menu'}
           <input type="hidden" name="a_menu[parent_id]" value="0">
        {elseif $rodzaj_menu=='podmenu'}
            <label for="rodzic">Wybierz do którego elementu menu ma należeć to podmenu</label>
            <select id="rodzic" name="a_menu[parent_id]" class="jRequired">
                {foreach $a_lista_menu as $a_menu_nadrzedne}
                    <option value="{$a_menu_nadrzedne.id_menu}" {if isset($a_menu) && $a_menu_nadrzedne.id_menu==$a_menu.parent_id}selected{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.parent_id==$a_menu_nadrzedne.id_menu}selected{/if}>{$a_menu_nadrzedne.name}</option>
                {/foreach}
            </select>
        {/if}
    </fieldset>
    <fieldset class="fullWidth">    
        <label for="rodzaj">Wybierz, gdzie ma prowadzić link</label>
        <select id="rodzaj_menu" name="a_menu[type]" class="jRequired">
            <option> </option>
            <option value="main" {if isset($a_menu) && $a_menu.type=='main'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='main'}selected="selected"{/if}>Strona główna</option>
            <option value="site" {if isset($a_menu) && $a_menu.type=='site'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='site'}selected="selected"{/if}>Pojedyncza strona</option>
            <option value="sites_by_category" {if isset($a_menu) && $a_menu.type=='sites_by_category'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='sites_by_category'}selected="selected"{/if}>Lista stron z kategorii</option>
            <option value="galleries" {if isset($a_menu) && $a_menu.type=='galleries'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='galleries'}selected="selected"{/if}>Lista galerii</option>
            <option value="gallery" {if isset($a_menu) && $a_menu.type=='gallery'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='gallery'}selected="selected"{/if}>Pojedyncza galeria</option>
            <!--{if $rodzaj_menu=='menu'}<option value="dishes" {if isset($a_menu) && $a_menu.type=='dishes'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='dishes'}selected="selected"{/if}>Karta dań</option>{/if}-->
            <option value="link" {if isset($a_menu) && $a_menu.type=='link'}selected="selected"{/if}{if isset($smarty.session.form.a_menu) && $smarty.session.form.a_menu.type=='link'}selected="selected"{/if}>Link</option>
        </select>
        <div id="rodzaj_menu_content">
            
        </div>  
        <input type="submit" value="{if isset($a_menu)}Zapisz{else}Dodaj{/if}">
    </fieldset>
</form>

<div id="rodzaj_menu_gallery" class="hidden">
    <label for="id_galerii">Wybierz galerię</label>
    <select id="id_galerii" name="a_menu[id]" class="fullWidth">
        {foreach $a_galerie as $a_galeria}
            <option value="{$a_galeria.id_galleries}" {if isset($a_menu) && $a_menu.type=='gallery' && $a_galeria.id_galleries==$a_menu.id}selected{/if}>{$a_galeria.title}</option>
        {/foreach}
    </select>
</div>

<div id="rodzaj_menu_site" class="hidden">
    <label for="id_artykulu">Wybierz stronę</label>
    <select id="id_artykulu" name="a_menu[id]" class="fullWidth">
        {foreach $a_kategorie as $a_kategoria}
            <optgroup label="{$a_kategoria.title}">
                {foreach $a_sites as $a_site}
                    {if $a_site.id_article_categories==$a_kategoria.id_article_categories}
                        <option value="{$a_site.id_sites}" {if isset($a_menu) && $a_menu.type=='site' && $a_site.id_sites==$a_menu.id}selected{/if}>{$a_site.title}</option>
                    {/if}
                {/foreach}
            </optgroup>
        {/foreach}
        <optgroup label="Nieprzypisane">
            {foreach $a_sites as $a_site}
                {if $a_site.id_article_categories==0}
                    <option value="{$a_site.id_sites}" {if isset($a_menu) && $a_menu.type=='site' && $a_site.id_sites==$a_menu.id}selected{/if}>{$a_site.title}</option>
                {/if}
            {/foreach}
        </optgroup>
    </select>
</div>

<div id="rodzaj_menu_sites_by_category" class="hidden">
    <label for="id_article_categories">Wybierz kategorię artykułów</label>
    <select id="id_article_categories" name="a_menu[id]" class="fullWidth">
        {foreach $a_kategorie as $a_kategoria}
            <option value="{$a_kategoria.id_article_categories}" {if isset($a_menu) && $a_menu.type=='sites_by_category' && $a_kategoria.id_article_categories==$a_menu.id}selected{/if}>{if $a_kategoria.id_article_categories==0}Nieprzypisana{else}{$a_kategoria.title}{/if}</option>
        {/foreach}
    </select>
</div>

<div id="rodzaj_menu_link" class="hidden">
    <label for="form_link">Link</label>
    <input type="text" name="a_menu[link]" id="form_link" value="{if isset($a_menu) && !isset($smarty.session.form.a_menu)}{$a_menu.link}{/if}{if isset($smarty.session.form.a_menu)}{$smarty.session.form.a_menu.link}{/if}">
</div>
