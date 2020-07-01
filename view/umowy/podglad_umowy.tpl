<section class="printHide">
	<h1>Podgląd umowy</h1>
	
	<div class="center">
		<a href="{$_base_url}umowy/formularz_umowy_krok1{if isset($hash)}/hash/{$hash}{/if}" class="button red buttonIcon wsteczButton">Popraw</a>
		<a href="{$_base_url}umowy/zapisz" class="button green buttonIcon takButton">Generuj</a>
	</div>
	
	<div class="marginTop50">
		{$umowa}
	</div>

	<div class="center">
		<a href="{$_base_url}umowy/formularz_umowy_krok1{if isset($hash)}/hash/{$hash}{/if}" class="button red buttonIcon wsteczButton">Popraw</a>
		<a href="{$_base_url}umowy/zapisz" class="button green buttonIcon takButton">Generuj</a>
	</div>
	
</section>