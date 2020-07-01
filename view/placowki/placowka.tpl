<section>
	{if $czy_wlasciciel}
		<a href="{$_base_url}placowki/formularz-placowki/id/{$a_placowka.id_placowki}" class="button buttonIcon edytujButton">Edytuj dane</a>
	{/if}

	<!--
		<a href="{$_base_url}legitymacje/formularz-wyboru-karty/id_placowki/{$a_placowka.id_placowki}" class="button">Dodaj legitymację</a>
	-->
	<a href="{$_base_url}placowki/lista-pracodawcow" class="button">Pracodawcy</a>
	<a href="{$_base_url}placowki/lista-pracodawcow/czy_szkoly/1" class="button">Szkoły</a>
	
	{if session::who('admin') || session::get('czy_zdalny')}
		<a href="{$_base_url}users/umowy" class="button buttonIcon umowyButton">Umowy stare</a>
	{/if}
	
	{if session::get_user('typ')=='placowka'}
		<a href="{$_base_url}umowy/lista-umow" class="button buttonIcon umowyButton">Umowy</a>
	{/if}
	
	<a href="{$_base_url}strony/informacje-rodo,51" class="button buttonIcon rodoButton">Informacje RODO</a>
	
	
	<h1 class="marginTop20">Placówka {$a_placowka.nazwa} <img src="images/core/help_icon.png" alt="Pomoc" title="Pomoc" class="pokazPomoc" data-id="77"></h1>
	
	<h2>Legitymacje aktywne:</h2>

	{if $a_karty}
		<ul class="lista_kart">
			{foreach $a_karty as $a_karta}
				<li {if $a_karta.status=='oczekujaca'}class="karta_oczekujaca"{/if}>
					Status umowy:
					{if $a_karta.id_karty==1}
						{if !$a_umowa_legitymacje_nauczyciela}
							brak wygenerowanej umowy
						{else if $a_umowa_legitymacje_nauczyciela.status=='oczekująca'}
							oczekuje na dostarczenie
						{else}
							{$a_umowa_legitymacje_nauczyciela.status}
						{/if}
					{else}
						{if !$a_umowa_legitymacje_szkolne}
							brak wygenerowanej umowy
						{else if $a_umowa_legitymacje_szkolne.status=='oczekująca'}
							oczekuje na dostarczenie
						{else}
							{$a_umowa_legitymacje_szkolne.status}
						{/if}
					{/if}
					<a href="{$_base_url}legitymacje/lista-osob-legitymacji/id_karty/{$a_karta.id_karty}">
						<img src="images/karty/{$a_karta.img}" alt="">
						{if $a_karta.status=='oczekujaca'}<p>Oczekuje na potwierdzenie</p>{/if}
						<div class="opis_karty">{$a_karta.appetizer}</div>
					</a>
				</li>
			{/foreach}
		</ul>
	{else}
		<p>Brak</p>
	{/if}
	
	<h2>Legitymacje dostępne:</h2>
		<div class="marginTop20"></div>
		{if !$czy_potwierdzona_umowa_ls}
			<a href="{$_base_url}umowy/lista_umow" class="button green">wymaga zawarcia umów >></a>
		{/if}
	{if $a_karty_pozostale}
		<ul class="lista_kart marginTop20">
			{foreach $a_karty_pozostale as $a_karta}
				<li class="karta_inactive">
					<a href="{$_base_url}{if $a_karta.id_karty!=1 && !$a_umowa_legitymacje_szkolne || $a_karta.id_karty==1 && !$a_umowa_legitymacje_nauczyciela}legitymacje/umowy_legitymacji_szkolnych/id_karty/{$a_karta.id_karty}{else}strony/{$a_karta.sludge},{$a_karta.id_sites}{/if}">
						<img src="images/karty/{$a_karta.img}" alt="">
						<p>Aktywuj</p>
						<div class="opis_karty">{$a_karta.appetizer}</div>
					</a>
				</li>
			{/foreach}
		</ul>
	{else}
		<p>Brak</p>
	{/if}
	
</section>