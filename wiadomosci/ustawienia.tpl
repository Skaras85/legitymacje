<p class="h2">{lang::get('wiadomosci-ustawienia-header')}</p>

<form method="post" action="{$_base_url}wiadomosci/zapisz_ustawienia">
    <fieldset>
        <input type="radio" class="iCheck" name="a_ustawienia[czy_wyslac_maila]" value="czy_wyslac_maila" id="czy_wyslac_maila" {if $czy_wyslac_maila==1}checked{/if}> <label for="czy_wyslac_maila">{lang::get('wiadomosci-ustawienia-czy-wyslac-maila')}</label><br>
        <input type="radio" class="iCheck" name="a_ustawienia[czy_wyslac_maila]" value="czy_wyslac_maila_cron" id="czy_wyslac_maila_cron" {if $czy_wyslac_maila_cron==1}checked{/if}> <label for="czy_wyslac_maila_cron">{lang::get('wiadomosci-ustawienia-czy-wyslac-maila-cron')}</label>
        <input type="submit" value="{lang::get('wiadomosci-ustawienia-zapisz')}">
    </fieldset>
</form>