<a href="{if isset($a_strona)}strony/lista-stron{else}panel{/if}"><h2 id="panelTitle" title="Powrót">{if isset($a_strona)}Edytuj stronę {$a_strona.title}{else}Dodaj nową stronę{/if}</h2></a>

<form id="form" method="post" action="{$_base_url}" class="jValidate" enctype="multipart/form-data">
	<input name="action" value="{if isset($a_strona)==true}edit{else}add{/if}" type="hidden">
	<input name="module" value="strony" type="hidden">
	<input name="a_strona[id_sites]" value="{if isset($a_strona)}{$a_strona.id_sites}{/if}" type="hidden">
	
	<section class="jTabs tabsVertical">
	    <nav>
            <ul>
                <li><a href="#podstawowe" class="chosenTab">Podstawowe</a></li>
                <li><a href="#seo">SEO</a></li>
                <li><a href="#inne">Inne</a></li>
                {if $a_langs>0}<li><a href="#jezyki">Języki</a></li>{/if}
            </ul>
        </nav>
        <div> 
            <article id="podstawowe">
            	<fieldset class="fullWidth">
            		<label for="title">Tytuł</label>
            		<input class="jRequired sludgeSource" data-sludge=".sludge" data-copy-to="#seo_title" autofocus="autofocus" name="a_strona[title]" id="title" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.title}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.title}{/if}">
            		<label for="sludge" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. sites/artykul/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
            		<input class="jRequired jAlfanumStrict sludge" name="a_strona[sludge]" id="sludge" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.sludge}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.sludge}{/if}">
            		<div class="">
            		<label for="kategoria_artykulu">Kategoria artykułu</label>
                        <select name="a_strona[id_article_categories]" id="kategoria_artykulu">
                            <option value="0" {if isset($a_strona) && $a_strona.id_article_categories==0}selected{/if}{if isset($smarty.session.form.a_strona) && $smarty.session.form.a_strona.id_article_categories==0}selected{/if}>Nieprzypisana</option>
                            {if $a_article_categories}
                                {foreach $a_article_categories as $a_kategoria}
                                    <option value="{$a_kategoria.id_article_categories}" {if isset($a_strona) && $a_strona.id_article_categories==$a_kategoria.id_article_categories}selected{/if}{if isset($smarty.session.form.a_strona) && $smarty.session.form.a_strona.id_article_categories==$a_kategoria.id_article_categories || (!isset($a_strona))}selected{/if}>{$a_kategoria.title}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </div>
                    
            		<label for="tresc">Treść</label>
            		<textarea name="a_strona[text]" id="tresc">{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.text}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.text}{/if}</textarea>
            		<br><br>
        		    <label for="zachecacz">Wstęp</label>
        		    <textarea name="a_strona[appetizer]" id="zachecacz">{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.appetizer}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.appetizer}{/if}</textarea>
            	    <br><br>

            	    <label for="zdjecie">{if isset($a_strona) && $a_strona.img!=''}Zmień zdjęcie profilowe, aktualne: {else}Zdjęcie profilowe{/if} (600x360)</label>
            	    {if isset($a_strona) && $a_strona.img!=''}<img src="{$a_strona.img}" width="400"><br>{/if}
            	    <input type="file" name="zdjecie" id="zdjecie" class="jExtension" data-extensions="jpg jpeg gif png">

            	    <div class="hidden">
            	       <label for="liczba_osob">Liczba osób</label>
                       <input class="" name="a_strona[liczba_osob]" id="liczba_osob" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.liczba_osob}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.liczba_osob}{/if}">
                    
                       <label for="czas">Czas trwania</label>
                       <input class="" name="a_strona[czas]" id="czas" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.czas}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.czas}{/if}">
                        
                       <label for="cena">Cena</label>
                       <input class="jPrice" name="a_strona[cena]" id="cena" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.cena}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.cena}{/if}">
                        
                       <label for="cena_prom">Cena promocyjna (0 jeśli nie ma)</label>
                       <input class="" name="a_strona[cena_prom]" id="cena_prom" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.cena_prom}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.cena_prom}{/if}">
                       
            	    </div>
            	</fieldset>
        	</article>
        	<article class="tabHidden" id="inne">
            	<fieldset class="fullWidth">
            	    <label for="tags" data-autocomplete-url="siusiu" class="tooltip" title="<p>Tagi pomagają wyszukiwać materiały o podobnych treściach wewnątrz strony.</p>">Tagi</label>
                    <input name="a_strona[tags]" id="tags" class="tagsInput" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.tags}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.tags}{/if}">
                    
            		<label for="data_dodania">Data (puste dla daty obecnej)</label>
            		<input name="a_strona[add_date]" id="data_dodania" class="datePicker" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.add_date|substr:0:10}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.add_date}{/if}">
            	</fieldset>
        	</article>
        	<article class="tabHidden" id="seo">
                <fieldset class="fullWidth">
                    <label for="seo_keywords" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                    <input name="a_strona[seo_keywords]" id="seo_keywords" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.seo_keywords}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.seo_keywords}{/if}">
                    
                    <label for="seo_title" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do tej podstrony. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                    <input name="a_strona[seo_title]" id="seo_title" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.seo_title}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.seo_title}{/if}">
                    
                    <label for="seo_description" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do podstrony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.</p>">Opis (ok. dwóch niedługich zdań)</label>
                    <input name="a_strona[seo_description]" id="seo_description" style="width: 100%" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona.seo_description}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.seo_description}{/if}">
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
                                        <input name="a_strona[title_{$a_lang.short}]" class="sludgeSource copySource" data-sludge=".sludge_{$a_lang.short}" data-copy-to="#seo_title_{$a_lang.short}" id="title_{$a_lang.short}" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona["title_`$a_lang.short`"]}{/if}">
                                        <label for="sludge_{$a_lang.short}" class="tooltip" title="<p>Link, który będzie widoczny w pasku adresu przeglądarki, np. sites/artykul/10/to-jest-wlasnie-przyjazny-link</p>">Przyjazny link</label>
                                        <input class="jAlfanumStrict sludge_EN" name="a_strona[sludge_{$a_lang.short}]" id="sludge_{$a_lang.short}" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["sludge_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona.sludge}{/if}">
                                        <label for="tresc_{$a_lang.short}">Treść</label>
                                        <textarea name="a_strona[text_{$a_lang.short}]" id="tresc_{$a_lang.short}">{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["text_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona["text_`$a_lang.short`"]}{/if}</textarea>
                                        <br><br>
                                        <label for="zachecacz_{$a_lang.short}">Zachęcacz</label>
                                        <textarea name="a_strona[appetizer_{$a_lang.short}]" id="zachecacz_{$a_lang.short}">{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["appetizer_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona["appetizer_`$a_lang.short`"]}{/if}</textarea>
                                   
                                        <label for="seo_keywords_{$a_lang.short}" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                                        <input name="a_strona[seo_keywords_{$a_lang.short}]" id="seo_keywords_{$a_lang.short}" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["seo_keywords_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona["seo_keywords_`$a_lang.short`"]}{/if}">
                                        
                                        <label for="seo_title_{$a_lang.short}" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do tej podstrony. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                                        <input name="a_strona[seo_title_{$a_lang.short}]" id="seo_title_{$a_lang.short}" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["seo_title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona["seo_title_`$a_lang.short`"]}{/if}">
                                        
                                        <label for="seo_description_{$a_lang.short}" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do podstrony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe dla podstrony.</p>">Opis (ok. dwóch niedługich zdań)</label>
                                        <input name="a_strona[seo_description_{$a_lang.short}]" id="seo_description_{$a_lang.short}" type="text" value="{if isset($a_strona) && !isset($smarty.session.form.a_strona)}{$a_strona["seo_description_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_strona)}{$smarty.session.form.a_strona["seo_description_`$a_lang.short`"]}{/if}">
                                   </fieldset>
                               </article>
                            {/foreach}
                        </div>
                </article>
            {/if}
        </div>
        <input value="{if isset($a_strona)}Zapisz{else}Dodaj{/if}" type="submit">
    </section>
</form>


<script type="text/javascript">
{literal}
    CKEDITOR.replace( "tresc",{
        filebrowserBrowseUrl: "{/literal}{$_base_url}{mod_panel::$js}{literal}/libs/Filemanager-master/index.html",
    });
    CKEDITOR.replace( "zachecacz",{
        filebrowserBrowseUrl: "{/literal}{$_base_url}{mod_panel::$js}{literal}/libs/Filemanager-master/index.html",
        height: "150px"
    });
{/literal}
</script>
{foreach $a_langs as $i=>$a_lang}
    <script type="text/javascript">
    {literal}
        CKEDITOR.replace( "tresc_{/literal}{$a_lang.short}{literal}",{
            filebrowserBrowseUrl: "{/literal}{$_base_url}{mod_panel::$js}{literal}/libs/Filemanager-master/index.html",
        });
        CKEDITOR.replace( "zachecacz_{/literal}{$a_lang.short}{literal}",{
            filebrowserBrowseUrl: "{/literal}{$_base_url}{mod_panel::$js}{literal}/libs/Filemanager-master/index.html",
            height: "150px"
        });
    {/literal}
    </script>
{/foreach}