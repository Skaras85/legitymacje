<section>
	<h1>Przesyłka nr {$a_przesylka.numer_przesylki}</h1>
	<p>
		<strong>Numer listu:</strong> {$a_przesylka.numer_listu}<br>
		<strong>Data otrzymania:</strong> {$a_przesylka.data_otrzymania}<br>
		<strong>Data nadania:</strong> {$a_przesylka.data_nadania}<br>
		{if session::who('admin')}
			<strong>ID placówki:</strong> {$a_przesylka.id_placowki}<br>
		{else}
			<strong>Placówka:</strong> {$a_przesylka.nazwa_placowki}<br>
		{/if}
		<strong>Rodzaj przesyłki:</strong> {$a_przesylka.rodzaj}<br>
		<strong>Ilość legitymacji:</strong> {$a_przesylka.liczba_legitymacji}<br>
		{if session::who('admin')}<strong>ID zamówienia:</strong> {$a_przesylka.id_zamowienia}<br>{/if}
		<strong>Status zamówienia:</strong> {$a_przesylka.status_zamowienia}<br>
		{if session::who('admin')}
			<strong>Czy kompletne:</strong> {if $a_przesylka.czy_kompletne==1}tak{else}nie{/if}<br>
			<strong>Czy wysłać email:</strong> {if $a_przesylka.czy_mail==1}tak{else}nie{/if}<br>
			<strong>Adresat:</strong> {$a_przesylka.adresat}<br>
			<strong>Dodano:</strong> {$a_przesylka.add_date}<br>
			<strong>Dodał:</strong> {$a_przesylka.admin}<br>
			<strong>Uwagi:</strong>
		{/if}
	</p>
	<p>{$a_przesylka.uwagi}</p>
</section>