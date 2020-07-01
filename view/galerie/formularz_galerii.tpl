<a href="{if isset($a_galeria)}galerie/lista-galerii{else}panel{/if}"><h2 id="panelTitle">{if isset($a_galeria)}Edycja galerii zdjęć{else}Dodaj galerię zdjęć{/if}</h2></a>

<form method="post" action="index.php" class="jValidate">
    <input type="hidden" name="module" value="galerie">
    <input type="hidden" name="action" value="{if isset($a_galeria)}edytuj_galerie_wszystko{else}dodaj_galerie{/if}">
    {if isset($a_galeria)}<input type="hidden" name="a_galeria[id_galleries]" value="{$a_galeria.id_galleries}">{/if}
    
    <section class="jTabs">
        <nav>
            <ul>
                <li><a href="#podstawowe" class="chosenTab">Podstawowe</a></li>
                <li><a href="#seo">SEO</a></li>
                <!--<li><a href="#inne">Inne</a></li>-->
                {if $a_langs>0}<li><a href="#jezyki">Języki</a></li>{/if}
            </ul>
        </nav>
        <div> 
            <article id="podstawowe">
                <fieldset class="fullWidth">
                    <label for="nazwa">Wpisz tytuł</label>
                    <input type="text" class="jRequired sludgeSource copySource" data-sludge=".sludge" data-copy-to="#seo_title" autofocus="autofocus" id="tytul" name="a_galeria[title]" value="{if isset($a_galeria)}{$a_galeria.title}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.title}{/if}">
                    <label for="sludge" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. galerie/pokaz/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
                    <input type="text" class="jRequired jAlfanum sludge" id="sludge" name="a_galeria[sludge]" value="{if isset($a_galeria)}{$a_galeria.sludge}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.sludge}{/if}">
                    
                </fieldset>
            </article>
            <article id="seo" class="tabHidden">
                <fieldset class="fullWidth">
                    <label for="seo_keywords" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                    <input name="a_galeria[seo_keywords]" id="seo_keywords" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria.seo_keywords}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.seo_keywords}{/if}">
                    
                    <label for="seo_title" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do tej podstrony. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                    <input name="a_galeria[seo_title]" id="seo_title" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria.seo_title}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.seo_title}{/if}">
                    
                    <label for="seo_description" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do podstrony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.</p>">Opis (ok. dwóch niedługich zdań)</label>
                    <input name="a_galeria[seo_description]" id="seo_description" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria.seo_description}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.seo_description}{/if}">
                    
                </fieldset>
            </article>
            <article id="inne" class="tabHidden">
                <fieldset class="fullWidth">
                    <label for="tags" class="tooltip" title="<p>Tagi pomagają wyszukiwać materiały o podobnych treściach wewnątrz strony.</p>">Tagi</label>
                    <input name="a_galeria[tags]" id="tags" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria.tags}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.tags}{/if}">
                    
                    <label for="data_dodania">Data (puste dla daty obecnej)</label>
                    <input name="a_galeria[add_date]" id="data_dodania" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria.add_date|substr:0:10}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.add_date}{/if}">
                
                    
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
                                        <input name="a_galeria[title_{$a_lang.short}]" class="sludgeSource copySource" data-sludge=".sludge_{$a_lang.short}" data-copy-to="#seo_title_{$a_lang.short}" id="title_{$a_lang.short}" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria["title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_galeria)}{$title_session}{/if}">
                                        <label for="sludge_{$a_lang.short}" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. sites/artykul/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
                                        <input class="jAlfanumStrict sludge_EN" name="a_galeria[sludge_{$a_lang.short}]" id="sludge_{$a_lang.short}" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria["sludge_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.sludge}{/if}">
                                        
                                        <label for="seo_keywords_{$a_lang.short}" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                                        <input name="a_galeria[seo_keywords_{$a_lang.short}]" id="seo_keywords_{$a_lang.short}" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria["seo_keywords_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.seo_keywords}{/if}">
                                        
                                        <label for="seo_title_{$a_lang.short}" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do tej podstrony. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                                        <input name="a_galeria[seo_title_{$a_lang.short}]" id="seo_title_{$a_lang.short}" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria["seo_title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.seo_title}{/if}">
                                        
                                        <label for="seo_description_{$a_lang.short}" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do podstrony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.</p>">Opis (ok. dwóch niedługich zdań)</label>
                                        <input name="a_galeria[seo_description_{$a_lang.short}]" id="seo_description_{$a_lang.short}" type="text" value="{if isset($a_galeria) && !isset($smarty.session.form.a_galeria)}{$a_galeria["seo_description_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_galeria)}{$smarty.session.form.a_galeria.seo_description}{/if}">
                                   </fieldset>
                               </article>
                            {/foreach}
                        </div>
                </article>
            {/if}
        </div>
        <input type="submit" value="{if isset($a_galeria)}Zapisz{else}Dodaj{/if}">
    </section>
</form>
