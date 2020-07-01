
<input type="hidden" id="url" value="{$a_user.sludge_miasta}/{$a_user.sludge}">
<input type="hidden" id="lat" value="{$a_user.lat}">
<input type="hidden" id="lon" value="{$a_user.lon}">
<input type="hidden" id="place_id" value="{$a_user.place_id}">
<input type="hidden" id="id_users" value="{$a_user.id_users}">

<div id="localHeader" style="background-image:url('{$_base_url}images/users/{$a_user.id_users}/bg/{$a_user.bg}?t={$a_user.token_odswiezenia}')">
	<div id="localHeaderContent">
		<img src="{$_base_url}images/users/{$a_user.id_users}/avatar/{$a_user.avatar}?t={$a_user.token_odswiezenia}" alt="Lunch w {$a_user.nazwa}" class="avatar whiteBorder">
		<h1>{$a_user.nazwa}</h1>
	</div>
</div>
<section id="localSite" class="clearfix">
	<div id="localSiteLunche">
		{if $a_user.id_users==$a_logged_user.id_users}
        	{if $czas_waznosci_konta<0}
        		<p class="komunikat_waznosc_konta_err">Niestety Twój abonament wygasł. Aby go odnowić i znów być widocznym w portalu <a href="{$_base_url}strony/strona/id/4">opłać go</a></p>
    		{else if $czas_waznosci_konta>0 && $czas_waznosci_konta<72}
    			<p class="komunikat_waznosc_konta_err">Ważność Twojego abonamentu kończy się za {$czas_waznosci_konta} godziny. <a href="{$_base_url}strony/strona/id/4">Pamiętaj o opłaceniu go na czas.</a></p>
    		{else}
    			<p class="komunikat_waznosc_konta_ok">Konto ważne do {$a_user.data_waznosci_konta}</p>
    		{/if}
        {/if}
		<div class="localWrapper">
			
			<div class="localSiteLuncheHeader">
				{if $a_user.czy_zamkniety==0}
					<h2 class="left">Przykładowe dania wege/wegan:</h2>
					<a href="" class="compare">Porównaj restaurację</a>
					<div class="clear"></div>
				{else}
					<p class="local_closed">Lokal zamknięty na stałe</p>
				{/if}
				
			</div>
			{if $a_user.id_users==$a_logged_user.id_users || session::who('admin')}
				<a href="{$_base_url}lunch/formularz-lunchu{if session::who('admin')}/id_users/{$a_user.id_users}{/if}" class="button">dodaj posiłek</a>
	        	<p class="info marginTop10">Kolejność wyświetlania posiłków możesz zmieniać przesuwając je między sobą.</p>
	        {/if}
			<div {if isset($profil) && ($a_user.id_users==$a_logged_user.id_users || session::who('admin'))}id="sortable"{/if}>
	        	{if $a_user['a_lunche']}
					{foreach $a_user['a_lunche'] as $a_lunch}
						{include file='users/lunch_loop.tpl'}
					{/foreach}
				{else}

				{/if}
			</div>
		</div>
    </div>
    <div id="localSiteData">
		<div class="clearfix">
			{if $a_user.id_users==$a_logged_user.id_users || session::who('admin')}
            	<p class="left"><a href="{$_base_url}users/formularz_uzytkownika{if session::who('admin')}/id/{$a_user.id_users}{/if}" class="button">Edytuj dane</a></p>
            
            	{if session::who('admin')}
            		<p class="right"><a href="{$_base_url}users/statystyki{if session::who('admin')}/id/{$a_user.id_users}{/if}" class="button">Statystyki</a></p>
            	{/if}
            {/if}
            <div  class="clear"></div>
			<address>
				<p>{$a_user.ulica}<br>{if $a_user.kod_pocztowy!=''}{$a_user.kod_pocztowy}, {/if}{$a_user.nazwa_miasta}{if $a_user.id_users!=201}, <a href="{$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge_dzielnicy}">{$a_user.dzielnica}</a>{/if}{if $a_user.adres_tel!=''}<br>Tel. <a href="tel:+48{$a_user.adres_tel}">{$a_user.adres_tel}</a>{/if}</p>
			</address>
			<!--<a href="http://www.facebook.com/sharer.php?u={$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge}" class="facebookShare" target="_blank">Udostępnij na facebooku</a>-->
			<div class="right">
				<div class="fb-like" data-href="{$_base_url}{$a_user.sludge_miasta}/{$a_user.sludge}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
    		</div>
		</div>
		<div class="localSiteLinks clearfix">
			<a href="http://maps.google.com/maps?daddr={$lat},{$lon}&saddr={$a_user.lat},{$a_user.lon}&z=17&t=m" class="nav" target="_blank">Nawiguj</a>
			<a href="#" class="dodaj_do_ulubionych {if $is_favorite}favoritesChecked{/if}">{if $is_favorite}Dodano do ulubionych{else}Dodaj do ulubionych{/if}</a>
			{if $a_user.www!=''}<a href="{$a_user.www}" class="www" target="_blank" rel="nofollow">{$a_user.www_raw}</a>{/if}
			{if $a_user.facebook!=''}<a href="{$a_user.facebook}" class="facebook_local" target="_blank" rel="nofollow">facebook</a>{/if}
			{if $a_user.dostawa!=''}<a href="{$a_user.dostawa}" class="delivery" target="_blank" rel="nofollow">Zamów z dostawą</a>{/if}
			
		</div>
		{if $a_user.opis!=''}
			<p class="opisTitle">Opis restauracji:</p>
			<p class="opisContent">{$a_user.opis}</p>
		{/if}
		{if $a_typy_kuchni}
			<p class="opisTitle">Serwowana kuchnia:</p>
			<ul class="opisList clearfix">
				{foreach $a_typy_kuchni as $index=>$a_typ_kuchni}
					<li><a href="{$_base_url}{$a_user.sludge_miasta}/{$a_typ_kuchni.sludge}">{$a_typ_kuchni.nazwa}</a>{if $index+1<count($a_typy_kuchni)},&nbsp;{/if}</li>
				{/foreach}
			</ul>
		{/if}
		
		{if $a_dodatkowo}
			<p class="opisTitle">Udogodnienia:</p>
			<ul class="opisList clearfix">
				{foreach $a_dodatkowo as $index=>$a_dodatek}
					<li><a href="{$_base_url}{$a_user.sludge_miasta}/{$a_dodatek.sludge}">{$a_dodatek.nazwa}</a>{if $index+1<count($a_dodatkowo)},&nbsp;{/if}</li>
				{/foreach}
			</ul>
		{/if}
		<div id="googleMapProfile"></div>
	</div>
</section>
<section id="nearLocals">
	<div class="nearLocalsContent clearfix">
		<h2>Proponowane restauracje wege w pobliżu</h2>
		{include file='lunch/lunch_locals.tpl'}
		<p><a href="{$_base_url}users/lista-userow?lat={$a_user.lat}&lon={$a_user.lon}" class="orangeToGreen">Pokaż więcej proponowanych restauracji wege w pobliżu</a></p>
	</div>
</section>
