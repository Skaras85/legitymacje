<p class="commentsHeader">{lang::get('komentarze-header')} (<span class="numberOfComments">{$number_of_comments}</span>):</p>

{if session::is_logged()}
<form method="post" action="index.php" id="form_comments">
    <input name="a_comment[type]" value="{$comments_type}" type="hidden">
    <input name="a_comment[subject_id]" value="{$comments_subject}" type="hidden">
    <input name="module" value="comments" type="hidden">
    <input name="action" value="{if $edycja_komentarza==true}edit{else}add{/if}" type="hidden">
    <fieldset>
        <label for="comment_text">{if $edycja_komentarza==true}{lang::get('komentarz-edytuj')}{else}{lang::get('komentarz-dodaj')}{/if} komentarz</label>
        <nav class="commentBbCodeMenu">
            <ul class="clearfix">
                <li><button class="BbCodeButton" data-type="b" title="Pogrubienie"><b>B</b></button></li>
                <li><button class="BbCodeButton" data-type="i" title="Pochylenie"><i>I</i></button></li>
                <li><button class="BbCodeButton" data-type="u" title="Podkreślenie"><u>U</u></button></li>
                <li><button class="BbCodeButton" data-type="img" title="Wstaw obrazek">img</button></li>
                <li><button class="BbCodeButton" data-type="link" title="Wstaw hiperłącze">link</button></li>
            </ul>
        </nav>
        <textarea name="a_comment[text]" id="comment_text">{if $edycja_komentarza==true}{$a_comment.text}{/if}</textarea>
        <input type="submit" value="{if $edycja_komentarza==true}{lang::get('komentarz-edytuj')}{else}{lang::get('komentarz-dodaj')}{/if} {lang::get('komentarz')}">
    </fieldset>
</form>
{else}
    <p class="communicat_info">{lang::get('komentarze-msg-zalogowany')}</p>
{/if}

<section id="commentsWrapper">
    {$comments_string}
</section>