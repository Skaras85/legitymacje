<?php

class con_wiadomosci extends controllers_parent{
	
	public static $default_action = 'pokaz';

	public static function nowa_wiadomosc__lg()
	{
		head::add_js_file(mod_panel::$js.'/libs/ckeditor/ckeditor.js',false,'head');

		view::add('js', mod_panel::$js);
		view::display('wiadomosci/form_wiadomosci.tpl');
	}
	
	public static function edytuj__lg()
	{
		if(!isset($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err("Nieprawidłowy numer wiadomości");
			view::message();
		}
		
		$a_wiadomosc = mod_wiadomosci::get_wiadomosc($_GET['id']);
		
		if(!app::get_result())
			view::message();
		
		view::add('adresat',db::get_one("SELECT concat(imie,' ',nazwisko) FROM users WHERE id_users=".$a_wiadomosc['id_adresata']));
		head::add_js_file(mod_panel::$js.'/libs/ckeditor/ckeditor.js',false,'head');
		view::add('a_wiadomosc',$a_wiadomosc);
		
		view::add('js', mod_panel::$js);
		view::display('wiadomosci/form_wiadomosci.tpl');
	}

	public static function zapisz_robocza_ajax__lg()
	{
		parse_str($_POST['a_wiadomosc'], $a_wiadomosc);
		$a_wiadomosc = $a_wiadomosc['a_wiadomosc'];
		$a_wiadomosc['tresc'] = $_POST['tresc'];
		$a_wiadomosc['status'] = 'robocza';
		$a_wiadomosc['id_adresata'] = !session::who('admin') ? 0 : $a_wiadomosc['id_adresata'];
		$a_wiadomosc['id_nadawcy'] = !session::who('admin') ? session::get_id() : 0;

		if(empty($a_wiadomosc['id_wiadomosci']))
			$id_wiadomosci = mod_wiadomosci::nowa_wiadomosc($a_wiadomosc);
		else
			$id_wiadomosci = mod_wiadomosci::edytuj_wiadomosc($a_wiadomosc);

		view::json(app::get_result(),view::get_message(),array('id_wiadomosci'=>$id_wiadomosci));
	}

	public static function wyslij_wiadomosc__lg()
	{
		$a_wiadomosc = $_POST['a_wiadomosc'];
		$a_wiadomosc['status'] = 'wyslana';
		$a_wiadomosc['id_adresata'] = !session::who('admin') ? 0 : $a_wiadomosc['id_adresata'];
		$a_wiadomosc['id_nadawcy'] = !session::who('admin') ? session::get_id() : 0;

		if($a_wiadomosc['id_wiadomosci']=='')
			$id_wiadomosci = mod_wiadomosci::nowa_wiadomosc($a_wiadomosc);
		else
			$id_wiadomosci = mod_wiadomosci::edytuj_wiadomosc($a_wiadomosc);
		
		mod_wiadomosci::zapisz_zalaczniki($_FILES,$id_wiadomosci);
		
		db::update('users','id_users='.$a_wiadomosc['id_adresata'],array('czy_nowe_wiadomosci'=>1));
/*
		if(mod_wiadomosci::czy_wyslac_maila($a_wiadomosc['id_adresata']))
		{
			$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_wiadomosc['id_adresata']);
			$a_mail = db::get_row("SELECT * FROM sites WHERE id_sites=2");
			mailer::add_address($email);
			mailer::send($a_mail['title'],$a_mail['text'],false,true);
		}
*/
		view::redirect('wiadomosci/pokaz/typ/odebrane');
	}
	
	public static function odpowiedz__lg()
	{
		if(!isset($_POST['id_wiadomosci']) || !hlp_validator::id($_POST['id_wiadomosci']))
		{
			app::err("Nieznana wiadomość");
			view::message();
		}
		
		$a_wiadomosc = mod_wiadomosci::get_wiadomosc($_POST['id_wiadomosci']);
		
		$a_wiadomosc['temat'] = 'Odp: '.$a_wiadomosc['temat'];
		$a_wiadomosc['id_adresata'] = $a_wiadomosc['id_nadawcy'];
		
		$nadawca = empty($a_wiadomosc['nadawca']) ? "Administracja" : $a_wiadomosc['nadawca'];
		
		$a_wiadomosc['tresc'] = "<p></p><p>{$nadawca} napisał dnia {$a_wiadomosc['data_wyslania']}: </p><blockquote>" . $a_wiadomosc['tresc'] .'</blockquote>';
		$a_wiadomosc['id_wiadomosci'] = '';

		if(session::get('is_mobile'))
		{
			$a_wiadomosc['tresc'] = str_replace('<br>', "\r\n", $a_wiadomosc['tresc']);
			$a_wiadomosc['tresc'] = strip_tags($a_wiadomosc['tresc']);
		}
		
		if(!app::get_result())
			view::message();
		
		head::add_js_file(mod_panel::$js.'/libs/ckeditor/ckeditor.js',false,'head');
		view::add('a_wiadomosc',$a_wiadomosc);
		view::add('js', mod_panel::$js);
		view::display('wiadomosci/form_wiadomosci.tpl');
	}
	
	public static function pokaz__lg()
	{
		if(empty($_GET['typ']) || !in_array($_GET['typ'], array('odebrane','wyslane','robocze')))
			$_GET['typ'] = 'odebrane';

		$a_wiadomosci = mod_wiadomosci::get_wiadomosci($_GET['typ']);

		if($_GET['typ']=='wyslane')
			view::add('wyslane',true);
		
		if($_GET['typ']=='robocze')
			view::add('robocze',true);

		db::update('users','id_users='.session::get_id(),array('czy_nowe_wiadomosci'=>0));
		
		view::add('a_wiadomosci',$a_wiadomosci);
		view::add('typ',$_GET['typ']);
		view::add('rodzaj',$rodzaj);
		view::add('fraza',$fraza);
		view::display('wiadomosci/lista.tpl');
	}
	
	public static function czytaj__lg()
	{
		if(!isset($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err("Nieznana wiadomość");
			view::message();
		}
		
		$a_wiadomosc = mod_wiadomosci::get_wiadomosc($_GET['id']);
		
		if(session::get('is_mobile'))
		{
			$a_wiadomosc['tresc'] = '<div style="font-size:24px">'.$a_wiadomosc['tresc'].'</div>';
		}
		
		if(!app::get_result())
			view::message();
		
		if($a_wiadomosc['data_przeczytania']=='0000-00-00 00:00:00')
			mod_wiadomosci::przeczytaj_wiadomosc($_GET['id']);
		
		view::add('a_zalaczniki',mod_wiadomosci::get_zalaczniki($a_wiadomosc['id_wiadomosci'],$a_wiadomosc['id_nadawcy']));
		view::add('a_zalaczniki_zewnetrzne',mod_wiadomosci::get_zalaczniki_zewnetrzne($a_wiadomosc['id_wiadomosci']));
		
		view::add('a_wiadomosc',$a_wiadomosc);
		view::display();
	}

	public static function ustawienia__lg()
	{
		view::add('czy_wyslac_maila',mod_wiadomosci::czy_wyslac_maila(session::get_id()));
		view::add('czy_wyslac_maila_cron',mod_wiadomosci::czy_wyslac_maila_cron(session::get_id()));
		view::add('user_panel_active',self::get_active_menu());
		view::display();
	}
	
	public static function zapisz_ustawienia__lg()
	{
		if(!isset($_POST['a_ustawienia']))
			$_POST['a_ustawienia']=array();
		
		mod_wiadomosci::zapisz_ustawienia($_POST['a_ustawienia'],session::get_owner_id());
		view::add('user_panel_active',self::get_active_menu());
		view::message();
	}
	
	public static function usun__lg()
	{
		mod_wiadomosci::usun($_POST['ids']);
	}
	
	public static function cron_wyslij_maila_o_nowych_wiadomosciach()
	{
		$a_users = mod_wiadomosci::get_users_nowe_wiadomosci();
		foreach($a_users as $a_user)
		{
			if(mod_wiadomosci::czy_wyslac_maila_cron($a_user['id_adresata']))
			{
				$a_mail = db::get_row("SELECT * FROM sites WHERE id_sites=2");
				mailer::add_address($a_user['email']);
				$a_mail['text'] = str_replace('{{liczba_wiadomosci}}', $a_user['liczba_wiadomosci'], $a_mail['text']);
				mailer::send($a_mail['title'],$a_mail['text'],false,true);	
			}
		}
	}
}

?>