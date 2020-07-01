<section>
	<h1>Przesyłki <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="74"></h1>
	
	
	
	{if session::who('admin')}
		<a href="{$_base_url}przesylki/lista-placowek" class="button">Placówki</a>
	{/if}
	
	{include file='przesylki/tabela_przesylek.tpl'}
	
</section>