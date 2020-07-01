<section>
	<h1>{if isset($czy_szkoly)}Szkoły{else}Pracodawcy{/if}</h1>
	
	{if session::who('admin') || session::get('czy_zdalny') || !$czy_umowa_wazna}
		<a href="{$_base_url}placowki/formularz-pracodawcy{if isset($czy_szkoly)}/czy_szkoly/1{/if}" class="button green buttonIcon takButton">Dodaj</a>
	{/if}
	
	{if $a_pracodawcy}
		<table class="marginTop20" id="pracodawcy">
			<tr>
				<th>ID</th>
				<th>Nazwa</th>
				{if session::who('admin') || !$czy_umowa_wazna}
					<th>Edytuj</th>
					<th>Usuń</th>
				{/if}
			</tr>
			{foreach $a_pracodawcy as $a_pracodawca}
				<tr data-id="{$a_pracodawca.id_pracodawcy}">
					<td>{$a_pracodawca.id_pracodawcy}</td>
					<td>{$a_pracodawca.dane1}</td>
					{if session::who('admin') || session::get('czy_zdalny') || !$czy_umowa_wazna}
						<td><a href="{$_base_url}placowki/formularz-pracodawcy/id/{$a_pracodawca.id_pracodawcy}{if isset($czy_szkoly)}/czy_szkoly/1{/if}" class="modal">edytuj</a></td>
						<td><a href="#" class="czy_usunac_pracodawce">usuń</a></td>
					{/if}
				</tr>
			{/foreach}
		</table>
	{/if}
	
</section>