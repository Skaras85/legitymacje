<section>
	{include file='mailing/mailing_menu.tpl'}

    <article>
		<h1 class="clear">Zarządzaj szablonami mailingu</h1>
		
		<a href="{$_base_url}mailing/formularz_szablonu" class="button">Dodaj nowy</a>

		{if !empty($a_szablony)}
			<table class="marginTop20">
				<thead>
					<tr>
						<th>Temat</th>
						<th>Edycja</th>
					</tr>
				</thead>
				<tbody>
					{foreach $a_szablony as $a_szablon}
		    			<tr>
		    				<td>{$a_szablon.temat}</td>
		    				<td><a href="mailing/formularz_szablonu/id/{$a_szablon.id_mailing_szablony}">edytuj</a></td>
		    			</tr>
				  	{/foreach}
				</tbody>
			</table>
		{/if}
	</article>
</section>