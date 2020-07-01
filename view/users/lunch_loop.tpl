<div class="localListLunch clearfix" data-id="{$a_lunch.id_lunche}">
	{if $a_lunch.img}
		<a href="images/users/{$a_lunch.id_users}/lunche/large_{$a_lunch.img}?t={$a_lunch.token_odswiezenia}" class="fancybox"><img src="images/users/{$a_lunch.id_users}/lunche/small_{$a_lunch.img}?t={$a_lunch.token_odswiezenia}" alt="Wege jedzenie w {$a_user.nazwa}" class="avatar"></a>
	{/if}
	<div class="localListLunchContent {if $a_lunch.img}short{/if}">
		<p>{$a_lunch.tresc}</p>
	</div>
	<div class="localListLunchData">
		<div class="lunchPrice">
			{$a_lunch.cena} zł
		</div>
	</div>
	
	{if isset($profil) && ($a_user.id_users==$a_logged_user.id_users || session::who('admin'))}
		<div class="clear"></div>
		<p><a href="{$_base_url}lunch/formularz-lunchu/id/{$a_lunch.id_lunche}" class="adminLink">edytuj posiłek</a> |
		   <a href="#" class="adminLink czy_usunac_lunch">usuń posiłek</a></p>
	{/if}
</div>