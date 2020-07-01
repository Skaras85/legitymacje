<a href="{if isset($a_ad)}panel/lista-reklam{else}panel{/if}"><h2 id="panelTitle" title="Powrót">{if isset($a_ad)}Edytuj reklamę{else}Dodaj reklamę{/if}</h2></a>

<form method="post" action="index.php" enctype="multipart/form-data" class="jValidate">
	
	<input type="hidden" name="action" value="{if isset($a_ad)}edytuj_reklame{else}dodaj_reklame{/if}">
    <input type="hidden" name="module" value="panel">
    {if isset($a_ad)}<input type="hidden" name="a_ad[id_ads]" value="{$a_ad.id_ads}">{/if}

	 <section class="jTabs">
	    <nav>
            <ul>
                <li><a href="#ogolne" class="chosenTab">Ogólne</a></li>
                <li><a href="#floater">Floater</a></li>
            </ul> 
        </nav>
        <div>  
            <article id="ogolne">
                <fieldset class="fullWidth">
                    <label for="form_tytul">Tytuł:</label>
                    <input type="text" name="a_ad[title]" id="form_tytul" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.title}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.title}{/if}" class="jRequired">
                    
                    <label for="form_link" class="tooltip" title="<p>Wklej link (strony, podstrony), do której użytkownik zostanie przeniesiony po kliknięciu reklamy</p>">Link:</label>
                    <input type="text" name="a_ad[link]" id="form_link" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.link}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.link}{/if}">
                    
                    <label for="form_tresc">Treść:</label>
                    <textarea name="a_ad[text]" id="tresc">{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.text}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.text}{/if}</textarea>
                    
                    <br><br>
                    <label for="form_image">Zdjęcie:</label>
                    <input data-extensions="jpg jpeg gif png" type="file" name="image" id="form_image" class="jExtension">
                    {if isset($a_ad.img)}<br><br><img src="{$a_ad.img}" alt=""><br><br>
                    <input type="checkbox" name="a_ad[usun_zdjecie]" id="usun_zdjecie"> <label for="usun_zdjecie">Usuń zdjęcie</label><br><br>
                    {/if}
                    
                    <label for="bg_color">Kolor tła</label>
                    <input type="text" name="a_ad[bg_color]" value="{$a_ad.bg_color}" id="bg_color" style="width: 300px" class="colorpicker">
                    
                    <label for="form_type">Typ:</label>
                    <select name="a_ad[type]" id="form_type">
                        <option value="banner" {if isset($a_ad) && $a_ad.type=='banner'}selected="selected"{/if}{if isset($smarty.session.form.a_ad) && $smarty.session.form.a_ad.type=='banner'}selected="selected"{/if}>Banner</option>
                        <option value="floater" {if isset($a_ad) && $a_ad.type=='floater'}selected="selected"{/if}{if isset($smarty.session.form.a_ad) && $smarty.session.form.a_ad.type=='floater'}selected="selected"{/if}>Floater</option>
                    </select>
                    
                    <label for="form_width">Szerokość:</label>
                    <input type="text" name="a_ad[width]" class="jID" id="form_width" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.width}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.width}{/if}"> 
                    <label for="form_height">Wysokość:</label>
                    <input type="text" name="a_ad[height]" class="jID" id="form_height" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.height}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.height}{/if}"> 
                    
                    <br><br>
                    <input type="checkbox" name="a_ad[is_cookie]" id="form_cookie" {if isset($a_ad) && !isset($smarty.session.form.a_ad) && $a_ad.is_cookie==true}checked{/if}{if isset($smarty.session.form.a_ad) && $smarty.session.form.a_ad.is_cookie==true}checked{/if}> <label for="form_cookie">Pokaż tylko za pierwszym razem</label><br><br>
                    
                    
                    <label for="date_from">Widoczny od:</label>
                    <input type="text" name="a_ad[date_from]" class="datePicker" id="date_from" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.date_from}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.date_from}{/if}"> 
                    <label for="date_to">Widoczny do:</label>
                    <input type="text" name="a_ad[date_to]" class="datePicker" id="date_to" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.date_to}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.date_to}{/if}"> 
                    
                </fieldset>
            </article> 
            <article id="floater" class="tabHidden">
                <fieldset id="floater" class="fullWidth">
                    <label for="form_position_x">Pozycja w poziomie</label>
                    <select name="a_ad[position_x]" id="form_position_x">
                        <option value="left" {if isset($a_ad) && $a_ad.position_x=='left'}selected{/if}>Lewo</option>
                        <option value="right" {if isset($a_ad) && $a_ad.position_x=='right'}selected{/if}>Prawo</option>
                    </select>
                    <label for="form_position_y">Pozycja w pionie</label>
                    <select name="a_ad[position_y]" id="form_position_y">
                        <option value="top" {if isset($a_ad) && $a_ad.position_y=='top'}selected{/if}>Góra</option>
                        <option value="bottom" {if isset($a_ad) && $a_ad.position_y=='bottom'}selected{/if}>Dół</option>
                    </select>
                    <label for="form_attachment">Przywiązanie</label>
                    <select name="a_ad[attachment]" id="form_attachment">
                        <option value="absolute" {if isset($a_ad) && $a_ad.attachment=='absolute'}selected{/if}>Stałe</option>
                        <option value="fixed" {if isset($a_ad) && $a_ad.attachment=='fixed'}selected{/if}>Przyklejone</option>
                    </select>
                    <label for="form_remove_after">Wyłącz po (0 dla nigdy)</label>
                    <input type="text" name="a_ad[remove_after]" id="form_remove_after" value="{if isset($a_ad) && !isset($smarty.session.form.a_ad)}{$a_ad.remove_after}{/if}{if isset($smarty.session.form.a_ad)}{$smarty.session.form.a_ad.remove_after}{/if}" class="jRequired numeric">
                    
                </fieldset>
            </article>
        </div>
        <input type="submit" value="{if isset($a_ad)}Zapisz{else}Dodaj{/if}">
    </section>
</form>

<script type="text/javascript">
{literal}
    CKEDITOR.replace( "tresc",{
        filebrowserBrowseUrl: "{/literal}{$_base_url}{literal}javascript/libs/Filemanager-master/index.html"
    });
{/literal}
</script>
