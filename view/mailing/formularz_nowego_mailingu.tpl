<a href="mailing/panel"><h2 id="panelTitle" title="Powrót">Nowy mailing</h2></a>
<article>
    <label for="szablon">Szablon</label>
    <select id="szablon_mailingu">
        <option value='0'>Wybierz</option>
        {foreach $a_szablony as $a_szablon}
            <option value='{$a_szablon.id_sites}'>{$a_szablon.title}</option>
        {/foreach}
    </select>
    <form action="{$_base_url}/mailing/zapisz_mailing" method="POST" class="fullWidth">
        
        
        <fieldset>
            <legend>Adresaci</legend>
            <input type="checkbox" name="a_mail[adresat_graficy]" class="iCheck" id="adresat_graficy" checked> <label for="adresat_graficy">Graficy</label>
            <input type="checkbox" name="a_mail[adresat_zleceniodawcy]" class="iCheck" id="adresat_zleceniodawcy"> <label for="adresat_zleceniodawcy">Zleceniodawcy</label>
            <input type="checkbox" name="a_mail[adresat_partnerzy]" class="iCheck" id="adresat_partnerzy"> <label for="adresat_partnerzy">Partnerzy</label>
        </fieldset>
        
        <label for="tytul">Tytuł maila</label>
        <input type="text" name="a_mail[tytul]" id="tytul">
        
        <label for="tresc">Treść maila</label>
        <textarea name="a_mail[text]" id="tresc"></textarea>
        
        <input type="button" value="TEST" class="button" id="wyslij_testowy_mailing">
        <input type="submit" value="mailing" id="wyslij_mailing">
    </form>
</article>

<script type="text/javascript">
{literal}
    CKEDITOR.replace( "tresc",{
        filebrowserBrowseUrl: "{/literal}{$_base_url}{$js}{literal}/libs/Filemanager-master/index.html",
    });
{/literal}
</script>