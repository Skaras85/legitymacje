<nav class="addMenu horizontal printHide">
	<ul>{if !session::who('admin')}{/if}
		<li><a href="{$_base_url}wiadomosci/nowa-wiadomosc" class="button buttonIcon napiszButton">Nowa wiadomość</a></li>
		<li><a href="{$_base_url}wiadomosci/pokaz/typ/odebrane" class="button buttonIcon odebraneButton">Odebrane ({$liczba_odebranych_wiadomosci})</a></li>
		<li><a href="{$_base_url}wiadomosci/pokaz/typ/robocze" class="button buttonIcon roboczeButton">Robocze ({$liczba_roboczych_wiadomosci})</a></li>
		<li><a href="{$_base_url}wiadomosci/pokaz/typ/wyslane" class="button buttonIcon wyslaneButton">Wysłane ({$liczba_wyslanych_wiadomosci})</a></li>
	</ul>
</nav>