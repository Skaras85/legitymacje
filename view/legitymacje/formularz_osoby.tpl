<section>

	<h1>Formularz legitymacji</h1>

	<form action="{$_base_url}" method="POST" class="jValidate" enctype="multipart/form-data">
	    <fieldset class="clearfix">
			 <input type="hidden" name="id_karty" value="{$a_karta.id_karty}">
			 <input type="hidden" name="id_placowki" value="{$a_placowka.id_placowki}">
			 {if isset($a_legitymacja)}<input type="hidden" name="id_legitymacje" value="{$a_legitymacja.id_legitymacje}">{/if}
			{if $a_pola}
				<table class="legitymacje_osoby">
					
					{foreach $a_pola as $index=>$a_pole}
						{assign var="wartosc" value="kol`$a_pole['kolumna']`"}
						<tr>
							{if in_array($a_pole.typ,array('imię 1','imię 2','nazwisko 1','nazwisko 2','imiona','nazwiska','pesel','adres','nr legitymacji','stanowisko','data urodzenia','data wydania','okres zatrudnienia (prosty)','uwagi'))}
								<td><label for="text_{$a_pole.id_karty_pola}" class="inline">{$a_pole.nazwa}</label></td>
								<td><input type="text" id="text_{$a_pole.id_karty_pola}" name="a_dane[{$a_pole.id_karty_pola}]" class="legitymacja_pole_txt strtoupper {if in_array($a_pole.typ,array('imię 1','nazwisko 1','imiona','nr legitymacji'))}jRequired{/if} jMinLength {if $a_pole.liczba_znakow!=0}jMaxLength{/if} {if $a_pole.typ=='pesel'}jPesel{/if} {if $a_pole.typ=='data urodzenia' || $a_pole.typ=='data wydania'}{/if} {if $a_pole.typ=='nr legitymacji'}numer_legitymacji_input{/if}" placeholder="{$a_pole.placeholder}" data-min-length="{if in_array($a_pole.typ,array('data urodzenia', 'data wydania'))}8{elseif $a_pole.typ=='nr legitymacji'}1{else}3{/if}" data-max-length="{$a_pole.liczba_znakow}" {if $index==0}autofocus{/if} value="{if isset($a_legitymacja)}{$a_legitymacja[$wartosc]}{elseif isset($a_zapamietane_wartosci[{$a_pole.id_karty_pola}])}{$a_zapamietane_wartosci[{$a_pole.id_karty_pola}]}{/if}"></td>
								<td class="{if $a_pole.liczba_znakow}legitymacja_liczba_znakow{/if}" data-dopuszczalna-liczba-znakow="{$a_pole.liczba_znakow}">{if $a_pole.liczba_znakow}<span>{if isset($a_legitymacja)}{$a_legitymacja[$wartosc]|strlen}{else}0{/if}</span>/{$a_pole.liczba_znakow}{else}&nbsp;{/if}</td>
							{elseif $a_pole.typ=='okres zatrudnienia (złożony)'}
								<td>{$a_pole.nazwa}</td>
								<td>
									<div><input type="radio" id="na_czas_nieokreslony" name="okres_zatrudnienia_czas" value="na_czas_nieokreslony" class="iCheck okres_zatrudnienia_czas" {if isset($a_legitymacja) && $a_legitymacja[$wartosc]=='' || !isset($a_legitymacja)}checked{/if}> <label for="na_czas_nieokreslony" class="inline">na czas nieokreślony</label></div>
									<div><input type="radio" id="na_czas_okreslony" name="okres_zatrudnienia_czas" value="na_czas_okreslony" class="iCheck okres_zatrudnienia_czas" {if isset($a_legitymacja) && $a_legitymacja[$wartosc]!=''}checked{/if}> <label for="na_czas_okreslony" class="inline">na czas określony</label></div>
									<div class="{if !isset($a_legitymacja) || $a_legitymacja[$wartosc]==''}hidden{/if} okres_zatrudnienia_czas_data">
										<label for="text_{$a_pole.id_karty_pola}" class="inline">{$a_pole.nazwa}</label>
										<input type="text" id="text_{$a_pole.id_karty_pola}" name="a_dane[{$a_pole.id_karty_pola}]" class="legitymacja_pole_txt {if isset($a_legitymacja) && $a_legitymacja[$wartosc]!=''}jRequired jDate{/if}" placeholder="0000-00-00" value="{if isset($a_legitymacja)}{$a_legitymacja[$wartosc]}{/if}">
									</div>
								</td>
								<td>&nbsp;</td>
							{elseif $a_pole.typ=='pracodawca' || $a_pole.typ=='szkoła'}
								<td><label for="text_{$a_pole.id_karty_pola}" class="inline">{$a_pole.nazwa}</label></td>
								<td>
									<select name="a_dane[{$a_pole.id_karty_pola}]">
										{if $a_pracodawcy}
											{foreach $a_pracodawcy as $a_pracodawca}
												<option value="{$a_pracodawca.id_pracodawcy}" {if isset($a_legitymacja) && $a_legitymacja[$wartosc]==$a_pracodawca.id_pracodawcy || !isset($a_legitymacja)  && isset($a_zapamietane_wartosci[{$a_pole.id_karty_pola}]) && $a_zapamietane_wartosci[{$a_pole.id_karty_pola}]==$a_pracodawca.id_pracodawcy}selected{/if}>{$a_pracodawca.dane1}</option>
											{/foreach}
										{/if}
									</select>
								</td>
								<td>&nbsp;</td>
							{elseif $a_pole.typ=='zdjęcie' || $a_pole.typ=='podpis'}
								<td><label for="text_{$a_pole.id_karty_pola}" class="inline">{$a_pole.nazwa}</label></td>
								<td>
									{if isset($a_legitymacja) && isset($a_photos) && $a_photos['zdjecie']}
										<img src="{$_base_url}{$a_photos['zdjecie']}?{$img_rand}" class="right" alt="" width="100">
									{/if}
									{if isset($a_legitymacja) && isset($a_photos) && $a_photos['zdjecie_obrobka']}
										<img src="{$_base_url}{$a_photos['zdjecie_obrobka']}?{$img_rand}" class="right" alt="" width="100">
									{/if}
									{if isset($a_legitymacja) && $a_photos['podpis']}
										<img src="{$_base_url}{$a_photos['podpis']}?{$img_rand}" class="right" alt="" width="100">
									{/if}
									{if isset($a_legitymacja) && $a_photos['podpis_obrobka']}
										<img src="{$_base_url}{$a_photos['podpis_obrobka']}?{$img_rand}" class="right" alt="" width="100">
									{/if}
									<label for="file_{$a_pole.id_karty_pola}" class="inline">{$a_pole.nazwa}</label> <input type="file" class="file_upload jExtension" data-extensions="jpg jpeg gif png pdf" name="files[]" id="file_{$a_pole.id_karty_pola}" data-typ="{if $a_pole.typ=='zdjęcie'}zdjecie{else}podpis{/if}" data-zrodlo="formularz">
								</td>
								<td>&nbsp;</td>z
							{elseif in_array($a_pole.typ,array('zdjęcie i podpis','zdjęcie i podpis (złożony)','zdjęcie (złożony)','podpis (złożony)'))}
								<td><label for="text_{$a_pole.id_karty_pola}" class="inline">{$a_pole.nazwa}</label></td>
								<td>
									{if in_array($a_pole.typ,array('zdjęcie i podpis (złożony)','zdjęcie (złożony)','podpis (złożony)'))}
										<div><input type="radio" id="zdjecie_i_podpis_wysle_poczta" name="zdjecie_i_podpis_zlozone" value="wysle_poczta" class="iCheck zdjecie_i_podpis_zlozone" {if (!isset($a_photos) || isset($a_photos) && !$a_photos['zdjecie'] && !$a_photos['podpis'] && !$a_photos['zdjecie_obrobka'] && !$a_photos['podpis_obrobka'])}checked{/if} {if isset($a_legitymacja) && (isset($a_photos) && ($a_photos['zdjecie'] || $a_photos['zdjecie_obrobka'] || $a_photos['podpis'] || $a_photos['podpis_obrobka']))}disabled{/if}> <label for="zdjecie_i_podpis_wysle_poczta" class="inline">wyślę pocztą</label></div>
										<div><input type="radio" id="zdjecie_i_podpis_dodam_teraz" name="zdjecie_i_podpis_zlozone" value="dodam_teraz" class="iCheck zdjecie_i_podpis_zlozone" {if isset($a_photos) && ($a_photos['zdjecie'] || $a_photos['podpis'] || $a_photos['zdjecie_obrobka'] || $a_photos['podpis_obrobka'])}checked{/if}> <label for="zdjecie_i_podpis_dodam_teraz" class="inline">dodam teraz</label></div>
									{/if}
									
									<div class="zdjecie_i_podpis_wrapper {if in_array($a_pole.typ,array('zdjęcie i podpis (złożony)','zdjęcie (złożony)','podpis (złożony)')) && !$a_photos['zdjecie'] && !$a_photos['podpis'] && !$a_photos['zdjecie_obrobka'] && !$a_photos['podpis_obrobka']}hidden{/if}">
										<div class="clearfix">
											{if !$a_photos['zdjecie'] && !$a_photos['zdjecie_obrobka'] && in_array($a_pole.typ,array('zdjęcie i podpis (złożony)','zdjęcie (złożony)'))}
												<label for="file_{$a_pole.id_karty_pola}" class="inline">zdjęcie</label> 
												<input type="file" class="file_upload jExtension {if isset($a_legitymacja) && !$a_photos['zdjecie'] && !$a_photos['zdjecie_obrobka']}jRequiredd{/if}" data-extensions="jpg jpeg gif png pdf" name="files[]" id="file_{$a_pole.id_karty_pola}" data-typ="zdjecie" data-zrodlo="formularz">
											{/if}
											
											{if isset($a_legitymacja) && isset($a_photos) && $a_photos['zdjecie']}
												<!--<img src="{$_base_url}{$a_photos['zdjecie']}?{$img_rand}" class="" alt="" width="100">-->
												<img src="{$_base_url}images/site/uploaded-photo-icon.png" data-hint="{$_base_url}img.php?id_legitymacje={$a_legitymacja.id_legitymacje}&typ=zdjecie" class="pokazZdjecie">
												<img src="{$_base_url}images/site/delete.png" class="deleteImage pointer" data-type="zdjecie" data-id-osoby="{$a_legitymacja.id_legitymacje}" title="Usuń">
											{/if}
											{if isset($a_legitymacja) && isset($a_photos) && $a_photos['zdjecie_obrobka']}
												<!--<img src="{$_base_url}{$a_photos['zdjecie_obrobka']}?{$img_rand}" class="" alt="" width="100">-->
												<img src="{$_base_url}images/site/uploaded-photo-icon.png" data-hint="{$_base_url}img.php?id_legitymacje={$a_legitymacja.id_legitymacje}&typ=zdjecie&obrobka=1" class="pokazZdjecie">
												<img src="{$_base_url}images/site/delete.png" class="deleteImage pointer" data-type="zdjecie" data-id-osoby="{$a_legitymacja.id_legitymacje}" title="Usuń">
											{/if}
											
										</div>
										<div class="clearfix">
											{if !$a_photos['podpis'] && !$a_photos['podpis_obrobka'] && in_array($a_pole.typ,array('zdjęcie i podpis (złożony)','podpis (złożony)'))}
												<label for="file_{$a_pole.id_karty_pola}" class="inline">podpis</label> 
												<input type="file" class="file_upload jExtension {if isset($a_legitymacja) && !$a_photos['podpis'] && !$a_photos['podpis_obrobka']}jRequiredd{/if}" data-extensions="jpg jpeg gif png pdf" name="files[]" id="file_{$a_pole.id_karty_pola}" data-typ="podpis" data-zrodlo="formularz">
											{/if}
											
											{if isset($a_legitymacja) && $a_photos['podpis']}
												<!--<img src="{$_base_url}{$a_photos['podpis']}?{$img_rand}" class="" alt="" width="100">-->
												<img src="{$_base_url}images/site/uploaded-sign-icon.png" data-hint="{$_base_url}img.php?id_legitymacje={$a_legitymacja.id_legitymacje}&typ=podpis" class="pokazZdjecie">
												<img src="{$_base_url}images/site/delete.png" class="deleteImage pointer" data-type="podpis" data-id-osoby="{$a_legitymacja.id_legitymacje}" title="Usuń">
											{/if}
											{if isset($a_legitymacja) && $a_photos['podpis_obrobka']}
												<!--<img src="{$_base_url}{$a_photos['podpis_obrobka']}?{$img_rand}" class="" alt="" width="100">-->
												<img src="{$_base_url}images/site/uploaded-sign-icon.png" data-hint="{$_base_url}img.php?id_legitymacje={$a_legitymacja.id_legitymacje}&typ=podpis&obrobka=1" class="pokazZdjecie">
												<img src="{$_base_url}images/site/delete.png" class="deleteImage pointer" data-type="podpis" data-id-osoby="{$a_legitymacja.id_legitymacje}" title="Usuń">
											{/if}
										</div>
									</div>
								</td>
								<td>&nbsp;</td>
							{/if}
							<td>{if $a_pole.czy_zapamietac}<a href="#" class="button legitymacja_save" data-id-pola="{$a_pole.id_karty_pola}">Zapamiętaj</a>{else}&nbsp;{/if}</td>
						</tr>
					{/foreach}
					<tr>
						<td>&nbsp;</td>
						<td>
							<input type="submit" value="Zapisz" class="button green zapisz_dane_legitymacji buttonIcon takButton">
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			{/if}

		</fieldset>
	</form>
</section>
