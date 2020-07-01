<section class="formSection">

	<a href="legitymacje/lista-kart"><h2 id="panelTitle" title="Powrót">Dodaj legitymację do {$a_placowka.nazwa}</h2></a>

	<form action="{$_base_url}" method="POST" class="jValidate" enctype="multipart/form-data">
	    <fieldset class="clearfix">
    		{if $a_karty}
    			<p>Wybierz typ legitymacji który chcesz dodać</p>
	    		<ul class="lista_kart">
					{foreach $a_karty as $a_karta}
						<li>
							<a href="{$_base_url}legitymacje/dodaj-legitymacje/id_karty/{$a_karta.id_karty}/id_placowki/{$a_placowka.id_placowki}">
								<img src="images/karty/{$a_karta.img}" alt="">
							</a>
						</li>
					{/foreach}
				</ul>
	    	{else}
	    		<p>Do tej placówki dodane sa już wszystkie typy legitymacji</p>
	    	{/if}
		</fieldset>
	</form>
</section>
