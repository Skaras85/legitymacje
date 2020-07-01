{if session::is_logged()}
	<section class="clearfix">

		{if session::who('admin')}
			<a href="{$_base_url}placowki/admin_lista_placowek" class="mainKafelek button">Zarządzaj placówkami</a>
			<a href="{$_base_url}users/lista_kont_wewnetrznych" class="mainKafelek button">Konta wewnętrzne</a>
			<a href="{$_base_url}legitymacje/formularz-zdjec" class="mainKafelek button">Zdjęcia</a>
			<a href="{$_base_url}przesylki/lista-placowek" class="mainKafelek button">Przesyłki</a>
			<a href="{$_base_url}migracja/admin-migracja" class="mainKafelek button">Migracje</a>
			<a href="{$_base_url}rozliczenia/lista-rozliczen" class="mainKafelek button">Rozliczenia</a>
		{/if}
		<div class="mainLeft left">
			{if $a_newsy}
				<h1>Aktualności</h1>
				
				{foreach $a_newsy as $a_news}
					<div class="aktualnosc">
						<h2>{$a_news.title}</h2>
						<p>Data publikacji: {$a_news.add_date|substr:0:10}</p>
						{$a_news.appetizer}
						<p><a href="{$_base_url}strony/{$a_news.sludge},{$a_news.id_sites}" class="modal">Czytaj dalej</a></p>
					</div>
				{/foreach}
			{/if}
		</div>
		<div class="mainRight right">
			{if isset($a_slidy)}
				<div id="slider">
					{foreach $a_slidy as $a_slide}
						<div><a href="{$a_slide.link}"><img src="{$a_slide.img}" alt="{$a_slide.title}"></a></div>
					{/foreach}
				</div>
			{/if}
			<h1>Placówki</h1>
			<a href="{$_base_url}placowki/formularz-placowki" class="button buttonIcon green dodajButton">Dodaj</a>
			{if $a_placowki}
				
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
			{/if}
		</div>
	</section>
{else}
	
	{include file="users/formularz_logowania.tpl"}
{/if}
