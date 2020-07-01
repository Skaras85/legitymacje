<section class="formSection">

	<a href="legitymacje/lista-kart"><h2 id="panelTitle" title="Powrót">Dodaj pola do karty {$a_karta.nazwa}</h2></a>

	<form action="{$_base_url}" method="POST" class="jValidate" enctype="multipart/form-data">
		<input type="hidden" name="module" value="karty">
		<input type="hidden" name="action" value="zapisz_pola">
	    <input type="hidden" name="id_karty" value="{$a_karta.id_karty}">

	
	    <fieldset class="clearfix">

				<table id="pola_karty">
					<tr>
						<th>Typ</th>
						<th>Kolumna</th>
						<th>Liczba znaków</th>
						<th>X</th>
						<th>Y</th>
						<th>Font size</th>
						<th>Font family</th>
						<th>Kolejność</th>
						<th>Domyślnie</th>
						<th>Czy rewers</th>
						<th>Czy zapamiętać?</th>
						<th>Akcja</th>
					</tr>
					{if $a_pola}
						{foreach $a_pola as $index=>$a_pole}
							<tr>
								<td>
									<select name="a_pola[{$index}][id_karty_pola_typy]" id="form_typ_pola">
										{foreach $a_typy as $a_typ}
											<option value="{$a_typ.id_karty_pola_typy}" {if $a_pole.id_karty_pola_typy==$a_typ.id_karty_pola_typy}selected{/if}>{$a_typ.typ}</option>
										{/foreach}
									</select>
								</td>
								<td><input type="text" name="a_pola[{$index}][kolumna]" value="{$a_pole.kolumna}" class="jNumber"></td>
								<td><input type="text" name="a_pola[{$index}][liczba_znakow]" value="{$a_pole.liczba_znakow}" class="jNumber"></td>
								<td><input type="text" name="a_pola[{$index}][x]" value="{$a_pole.x}" class="jNumber"></td>
								<td><input type="text" name="a_pola[{$index}][y]" value="{$a_pole.y}" class="jNumber"></td>
								<td><input type="text" name="a_pola[{$index}][font_size]" value="{$a_pole.font_size}" class="jNumber"></td>
								<td><input type="text" name="a_pola[{$index}][font_family]" value="{$a_pole.font_family}"></td>
								<td><input type="text" name="a_pola[{$index}][kolejnosc]" value="{$a_pole.kolejnosc}" class="jNumber"></td>
								<td><input type="text" name="a_pola[{$index}][placeholder]" value="{$a_pole.placeholder}"></td>
								<td><input type="checkbox" name="a_pola[{$index}][czy_rewers]" {if $a_pole.czy_rewers==1}checked{/if}></td>
								<td class="center"><input type="checkbox" name="a_pola[{$index}][czy_zapamietac]" {if $a_pole.czy_zapamietac}checked{/if}></td>
								<td><a href="#" class="button usun_pole_karty">usuń</a></td>
							</tr>
						{/foreach}
					{/if}
					<tr>
						<td>
							<select name="a_pola[{$a_pola|count}][id_karty_pola_typy]" id="form_typ_pola">
								{foreach $a_typy as $a_typ}
									<option value="{$a_typ.id_karty_pola_typy}">{$a_typ.typ}</option>
								{/foreach}
							</select>
						</td>
						<td><input type="text" name="a_pola[{$a_pola|count}][kolumna]" value="" class="jNumber"></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][liczba_znakow]" value="" class="jNumber"></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][x]" value="" class="jNumber"></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][y]" value="" class="jNumber"></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][font_size]" value="" class="jNumber"></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][font_family]" value=""></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][kolejnosc]" value="" class="jNumber"></td>
						<td><input type="text" name="a_pola[{$a_pola|count}][placeholder]" value="{$a_pole.placeholer}"></td>
						<td><input type="checkbox" name="a_pola[{$a_pola|count}][czy_rewers]"></td>
						<td class="center"><input type="checkbox" name="a_pola[{$a_pola|count}][czy_zapamietac]"></td>
						<td><a href="#" class="button dodaj_pole_karty"><nobr>+ dodaj kolejne</nobr></a></td>
					</tr>
				</table>
		    	

		    	<input type="submit" value="Zapisz">

		</fieldset>
	</form>
</section>
