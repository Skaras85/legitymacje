<?php

class mod_rozliczenia extends db{
	
	public static function dodaj_wplate($data_wplaty,$kwota_wplaty,$sposob_wplaty,$id_zamowienia)
	{
		db::insert('wplaty',array('data_wplaty'=>$data_wplaty,
								  'kwota_wplaty'=>str_replace(',','.',$kwota_wplaty),
								  'sposob_wplaty'=>$sposob_wplaty,
								  'id_zamowienia'=>$id_zamowienia,
								  'id_users'=>session::is_logged() ? session::get_id() : 0));
	}
	
	public static function get_wplaty($id_zamowienia)
	{
		return db::get_many("SELECT wplaty.*,users.imie,users.nazwisko FROM wplaty JOIN users USING(id_users) WHERE id_zamowienia=$id_zamowienia");
	}
	
	public static function make_id_zrodla($id_dokumenty_sprzedazy)
	{
		if($id_dokumenty_sprzedazy!=0)
		{
			$id_zrodla = "00000000-0000-0000-0000-";
			$dlugosc = 12-strlen($id_dokumenty_sprzedazy);
			
			for($i=1; $i<=$dlugosc; $i++)
				$id_zrodla .= '0';
			
			$id_zrodla .= $id_dokumenty_sprzedazy;
		}
		else
			$id_zrodla = "00000000-0000-0000-0000-000000000001";
		
		return $id_zrodla;
	}

	public static function generuj_xml($a_kontrahenci,$a_zamowienia)
	{
		$xml = '<?xml version="1.0" encoding="windows-1250"?>'."\r\n";
				$xml .= '<ROOT xmlns="http://www.cdn.com.pl/optima/offline">'."\r\n";
				$xml .= "  <KONTRAHENCI>\r\n
						    <WERSJA>2.00</WERSJA>\r\n
						    <BAZA_ZRD_ID />\r\n
						    <BAZA_DOC_ID />\r\n";
							
				foreach($a_kontrahenci as $a_kontrahent)
				{
					$xml .= "<KONTRAHENT>\r\n
								<ID_ZRODLA>";	
								
						$xml .= self::make_id_zrodla($a_kontrahent['id_dokumenty_sprzedazy']);
								
						$xml .= "</ID_ZRODLA>\r\n
								<FORMA_PLATNOSCI>{$a_kontrahent['nazwa_sposobu_platnosci']}</FORMA_PLATNOSCI>\r\n
								<FORMA_PLATNOSCI_ID>{$a_kontrahent['id_sposoby_platnosci']}</FORMA_PLATNOSCI_ID>\r\n
							 	<INDYWIDUALNY_TERMIN>Tak</INDYWIDUALNY_TERMIN>\r\n
							 	<TERMIN>{$a_kontrahent['termin_platnosci_faktury']}</TERMIN>\r\n
							 	<ADRESY>\r\n
		        					<ADRES>\r\n
		        						<STATUS>aktualny</STATUS>\r\n
		        						<NAZWA1>{$a_kontrahent['nabywca_nazwa']}</NAZWA1>\r\n
		        						<KRAJ>Polska</KRAJ>\r\n
		        						<ULICA>{$a_kontrahent['nabywca_adres']}</ULICA>\r\n
		        						<KOD_POCZTOWY>{$a_kontrahent['nabywca_kod_pocztowy']}</KOD_POCZTOWY>\r\n
		        						<POCZTA>{$a_kontrahent['nabywca_poczta']}</POCZTA>\r\n
		        						<NIP>{$a_kontrahent['nabywca_nip']}</NIP>\r\n
		    						</ADRES>\r\n
	      						</ADRESY>\r\n
      						</KONTRAHENT>\r\n";
				}
				
				$xml .= "</KONTRAHENCI>\r\n";
				$xml .= "<REJESTRY_SPRZEDAZY_VAT>\r\n
    						 <WERSJA>2.00</WERSJA>\r\n
    						 <BAZA_ZRD_ID />\r\n
    						 <BAZA_DOC_ID />\r\n";
				
				foreach($a_zamowienia as $a_zamowienie)
				{
					$date = strtotime("+{$a_zamowienie['termin_platnosci_faktury']} day");
					$termin_platnosci_faktury = date('Y-m-d', $date);
					
					$liczba_kart = mod_zamowienia::get_liczba_kart_z_zamowienia($a_zamowienie['id_zamowienia']);
					$cena_kart_netto = round(($a_zamowienie['cena_legitymacji']*100*$liczba_kart)/123,2);
					
					$a_produkty = mod_zamowienia::zwroc_produkty($a_zamowienie['id_zamowienia']);
					
					$cena_przesylki = round($a_zamowienie['cena_przesylki']/1.23,2);

					$xml .= "<REJESTR_SPRZEDAZY_VAT>\r\n
								 <ID_ZRODLA>".self::make_id_zrodla($a_zamowienie['id_dokumenty_sprzedazy'])."</ID_ZRODLA>\r\n
								 <REJESTR>SPT</REJESTR>\r\n
								 <DATA_WYSTAWIENIA>".substr($a_zamowienie['data_realizacji'],0,10)."</DATA_WYSTAWIENIA>\r\n
								 <DATA_SPRZEDAZY>".substr($a_zamowienie['data_zlozenia'],0,10)."</DATA_SPRZEDAZY>\r\n
								 <TERMIN>$termin_platnosci_faktury</TERMIN>\r\n
								 <NUMER>{$a_zamowienie['numer_faktury']}</NUMER>\r\n
								 <TYP_PODMIOTU>kontrahent</TYP_PODMIOTU>\r\n
								 <PODMIOT_ID>";
								 
								 if($a_zamowienie['id_dokumenty_sprzedazy']!=0)
									$xml .= self::make_id_zrodla($a_zamowienie['id_placowki']);
								 else
									$xml .= "00000000-0000-0000-0000-000000000000";
								 
								 $xml .= "</PODMIOT_ID>\r\n
								 <NAZWA1>";
								 
								 if($a_zamowienie['id_dokumenty_sprzedazy']!=0)
								 	$xml .= $a_zamowienie['nabywca_nazwa'];
								 else
								 	$xml .= $a_zamowienie['placowka_nazwa'];
								 
						 $xml .= "</NAZWA1>\r\n
	    						 <KRAJ>Polska</KRAJ>\r\n
	    						 <ULICA>";
	    						 
								 if($a_zamowienie['id_dokumenty_sprzedazy']!=0)
								 	$xml .= $a_zamowienie['nabywca_adres'];
								 else
								 	$xml .= $a_zamowienie['placowka_adres'];
	    						 
	    						 $xml .= "</ULICA>\r\n
	    						 <KOD_POCZTOWY>";
	    						 
	    						 if($a_zamowienie['id_dokumenty_sprzedazy']!=0)
								 	$xml .= $a_zamowienie['nabywca_kod_pocztowy'];
								 else
								 	$xml .= $a_zamowienie['placowka_kod_pocztowy'];
	    						 
						 $xml .= "</KOD_POCZTOWY>\r\n
	    						 <POCZTA>";
	    						 
								 if($a_zamowienie['id_dokumenty_sprzedazy']!=0)
								 	$xml .= $a_zamowienie['nabywca_poczta'];
								 else
								 	$xml .= $a_zamowienie['placowka_poczta'];
								 
						 $xml .= "</POCZTA>\r\n
	    						 <NIP>{$a_zamowienie['nabywca_nip']}</NIP>\r\n
	    						 <FORMA_PLATNOSCI>{$a_zamowienie['nazwa_sposobu_platnosci']}</FORMA_PLATNOSCI>\r\n
	      						 <FORMA_PLATNOSCI_ID>{$a_zamowienie['id_sposoby_platnosci']}</FORMA_PLATNOSCI_ID>\r\n
	  						 	 <POZYCJE>\r\n
	  						 	 	<POZYCJA>\r\n
	  						 	 		<STAWKA_VAT>23.00</STAWKA_VAT>\r\n
	  						 	 		<STATUS_VAT>opodatkowana</STATUS_VAT>\r\n
	  						 	 		<NETTO>$cena_kart_netto</NETTO>\r\n
						 	 		</POZYCJA>\r\n
						 	 		<POZYCJA>\r\n
	  						 	 		<STAWKA_VAT>23.00</STAWKA_VAT>\r\n
	  						 	 		<STATUS_VAT>opodatkowana</STATUS_VAT>\r\n
	  						 	 		<NETTO>$cena_przesylki</NETTO>\r\n
						 	 		</POZYCJA>\r\n";
					 	 		
		 	 		 if($a_produkty)
		 	 		 {
		 	 		 	foreach($a_produkty as $a_produkt)
		 	 		 	{
		 	 		 		$xml .= "<POZYCJA>\r\n
	  						 	 		<STAWKA_VAT>23.00</STAWKA_VAT>\r\n
	  						 	 		<STATUS_VAT>opodatkowana</STATUS_VAT>\r\n
	  						 	 		<NETTO>{$a_produkt['cena']}</NETTO>\r\n
						 	 		</POZYCJA>\r\n";
		 	 		 	}
		 	 		 }

						$xml .= "</POZYCJE>\r\n
								</REJESTR_SPRZEDAZY_VAT>\r\n";
				}
				
				$xml .= "</REJESTRY_SPRZEDAZY_VAT>\r\n
						</ROOT>";
				
				file_put_contents('faktury.xml', $xml);
	}
	
	public static function generuj_csv($a_kontrahenci)
	{
		$csv = '';
		foreach($a_kontrahenci as $index=>$a_zamowienie)
		{
			$csv .=  ($index+1).';';
			$csv .= substr($a_zamowienie['data_realizacji'], 0, 10).';';
			$csv .= substr($a_zamowienie['data_realizacji'], 0, 10).';';
			$csv .= $a_zamowienie['numer_faktury'].';';
			$csv .= $a_zamowienie['nabywca_nazwa'].';';
			$csv .= $a_zamowienie['nabywca_adres'].','.$a_zamowienie['nabywca_kod_pocztowy'].','.$a_zamowienie['nabywca_poczta'].';';
			
			if($a_zamowienie['id_dokumenty_sprzedazy'])
				$csv .= str_replace('-','',str_replace(' ','',$a_zamowienie['nabywca_nip'])).';';
			else
				$csv .= ';';

			$wartosc_netto = round(($a_zamowienie['wartosc_zamowienia']*100-$a_zamowienie['wartosc_zamowienia']*100/1.23)/100,2);
			
			$csv .= str_replace('.',',',round(($a_zamowienie['wartosc_zamowienia']*100-$wartosc_netto*100)/100,2)).';';
			
			$csv .= str_replace('.',',',$wartosc_netto) . ";\r\n";
		}
		
		file_put_contents('faktury.csv', $csv);
	}
	
	public static function generuj_csv2($a_kontrahenci)
	{
		$csv = '';
		foreach($a_kontrahenci as $index=>$a_zamowienie)
		{
			$csv .= $a_zamowienie['numer_faktury'].';';
			
			$csv .= $a_zamowienie['id_dokumenty_sprzedazy'] ? 'FS;' : 'PR;';
			
			$csv .= substr($a_zamowienie['data_realizacji'], 0, 10).';';
			$csv .= substr($a_zamowienie['data_realizacji'], 0, 10).';';
			
			$csv .= $a_zamowienie['nazwa_sposobu_platnosci'].';';
			
			$csv .= ';';
			
			$csv .= date('Y-m-d', strtotime($a_zamowienie['data_realizacji']." +{$a_zamowienie['termin_platnosci_faktury']} days")).';';
			
			$csv .= ';;';
			
			$csv .= $a_zamowienie['nabywca_nazwa'].';';
			$csv .= $a_zamowienie['nabywca_kod_pocztowy'].';';
			$csv .= $a_zamowienie['nabywca_poczta'].';';
			$csv .= $a_zamowienie['nabywca_adres'].';';
			if($a_zamowienie['id_dokumenty_sprzedazy'])
				$csv .= str_replace('-','',str_replace(' ','',$a_zamowienie['nabywca_nip'])).';';
			else
				$csv .= ';';
			
			$kwota_netto = round($a_zamowienie['wartosc_zamowienia']/1.23,2);
			$vat = round((($a_zamowienie['wartosc_zamowienia']*100-$kwota_netto*100)/100),2);
			
			$kwota_netto = str_replace('.',',',$kwota_netto);
			$vat = str_replace('.', ',', $vat);
			
			$csv .= $kwota_netto.';';
			$csv .= $vat.';';
			
			$csv .= $kwota_netto.';';
			$csv .= $vat.";\r\n";
		
			

		}
		
		file_put_contents('faktury2.csv', $csv);
	}

}

?>