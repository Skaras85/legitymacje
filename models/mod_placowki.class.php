<?php

class mod_placowki extends db{
	
	public static function czy_wlasciciel_placowi($id_placowki)
	{
		return db::get_one("SELECT 1 FROM placowki WHERE id_placowki=$id_placowki AND id_users=".session::get_id());
	}
	
	public static function sprawdz_dostep($id_placowki)
	{
		if(session::who('admin'))
			return true;
		return db::get_one("SELECT 1 FROM users_placowki WHERE id_placowki=$id_placowki AND id_users=".session::get_id());
	}
	
	public static function check_placowka_data($ia_placowka,$edit=false)
	{
		$kom = 'Nie zapisano, ';

		if($edit && !session::who('admin') && !self::czy_wlasciciel_placowi($ia_placowka['id_placowki']))
		{
			app::err($kom.'nie masz uprawnień do edycji danych tej placówki');
			return false;
		}

		if(empty($ia_placowka['id_typy_szkol']) || !hlp_validator::id($ia_placowka['id_typy_szkol']))
		{
			app::err($kom.'nieprawidłowe typ szkoły '.$ia_placowka['id_typy_szkol']);
			return false;
		}
		
		if(empty($ia_placowka['nazwa']) || !hlp_validator::alfanum($ia_placowka['nazwa']))
		{
			app::err($kom.'nieprawidłowe pełna nazwa');
			return false;
		}
		
		if(session::get_user('typ')=='placowka' && empty($ia_placowka['regon']) || !hlp_validator::regon($ia_placowka['regon']))
		{
			app::err($kom.'nieprawidłowy regon');
			return false;
		}
		
		if(empty($ia_placowka['nazwa_skrocona']) || !hlp_validator::alfanum($ia_placowka['nazwa_skrocona']) || strlen($ia_placowka['nazwa_skrocona'])>10)
		{
			app::err($kom.'nieprawidłowe nazwa skrócona');
			return false;
		}
		
		if(empty($ia_placowka['adres']) || !hlp_validator::alfanum($ia_placowka['adres']))
		{
			app::err($kom.'nieprawidłowy adres');
			return false;
		}
		
		if(empty($ia_placowka['kod_pocztowy']) || !hlp_validator::kod_pocztowy($ia_placowka['kod_pocztowy']))
		{
			app::err($kom.'nieprawidłowy kod pocztowy');
			return false;
		}
		
		if(empty($ia_placowka['poczta']) || !hlp_validator::alfanum($ia_placowka['poczta']))
		{
			app::err($kom.'nieprawidłowa poczta');
			return false;
		}
		/*
		if(empty($ia_placowka['dyrektor']) || !hlp_validator::alfanum($ia_placowka['dyrektor']))
		{
			app::err($kom.'nieprawidłowy dyrektor');
			return false;
		}
		*/
		if(session::get_user('typ')=='placowka')
		{
			if(empty($ia_placowka['dokument_sprzedazy']) || !in_array($ia_placowka['dokument_sprzedazy'],array('faktura','paragon')) && !hlp_validator::id($ia_placowka['dokument_sprzedazy']))
			{
				app::err($kom.'nieprawidłowy dokument sprzedaży');
				return false;
			}
	
			if(hlp_validator::id($ia_placowka['dokument_sprzedazy']) && !self::sprawdz_dostep_dokumentu_sprzedazy($ia_placowka['dokument_sprzedazy']))
			{
				app::err($kom.'brak dostępu do tego dokumentu spredaży');
				return false;
			}
	
			if(!empty($ia_placowka['wysylka_nazwa']) && !hlp_validator::alfanum($ia_placowka['nazwa']))
			{
				app::err($kom.'nieprawidłowa nazwa do wysyłki');
				return false;
			}
			
			if(!empty($ia_placowka['wysylka_adres']) && !hlp_validator::alfanum($ia_placowka['wysylka_adres']))
			{
				app::err($kom.'nieprawidłowy adres wysyłki');
				return false;
			}
			
			if(!empty($ia_placowka['wysylka_kod_pocztowy']) && !hlp_validator::kod_pocztowy($ia_placowka['wysylka_kod_pocztowy']))
			{
				app::err($kom.'nieprawidłowy kod pocztowy wysyłki');
				return false;
			}
		}
		
		$sql_typ_usera = session::get_user('typ')=='agencja' ? " AND id_users=".session::get_id() : '';
		
		//if(session::get_user('typ')=='placowka')
		{
			if(!$edit)
			{
				if(db::get_one("SELECT 1 FROM placowki WHERE regon='{$ia_placowka['regon']}' $sql_typ_usera"))
				{
					app::err($kom.'w systemie znajduje już się taki numer regon');
					return false;
				}
	
			}
			else
			{
				if(db::get_one("SELECT 1 FROM placowki WHERE regon='{$ia_placowka['regon']}' $sql_typ_usera AND id_placowki<>".$ia_placowka['id_placowki']))
				{
					app::err($kom.'w systemie znajduje już się taki numer regon');
					return false;
				}
	
			}
		}

		return true;
	}

	public static function check_dokument_sprzedazy_data($ia_dokument,$dokument_sprzedazy,$edit=false)
	{
		$kom = "Nie zapisano dokumentu sprzedaży, ";
		if($dokument_sprzedazy=='faktura')
		{
			if(empty($ia_dokument['nabywca_nazwa']) || !hlp_validator::alfanum($ia_dokument['nabywca_nazwa']))
			{
				app::err($kom.'nieprawidłowe nazwa nabywcy');
				return false;
			}
			
			if(empty($ia_dokument['nabywca_adres']) || !hlp_validator::alfanum($ia_dokument['nabywca_adres']))
			{
				app::err($kom.'nieprawidłowy adres nabywcy');
				return false;
			}
			
			if(empty($ia_dokument['nabywca_kod_pocztowy']) || !hlp_validator::kod_pocztowy($ia_dokument['nabywca_kod_pocztowy']))
			{
				app::err($kom.'nieprawidłowy kod pocztowy nabywcy');
				return false;
			}
			
			if(empty($ia_dokument['nabywca_poczta']) || !hlp_validator::alfanum($ia_dokument['nabywca_poczta']))
			{
				app::err($kom.'nieprawidłowa poczta nabywcy');
				return false;
			}
			
			if(empty($ia_dokument['nabywca_nip']) || !hlp_validator::nip($ia_dokument['nabywca_nip']))
			{
				app::err($kom.'nieprawidłowy NIP nabywcy');
				return false;
			}
			
			if(!empty($ia_dokument['platnik_nazwa']) && !hlp_validator::alfanum($ia_dokument['platnik_nazwa']))
			{
				app::err($kom.'nieprawidłowe nazwa płatnika');
				return false;
			}
			
			if(!empty($ia_dokument['platnik_adres']) && !hlp_validator::alfanum($ia_dokument['platnik_adres']))
			{
				app::err($kom.'nieprawidłowy adres płatnika');
				return false;
			}
	
			if(!empty($ia_dokument['platnik_kod_pocztowy']) && !hlp_validator::kod_pocztowy($ia_dokument['platnik_kod_pocztowy']))
			{
				app::err($kom.'nieprawidłowy kod pocztowy płatnika');
				return false;
			}
			
			if(empty($ia_dokument['platnik_poczta']) || !hlp_validator::alfanum($ia_dokument['platnik_poczta']))
			{
				app::err($kom.'nieprawidłowa poczta płatnika');
				return false;
			}
		}

		return true;
	}

	public static function zapisz_dane($ia_placowka,$id_users=false,$id_users_old=false,$czy_wewnetrzne=false)
	{
		$_SESSION['form']['a_placowka']=$ia_placowka;
		
		if(isset($ia_placowka['id_placowki']) && !self::sprawdz_dostep($ia_placowka['id_placowki']))
			return app::err('Brak dostępu do tej placówki');

		if(!self::check_placowka_data($ia_placowka,!empty($ia_placowka['id_placowki'])))
			return false;

		$is_placowka = session::get_user('typ')=='placowka' ? true : false;

		$a_dane = array("id_typy_szkol"=>$ia_placowka['id_typy_szkol'],
						 "uniqid_placowki"=>uniqid(),
						 "nazwa"=>$ia_placowka['nazwa'],
						 "regon"=>$ia_placowka['regon'],
						 "nazwa_skrocona"=>$ia_placowka['nazwa_skrocona'],
						 "adres"=>$ia_placowka['adres'],
						 "kod_pocztowy"=>$ia_placowka['kod_pocztowy'],
						 "poczta"=>$ia_placowka['poczta'],
						 //"dyrektor"=>$ia_placowka['dyrektor'],
						 //"dokument_sprzedazy"=>$ia_placowka['dokument_sprzedazy'],
						 "wysylka_nazwa"=>$is_placowka ? $ia_placowka['wysylka_nazwa'] : session::get_user('wysylka_nazwa'),
						 "wysylka_adres"=>$is_placowka ? $ia_placowka['wysylka_adres'] : session::get_user('wysylka_adres'),
						 "wysylka_poczta"=>$is_placowka ? $ia_placowka['wysylka_poczta'] : session::get_user('wysylka_poczta'),
						 "wysylka_kod_pocztowy"=>$is_placowka ? $ia_placowka['wysylka_kod_pocztowy'] : session::get_user('wysylka_kod_pocztowy'),
						 "uwagi_dla_kuriera"=>$is_placowka ? $ia_placowka['uwagi_dla_kuriera'] : session::get_user('uwagi_dla_kuriera'),
						 "id_users"=>$id_users ? $id_users : session::get_id(),
						 "typ"=>$czy_wewnetrzne ? 'wewnętrzne' : 'standard',
						 "status"=>'aktywna'
						 );
						 
		 if($id_users_old)
		 	$a_dane=array_merge($a_dane,array('id_placowki'=>$id_users_old));
						 
		 if(session::who('admin') && !$id_users)
		 	$a_dane = array_merge($a_dane,array('status'=>isset($ia_placowka['status']) ? 'aktywna' : 'nieaktywna'));
		 	
		 if(session::get('czy_zdalny'))
	 		$a_dane = array_merge($a_dane,array('status'=>'aktywna','typ'=>'wewnętrzne'));
			
	 	if($id_users)
			$a_dane = array_merge($a_dane,array('status'=>'aktywna'));
	 
		if(empty($ia_placowka['id_placowki']))
		{
			$id_placowki=self::insert("placowki",$a_dane);
			self::add_users_to_placowka($id_users ? $id_users : session::get_id(),$id_placowki,1);
			//mod_legitymacje::przypisz_karte_do_placowki(1,$id_placowki,'aktywna');
		}
		else
		{
			//$a_dane = array_merge($a_dane,array('id_users'=>session::get_id()));
			$id_placowki=self::update("placowki","id_placowki=".$ia_placowka['id_placowki'],$a_dane);
		}

		if($id_placowki===false)
		{
			app::err("Nie dodano placówki, błąd bazy danych");
			return false;
		}
		
		mkdir("images/placowki/$id_placowki",0777);

		app::ok();
		unset($_SESSION['form']['a_placowka']);
		return empty($ia_placowka['id_placowki']) ? $id_placowki : $ia_placowka['id_placowki'];
	}

	public static function add_users_to_placowka($id_users,$id_placowki,$czy_wlasny=1)
	{
		db::insert('users_placowki',array('id_users'=>$id_users,'id_placowki'=>$id_placowki,'czy_wlasny'=>$czy_wlasny));
	}

	//placowki ktore dodal user
	public static function get_placowki_usera($id_users)
	{
		return db::get_many("SELECT placowki.*,imie,nazwisko FROM placowki JOIN users USING(id_users) WHERE placowki.id_users=$id_users");
	}

	//placowki do ktorych ma dostep
	public static function get_dostepne_placowki_usera($id_users)
	{
		return db::get_many("SELECT placowki.*,users.imie,users.nazwisko FROM placowki JOIN users_placowki USING(id_placowki) JOIN users ON users.id_users=users_placowki.id_users WHERE users_placowki.id_users=$id_users");
	}
	
	public static function get_placowka($id_placowki)
	{
		return db::get_row("SELECT placowki.*,users.imie,users.nazwisko,email,uniqid_users FROM placowki JOIN users USING(id_users) WHERE placowki.id_placowki=$id_placowki");
	}
	
	public static function get_placowki($search=false,$czy_mailing='',$czy_wszystkie=false,$typy_szkol=false,$rodzaj_konta=false,$typy_legitymacji=false)
	{
		$sql_mailing = '';
		if($czy_mailing==1)
			$sql_mailing = " AND want_mailing=1";
		if($czy_mailing===0)
			$sql_mailing = " AND want_mailing=0";

		$sql_search = $search ? " AND (placowki.id_placowki LIKE '%$search%' OR placowki.nazwa LIKE '%$search%' OR placowki.adres LIKE '%$search%' OR placowki.kod_pocztowy LIKE '%$search%' OR placowki.poczta LIKE '%$search%')" : '';
		$sql_typy_szkol = $typy_szkol ? " AND id_typy_szkol IN($typy_szkol)" : '';
		$sql_rodzaj_konta = $rodzaj_konta ? " AND placowki.typ='$rodzaj_konta'" : '';

		$a_placowki = db::get_many("SELECT placowki.* FROM placowki JOIN users USING(id_users) WHERE 1=1 $sql_search $sql_mailing $sql_typy_szkol $sql_rodzaj_konta ORDER BY id_placowki DESC");
		
		if(session::who('admin'))
		{
			if($typy_legitymacji)
			{
				foreach($a_placowki as $index=>$a_placowka)
				{
					if(mod_legitymacje::sprawdz_dostepnosc_karty($typy_legitymacji,$a_placowka['id_placowki']))
						unset($a_placowki[$index]);
				}
			}
			
			if(!$czy_wszystkie)
			{
				foreach($a_placowki as $index=>$a_placowka)
				{
					$a_placowki[$index]['a_umowy'] = mod_umowy::get_umowy($a_placowka['id_placowki']);
				}
			}
			
			
		}
		
		return $a_placowki;
	}
	
	public static function usun_dostep_do_placowek($id_users)
	{
		db::delete('users_placowki',"id_users=$id_users AND czy_wlasny=0");
	}
	
	public static function sprawdz_poprawnosc_placowki($id_placowki)
	{
		if(!mod_placowki::sprawdz_dostep($id_placowki))
			return app::err('Brak dostępu');

		$a_placowka = mod_placowki::get_placowka($id_placowki);
		
		if(!$a_placowka)
			return app::err('Nieznana placówka');
		
		return $a_placowka;
	}
	
	public static function get_pracodawcy($id_placowki,$czy_szkoly=false)
	{
		$sql_szkoly = $czy_szkoly ? " AND typ=2" : ' AND typ=1';
		return db::get_many("SELECT * FROM pracodawcy WHERE id_placowki=$id_placowki $sql_szkoly");
	}
	
	public static function sprawdz_dostep_pracodawcy($id_pracodawcy)
	{
		return db::get_one("SELECT 1 FROM pracodawcy WHERE id_pracodawcy=$id_pracodawcy AND id_users=".session::get_id());
	}
	
	public static function get_pracodawca($id_pracodawcy)
	{
		return db::get_row("SELECT * FROM pracodawcy WHERE id_pracodawcy=$id_pracodawcy");
	}
	
	public static function get_szkola($id_szkoly)
	{
		return db::get_row("SELECT * FROM pracodawcy WHERE id_pracodawcy=$id_szkoly");
	}
	
	public static function zapisz_pracodawce($ia_pracodawca,$id_users)
	{
		if(!session::get('id_placowki'))
			return app::err('Nie wybrano placówki');
		/*
		if(empty($ia_pracodawca['nazwa']))
			return app::err('Musisz wpisać nazwę');
		
		if(empty($ia_pracodawca['dane1']))
			return app::err('Musisz wpisać dane 1');
		*/
		if(!empty($ia_pracodawca['dane1']) && strlen($ia_pracodawca['dane1'])<3)
			return app::err('Dane 1 muszą mieć przynajmniej 3 znaki');
		
		
		$a_dane = array("nazwa"=>$ia_pracodawca['nazwa'],
						 "dane1"=>$ia_pracodawca['dane1'],
						 "dane2"=>$ia_pracodawca['dane2'],
						 "dane3"=>$ia_pracodawca['dane3'],
						 "dane4"=>$ia_pracodawca['dane4'],
						 "dane5"=>empty($ia_pracodawca['dane5']) ? '' : $ia_pracodawca['dane5'],
						 "dane6"=>empty($ia_pracodawca['dane6']) ? '' : $ia_pracodawca['dane6'],
						 "id_users"=>$id_users ? $id_users : session::get_id(),
						 "typ"=>isset($ia_pracodawca['czy_szkoly']) ? 2 : 1,
						 "id_placowki"=>session::get('id_placowki')
						 );
						 
		if(empty($ia_pracodawca['id_pracodawcy']))
			$id_pracodawcy=self::insert("pracodawcy",$a_dane);
		else
			$id_pracodawcy=self::update("pracodawcy","id_pracodawcy=".$ia_pracodawca['id_pracodawcy'],$a_dane);

		if($id_users===false)
			return app::err("Nie dodano, błąd bazy danych");

		unset($_SESSION['form']['a_pracodawca']);

		app::ok("Dane zapisane");
		return $id_pracodawcy;
	}
	
	public static function subkonta_placowki($id_placowki)
	{
		return db::get_many("SELECT * FROM users JOIN users_placowki USING(id_users) WHERE id_placowki=$id_placowki");
	}
	
	public static function generuj_potwierdzenie_rejestracji_pdf($a_placowka,$a_dokument)
	{
		view::add('a_placowka',$a_placowka);
		view::add('a_dokument',$a_dokument);
		view::add('a_user',db::get_by_id('users',session::get_id()));
		$html = view::display('placowki/potwierdzenie_rejestracji_pdf.tpl',true,true);
		$url = "images/placowki/{$a_placowka['id_placowki']}/{$a_placowka['uniqid_placowki']}";
 		file_put_contents("{$url}.html", $html);
 		system("/usr/local/bin/wkhtmltopdf {$url}.html {$url}.pdf");
		unlink("{$url}.html");
	}
	
	public static function sprawdz_dostep_dokumentu_sprzedazy($id_dokumenty_sprzedazy)
	{
		$sql_user = !session::who('admin') ? " AND id_users=".session::get_id() : '';
		return db::get_one("SELECT 1 FROM users_placowki JOIN dokumenty_sprzedazy USING(id_placowki) WHERE id_dokumenty_sprzedazy=$id_dokumenty_sprzedazy $sql_user");
	}
	
	public static function zapisz_dane_dokumentu_sprzedazy($a_dokument,$id_placowki)
	{
		if(isset($a_dokument['id_dokumenty_sprzedazy']) && !self::sprawdz_dostep_dokumentu_sprzedazy($a_dokument['id_dokumenty_sprzedazy']))
			return app::err('Brak dostępu do dokumentu sprzedaży');

		if(!self::check_dokument_sprzedazy_data($a_dokument,'faktura',!empty($a_dokument['id_dokumenty_sprzedazy'])))
			return false;

		$a_dane = array("id_placowki"=>$id_placowki,
						 "nabywca_nazwa"=>$a_dokument['nabywca_nazwa'],
						 "nabywca_adres"=>$a_dokument['nabywca_adres'],
						 "nabywca_kod_pocztowy"=>$a_dokument['nabywca_kod_pocztowy'],
						 "nabywca_poczta"=>$a_dokument['nabywca_poczta'],
						 "nabywca_nip"=>$a_dokument['nabywca_nip'],
						 "platnik_nazwa"=>!empty($a_dokument['platnik_nazwa']) ? $a_dokument['platnik_nazwa'] : $a_dokument['nabywca_nazwa'],
						 "platnik_adres"=>!empty($a_dokument['platnik_adres']) ? $a_dokument['platnik_adres'] : $a_dokument['nabywca_adres'],
						 "platnik_kod_pocztowy"=>!empty($a_dokument['platnik_kod_pocztowy']) ? $a_dokument['platnik_kod_pocztowy'] : $a_dokument['nabywca_kod_pocztowy'],
						 "platnik_poczta"=>!empty($a_dokument['platnik_poczta']) ? $a_dokument['platnik_poczta'] : $a_dokument['nabywca_poczta']
						 );


		if(empty($a_dokument['id_dokumenty_sprzedazy']))
			$id_dokumenty_sprzedazy=self::insert("dokumenty_sprzedazy",$a_dane);
		else
			$id_dokumenty_sprzedazy=self::update("dokumenty_sprzedazy","id_dokumenty_sprzedazy=".$a_dokument['id_dokumenty_sprzedazy'],$a_dane);

		if($id_dokumenty_sprzedazy===false)
		{
			app::err("Nie dodano dokumentu sprzedaży, błąd bazy danych");
			return false;
		}
		
		app::ok();
		unset($_SESSION['form']['a_dokument']);
		return isset($a_dokument['id_dokumenty_sprzedazy']) ? $a_dokument['id_dokumenty_sprzedazy'] : $id_dokumenty_sprzedazy;
	}

	public static function get_dokument_sprzedazy($id_dokumenty_sprzedazy)
	{
		return db::get_by_id('dokumenty_sprzedazy',$id_dokumenty_sprzedazy);
	}

	public static function get_dokumenty_sprzedazy($id_placowki)
	{
		return db::get_many("SELECT * FROM dokumenty_sprzedazy WHERE id_placowki=$id_placowki");
	}
	
	public static function aktualizuj_dokument_sprzedazy_placowki($id_placowki,$id_dokumenty_srzedazy)
	{
		db::update('placowki',"id_placowki=$id_placowki",array('id_dokumenty_sprzedazy'=>$id_dokumenty_srzedazy));
	}
}

?>