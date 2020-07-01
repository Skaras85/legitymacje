<section>
    {include file='mailing/mailing_menu.tpl'}
    <article>
		<h1 class="clear">Historia mailingu</h1>

		<table class="dataTables">
			<thead>
				<tr>
					<th>L.p.</th>
					<th>Placówka</th>
					<th>Email</th>
					<th>Nazwa szablonu</th>
					<th>Data wysłania</th>
					<th>Status</th>
					<th>Podgląd</th>
				</tr>
			</thead>
			<tbody>
				{foreach $a_osoby as $a_osoba}
	    			<tr>
	    				<td>{counter}</td>
	    				<td>{$a_osoba.nazwa}</td>
	    				<td>{$a_osoba.email}</td>
	    				<td>{$a_osoba.temat}</td>
	    				<td>{$a_osoba.data_wysylki}</td>
	    				<td>{$a_osoba.msg}</td>
	    				<td><a href="mailing/podglad_mailingu/id_mailing_osoby/{$a_osoba.id_mailing_osoby}" class="modal">Podgląd</a></td>
	    			</tr>
			  	{/foreach}
			</tbody>
		</table>
	</article>
</section>