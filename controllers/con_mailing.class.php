<?php

class con_mailing extends controllers_parent{
	
	public static function historia__admin()
	{
		$a_osoby = mod_mailing::get_osoby_mailingu();
		view::add('a_osoby',$a_osoby);
		view::display();
	}
	
	public static function lista_szablonow__admin()
	{
		view::add('a_szablony',mod_mailing::get_szablony());
		view::display();
	}
	
	public static function formularz_szablonu__admin()
	{
		head::add_js_file(mod_panel::$js.'/libs/ckeditor/ckeditor.js',false,'head');

		if(!empty($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			$a_szablon = mod_mailing::get_szablon($_GET['id']);
			view::add('a_strona',$a_szablon);
			
			$a_zalaczniki = glob("images/zalaczniki/{$a_szablon['id_mailing_szablony']}-*");
			view::add('a_zalaczniki',$a_zalaczniki);
		}

		view::display();
	}
	
	public static function usun_zalacznik__admin()
	{
		unlink("images/zalaczniki/".$_POST['zalacznik']);
	}
	
	public static function zapisz_szablon__admin()
	{
		mod_mailing::zapisz_szablon($_POST['a_strona']);
		
		if(!app::get_result())
			view::redirect('mailing/formularz_powiadomienia/id/'.$_POST['a_strona']['id_mailing_szablony']);
		else
			view::redirect('mailing/lista-szablonow');
	}
	
	public static function get_szablony__admin()
	{
		view::json(true,'',array('a_szablony'=>mod_mailing::get_szablony()));
	}
/*
	public static function get_szablon_powiadomien()
	{
		if(!perms::check('czy-szablony-powiadomien') && !perms::check('czy-powiadomienia-rodzicow'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(empty($_GET['id']) || !mod_sites::sprawdz_dostep_szablonu_powiadomienia($_GET['id']))
			return false;
		
		view::json(true,'',array('a_szablon'=>mod_sites::get_szablon_powiadomienia($_GET['id'])));
	}
*/
	public static function get_podglad___admin()
	{
		if(empty($_GET['id_placowki']) || !hlp_validator::id($_GET['id_placowki']))
			exit;

		if(empty($_GET['id_mailing_szablony']) || !hlp_validator::id($_GET['id_mailing_szablony']))
			exit;

		$a_szablon = mod_mailing::get_szablon($_GET['id_mailing_szablony']);
		
		$a_szablon['text'] = mod_mailing::stworz_tresc_mailingu($_GET['id_mailing_szablony'],$a_szablon['text'],$_GET['id_placowki']);
		view::json(true,'',array('a_szablon'=>$a_szablon));
	}

	public static function podglad_mailingu__admin()
	{
		$a_mailing_osoby = db::get_by_id('mailing_osoby',$_GET['id_mailing_osoby']);
		$a_mailing = db::get_by_id('mailing',$a_mailing_osoby['id_mailing']);
		$a_mailing = array_merge($a_mailing,$a_mailing_osoby);

		view::add('a_mailing',$a_mailing);
		view::display();
	}

	public static function zapisz_mailing__admin()
	{
		if(!hlp_validator::id($_POST['id_mailing_szablony']) || !db::get_by_id('mailing_szablony',$_POST['id_mailing_szablony']))
			return false;
		
		if(empty($_POST['ids']))
			return false;

		mod_mailing::zapisz_mailing($_POST['id_mailing_szablony'],$_POST['ids']);
		view::json(app::get_result(),view::get_message());
	}
	
	public static function wyslij_mailing()
	{//db::deb();
		$a_mailingi = db::get_many("SELECT mailing.* FROM mailing WHERE status='open' ORDER BY id_mailing");

		if($a_mailingi)
		{
			foreach($a_mailingi as $a_mailing)
			{
				$a_adresaci = mod_mailing::get_adresaci($a_mailing['id_mailing'],10);
	
				if($a_adresaci)
					mod_mailing::wyslij_mailing($a_mailing,$a_adresaci);
				else
				{
					db::update('mailing',"id_mailing={$a_mailing['id_mailing']}",array('status'=>'closed',
																					   'date_end'=>'NOW()'));
				    self::wyslij_mailing();
			    }
		    }
		}
	}
	
	public static function wypisz()
	{
		if(empty($_GET['id']) && !hlp_validator::alfanum($_GET['id']))
		{
			app::err('Nieznany użytkownik');
			view::message();
		}

		db::update('users', "uniqid_users='{$_GET['id']}'", array('want_mailing'=>0));
		app::ok('Poprawnie wypisało z newslettera');
		view::message();
	}
	
	
	/*
	public static function pokaz_wyslane_powiadomienia()
	{
		if(!input::post('id_users') || !hlp_validator::id(input::post('id_users')) || ! mod_users::sprawdz_dostep(input::post('id_users')))
			return false;
		
		if(!input::post('miesiac') || !hlp_validator::id(input::post('miesiac')))
			return false;
		
		if(!input::post('rok') || !hlp_validator::id(input::post('rok')))
			return false;

		view::json(true,'',array('a_powiadomienia'=>db::get_many("SELECT mailing_osoby.*,powiadomienia_typy.nazwa FROM mailing_osoby JOIN mailing USING(id_mailing) JOIN powiadomienia_typy USING(id_powiadomienia_typy) WHERE mailing_osoby.id_users={$_POST['id_users']} AND rok={$_POST['rok']} AND miesiac={$_POST['miesiac']}")));
	}
	*/
	
	public static function lista_placowek__admin()
	{
		$search = !empty($_REQUEST['search']) ? $_REQUEST['search'] : false;
		//$a_czy_mailing_wynik = mod_users::get_wybrane_grupy('czy_mailing');
		//$czy_mailing = $a_czy_mailing_wynik['wybrane_czy_mailing'];
		$czy_mailing = isset($_REQUEST['czy_mailing']) ? $_REQUEST['czy_mailing'] : '';
		$rodzaj_konta = !empty($_REQUEST['rodzaj_konta']) && in_array($_REQUEST['rodzaj_konta'], array('wewnętrzne', 'standard'))  ? $_REQUEST['rodzaj_konta'] : '';
		
		$a_typy_szkol = mod_users::get_wybrane_grupy('typy_szkol');
		$typy_szkol = $a_typy_szkol['wybrane_typy_szkol'];
		
		$a_typy_legitymacji = mod_users::get_wybrane_grupy('typy_legitymacji');
		$typy_legitymacji = $a_typy_legitymacji['wybrane_typy_legitymacji'];

		if($search || !empty($_REQUEST['czy_wszystkie']) || $czy_mailing || $typy_szkol || $rodzaj_konta || $typy_legitymacji)
			view::add('a_placowki',mod_placowki::get_placowki($search,$czy_mailing,!empty($_REQUEST['czy_wszystkie']),$typy_szkol,$rodzaj_konta,$typy_legitymacji));

		view::add('a_typy_legitymacji', db::get_all('karty'));
		view::add('rodzaj_konta', $rodzaj_konta);
		view::add('a_typy_szkol', db::get_all('typy_szkol'));
		view::add('czy_wszystkie', !empty($_GET['czy_wszystkie']));
		view::add('search', $search);
		view::display();
	}
}

?>