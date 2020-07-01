<section class="formSection">
	<article>
		<h1>{$a_opis.title}</h1>
		{$a_opis.text}
		
		{if $a_cenniki}
			<h2>Cennik</h2>
			<table>
				<tr>
					<th>Od</th>
					<th>Do</th>
					<th>Cena</th>
				</tr>
				{foreach $a_cenniki as $a_cennik}
					<tr>
						<td>{$a_cennik.od}</td>
						<td>{$a_cennik.do}</td>
						<td>{$a_cennik.cena|price} zł</td>
					</tr>
				{/foreach}
			</table>
		{/if}
		
		{if !isset($czy_bez_tekstu)}
	        <h2 class="marginTop20">{$a_strona.title}</h2>
	    	{$a_strona.text}
			<div class="center">
				{if $czy_umowa}
					<a href="{$_base_url}legitymacje/dodaj-legitymacje/id_karty/{$id_karty}" class="button green buttonIcon dalejButton">AKTYWUJ MOŻLIWOŚĆ ZAMAWIANIA</a>
				{else}
					<a href="{$_base_url}umowy/lista_umow" class="button green buttonIcon dalejButton">Przejdź do generatora umów</a>
				{/if}
			</div>
		{/if}
	</article>
</section>