<section>
	<h1>Subkonta <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="72"></h1>
	<a href="{$_base_url}users/formularz-uzytkownika/czy_subkonto/tak" class="button green buttonIcon dodajButton">Dodaj</a>
	{if $a_subkonta}
		<ul class="marginTop20">
			{foreach $a_subkonta as $a_subkonto}
				<li><a href="{$_base_url}users/formularz-uzytkownika/id/{$a_subkonto.id_users}/czy_subkonto/tak">{$a_subkonto.nazwisko} {$a_subkonto.imie}</a></li>
			{/foreach}
		</ul>
	{/if}
</section>