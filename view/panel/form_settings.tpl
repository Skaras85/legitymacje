<a href="panel"><h2 id="panelTitle">Zarządzaj ustawieniami strony</h2></a>

<form method="post" action="{$_base_url}" enctype="multipart/form-data" class="jValidate">
    
    <input type="hidden" name="module" value="panel">
    <input type="hidden" name="action" value="edit_settings">

    <section class="jTabs">
        <nav>
            <ul>
                <li><a href="#mainsite" class="chosenTab">Strona główna</a></li>
                <li><a href="#seo" class="chosenTab">SEO</a></li>
                <li><a href="#background">Tło strony</a></li>
            </ul>
        </nav> 
        <div>   
            <article id="seo" class="">
                <fieldset class="fullWidth">
                    <label for="keywords" class="tooltip" title="<p>Podaj frazy oddzielone przecinkami.<br><i>Np. podróżne po Europie, wycieczki rowerowe, czynny odpoczynek, relaks...</i><br><strong>Uwaga!</strong> Obecnie słowa kluczowe mają znikomy wpływ na pozycjonowanie. Niemniej jednak zaleca się ich wpisanie.</p>">Frazy kluczowe (8-10 fraz)</label>
                    <input type="text" class="jRequired" id="tytul" name="a_settings[keywords]" value="{$form_keywords}">
            
                    <label for="title" class="tooltip" title="<p>Tytuł ten będzie widoczny w wynikach wyszukiwania Google i będzie linkiem prowadzącym do strony głównej. Gdy tytuł będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe.<br><i>Np. Wycieczki po Europie - wycieczkowo.pl</i></p>">Tytuł (kilka słów o zawartości strony)</label>
                    <input type="text" class="jRequired" id="title" name="a_settings[title]" value="{$form_title}">
            
                    <label for="description" class="tooltip" title="<p>Opis będzie widoczny w wynikach wyszukiwania Google pod linkiem prowadzącym do strony. Gdy opis będzie zbyt długi zostanie ucięty. Najlepiej zawrzeć w nim najistotniejsze frazy kluczowe.</p>">Opis (ok. dwóch niedługich zdań)</label>
                    <input type="text" class="jRequired" id="description" name="a_settings[description]" value="{$form_description}">
                </fieldset>
            </article>
            <article id="mainsite" class="tabHidden">
                <fieldset class="fullWidth">
                    
                    <input type="checkbox" id="czy_slider" class="iCheck" name="a_settings[is_slider_visible]" {if $form_is_slider_visible=='tak'}checked{/if}><label for="czy_slider">Slider widoczny?</label>
                    
                    <div class="hidden">
                        <label for="number_of_news_on_mainsite">Liczba newsów na stronie głównej</label>
                        <input type="text" class="jRequired jID" id="number_of_news_on_mainsite" name="a_settings[number_of_news_on_mainsite]" value="{$form_number_of_news_on_mainsite}">
                    </div>
                    <label for="slider_transition">Rodzaj przejścia między slidami</label>
                    <select name="a_settings[slider_transition]" id="slider_transition">
                        <option {if $form_slider_transition=='fade'}selected{/if}>fade</option>
                        <option {if $form_slider_transition=='slide'}selected{/if}>slide</option>
                    </select>

                    <!--<select name="a_settings[slider_transition]" id="slider_transition">
                        <option {if $form_slider_transition=='blindX'}selected{/if}>blindX</option>
                        <option {if $form_slider_transition=='blindY'}selected{/if}>blindY</option>
                        <option {if $form_slider_transition=='blindZ'}selected{/if}>blindZ</option>
                        <option {if $form_slider_transition=='cover'}selected{/if}>cover</option>
                        <option {if $form_slider_transition=='curtainX'}selected{/if}>curtainX</option>
                        <option {if $form_slider_transition=='curtainY'}selected{/if}>curtainY</option>
                        <option {if $form_slider_transition=='fade'}selected{/if}>fade</option>
                        <option {if $form_slider_transition=='fadeZoom'}selected{/if}>fadeZoom</option>
                        <option {if $form_slider_transition=='growX'}selected{/if}>growX</option>
                        <option {if $form_slider_transition=='growY'}selected{/if}>growY</option>
                        <option {if $form_slider_transition=='none'}selected{/if}>none</option>
                        <option {if $form_slider_transition=='scrollUp'}selected{/if}>scrollUp</option>
                        <option {if $form_slider_transition=='scrollDown'}selected{/if}>scrollDown</option>
                        <option {if $form_slider_transition=='scrollLeft'}selected{/if}>scrollLeft</option>
                        <option {if $form_slider_transition=='scrollRight'}selected{/if}>scrollRight</option>
                        <option {if $form_slider_transition=='scrollHorz'}selected{/if}>scrollHorz</option>
                        <option {if $form_slider_transition=='scrollVert'}selected{/if}>scrollVert</option>
                        <option {if $form_slider_transition=='shuffle'}selected{/if}>shuffle</option>
                        <option {if $form_slider_transition=='slideX'}selected{/if}>slideX</option>
                        <option {if $form_slider_transition=='slideY'}selected{/if}>slideY</option>
                        <option {if $form_slider_transition=='toss'}selected{/if}>toss</option>
                        <option {if $form_slider_transition=='turnUp'}selected{/if}>turnUp</option>
                        <option {if $form_slider_transition=='turnDown'}selected{/if}>turnDown</option>
                        <option {if $form_slider_transition=='turnLeft'}selected{/if}>turnLeft</option>
                        <option {if $form_slider_transition=='turnRight'}selected{/if}>turnRight</option>
                        <option {if $form_slider_transition=='uncover'}selected{/if}>uncover</option>
                        <option {if $form_slider_transition=='wipe'}selected{/if}>wipe</option>
                        <option {if $form_slider_transition=='zoom'}selected{/if}>zoom</option>
                    </select>-->
                    <label for="slider_speed_of_transition">Czas pokazywania slidu (w milisekundach)</label>
                    <input type="text" class="jRequired jID" id="slider_speed_of_transition" name="a_settings[slider_speed_of_transition]" value="{$form_slider_speed_of_transition}">
                
                    <input type="checkbox" id="is_sticky_header" class="iCheck" name="a_settings[is_sticky_header]" {if $form_is_sticky_header=='tak'}checked{/if}><label for="is_sticky_header">Czy header ruchomy?</label>
                    <input type="checkbox" id="is_go_to_top_button" class="iCheck" name="a_settings[is_go_to_top_button]" {if $form_is_go_to_top_button=='tak'}checked{/if}><label for="is_go_to_top_button">Czy widoczny guzik "Go to top"?</label>
                    <input type="checkbox" id="is_cookie_comm" class="iCheck" name="a_settings[is_cookie_comm]" {if $form_is_cookie_comm=='tak'}checked{/if}><label for="is_cookie_comm">Czy widoczny komunikat o polityce cookies?</label>
                    
                </fieldset>
            </article>
            <article id="background" class="tabHidden">
                <fieldset id="background" class="fullWidth">
                    <label for="form_image">Obraz tła</label>
                    <input data-extensions="jpg jpeg gif png" type="file" name="image" id="form_image" class="jExtension">
                    
                    <div>
                    <input type="checkbox" name="a_settings[is_image]" id="is_image" class="iCheck" {if $form_is_bg_image=='tak'}checked{/if}>
                    <label for="is_image">Tło obrazkowe?</label>
                    </div>
                    
                    <label for="bg_color">Kolor tła</label>
                    <input type="text" name="a_settings[bg_color]" value="{$form_bg_color}" id="bg_color" style="width: 300px" class="colorpicker">
                    <label for="bg_position">Pozycja tła</label>
                    <select name="a_settings[bg_position]" id="bg_position">
                        <option value="top left" {if $form_bg_position=='top left'}selected{/if}>Góra lewo</option>
                        <option value="top center" {if $form_bg_position=='top center'}selected{/if}>Góra środek</option>
                        <option value="top right" {if $form_bg_position=='top right'}selected{/if}>Góra prawo</option>
                        <option value="center" {if $form_bg_position=='center'}selected{/if}>Środek</option>
                        <option value="bottom left" {if $form_bg_position=='bottom left'}selected{/if}>Dół lewo</option>
                        <option value="bottom center" {if $form_bg_position=='bottom center'}selected{/if}>Dół środek</option>
                        <option value="bottom right" {if $form_bg_position=='bottom right'}selected{/if}>Dół prawo</option>
                    </select>
                    <label for="bg_repeat">Powtarzanie tła</label>
                    <select name="a_settings[bg_repeat]" id="bg_repeat">
                        <option value="no-repeat" {if $form_bg_repeat=='no-repeat'}selected{/if}>Nie powtarzaj</option>
                        <option value="repeat-x" {if $form_bg_repeat=='repeat-x'}selected{/if}>Powtarzaj poziomo</option>
                        <option value="repeat-y" {if $form_bg_repeat=='repeat-y'}selected{/if}>Powtarzaj pionowo</option>
                        <option value="repeat" {if $form_bg_repeat=='repeat'}selected{/if}>Potarzaj</option>
                    </select>
                    <label for="bg_attachment">Przywiązanie tła</label>
                    <select name="a_settings[bg_attachment]" id="bg_attachment">
                        <option value="scroll" {if $form_bg_attachment=='scroll'}selected{/if}>Stałe</option>
                        <option value="fixed" {if $form_bg_attachment=='fixed'}selected{/if}>Przyklejone</option>
                    </select>
                    <label for="bg_size">Rozmiar tła</label>
                    <select name="a_settings[bg_size]" id="bg_size">
                        <option value="auto" {if $form_bg_size=='auto'}selected{/if}>Domyślny</option>
                        <option value="100% auto" {if $form_bg_size=='100% auto'}selected{/if}>Rozciągnięty do szerokości strony</option>
                        <option value="auto 100%" {if $form_bg_size=='auto 100%'}selected{/if}>Rozciągnięty do wysokości strony</option>
                        <option value="100% 100%" {if $form_bg_size=='100% 100%'}selected{/if}>Rozciągnięty na całą stronę</option>
                    </select>
                </fieldset>
            </article>
        </div>
        <input type="submit" value="Zapisz">
    </section>

</form>
