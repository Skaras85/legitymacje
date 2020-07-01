<section class="formSection">
	<article>
        <h1>{$a_strona.title}</h1>
    	{$a_strona.text}
    	{if $a_strona.id_article_categories==5}
    		<div class="center">
    			{if !$podglad_i_druk}
    				<a href="{$_base_url}users/akceptuj_umowe/id/{$a_strona.id_sites}" class="button big green printHide">Akceptuję i drukuję</a>
    			{else}
    				<a href="#" class="button big green print">Drukuj</a>
    			{/if}
    		</div>
    	{/if}
    	{if $a_strona.id_karty && !$czy_ma_juz_ta_karte}
    		<div class="center">
    			<a href="{$_base_url}legitymacje/dodaj_legitymacje/id_karty/{$a_strona.id_karty}" class="button green">Aktywuj kartę</a>
    		</div>
    	{/if}
	</article>
</section>