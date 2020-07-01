<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=utf-8">
		<link rel="stylesheet" type="text/css" href="{$_base_url}css/placowka_potwierdzenie_pdf.css">
	</head>
	<body>
		<div class="center">
			<p class="bold">Formularz uzyskania dostępu do konta online</p>
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
				<p>Imię i nazwisko dyrektora: {$a_placowka.dyrektor}</p>
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
					<p>Nazwa: {$a_dokument.nabywca_nazwa}</p>
					<p>Adres: {$a_dokument.nabywca_adres}</p>
					<p>Kod pocztowy: {$a_dokument.nabywca_kod_pocztowy}</p>
					<p>Poczta: {$a_dokument.nabywca_poczta}</p>
					<p>NIP: {$a_dokument.nabywca_nip}</p>
				</div>
				<div class="right">
					<p class="bold">Dane odbiory / płatnika:</p>
					<p>Nazwa: {$a_dokument.platnik_nazwa}</p>
					<p>Adres: {$a_dokument.platnik_adres}</p>
					<p>Kod pocztowy: {$a_dokument.platnik_kod_pocztowy}</p>
					<p>Poczta: {$a_dokument.platnik_poczta}</p>
				</div>
			{/if}
		</div>
		<div class="marginTop20">
			{$a_text.text}
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