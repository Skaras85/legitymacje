<?php

class mod_zamowienia extends db
{
	public static function sprawdz_dostep($id_zamowienia)
	{
		$id_users = session::get_user('parent_id') ? session::get_user('parent_id') : session::get_id();

		$a_subkonta = mod_users::get_subkonta($id_users);
		
		if($a_subkonta)
		{
			foreach($a_subkonta as $a_subkonto)
			{
				$id_users .= ','.$a_subkonto['id_users'];
			}
		}
		
		return db::get_one("SELECT 1 FROM zamowienia WHERE id_zamowienia=$id_zamowienia AND id_users IN ($id_users)");
	}
	
	public static function sprawdz_zamowienie($a_zamowienie)
	{
		if(session::get_user('typ')=='placowka' && (!isset($a_zamowienie['id_dokumenty_sprzedazy']) || $a_zamowienie['id_dokumenty_sprzedazy']>0 && (!hlp_validator::id($a_zamowienie['id_dokumenty_sprzedazy']) || !mod_placowki::sprawdz_dostep_dokumentu_sprzedazy($a_zamowienie['id_dokumenty_sprzedazy']))))
			return app::err('Nieznany dokument sprzedaży');
		/*
		if($a_zamowienie['dokument_sprzedazy']=='faktura')
		{
			if(empty($a_zamowienie['nabywca_nazwa']) || !hlp_validator::alfanum($a_zamowienie['nabywca_nazwa']))
				return app::err('Nieprawidłowa nazwa nabywcy');
			
			if(empty($a_zamowienie['nabywca_adres']) || !hlp_validator::alfanum($a_zamowienie['nabywca_adres']))
				return app::err('Nieprawidłowa adres nabywcy');
			
			if(empty($a_zamowienie['nabywca_kod_pocztowy']) || !hlp_validator::kod_pocztowy($a_zamowienie['nabywca_kod_pocztowy']))
				return app::err('Nieprawidłowa kod pocztowy nabywcy');
			
			if(empty($a_zamowienie['nabywca_poczta']))
				return app::err('Nieprawidłowa poczta nabywcy');
			
			if($a_zamowienie['dokument_sprzedazy']=='faktura' && (empty($a_zamowienie['nabywca_nip']) || !hlp_validator::nip($a_zamowienie['nabywca_nip'])))
				return app::err('Nieprawidłowa NIP nabywcy');
		}
		*/
		if(empty($a_zamowienie['id_sposoby_platnosci']) || !hlp_validator::id($a_zamowienie['id_sposoby_platnosci']) || !mod_koszyk::get_sposob_platnosci($a_zamowienie['id_sposoby_platnosci']))
			return app::err('Nieprawidłowy sposób płatności');
		
		if(empty($a_zamowienie['id_sposoby_wysylki']) || !hlp_validator::id($a_zamowienie['id_sposoby_wysylki']) || !mod_koszyk::get_sposob_wysylki($a_zamowienie['id_sposoby_wysylki']))
			return app::err('Nieprawidłowy sposób wysyłki');
		
		if($a_zamowienie['id_sposoby_platnosci']==4 && $a_zamowienie['id_sposoby_wysylki']!=4)
			return app::err('Nieprawidłowy sposób wysyłki');
		
		if($a_zamowienie['id_dokumenty_sprzedazy']==0 && in_array($a_zamowienie['id_sposoby_platnosci'], array(1,5)))
			return app::err('Nieprawidłowy sposób płatności');

		if($a_zamowienie['id_sposoby_platnosci']==3 && $a_zamowienie['id_sposoby_wysylki']!=5)
			return app::err('Nieprawidłowy sposób wysyłki');
		
		if(($a_zamowienie['id_sposoby_platnosci']==1 || $a_zamowienie['id_sposoby_platnosci']==5) && $a_zamowienie['id_sposoby_wysylki']==5)
			return app::err('Nieprawidłowy sposób wysyłki');
			
		return true;
	}

	public static function dodaj_produkty_do_zamowienia($id_zamowienia,$id_sposoby_wysylki)
	{
		$a_produkty = mod_koszyk::zwroc_produkty(session::get_id());
		$cena_przesylki = mod_produkty::get_najwyzsza_cena_przesylki($a_produkty,$id_sposoby_wysylki);
		
		if($a_produkty)
		{
			foreach($a_produkty as $a_produkt)
			{
				$id_cenniki_przesylki = mod_produkty::get_cennik_produktu_wysylka($a_produkt['id_produkty'],$id_sposoby_wysylki,session::get('id_placowki'));
				
				if(!$id_cenniki_przesylki)
					$id_cenniki_przesylki = mod_produkty::get_cennik_produktu_wysylka($a_produkt['id_produkty'],$id_sposoby_wysylki,0);
				
				$id_cennika = mod_produkty::get_cennik_produktu($a_produkt['id_produkty']);
				
				if(!$id_cennika)
					$id_cennika = $a_produkt['id_cenniki'];
				
				$cena = mod_produkty::get_cena_produktu($a_produkt['ilosc'],$id_cennika);
				
				$id_zamowienia_produkty = db::insert('zamowienia_produkty',array(
													 'id_produkty'=>$a_produkt['id_produkty'],
												   	 'id_zamowienia'=>$id_zamowienia,
												   	 'ilosc'=>$a_produkt['ilosc'],
													 'id_cenniki'=>$id_cennika,
													 'id_cenniki_wysylka'=>$id_cenniki_przesylki,
													 'cena'=>mod_produkty::get_cena_produktu($a_produkt['ilosc'],$id_cennika),
													 'cena_wysylki'=>$cena_przesylki));
														   			   
			    if($id_zamowienia_produkty)
			   		db::delete('koszyk','id_koszyk='.$a_produkt['id_koszyk']);
			}
		}
		return true;
	}

	public static function dodaj_legitymacje_do_zamowienia($id_zamowienia)
	{
		$a_legitymacje = mod_koszyk::get_legitymacje(session::get_id());
		
		if($a_legitymacje)
		{
			foreach($a_legitymacje as $a_legitymacja)
			{//28-02-2021
				$data_waznosci = !empty($a_legitymacja['kol5']) ? $a_legitymacja['kol5'] : mod_panel::get_parametr('data_waznosci_legitymacji');
				$id_zamowienia_legitymacje = db::insert('zamowienia_legitymacje',array('id_legitymacje'=>$a_legitymacja['id_legitymacje'],
														   'id_zamowienia'=>$id_zamowienia,
														   'id_karty'=>$a_legitymacja['id_karty'],
														   'id_placowki'=>$a_legitymacja['id_placowki'],
														   'kol1'=>$a_legitymacja['kol1'],
														   'kol2'=>$a_legitymacja['kol2'],
														   'kol3'=>$a_legitymacja['kol3'],
														   'kol4'=>$a_legitymacja['kol4'],
														   'kol5'=>$data_waznosci,
														   'kol6'=>$a_legitymacja['kol6'],
														   'kol7'=>$a_legitymacja['kol7'],
														   'kol8'=>$a_legitymacja['kol8'],
														   'kol9'=>$a_legitymacja['kol9'],
														   'kol10'=>$a_legitymacja['kol10'],
														   'kol11'=>$a_legitymacja['kol11'],
														   'kol12'=>$a_legitymacja['kol12'],
														   'kol13'=>$a_legitymacja['kol13']));
														   			   
			    if($id_zamowienia_legitymacje)
			   		db::delete('koszyk','id_koszyk='.$a_legitymacja['id_koszyk']);
			}
		}
		return true;
	}

	public static function stworz_numer_zamowienia()
	{
		$rok = date('Y');
		$liczba = db::get_one("SELECT COUNT(*) FROM zamowienia WHERE YEAR(data_zlozenia)=".$rok)+1;
		
		if($liczba<10)
			return '000'.$liczba.'/'.$rok;
		elseif($liczba<100)
			return '00'.$liczba.'/'.$rok;
		elseif($liczba<1000)
			return '0'.$liczba.'/'.$rok;
		else
			return $liczba.'/'.$rok;
	}

	public static function zloz_zamowienie($a_zamowienie)
	{
		$a_placowka = mod_placowki::get_placowka(session::get('id_placowki'));
		
		if(!empty($a_zamowienie['id_dokumenty_sprzedazy']))
			$a_dokument = mod_placowki::get_dokument_sprzedazy($a_zamowienie['id_dokumenty_sprzedazy']);
		
		$a_posob_platnosci = mod_koszyk::get_sposob_platnosci($a_zamowienie['id_sposoby_platnosci']);
		
		$a_user = mod_users::get_user(session::get_id());
		
		$id_zamowienia = db::insert('zamowienia',array('id_placowki'=>session::get('id_placowki'),
													   'id_users'=>session::get_id(),
													   'id_karty'=>mod_koszyk::get_id_karty_z_koszyka(session::get_id()),
													   'status'=>'złożone',
													   'numer_zamowienia'=>self::stworz_numer_zamowienia(),
													   'id_sposoby_platnosci'=>$a_zamowienie['id_sposoby_platnosci'],
													   'id_sposoby_wysylki'=>$a_zamowienie['id_sposoby_wysylki'],
													   'nabywca_nazwa'=>isset($a_dokument) ? $a_dokument['nabywca_nazwa'] : $a_user['nazwa'],
													   'nabywca_adres'=>isset($a_dokument) ? $a_dokument['nabywca_adres'] : $a_user['ulica'],
													   'nabywca_kod_pocztowy'=>isset($a_dokument) ? $a_dokument['nabywca_kod_pocztowy'] : $a_user['kod_pocztowy'],
													   'nabywca_poczta'=>isset($a_dokument) ? $a_dokument['nabywca_poczta'] : $a_user['miasto'],
													   'nabywca_nip'=>isset($a_dokument) ? $a_dokument['nabywca_nip'] : $a_user['nip'],
													   'placowka_nazwa'=>$a_placowka['nazwa'],
													   'placowka_regon'=>$a_placowka['regon'],
													   'placowka_adres'=>$a_placowka['adres'],
													   'placowka_kod_pocztowy'=>$a_placowka['kod_pocztowy'],
													   'placowka_poczta'=>$a_placowka['poczta'],
													   'placowka_dyrektor'=>$a_placowka['dyrektor'],
													   'wysylka_nazwa'=>$a_user['typ']=='placowka' ? $a_zamowienie['wysylka_nazwa'] : $a_user['wysylka_nazwa'],
													   'wysylka_adres'=>$a_user['typ']=='placowka' ? $a_zamowienie['wysylka_adres'] : $a_user['wysylka_adres'],
													   'wysylka_poczta'=>$a_user['typ']=='placowka' ? $a_zamowienie['wysylka_poczta'] : $a_user['wysylka_poczta'],
													   'wysylka_kod_pocztowy'=>$a_user['typ']=='placowka' ? $a_zamowienie['wysylka_kod_pocztowy'] : $a_user['wysylka_kod_pocztowy'],
													   'platnik_nazwa'=>isset($a_dokument) ? $a_dokument['platnik_nazwa'] : $a_user['platnik_nazwa'],
													   'platnik_adres'=>isset($a_dokument) ? $a_dokument['platnik_adres'] : $a_user['platnik_adres'],
													   'platnik_kod_pocztowy'=>isset($a_dokument) ? $a_dokument['platnik_kod_pocztowy'] : $a_user['platnik_kod_pocztowy'],
													   'platnik_poczta'=>isset($a_dokument) ? $a_dokument['platnik_poczta'] : $a_user['platnik_poczta'],
													   'uwagi_dla_kuriera'=>$a_zamowienie['uwagi_dla_kuriera'],
													   'uwagi'=>$a_zamowienie['uwagi'],
													   'id_dokumenty_sprzedazy'=>isset($a_zamowienie['id_dokumenty_sprzedazy']) ? $a_zamowienie['id_dokumenty_sprzedazy']  : 0,
													   'termin_platnosci_faktury'=>$a_posob_platnosci['termin_platnosci_faktury'],
													   'data_zlozenia'=>'NOW()',
													   'id_pracownika'=>session::get('czy_zdalny') ? session::get('id_pracownika') : 0));
													   
	    self::dodaj_legitymacje_do_zamowienia($id_zamowienia);
		self::dodaj_produkty_do_zamowienia($id_zamowienia,$a_zamowienie['id_sposoby_wysylki']);
									
		app::ok('Twoje zamówienie zostało złożone');
		return $id_zamowienia;

	}
	
	public static function dodaj_karty_do_zamowienia()
	{
		//przenisc do zamowien
		$id_zamowienia = db::get_one("SELECT id_zamowienia FROM zamowienia WHERE status='złożone' AND id_placowki=".session::get('id_placowki'));
		self::dodaj_legitymacje_do_zamowienia($id_zamowienia);

		app::ok('Karty zostały dopisane do zamówienia');
		return true;
	}

	public static function sprawdz_czy_zamowienie_zlozone()
	{
		$id_karty_z_koszyka = mod_koszyk::get_id_karty_z_koszyka(session::get_id());
		return db::get_one("SELECT 1 FROM zamowienia WHERE id_placowki=".session::get('id_placowki')." AND id_karty=$id_karty_z_koszyka AND status IN('złożone','w realizacji')");
	}
	
	public static function get_liczba_kart_z_zamowienia($id_zamowienia)
	{
		return db::get_one("SELECT COUNT(*) FROM zamowienia_legitymacje WHERE id_zamowienia=$id_zamowienia");
	}
	
	public static function get_typ_karty_z_zamowienia($id_zamowienia)
	{
		return db::get_one("SELECT zamowienia_legitymacje.id_karty FROM zamowienia JOIN zamowienia_legitymacje USING(id_zamowienia) WHERE id_zamowienia=$id_zamowienia LIMIT 1");
	}

	public static function get_zamowienie($id_zamowienia)
	{
		return db::get_row("SELECT zamowienia.*,sposoby_platnosci.nazwa as nazwa_sposobu_platnosci,
							sposoby_wysylki.nazwa as nazwa_sposobu_wysylki
							FROM zamowienia
							JOIN sposoby_platnosci USING(id_sposoby_platnosci)
							JOIN sposoby_wysylki USING(id_sposoby_wysylki)
						  	WHERE id_zamowienia=$id_zamowienia ORDER BY id_karty");
	}
	
	public static function get_cennik_kart_zamowienia($id_zamowienia)
	{
		//czy placówka ma dla tej karty cennik indywidualny
		$id_karty = mod_zamowienia::get_typ_karty_z_zamowienia($id_zamowienia);
		$id_cenniki = mod_karty::get_cennik_karty($id_karty);

		if(!$id_cenniki)
			$id_cenniki = mod_karty::get_domyslny_cennik_karty($id_karty);

		return $id_cenniki;
	}
	
	public static function get_cennik_przesylki_zamowienia($id_zamowienia,$id_sposoby_wysylki)
	{
		//czy placówka ma dla tej karty cennik indywidualny
		$id_karty = mod_zamowienia::get_typ_karty_z_zamowienia($id_zamowienia);
		$a_cennik = mod_karty::get_cenniki_karty_wysylka($id_karty,$id_sposoby_wysylki);

		$id_cenniki = $a_cennik ? $a_cennik[0]['id_cenniki'] : false;

		if(!$id_cenniki)
			$id_cenniki = mod_karty::get_domyslny_cennik_karty_wysylki($id_karty,$id_sposoby_wysylki);

		return $id_cenniki;
	}

	public static function aktualizuj_dane($id_zamowienia,$cena_za_karty,$cena_za_przesylke,$id_cenniki_kart,$id_cenniki_przesylki)
	{
		db::update('zamowienia','id_zamowienia='.$id_zamowienia,array('cena_legitymacji'=>$cena_za_karty,
																	  'cena_przesylki'=>$cena_za_przesylke,
																	  'id_cenniki_przesylka'=>$id_cenniki_przesylki,
																	  'id_cenniki_karty'=>$id_cenniki_kart
																	  ));
	}
	
	public static function get_zamowienia_prosto($id_placowki,$status)
	{
		return db::get_many("SELECT * FROM zamowienia WHERE id_placowki=$id_placowki AND status='$status'");
	}
	
	public static function get_zamowienia($status=false,$id_karty=false,$dokument=false,$platnosci=false,$id_sposoby_platnosci=false,$id_users=false,$id_pracownika_realizujacego=false,$miesiac=false,$rok=false,$id_zamowienia=false)
	{
		$sql_status = $status ? " AND zamowienia.status='$status'" : '';
		$sql_id_karty = $id_karty ? " AND id_karty=$id_karty" : '';
		
		$sql_dokument = '';
		if($dokument=='faktury')
			$sql_dokument = " AND zamowienia.id_dokumenty_sprzedazy<>0";
		elseif($dokument=='paragony')
			$sql_dokument = " AND zamowienia.id_dokumenty_sprzedazy=0";
		
		$sql_id_sposoby_platnosci = $id_sposoby_platnosci ? " AND id_sposoby_platnosci=$id_sposoby_platnosci" : '';
		
		$sql_id_users = $id_users ? " AND zamowienia.id_users IN ($id_users)" : false;

		$sql_id_zamowienia = $id_zamowienia ? " AND id_zamowienia=$id_zamowienia" : '';
		
		$sql_id_pracownika = $id_pracownika_realizujacego ? " AND id_pracownika_realizujacego=$id_pracownika_realizujacego" : '';
		$sql_miesiac = $miesiac ? "AND MONTH(data_realizacji)=$miesiac" : '';
		$sql_rok = $rok ? "AND YEAR(data_realizacji)=$rok" : '';
		
		$sql_id_placowki = session::who('admin') || session::who('mod') ? '' : ' AND zamowienia.id_placowki='.session::get('id_placowki');

		$a_zamowienia = db::get_many("SELECT zamowienia.*,placowki.nazwa, placowki.poczta,sposoby_platnosci.nazwa as nazwa_sposobu_platnosci,
							 sposoby_wysylki.nazwa as nazwa_sposobu_wysylki
							 FROM zamowienia 
							 JOIN placowki USING(id_placowki) 
							 LEFT JOIN sposoby_platnosci USING(id_sposoby_platnosci)
							 LEFT JOIN sposoby_wysylki USING(id_sposoby_wysylki)
							 WHERE 1=1 $sql_id_karty $sql_status $sql_dokument $sql_id_sposoby_platnosci $sql_id_users $sql_id_pracownika $sql_miesiac $sql_rok $sql_id_zamowienia $sql_id_placowki
							 ORDER BY id_zamowienia DESC");
				 //db::deb();
		 if($a_zamowienia)
		 {
		 	foreach($a_zamowienia as $index=>$a_zamowienie)
			{
				$a_zamowienia[$index]['nazwa_legitymacji'] = db::get_one("SELECT nazwa FROM karty WHERE id_karty=".$a_zamowienie['id_karty']);
				$a_zamowienia[$index]['liczba_kart'] = db::get_one("SELECT COUNT(*) FROM zamowienia_legitymacje WHERE id_zamowienia=".$a_zamowienie['id_zamowienia']);
				$a_zamowienia[$index]['wplaty'] = db::get_one("SELECT SUM(kwota_wplaty) FROM wplaty WHERE id_zamowienia=".$a_zamowienie['id_zamowienia']);

				$kwota_brutto = $a_zamowienie['cena_legitymacji']*$a_zamowienia[$index]['liczba_kart']*100+$a_zamowienie['cena_przesylki']*100;

				if($status!='zrealizowane')
				{
					$a_produkty = mod_zamowienia::zwroc_produkty($a_zamowienie['id_zamowienia']);
					$cena_produktow = mod_produkty::ustal_cene_produktow($a_produkty);
				}
				else
					$cena_produktow = self::get_cena_zamowionych_produktow($a_zamowienie['id_zamowienia']);
				
				$kwota_brutto = $kwota_brutto+$cena_produktow*100;
				$a_zamowienia[$index]['wartosc_zamowienia'] = $kwota_brutto/100;
				
				if($platnosci)
				{
					if($platnosci=='rozliczone' && $a_zamowienia[$index]['wplaty']*100!=$kwota_brutto || $platnosci=='nierozliczone' && $a_zamowienia[$index]['wplaty']*100==$kwota_brutto)
						unset($a_zamowienia[$index]);
				}
			}
		 }
						 
		 return $a_zamowienia;
	}
	
	public static function get_kontrahenci($status=false,$id_karty=false,$dokument=false,$platnosci='wszystkie',$id_sposoby_platnosci=false,$id_users=false,$id_pracownika_realizujacego=false,$miesiac=false,$rok=false)
	{
		$sql_status = $status ? " AND zamowienia.status='$status'" : '';
		$sql_id_karty = $id_karty ? " AND id_karty=$id_karty" : '';
		
		$sql_dokument = '';
		if($dokument=='faktury')
			$sql_dokument = " AND zamowienia.id_dokumenty_sprzedazy<>0";
		elseif($dokument=='paragony')
			$sql_dokument = " AND zamowienia.id_dokumenty_sprzedazy=0";
		
		$sql_id_sposoby_platnosci = $id_sposoby_platnosci ? " AND id_sposoby_platnosci=$id_sposoby_platnosci" : '';
		
		$sql_id_users = $id_users ? " AND zamowienia.id_users=$id_users" : false;
		
		$sql_id_pracownika = $id_pracownika_realizujacego ? " AND id_pracownika_realizujacego=$id_pracownika_realizujacego" : '';
		$sql_miesiac = $miesiac ? "AND MONTH(data_realizacji)=$miesiac" : '';
		$sql_rok = $rok ? "AND YEAR(data_realizacji)=$rok" : '';
//db::deb();
		$a_zamowienia = db::get_many("(SELECT zamowienia.*,placowki.nazwa, placowki.poczta,sposoby_platnosci.nazwa as nazwa_sposobu_platnosci,
							 (SELECT COUNT(*) FROM zamowienia_legitymacje zl WHERE zl.id_zamowienia=zamowienia.id_zamowienia) as liczba_kart
							 FROM zamowienia 
							 JOIN placowki USING(id_placowki) 
							 LEFT JOIN sposoby_platnosci USING(id_sposoby_platnosci)
							 WHERE 1=1 $sql_id_karty $sql_status $sql_dokument $sql_id_sposoby_platnosci $sql_id_users $sql_id_pracownika $sql_miesiac $sql_rok
							 AND zamowienia.id_dokumenty_sprzedazy<>0)
							 UNION
							 (SELECT zamowienia.*,placowki.nazwa, placowki.poczta,sposoby_platnosci.nazwa as nazwa_sposobu_platnosci,
							 (SELECT COUNT(*) FROM zamowienia_legitymacje zl WHERE zl.id_zamowienia=zamowienia.id_zamowienia) as liczba_kart
							 FROM zamowienia 
							 JOIN placowki USING(id_placowki) 
							 LEFT JOIN sposoby_platnosci USING(id_sposoby_platnosci)
							 WHERE 1=1 $sql_id_karty $sql_status $sql_dokument $sql_id_sposoby_platnosci $sql_id_users $sql_id_pracownika $sql_miesiac $sql_rok
							 AND zamowienia.id_dokumenty_sprzedazy=0) ORDER BY data_realizacji");
//exit;
		 if($a_zamowienia)
		 {
		 	foreach($a_zamowienia as $index=>$a_zamowienie)
			{
				$kwota_brutto = $a_zamowienie['cena_legitymacji']*$a_zamowienie['liczba_kart']*100+$a_zamowienie['cena_przesylki']*100;
				
				$a_produkty = mod_zamowienia::zwroc_produkty($a_zamowienie['id_zamowienia']);
				$cena_produktow = mod_produkty::ustal_cene_produktow($a_produkty);
				$kwota_brutto = $kwota_brutto+$cena_produktow*100;
				$a_zamowienia[$index]['wartosc_zamowienia'] = $kwota_brutto/100;
				
				if($platnosci!='wszystkie')
				{
					if($platnosci=='rozliczone' && $a_zamowienie['wplaty']!=$kwota_brutto || $platnosci=='nierozliczone' && $a_zamowienie['wplaty']==$kwota_brutto)
						unset($a_zamowienia[$index]);
				}
			}
		 }
						 
		 return $a_zamowienia;
	}

	public static function get_zamowione_legitymacje($id_zamowienia)
	{
		return db::get_many("SELECT * FROM zamowienia_legitymacje WHERE id_zamowienia=$id_zamowienia");
	}

	public static function get_ilosc_zdjec(&$a_zamowienia)
	{
		if($a_zamowienia)
		{
			foreach($a_zamowienia as $index=>$a_zamowienie)
			{
				$a_legitymacje = self::get_zamowione_legitymacje($a_zamowienie['id_zamowienia']);
				$a_zamowienia[$index]['liczba_zdjec'] = 0;
				$a_zamowienia[$index]['liczba_podpisow'] = 0;
				
				if($a_legitymacje)
				{
					foreach($a_legitymacje as $a_legitymacja)
					{
						$a_zdjecia = mod_legitymacje::get_photos($a_legitymacja['id_legitymacje']);
						$a_zamowienia[$index]['liczba_zdjec'] += $a_zdjecia['zdjecie'] ? 1 : 0;
						$a_zamowienia[$index]['liczba_podpisow'] += $a_zdjecia['podpis'] ? 1 : 0;
					}
				}
			}
		}
	}
	
	public static function zmien_status_zamowienia($id_zamowienia,$status)
	{
		if(!in_array($status,array('złożone','w realizacji','w druku','zrealizowane','wydrukowane','anulowane')))
			return false;
		
		db::update('zamowienia',"id_zamowienia=$id_zamowienia",array('status'=>$status));
	}
	
	public static function tworz_numer_faktury($id_dokumenty_sprzedazy)
	{
		$typ = $id_dokumenty_sprzedazy ? '<>' : '=';
		$rok = date('Y');
		$miesiac = date('m');
		$numer = db::get_one("SELECT COUNT(*)+1 FROM zamowienia WHERE YEAR(data_realizacji)=$rok AND MONTH(data_realizacji)=$miesiac AND id_dokumenty_sprzedazy{$typ}0");
		
		if($id_dokumenty_sprzedazy==0)
			$numer .= '/paragon';
		
		$numer .= "/L/$miesiac/$rok";

		return $numer;
	}
	
	public static function realizuj_zamowienie($a_zamowienie,$czy_korekta=false)
	{
		if(!self::sprawdz_zamowienie($a_zamowienie))
			return false;

		$a_dane = array('status'=>$czy_korekta ? $a_zamowienie['status'] : 'zrealizowane',
					   'id_sposoby_platnosci'=>$a_zamowienie['id_sposoby_platnosci'],
					   'id_sposoby_wysylki'=>$a_zamowienie['id_sposoby_wysylki'],
					   'nabywca_nazwa'=>$a_zamowienie['nabywca_nazwa'],
					   'nabywca_adres'=>$a_zamowienie['nabywca_adres'],
					   'nabywca_kod_pocztowy'=>$a_zamowienie['nabywca_kod_pocztowy'],
					   'nabywca_poczta'=>$a_zamowienie['nabywca_poczta'],
					   'nabywca_nip'=>$a_zamowienie['nabywca_nip'],
					   'wysylka_nazwa'=>$a_zamowienie['wysylka_nazwa'],
					   'wysylka_adres'=>$a_zamowienie['wysylka_adres'],
					   'wysylka_kod_pocztowy'=>$a_zamowienie['wysylka_kod_pocztowy'],
					   'wysylka_poczta'=>$a_zamowienie['wysylka_poczta'],
					   'platnik_nazwa'=>$a_zamowienie['platnik_nazwa'],
					   'platnik_adres'=>$a_zamowienie['platnik_adres'],
					   'platnik_kod_pocztowy'=>$a_zamowienie['platnik_kod_pocztowy'],
					   'platnik_poczta'=>$a_zamowienie['platnik_poczta'],
					   'uwagi_dla_kuriera'=>$a_zamowienie['uwagi_dla_kuriera'],
					   'uwagi'=>$a_zamowienie['uwagi'],
					   'data_realizacji'=>'NOW()',
					   'termin_platnosci_faktury'=>$a_zamowienie['termin_platnosci_faktury'],
					   'uwagi_faktura'=>$a_zamowienie['uwagi_faktura'],
					   'id_dokumenty_sprzedazy'=>$a_zamowienie['id_dokumenty_sprzedazy']);
					   
	    if($czy_korekta)
			$a_dane = array_merge($a_dane, array('placowka_nazwa'=>$a_zamowienie['placowka_nazwa'],
												   'placowka_regon'=>$a_zamowienie['placowka_regon'],
												   'placowka_adres'=>$a_zamowienie['placowka_adres'],
												   'placowka_kod_pocztowy'=>$a_zamowienie['placowka_kod_pocztowy'],
												   'placowka_poczta'=>$a_zamowienie['placowka_poczta'],
												   'placowka_dyrektor'=>$a_zamowienie['placowka_dyrektor'],
												   'cena_legitymacji'=>str_replace(',','.',$a_zamowienie['cena_legitymacji']),
												   'cena_przesylki'=>str_replace(',','.',$a_zamowienie['cena_przesylki']),
												   'status'=>$a_zamowienie['status'],
												   'data_realizacji'=>$a_zamowienie['data_realizacji']
												   ));
		else
		{
			$a_zam = self::get_zamowienie($a_zamowienie['id_zamowienia']);
			
			if(!empty($a_zam['numer_faktury']))
				return app::err('To zamówienie ma już wystawioną fakturę. Możesz dokonać korekty');
			
			$a_dane = array_merge($a_dane, array('numer_faktury'=> self::tworz_numer_faktury($a_zamowienie['id_dokumenty_sprzedazy'])));
		}

		db::update('zamowienia',"id_zamowienia={$a_zamowienie['id_zamowienia']}",$a_dane);

		return app::ok('Zamówienie przeniesiono do zrealizowanych');
	}

	public static function ustal_cene_produktow(&$a_produkty)
	{
		$cena_laczna = 0;
		
		if($a_produkty)
		{
			foreach($a_produkty as $index=>$a_produkt)
			{
				$a_produkty[$index]['cena'] = mod_produkty::get_cena_produktu($a_produkt['ilosc'],$a_produkt['id_cenniki']);
				$cena_laczna += $a_produkty[$index]['cena'];
			}
		}
		
		return $cena_laczna;
	}

	public static function generuj_fakture($id_zamowienia)
	{
		$a_zamowienie = mod_zamowienia::get_zamowienie($id_zamowienia);
		$id_typy_katry = mod_zamowienia::get_typ_karty_z_zamowienia($id_zamowienia);
		
		$newdate = date('Y-m-d',strtotime ("+{$a_zamowienie['termin_platnosci_faktury']} day" , strtotime ( substr($a_zamowienie['data_realizacji'],0,10) ) )) ;
		view::add('data_platnosci',$newdate);
		view::add('a_zamowienie',$a_zamowienie);
		view::add('a_karta',db::get_by_id('karty',$id_typy_katry));
		view::add('liczba_kart',self::get_liczba_kart_z_zamowienia($id_zamowienia));
		view::add('dane_sprzedawcy',db::get_one("SELECT `value` FROM settings WHERE id_settings=17"));
		view::add('a_zamowione_legitymacje', self::get_zamowione_legitymacje($id_zamowienia));
		
		$a_produkty = self::zwroc_produkty($id_zamowienia);
	    $cena_produktow = mod_produkty::ustal_cene_produktow($a_produkty);

		view::add('cena_produktow',$cena_produktow);
		view::add('a_produkty',$a_produkty);
		//view::add('okres_zatrudnienia', )
		$html = view::display('zamowienia/faktura.tpl',true,true);

		$url = "images/faktury/".str_replace('/', '-', $a_zamowienie['numer_faktury']);
		file_put_contents($url.".html", $html);
 		system("/usr/local/bin/wkhtmltopdf {$url}.html {$url}.pdf");
		unlink("{$url}.html");
		return "{$url}.pdf";
	}
	
	public static function sprawdz_czy_produkt_byl_zamawiany($id_produkty)
	{
		return db::get_one("SELECT 1 FROM zamowienia_produkty WHERE id_produkty=$id_produkty");
	}
	
	public static function zwroc_produkty($id_zamowienia)
	{
		return db::get_many("SELECT * FROM zamowienia_produkty JOIN produkty USING(id_produkty) WHERE id_zamowienia=$id_zamowienia");
	}
	
	public static function get_cena_zamowionych_produktow($id_zamowienia)
	{
		return db::get_one("SELECT SUM(cena*ilosc*100)/100 FROM zamowienia_produkty WHERE id_zamowienia=$id_zamowienia");
	}
	
	public static function get_historia_zamowien_legitymacji($id_legitymacji)
	{
		return db::get_many("SELECT data_zlozenia, status FROM zamowienia JOIN zamowienia_legitymacje USING(id_zamowienia) WHERE id_legitymacje=$id_legitymacji");
	}
}

?>