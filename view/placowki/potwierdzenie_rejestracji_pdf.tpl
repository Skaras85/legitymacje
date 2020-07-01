<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=utf-8">
		<link rel="stylesheet" type="text/css" href="{$_base_url}css/placowka_potwierdzenie_pdf.css">
	</head>
	<body>
		<div class="center">
			<p class="bold">POTWIERDZENIE REJESTRACJI</p>
			<p>w systemie zamawiania legitymacji dla oświaty https://legitymacje.loca.pl</p>
		</div>
		<p class="bold marginTop20">Właściciel systemu:</p>
		<p>Firma: Grupa LOCA sp. z o.o. z siedzibą w Działdowie przy ul. Stefana Żeromskiego 6, 13-200 Działdowo, wpisana do rejestru przedsiębiorców prowadzonego  przez Sąd Rejonowy w Olsztynie VIII Wydział Gospodarczy KRS pod numerem 0000735680,  kapitał zakładowy 20 000 złotych, numer NIP: 571-171-80-88, numer REGON: 380791636.</p>
		<div class="marginTop20 clearfix">
			<div class="left">
				<p class="bold">Dane placówki:</p>
				<p>Nazwa: {$a_placowka.nazwa}</p>
				<p>Adres: {$a_placowka.adres}</p>
				<p>Kod pocztowy: {$a_placowka.kod_pocztowy}</p>
				<p>Poczta: {$a_placowka.poczta}</p>
				<!--<p>Imię i nazwisko dyrektora: {$a_placowka.dyrektor}</p>-->
				<p>ID placówki: {$a_placowka.id_placowki}</p>
			</div>
			<div class="right">
				<p class="bold">Osoba upoważniona do składania zamówień:</p>
				<p>Imię i nazwisko: {$a_user.imie} {$a_user.nazwisko}</p>
				<p>Telefon: {$a_user.telefon}</p>
				<p>Adres e-mail: {$a_user.email}</p>
			</div>
		</div>
		<div class="marginTop20 clearfix">
			<p>Dokument sprzedaży: {$a_placowka.dokument_sprzedazy}</p>
			{if $a_placowka.dokument_sprzedazy=='faktura'}
				<div class="left">
					<p class="bold">Dane Nabywcy:</p>
					<p>Nazwa: {if $a_user.typ=='placowka'}{$a_dokument.nabywca_nazwa}{else}{$a_user.nabywca_nazwa}{/if}</p>
					<p>Adres: {if $a_user.typ=='placowka'}{$a_dokument.nabywca_adres}{else}{$a_user.nabywca_adres}{/if}</p>
					<p>Kod pocztowy: {if $a_user.typ=='placowka'}{$a_dokument.nabywca_kod_pocztowy}{else}{$a_user.nabywca_kod_pocztowy}{/if}</p>
					<p>Poczta: {if $a_user.typ=='placowka'}{$a_dokument.nabywca_poczta}{else}{$a_user.nabywca_poczta}{/if}</p>
					<p>NIP: {if $a_user.typ=='placowka'}{$a_dokument.nabywca_nip}{else}{$a_user.nabywca_nip}{/if}</p>
				</div>
				<div class="right">
					<p class="bold">Dane odbiory / płatnika:</p>
					<p>Nazwa: {if $a_user.typ=='placowka'}{$a_dokument.platnik_nazwa}{else}{$a_user.platnik_nazwa}{/if}</p>
					<p>Adres: {if $a_user.typ=='placowka'}{$a_dokument.platnik_adres}{else}{$a_user.platnik_adres}{/if}</p>
					<p>Kod pocztowy: {if $a_user.typ=='placowka'}{$a_dokument.platnik_kod_pocztowy}{else}{$a_user.platnik_kod_pocztowy}{/if}</p>
					<p>Poczta: {if $a_user.typ=='placowka'}{$a_dokument.platnik_poczta}{else}{$a_user.platnik_poczta}{/if}</p>
				</div>
			{/if}
		</div>
		<p class="marginTop20">
			Akceptuję regulamin systemu zamieszczony na stronie http://legitymacjanauczyciela.pl/oferta/regulamin 
		</p>
		<div class="clearfix pieczatki">
			<div class="left">
				<p>Pieczątka placówki</p>
			</div>
			<div class="right">
				<p>Data i podpis Dyrektora</p>
			</div>
		</div>
		<div class="stopka">
			Podpisany formularz należy przesłać FAX-em na numer 23 682 14 08  lub skanem na adres: legitymacje@loca.pl . Po jego otrzymaniu konto zostanie aktywowane.
		</div>
	</body>
</html>