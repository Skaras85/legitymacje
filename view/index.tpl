{head::doctype()}
<html class="no-js" lang="{$lang}" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
	<head>
		{head::get_header()}
		
		<meta property="og:title" content="{head::$title}">
		<meta property="og:description" content="{head::$description}">
        
		<link rel="stylesheet" href="css/print.css" media="print">
        <link rel="shortcut icon" type="image/png" href="favicon.ico"/>

		{con_panel::get_background_css()}
	</head>
	<body data-is_sticky_header="{$is_sticky_header}" data-base-url="{$_base_url}" data-js="{$js}" class="">
		{if session::get('czy_zdalny')}
			<div id="zdalny_header" class="printHide">
				<div class="headerWrapper">
					<p>Użytkownik: {$smarty.session.a_user.imie_i_nazwisko}
					{if isset($a_wybrana_placowka)}
						, Placówka: {$a_wybrana_placowka.nazwa}
					{/if}
					</p>
				</div>
			</div>
		{/if}
	    <header id="mainHeader" class="clearfix printHide">
	    	<div class="headerWrapper">
		    	<a href="{$_base_url}{if session::is_logged()}{/if}" id="logo"><img src="{$_base_url}images/site/logo-loca.png" alt="Grupa Loca"></a>
		    	{if isset($czy_nowe_wiadomosci) && $czy_nowe_wiadomosci}
		    		<a href="{$_base_url}wiadomosci/pokaz/typ/odebrane" class="nowe_wiadomosci_icon"><img src="images/core/mail_icon.png" alt="Nowe wiadomości" title="Nowe wiadomości"></a>
		    	{/if}
		    	<div id="userNavWrapper" class="clearfix">
	    		    <nav id="accountNav">
	    		        <ul>
	   						<li><a href="{$_base_url}">Strona główna</a></li>
	   						<li><a href="{$_base_url}strony/formularze,14">Formularze</a></li>
	   						<li><a href="{$_base_url}strony/regulamin,15">Regulamin</a></li>
	   						<li><a href="{$_base_url}strony/rodo,16">RODO</a></li>
	   						<li><a href="{$_base_url}strony/cennik,17">Cennik</a></li>
	   						<li><a href="{$_base_url}strony/jak-zamowic,18">Jak zamówić</a></li>
	   						<li><a href="{$_base_url}strony/kontakt,19">Kontakt</a></li>
	   						{if session::who('admin')}
	   							<li><a href="{$_base_url}panel/panell">Panel</a></li>
	   						{/if}
	   						{if session::is_logged()}
	   							<li><a href="{$_base_url}users/wyloguj">Wyloguj</a></li>
	   						{/if}
	    		        </ul>
	    		    </nav>
			    </div>
	        </div>	
	    </header>
	    <div id="siteWrapper" class="clearfix">
	    	{if session::is_logged()}
				<nav class="printHide userNav clearfix">
					<ul>
						<li><a href="{$_base_url}placowki/lista-placowek" class="button buttonIcon placowkiButton">Placówki</a></li>
						<li><a href="{$_base_url}users/subkonta" class="button buttonIcon subkontaButton">Subkonta</a></li>
						{if session::get('id_placowki')}
							<li><a href="{$_base_url}zamowienia/lista_zamowien" class="button buttonIcon zamowieniaButton">Moje zamówienia</a></li>
						{/if}
						<li><a href="{$_base_url}przesylki/lista-przesylek" class="button buttonIcon pocztaButton">Poczta</a></li>
						<li><a href="{$_base_url}users/formularz-uzytkownika/id/{session::get_id()}" class="button buttonIcon kontoButton">Konto</a></li>
						{if session::get_user('typ')=='agencja'}
							<a href="{$_base_url}users/umowy" class="button buttonIcon umowyButton">Umowy</a>
						{/if}
						
						{if session::get('id_placowki')}
							{if isset($czy_placowka_ma_karty_szkolne) && $czy_placowka_ma_karty_szkolne}
								<li><a href="{$_base_url}produkty/pokaz-produkty/id/1" class="button">Hologramy</a></li>
							{/if}
							<li><a href="{$_base_url}koszyk/koszyk" class="button buttonIcon koszykButton">Koszyk (<span class="liczba_kart">{$liczba_kart_w_koszyku}</span>)</a></li>
						
							{if session::get('czy_zdalny')}
								<li><a href="{$_base_url}logi/lista" class="button">Poradnik</a></li>
							{/if}
						{/if}
						<li><a href="{$_base_url}strony/poradnik" class="button buttonIcon poradnikButton">Poradnik</a></li>
						<li><a href="{$_base_url}wiadomosci/pokaz/typ/odebrane" class="button buttonIcon wiadomosciButton">Wiadomości</a></li>
						{if session::who('admin') || session::who('mod')}
							<li><a href="#" class="button dropdownButton buttonIcon adminButton">Admin</a>
								<ul class="dropdownMenu hidden">
									{if session::who('admin')}
										<li><a href="{$_base_url}karty/lista-kart" class="button">Karty</a></li>
										<li><a href="{$_base_url}cenniki/lista-cennikow" class="button">Cenniki</a></li>
										<li><a href="{$_base_url}mailing/historia" class="button">Mailing</a></li>
									{/if}
									
									<li><a href="{$_base_url}przesylki/lista-placowek" class="button">Przesyłki</a></li>
									<li><a href="{$_base_url}zamowienia/lista-zamowien" class="button">Zamówienia</a></li>
									<li><a href="{$_base_url}rozliczenia/lista-rozliczen" class="button">Rozliczenia</a></li>
									<li><a href="{$_base_url}legitymacje/formularz-zdjec" class="button">Zdjęcia</a></li>
									<li><a href="{$_base_url}migracja/admin-migracja" class="button">Migracje</a></li>
									<li><a href="{$_base_url}zamowienia/lista_zamowien_druk" class="button">Druk</a></li>
									{if session::who('admin')}
										<li><a href="{$_base_url}panel/parametry" class="button">Parametry</a></li>
										<li><a href="{$_base_url}legitymacje/szukaj-legitymacji" class="button">Nauczyciele</a></li>
									{/if}
									<li><a href="{$_base_url}umowy/lista_umow_admin" class="button">Umowy</a></li>
								</ul>
							</li>
						{/if}
					</ul>
				</nav>
			{/if}
			<main class="{if !isset($is_main)}mainFullWidth{/if} clearfix">
				
                {if !app::get_result() and view::get_message()!='' and $_subpage!='system_view/komunikat.tpl'}
                    <p class='communicat_error'>{view::get_message()}</p>
                {/if}
                {if app::get_result() and view::get_message()!='' and $_subpage!='system_view/komunikat.tpl'}
                    <p class='communicat_ok'>{view::get_message()}</p>
                {/if}
                {include file=$_subpage}
            </main><!--leftSiteWrapper-->
		</div><!--siteWrapper-->
		<footer>
            <div id="footerContent" class="clearfix">
					<p>Grupa LOCA, 13-200 Działdowo, ul. Stefana Żeromskiego 6, tel. 23 696 90 00, FAX 23 682 14 08, <a href="http://www.loca.pl">www.loca.pl</a>, E-mail: legitymacje@loca.pl<br>
					Nasza działalność: Legitymacje nauczyciela, identyfikatory plastikowe, systemy kontroli dostępu, karty biblioteczne, smycze z nadrukiem.</p>
					<p>Korzystanie z tej witryny oznacza wyrażenie zgody na wykorzystanie plików cookies. Więcej informacji możesz znaleźć w naszej <a href="{$_base_url}strony/polityka-prywatnosci,22">Polityce prywatności.</a></p>
			</div>
        </footer>
        {if $is_go_to_top_button=='tak'}
		  <a href="#banner" class="goToTop">Idź do góry</a>
		{/if}

		{head::js_files()}
		{if isset($komunikat_popup)}
			<div id="komunikat_popup"></div>
		{/if}
	</body>
</html>

