<?php

class mod_przesylki extends db{
	
	public static function sprawdz_dostep_przesylki($id_przesylki)
	{
		return db::get_one("SELECT 1 FROM przesylki JOIN users_placowki USING(id_placowki) WHERE id_przesylki=$id_przesylki");
	}
	
	public static function get_przesylki($id_placowki=false,$id_zamowienia=false,$id_users=false)
	{
		$sql_id_placowki = $id_placowki ? " AND przesylki.id_placowki=$id_placowki" : '';
		$sql_id_zamowienia = $id_zamowienia!==false ? " AND przesylki.id_zamowienia=$id_zamowienia" : '';
		$sql_id_users = $id_users ? " AND users_placowki.id_users=$id_users" : '';
		return db::get_many("SELECT przesylki.*,zamowienia.status as status_zamowienia FROM przesylki LEFT JOIN zamowienia USING(id_zamowienia) LEFT JOIN users_placowki ON users_placowki.id_placowki=przesylki.id_placowki WHERE 1=1 $sql_id_placowki $sql_id_zamowienia $sql_id_users ORDER BY numer_przesylki");
	}
	
	public static function get_przesylka($id_przesylki)
	{
		return db::get_row("SELECT przesylki.*,zamowienia.status as status_zamowienia, CONCAT(users.nazwisko,' ',users.imie) as adresat,
				 			CONCAT(u.nazwisko,' ',u.imie) as admin,placowki.nazwa as nazwa_placowki
							FROM przesylki 
							LEFT JOIN zamowienia USING(id_zamowienia) 
							LEFT JOIN users ON users.id_users=przesylki.id_mail_adresat 
							LEFT JOIN placowki ON przesylki.id_placowki=placowki.id_placowki
							JOIN users u ON u.id_users=przesylki.id_administratora
							WHERE id_przesylki=$id_przesylki");
	}
	
	public static function check_przesylka_data($ia_przesylka,$edit=false)
	{
		$kom = 'Nie zapisano, ';

		if(empty($ia_przesylka['rodzaj']))
		{
			app::err($kom.'nieprawidłowe typ');
			return false;
		}
		
		if(empty($ia_przesylka['numer_listu']))
		{
			app::err($kom.'nieprawidłowy numer listu');
			return false;
		}
		
		if(empty($ia_przesylka['rok']) || !hlp_validator::id($ia_przesylka['rok']))
		{
			app::err($kom.'nieprawidłowe rok daty nadania');
			return false;
		}
		
		if(empty($ia_przesylka['miesiac']) || !hlp_validator::id($ia_przesylka['miesiac']))
		{
			app::err($kom.'nieprawidłowe miesiąc daty nadania');
			return false;
		}
		
		if(empty($ia_przesylka['dzien']) || !hlp_validator::id($ia_przesylka['dzien']))
		{
			app::err($kom.'nieprawidłowe dzień daty nadania');
			return false;
		}
		
		if(empty($ia_przesylka['data_otrzymania']) || !hlp_validator::data($ia_przesylka['data_otrzymania']))
		{
			app::err($kom.'nieprawidłowa data otrzymania');
			return false;
		}
		
		if(!isset($ia_przesylka['liczba_legitymacji']) || !hlp_validator::numer($ia_przesylka['liczba_legitymacji']))
		{
			app::err($kom.'nieprawidłowa liczba legitymacji');
			return false;
		}
		
		if(empty($ia_przesylka['czy_kompletne']) || !in_array($ia_przesylka['czy_kompletne'],array('tak','nie')))
		{
			app::err($kom.'nieprawidłowe czy kompletne');
			return false;
		}
		
		if(empty($ia_przesylka['id_mail_adresat']) || !hlp_validator::id($ia_przesylka['id_mail_adresat']))
		{
			app::err($kom.'nieprawidłowy adresat maila');
			return false;
		}
		
		if(empty($ia_przesylka['id_placowki']) || !hlp_validator::id($ia_przesylka['id_placowki']))
		{
			app::err($kom.'nieprawidłowa placówka');
			return false;
		}

		return true;
	}

	public static function stworz_numer_przesylki()
	{
		$liczba = db::get_one("SELECT COUNT(*) FROM przesylki")+1;
		
		for($i=strlen($liczba);$i<8;$i++)
		{
			$liczba = '0'.$liczba;
		}

		return 'L'.$liczba;
	}

	public static function zapisz_przesylke($ia_przesylka)
	{
		
		$_SESSION['form']['a_przesylka']=$ia_przesylka;

		if(!self::check_przesylka_data($ia_przesylka,!empty($ia_przesylka['id_przesylki'])))
			return false;
		
		$numer_przesylki = self::stworz_numer_przesylki();

		$a_dane = array("id_placowki"=>$ia_przesylka['id_placowki'],
						 "rodzaj"=>$ia_przesylka['rodzaj'],
						 "numer_listu"=>$ia_przesylka['numer_listu'],
						 "numer_przesylki"=>$numer_przesylki,
						 "data_nadania"=>"{$ia_przesylka['rok']}-{$ia_przesylka['miesiac']}-{$ia_przesylka['dzien']}",
						 "data_otrzymania"=>$ia_przesylka['data_otrzymania'],
						 "liczba_legitymacji"=>$ia_przesylka['liczba_legitymacji'],
						 "czy_kompletne"=>$ia_przesylka['czy_kompletne']=='tak' ? 1 : 0,
						 "uwagi"=>nl2br($ia_przesylka['uwagi']),
						 "id_zamowienia"=>$ia_przesylka['id_zamowienia'],
						 "czy_mail"=>isset($ia_przesylka['czy_wyslac_maila']) ? 1 : 0,
						 "id_mail_adresat"=>$ia_przesylka['id_mail_adresat'],
						 "id_administratora"=>session::get_id(),
						 "add_date"=>'NOW()'
						 );

		if(empty($ia_przesylka['id_przesylki']))
		{
			$id_przesylki=self::insert("przesylki",$a_dane);
		}
		else
			$id_przesylki=self::update("przesylki","id_przesylki=".$ia_przesylka['id_przesylki'],$a_dane);

		if($id_przesylki===false)
		{
			app::err("Nie dodano przesyłki, błąd bazy danych");
			return false;
		}

		app::ok("Przesyłka zapisana, numer ".$numer_przesylki);
		unset($_SESSION['form']['a_przesylka']);
		return $numer_przesylki;
	}

	public static function przypisz_przesylke_do_zamowienia($id_przesylki,$id_zamowienia)
	{
		db::update('przesylki',"id_przesylki=$id_przesylki",array('id_zamowienia'=>$id_zamowienia));
	}
	
	
}

?>