<?php

class mod_produkty extends db
{
	public static function get_produkty_po_kategorii($id_kategorii)
	{
		$sql_admin = session::who('admin') ? '' : ' AND produkty.is_visible=1';
		return db::get_many("SELECT produkty.*,sites.text FROM produkty LEFT JOIN sites USING(id_sites) WHERE id_produkty_kategorie=$id_kategorii $sql_admin");
	}

	public static function get_kategoria($id_kategorii)
	{
		return db::get_row("SELECT * FROM produkty_kategorie WHERE id_produkty_kategorie=$id_kategorii");
	}
	
	public static function get_kategoria_produktu($id_produkty)
	{
		return db::get_row("SELECT produkty_kategorie.* FROM produkty_kategorie JOIN produkty USING(id_produkty_kategorie) WHERE id_produkty=$id_produkty");
	}
	
	public static function get_kategorie()
	{
		return db::get_many("SELECT * FROM produkty_kategorie");
	}
	
	public static function get_produkt($id_produkty)
	{
		return db::get_row("SELECT * FROM produkty WHERE id_produkty=$id_produkty");
	}
	
	public static function ustal_cene_produktow(&$a_produkty)
	{
		$cena_laczna = 0;
		
		if($a_produkty)
		{
			foreach($a_produkty as $index=>$a_produkt)
			{
				$id_cennika = self::get_cennik_produktu($a_produkt['id_produkty']);
				
				if(!$id_cennika)
					$id_cennika = $a_produkt['id_cenniki'];
				
				$a_produkty[$index]['cena'] = self::get_cena_produktu($a_produkt['ilosc'],$id_cennika);
				$cena_laczna += $a_produkty[$index]['cena']*$a_produkt['ilosc'];
			}
		}
		
		return $cena_laczna;
	}
	
	public static function get_cena_produktu($ilosc,$id_cenniki)
	{
		return db::get_one("SELECT cena FROM cenniki_przedzialy_cenowe WHERE id_cenniki=$id_cenniki AND $ilosc>=od AND $ilosc<=do");
	}
	
	public static function check_produkt_data($ia_produkt,$edit=false)
	{
		$kom = 'Nie zapisano, ';
			
		if(empty($ia_produkt['nazwa']))
		{
			app::err($kom.'nieprawidłowa nazwa');
			return false;
		}
		
		if(empty($ia_produkt['id_cenniki']) || !hlp_validator::id($ia_produkt['id_cenniki']))
		{
			app::err($kom.'nieprawidłowy cennik');
			return false;
		}
		
		if(empty($ia_produkt['id_sites']) || !hlp_validator::id($ia_produkt['id_sites']))
		{
			app::err($kom.'nieprawidłowy opis');
			return false;
		}
		
		if(empty($ia_produkt['id_produkty_kategorie']) || !hlp_validator::id($ia_produkt['id_produkty_kategorie']))
		{
			app::err($kom.'nieprawidłowa kategoria');
			return false;
		}
		
		if($edit)
		{
			if(empty($ia_produkt['id_produkty']) || !hlp_validator::id($ia_produkt['id_produkty']))
			{
				app::err($kom.'nieprawidłowe id_produkty');
				return false;
			}
		}

		return true;
	}
	
	public static function zapisz_dane($ia_produkt)
	{
		$_SESSION['form']['a_produkt']=$ia_produkt;

		if(!self::check_produkt_data($ia_produkt,!empty($ia_produkt['id_produkty'])))
			return false;

		$a_dane = array("nazwa"=>$ia_produkt['nazwa'],
						"id_produkty_kategorie"=>$ia_produkt['id_produkty_kategorie'],
						"id_cenniki"=>$ia_produkt['id_cenniki'],
						"id_sites"=>$ia_produkt['id_sites']);

		if(empty($ia_produkt['id_produkty']))
			$id_produkty=self::insert("produkty",$a_dane);
		else
			$id_produkty=self::update("produkty","id_produkty=".$ia_produkt['id_produkty'],$a_dane);

		if($id_produkty===false)
		{
			app::err("Nie dodano karty, błąd bazy danych");
			return false;
		}
		
		$id_produkty = isset($ia_produkt['id_produkty']) ? $ia_produkt['id_produkty'] : $id_produkty;

		if(is_uploaded_file($_FILES['file']['tmp_name']))
		{
			hlp_image::delete('images/produkty/'.$id_produkty);
			$ext = hlp_image::save($_FILES['file'],'images/produkty/'.$id_produkty);
			db::update('produkty',"id_produkty=$id_produkty",array('img'=>$id_produkty.$ext));
		}

		app::ok("Produkt zapisany");
		unset($_SESSION['form']['a_produkt']);
		return $id_produkty;
	}
	
	public static function zapisz_cenniki_wysylki_produkty($id_produkty,$a_cenniki,$id_placowki=0)
	{
		db::delete('produkty_cenniki_wysylki','id_produkty='.$id_produkty);
		
		foreach($a_cenniki as $id_sposoby_wysylki=>$id_cenniki)
		{
			db::insert('produkty_cenniki_wysylki',array('id_produkty'=>$id_produkty,
													 	'id_sposoby_wysylki'=>$id_sposoby_wysylki,
													 	'id_cenniki'=>$id_cenniki,
														'id_placowki'=>$id_placowki));
		}
	}
	
	public static function get_cenniki_produktu_wysylki($id_produktu,$id_placowki=0)
	{
		$sql_id_placowki = $id_placowki ? " AND id_placowki=$id_placowki" : '';
		return db::get_many("SELECT * FROM produkty_cenniki_wysylki WHERE id_produkty=$id_produktu $sql_id_placowki");
	}
	
	public static function get_cennik_produktu_wysylka($id_produkty,$id_sposoby_wysylki,$id_placowki=0)
	{
		return db::get_one("SELECT id_cenniki FROM produkty_cenniki_wysylki WHERE id_produkty=$id_produkty AND id_sposoby_wysylki=$id_sposoby_wysylki AND id_placowki=$id_placowki");
	}

	public static function get_najwyzsza_cena_przesylki($a_produkty,$id_sposoby_wysylki)
	{
		$najwyzsza_cena_przesylki = 0;
		if($a_produkty)
		{
			foreach($a_produkty as $a_produkt)
			{
				$id_cenniki_wysylka = self::get_cennik_produktu_wysylka($a_produkt['id_produkty'],$id_sposoby_wysylki,session::get('id_placowki'));
				
				if(!$id_cenniki_wysylka)
					$id_cenniki_wysylka = self::get_cennik_produktu_wysylka($a_produkt['id_produkty'],$id_sposoby_wysylki,0);
				
				$cena_przesylki = self::get_cena_produktu($a_produkt['ilosc'],$id_cenniki_wysylka);
	
				if($najwyzsza_cena_przesylki<$cena_przesylki)
					$najwyzsza_cena_przesylki = $cena_przesylki;
			}
		}
		return $najwyzsza_cena_przesylki;
	}
	
	public static function get_cennik_produktu($id_produktu)
	{
		return db::get_one("SELECT id_cenniki FROM produkty_placowki_cenniki WHERE id_produkty=$id_produktu AND id_placowki=".session::get('id_placowki'));
	}
	
	public static function get_domyslny_cennik_produktu($id_produktu)
	{
		return db::get_one("SELECT id_cenniki FROM produkty WHERE id_produktu=$id_produktu");
	}
	
	public static function zapisz_cennik($id_produktu,$id_cenniki,$a_sposoby_wysylki)
	{
		db::delete('produkty_placowki_cenniki',"id_produkty=$id_produktu AND id_placowki=".session::get('id_placowki'));
		db::insert('produkty_placowki_cenniki',array('id_produkty'=>$id_produktu,
												   'id_placowki'=>session::get('id_placowki'),
												   'id_cenniki'=>$id_cenniki));
			
		
		db::delete('produkty_cenniki_wysylki',"id_produkty=$id_produktu AND id_placowki=".session::get('id_placowki'));
		
		foreach($a_sposoby_wysylki as $id_sposoby_wysylki=>$id_cenniki)
		{
			db::insert('produkty_cenniki_wysylki',array('id_produkty'=>$id_produktu,
												  		'id_sposoby_wysylki'=>$id_sposoby_wysylki,
														'id_cenniki'=>$id_cenniki,
														'id_placowki'=>session::get('id_placowki')));
		}
	}

}

?>