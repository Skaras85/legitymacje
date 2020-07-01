<section class="printHide">
	<h1>Umowy placówki {$a_placowka.nazwa}</h1>
	<table>
		<tr>
			<th>L.p.</th>
			<th>Nazwa</th>
			<th>Podgląd i Akceptacja</th>
			<th>Data akceptacji</th>
			<th>Numer umowy</th>
			<th>Status</th>
			<th>Data potwierdzenia</th>
			<th>Podgląd skanu</th>
			{if session::get('czy_zdalny')}
				<th>Potwierdź</th>
			{/if}	
		</tr>
		{if $a_umowy}
			{foreach $a_umowy as $a_umowa}
				<tr>
					<td>{counter}</td>
					<td>{$a_umowa.title}</td>
					<td>{if !$a_umowa.a_umowa}<nobr><a href="{$_base_url}strony/{$a_umowa.sludge},{$a_umowa.id_sites}" class="modal button">Podgląd i akceptacja</a> <a href="{$_base_url}strony/strona/id/{$a_umowa.id_sites}/podglad-i-druk/1" class="modal button">Podgląd i druk</a></nobr>{else}&nbsp;{/if}</td>
					<td>{if $a_umowa.a_umowa}{$a_umowa.a_umowa.data_akceptacji}{else}&nbsp;{/if}</td>
					<td>{if $a_umowa.a_umowa}{$a_umowa.a_umowa.numer_umowy}{else}&nbsp;{/if}</td>
					<td>{if $a_umowa.a_umowa}{$a_umowa.a_umowa.status}{else}&nbsp;{/if}</td>
					<td>{if $a_umowa.a_umowa && $a_umowa.a_umowa.status=='potwierdzona'}{$a_umowa.a_umowa.data_potwierdzenia}{else}&nbsp;{/if}</td>
					<td>{if $a_umowa.a_umowa && $a_umowa.a_umowa.status=='potwierdzona'}<a href="{$_base_url}images/placowki/{session::get('id_placowki')}/potwierdzenia/{str_replace('/','-',$a_umowa.a_umowa.numer_umowy)}.pdf" class="button">podgląd</a>{else}&nbsp;{/if}</td>
					{if session::get('czy_zdalny')}
						<td>
							{if !$a_umowa.a_umowa || $a_umowa.a_umowa.status=='wydrukowana'}
								<form method="POST" action="index.php" enctype="multipart/form-data" class="jValidate">
									<input type="hidden" name="module" value="users">
									<input type="hidden" name="action" value="potwierdz_umowe">
									<input type="hidden" name="id_sites" value="{$a_umowa.id_sites}">
									<input type="file" name="potwierdzenie" class="jExtension jRequired" data-extensions="pdf">
									<input type="submit" value="Potwierdź" class="autoWidth">
								</form>
							{/if}
						</td>
					{/if}
				</tr>
			{/foreach}
		{/if}
	</table>
</section>