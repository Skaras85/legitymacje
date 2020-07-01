<select name="miesiac" id="miesiac" class="jRequired autoWidth">
    {if isset($wszystkie_miesiace)}
        <option value="0" {if isset($wybrany_miesiac) && $wybrany_miesiac==0}selected{/if}>wszystkie</option>
    {/if}
    <option value="01" {if isset($wybrany_miesiac) && $wybrany_miesiac==1}selected{/if}>styczeń</option>
    <option value="02" {if isset($wybrany_miesiac) && $wybrany_miesiac==2}selected{/if}>luty</option>
    <option value="03" {if isset($wybrany_miesiac) && $wybrany_miesiac==3}selected{/if}>marzec</option>
    <option value="04" {if isset($wybrany_miesiac) && $wybrany_miesiac==4}selected{/if}>kwiecień</option>
    <option value="05" {if isset($wybrany_miesiac) && $wybrany_miesiac==5}selected{/if}>maj</option>
    <option value="06" {if isset($wybrany_miesiac) && $wybrany_miesiac==6}selected{/if}>czerwiec</option>
    <option value="07" {if isset($wybrany_miesiac) && $wybrany_miesiac==7}selected{/if}>lipiec</option>
    <option value="08" {if isset($wybrany_miesiac) && $wybrany_miesiac==8}selected{/if}>sierpień</option>
    <option value="09" {if isset($wybrany_miesiac) && $wybrany_miesiac==9}selected{/if}>wrzesień</option>
    <option value="10" {if isset($wybrany_miesiac) && $wybrany_miesiac==10}selected{/if}>październik</option>
    <option value="11" {if isset($wybrany_miesiac) && $wybrany_miesiac==11}selected{/if}>listopad</option>
    <option value="12" {if isset($wybrany_miesiac) && $wybrany_miesiac==12}selected{/if}>grudzień</option>
</select>