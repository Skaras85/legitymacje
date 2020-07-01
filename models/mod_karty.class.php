<?php

class mod_karty extends db{
	
	public static function sprawdz_dostep($id_placowki)
	{
		return db::get_one("SELECT 1 FROM placowki WHERE id_placowki=$id_placowki AND id_dodajacego=".session::get_id());
	}
	
	public static function sprawdz_dostep_karty($id_karty,$id_placowki)
	{
		return db::get_one("SELECT 1 FROM karty_placowki WHERE id_placowki=$id_placowki AND id_karty=$id_karty");
	}
	
	public static function check_karty_data($ia_karta,$edit=false)
	{
		$kom = 'Nie zapisano, ';
			
		if(empty($ia_karta['nazwa']))
		{
			app::err($kom.'nieprawidłowa nazwa');
			return false;
		}
		
		if(empty($ia_karta['id_cenniki']) || !hlp_validator::id($ia_karta['id_cenniki']))
		{
			app::err($kom.'nieprawidłowy cennik');
			return false;
		}
		
		if(empty($ia_karta['id_sites']) || !hlp_validator::id($ia_karta['id_sites']))
		{
			app::err($kom.'nieprawidłowy opis');
			return false;
		}
		
		if($edit)
		{
			if(empty($ia_karta['id_karty']) || !hlp_validator::id($ia_karta['id_karty']))
			{
				app::err($kom.'nieprawidłowe id_karty');
				return false;
			}
		}

		return true;
	}

	public static function zapisz_dane($ia_karta)
	{
		$_SESSION['form']['a_karta']=$ia_karta;

		if(!self::check_karty_data($ia_karta,!empty($ia_karta['id_karty'])))
			return false;

		$a_dane = array("nazwa"=>$ia_karta['nazwa'],
						"id_cenniki"=>$ia_karta['id_cenniki'],
						"id_sites"=>$ia_karta['id_sites']);

		if(empty($ia_karta['id_karty']))
			$id_karty=self::insert("karty",$a_dane);
		else
			$id_karty=self::update("karty","id_karty=".$ia_karta['id_karty'],$a_dane);

		if($id_karty===false)
		{
			app::err("Nie dodano karty, błąd bazy danych");
			return false;
		}
		
		$id_karty = isset($ia_karta['id_karty']) ? $ia_karta['id_karty'] : $id_karty;

		if(is_uploaded_file($_FILES['file']['tmp_name']))
		{
			hlp_image::delete('images/karty/'.$id_karty);
			$ext = hlp_image::save($_FILES['file'],'images/karty/'.$id_karty);
			db::update('karty',"id_karty=$id_karty",array('img'=>$id_karty.$ext));
		}

		app::ok("Karta zapisana");
		unset($_SESSION['form']['a_karta']);
		return $id_karty;
	}
	
	public static function zapisz_domyslne_cenniki_wysylki_karty($id_karty,$a_cenniki,$a_max_ilosc_legitymacji)
	{
		db::delete('karty_cenniki_wysylki','id_karty='.$id_karty);
		
		foreach($a_cenniki as $id_sposoby_wysylki=>$id_cenniki)
		{
			db::insert('karty_cenniki_wysylki',array('id_karty'=>$id_karty,
													 'id_sposoby_wysylki'=>$id_sposoby_wysylki,
													 'id_cenniki'=>$id_cenniki,
													 'max_ilosc_legitymacji'=>$a_max_ilosc_legitymacji[$id_sposoby_wysylki]));
		}
	}
	
	public static function get_id_karty_legitymacji($id_legitymacji)
	{
		return db::get_one("SELECT id_karty FROM karty JOIN legitymacje USING(id_karty) WHERE id_legitymacje=$id_legitymacji");
	}
	
	public static function get_karta($id_karty)
	{
		return db::get_row("SELECT * FROM karty WHERE id_karty=$id_karty");
	}
	
	public static function get_karta_placowki($id_karty)
	{
		return db::get_row("SELECT karty.*,karty_placowki.status,sites.sludge FROM karty JOIN sites USING(id_sites) JOIN karty_placowki USING(id_karty) WHERE karty.id_karty=$id_karty AND id_placowki=".session::get('id_placowki'));
	}
	
	public static function get_karty()
	{
		return db::get_many("SELECT * FROM karty");
	}
	
	public static function get_pola_karty_raw($id_karty)
	{
		return db::get_many("SELECT * FROM karty_pola  WHERE id_karty=$id_karty ORDER BY kolejnosc");
	}
	
	public static function get_pola_karty($id_karty,$bez_zdjecia=false, $strona=false)
	{
		$sql_bez_zdjecia = $bez_zdjecia ? " AND id_karty_pola_typy NOT IN (13,14,19,20,21,22)" : '';
		
		$sql_strona = '';
		if($strona=='awers')
			$sql_strona = " AND czy_rewers=0";
		elseif($strona=='rewers')
			$sql_strona = " AND czy_rewers=1";
		
		$a_pola = db::get_many("SELECT * FROM karty_pola JOIN karty_pola_typy USING(id_karty_pola_typy) WHERE id_karty=$id_karty $sql_bez_zdjecia $sql_strona ORDER BY kolejnosc");
	
		if($a_pola)
		{
			foreach($a_pola as $index=>$a_pole)
			{
				if($a_pole['typ']=='radio label')
					$a_pola[$index]['a_opcje'] = db::get_many("SELECT * FROM karty_pola JOIN karty_pola_typy USING(id_karty_pola_typy) WHERE (radio_grupa={$a_pole['radio_grupa']} AND typ<>'radio label') AND id_karty=$id_karty ORDER BY kolejnosc");
			}
		}

		return $a_pola;
	}
	
	public static function get_pole_karty($id_karty_pole)
	{
		return db::get_row("SELECT * FROM karty_pola JOIN karty_pola_typy USING(id_karty_pola_typy) WHERE id_karty_pola=$id_karty_pole");
	}
	
	public static function usun_pola($id_karty)
	{
		db::delete('karty_pola',"id_karty=$id_karty");
	}
	
	public static function zapisz_pola($a_pola,$id_karty)
	{
		if($a_pola)
		{
			foreach($a_pola as $index=>$a_pole)
			{
				if(!empty($a_pole['x']) || !empty($a_pole['y']) || !empty($a_pole['kolumna']) || !empty($a_pole['kolejnosc']))
					db::insert('karty_pola',array('id_karty'=>$id_karty,
												  'id_karty_pola_typy'=>$a_pole['id_karty_pola_typy'],
												  'liczba_znakow'=>empty($a_pole['liczba_znakow']) ? 0 : $a_pole['liczba_znakow'],
												  'x'=>empty($a_pole['x']) ? 0 : $a_pole['x'],
												  'y'=>empty($a_pole['y']) ? 0 : $a_pole['y'],
												  'font_size'=>empty($a_pole['font_size']) ? 14 : $a_pole['font_size'],
												  'font_family'=>$a_pole['font_family'],
												  'kolumna'=>empty($a_pole['kolumna']) ? 0 : $a_pole['kolumna'],
												  'kolejnosc'=>$a_pole['kolejnosc'],
												  'placeholder'=>$a_pole['placeholder'],
												  'czy_rewers'=>isset($a_pole['czy_rewers']) ? 1 : 0,
												  'czy_zapamietac'=>isset($a_pole['czy_zapamietac']) ? 1 : 0));

			}
		}
	}
	
	public static function zapisz_cennik($id_karty,$id_cenniki,$a_sposoby_wysylki)
	{
		$id_karty_placowki = db::get_one("SELECT id_karty_placowki FROM karty_placowki WHERE id_karty=$id_karty AND id_placowki=".session::get('id_placowki'));
		
		db::update('karty_placowki',"id_karty_placowki=$id_karty_placowki",array('id_cenniki'=>$id_cenniki));

		db::delete('karty_placowki_cenniki_wysylki',"id_karty_placowki=$id_karty_placowki");
		
		foreach($a_sposoby_wysylki as $id_sposoby_wysylki=>$id_cenniki)
		{
			if($id_cenniki)
				db::insert('karty_placowki_cenniki_wysylki',array('id_karty_placowki'=>$id_karty_placowki,
														  		  'id_sposoby_wysylki'=>$id_sposoby_wysylki,
																  'id_cenniki'=>$id_cenniki));
		}
	}
	
	public static function get_cenniki_karty_wysylka($id_karty,$id_sposoby_wysylki=false)
	{
		$id_karty_placowki = db::get_one("SELECT id_karty_placowki FROM karty_placowki WHERE id_karty=$id_karty AND id_placowki=".session::get('id_placowki'));
		$sql_sposob_wysylki = $id_sposoby_wysylki ? " AND id_sposoby_wysylki=$id_sposoby_wysylki" : '';
		return db::get_many("SELECT * FROM karty_placowki_cenniki_wysylki WHERE id_karty_placowki=$id_karty_placowki $sql_sposob_wysylki");
	}
	
	public static function get_cennik_karty($id_karty)
	{
		return db::get_one("SELECT id_cenniki FROM karty_placowki WHERE id_karty=$id_karty AND id_placowki=".session::get('id_placowki'));
	}
	
	public static function get_domyslny_cennik_karty($id_karty)
	{
		return db::get_one("SELECT id_cenniki FROM karty WHERE id_karty=$id_karty");
	}
	
	public static function get_domyslne_cenniki_karty_wysylki($id_karty)
	{
		return db::get_many("SELECT * FROM karty_cenniki_wysylki WHERE id_karty=$id_karty");
	}
	
	public static function get_domyslny_cennik_karty_wysylki($id_karty,$id_sposoby_wysylki)
	{
		return db::get_one("SELECT id_cenniki FROM karty_cenniki_wysylki WHERE id_karty=$id_karty AND id_sposoby_wysylki=$id_sposoby_wysylki");
	}
	
	public static function get_cennik_karty_wysylka($id_karty,$id_sposoby_wysylki)
	{
		//pobieramy cennnik tej karty dla tej placówki
		$a_cennik = mod_karty::get_cenniki_karty_wysylka($id_karty,$id_sposoby_wysylki);
		$id_cenniki = $a_cennik ? $a_cennik[0]['id_cenniki'] : false;
		
		if(!$id_cenniki)
		{
			///jeśli nie ma takiego cennika pobieramy domyślny cennnik karty
			$id_cenniki = mod_karty::get_domyslny_cennik_karty_wysylki($id_karty,$id_sposoby_wysylki);
		}
		
		return $id_cenniki;
	}
	
	public static function get_cena_kart($liczba_kart,$id_cenniki)
	{
		return db::get_one("SELECT cena FROM cenniki_przedzialy_cenowe WHERE id_cenniki=$id_cenniki AND $liczba_kart>=od AND $liczba_kart<=do");
	}
	
	public static function get_cennik($id_cenniki)
	{
		return db::get_row("SELECT * FROM cenniki WHERE id_cenniki=$id_cenniki");
	}
	
	public static function get_ceny_z_cennika($id_cenniki)
	{
		return db::get_many("SELECT * FROM cenniki_przedzialy_cenowe WHERE id_cenniki=$id_cenniki");
	}
	
}

?>