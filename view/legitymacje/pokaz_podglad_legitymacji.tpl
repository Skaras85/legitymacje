{if $id_prev}
	<a href="legitymacje/pokaz_podglad_legitymacji/id_legitymacje/{$id_prev}{if $id_zamowienia}/id_zamowienia/{$id_zamowienia}{/if}" class="button left green autoWidth modal buttonIcon wsteczButton" data-width="879px">Poprzednie</a>
{/if}
{if $id_zamowienia && session::get('czy_zdalny')}
	<input type="text" class="autoWidth kod_karty" placeholder="Kod karty" value="{$a_osoba.kod_karty}" data-id-zamowienia="{$id_zamowienia}" data-id-legitymacje="{$id_legitymacji}" autofocus>
{/if}
{if $id_next}
	<a href="legitymacje/pokaz_podglad_legitymacji/id_legitymacje/{$id_next}{if $id_zamowienia}/id_zamowienia/{$id_zamowienia}{/if}" class="button right green autoWidth modal buttonIcon dalejButton" data-width="879px">Następne</a>
{/if}
<div class="podglad_legitymacji_content clear {if $a_karta.id_karty==1}nauczyciela{else}szkolna{/if}">
	{$podglad_karty}
</div>
