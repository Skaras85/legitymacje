<a href="panel"><h2 id="panelTitle" title="Powrót">Wersje językowe</h2></a>


	 <section class="jTabs">
	    <nav>
            <ul>
                <li><a href="#tlumaczenia" class="chosenTab">Tłumaczenia</a></li>
                <li><a href="#jezyki">Języki</a></li>
            </ul> 
        </nav>
        <div>  
            <article id="tlumaczenia">
                {if $a_langs}
                    <table class="trHover">
                        <tr>
                            <th>Nazwa</th>
                            {foreach $a_langs as $a_lang}
                                <th>{$a_lang.short}</th>
                            {/foreach}
                            <th>Zapisz</th>
                        </tr>
                        {foreach $a_texts as $a_text}
                            <tr id="lang_{$a_text.id_lang_texts}">
                                <form method="POST" action="{$_base_url}panel/save_lang_texts">
                                    <input type="hidden" name="a_text[id_lang_texts]" value="{$a_text.id_lang_texts}">
                                    <td>{$a_text.name} {if $a_text.description!=''}<span class="tooltip" title="<p>{$a_text.description}</p>">Pomoc</span>{/if}</td>
                                    {foreach $a_langs as $a_lang}
                                        <td><input type="text" name="a_text[value_{$a_lang.short}]" value="{if {$a_text["value_`$a_lang.short`"]}}{$a_text["value_`$a_lang.short`"]}{/if}" style="width: 100%"></td>
                                    {/foreach}
                                    <td>
                                        <input type="submit" value="Zapisz" style="width: auto">
                                    </td>
                                </form>
                            </tr>
                        {/foreach}
                    </table>
                {/if}
            </article> 
            <article id="jezyki" class="tabHidden">
                <span class="tooltip" title="<p>Kolejność wyświetlania języków można zmieniać przesuwając je między sobą</p>">Pomoc</span>

                <form method="POST" action="{$_base_url}panel/edit_langs" class="jValidate">
                    {if $a_langs}
                        <table class="panelTabela langs trHover" id="langs">
                            <thead>
                                <tr>
                                    <th>L.p</th>
                                    <th>Nazwa</th>
                                    <th>Skrót</th>
                                    <th>Czy aktywny?</th>
                                    <th>Domyślny</th>
                                    <th>Usuń</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $a_langs as $a_element}
                                    <tr class="sortElement center" data-lang-id="{$a_element.id_langs}">
                                        <td>{counter}</td>
                                        <td><input type="text" name="a_langs[{$a_element.id_langs}][name]" value="{$a_element.name}"></td>
                                        <td><input type="text" name="a_langs[{$a_element.id_langs}][short]" value="{$a_element.short}" class="jAlfanumHC"></td>
                                        <td><input type="checkbox" name="a_langs[{$a_element.id_langs}][is_active]" {if $a_element.is_active==1}checked{/if}></td>
                                        <td><input type="radio" name="id_default" value="{$a_element.id_langs}" {if $a_element.is_default==1}checked{/if}></td>
                                        <td><a href="#" class="czyUsunacJezyk">Usuń</a></td>
                                    </tr>
                                {/foreach}
                                <tr>
                                    <td colspan="6" class="center">
                                        <input type="submit" value="Zapisz">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    {/if}
                </form>
                <form method="POST" action="{$_base_url}panel/add_lang">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="6">Dodaj nowy</th>
                            </tr>
                            <tr>
                                <th>Nazwa</th>
                                <th>Skrót</th>
                                <th>Czy aktywny?</th>
                                <th>Domyślny</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="center">
                                <td><input type="text" name="a_lang[name]"></td>
                                <td><input type="text" name="a_lang[short]" class="jAlfanumHC"></td>
                                <td><input type="checkbox" name="a_lang[is_active]"></td>
                                <td><input type="checkbox" name="a_lang[is_default]"></td>
                                <td><input type="submit" value="Dodaj"></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </article>
        </div>
    </section>
