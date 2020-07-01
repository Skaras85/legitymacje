<?php

class mod_koszyk extends db
{
	public static function dodaj($a_legitymacje)
	{
		if($a_legitymacje)
		{
			foreach($a_legitymacje as $id_legitymacje=>$asd)
			{
				if(self::get_liczba_kart() && self::get_id_karty_z_koszyka(session::get_id())!=mod_karty::get_id_karty_legitymacji($id_legitymacje))
					return app::err('Możesz zamówić tylko jeden typ legitymacji na raz');
				
				if(!self::sprawdz_czy_juz_w_koszyku($id_legitymacje,'legitymacja') && mod_legitymacje::sprawdz_dostep_legitymacji($id_legitymacje))
					db::insert('koszyk',array('id_users'=>session::get_id(),
											  'id'=>$id_legitymacje,
											  'typ'=>'legitymacja',
											  'add_date'=>'NOW()'));
			}
		}
		
		return app::ok('Dodano do koszyka');
	}
	
	public static function dodaj_produkt($id_produkty,$ilosc)
	{
		if(empty($id_produkty) || !hlp_validator::id($id_produkty))
			return app::err("Nieznany produkt");
		
		if(empty($ilosc) || !hlp_validator::id($ilosc))
			return app::err("Ilość musi być dodatnia");
		
		$a_produkt = mod_produkty::get_produkt($id_produkty);
		
		if(!$a_produkt)
			return app::err("Brak takiego produktu");
		
		if($a_produkt['id_produkty_kategorie']==1 && !mod_legitymacje::czy_placowka_ma_legitymacje_szkolne(session::get('id_placowki')))
			return app::err("Aby zamówić hologram musisz mieć aktywna legitymację szkolną");

		if(self::sprawdz_czy_juz_w_koszyku($id_produkty,'produkt'))
		{
			$ilosc_w_koszyku = db::get_one("SELECT ilosc FROM koszyk WHERE id=$id_produkty AND typ='produkt' AND id_users=".session::get_id());
			db::update('koszyk',"id=$id_produkty AND typ='produkt' AND id_users=".session::get_id(),array('ilosc'=>$ilosc_w_koszyku+$ilosc));
		}
		else
			db::insert('koszyk',array('id_users'=>session::get_id(),
									  'id'=>$id_produkty,
									  'typ'=>'produkt',
									  'ilosc'=>$ilosc,
									  'add_date'=>'NOW()'));
									  
	    return app::ok('Dodano do koszyka');
	}

	public static function zwroc_produkty($id_users)
	{
		return db::get_many("SELECT * FROM koszyk JOIN produkty ON id=id_produkty WHERE id_users=$id_users AND typ='produkt'");
	}
	
	public static function sprawdz_czy_juz_w_koszyku($id,$typ)
	{
		return db::get_one("SELECT 1 FROM koszyk WHERE id=$id AND typ='$typ' AND id_users=".session::get_id());
	}
	
	public static function get_liczba_kart()
	{
		return db::get_one("SELECT COUNT(*) FROM koszyk JOIN legitymacje ON id=id_legitymacje WHERE id_users=".session::get_id()." AND typ='legitymacja' AND id_placowki=".session::get('id_placowki'));
	}
	
	public static function get_liczba_produktow()
	{
		return db::get_one("SELECT COUNT(*) FROM koszyk JOIN produkty ON id=id_produkty WHERE id_users=".session::get_id()." AND typ='produkt' AND id_users=".session::get_id());
	}
	
	public static function get_legitymacje($id_users)
	{
		return db::get_many("SELECT legitymacje.*,id_koszyk FROM koszyk JOIN legitymacje ON id_legitymacje=id WHERE id_users=$id_users AND typ='legitymacja' AND id_placowki=".session::get('id_placowki'));
	}
	
	public static function get_id_karty_z_koszyka($id_users)
	{
		return db::get_one("SELECT legitymacje.id_karty FROM koszyk JOIN legitymacje ON id_legitymacje=id WHERE id_users=$id_users AND typ='legitymacja' LIMIT 1");
	}
	
	public static function get_dane_legitymacji($id_karty,$id_placowki)
	{
		return db::get_many("SELECT * FROM legitymacje JOIN koszyk ON id_legitymacje=id WHERE id_karty=$id_karty AND id_placowki=$id_placowki AND typ='legitymacja'");
	}
	
	public static function get_legitymacje_tabela($id_users)
	{
		$id_karty = self::get_id_karty_z_koszyka(session::get_id());
		return $id_karty ? mod_legitymacje::zwroc_tabele_z_danymi(mod_karty::get_pola_karty($id_karty),self::get_dane_legitymacji($id_karty,session::get('id_placowki')),true,false,$id_karty) : false;
	}
	
	public static function usun($a_pozycje,$typ)
	{
		if($a_pozycje)
		{
			foreach($a_pozycje as $id=>$asd)
			{
				db::delete('koszyk',"id=$id AND typ='$typ' AND id_users=".session::get_id().' LIMIT 1');
			}
		}
	}
	
	public static function sprawdz_koszyk()
	{
		return db::get_one("SELECT 1 FROM koszyk WHERE id_users=".session::get_id());
	}
	
	public static function get_sposoby_wysylki($czy_z_cena=false)
	{
		$liczba_kart = mod_koszyk::get_liczba_kart();
		
		if($liczba_kart)
		{
			$id_karty = mod_koszyk::get_id_karty_z_koszyka(session::get_id());
			$a_sposoby_wysylki = db::get_many("SELECT * FROM karty_cenniki_wysylki JOIN sposoby_wysylki USING(id_sposoby_wysylki) WHERE max_ilosc_legitymacji>=$liczba_kart AND id_karty=$id_karty");
		}
		else
			$a_sposoby_wysylki = db::get_all('sposoby_wysylki');
		
		if($czy_z_cena)
		{
			foreach($a_sposoby_wysylki as $index=>$a_sposob_wysylki)
			{
				if($liczba_kart)
					$a_sposoby_wysylki[$index]['cena_przesylki'] = mod_karty::get_cena_kart($liczba_kart,$a_sposob_wysylki['id_cenniki']);
				else
				{
					$a_produkty = self::zwroc_produkty(session::get_id());
					$a_sposoby_wysylki[$index]['cena_przesylki'] = mod_produkty::get_najwyzsza_cena_przesylki($a_produkty,$a_sposob_wysylki['id_sposoby_wysylki']);
				}
			}
		}
		return $a_sposoby_wysylki;
	}
	
	public static function get_sposob_wysylki($id_sposoby_wysylki)
	{
		return db::get_by_id('sposoby_wysylki',$id_sposoby_wysylki);
	}
	
	public static function get_sposoby_platnosci()
	{
		return db::get_all('sposoby_platnosci');
	}
	
	public static function get_sposob_platnosci($id_sposoby_platnosci)
	{
		return db::get_by_id('sposoby_platnosci',$id_sposoby_platnosci);
	}
	
}

?>