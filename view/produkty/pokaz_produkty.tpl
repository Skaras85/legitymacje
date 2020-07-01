<section>
	<h1>{$a_kategoria.nazwa}</h1>
	
	{if session::who('admin')}
		<a href="{$_base_url}karty/formularz-karty/hologram/1" class="button">Dodaj</a>
		<div class="marginTop20"></div>
	{/if}
	
	{if $a_produkty}
		{foreach $a_produkty as $a_produkt}
			<div class="produktWrapper {if !$a_produkt.is_visible}hide{/if}">
				<h2>{$a_produkt.nazwa}</h2>
				{if $a_produkt.img}
					<!--<a href="images/produkty/{$a_produkt.img}" class="fancybox">--><img src="images/produkty/{$a_produkt.img}" alt="{$a_produkt.nazwa}"><!--</a>-->
				{/if}

				{$a_produkt.text}
				<div class="clear"></div>
				<form action="{$_base_url}" method="POST" class="jValidate">
					<input type="hidden" name="id_produkty" value="{$a_produkt.id_produkty}">
					<p>Określ ilość hologramów (liczba musi być wielokrotnością liczby 10)</p>
					<input type="text" name="ilosc" class="autoWidth inline jNumberMultiple liczba_produktow" data-number-multiple="10">
					<input type="submit" value="Zamów" class="button green dodaj_produkt_do_koszyka buttonIcon takButton">
				</form>
				{if session::who('admin')}
					<a href="{$_base_url}karty/formularz-karty/hologram/1/id/{$a_produkt.id_produkty}" class="button">Edytuj</a>
					{if session::get('id_placowki')}
						<a href="{$_base_url}produkty/formularz-cennika/id_produktu/{$a_produkt.id_produkty}" class="button">Cenniki dla placówki</a>
					{/if}
					<a href="{$_base_url}produkty/usun_produkt/id/{$a_produkt.id_produkty}" class="button">Usuń</a>
					<a href="{$_base_url}produkty/zmien_widocznosc_produktu/id/{$a_produkt.id_produkty}" class="button">{if $a_produkt.is_visible}Ukryj{else}Pokaż{/if}</a>
				{/if}
			</div>
		{/foreach}
	{/if}
</section>