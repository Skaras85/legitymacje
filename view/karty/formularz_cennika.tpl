<section class="clearfix">
	<h1>Cennik {if isset($a_produkt)}produktu {$a_produkt.nazwa}{else}karty {$a_karta.nazwa}{/if} placówki {$a_placowka.nazwa}</h1>
	
	<form action="{$_base_url}" method="POST" class="jValidate clearfix">

		{if isset($a_produkt)}
			<input type="hidden" name="module" value="produkty">
			<input type="hidden" name="action" value="zapisz_cennik">
			<input type="hidden" name="id_produktu" value="{$id_produktu}">
		{else}
			<input type="hidden" name="module" value="karty">
			<input type="hidden" name="action" value="zapisz_cennik">
			<input type="hidden" name="id_karty" value="{$id_karty}">
		{/if}
		
		<div class="left">
			<h2>Cennik {if isset($a_produkt)}produktu{else}kart{/if}</h2>
			<select name="id_cenniki">
				<option value="0"></option>
				{foreach $a_cenniki as $a_cennik}
					<option value="{$a_cennik.id_cenniki}" {if $id_cenniki==$a_cennik.id_cenniki}selected{/if}>{$a_cennik.nazwa}</option>
				{/foreach}
			</select>

			<h2>Sposoby wysyłki</h2>
			
			{foreach $a_sposoby_wysylki as $index=>$a_sposob}
				<label for="sposob_wysylki_{$a_sposob.id_sposoby_wysylki}" class="sposob_wysylki_label">{$a_sposob.nazwa}</label>
				<select name="a_sposoby_wysylki[{$a_sposob.id_sposoby_wysylki}]" id="sposob_wysylki_label{$a_sposob.id_sposoby_wysylki}">
					<option value="0"></option>
					{foreach $a_cenniki as $a_cennik}
						<option value="{$a_cennik.id_cenniki}" {foreach $a_cenniki_wysylka as $a_cennik_wysylka}{if $a_cennik_wysylka.id_cenniki==$a_cennik.id_cenniki && $a_cennik_wysylka.id_sposoby_wysylki==$a_sposob.id_sposoby_wysylki}selected{/if}{/foreach}>{$a_cennik.nazwa}</option>
					{/foreach}
				</select>
			{/foreach}
			
			<input type="submit" value="Zapisz" class="marginTop20">
		</div>
		
	</form>
</section>