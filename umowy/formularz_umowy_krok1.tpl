<section>
	<h1>Formularz umowy</h1>
	
	<form id="form" method="post" action="{$_base_url}" class="jValidate">
		<input type="hidden" name="action" value="formularz_umowy_krok2" id="action">
		<input type="hidden" name="module" value="umowy">
		{if isset($a_umowa.id_umowy)}<input name="a_umowa[id_umowy]" value="{$a_umowa.id_umowy}" id="id_umowy" type="hidden">{/if}
		
			{if !$hash}
				<label for="form_umowy_typy">Typ umowy</label>
				<select name="a_umowa[id_umowy_typy]" id="form_umowy_typy" class="jRequired">
					<option value="0">Wybierz</option>
					{foreach $a_umowy_typy as  $a_typ_umowy}
						{if !$czy_ma_typ_umowy_2 || $czy_ma_typ_umowy_2 && $a_typ_umowy.id_umowy_typy<>2}
							<option value="{$a_typ_umowy.id_umowy_typy}" {if isset($a_umowa) && $a_umowa.id_umowy_typy==$a_typ_umowy.id_umowy_typy}selected{/if}>{$a_typ_umowy.nazwa}</option>
						{/if}
					{/foreach}
				</select>
			{else}
				<input type="hidden" name="hash" value="{$hash}">
			{/if}
		
		<label for="form_umowy_naglowek">Nagłówek</label>
		<select name="a_umowa[id_umowy_naglowki]" id="form_umowy_naglowek" class="jRequired">
			<option value="0">Wybierz</option>
			{foreach $a_umowy_naglowki as  $a_naglowek}
				<option value="{$a_naglowek.id_umowy_naglowki}" {if isset($a_umowa) && $a_umowa.id_umowy_naglowki==$a_naglowek.id_umowy_naglowki}selected{/if}>{$a_naglowek.nazwa}</option>
			{/foreach}
		</select>

		
   		<input type="submit" value="Krok 2" class="green  buttonIcon dalejButton">

     </form>  
</section>