
<section class="formSection">
	<form action="{$_base_url}" method="POST" class="jValidate">
		<h1 class="left autoWidth">Aktywuj swoje konto</h1> <input type="submit" value="Aktywuj konto" class="button green autoWidth buttonH1 left buttonIcon takButton">
		
		<input type="hidden" name="module" value="users">
		<input type="hidden" name="action" value="aktywuj_konto_confirm">
		<input type="hidden" name="a_user[hash]" value="{$token_maila_aktywacja_konta}">
		
	    <fieldset class="clearfix clear">
	    	<div class="left">
	        	
	        	<h2>Dane logowania</h2>
	        	<div class="password_wrapper">
		        	<label for="form_haslo">{if isset($a_user) && !$czy_przenoszenie}Nowe hasło{else}Hasło{/if}:</label>
		        	<input type="password" name="a_user[haslo]" placeholder="Tu wpisz swoje hasło, min. 8 dowolnych znaków" id="form_haslo" class="jEqual jMinLength {if !isset($a_user) && !session::who('admin')}jRequired{/if}" data-min-length="8" data-equal-to="form_powtorz_haslo" data-equal-txt="Hasła nie są identyczne">
		        	<img src="images/core/eye_icon.png" class="show_password">
		        	<div id="pass_strength" class="clear"><div></div></div>
		        </div>
		        
		        <div class="password_wrapper clearfix">
		        	<label for="form_powtorz_haslo">{if isset($a_user) && !$czy_przenoszenie}Powtórz nowe hasło{else}Powtórz hasło{/if}:</label>
		        	<input type="password" name="a_user[haslo_powtorzone]" placeholder="Tu ponownie wpisz swoje hasło" id="form_powtorz_haslo" class="{if !isset($a_user) && !session::who('admin')}jRequired{/if} jEqual"  data-equal-to="form_haslo" data-equal-txt="Hasła nie są identyczne">
	        		<img src="images/core/eye_icon.png" class="show_password">
	        	</div>
	        	
	        	
		   </div>
	       <div class="left">
		       		<input type="checkbox" name="regulamin" id="regulamin" class="iCheck jChecked"> <label for="regulamin">Akceptuję <a href="{$_base_url}strony/regulamin,11" class="modal">regulamin</a></label>
					<div></div>
					<input type="checkbox" name="polityka_prywatnosci" id="polityka_prywatnosci" class="iCheck jChecked"> <label for="polityka_prywatnosci">Akceptuję <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">politykę prywatności</a></label>
					

					<div class="marginTop20">
		            	<input type="checkbox" name="a_user[czy_newsletter]" id="newsletter" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter" {if isset($a_user) && !isset($smarty.session.form.a_user) && $a_user.czy_newsletter || !isset($a_user) && isset($smarty.session.form.a_user) && $smarty.session.form.a_user.czy_newsletter}checked{/if}> <label for="newsletter"  class="smallFont">( Opcjonalnie - nie jest wymagane do założenia konta )
						Wyrażam zgodę na otrzymywanie od firmy  Grupa LOCA Sp. Z o.o. informacji handlowych drogą elektroniczną, zgodnie z art. 10 ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną (t.j. Dz.U. z 2017 r., poz. 1219 z późn. zm.)</label>
						<input type="checkbox" name="a_user[czy_newsletter2]" id="newsletter2" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter" {if isset($a_user) && !isset($smarty.session.form.a_user) && $a_user.czy_newsletter || !isset($a_user) && isset($smarty.session.form.a_user) && $smarty.session.form.a_user.czy_newsletter2}checked{/if}> <label for="newsletter2" class="smallFont">(Opcjonalnie - nie jest wymagane do założenia konta)
						Wyrażam zgodę na przetwarzanie przez firmę Grupa LOCA Sp. Z o.o.  moich danych osobowych na potrzeby otrzymywania newslettera. Oświadczam, że zapoznałem/am się z obowiązkiem informacyjnym administratora danych, który administrator spełnia w II. 13 dokumentu „Polityka prywatności” dostępnego na <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">{$_base_url}strony/polityka-prywatnosci,22</a>.</label>
					
					</div>
	       </div>
		
		</fieldset>
	</form>
</section>
