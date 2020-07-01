<section class="printHide">

	{if empty($lista_umow_admin)}
		<h1>Umowy placówki {$a_placowka.nazwa}</h1>
		<div class="center marginTop40">
			<a href="{$_base_url}umowy/formularz_umowy_krok1" class="button buttonIcon umowaButton green">Generuj nową umowę</a>
			{if session::who('admin') || session::get('czy_zdalny')}
				<a href="{$_base_url}umowy/formularz_umowy_zew" class="button buttonIcon umowaButton green modal">Dodaj umowę zew.</a>
				
				{if $a_pracodawcy}
					<a href="#" class="button buttonIcon umowaButton green czy_wyslac_link_do_generatora_umow">Wyślij link generatora</a>
				{/if}
			{/if}
			<div class="marginTop40">
				{$a_opis.text}
			</div>
		</div>
	{else}
		<h1>Umowy</h1>
		
		<form action="{$_base_url}umowy/lista_umow_admin">
			
			<label for="typ_umowy" class="inline">Typ umowy:</label>
			<select name="typ_umowy" id="typ_umowy" class="autoWidth">
				<option value="" {if $typ_umowy===''}selected{/if}>Wszystkie</option>
				{if !empty($a_umowy_typy)}
					{foreach $a_umowy_typy as $a_typ}
						<option value="{$a_typ.id_umowy_typy}" {if $typ_umowy==$a_typ.id_umowy_typy}selected{/if}>{$a_typ.nazwa}</option>
					{/foreach}
				{/if}
			</select>
			
			<select name="filtr" class="autoWidth">
				<option value="" {if $filtr===''}selected{/if}>Wszystkie</option>
				<option {if $filtr=='oczekująca'}selected{/if}>oczekująca</option>
				<option {if $filtr=='podpisana'}selected{/if}>podpisana</option>
				<option {if $filtr=='unieważniona'}selected{/if}>unieważniona</option>
				<option {if $filtr=='rozwiązana'}selected{/if}>rozwiązana</option>
				<option {if $filtr=='wygasła'}selected{/if}>wygasła</option>
			</select>

			<label for="fraza" class="inline">Fraza:</label>
			<input type="text" name="fraza" class="autoWidth" id="fraza" value="{$fraza}">
	
			<input type="submit" value="Szukaj" class="autoWidth">
		</form>
		
		<div class="right">
			<a href="#" class="zmien_status_umowy button" data-status="unieważniona">Unieważnij umowy</a>
			<a href="#" class="zmien_status_umowy button" data-status="wygasła">Wygaś umowy</a>
		</div>
	{/if}
	
	{if !empty($a_umowy)}
		<table class="dataTables">
			<thead>
				<tr>
					<th>L.p.</th>
					<th>ID placówki</th>
					<th>Nazwa</th>
					<th>Data wygenerowania</th>
					{if session::get('czy_zdalny') || session::who('admin')}
						<th>Edycja</th>
					{/if}
					<th>Status</th>
					<th>Data potwierdzenia</th>
					<th>Termin ważności</th>
					<th>Podgląd pdf</th>
					{if session::get('czy_zdalny') || session::who('admin')}
						<th>Podgląd skanu</th>
						<th><input type="checkbox" id="zaznaczDane" class="checkAll"><label for="zaznaczDane" class="inline">Zaznacz</label></th>
					{/if}
				</tr>
			</thead>
			{if $a_umowy}
				<tbody>
					{foreach $a_umowy as $a_umowa}
						<tr data-id="{$a_umowa.id_umowy2}">
							<td>{counter}</td>
							<td>{$a_umowa.id_placowki}</td>
							<td>{if !empty($a_umowa.nazwa)}{$a_umowa.nazwa}{else}Umowa zew.{/if}</td>
							<td>{$a_umowa.data_dodania}</td>
							{if session::get('czy_zdalny') || session::who('admin')}
								<td><a href="{$_base_url}umowy/formularz-edycji/id_umowy/{$a_umowa.id_umowy2}{if isset($lista_umow_admin)}/czy_admin/1{/if}">Edytuj</a></td>
							{/if}
							<td>{$a_umowa.status}</td>
							<td>{if $a_umowa.status=='podpisana'}{$a_umowa.data_potwierdzenia}{else}&nbsp;{/if}</td>
							<td>{if $a_umowa.okres_obowiazywania=='0000-00-00'}na czas nieokreślony{else}{$a_umowa.okres_obowiazywania}{/if}</td>
							<td><a href="{$_base_url}get.php?typ=umowy&id_umowy={$a_umowa.id_umowy2}">Podgląd</a></td>			
							{if session::get('czy_zdalny') || session::who('admin')}
								<td>
									{if $a_umowa.status!='podpisana'}
										<a href="{$_base_url}umowy/formularz-skanu/id_umowy/{$a_umowa.id_umowy2}" class="button modal">Załącz</a>
									{else}
										<a href="{$_base_url}get.php?typ=potwierdzenia&id_umowy={$a_umowa.id_umowy2}">Podgląd</a>
									{/if}
								</td>
								<td class="center"><input type="checkbox" name="a_umowa[{$a_umowa.id_umowy2}]" class="checkAllTarget umowy" data-check-id="zaznaczDane"></td>
							{/if}
						</tr>
					{/foreach}
				</tbody>
			{/if}
		</table>
	{/if}
</section>