<?php

class con_umowy extends controllers_parent{

	public static function lista_umow__lg()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Nieprawidłowa placówka');
			view::message();
		}

		view::add('a_opis',db::get_by_id('sites', 54));
		view::add('a_placowka',mod_placowki::get_placowka(session::get('id_placowki')));
		view::add('a_umowy',mod_umowy::get_umowy(session::get('id_placowki')));
		view::add('a_pracodawcy', mod_placowki::get_pracodawcy(session::get('id_placowki')));
		view::display();
	}
	
	public static function formularz_umowy_krok0()
	{
		if(!session::is_logged())
		{
			app::err('Brak dostępu');
			view::message();	
		}

		if(isset($_SESSION['a_umowa']['id_umowy_naglowki']))
			unset($_SESSION['a_umowa']['id_umowy_naglowki']);

		view::display();
	}
	
	public static function formularz_umowy_krok1()
	{
		if(!session::is_logged() && (empty($_GET['hash']) || !hlp_validator::alfanum($_GET['hash'])))
		{
			app::err('Brak dostępu');
			view::message();	
		}

		if(isset($_GET['hash']))
		{
			$a_placowka = db::get_row("SELECT * FROM placowki WHERE link_generatora_umowa_hash='{$_GET['hash']}'");

			if(!$a_placowka || strtotime(date('Y-m-d'))>strtotime($a_placowka['link_generatora_umowa_data_waznosci']))
			{
				app::err('Data ważności linku wygasła');
				view::message();
			}
			
			session::set('id_placowki', $a_placowka['id_placowki']);
		}

		if(isset($_SESSION['a_umowa']['id_umowy_naglowki']))
			unset($_SESSION['a_umowa']['id_umowy_naglowki']);
		
		$wariant_umowy = isset($_GET['wariant_umowy']) ? $_GET['wariant_umowy'] : 'pelny';

		view::add('czy_ma_typ_umowy_2', mod_umowy::czy_ma_typ_umowy(2));
		view::add('a_umowy_typy', mod_umowy::get_umowy_typy());
		view::add('a_umowy_naglowki', mod_umowy::get_umowy_naglowki());
		view::add('hash', isset($_GET['hash']) ? $_GET['hash'] : false);
		view::add('wariant_umowy', $wariant_umowy);

		view::display();
	}
	
	public static function formularz_umowy_wybor_elegitymacji()
	{
		if(!isset($_POST['a_umowa']) && isset($_GET['id_umowy_typy']) && isset($_GET['id_umowy_naglowki']))
		{
			$_POST['a_umowa']['id_umowy_typy'] = $_GET['id_umowy_typy'];
			$_POST['a_umowa']['id_umowy_naglowki'] = $_GET['id_umowy_naglowki'];
		}
		
		if(isset($_POST['a_umowa']))
		{
			if(!mod_umowy::check_umowa_data($_POST['a_umowa'], true))
				view::message();

			view::add('a_umowa', $_POST['a_umowa']);
		}
		else
		{
			app::err("brak danych");
			view::message();
		}

		if(session::get('a_karty'))
			view::add('a_wybrane_karty', array_keys(session::get('a_karty')));
		view::add('hash', isset($_GET['hash']) ? $_GET['hash'] : false);
		view::add('a_karty', db::get_many('SELECT * FROM karty WHERE id_karty<>1'));
		view::display();
	}
	
	public static function formularz_umowy_krok2()
	{
		if(!session::is_logged() && (empty($_POST['hash']) || !hlp_validator::alfanum($_POST['hash'])))
		{
			app::err('Brak dostępu');
			view::message();	
		}
		
		if(isset($_POST['a_umowa']))
		{
			if(!empty($_POST['hash']))
				$_POST['a_umowa']['id_umowy_typy'] = 1;
			
			if(!mod_umowy::check_umowa_data($_POST['a_umowa'], true))
				view::message();

			view::add('a_umowa', $_POST['a_umowa']);
		}
		else
		{
			app::err("brak danych");
			view::message();
		}
		
		if($_POST['a_umowa']['id_umowy_typy']=2 && !empty($_POST['a_karty']))
		{
			session::set('a_karty', $_POST['a_karty']);
			view::add('a_karty', $_POST['a_karty']);
		}

		view::add('hash', isset($_POST['hash']) ? $_POST['hash'] : false);
		view::add('a_placowka', mod_placowki::get_placowka(session::get('id_placowki')));
		view::add('a_dane_nabywcy', mod_placowki::get_dokumenty_sprzedazy(session::get('id_placowki')));
		view::add('czy_szkoly_lub_pracodawcy',mod_placowki::get_pracodawcy(session::get('id_placowki'),$_POST['a_umowa']['id_umowy_typy']==2 ? true : false));
		view::add('wariant_umowy', $_POST['a_umowa']['wariant_umowy']);
		
		view::display();
	}
	
	public static function podglad_umowy()
	{
		if(!session::is_logged() && (empty($_POST['hash']) || !hlp_validator::alfanum($_POST['hash'])))
		{
			app::err('Brak dostępu');
			view::message();	
		}
		
		$_SESSION['app']['save_result'] = 1;
		if(!isset($_POST['a_umowa']))
		{
			app::err('Brak danych');
			view::message();
		}
		
		if(!mod_umowy::check_umowa_data($_POST['a_umowa']))
			view::redirect('umowy/formularz_umowy_krok1');

		$_SESSION['a_umowa'] = $_POST['a_umowa'];
		
		if(!empty($_POST['hash']))
		{
			session::set('hash', $_POST['hash']);
			view::add('hash', $_POST['hash']);
		}

		view::add('umowa', mod_umowy::generuj_umowe($_POST['a_umowa']['id_umowy_naglowki'], $_POST['a_umowa']['id_umowy_typy'], $_POST['a_umowa'],  $_POST['a_umowa']['wariant_umowy']));

		view::display();
	}
	
	public static function zapisz()
	{
		if(!session::is_logged() && empty(session::get('hash')))
		{
			app::err('Brak dostępu');
			view::message();	
		}
		
		$_SESSION['app']['save_result'] = 1;
		if(!isset($_SESSION['a_umowa']))
		{
			app::err('Brak danych');
			view::message();
		}
		
		$id_umowy = mod_umowy::zapisz($_SESSION['a_umowa']);

		if(!app::get_result())
			view::redirect('umowy/formularz_umowy_krok1');
		else
		{
			$a_umowa =  mod_umowy::get_umowa($id_umowy);
			
			$pdf = mod_umowy::generuj_pdf($a_umowa['tresc'], $a_umowa['numer_umowy']);

			//if(mod_legitymacje::sprawdz_dostepnosc_karty(1,session::get('id_placowki')))
			//	mod_legitymacje::przypisz_karte_do_placowki(1,session::get('id_placowki'),'aktywna');
			
			unset($_SESSION['a_umowa']);
			
			//$hash = session::get('hash') ? '/hash/'.session::get('hash') : '';

			if($a_umowa['id_umowy_typy']==1)
			{
				if(mod_legitymacje::sprawdz_dostepnosc_karty(1, session::get('id_placowki')))
				{
					mod_legitymacje::przypisz_karte_do_placowki(1,session::get('id_placowki'),'aktywna');
					
					$email = db::get_one("SELECT email FROM users WHERE id_users=".session::get_id());
					
					mod_users::wyslij_maila($email,21,'','',array('id_placowki'=>session::get('id_placowki'),'id_karty'=>1));
				}
				
				mod_logi::dodaj('dodano legitymację', $_GET['id_karty']);
			}
			elseif($a_umowa['id_umowy_typy']==2 && !empty(session::get('a_karty')))
			{
				$email = db::get_one("SELECT email FROM users WHERE id_users=".session::get_id());
					
				foreach(session::get('a_karty') as $id_karty=>$asd)
				{
					mod_legitymacje::przypisz_karte_do_placowki($id_karty,session::get('id_placowki'),'aktywna');

					mod_users::wyslij_maila($email,21,'','',array('id_placowki'=>session::get('id_placowki'),'id_karty'=>$id_karty));
			
					mod_logi::dodaj('dodano legitymację', $id_karty);
				}
			}

			if(!empty(session::get('hash')))
			{
				con_umowy::wyslij_umowe($id_umowy, session::get('hash'));
				app::ok('Umowa została utworzona i wysłana na adres email');
				session::delete('hash');
				db::update('placowki', 'id_placowki='.session::get('id_placowki'), array('link_generatora_umowa_hash'=>'', 'link_generatora_umowa_data_waznosci'=>''));
				view::message();
			}
			else
			{
				app::ok('Umowa została utworzona<br><a href="umowy/wyslij_umowe/id_umowy/'.$id_umowy.'" class="button">Wyślij umowę na mój adres e-mail</a>');
				view::redirect('umowy/lista_umow');
			}
		}
	}

	public static function wyslij_umowe($id_umowy=false, $hash=false)
	{
		$id_umowy = !empty($id_umowy) ? $id_umowy : $_GET['id_umowy'];
		if(!session::is_logged() && !$hash)
		{
			app::err('Brak dostępu');
			view::message();	
		}

		if(empty($id_umowy) || !hlp_validator::id($id_umowy))
		{
			app::err('Nieprawidłowa umowa');
			view::message();
		}

		if(empty($hash) && !mod_umowy::sprawdz_dostep($id_umowy))
		{
			app::err('Brak dostępu do umowy');
			view::message();
		}

		$a_umowa = mod_umowy::get_umowa($id_umowy);
		$email = session::is_logged() ? session::get_user('email') : db::get_one("SELECT email FROM users JOIN placowki USING(id_users) WHERE id_placowki=".session::get('id_placowki'));
		
		mod_users::wyslij_maila($email, 28, '', '', array('numer_umowy'=>$a_umowa['numer_umowy']));

		app::ok('Umowa została wysłana na maila');
		
		if(empty($hash))
			view::message();
	}

	public static function formularz_edycji__lg()
	{
		if(!session::get('czy_zdalny') && !session::who('admin'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(empty($_GET['id_umowy']) || !hlp_validator::id($_GET['id_umowy']))
		{
			app::err('Nieprwidłowa umowa');
			view::message();
		}

		if(isset($_GET['czy_admin']))
			view::add('lista_umow_admin', 1);
		
		head::add_js_file(mod_panel::$js.'libs/ckeditor/ckeditor.js',false,'head');
		view::add('a_umowa', mod_umowy::get_umowa($_GET['id_umowy']));
		view::display();
	}

	public static function edytuj_umowe__lg()
	{
		if(!session::get('czy_zdalny') && !session::who('admin'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(empty($_POST['a_umowa']['id_umowy']) || !hlp_validator::id($_POST['a_umowa']['id_umowy']))
		{
			app::err('Nieprwidłowa umowa');
			view::message();
		}

		db::update('umowy2', 'id_umowy2='.$_POST['a_umowa']['id_umowy'], array('tresc'=>$_POST['a_umowa']['tresc'],
																			   'numer_umowy'=>$_POST['a_umowa']['numer_umowy'],
																			   'okres_obowiazywania'=>$_POST['a_umowa']['okres_obowiazywania'],
																			   'uwagi'=>$_POST['a_umowa']['uwagi'],
																			   'email'=>$_POST['a_umowa']['email'],
																			   'email_naruszenia'=>$_POST['a_umowa']['email_naruszenia'],
																			   ));
		
		$a_umowa =  mod_umowy::get_umowa($_POST['a_umowa']['id_umowy']);
		$pdf = mod_umowy::generuj_pdf($a_umowa['tresc'],$a_umowa['numer_umowy']);
		
		app::ok("Umowa zapisana");
		
		if(isset($_POST['lista_umow_admin']))
			view::redirect('umowy/lista_umow_admin');
		else
			view::redirect('umowy/lista_umow');
	}

	public static function potwierdz_umowe__lg()
	{
		if(empty($_POST['id_umowy']) || !hlp_validator::id($_POST['id_umowy']))
		{
			app::err('Nieprawidłowa umowa');
			view::message();
		}
		
		$a_umowa = mod_umowy::get_umowa($_POST['id_umowy']);

		if(is_uploaded_file($_FILES['potwierdzenie']['tmp_name']))
		{
			$dir = "images/placowki/".$a_umowa['id_placowki'].'/potwierdzenia';
		
			if(!is_dir($dir))
				mkdir($dir,0777);
			
			hlp_image::save($_FILES['potwierdzenie'],$dir.'/'.str_replace('/','-',$a_umowa['numer_umowy']));
			
			$data = date('Y-m-d H:i:s');
			$a_umowa['data_potwierdzenia'] = $data;
			db::update('umowy2',"id_umowy2={$_POST['id_umowy']}",array('status'=>'podpisana','data_potwierdzenia'=>$data));
			mod_logi::dodaj('dodano potwierdzenie umowy', $_POST['id_sites']);
		}

		
		db::update('umowy2',"id_umowy2={$_POST['id_umowy']}",array('okres_obowiazywania'=>$_POST['a_umowa']['czas_umowy']=='nieokreslony' ? '0000-00-00' : $_POST['a_umowa']['okres_obowiazywania'],
																   'wersja'=>$_POST['a_umowa']['wersja'],
																   'uwagi'=>$_POST['a_umowa']['uwagi']));

		if(isset($_POST['wyslij_potwierdzenie']))
		{
			$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_umowa['id_users']);
			mod_users::wyslij_maila($email,41,'','',array('a_umowa'=>$a_umowa));
		}
		
		if(session::who('admin') || session::who('mod'))
			view::redirect('umowy/lista_umow_admin');
		else
			view::redirect('umowy/lista_umow');
		
	}

	public static function formularz_skanu__lg()
	{
		if(empty($_GET['id_umowy']) || !hlp_validator::id($_GET['id_umowy']))
		{
			app::err('Nieprawidłowa umowa');
			view::message();
		}

		view::add('a_wersje_umowy', db::get_many("SELECT * FROM wersje_umowy ORDER BY wersja DESC"));
		view::add('a_umowa', mod_umowy::get_umowa($_GET['id_umowy']));
		view::display();
	}
	
	public static function formularz_umowy_zew()
	{
		if(!session::who('admin') && !session::who('mod') && !session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień');
			view::message();
		}
		
		view::add('a_typy', mod_umowy::get_umowy_typy());
		view::display();
	}
	
	public static function zapisz_umowe_zew()
	{
		if(!session::who('admin') && !session::who('mod') && !session::get('czy_zdalny'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(empty($_POST['a_umowa']))
		{
			app::err('Brak danych');
			view::redirect('umowy/lista-umow');
		}

		mod_umowy::zapisz_umowe_zew($_POST['a_umowa']);
		view::redirect('umowy/lista_umow');
	}

	public static function lista_umow_admin__admin_mod()
	{
		$typ_umowy = !empty($_GET['typ_umowy']) && hlp_validator::id($_GET['typ_umowy']) ? $_GET['typ_umowy'] : '';
		$fraza = !empty($_GET['fraza']) ? $_GET['fraza'] : '';
		$filtr = !empty($_GET['filtr']) ? $_GET['filtr'] : '';

		view::add('a_umowy',mod_umowy::get_umowy(false,$typ_umowy,$fraza,$filtr));
			
		view::add('lista_umow_admin', 1);
		view::add('a_umowy_typy', db::get_all('umowy_typy'));
		view::add('typ_umowy', $typ_umowy);
		view::add('fraza', $fraza);
		view::add('filtr', $filtr);
		view::display('umowy/lista_umow.tpl');
	}
	
	public static function zmien_status_umowy__admin_mod()
	{
		$a_dane = array();
		parse_str($_POST['umowy'], $a_dane);
		
		if(empty($_POST['status']) || !in_array($_POST['status'], array('unieważniona', 'wygasła')))
			view::json(false, 'Nieprawidłowy status');

		foreach($a_dane['a_umowa'] as $id_umowy=>$asd)
		{
			if($_POST['status']=='unieważniona')
				$sql_status = " AND status='oczekująca'";
			else
				$sql_status = " AND status='podpisana'";
				
			db::update('umowy2', "id_umowy2=$id_umowy $sql_status", array('status'=>$_POST['status']));
		}
		
		app::ok("Status zmieniony");
		view::json(true);
	}
	
}

?>