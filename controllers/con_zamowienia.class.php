<?php

class con_zamowienia extends controllers_parent{
	/*
	public static function asd()
	{db::deb();
		$a_zamowienia = db::get_many("SELECT id_zamowienia FROM zamowienia WHERE extract(year from data_zlozenia)=2019 and id_karty=1");
		
		foreach($a_zamowienia as $a_zamowienie)
		{
			$a_legitymacje = db::get_many("SELECT id_zamowienia_legitymacje FROM zamowienia_legitymacje where id_zamowienia={$a_zamowienie['id_zamowienia']} and kol5=''");
		
			foreach($a_legitymacje as $a_legitymacja)
			{
				db::update('zamowienia_legitymacje',"id_zamowienia_legitymacje=".$a_legitymacja['id_zamowienia_legitymacje'], array('kol5'=>'2022-02-28'));
			}
		}
	}
	*/
	public static function lista_zamowien__lg()
	{
		if(session::who('admin') || session::who('mod'))
		{
			$status = empty($_GET['status']) ? 'złożone' : $_GET['status'];
			view::add('a_users',db::get_many("SELECT imie,nazwisko,id_users FROM users WHERE rodzaj='admin'"));
			$id_users = 0;
		}
		else
		{
			if(!session::get('id_placowki'))
			{
				app::ok('Musisz wybrać placówkę');
				view::message();
			}
			
			$status = false;
			$id_users = session::get_user('parent_id') ? session::get_user('parent_id') : session::get_id();

			$a_subkonta = mod_users::get_subkonta($id_users);
			
			if($a_subkonta)
			{
				foreach($a_subkonta as $a_subkonto)
				{
					$id_users .= ','.$a_subkonto['id_users'];
				}
			}
		}

		$id_karty = isset($_GET['id_karty']) ? $_GET['id_karty'] : 0;
		$id_pracownika_realizujacego = isset($_GET['id_users'])  && $status!='złożone' ? $_GET['id_users'] : 0;

		$a_zamowienia = mod_zamowienia::get_zamowienia($status,$id_karty,false,false,false,$id_users,$id_pracownika_realizujacego);
		
		mod_zamowienia::get_ilosc_zdjec($a_zamowienia);
		
		view::add('status',$status);
		view::add('a_zamowienia',$a_zamowienia);
		view::add('id_karty',$id_karty);
		view::add('a_karty',db::get_all('karty'));
		view::add('id_pracownika_realizujacego',$id_pracownika_realizujacego);
		view::display();
	}
	
	public static function lista_zamowien_druk__admin_mod()
	{
		$id_karty = isset($_POST['id_karty']) ? $_POST['id_karty'] : 1;

		if(!isset($_POST['submit']) || isset($_POST['submit']) && empty($_POST['a_legitymacje']))
		{
			if($id_karty)
			{
				$a_zamowienia = mod_zamowienia::get_zamowienia('w druku',$id_karty);
				$tabela = mod_legitymacje::zwroc_tabele_z_danymi_druk($a_zamowienia);
	
				view::add('tabela', $tabela);
			}
			
			view::add('id_karty', $id_karty);
			view::add('a_karty',db::get_all('karty'));
		}
		else
		{

			if(!empty($_POST['a_legitymacje']))
			{
				$ids = '';
				foreach($_POST['a_legitymacje'] as $id_legitymacje=>$asd)
				{
					$ids .= $id_legitymacje.',';
				}
				$ids = trim($ids, ',');
			}

			$a_legitymcje = mod_legitymacje::get_karty($ids, $_POST['id_karty'], true);

			$html = mod_legitymacje::get_zamowione_legitymacje_druk($a_legitymcje, $_POST['typ_druku']);
			view::add('html', $html);
		}
		
		view::display();
	}
	
	public static function get_zamowione_legitymacje_druk__admin_mod()
	{
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);

		if(!empty($a_dane['a_legitymacje']))
		{
			$ids = '';
			foreach($a_dane['a_legitymacje'] as $id_legitymacje=>$asd)
			{
				$ids .= $id_legitymacje.',';
			}
			$ids = trim($ids, ',');
		}

		$a_legitymcje = mod_legitymacje::get_karty($ids, $_POST['id_karty'], true);
		$a_pola_karty = mod_karty::get_pola_karty_raw($_POST['id_karty']);
		
		$html = mod_legitymacje::get_zamowione_legitymacje_druk($a_legitymcje, $a_pola_karty);
		
		view::json(true, '', array('html'=>$html));
	}
	
	public static function formularz_wyboru_pracownika_realizujacego__admin_mod()
	{
		view::add('a_pracownicy',mod_users::get_pracownicy());
		view::display();
	}
	
	public static function przypisz_pracownika_do_zamowien__admin_mod()
	{
		if(empty($_POST['id_pracownika']) || !hlp_validator::id($_POST['id_pracownika']))
			view::json(false,'Nieznany pracownik');
		
		$a_params = array();
		parse_str($_POST['zamowienia'], $a_params);
		
		foreach($a_params['a_zamowienia'] as $id_zamowienia=>$asd)
		{
			db::update('zamowienia',"id_zamowienia=$id_zamowienia",array('id_pracownika_realizujacego'=>$_POST['id_pracownika']));
			mod_zamowienia::zmien_status_zamowienia($id_zamowienia,'w realizacji');
		}
		
		echo app::ok('Pracownik przypisany do zamówień');
	}
	
	public static function dodaj_do_druku__admin_mod()
	{
		$a_params = array();
		parse_str($_POST['zamowienia'], $a_params);
		
		foreach($a_params['a_zamowienia'] as $id_zamowienia=>$asd)
		{
			db::update('zamowienia',"id_zamowienia=$id_zamowienia",array('data_dodania_do_druku'=>'NOW()'));
			mod_zamowienia::zmien_status_zamowienia($id_zamowienia,'w druku');
		}
		
		echo app::ok('Dodano do druku');
	}
	
	public static function zmien_status__admin_mod()
	{
		$a_params = array();
		parse_str($_POST['zamowienia'], $a_params);
		
		foreach($a_params['a_zamowienia'] as $id_zamowienia=>$asd)
		{
			mod_zamowienia::zmien_status_zamowienia($id_zamowienia,$_POST['status']);
		}
		
		echo app::ok('Dodano do wydrukowanych');
	}
	
	public static function anuluj_zamowienie__admin_mod()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']))
			app::err('Nieznane zamówienie');
		else
		{
			mod_zamowienia::zmien_status_zamowienia($_POST['id'],'anulowane');
			
			if($_POST['czy_wyslac_powiadomienie']=='true')
			{
				$a_zamowienie = mod_zamowienia::get_zamowienie($_POST['id']);
				mod_users::wyslij_maila(db::get_one("SELECT email FROM users WHERE id_users=".$a_zamowienie['id_users']),50,'','',array('numer_zamowienia'=>$a_zamowienie['numer_zamowienia']));
			
				app::ok('Zamówienie anulowane, powiadomienie wysłane');
			}
			else
				app::ok('Zamówienie anulowane');
			
			view::json(true);
		}
	}
	
	public static function pobierz_do_druku__admin_mod()
	{
		$a_params = array();
		parse_str($_POST['zamowienia'], $a_params);
		
		$zip = new ZipArchive();

		$filename = "./drukowanie.zip";
		@unlink($filename);
		$zip->open($filename, ZipArchive::CREATE);
		
		$csv = '';
		foreach($a_params['a_zamowienia'] as $id_zamowienia=>$asd)
		{
			$a_legitymacje = mod_zamowienia::get_zamowione_legitymacje($id_zamowienia);
			$a_zamowienie = mod_zamowienia::get_zamowienie($id_zamowienia);
			$a_placowka = mod_placowki::get_placowka($a_zamowienie['id_placowki']);

			if($a_legitymacje)
			{
				$a_pola = mod_karty::get_pola_karty($a_zamowienie['id_karty']);
				foreach($a_legitymacje as $a_legitymacja)
				{
					$csv .= self::WIN1250_2_UTF8("{$a_legitymacja['id_placowki']};{$a_legitymacja['id_legitymacje']};{$a_zamowienie['numer_zamowienia']};");
				
					$a_photos = mod_legitymacje::get_photos($a_legitymacja['id_legitymacje']);
				
					if(file_exists($a_photos['zdjecie']))
						$zip->addFile($a_photos['zdjecie']);
					
					if(file_exists($a_photos['podpis']))
						$zip->addFile($a_photos['podpis']);
				
					if($a_pola)
					{
						foreach($a_pola as $a_pole)
						{
							if($a_pole['typ']=='pracodawca')
							{
								$a_pracodawca = mod_placowki::get_pracodawca($a_legitymacja['kol'.$a_pole['kolumna']]);
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane1']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane2']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane3']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane4']).';';
								$csv .= ';;';
							}
							elseif($a_pole['typ']=='szkoła')
							{
								$a_pracodawca = mod_placowki::get_pracodawca($a_legitymacja['kol'.$a_pole['kolumna']]);
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane1']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane2']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane3']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane4']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane5']).';';
								$csv .= self::WIN1250_2_UTF8($a_pracodawca['dane6']).';';
							}
							elseif($a_pole['typ']=='okres zatrudnienia (prosty)' || $a_pole['typ']=='okres zatrudnienia (złożony)')
							{
								if(empty($a_legitymacja['kol'.$a_pole['kolumna']]))
									$csv .= mod_panel::get_parametr('data_waznosci_legitymacji').';';
								else
								{
									$a_data_waznosci = explode('-',$a_legitymacja['kol'.$a_pole['kolumna']]);
									$data_waznosci = $a_data_waznosci[2].$a_data_waznosci[1].$a_data_waznosci[0];
									$csv .= self::WIN1250_2_UTF8($data_waznosci).';';
								}
							}
							elseif(in_array($a_pole['typ'],array('zdjęcie i podpis','zdjęcie i podpis (złożony)')))
							{
								$csv .= basename($a_photos['zdjecie']).';';
								$csv .= basename($a_photos['podpis']).';';
							}	
							elseif(in_array($a_pole['typ'],array('zdjęcie (złożony)','zdjęcie')))
								$csv .= basename($a_photos['zdjecie']).';';
							elseif(in_array($a_pole['typ'],array('podpis (złożony)','podpis')))
								$csv .= basename($a_photos['podpis']).';';
							else
								$csv .= self::WIN1250_2_UTF8($a_legitymacja['kol'.$a_pole['kolumna']]).';';
						}
					}
					
					$csv .= $a_placowka['dyrektor'].';';
					
					$csv .= "\r\n";
					$typ_legitymacji = $a_zamowienie['id_karty']==1 ? 'nauczyciela' : 'szkolne';
					file_put_contents("legitymacje_{$typ_legitymacji}.csv", $csv);
				}
			}
		}

		if(file_exists('legitymacje_nauczyciela.csv'))
			$zip->addFile("legitymacje_nauczyciela.csv");
		
		if(file_exists('legitymacje_szkolne.csv'))
			$zip->addFile("legitymacje_szkolne.csv");
		
		$zip->close();

		app::ok('Pliki gotowe do pobrania <a href="get.php?typ=drukowanie.zip">pobierz</a>');
		view::json(true,'');
	
	}

	public static function win2utf() {
	   $tabela = array(
	    "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
	    "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
	    "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
	    "\x9f" => "\xc5\xba", "\xaf" => "\xc5\xbb", "\xbf" => "\xc5\xbc", "\xac" => "\xc5\xb9",
	    "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83", "\x8f" => "\xc5\xb9", "\xfe" => " ");
	   return $tabela;
	  }
	
	
	public static function WIN1250_2_UTF8($linia){
	   return strtr($linia, self::win2utf());
	  }
	
	public static function formularz_realizacji__admin_mod()
	{
		if(empty($_GET['id_zamowienia']) || !hlp_validator::id($_GET['id_zamowienia']))
		{
			app::err('Nieznane zamówienie');
			view::message();
		}
		
		view::add('a_sposoby_wysylki',mod_koszyk::get_sposoby_wysylki());
		view::add('a_sposoby_platnosci',mod_koszyk::get_sposoby_platnosci());
		$a_zamowienie = mod_zamowienia::get_zamowienie($_GET['id_zamowienia']);
		view::add('a_zamowienie',$a_zamowienie);
		view::add('a_dokumenty',mod_placowki::get_dokumenty_sprzedazy($a_zamowienie['id_placowki']));
		view::add('czy_korekta',isset($_GET['korekta']));
		
		view::display();
	}
	
	public static function realizuj_zamowienie__admin_mod()
	{
		if(empty($_POST['a_zamowienie']))
		{
			app::err('Brak zamówienia');
			view::message();
		}
		
		if(!mod_zamowienia::realizuj_zamowienie($_POST['a_zamowienie'],isset($_POST['czy_korekta'])))
			view::message();
		
		$a_zamowienie = mod_zamowienia::get_zamowienie($_POST['a_zamowienie']['id_zamowienia']);
		$url_faktury = mod_zamowienia::generuj_fakture($_POST['a_zamowienie']['id_zamowienia']);
		
		if(!empty($_POST['a_zamowienie']['czy_powiadomienie']))
		{
			$wartosc_zamowienia = 0;
			if($a_zamowienie['id_karty'])
			{
				$a_karta = mod_karty::get_karta($a_zamowienie['id_karty']);
				$liczba_kart = mod_zamowienia::get_liczba_kart_z_zamowienia($a_zamowienie['id_zamowienia']);
				
				view::add('a_karta',$a_karta);
				view::add('liczba_kart',$liczba_kart);
				view::add('cena_karty',$a_zamowienie['cena_legitymacji']);

				$id_cenniki_przesylki = mod_zamowienia::get_cennik_przesylki_zamowienia($a_zamowienie['id_zamowienia'],$a_zamowienie['id_sposoby_wysylki']);
				$cena_za_przesylke = mod_karty::get_cena_kart($liczba_kart,$id_cenniki_przesylki);
				$wartosc_zamowienia += $a_zamowienie['cena_legitymacji']*$liczba_kart;
			}
			
			$a_produkty = mod_zamowienia::zwroc_produkty($a_zamowienie['id_zamowienia']);
			view::add('a_produkty',$a_produkty);
			
			$cena_produktow = mod_produkty::ustal_cene_produktow($a_produkty);
			$wartosc_zamowienia += $cena_produktow;
			
			$a_sposob_wysylki = mod_karty::get_cennik(mod_zamowienia::get_cennik_przesylki_zamowienia($a_zamowienie['id_zamowienia'],$a_zamowienie['id_sposoby_wysylki']));

			view::add('a_sposob_wysylki',$a_sposob_wysylki);
			
			if(empty($id_cenniki_przesylki))
				$cena_za_przesylke = mod_produkty::get_najwyzsza_cena_przesylki($a_produkty,$a_zamowienie['id_sposoby_wysylki']);
			
			$wartosc_zamowienia += $cena_za_przesylke;
			
			view::add('cena_za_wysylke',$cena_za_przesylke);
			view::add('a_zamowienie',$a_zamowienie);
			view::add('wartosc_zamowienia',$wartosc_zamowienia);
			view::add('czy_email',1);
			$html = view::display('koszyk/podsumowanie_zamowienia.tpl',true,true);

			$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_zamowienie['id_users']);
			mod_users::wyslij_maila($email,6,false,false,array('szczegoly'=>$html));
		}
		
		app::ok("Zamówienie zrealizowano, <a href='$url_faktury' download class='pobierz_fakture'>pobierz fakturę</a>");
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $url_faktury . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($url_faktury));
		header('Accept-Ranges: bytes');
		@readfile($url_faktury);
		exit();
	}

	public static function podglad_zamowienia__lg()
	{
		if(empty($_GET['id_zamowienia']) || !hlp_validator::id($_GET['id_zamowienia']))
		{
			app::err("Nieznane zamówienie");
			view::message();
		}
		
		if(!session::who('admin') && mod_zamowienia::sprawdz_dostep($_GET['id_zamowienia']) && !session::get('czy_zdalny'))
		{
			app::err("Brak dostępu");
			view::message();
		}
		
		$a_zamowienie = mod_zamowienia::get_zamowienie($_GET['id_zamowienia']);
		$a_legitymacje = mod_zamowienia::get_zamowione_legitymacje($_GET['id_zamowienia']);
		$a_produkty = mod_zamowienia::zwroc_produkty($_GET['id_zamowienia']);
		
		$tabela = mod_legitymacje::zwroc_tabele_z_danymi(mod_karty::get_pola_karty($a_zamowienie['id_karty']),$a_legitymacje,false,true,$a_zamowienie['id_karty']);
		view::add('tabela',$tabela);
		view::add('a_zamowienie',$a_zamowienie);
		view::add('a_produkty',$a_produkty);
		view::add('czy_zamowienie',1);
		view::display();
	}

	public static function zwroc_historie_zamowien_legitymacji__lg()
	{
		if(empty($_POST['id_legitymacji']) || !hlp_validator::id($_POST['id_legitymacji']) || !mod_legitymacje::sprawdz_dostep_legitymacji($_POST['id_legitymacji']))
		{
			app::err("Nieznana legitymacja");
			view::message();
		}
		
		$a_zamowienia = mod_zamowienia::get_historia_zamowien_legitymacji($_POST['id_legitymacji']);
		
		view::json(true, '', array('a_zamowienia'=>$a_zamowienia));
	}
	
	public static function zapisz_kod_karty()
	{
		if(!session::get('czy_zdalny'))
			view::json(false, 'Brak uprawnień');
		
		if(empty($_POST['id_zamowienia']) || !hlp_validator::id($_POST['id_zamowienia']))
			view::json(false, 'Nieprawidłowe zamówienia');
		
		if(empty($_POST['id_legitymacji']) || !hlp_validator::id($_POST['id_legitymacji']))
			view::json(false, 'Nieprawidłowa legitymacja');
			
		if(!empty($_POST['kod']) && !hlp_validator::alfanum($_POST['kod']))
			view::json(false, 'Nieprawidłowy kod');

		db::update('zamowienia_legitymacje', "id_zamowienia={$_POST['id_zamowienia']} AND id_legitymacje={$_POST['id_legitymacji']}", array('kod_karty'=>$_POST['kod']));
		view::json(true, 'Zapisano kod karty');
	}
}

?>