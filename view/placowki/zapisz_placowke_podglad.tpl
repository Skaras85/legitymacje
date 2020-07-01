<section class="clearfix formSection">
	
	<h1>Sprawdź poprawność danych</h1>
	{if !isset($umowa)}
		<a href="{$_base_url}placowki/zapisz_placowke" class="button green buttonIcon takButton">Zapisz placówkę</a>
		<a href="{$_base_url}placowki/formularz-placowki{if isset($smarty.session.form.a_placowka.id_placowki)}/id/{$smarty.session.form.a_placowka.id_placowki}{/if}" class="button red right buttonIcon wsteczButton">Popraw</a>
	{else}
		<a href="{$_base_url}legitymacje/pobierz_umowe_aktywacji_karty/id_placowki/{$id_placowki}/id_karty/{$id_karty}/pdf/1" class="button green buttonIcon takButton" download>Potwierdzam dane i pobieram umowę</a>
	{/if}
	
	<div class="clear"></div>
	
	<div class="left">
		<h2 class="marginTop20">Dane placówki</h2>
		<p>REGON: {$smarty.session.form.a_placowka.regon}<br>
		Typ placówki: {$a_typ_szkoly.nazwa}<br>
		Pełna nazwa: {$smarty.session.form.a_placowka.nazwa}<br>
		Nazwa skrócona: {$smarty.session.form.a_placowka.nazwa_skrocona}<br>
		<p>
			Adres: {$smarty.session.form.a_placowka.adres}<br>
			Kod pocztowy: {$smarty.session.form.a_placowka.kod_pocztowy}<br>
			Poczta: {$smarty.session.form.a_placowka.poczta}<br>
			<!--Imię i nazwisko Dyrektora Placówki: {$smarty.session.form.a_placowka.dyrektor}<br>-->
		</p>
	</div>
	<div class="left">
		<h2>Dokument sprzedaży</h2>
		<p>
			Typ dokumentu: {if $a_user.typ=='placowka'}{$smarty.session.form.a_placowka.dokument_sprzedazy}{else}Faktura{/if}<br>
			{if $smarty.session.form.a_placowka.dokument_sprzedazy!='paragon'}
				Nazwa nabywcy: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.nabywca_nazwa}{else}{$a_user.nazwa}{/if}<br>
				Adres nabywcy: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.nabywca_adres}{else}{$a_user.ulica}{/if}<br>
				Kod pocztowy nabywcy: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.nabywca_kod_pocztowy}{else}{$a_user.kod_pocztowy}{/if}<br>
				Poczta nabywcy: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.nabywca_poczta}{else}{$a_user.miasto}{/if}<br>
				NIP nabywcy: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.nabywca_nip}{else}{$a_user.nip}{/if}<br>
			{/if}
		</p>
		{if $smarty.session.form.a_placowka.dokument_sprzedazy!='paragon'}
			<h2>Odbiorca / płatnik</h2>
			<p>
				Nazwa odbiorcy/płatnika: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.platnik_nazwa}{else}{$a_user.platnik_nazwa}{/if}<br>
				Adres odbiorcy/płatnika: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.platnik_adres}{else}{$a_user.platnik_adres}{/if}<br>
				Kod pocztowy odbiorcy/płatnika: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.platnik_kod_pocztowy}{else}{$a_user.platnik_kod_pocztowy}{/if}<br>
				Poczta odbiorcy/płatnika: {if $a_user.typ=='placowka'}{$smarty.session.form.a_dokument.platnik_poczta}{else}{$a_user.platnik_poczta}{/if}<br>
			</p>
		{/if}
	</div>
	<div class="left">
		<h2>Adres wysyłki</h2>
		<p>
			Nazwa: {if $a_user.typ=='placowka'}{$smarty.session.form.a_placowka.wysylka_nazwa}{else}{$a_user.wysylka_nazwa}{/if}<br>
			Adres wysyłki: {if $a_user.typ=='placowka'}{$smarty.session.form.a_placowka.wysylka_adres}{else}{$a_user.wysylka_adres}{/if}<br>
			Kod pocztowy wysyłki: {if $a_user.typ=='placowka'}{$smarty.session.form.a_placowka.wysylka_kod_pocztowy}{else}{$a_user.wysylka_kod_pocztowy}{/if}<br>
			Poczta wysyłki: {if $a_user.typ=='placowka'}{$smarty.session.form.a_placowka.wysylka_poczta}{else}{$a_user.wysylka_poczta}{/if}<br>
			Telefon i uwagi dla kuriera: {if $a_user.typ=='placowka'}{$smarty.session.form.a_placowka.uwagi_dla_kuriera}{else}{$a_user.uwagi_dla_kuriera}{/if}<br>
		</p>
	</div>
	
</section>