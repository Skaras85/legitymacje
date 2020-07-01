<section>
	<h1>Formularz migracji</h1>
	<form method="POST" action="index.php" class="jValidate">
		<input type="hidden" name="module" value="users">
		<input type="hidden" name="action" value="akceptuj_formularz_migracji">
		<input type="hidden" name="uniqid_users" value="{$uniqid_users}">
		<div class="marginTop20 clear">
			<input type="checkbox" name="regulamin" id="regulamin" class="iCheck jChecked" checked> <label for="regulamin">Akceptuję <a href="{$_base_url}strony/regulamin,11" class="modal">regulamin</a></label>
			<div></div>
			<input type="checkbox" name="polityka_prywatnosci" id="polityka_prywatnosci" class="iCheck jChecked" checked> <label for="polityka_prywatnosci">Akceptuję <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">politykę prywatności</a></label>
		</div>
		<div class="marginTop20">
			<input type="checkbox" name="zgoda1" id="zgoda1" class="iCheck jChecked" checked> <label for="zgoda1">Wyrażam zgodę na przejście praw i obowiązków z poprzednio zawartej między Placówką oświaty, w imieniu której działam, umowy powierzenia przetwarzania danych osobowych w aplikacji https://legitymacje.loca.pl , z Mariuszem Kociędą prowadzącym działalność gospodarczą pod firmą Mariusz Kocięda "Grupa Loca" (ze stałym adresem wykonywania działalności gospodarczej przy ul. Stefana Żeromskiego 6,13-200 Działdowo), na spółkę Grupa LOCA sp. z o.o. z siedzibą w Działdowie przy ul. Stefana Żeromskiego 6, 13-200 Działdowo</label>
			<input type="checkbox" name="zgoda2" id="zgoda2" class="iCheck jChecked" checked> <label for="zgoda2">Oświadczam, że jestem uprawniony do składania w/w oświadczeń w imieniu Placówki oświaty, którą reprezentuję</label>
		</div>
		<div class="marginTop20">
	    	<input type="checkbox" name="a_user[czy_newsletter]" id="newsletter" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter"> <label for="newsletter"  class="">( Opcjonalnie - nie jest wymagane do założenia konta )
			Wyrażam zgodę na otrzymywanie od firmy  Grupa LOCA Sp. Z o.o. informacji handlowych drogą elektroniczną, zgodnie z art. 10 ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną (t.j. Dz.U. z 2017 r., poz. 1219 z późn. zm.)</label>
			<input type="checkbox" name="a_user[czy_newsletter2]" id="newsletter2" class="iCheck jCheckMany newsletter" data-jcheckmany-target=".newsletter" data-jcheckmany-txt="Musisz zaznaczyc oba pola aby otrzymywać newsletter"> <label for="newsletter2" class="">(Opcjonalnie - nie jest wymagane do założenia konta)
			Wyrażam zgodę na przetwarzanie przez firmę Grupa LOCA Sp. Z o.o.  moich danych osobowych na potrzeby otrzymywania newslettera. Oświadczam, że zapoznałem/am się z obowiązkiem informacyjnym administratora danych, który administrator spełnia w II. 13 dokumentu „Polityka prywatności” dostępnego na <a href="{$_base_url}strony/polityka-prywatnosci,22" class="modal">{$_base_url}strony/polityka-prywatnosci,22</a>.</label>
		</div>
		<input type="submit" value="Akceptuję">
	</form>
</section>