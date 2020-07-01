<section id="userList" class="clearfix">
	<form action="{$_base_url}users/lista-userow" class="searchForm">
		<div class="searchButton"></div>
		<p class="strong">Wyszukiwanie zaawansowane</p>

		<input type="text" class="searchAdvenced" name="adres" id="searchInput" value="{$address}" placeholder="Wpisz adres, np. Zielna 14">

		<input type="submit" value="Filtruj" class="">



		<input type="hidden" name="lat" value="{$lat}" id="lat">
		<input type="hidden" name="lon" value="{$lon}" id="lon">
		<!--<input type="hidden" name="data" value="{$data}" id="data">-->
		{if $a_wybrana_dzielnica}
			<input type="hidden" name="sludge" value="{$a_wybrana_dzielnica.sludge}">
		{/if}
	
		<!--
		<input type="checkbox" name="czy_z_dowozem" id="czy_z_dowozem" class="iCheck" {if $czy_z_dowozem}checked{/if}> <label for="czy_z_dowozem">Tylko z dowozem</label>
		-->
		
		<div class="goVegeWrapper {if $is_vege}checked{/if}">
			<div></div>
			<input type="checkbox" id="go_vege" {if $is_vege}checked{/if}> <label for="go_vege">Jestem wegan</label>
		</div>
		<div class="clear"></div>
		
		<p class="marginTop20 bold">Typy kuchni</p>
		{foreach $a_typy_kuchni as $a_typ_kuchni}
			<div><input type="checkbox" name="a_typy_kuchni[{$a_typ_kuchni.id_typy_kuchni}]" id="typ_kuchni_{$a_typ_kuchni.id_typy_kuchni}" class="iCheck" {if in_array($a_typ_kuchni.id_typy_kuchni,$a_wybrane_typy_kuchni)}checked{/if}> <label for="typ_kuchni_{$a_typ_kuchni.id_typy_kuchni}">{$a_typ_kuchni.nazwa}</label></div>
		{/foreach}
		
		<p class="marginTop20 bold">Udogodnienia</p>
		{foreach $a_udogodnienia as $a_udogodnienie}
			<div><input type="checkbox" name="a_udogodnienia[{$a_udogodnienie.id_dodatkowo}]" id="a_udogodnienia_{$a_udogodnienie.id_dodatkowo}" class="iCheck" {if in_array($a_udogodnienie.id_dodatkowo,$a_wybrane_udogodnienia)}checked{/if}> <label for="a_udogodnienia_{$a_udogodnienie.id_dodatkowo}">{$a_udogodnienie.nazwa}</label></div>
		{/foreach}
		
		<p class="marginTop20 bold">Dzielnice</p>
		{foreach $a_dzielnice as $a_dzielnica}
			<div><input type="checkbox" name="a_dzielnice[{$a_dzielnica.id_dzielnice}]" id="a_dzielnice_{$a_dzielnica.id_dzielnice}" class="iCheck" {if in_array($a_dzielnica.id_dzielnice,$a_wybrane_dzielnice)}checked{/if}> <label for="a_dzielnice_{$a_dzielnica.id_dzielnice}">{$a_dzielnica.nazwa}</label></div>
		{/foreach}
	
		
		<input type="submit" value="Filtruj" class="">
	</form>
	<div class="users_list">
		{if $a_strona}
			<div class="userListDesc">
				<h1>{$a_strona.title}</h1>
				{$a_strona.text}
			</div>
		{/if}
		<div id="districtMap"></div>
		{if $a_users}
			<div id="usersList">
				{foreach $a_users as $a_user}
					<article class="localWrapper clearfix">
						<div class="localListData clearfix {if $a_user.wyswietlenia!=0}visited{/if}">
							<div class="localListDataWrapper clearfix">
								<input type="hidden" class="lat" value="{$a_user.lat}">
								<input type="hidden" class="lon" value="{$a_user.lon}">
								<input type="hidden" class="id_users" value="{$a_user.id_users}">
								<a href="{$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge}" target="_blank">
									<img src="images/users/{$a_user.id_users}/avatar/{$a_user.avatar}?t={$a_user.token_odswiezenia}" class="avatar">
								</a>
								<div class="localListDataDetails">
									<h2><a href="{$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge}" target="_blank">{$a_user.nazwa}</a></h2>
									<address>{$a_user.ulica}<br>{if $a_user.kod_pocztowy!=''}{$a_user.kod_pocztowy}, {/if}{$a_user.nazwa_miasta}, {$a_user.dzielnica}</address>
									{if !$a_wybrana_dzielnica && !$a_strona}
										<p class="distance">Odległość: {$a_user.distance|distance}</p>
									{/if}
									<a href="" class="pin">Pokaż na mapie</a>
									<a href="http://maps.google.com/maps?daddr={$lat},{$lon}&saddr={$a_user.lat},{$a_user.lon}&z=17&t=m" class="nav" target="_blank">Nawiguj</a>
									{if $a_user.dostawa!=''}<a href="{$a_user.dostawa}" class="delivery" target="_blank" rel="nofollow">Zamów z dostawą</a>{/if}
									<a href="" class="compare">Porównaj lokal</a>
									<!--<div class="lunchHours">Godziny podawania:<br>{$a_user.godzina_od|date_format:"%H:%M"} - {$a_user.godzina_do|date_format:"%H:%M"}</div>-->
								</div>
							</div>
						</div>
						<div class="localListLuncheWrapper">
							<p><a href="{$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge}" class="orangeToGreen">Przejdź do restauracji</a></p>
							{if $a_user['a_lunche']}
								{foreach $a_user['a_lunche'] as $a_lunch}
									{include file='users/lunch_loop.tpl'}
								{/foreach}
							{/if}
						</div>
					</article>
				{/foreach}
			</div>
			{include file='system_view/pagination.tpl'}
		{else}
			<p class="communicat_info">Niestety brak lokali spełniających wybrane kryteria na dziś!</p>
		{/if}
	</div>
</section>