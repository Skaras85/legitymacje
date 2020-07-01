<?php

class con_migracja extends controllers_parent{
	
	public static function admin_migracja__admin_mod()
	{
		view::display();
	}
	
	public static function formularz_migracji()
	{
		if(!session::who('admin') && (empty($_POST['a_user']) || !hlp_validator::id($_POST['a_user']['email'])) || session::who('admin') && (empty($_POST['id_placowki']) || !hlp_validator::id($_POST['id_placowki'])))
		{	
			app::err('Migracja konta nie może się powieść');
			view::redirect('users/formularz-logowania');
		}

		$a_placowka = mod_migracja::get_dane_placowki(!empty($_POST['a_user']) ? $_POST['a_user'] : false, !empty($_POST['id_placowki']) ? $_POST['id_placowki'] : false);

		if($a_placowka)
		{
			if(!session::who('admin') && $a_placowka['rejestracja_wlasna']=='tak')
			{	
				app::err('Migracja konta nie może się powieść');
				view::redirect('users/formularz-logowania');
			}
			
			if(!mod_migracja::sprawdz_czy_migracja_juz_byla($a_placowka['id_placowki']))
			{
				view::add('a_pracodawcy',mod_migracja::get_pracodawcy($a_placowka['id_placowki']));
				view::add('a_nauczyciele',mod_migracja::get_nauczyciele($a_placowka['id_placowki']));
				view::add('a_placowka',$a_placowka);
				
				view::add('a_typy_szkol',db::get_all('typy_szkol'));
				view::add('a_migracja',db::get_by_id('sites',8));
				view::display('migracja/formularz_migracji.tpl');
			}
			else
			{
				app::err('Ta placówka przeszła już migrację, spróbuj się zalogować wpisując adres email i hasło');
				view::redirect('users/formularz-logowania');
			}
		}
		else
		{
			app::err('Brak placówki w bazie o takim ID');
			view::redirect('users/formularz-logowania');
		}
	}
	
	public static function zapisz_dane()
	{
		if(empty($_POST['a_user']) || empty($_POST['a_placowka']) || empty($_POST['a_pracodawca']))
		{
			app::err('Nie przeprowadzono migracji, brak danych');
			view::redirect('');
		}

		$id_users = mod_users::zarejestruj($_POST['a_user'],false,session::who('admin'));
		
		if(!app::get_result())
			view::redirect('');

		mod_logi::dodaj('migracja', $id_users);

		if($id_users)
		{
			$id_placowki = mod_placowki::zapisz_dane($_POST['a_placowka'],$id_users,$_POST['a_user']['id_users_old'],session::who('admin'));
			mod_logi::dodaj('migracja dodano placówkę', $id_placowki);

			if($id_placowki)
			{
				mod_placowki::zapisz_dane_dokumentu_sprzedazy($_POST['a_placowka'],$id_placowki);
				foreach($_POST['a_pracodawca'] as $id_pracodawcy=>$a_pracodawca)
				{
					mod_migracja::zapisz_pracodawce($a_pracodawca,$id_users,$id_placowki,$id_pracodawcy);
					mod_logi::dodaj('migracja dodano pracodawcę', $id_pracodawcy);
				}
//db::deb();
				if(!empty($_POST['a_legitymacje']))
					mod_migracja::zapisz_legitymacje($_POST['a_legitymacje'],$id_placowki);
	
				$a_zamowienia_old = db::get_many("SELECT * FROM old_zamowienia WHERE id_placowki=".$_POST['a_user']['id_users_old']);
				
				if($a_zamowienia_old)
				{
					foreach($a_zamowienia_old as $a_zamowienie)
					{//db::deb();
						$a_faktura_old = db::get_row("SELECT * FROM old_faktury WHERE id_zamowienia=".$a_zamowienie['id_zamowienia']);
						
						if(in_array($a_zamowienie['status'],array('anulowane','anulowano','anulowany')))
							$status = 'anulowane';
						elseif($a_zamowienie['status']=='zrealizowane')
							$status = 'zrealizowane';
						elseif($a_zamowienie['status']=='w realizacji')
							$status = 'w realizacji';
						else
							$status = 'złożone';
						
						db::insert('zamowienia',array('id_placowki'=>$id_placowki,
													  'id_users'=>$id_users,
													  'id_karty'=>1,
													  'numer_zamowienia'=>$a_faktura_old['numer_faktury'],
													  'status'=>$status,
													  'data_zlozenia'=>$a_zamowienie['data_zlozenia'],
													  'cena_legitymacji'=>$a_zamowienie['cena_legitymacji'],
													  'cena_przesylki'=>$a_zamowienie['koszt_dostawy'],
													  'data_oplacenia'=>$a_faktura_old['data_zaplacenia'],
													  'placowka_nazwa'=>$a_faktura_old['nazwa_placowki'],
													  'placowka_regon'=>$_POST['a_placowka']['regon'],
													  'placowka_adres'=>$a_faktura_old['ulica'],
													  'placowka_kod_pocztowy'=>$a_faktura_old['kod_pocztowy'],
													  'placowka_poczta'=>$a_faktura_old['miasto'],
													  'placowka_dyrektor'=>$_POST['a_placowka']['dyrektor'],
													  'czy_paragon'=>$a_zamowienie['typ_dokumentu']=='paragon' ? 1 : 0,
													  'czy_archiwalne'=>1
													  ));
					}
				}
			}
		}

		if(!session::who('admin'))
		{
			mod_users::wyslij_maila($_POST['a_user']['email'],43,false,false);
			mod_users::wyslij_maila('legitymacje@loca.pl',43,false,false);
			app::ok('Migracja przebiegła prawidłowo, możesz się zalogować przy pomocy E-mail.');
			view::redirect('');
		}
		else
		{
			if(isset($_POST['czy_wyslac_powiadomienie']))
				mod_users::wyslij_maila($_POST['a_user']['email'],44,false,false);
			app::ok('Migracja przebiegła prawidłowo.');
			view::redirect('users/lista_kont_wewnetrznych/email/'.$_POST['a_user']['email']);
		}
		
		
	}

	public static function formularz_importu_nauczycieli()
	{
		if(!session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień, aby oglądać tę stronę');
			view::message();
		}
		
		if(!empty($_POST['id_placowki']) && hlp_validator::id($_POST['id_placowki']))
		{
			view::add('a_nauczyciele',mod_migracja::get_nauczyciele($_POST['id_placowki']));
			view::add('id_placowki',$_POST['id_placowki']);
		}
		
		if(!empty($_POST['id_nauczyciela']) && hlp_validator::id($_POST['id_nauczyciela']))
		{
			view::add('a_nauczyciele',mod_migracja::get_nauczyciel($_POST['id_nauczyciela']));
			view::add('id_nauczyciela',$_POST['id_nauczyciela']);
		}

		view::add('a_pracodawcy',mod_placowki::get_pracodawcy(session::get_id()));
		view::display();
	}

	public static function importuj_nauczycieli()
	{
		if(!session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień, aby oglądać tę stronę');
			view::message();
		}
		
		if(!empty($_POST['a_legitymacje']))
			mod_migracja::zapisz_legitymacje($_POST['a_legitymacje'],session::get('id_placowki'),$_POST['id_pracodawcy']);
		app::ok('Nauczyciele zaimportowani');
		view::message();
	}
}

?>