<section>
	<h1>Karty</h1>
	{if $a_karty}
		<ul class="lista_kart">
			{foreach $a_karty as $a_karta}
				<li>
					<a href="{$_base_url}karty/formularz-karty/id/{$a_karta.id_karty}">
						<img src="images/karty/{$a_karta.img}" alt="">
					</a>
				</li>
			{/foreach}
		</ul>
	{/if}
	<a href="{$_base_url}karty/formularz-karty" class="button">Dodaj</a>
</section>