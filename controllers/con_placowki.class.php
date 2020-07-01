<?php
class con_placowki extends controllers_parent{
	
	public static function formularz_placowki__lg()
	{
		if(isset($_GET['id']) && !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowa placówka');
			view::message();
		}

		if(isset($_GET['id']) && !session::who('admin') && !mod_placowki::sprawdz_dostep($_GET['id']))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(session::get_user('typ')=='agencja' && !mod_users::czy_potwierdzone_umowy())
		{
			app::err('Aby dodać placówkę musisz najpierw potwierdzić umowy i umowy współpracy');
			view::message();
		}
		
		if(isset($_GET['id']))
		{
			$a_placowka = mod_placowki::get_placowka($_GET['id']);
			
			if(!$a_placowka)
			{
				app::err('Brak placówki o podanym numerze');
				view::message();
			}
			
			$a_dokument = $a_placowka['id_dokumenty_sprzedazy'] ?  mod_placowki::get_dokument_sprzedazy($a_placowka['id_dokumenty_sprzedazy']) : false;
			
			view::add('a_dokumenty_sprzedazy',mod_placowki::get_dokumenty_sprzedazy($_GET['id']));
			view::add('a_dokument',$a_dokument);
			view::add('a_placowka',$a_placowka);
		}

		if(isset($_SESSION['a_placowka']))
			$_SESSION['form']['a_placowka'] = $_SESSION['a_placowka'];

		view::add('a_typy_szkol',db::get_all("typy_szkol"));
		view::display();
	}
	
	public static function sprawdz_regon()
	{
		if(empty($_GET['val']) || !hlp_validator::regon($_GET['val']))
			view::json(false,'Nieprawidłowy numer REGON');
		
		$sql_id_placowki = '';
		if(!empty($_GET['id_placowki']) && hlp_validator::id($_GET['id_placowki']))
			$sql_id_placowki = " AND id_placowki<>".$_GET['id_placowki'];
		
		$sql_typ_usera = session::get_user('typ')=='agencja' ? " AND id_users=".session::get_id() : '';
			
		if(db::get_one("SELECT 1 FROM placowki WHERE regon='{$_GET['val']}' $sql_id_placowki $sql_typ_usera"))
			view::json(false,'Istnieje już placówka z takim numerem REGON');
		else
			view::json(true);
		exit;
	}
	
	public static function zapisz_placowke_podglad__lg()
	{
		if(!empty($_POST['a_placowka']))
		{
			$a_placowka=$_POST['a_placowka'];
			$_SESSION['form']['a_placowka']=$a_placowka;
			$_SESSION['a_placowka'] = $a_placowka;
			
			if(session::get_user('typ')=='placowka')
			{
				$a_dokument=$_POST['a_dokument'];
				$_SESSION['form']['a_dokument']=$a_dokument;
				$_SESSION['a_dokument'] = $a_dokument;
			}

			if(!mod_placowki::check_placowka_data($a_placowka,isset($a_placowka['id_placowki'])) || session::get_user('typ')=='placowka' && !mod_placowki::check_dokument_sprzedazy_data($a_dokument,$a_placowka['dokument_sprzedazy']))
				view::redirect('placowki/formularz_placowki');
			else
			{
				if(session::get_user('typ')=='placowka' && hlp_validator::id($a_placowka['dokument_sprzedazy']))
				{
					$a_dokument = mod_placowki::get_dokument_sprzedazy($a_placowka['dokument_sprzedazy']);
					$_SESSION['form']['a_placowka']['dokument_sprzedazy'] = 'Faktura - ' . $a_dokument['nabywca_nazwa'];
				}
				
				view::add('a_user', mod_users::get_user(session::get_id()));
				view::add('a_typ_szkoly',db::get_by_id("typy_szkol",$a_placowka['id_typy_szkol']));
				view::add('a_placowka',$a_placowka);
				view::add('a_dokument',$a_dokument)	;
				view::display();
			}
		
		}
	}

	public static function zapisz_placowke__lg()
	{
		if(empty($_SESSION['a_placowka']) || $_SESSION['a_placowka']['dokument_sprzedazy']=='faktura' && empty($_SESSION['a_dokument']))
			view::redirect('placowki/formularz_placowki');
		
		if(session::get_user('typ')=='agencja' && !mod_users::czy_potwierdzone_umowy())
		{
			app::err('Aby dodać placówkę musisz najpierw potwierdzić umowy i umowy współpracy');
			view::message();
		}
		
		$a_placowka = $_SESSION['a_placowka'];
		$a_dokument = isset($_SESSION['a_dokument']) ? $_SESSION['a_dokument'] : false;

		$id_placowki = mod_placowki::zapisz_dane($a_placowka);

		if(!app::get_result())
		{
			if(isset($a_placowka['id_placowki']))
				view::redirect('placowki/formularz_placowki/id/'.$a_placowka['id_placowki']);
			else
				view::redirect('placowki/formularz_placowki');
		}
		else
		{
			if(isset($a_placowka['dokument_sprzedazy']))
			{
				if($a_placowka['dokument_sprzedazy']!='paragon')
				{
					$id_dokumenty_sprzedazy = mod_placowki::zapisz_dane_dokumentu_sprzedazy($a_dokument,$id_placowki);
					mod_placowki::aktualizuj_dokument_sprzedazy_placowki($id_placowki,$id_dokumenty_sprzedazy);
				}
				else
					mod_placowki::aktualizuj_dokument_sprzedazy_placowki($id_placowki,0);

				unset($_SESSION['form']['a_placowka']);
				unset($_SESSION['a_placowka']);
				unset($_SESSION['form']['a_dokument']);
				unset($_SESSION['a_dokument']);
			}
			
			if(session::who('admin') || session::get('czy_zdalny'))
				view::redirect('placowki/admin_lista_placowek');
			else
			{
				if(isset($a_placowka['id_placowki']))
				{
					mod_logi::dodaj('edytowano placówkę', $id_placowki);
					app::ok('Dane placówki zapisane');
					view::redirect('placowki/lista_placowek');
				}
				else
				{
					$a_placowka['id_placowki'] = $id_placowki;
					$a_placowka['uniqid_placowki'] = db::get_one("SELECT uniqid_placowki FROM placowki WHERE id_placowki=$id_placowki");
					
					if(isset($id_dokumenty_sprzedazy))
						$a_dokument = mod_placowki::get_dokument_sprzedazy($id_dokumenty_sprzedazy);
					
					mod_placowki::generuj_potwierdzenie_rejestracji_pdf($a_placowka,$a_dokument);
					/*
					$url = "images/placowki/{$a_placowka['id_placowki']}/{$a_placowka['uniqid_placowki']}.pdf";
					$a_strona = db::get_by_id('sites',9);
					$a_strona['text'] .= "<a href='".app::base_url()."$url' download class='button big green'>POBIERZ I WYDRUKUJ FORMULARZ REJESTRACJI</a>";
					$a_strona['text'] = str_replace('{id_placowki}', $id_placowki, $a_strona['text']);
					*/
					view::add('a_strona',$a_strona);
					
					if(session::get_user('parent_id')==0)
					{
						mod_users::wyslij_maila(session::get_user('email'), 10);

						//mailer::add_attachment($url,'potwierdzenie_rejestracji.pdf','base64','application/pdf');
						//mailer::send($a_mail['title'],$a_mail['text'],false,true,strip_tags($a_mail['text']));
					}
					
					//db::insert('placowki_karty',array('id_placowki'=>$id_placowki,'id_karty'=>1,'status'=>'aktywna'));
					mod_logi::dodaj('dodano placówkę', $id_placowki);

					session::set('id_placowki',$a_placowka['id_placowki']);
					view::redirect('placowki/lista_pracodawcow/czy_szkoly/1/czy_dodawanie_placowki/1');
					//view::display('strony/strona.tpl');
				}
			}
		}
	}

	public static function placowka()
	{
		$a_placowka = mod_placowki::sprawdz_poprawnosc_placowki($_GET['id']);
		
		if(!app::get_result())
			view::message();

		session::set('id_placowki',$_GET['id']);
		$a_karty_placowki = mod_legitymacje::get_karty_placowki(session::get('id_placowki'));

		view::add('a_umowa_legitymacje_nauczyciela', mod_umowy::get_umowa_placowki(1, session::get('id_placowki')));
		view::add('a_umowa_legitymacje_szkolne', mod_umowy::get_umowa_placowki(2, session::get('id_placowki')));
		view::add('a_karty',$a_karty_placowki);
		view::add('a_karty_pozostale',mod_legitymacje::get_karty_niedodane(session::get('id_placowki'),$a_karty_placowki));
		view::add('a_placowka',$a_placowka);
		view::add('czy_wlasciciel',mod_placowki::czy_wlasciciel_placowi($_GET['id']));
		view::display();
	}

	public static function lista_placowek__lg()
	{
		if(session::get_user('typ')=='agencja')
			view::add('czy_potwierdzone_umowy',mod_users::czy_potwierdzone_umowy());
		
		if(isset($_GET['komunikat']) && db::get_one("SELECT is_visible FROM sites WHERE id_sites=47"))
			view::add('komunikat_popup',1);
		
		view::add('a_placowki',mod_placowki::get_dostepne_placowki_usera(session::get_id()));
		view::display();
	}
	
	public static function admin_lista_placowek()
	{
		if(!session::who('admin') && !session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień, aby oglądać tę stronę');
			view::message();
		}
		
		$search = !empty($_GET['search']) ? $_GET['search'] : false;
		$czy_mailing = isset($_GET['czy_mailing']) ? $_GET['czy_mailing'] : '';

		if($search || !empty($_GET['czy_wszystkie']))
			view::add('a_placowki',mod_placowki::get_placowki($search,$czy_mailing,!empty($_GET['czy_wszystkie'])));
		
		view::add('czy_wszystkie', !empty($_GET['czy_wszystkie']));
		view::add('czy_mailing', $czy_mailing);
		view::add('search', $search);
		view::display();
	}
	
	public static function lista_pracodawcow__lg()
	{
		if(isset($_GET['czy_szkoly']))
			view::add('czy_szkoly',1);
		
		view::add('a_pracodawcy',mod_placowki::get_pracodawcy(session::get('id_placowki'),isset($_GET['czy_szkoly'])));
		
		$a_umowa = mod_umowy::get_umowa_placowki(isset($_GET['czy_szkoly']) ? 2 : 1,session::get('id_placowki'));
		view::add('czy_umowa_wazna', $a_umowa['status']=='aktywna' ? true : false);
		
		if(!isset($_GET['czy_dodawanie_placowki']))
			view::display();
		else
			view::display('placowki/lista_pracodawcow_dodawanie_placowki.tpl');
	}
	
	public static function formularz_pracodawcy__lg()
	{
		if(isset($_GET['id']) && !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy pracodawca');
			view::message();
		}

		if(isset($_GET['id']) && !session::who('admin') && !session::get('czy_zdalny') && !mod_placowki::sprawdz_dostep_pracodawcy($_GET['id']))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(isset($_GET['id']))
		{
			$a_pracodawca = mod_placowki::get_pracodawca($_GET['id']);
			
			if(!$a_pracodawca)
			{
				app::err('Brak pracodawcy o podanym numerze');
				view::message();
			}
			
			$a_umowa = mod_umowy::get_umowa_placowki($a_pracodawca['typ'],session::get('id_placowki'));
			
			if($a_umowa['status']=='aktywna')
			{
				app::err('Dla tego pracodawcy/szkoły umowa jest ważna i nie można go edytować');
				view::message();
			}
			
			view::add('a_pracodawca',$a_pracodawca);
		}
		
		if(isset($_GET['id_karty']))
			view::add('id_karty',$_GET['id_karty']);
		
		view::add('czy_dodawanie_placowki', !empty($_GET['czy_dodawanie_placowki']));
		view::add('czy_szkoly',!empty($_GET['czy_szkoly']));
		view::display();
	}
	

	public static function zapisz_pracodawce__lg()
	{
		$a_pracodawca=$_POST['a_pracodawca'];
		$_SESSION['form']['a_pracodawca']=$a_pracodawca;
/*
		if(isset($_POST['przejdz_dalej']))
		{
			view::redirect('placowki/formularz_pracodawcy'.((!isset($a_pracodawca['czy_szkoly']) ? '/czy_szkoly/1' : '').((!isset($a_pracodawca['czy_dodawanie_placowki']) ? '/czy_dodawanie_placowki/1' : ''))));
			exit;
		}*/

		$edycja_pracodawcy = isset($a_pracodawca['id_pracodawcy']);
		$czy_szkoly = isset($a_pracodawca['czy_szkoly']) ? "/czy_szkoly/1" : "";
		$czy_dodawanie_placowki = isset($a_pracodawca['czy_dodawanie_placowki']) ? "/czy_dodawanie_placowki/1" : "";
		
		$id_pracodawcy = mod_placowki::zapisz_pracodawce($a_pracodawca);
		
		if(!app::get_result())
		{
			if(isset($a_pracodawca['id_pracodawcy']))
				view::redirect('placowki/formularz_pracodawcy/id/'.$a_pracodawca['id_pracodawcy'].$czy_szkoly.$czy_dodawanie_placowki);
			else
				view::redirect('placowki/formularz_pracodawcy'.$czy_szkoly.$czy_dodawanie_placowki);
		}
		else
		{
			mod_logi::dodaj('dodano pracodawcę', $id_pracodawcy);
			
			if(!empty($_POST['id_karty']) && hlp_validator::id($_POST['id_karty']))
				view::redirect('legitymacje/lista-osob-legitymacji/id_karty/'.$_POST['id_karty'].$czy_szkoly);
			elseif($edycja_pracodawcy)
				view::redirect('placowki/lista-pracodawcow'.$czy_szkoly);
			//elseif(!empty($czy_dodawanie_placowki))
			//	view::redirect('placowki/formularz_pracodawcy'.$czy_szkoly.$czy_dodawanie_placowki);
			elseif($czy_dodawanie_placowki)
				view::redirect('placowki/lista_pracodawcow/czy_dodawanie_placowki/1'.$czy_szkoly);
			elseif($czy_dodawanie_placowki && !$czy_szkoly)
				view::redirect('umowy/lista-umow');
			else
				view::redirect('placowki/lista-pracodawcow'.$czy_szkoly);
		}
	}
	
	public static function usun_pracodawce__lg()
	{
		if(isset($_POST['id']) && !session::who('admin') && !session::get('czy_zdalny') && !mod_placowki::sprawdz_dostep_pracodawcy($_POST['id']))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		mod_logi::dodaj('usunięto pracodawcę', $_POST['id']);
		db::delete('pracodawcy',"id_pracodawcy=".$_POST['id']);
	}
	
	public static function usun_placowke__admin_mod()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']))
			exit;
		
		mod_logi::dodaj('usunięto placówkę', $_POST['id']);
		db::delete('placowki',"id_placowki=".$_POST['id']);
	}
	
	public static function aktywuj_placowke__admin_mod()
	{
		if(empty($_POST['id_placowki']) || !hlp_validator::id($_POST['id_placowki']))
		{
			app::err('Nieprawidłowa placówka');
			view::redirect('placowki/admin_lista_placowek');
		}
		
		if(!is_uploaded_file($_FILES['dokument']['tmp_name']))
		{
			app::err('Nie załączono dokumentu');
			view::redirect('placowki/admin_lista_placowek');
		}
		
		db::update('placowki','id_placowki='.$_POST['id_placowki'],array('status'=>'aktywna'));
		$a_placowka = mod_placowki::get_placowka($_POST['id_placowki']);
		$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_placowka['id_users']);

		mod_users::wyslij_maila($email,13);
		
		hlp_image::save($_FILES['dokument'],"images/placowki/{$_POST['id_placowki']}/dokument");

		if(app::get_result())
			app::ok('Placówka została aktywowana, a email wysłany do właściciela');
			
		view::redirect('placowki/admin_lista_placowek');
	}

	public static function get_regony__admin_mod()
	{//db::deb();
		view::json(true,'',array('a_regony'=>db::get_many("SELECT * FROM regon WHERE kod_pocztowy='{$_POST['kod_pocztowy']}'")));
	}
	/*
	public static function asd()
	{
		$a_regony = db::get_many('select id_regon, regon from regon');
		db::deb();
		foreach($a_regony as $a_regon)
		{
			if(strlen($a_regon['regon'])<9)
			{
				$a_regon['regon'] = '0'.$a_regon['regon'];
				
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				if(strlen($a_regon['regon'])<9)
					$a_regon['regon'] = '0'.$a_regon['regon'];
				
				db::update('regon','id_regon='.$a_regon['id_regon'],array('regon'=>'"'.$a_regon['regon'].'"'));
			}
		}
	}*/
	
	public static function zapisz_dane_dokumentu_sprzedazy__lg()
	{
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);

		$id_dokument_sprzedazy = mod_placowki::zapisz_dane_dokumentu_sprzedazy($a_dane['a_dokument'],session::get('id_placowki'));
		view::json(true,'Dodano dokument sprzedaży',array('id_dokument_sprzedazy'=>$id_dokument_sprzedazy));
	}
	
	public static function get_dokument_sprzedazy__lg()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']))
			exit;
		
		view::json(true,'',mod_placowki::get_dokument_sprzedazy($_POST['id']));
	}

}

	
?>