<section>
	<h1>Formularz umowy - wybierz rodzaj e-legitymacji</h1>
	
	<form id="form" method="post" action="{$_base_url}" class="jValidate formularz_umowy_rodzaj_elegitymacji">
		<input type="hidden" name="action" value="formularz_umowy_krok2" id="action">
		<input type="hidden" name="module" value="umowy">
		<input type="hidden" name="a_umowa[id_umowy_typy]" value="{$a_umowa.id_umowy_typy}">
		<input type="hidden" name="a_umowa[id_umowy_naglowki]" value="{$a_umowa.id_umowy_naglowki}">
		{if $hash}
			<input type="hidden" name="hash" value="{$hash}">
		{/if}
		
		<a href="{$_base_url}umowy/formularz_umowy_krok1{if $hash}/hash/{$hash}{/if}" class="button red buttonIcon wsteczButton">Powrót</a>
		<input type="submit" value="Krok 3" class="green  buttonIcon dalejButton"  {if !isset($a_wybrane_karty)}disabled{/if}>
		
		<table>
			<thead>
				<th>Nazwa wzoru</th>
				<th>Czy zbliżeniowa?</th>
				<th>Opis</th>
				<th>Wybierz</th>
			</thead>
			{foreach $a_karty as $a_karta}
				<tr>
					<td>{$a_karta.nazwa}</td>
					<td>{if $a_karta.czy_zblizeniowa==1}TAK{else}NIE{/if}</td>
					<td><a href="{$_base_url}/legitymacje/umowy_legitymacji_szkolnych/id_karty/{$a_karta.id_karty}/bez_tekstu/1" class="modal button">Pokaż</a></td>
					<td><input type="checkbox" name="a_karty[{$a_karta.id_karty}]" {if isset($a_wybrane_karty)}{foreach $a_wybrane_karty as $wybrana_karta}{if $wybrana_karta==$a_karta.id_karty}checked{/if}{/foreach}{/if}></td>
				</tr>
			{/foreach}
		</table>
		
		<a href="{$_base_url}umowy/formularz_umowy_krok1{if $hash}/hash/{$hash}{/if}" class="button red buttonIcon wsteczButton">Powrót</a>
   		<input type="submit" value="Krok 3" class="green buttonIcon dalejButton" {if !isset($a_wybrane_karty)}disabled{/if}>
   		
     </form>  
</section>