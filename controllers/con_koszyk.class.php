<?php

class con_koszyk extends controllers_parent{
	
	private static $p24_crc = '403bc7bbbae90016';
	private static $p24_crc_sandbox = 'ceec2e76dbf3c15c';
	private static $p24_env = 'regular';
	private static $p24_id_sprzedawcy = 78028;
	
	public static function dodaj_do_koszyka__lg()
	{
		if(empty($_POST['a_dane']))
			view::json(false,'Brak elementów');
		
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);

		if(empty($a_dane['id_produkty']) && mod_koszyk::dodaj($a_dane['a_legitymacje']) || !empty($a_dane['id_produkty']) && mod_koszyk::dodaj_produkt($a_dane['id_produkty'],$a_dane['ilosc']))
			view::json(true,view::get_message(),array('liczba_kart'=>mod_koszyk::get_liczba_kart()+mod_koszyk::get_liczba_produktow()));
		else
			view::json(false,view::get_message());
	}
	
	public static function usun_z_koszyka__lg()
	{
		if(empty($_POST['a_dane']))
			view::json(false,'Brak elementów');
		
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);

		if(!empty($a_dane['a_legitymacje']))
			mod_koszyk::usun($a_dane['a_legitymacje'],'legitymacja');
		
		if(!empty($a_dane['a_produkty']))
			mod_koszyk::usun($a_dane['a_produkty'],'produkt');
		
		app::ok('Usunięto z koszyka');
		
		view::json(true,'Usunięto z koszyka');
	}
	
	public static function koszyk__lg()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Wybierz najpierw placówkę');
			view::message();
		}
		
		if(session::get('czy_zdalny'))
		{
			view::add('a_przesylki',mod_przesylki::get_przesylki(session::get('id_placowki'),0));
			view::add('czy_koszyk',1);
			
			if(session::set('id_przesylki'))
				session::delete('id_przesylki');
		}
		
		$a_produkty = mod_koszyk::zwroc_produkty(session::get_id());
		
		if($a_produkty)
			$cena_laczna_produktow = mod_produkty::ustal_cene_produktow($a_produkty);

		view::add('id_karty',mod_koszyk::get_id_karty_z_koszyka(session::get_id()));
		view::add('tabela',mod_koszyk::get_legitymacje_tabela(session::get_id()));
		view::add('a_produkty',$a_produkty);
		view::display();
	}
	
	public static function zamow__lg()
	{
		if(!mod_koszyk::sprawdz_koszyk())
		{
			app::err('Brak produktów w koszyku');
			view::message();
		}
		
		if(session::get('czy_zdalny') && !empty($_POST['id_przesylki']) && hlp_validator::id($_POST['id_przesylki']))
			session::set('id_przesylki',$_POST['id_przesylki']);
		
		view::add('a_sposoby_wysylki',mod_koszyk::get_sposoby_wysylki(true));
		view::add('a_sposoby_platnosci',mod_koszyk::get_sposoby_platnosci());
		view::add('czy_zamowienie_zlozone',isset($_GET['nowe-zamowienie']) ? false : mod_zamowienia::sprawdz_czy_zamowienie_zlozone());
		view::add('a_placowka',mod_placowki::get_placowka(session::get('id_placowki')));
		view::add('a_dokumenty',mod_placowki::get_dokumenty_sprzedazy(session::get('id_placowki')));
		view::add('a_user',mod_users::get_user(session::get_id()));
		view::display();
	}
	
	public static function podsumowanie_zamowienia__lg()
	{
		if(!mod_koszyk::sprawdz_koszyk())
		{
			app::err('Brak produktów w koszyku');
			view::message();
		}
		
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}
		
		if(empty($_POST['a_zamowienie']))
		{
			app::err('Brak danych');
			view::redirect('koszyk/zamow');
		}

		if(!mod_zamowienia::sprawdz_zamowienie($_POST['a_zamowienie']))
			view::redirect('koszyk/zamow');

		$_SESSION['a_zamowienie']=$_POST['a_zamowienie'];

		$liczba_kart = mod_koszyk::get_liczba_kart(session::get_id());
		$wartosc_zamowienia = 0;
		
		if($liczba_kart)
		{
			$id_karty = mod_koszyk::get_id_karty_z_koszyka(session::get_id());
			
			$id_cenniki_wysylka = mod_karty::get_cennik_karty_wysylka($id_karty,$_POST['a_zamowienie']['id_sposoby_wysylki']);
			$cena_przesylki = mod_karty::get_cena_kart($liczba_kart,$id_cenniki_wysylka);
	
			$id_cenniki_karty = mod_karty::get_cennik_karty($id_karty);
			
			if(!$id_cenniki_karty)
				$id_cenniki_karty = mod_karty::get_domyslny_cennik_karty($id_karty);
			
			$cena_karty = mod_karty::get_cena_kart($liczba_kart,$id_cenniki_karty);
 			view::add('cena_karty',$cena_karty);
			view::add('a_karta',mod_karty::get_karta($id_karty));
			
			$wartosc_zamowienia += $cena_karty*$liczba_kart;
		}

		$a_produkty = mod_koszyk::zwroc_produkty(session::get_id());

		if($a_produkty)
		{
			$cena_laczna_produktow = mod_produkty::ustal_cene_produktow($a_produkty);

			view::add('a_produkty',$a_produkty);
			
			$wartosc_zamowienia += $cena_laczna_produktow;
			
			//ignorujemy cenę wysyłki produktu jeśli są w zamówieniu karty - one są ważniejsze
			//jeśli nie ma kart dopiero wtedy cena wysyłki produktu jest istotna
			if(empty($id_cenniki_wysylka))
			{
				$cena_przesylki = mod_produkty::get_najwyzsza_cena_przesylki($a_produkty,$_POST['a_zamowienie']['id_sposoby_wysylki']);
			}
		}
		
		$wartosc_zamowienia += $cena_przesylki;

		view::add('a_user',mod_users::get_user(session::get_id()));
		view::add('wartosc_zamowienia',$wartosc_zamowienia);
		view::add('a_sposob_wysylki',mod_koszyk::get_sposob_wysylki($_POST['a_zamowienie']['id_sposoby_wysylki']));
		view::add('cena_za_wysylke',$cena_przesylki);
		view::add('liczba_kart',$liczba_kart);		
		view::add('a_zamowienie',$_POST['a_zamowienie']);
		if($_POST['a_zamowienie']['id_dokumenty_sprzedazy']!=0)
			view::add('a_dokument',mod_placowki::get_dokument_sprzedazy($_POST['a_zamowienie']['id_dokumenty_sprzedazy']));
		view::display();
	}
	
	public static function zloz_zamowienie__lg()
	{
		if(!mod_koszyk::sprawdz_koszyk())
		{
			app::err('Brak produktów w koszyku');
			view::message();
		}
		
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}
		
		if(empty($_SESSION['a_zamowienie']))
		{
			app::err('Brak danych');
			view::redirect('koszyk/zamow');
		}

		if(!mod_zamowienia::sprawdz_zamowienie($_SESSION['a_zamowienie']))
			view::redirect('koszyk/zamow');

		if(session::get('czy_zdalny') && session::get('id_przesylki'))
		{
			mod_przesylki::przypisz_przesylke_do_zamowienia(session::get('id_przesylki'),$id_zamowienia);
			session::delete('id_przesylki');
		}

		$id_karty = mod_koszyk::get_id_karty_z_koszyka(session::get_id());
		$id_zamowienia = mod_zamowienia::zloz_zamowienie($_SESSION['a_zamowienie']);

		$cena_za_karty = 0;
		$cena_za_przesylke = 0;
		$id_cenniki_kart = 0;
		$id_cenniki_przesylki = 0;
		$liczba_kart = 0;
		$wartosc_zamowienia = 0;
		
		if($id_karty)
		{
			$id_cenniki_kart = mod_zamowienia::get_cennik_kart_zamowienia($id_zamowienia);
			$id_cenniki_przesylki = mod_zamowienia::get_cennik_przesylki_zamowienia($id_zamowienia,$_SESSION['a_zamowienie']['id_sposoby_wysylki']);
			$liczba_kart = mod_zamowienia::get_liczba_kart_z_zamowienia($id_zamowienia);
			$cena_za_karty = mod_karty::get_cena_kart($liczba_kart,$id_cenniki_kart);
			$cena_za_przesylke = mod_karty::get_cena_kart($liczba_kart,$id_cenniki_przesylki);
			$wartosc_zamowienia += $cena_za_karty*$liczba_kart;
		}
		
		$a_produkty = mod_zamowienia::zwroc_produkty($id_zamowienia);
		
		$cena_produktow = mod_produkty::ustal_cene_produktow($a_produkty);
		$wartosc_zamowienia += $cena_produktow;
		
		if(empty($id_cenniki_przesylki))
			$cena_za_przesylke = mod_produkty::get_najwyzsza_cena_przesylki($a_produkty,$_SESSION['a_zamowienie']['id_sposoby_wysylki']);
		
		$wartosc_zamowienia += $cena_za_przesylke;

		mod_zamowienia::aktualizuj_dane($id_zamowienia,$cena_za_karty,$cena_za_przesylke,$id_cenniki_kart,$id_cenniki_przesylki);

		view::add('wartosc_zamowienia',$wartosc_zamowienia);
		view::add('czy_email',1);
		view::add('cena_karty',mod_karty::get_cena_kart($liczba_kart,$id_cenniki_kart));
		view::add('a_sposob_wysylki',mod_koszyk::get_sposob_wysylki($_SESSION['a_zamowienie']['id_sposoby_wysylki']));
		view::add('cena_za_wysylke',$cena_za_przesylke);
		view::add('liczba_kart',$liczba_kart);
		view::add('a_karta',mod_karty::get_karta($id_karty));
		view::add('a_produkty',$a_produkty);
		view::add('cena_produktow',$cena_produktow);
		view::add('a_zamowienie',mod_zamowienia::get_zamowienie($id_zamowienia));
		view::add('a_user',mod_users::get_user(session::get_id()));
		
		if($_SESSION['a_zamowienie']['id_dokumenty_sprzedazy']!=0)
			view::add('a_dokument',mod_placowki::get_dokument_sprzedazy($_SESSION['a_zamowienie']['id_dokumenty_sprzedazy']));
		
		
		$html = view::display('koszyk/podsumowanie_zamowienia.tpl',true,true);
		
		mod_users::wyslij_maila(session::get_user('email'),2,false,false,array('szczegoly'=>$html));
																			   
		mod_logi::dodaj('złożono zamówienie',$id_zamowienia);
													   
		if($_SESSION['a_zamowienie']['id_sposoby_platnosci']==2)
		{
			$p24_id_sprzedawcy = self::$p24_id_sprzedawcy;
			$p24_crc = self::$p24_env=='regular' ? self::$p24_crc : self::$p24_crc_sandbox;
			
			$wartosc_zamowienia = $wartosc_zamowienia*100;
			$numer_zamowienia = db::get_one("SELECT numer_zamowienia FROM zamowienia WHERE id_zamowienia=$id_zamowienia");
			view::add('cena',$wartosc_zamowienia);
			view::add('p24_crc',md5("$numer_zamowienia|$p24_id_sprzedawcy|$wartosc_zamowienia|PLN|$p24_crc"));
			view::add('numer_zamowienia',$numer_zamowienia);
			view::add('email',session::get_user('email'));
			view::add('p24_id_sprzedawcy',$p24_id_sprzedawcy);
			view::add('p24_env',self::$p24_env);
			
			unset($_SESSION['a_zamowienie']);
			view::display('koszyk/paysite.tpl');
		}
		else
		{
			unset($_SESSION['a_zamowienie']);
			app::ok('Dziękujemy za złożenie zamówienia. Zamówienie otrzymało numer '. db::get_one("SELECT numer_zamowienia FROM zamowienia WHERE id_zamowienia=$id_zamowienia"));
			view::message();
		}
	}

	public static function dodaj_karty_do_zamowienia()
	{
		if(!mod_koszyk::sprawdz_koszyk())
		{
			app::err('Brak produktów w koszyku');
			view::message();
		}
		
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}

		mod_zamowienia::dodaj_karty_do_zamowienia();
		view::message();
	}
	
	public static function check_payment()
	{
		app::ok('Dziękujemy, jeśli tranzakcja zakończyła się pomyślnie, wkrótce otrzymają Państwo na maila potwierdzenie');
		view::message();
	}
	
	public static function response_to_p24()
	{
		include('framework/libs/class_przelewy24.php');
		
		$numer_zamowienia = $_POST['p24_session_id'];
		$a_zamowienie = db::get_row("SELECT * FROM zamowienia WHERE numer_zamowienia='$numer_zamowienia'");
		$liczba_kart = mod_zamowienia::get_liczba_kart_z_zamowienia($a_zamowienie['id_zamowienia']);
		
		$cena_produktow = mod_zamowienia::get_cena_zamowionych_produktow($a_zamowienie['id_zamowienia']);
		
		$cena_zamowienia = bcadd($a_zamowienie['cena_legitymacji']*$liczba_kart,$a_zamowienie['cena_przesylki']);
		$cena_zamowienia  = bcadd($cena_zamowienia, $cena_produktow)*100;
		
		$p24_crc = self::$p24_env=='regular' ? self::$p24_crc : self::$p24_crc_sandbox;
		
        $P24 = new Przelewy24($_POST["p24_merchant_id"],$_POST["p24_pos_id"],$p24_crc,self::$p24_env=='regular' ? false : true);
		$RET = $P24->testConnection();
		
		file_put_contents('asd.txt', json_encode($RET));
		
        foreach($_POST as $k=>$v) $P24->addValue($k,$v);  
		
        $P24->addValue('p24_currency','PLN');
        $P24->addValue('p24_amount',$cena_zamowienia);
        $res = $P24->trnVerify();
		file_put_contents('aaa.txt', json_encode($res));
		
        if(isset($res["error"]) and $res["error"] === '0')
        {
        	db::update('zamowienia',"id_zamowienia={$a_zamowienie['id_zamowienia']}",array('data_oplacenia'=>'NOW()',
        												 			'status'=>'w realizacji'));
																	
			mod_rozliczenia::dodaj_wplate('NOW()',$cena_zamowienia/100,'przelewy24',$a_zamowienie['id_zamowienia']);
																	
			mod_logi::dodaj('opłacono zamówienie',$a_zamowienie['id_zamowienia']);
																	
			$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_zamowienie['id_users']);
							
			mod_users::wyslij_maila($email,3,false,false,array('cena_legitymacji'=>$a_zamowienie['cena_legitymacji'],
															   'cena_przesylki'=>$a_zamowienie['cena_przesylki']));					
        }
	}
	
}

?>