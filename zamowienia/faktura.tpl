<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html charset=utf-8">
		<link rel="stylesheet" type="text/css" href="{$_base_url}css/faktura.css">
	</head>
	<body>
		<div class="newPage">
			{include file="zamowienia/faktura_content.tpl"}
		</div>
		<div class="newPage">
			{include file="zamowienia/faktura_content.tpl"}
		</div>
		
		{if !empty($a_zamowione_legitymacje)}
			<div class="protokol_title">PROTOKÓŁ WYKONANIA {if $a_zamowienie.id_karty==1}LEGITYMACJI NAUCZYCIELA{else}LEGITYMACJI SZKOLNYCH{/if}</div>
			<div class="protokol_data">
				Data zamówienia: {$a_zamowienie.data_zlozenia}<br>
				Numer zamówienia: {$a_zamowienie.numer_zamowienia}<br>
				Data zrealizowania: {$a_zamowienie.data_realizacji}
			</div>
			<table class="tabela">
				<tr>
					<th>L.p.</th>
					<th>ID</th>
					{if $a_zamowienie.id_karty==1}
						<th>Nazwisko 1</th>
						<th>Nazwisko 2</th>
						<th>Imię 1</th>
						<th>Imię 2</th>
						<th>Data ważności</th>
					{else}
						<th>Nazwisko</th>
						<th>Imie</th>
						<th>Nr legitymacji</th>
					{/if}
					<th class="podpis_blank">&nbsp;</th>
				</tr>
				{foreach $a_zamowione_legitymacje as $index=>$a_legitymacja}
					<tr>
						<td>{$index+1}</td>
						<td>{$a_legitymacja.id_legitymacje}</td>
						{if $a_zamowienie.id_karty==1}
							<td>{$a_legitymacja.kol3}</td>
							<td>{$a_legitymacja.kol4}</td>
							<td>{$a_legitymacja.kol1}</td>
							<td>{$a_legitymacja.kol2}</td>
							<td>{$a_legitymacja.kol5}</td>
						{else}
							<td>{$a_legitymacja.kol2}</td>
							<td>{$a_legitymacja.kol1}</td>
							<td>{$a_legitymacja.kol7}</td>
						{/if}
						<td>&nbsp;</td>
					</tr>
				{/foreach}
			</table>
			<div class="podpis_osoby_realizujacej">Podpis pracownika realizującej zamówienie</div>
		{/if}
	</body>
</html>
