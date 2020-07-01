<section id="blog">
	<article class="sectionContent">
		<p>
			Dziękujemy za złożenie zamówienia. <br>
			Aby je opłacić skorzystaj z poniższego linku:<br>
			
			<form action="https://{if $p24_env=='sandbox'}sandbox.przelewy24.pl/trnDirect{else}secure.przelewy24.pl/trnDirect{/if}" method="post">
    	
		    	<input type="hidden" name="p24_merchant_id" value="{$p24_id_sprzedawcy}">
		    	<input type="hidden" name="p24_pos_id" value="{$p24_id_sprzedawcy}">
		    	<input type="hidden" name="p24_session_id" value="{$numer_zamowienia}">
		    	<input type="hidden" name="p24_amount" value="{$cena}">
		    	<input type="hidden" name="p24_currency" value="PLN">
		    	<input type="hidden" name="p24_description" value="Zamowienie nr {$numer_zamowienia}">
		    	<input type="hidden" name="p24_email" value="{$email}">
		    	<input type="hidden" name="p24_country" value="PL">
		    	<input type="hidden" name="p24_url_return" value="{$_base_url}koszyk/check_payment">
		    	<input type="hidden" name="p24_url_status" value="{$_base_url}koszyk/response_to_p24">
		    	<input type="hidden" name="p24_api_version" value="3.2">
		    	<input type="hidden" name="p24_sign" value="{$p24_crc}">
		
		        <div class="center">
		            <input type="submit" value="Opłać przez przelewy24"><br>
		        </div>
		    </form>
		</p>
	
	</article>
</section>