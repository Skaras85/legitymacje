<section>
	<h1>Placówki <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="71"></h1>
	{if session::who('admin')}
		<a href="{$_base_url}placowki/admin_lista_placowek" class="button">Zarządzaj placówkami</a>
	{/if}
	
	{if session::get_user('typ')=='placowka' || session::get_user('typ')=='agencja' && $czy_potwierdzone_umowy}
		<a href="{$_base_url}placowki/formularz-placowki" class="button buttonIcon green dodajButton">Dodaj</a>
	{/if}
	{if session::get_user('typ')=='agencja' && !$czy_potwierdzone_umowy}
		<p class="communicat_info">Aby dodać placówkę musisz najpierw zawrzeć z nami <a href="{$_base_url}users/umowy">umowę współpracy</a></p>
	{/if}
	{if $a_placowki}
		<input type="text" id="szukaj_placowek" placeholder="Szukaj" autofocus="true" class="marginTop20">
		<ul class="lista_placowek">
			{foreach $a_placowki as $a_placowka}
				<li data-nazwa="{$a_placowka.nazwa_skrocona}">
					<a href="{$_base_url}placowki/placowka/id/{$a_placowka.id_placowki}">
						<p class="nazwa_placowki">{$a_placowka.nazwa_skrocona|substr:0:15}</p>
						<div class="dane_placowki {$a_placowka.status} clearfix">
							<p>{$a_placowka.status}, ID: {$a_placowka.id_placowki}</p>
						</div>
					</a>
				</li>
			{/foreach}
		</ul>
	{else}
		<p class="communicat_info">Proszę dodać placówkę którą reprezentujesz. Po zapisaniu danych, niezbędne będzie wygenerowanie umowy powierzenia danych osobowych oraz ramowej umowy współpracy. Związane jest to  wymogami RODO, które nakładają taki obowiązek zarówno na Administratora jak i na podmiot przetwarzający. </p>
	{/if}
	
</section>