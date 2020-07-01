<section>
	{include file='mailing/mailing_menu.tpl'}
	<h1 class="clear">Placówki do mailingu</h1>

	<form action="{$_base_url}mailing/lista_placowek" method="POST" class="marginTop20">
		<select name="czy_mailing" class="autoWidth">
			<option value="" {if $czy_mailing==''}selected{/if}>Wszyscy</option>
			<option value="1" {if $czy_mailing=='1'}selected{/if}>Chcą newsletter</option>
			<option value="0" {if $czy_mailing=='0'}selected{/if}>Nie chcą newsletter</option>
		</select>
		<!--
		<div class="multiSelect">
		    <ul>
		        <li><span></span>Czy chcą mailing{if !empty($a_wybrane_czy_mailing)} ({$a_wybrane_czy_mailing|@count}){/if}</li>
		        <li><input type="checkbox" name="a_czy_mailing[all]" class="checkAll" id="zaznacz_czy_mailing_all" {if isset($a_wybrane_czy_mailing['all'])}checked{/if}> <label for="zaznacz_czy_mailing_all">Wszyscy</label></li>
		        <li><input type="checkbox" name="a_czy_mailing[1]" id="czy_mailing_1" class="checkAllTarget" data-check-id="zaznacz_czy_mailing_all" {if isset($a_wybrane_czy_mailing[1])}checked{/if}> <label for="czy_mailing_1">Tak</label></li>
		    	<li><input type="checkbox" name="a_czy_mailing[0]" id="czy_mailing_0" class="checkAllTarget" data-check-id="zaznacz_czy_mailing_all" {if isset($a_wybrane_czy_mailing[0])}checked{/if}> <label for="czy_mailing_0">Nie</label></li>
		    </ul>
		</div>
		-->
		<div class="multiSelect">
		    <ul>
		        <li><span></span>Typy szkół{if !empty($a_wybrane_typy_szkol)} ({$a_wybrane_typy_szkol|@count}){/if}</li>
		        <li><input type="checkbox" name="a_typy_szkol[0]" class="checkAll" id="zaznacz_typy_szkol_all" {if isset($a_wybrane_typy_szkol[0])}checked{/if}> <label for="zaznacz_typy_szkol_all">Wszystkie</label></li>
		        {foreach $a_typy_szkol as $a_typ_szkoly}
		        	<li><input type="checkbox" name="a_typy_szkol[{$a_typ_szkoly.id_typy_szkol}]" id="typy_szkoly_{$a_typ_szkoly.id_typy_szkol}" class="checkAllTarget" data-check-id="zaznacz_typy_szkol_all" {if isset($a_wybrane_typy_szkol[{$a_typ_szkoly.id_typy_szkol}])}checked{/if}> <label for="typy_szkoly_{$a_typ_szkoly.id_typy_szkol}">{$a_typ_szkoly.nazwa}</label></li>
		    	{/foreach}
		    </ul>
		</div>
		
		<select name="rodzaj_konta" class="autoWidth">
			<option value="" {if $rodzaj_konta==''}selected{/if}>Konto dowolne</option>
			<option value="wewnętrzne" {if $rodzaj_konta=='wewnętrzne'}selected{/if}>Wewnętrzne</option>
			<option value="standard" {if $rodzaj_konta=='standard'}selected{/if}>Standard</option>
		</select>
		
		<div class="multiSelect">
		    <ul>
		        <li><span></span>Typy legitymacji{if !empty($a_wybrane_typy_legitymacji)} ({$a_wybrane_typy_legitymacji|@count}){/if}</li>
		        <li><input type="checkbox" name="a_typy_legitymacji[0]" class="checkAll" id="zaznacz_typy_legitymacji_all" {if isset($a_wybrane_typy_legitymacji[0])}checked{/if}> <label for="zaznacz_typy_legitymacji_all">Wszystkie</label></li>
		        {foreach $a_typy_legitymacji as $a_typ_legitymacji}
		        	<li><input type="checkbox" name="a_typy_legitymacji[{$a_typ_legitymacji.id_karty}]" id="typy_legitymacji_{$a_typ_legitymacji.id_karty}" class="checkAllTarget" data-check-id="zaznacz_typy_legitymacji_all" {if isset($a_wybrane_typy_legitymacji[{$a_typ_legitymacji.id_karty}])}checked{/if}> <label for="typy_legitymacji_{$a_typ_legitymacji.id_karty}">{$a_typ_legitymacji.nazwa}</label></li>
		    	{/foreach}
		    </ul>
		</div>
		
		<input type="text" name="search" placeholder="Szukaj" autofocus="true" value="{if !empty($search)}{$search}{/if}" class="autoWidth">		
		
		<br>
		<input type="submit" value="Szukaj"> <a href="#" class="button mailing_wybor_szablonu">Wyślij mailing</a>
		<a href="{$_base_url}mailing/lista_placowek/czy_wszystkie/1" class="button">Wczytaj wszystkie</a>
	</form>

	{if !empty($a_placowki)}
		
		
		<table class="marginTop20 dataTabless" id="placowki">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nazwa</th>
					<th>Adres</th>
					<th>Kod pocztowy</th>
					<!--<th>Status</th>-->
					<th>Miasto</th>
					<th>Typ</th>
					{if !$czy_wszystkie}<th>Umowy</th>{/if}
					<th><label for="zaznaczDane">Mailing</label> <input type="checkbox" class="checkAll" id="zaznaczDane"></th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_placowki as $a_placowka}
					<tr data-id="{$a_placowka.id_placowki}" data-id-users="{$a_placowka.id_users}">
						<td>{$a_placowka.id_placowki}</td>
						<td>{$a_placowka.nazwa}</td>
						<td>{$a_placowka.adres}</td>
						<td>{$a_placowka.kod_pocztowy}</td>
						<!--<td><nobr>{$a_placowka.status} {if $a_placowka.status=='nieaktywna'}<a href="#" class="button aktywuj_placowke">Aktywuj</a>{/if}</nobr></td>-->
						<td>{$a_placowka.poczta}</td>
						<td>{$a_placowka.typ}</td>
						
						{if !$czy_wszystkie}
							<td>
								{if $a_placowka.a_umowy}
									{foreach $a_placowka.a_umowy as $a_umowa}
										<a href="{$_base_url}get.php?typ=umowy&id_umowy={$a_umowa.id_umowy2}" class="umowa_{if $a_umowa.status=='oczekująca'}nie{/if}potwierdzona">{$a_umowa.numer_umowy}</a><br>
									{/foreach}
								{/if}
							</td>
						{/if}
						<td class="center"><input type="checkbox" name="a_placowki[{$a_placowka.id_placowki}]" class="checkAllTarget placowka" data-check-id="zaznaczDane"></td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
	
</section>