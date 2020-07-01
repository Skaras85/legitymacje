<section>
	<h1>Koszyk <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="76"></h1>
	
	{if $tabela || $a_produkty}
		<form action="{$_base_url}" method="POST">
			<input type="hidden" name="module" value="koszyk">
			<input type="hidden" name="action" value="zamow">
			<input type="button" class="button usun_z_koszyka" value="Usuń wybrane z koszyka" title="Usuń z koszyka">
			<!--<a href="{$_base_url}koszyk/zamow" class="button green">Zamów</a>-->
			<input type="submit" value="PRZEJDŹ DALEJ" class="green AutoWidth button buttonIcon dalejButton">
			{if $id_karty!=1 && $czy_placowka_ma_karty_szkolne}
				<a href="{$_base_url}/produkty/pokaz-produkty/id/1" class="button">Hologramy</a>
			{/if}
			<div class="marginTop20"></div>
			{$tabela}
			
			{if session::get('czy_zdalny')}
				{include file='przesylki/tabela_przesylek.tpl'}
			{/if}
		</form>
	{else}
		<p class="communicat_info">Brak elementów w koszyku</p>
	{/if}
	
	{include file='produkty/tabela_produktow.tpl'}
</section>