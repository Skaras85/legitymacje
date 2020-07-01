<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=utf-8">
		<link rel="stylesheet" type="text/css" href="{$_base_url}css/legitymacja_nauczyciela_pdf.css">
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/JsBarcode.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/ITF.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/EAN_UPC.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/CODE128.js"></script>
		
		{literal}
			<script>
				$(function(){

					$('.id_nauczyciela img').JsBarcode($('.id_nauczyciela img').data('val')+'',{format: 'CODE128',width: 2, height: 20});
            //128
				})
			</script>
		{/literal}
	</head>
	<body>
		{foreach $a_legitymacje as $a_legitymacja}
			<div class="formularz">
				<div class="top_left">
					<div class="id_nauczyciela">ID {$a_legitymacja.id_legitymacje} <img data-val="{$a_legitymacja.id_legitymacje}"></div>
					<p class="numer clear">numer nadany podczas elektronicznego zamawiania</p>
					<div class="legitymacja">
				
					</div>
					<p class="okres_umowy_label">OKRES OBOWIĄZYWANIA<br>UMOWY O PRACĘ:</p>
					<div class="okres_umowy clearfix">
						<div class="okres_umowy_kratka">{if $a_legitymacja['Okres zatrudnienia']!=''}X{/if}</div>
						<p class="okres_umowy_napis">do {if $a_legitymacja['Okres zatrudnienia']!=''}{$a_legitymacja['Okres zatrudnienia']}{/if}</p>
					</div>
					<div class="okres_umowy clearfix">
						<div class="okres_umowy_kratka">{if $a_legitymacja['Okres zatrudnienia']==''}X{/if}</div>
						<p class="okres_umowy_napis">na czas nieokreślony</p>
					</div>
					<p class="ookres_umowy_przypis">
						W PRZYPADKU ZATRUDNIENIA NA CZAS<br>
						NIEOKREŚLONY, LEGITYMACJA ZOSTANIE<br>
						WYDRUKOWANA Z DATĄ WAŻNOŚCI 3 LAT.
					</p>
				</div>
				<div class="top_right">
					<p class="title">FORMULARZ PERSONALIZACJI LEGITYMACJI NAUCZYCIELA</p>
					<p class="under_title">(po zeskanowaniu zostanie w całości odesłany razem z legitymacjami)</p>
					<p class="nazwiska"><span>NAZWISKA</span> dwa wiersze, do 14 znaków każdy.</p>
					<p class="nazwisko">{$a_legitymacja['Nazwisko']}</p>
					<p class="nazwisko">{$a_legitymacja['Nazwisko 2']}</p>
					
					<p class="nazwiska"><span>IMIONA</span> dwa wiersze, do 14 znaków każdy.</p>
					<p class="nazwisko">{$a_legitymacja['Imię']}</p>
					<p class="nazwisko">{$a_legitymacja['Imię 2']}</p>
					
					<div class="clearfix wzor_podpisu">
						<div class="wzor_podpisu_text">
							<p class="wzor_podpisu_label">WZÓR PODPISU</p>
							<p class="wzor_podpisu_przypis">
								- prosimy nie przekraczać<br>
								wyznaczonego pola,<br>
								- podpis w kolorze czarnym.
							</p>
						</div>
						<div class="wzor_podpisu_wzor"></div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="terms">
					</div>
				<div class="podpisy clearfix">
					<div class="pieczatka">PIECZĄTKA PLACÓWKI</div>
					<div class="podpis">PODPIS DYREKTORA LUB OSOBY UPOWAŻNIONEJ</div>
				</div>
			</div>
		{/foreach}
	</body>
</html>