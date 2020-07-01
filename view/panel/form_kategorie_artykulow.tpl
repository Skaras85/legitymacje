<a href="{if isset($a_kategoria)}panel/lista-kategorii-artykulow{else}panel{/if}"><h2 id="panelTitle">{if isset($a_kategoria)}Edycja kategorii artykułu{else}Dodaj kategorię artykułu{/if}</h2></a>

<form method="post" action="index.php" class="jValidate">
    
    <input type="hidden" name="module" value="panel">
    <input type="hidden" name="action" value="{if isset($a_kategoria)}edytuj_kategorie_artykulu{else}dodaj_kategorie_artykulu{/if}">
    {if isset($a_kategoria)}<input type="hidden" name="a_kategoria[id_article_categories]" value="{$a_kategoria.id_article_categories}">{/if}
    
    <section class="jTabs">
        <nav>
            <ul>
                <li><a href="#podstawowe" class="chosenTab">Podstawowe</a></li>
                <li><a href="#seo">SEO</a></li>
                {if $a_langs>0}<li><a href="#jezyki">Języki</a></li>{/if}
            </ul>
        </nav>
        <div>
            <article id="podstawowe">
                <fieldset class="fullWidth">
                    <label for="title">Tytuł</label>
                    <input class="jRequired sludgeSource copySource" data-sludge=".sludge" data-copy-to="#seo_title" autofocus="autofocus" name="a_kategoria[title]" id="title" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria.title}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.title}{/if}">
                    <label for="sludge" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. sites/artykul/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
                    <input class="jRequired jAlfanumStrict sludge" name="a_kategoria[sludge]" id="sludge" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria.sludge}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.sludge}{/if}">
                    
                </fieldset>
            </article>
            <article id="seo" class="tabHidden">
                <fieldset class="fullWidth">
                    <label for="seo_keywords" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                    <input name="a_kategoria[seo_keywords]" id="seo_keywords" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria.seo_keywords}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.seo_keywords}{/if}">
                    
                    <label for="seo_title" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do tej podstrony. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                    <input name="a_kategoria[seo_title]" id="seo_title" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria.seo_title}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.seo_title}{/if}">
                    
                    <label for="seo_description" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do podstrony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.</p>">Opis (ok. dwóch niedługich zdań)</label>
                    <input name="a_kategoria[seo_description]" id="seo_description" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria.seo_description}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.seo_description}{/if}">
                </fieldset>
            </article>
            {if $a_langs>1}
                <article class="tabHidden" id="jezyki">
                    <section class="jTabs">
                        <nav>
                            <ul>
                                {foreach $a_langs as $i=>$a_lang}
                                   <li><a href="#{$a_lang.short}" {if $i==0}class="chosenTab"{/if}>{$a_lang.short}</a></li>
                                {/foreach}
                            </ul>
                        </nav>
                        <div>
                            {foreach $a_langs as $i=>$a_lang}
                               <article id="{$a_lang.short}" {if $i!=0}class="hidden"{/if}>
                                   <fieldset class="fullWidth">
                                        <label for="title_{$a_lang.short}">Tytuł</label>
                                        <input name="a_kategoria[title_{$a_lang.short}]" class="sludgeSource copySource" data-sludge=".sludge_EN" data-copy-to="#seo_title_{$a_lang.short}" id="title_{$a_lang.short}" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria["title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria["title_`$a_lang.short`"]}{/if}">
                                        <label for="sludge_{$a_lang.short}" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. sites/artykul/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
                                        <input class="jAlfanumStrict sludge_EN" name="a_kategoria[sludge_{$a_lang.short}]" id="sludge_{$a_lang.short}" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria["sludge_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria.sludge}{/if}">
                                        
                                        <label for="seo_keywords_{$a_lang.short}" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                                        <input name="a_kategoria[seo_keywords_{$a_lang.short}]" id="seo_keywords_{$a_lang.short}" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria["seo_keywords_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria["seo_keywords_`$a_lang.short`"]}{/if}">
                                        
                                        <label for="seo_title_{$a_lang.short}" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do tej podstrony. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                                        <input name="a_kategoria[seo_title_{$a_lang.short}]" id="seo_title_{$a_lang.short}" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria["seo_title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria["seo_title_`$a_lang.short`"]}{/if}">
                                        
                                        <label for="seo_description_{$a_lang.short}" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do podstrony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.</p>">Opis (ok. dwóch niedługich zdań)</label>
                                        <input name="a_kategoria[seo_description_{$a_lang.short}]" id="seo_description_{$a_lang.short}" type="text" value="{if isset($a_kategoria) && !isset($smarty.session.form.a_kategoria)}{$a_kategoria["seo_description_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_kategoria)}{$smarty.session.form.a_kategoria["seo_description_`$a_lang.short`"]}{/if}">
                                   </fieldset>
                               </article>
                            {/foreach}
                        </div>
                </article>
            {/if}
        </div>
        <input type="submit" value="{if isset($a_kategoria)}Zapisz{else}Dodaj{/if}">
    </section>
</form>
