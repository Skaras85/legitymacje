<section class="formSection">
	<h1>Statystyki z {$wybrany_miesiac}-{$wybrany_rok}</h2>

	<form action="{$_base_url}users/statystyki">
		<input type="hidden" name="id" value="{$a_user.id_users}">
		<select name="miesiac" id="miesiac" class="jRequired autoWidth">
		    <option value="01" {if isset($wybrany_miesiac) && $wybrany_miesiac==1}selected{/if}>styczeń</option>
		    <option value="02" {if isset($wybrany_miesiac) && $wybrany_miesiac==2}selected{/if}>luty</option>
		    <option value="03" {if isset($wybrany_miesiac) && $wybrany_miesiac==3}selected{/if}>marzec</option>
		    <option value="04" {if isset($wybrany_miesiac) && $wybrany_miesiac==4}selected{/if}>kwiecień</option>
		    <option value="05" {if isset($wybrany_miesiac) && $wybrany_miesiac==5}selected{/if}>maj</option>
		    <option value="06" {if isset($wybrany_miesiac) && $wybrany_miesiac==6}selected{/if}>czerwiec</option>
		    <option value="07" {if isset($wybrany_miesiac) && $wybrany_miesiac==7}selected{/if}>lipiec</option>
		    <option value="08" {if isset($wybrany_miesiac) && $wybrany_miesiac==8}selected{/if}>sierpień</option>
		    <option value="09" {if isset($wybrany_miesiac) && $wybrany_miesiac==9}selected{/if}>wrzesień</option>
		    <option value="10" {if isset($wybrany_miesiac) && $wybrany_miesiac==10}selected{/if}>październik</option>
		    <option value="11" {if isset($wybrany_miesiac) && $wybrany_miesiac==11}selected{/if}>listopad</option>
		    <option value="12" {if isset($wybrany_miesiac) && $wybrany_miesiac==12}selected{/if}>grudzień</option>
		</select>
		<select name="rok" id="rok" class="jRequired autoWidth">
		    <option {if isset($wybrany_rok) && $wybrany_rok==$rok-1}selected{/if}>{$rok-1}</option>
		    <option  {if isset($wybrany_rok) && $wybrany_rok==$rok}selected{/if}>{$rok}</option>
		</select>
		<input type="submit" value="Wybierz" class="autoWidth">
	</form>

	{if $a_statystyki}
		<table class="statystyki">
			<tr>
				<th>dzień</th>
				{foreach $a_statystyki as $dzien=>$a_dzien}
					<th>{$dzien}</th>
				{/foreach}
			</tr>
			<tr>
				<th>Odwiedziny</th>
				{foreach $a_statystyki as $dzien=>$a_dzien}
					<td class="center">
						{if $a_dzien}
							{$a_dzien.odwiedziny}
						{else}
							0
						{/if}
					</td>
				{/foreach}
			</tr>
			<tr>
				<th>Unikalne</th>
				{foreach $a_statystyki as $dzien=>$a_dzien}
					<td class="center">
						{if $a_dzien}
							{$a_dzien.unikalne}
						{else}
							0
						{/if}
					</td>
				{/foreach}
			</tr>
			<tr>
				<th>Wyświetlenia</th>
				{foreach $a_statystyki as $dzien=>$a_dzien}
					<td class="center">
						{if $a_dzien}
							{$a_dzien.wyswietlenia}
						{else}
							0
						{/if}
					</td>
				{/foreach}
			</tr>
			<tr>
				<th>Wyświetlenia unikalne</th>
				{foreach $a_statystyki as $dzien=>$a_dzien}
					<td class="center">
						{if $a_dzien}
							{$a_dzien.wyswietlenia_unikalne}
						{else}
							0
						{/if}
					</td>
				{/foreach}
			</tr>
		</table>
		
	{/if}

</section>
