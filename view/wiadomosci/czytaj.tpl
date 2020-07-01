<section>
	<article class="clearfix">
		{include file="wiadomosci/wiadomosci_menu.tpl"}
		
		<div class="left wiadomosciWrapper">
		    <p>
		        {if $a_wiadomosc.id_nadawcy!=session::get_id()}
		            Od: {if $a_wiadomosc.id_nadawcy!=0}{$a_wiadomosc.adresat}{else}Administracja{/if}
		        {else}
		            Do: {if $a_wiadomosc.id_nadawcy!=0}{$a_wiadomosc.nadawca}{else}Administracja{/if}
		        {/if}
		    </p>
		    <p>
		        Wysłano: {$a_wiadomosc.data_wyslania}
		    </p>
		    <p>
		        Temat: {$a_wiadomosc.temat}
		    </p>
		    <p>Treść:</p>
		    {$a_wiadomosc.tresc}
		
		    {if !empty($a_zalaczniki) || $a_zalaczniki_zewnetrzne}
		    	<p>Załączniki:</p>
		        <ul>
		        	<!--{if !empty($a_zalaczniki)}
			            {foreach $a_zalaczniki as $zalacznik}
			                <li>{$zalacznik|basename} <a href="{$_base_url}get.php?typ=zalacznik&id_wiadomosci={$a_wiadomosc.uniqid_wiadomosci}&nazwa={$zalacznik|basename}"  download>pobierz</a></li>
			            {/foreach}
		            {/if}-->
		            {if !empty($a_zalaczniki_zewnetrzne)}
			            {foreach $a_zalaczniki_zewnetrzne as $a_zalacznik}
			                <li>{$a_zalacznik.nazwa} <a href="{$_base_url}get.php?typ=zalacznik_zewnetrzny&id_wiadomosci={$a_zalacznik.uniqid_wiadomosci}&id_zalacznika={$a_zalacznik.id_wiadomosci_zalaczniki_zewnetrzne}"  download>pobierz</a></li>
			            {/foreach}
		            {/if}
		        </ul>
		    {/if}
		    
		    {if session::who('admin') && $a_wiadomosc.id_adresata==0 || $a_wiadomosc.id_adresata==session::get_id()}
		        <form method="post" action="{$_base_url}">
		        	<input type="hidden" name="module" value="wiadomosci">
		        	<input type="hidden" name="action" value="odpowiedz">
		            <input type="hidden" name="id_wiadomosci" value="{$a_wiadomosc.uniqid_wiadomosci}">
		            <input type="submit" value="Odpowiedz" class="marginTop20 green buttonIcon odpowiedzButton">
		        </form>
		    {/if}
		    
		    {if session::who('admin') && $a_wiadomosc.id_nadawcy!=0}
			    <form action="https://realizacja.loca.pl" method="POST" target="_blank">
	                <input type="hidden" name="module" value="users">
	                <input type="hidden" name="action" value="zaloguj_na_usera">
	                <input type="hidden" name="id_users" value="{$a_wiadomosc.id_nadawcy}">
	                <input type="hidden" name="id_pracownika" value="{session::get_id()}">
	                <input type="submit" value="Zaloguj" class="autoWidth">
	            </form>
            {/if}
		   </div>
	</article>
</section>