<a href="{if isset($a_slide)}panel/lista-slidow{else}panel{/if}"><h2 id="panelTitle" title="Powrót">{if isset($a_slide)}Edytuj slide{else}Dodaj slide{/if}</h2></a>

<form method="post" action="index.php" enctype="multipart/form-data" class="jValidate">
	<fieldset class="fullWidth">
		<input type="hidden" name="action" value="{if isset($a_slide)}edytuj_slide{else}dodaj_slide{/if}">
		<input type="hidden" name="module" value="panel">
		<input type="hidden" name="a_slide[id_slides]" value="{if isset($a_slide)}{$a_slide.id_slides}{/if}">

        <section class="jTabs">
        <nav>
            <ul>
                <li><a href="#podstawowe" class="chosenTab">Podstawowe</a></li>
                {if $a_langs>0}<li><a href="#jezyki">Języki</a></li>{/if}
            </ul>
        </nav>
        <div> 
            <article id="podstawowe">
                <fieldset class="fullWidth">
            		<label for="form_tytul">Tytuł:</label>
            		<input type="text" name="a_slide[title]" id="form_tytul" value="{if isset($a_slide) && !isset($smarty.session.form.a_slide)}{$a_slide.title}{/if}{if isset($smarty.session.form.a_slide)}{$smarty.session.form.a_slide.title}{/if}" class="jRequired">
            		
            		<label for="form_link" class="tooltip" title="<p>Wklej link (strony, podstrony), do której użytkownik zostanie przeniesiony po kliknięciu slide'u</p>">Link:</label>
            		<input type="text" name="a_slide[link]" id="form_link" value="{if isset($a_slide) && !isset($smarty.session.form.a_slide)}{$a_slide.link}{/if}{if isset($smarty.session.form.a_slide)}{$smarty.session.form.a_slide.link}{/if}" class="">
            
            		<label for="form_image">Zdjęcie: <!--(wymiary 235x235px, inaczej zostanie przeskalowane, co może mieć wpływ na jakość. Do zmiany rozmiaru zdjęcia możesz wykorzystać <a href="http://www.online-image-editor.com/?language=polish" target="_blank">tą stronę</a>)--></label>
            		<input data-extensions="jpg jpeg gif png" type="file" name="image" id="form_image" class="jExtension">
            		{if isset($a_slide)}<br><br><img src="{$a_slide.img}" alt="" style="max-width: 100%"><br><br>{/if}
                    <!--<label for="date_from">Widoczny od:</label>
                    <input type="text" name="a_slide[date_from]" class="datePicker" id="date_from" value="{if isset($a_slide) && !isset($smarty.session.form.a_slide)}{$a_slide.date_from}{/if}{if isset($smarty.session.form.a_slide)}{$smarty.session.form.a_slide.date_from}{/if}"> 
            	    <label for="date_to">Widoczny do:</label>
                    <input type="text" name="a_slide[date_to]" class="datePicker" id="date_to" value="{if isset($a_slide) && !isset($smarty.session.form.a_slide)}{$a_slide.date_to}{/if}{if isset($smarty.session.form.a_slide)}{$smarty.session.form.a_slide.date_to}{/if}"> 
            	   -->
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
                                    <input name="a_slide[title_{$a_lang.short}]"  id="title_{$a_lang.short}" type="text" value="{if isset($a_slide) && !isset($smarty.session.form.a_slide)}{$a_slide["title_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_slide)}{$smarty.session.form.a_slide["title_`$a_lang.short`"]}{/if}">
                                   
                                    <label for="link_{$a_lang.short}">Link</label>
                                    <input name="a_slide[link_{$a_lang.short}]" id="link_{$a_lang.short}" type="text" value="{if isset($a_slide) && !isset($smarty.session.form.a_slide)}{$a_slide["link_`$a_lang.short`"]}{/if}{if isset($smarty.session.form.a_slide)}{$smarty.session.form.a_slide["link_`$a_lang.short`"]}{/if}">
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
