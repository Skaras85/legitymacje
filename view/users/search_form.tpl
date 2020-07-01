<form action="{$_base_url}users/lista-userow">
	<input type="hidden" name="lat" id="lat">
	<input type="hidden" name="lon" id="lon">
	<p class="searchText">Wpisz nazwę restauracji lub adres w pobliżu którego chciałbyś zjeść</p>
	<div class="searchWrapper">
		<input type="submit" value="Szukaj">
		<input type="text" class="wyszukiwarka searchMain" name="adres" id="searchInput" placeholder="Wpisz adres, np. Zielna 14">
	</div>
	<div class="goVegeWrapper {if $is_vege}checked{/if}">
		<div></div>
		<input type="checkbox" id="go_vege" {if $is_vege}checked{/if} class="reload"> <label for="go_vege">Jestem wegan</label>
	</div>
	<select id="zmien_miasto" class="hidden">
		{foreach $a_miasta as $a_miasto}
			<option value="{$a_miasto.id_miasta}" {if $wybrane_miasto==$a_miasto.id_miasta}selected{/if}>{$a_miasto.nazwa}</option>
		{/foreach}
	</select>
 </form>