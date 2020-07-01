<?php

class con_rozliczenia extends controllers_parent{
	
	public static function lista_rozliczen__admin_mod()
	{
		$dokument = empty($_GET['dokument']) ? false : $_GET['dokument'];
		$platnosci = empty($_GET['platnosci']) || $_GET['platnosci']=='wszystkie' ? false : $_GET['platnosci'];
		$id_sposoby_platnosci = isset($_GET['id_sposoby_platnosci']) ? $_GET['id_sposoby_platnosci'] : false;
		$wybrany_miesiac = isset($_GET['miesiac']) && (hlp_validator::id($_GET['miesiac']) || $_GET['miesiac']==0) && $_GET['miesiac']<=12 ? $_GET['miesiac'] : date('m');
		$wybrany_rok = isset($_GET['rok']) && hlp_validator::id($_GET['rok']) ? $_GET['rok'] : date('Y');

		$a_lata = db::get_many("SELECT DISTINCT YEAR(data_realizacji) as rok FROM zamowienia");
		view::add('a_lata', $a_lata);

		$a_zamowienia = mod_zamowienia::get_zamowienia('zrealizowane',false,$dokument,$platnosci,$id_sposoby_platnosci,false,false,$wybrany_miesiac,$wybrany_rok,false);
		
		view::add('a_zamowienia',$a_zamowienia);
		view::add('platnosci',$platnosci);
		view::add('id_sposoby_platnosci',$id_sposoby_platnosci);
		view::add('dokument',$dokument);
		view::add('a_sposoby_platnosci',db::get_all('sposoby_platnosci'));
		view::add('wszystkie_miesiace', 1);
		view::add('wybrany_miesiac', $wybrany_miesiac);
		view::add('wybrany_rok', $wybrany_rok);

		if(!empty($_GET['xml']) || !empty($_GET['csv']) || !empty($_GET['csv2']))
			$a_kontrahenci = mod_zamowienia::get_kontrahenci('zrealizowane',false,$dokument,$platnosci,$id_sposoby_platnosci,false,false,$wybrany_miesiac,$wybrany_rok);
		
		if(!empty($_GET['xml']) && $a_kontrahenci)
		{
			mod_rozliczenia::generuj_xml($a_kontrahenci, $a_zamowienia);
			app::ok("Pobierz plik <a href='".app::base_url()."get.php?typ=faktury.xml' target='_blank' download>faktury.xml</a>");
		}
		
		if(!empty($_GET['csv']) && $a_kontrahenci)
		{
			mod_rozliczenia::generuj_csv($a_kontrahenci);
			app::ok("Pobierz plik <a href='".app::base_url()."get.php?typ=faktury.csv' target='_blank' download>faktury.csv</a>");
		}
		
		if(!empty($_GET['csv2']) && $a_kontrahenci)
		{
			mod_rozliczenia::generuj_csv2($a_kontrahenci);
			app::ok("Pobierz plik <a href='".app::base_url()."get.php?typ=faktury2.csv' target='_blank' download>faktury2.csv</a>");
		}
		
		
		
		view::display();
	}
	
	public static function get_zapamietana_data_wplaty__admin_mod()
	{
		echo session::get('data_wplaty') ? session::get('data_wplaty') : date('Y-m-d');
	}
	
	public static function pobierz_wplaty__admin_mod()
	{
		$a_params = array();
		parse_str($_POST['a_wplaty'], $a_params);

		foreach($a_params['a_wplata'] as $id_zamowienia=>$a_wplata)
		{
			if(hlp_validator::data($a_wplata['data_wplaty']) && hlp_validator::price($a_wplata['kwota_wplaty']) && in_array($a_wplata['sposob_wplaty'],array('przelew','gotówka','przelewy24')))
				mod_rozliczenia::dodaj_wplate($a_wplata['data_wplaty'],$a_wplata['kwota_wplaty'],$a_wplata['sposob_wplaty'],$id_zamowienia);
			session::set('data_wplaty',$a_wplata['data_wplaty']);
		}

		app::ok('Wpłaty zapisane');
		echo 1;
	}
	
	public static function pokaz_wplaty__admin_mod()
	{
		if(empty($_GET['id_zamowienia']) || !hlp_validator::id($_GET['id_zamowienia']))
		{
			app::err('Nieznane zamówienie');
			view::message();
		}
		
		view::add('a_wplaty',mod_rozliczenia::get_wplaty($_GET['id_zamowienia']));
		view::display();
	}
	
	public static function formularz_ponaglenia__admin_mod()
	{
		if(empty($_GET['id_zamowienia']) || !hlp_validator::id($_GET['id_zamowienia']))
		{
			app::err('Nieznane zamówienie');
			view::message();
		}
		
		$a_zamowienia = mod_zamowienia::get_zamowienia(false,false,false,false,false,false,false,false,false,$_GET['id_zamowienia']);
		$a_zamowienie = $a_zamowienia[0];
		
		$wartosc_brutto = bcadd($a_zamowienie['liczba_kart']*$a_zamowienie['cena_legitymacji'],$a_zamowienie['cena_przesylki']);
		
		$a_tresc = db::get_by_id('sites', 52);
		
		$a_tresc['text'] = str_replace('[nr faktury]', $a_zamowienie['numer_faktury'], $a_tresc['text']);
		$a_tresc['text'] = str_replace('[data wystawienia]', $a_zamowienie['data_realizacji'], $a_tresc['text']);
		$a_tresc['text'] = str_replace('[wartosc brutto]', hlp_functions::to_price($wartosc_brutto), $a_tresc['text']);
		
		head::add_js_file(mod_panel::$js.'libs/ckeditor/ckeditor.js',false,'head');
		head::add_js_file(mod_panel::$js.'libs/tagsinput/jquery.tagsinput.min.js');
		
		view::add('a_zamowienie', $a_zamowienie);
		view::add('a_tresc', $a_tresc);
		view::display();
	}

	public static function wyslij_ponaglenie__admin_mod()
	{
		if(empty($_POST['a_ponaglenie']))
		{
			app::err('Brak danych');
			view::message();
		}
		
		$id_users = db::get_one("SELECT id_users FROM zamowienia WHERE id_zamowienia=".$_POST['a_ponaglenie']['id_zamowienia']);
		$email = db::get_one("SELECT email FROM users WHERE id_users=$id_users");
		
		mod_users::wyslij_maila($email,false,$_POST['a_ponaglenie']['text'],$_POST['a_ponaglenie']['title']);
		
		//mailer::add_address($email);
		//mailer::send($_POST['a_ponaglenie']['title'],$_POST['a_ponaglenie']['text'],false,true,strip_tags($_POST['a_ponaglenie']['text']));

		db::insert('ponaglenia', array('id_zamowienia'=>$_POST['a_ponaglenie']['id_zamowienia'],
									   'tytul'=>$_POST['a_ponaglenie']['title'],
									   'tresc'=>$_POST['a_ponaglenie']['text'], 
									   'data'=>'NOW()'));
		
		router::set_checkpoint('rozliczenia/lista-rozliczen');
		app::ok('Powiadomienie wysłane');
		view::message();
	}
}

?>