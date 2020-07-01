<html moznomarginboxes mozdisallowselectionprint>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=utf-8">
		<link rel="stylesheet" type="text/css" href="{$_base_url}css/legitymacja_ucznia_pdf.css">
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/JsBarcode.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/ITF.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/EAN_UPC.js"></script>
		<script src="{$_base_url}{$srv}libs/barcode/CODE128.js"></script>
		
		{literal}
			<script>
				$(function(){

					$('.id_placowki img').JsBarcode($('.id_placowki img').data('val')+'',{format: 'CODE128',width: 3, height: 40});
					
					$('.dane img').each(function(i,el){
						$(el).JsBarcode($(el).data('id')+'',{format: 'CODE128',width: 2, height: 40});
					});
            //128
				})
			</script>
		{/literal}
	</head>
	<body>
		<div class="top_left">
			{$a_placowka.nazwa}
		</div>
		<div class="top_right">
			<div class="id_placowki">
				<img data-val="{$a_placowka.id_placowki}">
				<p>{$a_placowka.id_placowki}</p>
			</div>
		</div>
		<div class="clear"></div>
		{foreach $a_legitymacje as $index=>$a_legitymacja}
			<div class="legitymacja">
				<div class="zdjecie">
					miejsce
					na
					przyklejenie
					kolorowego
					zdjęcia
				</div>
				<div class="dane clearfix">
					<div class="nazwisko">{$a_legitymacja['Nazwisko']}</div>
					<div class="imie">{$a_legitymacja['Imię']}</div>
					<div class="pesel">{$a_legitymacja['Pesel']|substr:0:6}*****</div>
					<img data-id="{$a_legitymacja['id_legitymacje']}">
				</div>
			</div>
			{if ($index+1)%10==0}<div class="break margin clear"></div>{/if}
		{/foreach}
	</body>
</html>