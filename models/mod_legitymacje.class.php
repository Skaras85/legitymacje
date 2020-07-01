<?php

class mod_legitymacje extends db{
	
	public static function get_dostepne_karty($id_placowki)
	{
		$a_karty_uzywane = db::get_many("SELECT karty_placowki* FROM karty_placowki WHERE id_placowki=$id_placowki");
		
		if($a_karty_uzywane)
		{
			$ids = '';
			foreach($a_karty_uzywane as $index=>$a_karta)
			{
				$ids .= $a_karta['id_karty'].',';
			}

			$ids = trim($ids,',');
			
			return db::get_many("SELECT * FROM karty WHERE id_karty NOT IN($ids)");
		}
		else
			return db::get_many("SELECT * FROM karty");
	}
	
	public static function sprawdz_dostepnosc_karty($id_karty,$id_placowki)
	{
		return !db::get_one("SELECT 1 FROM karty_placowki WHERE id_placowki=$id_placowki AND id_karty IN($id_karty)");
	}
	
	public static function przypisz_karte_do_placowki($id_karty,$id_placowki,$status)
	{
		db::insert('karty_placowki',array('id_placowki'=>$id_placowki,'id_karty'=>$id_karty,'status'=>$status));
		app::ok('Legitymacja dodana do placówki');
	}
	
	public static function get_karty_placowki($id_placowki)
	{
		return db::get_many("SELECT karty.id_karty, karty.nazwa, karty.img, karty_placowki.status, appetizer FROM karty JOIN karty_placowki USING(id_karty) LEFT JOIN sites USING(id_sites) WHERE karty_placowki.id_placowki=$id_placowki");
	}
	
	public static function get_karty_niedodane($id_placowki,$a_karty_placowki)
	{
		$ids = '';
		foreach($a_karty_placowki as $a_karta)
		{
			$ids .= $a_karta['id_karty'].',';
		}
		
		$ids = trim($ids,',');
		$sql_ids = !empty($ids) ? "  WHERE id_karty NOT IN($ids)" : '';

		return db::get_many("SELECT id_karty, karty.id_sites, karty.nazwa, karty.img, appetizer, sites.sludge FROM karty LEFT JOIN sites USING(id_sites) $sql_ids");
	}
	
	public static function get_kolumna_pracodawcy($id_karty)
	{
		$id_karty_pola_typy = $id_karty==1 ? 17 : 18;
		return db::get_one("SELECT kolumna FROM karty_pola WHERE id_karty=$id_karty AND id_karty_pola_typy=$id_karty_pola_typy");
	}
	
	public static function zapisz_dane_legitymacji($a_dane)
	{
		$kolumna_pracodawcy = self::get_kolumna_pracodawcy($a_dane['id_karty']);

		$a_wsad = array('id_karty'=>$a_dane['id_karty'],
					    'id_placowki'=>session::get('id_placowki'),
					    'kol'.$kolumna_pracodawcy=>$a_dane['id_pracodawcy']);

		foreach($a_dane['a_dane'] as $id_karty_pola=>$wartosc)
		{
			$a_pole = mod_karty::get_pole_karty($id_karty_pola);

			if($a_pole['kolumna'] && (!empty($wartosc) || !empty($a_dane['id_legitymacje'])))
				$a_wsad = array_merge($a_wsad,array('kol'.$a_pole['kolumna']=>strtoupper($wartosc)));
		}

		if(empty($a_dane['id_legitymacje']))
			$id_legitymacje = db::insert('legitymacje',$a_wsad);
		else
			db::update('legitymacje','id_legitymacje='.$a_dane['id_legitymacje'],$a_wsad);
		/*
		if(!empty($a_dane['id_legitymacje']))
		{
			$a_photos = self::get_photos($a_dane['id_legitymacje']);
			
			if($a_photos['zdjecie'])
				unlink($a_photos['zdjecie']);
			if($a_photos['podpis'])
				unlink($a_photos['podpis']);
		}
		*/
		return !empty($a_dane['id_legitymacje']) ? $a_dane['id_legitymacje'] : $id_legitymacje;
	}
	/*
	public static function get_dane_legitymacji($id_karty,$id_placowki,$id_zamowienia=false)
	{
		//return db::get_many("SELECT legitymacje.*,zl.id_legitymacje as czy_bylo_zamowienie FROM legitymacje LEFT JOIN zamowienia_legitymacje zl USING(id_legitymacje) WHERE legitymacje.id_karty=$id_karty AND legitymacje.id_placowki=$id_placowki");
		$sql_id_zamowienia = $id_zamowienia ? " AND id_zamowienia=$id_zamowienia" : '';
		$sql_zamowienie = "(SELECT 1 FROM zamowienia_legitymacje zl WHERE legitymacje.id_legitymacje=zl.id_legitymacje $sql_id_zamowienia LIMIT 1)";
		$sql_czy_bylo = $id_zamowienia ? "AND $sql_zamowienie IS NOT NULL" : '';
		return db::get_many("SELECT legitymacje.*,$sql_zamowienie as czy_bylo_zamowienie FROM legitymacje WHERE id_karty=$id_karty AND id_placowki=$id_placowki $sql_czy_bylo");
	
	}
	*/
	public static function get_dane_legitymacji($id_karty,$id_placowki,$id_zamowienia=false)
	{//db::deb();
		$sql_tabela = $id_zamowienia ? 'zamowienia_legitymacje' : "legitymacje";
		$sql_id_zamowienia = $id_zamowienia ? " AND id_zamowienia=$id_zamowienia" : '';
		$sql_zamowienie = $id_zamowienia ? '' : ",(SELECT 1 FROM zamowienia_legitymacje zl WHERE legitymacje.id_legitymacje=zl.id_legitymacje $sql_id_zamowienia LIMIT 1) as czy_bylo_zamowienie";
		//$sql_czy_bylo = $sql_zamowienie ? "AND $sql_zamowienie IS NOT NULL" : " AND id_zamowienia=$id_zamowienia";
		return db::get_many("SELECT $sql_tabela.* $sql_zamowienie FROM $sql_tabela WHERE id_karty=$id_karty AND id_placowki=$id_placowki $sql_id_zamowienia");
	
	}
	
	public static function get_zamowione_legitymacje_druk($a_osoby, $typ_druku)
	{
		$html = '';
		
		if(in_array($typ_druku, array('awers bez zdjęcia','zdjęcie','awers i zdjęcie')))
			$strona = 'awers';
		elseif($typ_druku=='awers i rewers bez zdjęcia' || $typ_druku=='awers, rewers i zdjęcie')
			$strona = 'awers';
		elseif($typ_druku=='rewers bez zdjęcia')
			$strona = 'rewers';
		
		foreach($a_osoby as $a_osoba)
		{
			$licznik = $typ_druku=='awers i rewers bez zdjęcia' || $typ_druku=='awers, rewers i zdjęcie' ? 2 : 1;
			for($i=1; $i<=$licznik; $i++)
			{
				$strona_biezaca = $strona;
				
				if($i==2)
					$strona_biezaca = 'rewers';
				
				$a_pola = mod_karty::get_pola_karty($_POST['id_karty'], false, $strona_biezaca);
				$html .= '<div class="legitymacja_druk">';
	
				foreach($a_pola as $a_pole)
				{
					$font_family = !empty($a_pole['font_family']) ? $a_pole['font_family'] : 'Arial, Helvetica, sans-serif';
					$style = " style='top: {$a_pole['y']}px; left: {$a_pole['x']}px; font-family: $font_family; font-size: {$a_pole['font_size']}px'";
	
					if(in_array($a_pole['typ'],array('zdjęcie','podpis','zdjęcie i podpis','zdjęcie i podpis (złożony)','zdjęcie (złożony)','podpis (złożony)')))
					{
						$a_photos = self::get_photos($a_osoba['id_legitymacje']);
	
						if($a_photos['zdjecie'] && ($a_pole['typ']!='zdjęcie' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)'))
						{
							if($typ_druku=='zdjęcie' || $typ_druku=='awers i zdjęcie' || $typ_druku=='awers, rewers i zdjęcie')
								$html .= "<img src='".app::base_url()."img.php?id_legitymacje={$a_osoba['id_legitymacje']}&typ=zdjecie' class='legitymacja_druk_zdjecie' $style>";
						}
						if($a_photos['podpis'] && ($a_pole['typ']=='podpis' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)'))
						{
							if($typ_druku!='zdjęcie')
								$html .= "<img src='".app::base_url()."img.php?id_legitymacje={$a_osoba['id_legitymacje']}&typ=podpis' class='legitymacja_druk_podpis' $style>";
						}
					}
					elseif($typ_druku!='zdjęcie' || $typ_druku=='awers i zdjęcie')
					{
						$html .= "<div class='legitymcje_druk_pole' $style>";
						
						if($a_pole['typ']=='pracodawca' || $a_pole['typ']=='szkoła')
						{
							$a_pracodawca = mod_placowki::get_pracodawca($a_osoba['kol'.$a_pole['kolumna']]);
							$html .= $a_pracodawca['dane1'];
						}
						elseif($a_pole['typ']=='okres zatrudnienia (prosty)' || $a_pole['typ']=='okres zatrudnienia (złożony)')
						{
							if(empty($a_osoba['kol'.$a_pole['kolumna']]))
							{
								if(!$czy_koszyk && !$czy_zamowienie)
									$html .= 'na czas nieokreślony';
								else
									$html .= mod_panel::get_parametr('data_waznosci_legitymacji');
							}
							else
							{
								$html .= date('dmY',strtotime(substr($a_osoba['kol'.$a_pole['kolumna']],0,10)));
							}
						}
						elseif($a_pole['typ']!='uwagi')
							$html .= $a_osoba['kol'.$a_pole['kolumna']];
						
						$html .= '</div>';
					}
				}
				$html .= '</div>';
			}
		}

		return $html;
	}
	
	public static function zwroc_tabele_z_danymi_druk($a_zamowienia)
	{
		$html = '<table class="dataTables"><thead><tr>
							<th>L.p.</th>
							<th>Nr zamówienia</th>
							<th>Data zamówienia</th>
							<th>ID placówki</th>
							<th>ID osoby</th>
							<th>Nazwisko</th>
							<th>Imię</th>
							<th><input type="checkbox" id="zaznaczDane" class="checkAll"><label for="zaznaczDane" class="inline">Zaznacz</label></th>
						</thead></tr><tbody>';
		
		if($a_zamowienia)
		{
			foreach($a_zamowienia as $a_zamowienie)
			{
				$a_pola_karty = mod_karty::get_pola_karty($a_zamowienie['id_karty'], true);
				$a_osoby = mod_zamowienia::get_zamowione_legitymacje($a_zamowienie['id_zamowienia']);

				if($a_osoby)
				{
					foreach($a_osoby as $index=>$a_osoba)
					{
						$html .= '<tr><td>'.($index+1).'</td>';
						$html .= '<td>'.$a_zamowienie['numer_zamowienia'].'</td>';
						$html .= '<td>'.$a_zamowienie['data_zlozenia'].'</td>';
						$html .= '<td>'.$a_zamowienie['id_placowki'].'</td>';
						$html .= '<td>'.$a_osoba['id_legitymacje'].'</td>';
						
						if($a_pola_karty)
						{
							foreach($a_pola_karty as $a_pole)
							{
								if($a_pole['typ']=='nazwisko 1')
									$html .= '<td>'.$a_osoba['kol'.$a_pole['kolumna']].'</td>';
									
								if($a_pole['typ']=='imię 1')
									$html .= '<td>'.$a_osoba['kol'.$a_pole['kolumna']].'</td>';
							}	
						}

						$html .= '<td class="center"><input type="checkbox" name="a_legitymacje['.$a_osoba['id_legitymacje'].']" class="checkAllTarget user" data-check-id="zaznaczDane"></td>';
						$html .= '</tr>';
					}
				}
			}
		}
		
		$html .= '</tbody></table>';
		
		return $html;
	}
	
	public static function zwroc_tabele_z_danymi($a_pola,$a_osoby,$czy_koszyk=false,$id_zamowienia=false,$id_karty,$czy_szukane=false)
	{
		$uniqid = uniqid();
		if(!$czy_koszyk && !$czy_szukane)
			$html = '<table class="dataTables listaOsob"  id="tabela_dane_karty">';
		elseif($czy_koszyk || $czy_szukane)
			$html = '<table class="koszyk">';
		
		if($a_pola)
		{
			$html .= '<thead><tr>';
			
			if(!$czy_koszyk)
				$html .= '<th>ID</th>';
				
			if($czy_szukane)
				$html .= '<th>Rodzaj legitymacji</th><th>ID placówki</th>';
			
			foreach($a_pola as $a_pole)
			{
				if(!$czy_koszyk || $czy_koszyk && $a_pole['typ']!='okres zatrudnienia (prosty)' && $a_pole['typ']!='okres zatrudnienia (złożony)')
					$html .= '<th>'.$a_pole['nazwa'].'</th>';
				elseif($czy_koszyk && ($a_pole['typ']=='okres zatrudnienia (prosty)' || $a_pole['typ']=='okres zatrudnienia (złożony)'))
					$html .= '<th>data ważności</th>';
			}
			
			if($id_zamowienia && session::get('czy_zdalny'))
				$html .= '<th>Kod HEX</th>';

			if(!$czy_koszyk && !$czy_szukane)
				$html .= '<th>Podgląd</th>';
			
			if(!$czy_koszyk && !$id_zamowienia && !$czy_szukane)
				$html .= '<th>Edytuj</th><th>Usuń</th>';
			
			if(!$id_zamowienia && !$czy_szukane)
				$html .= '<th><input type="checkbox" id="zaznaczDane_'.$uniqid.'" class="checkAll"><label for="zaznaczDane" class="inline">Zaznacz</label></th>';
			
			$html .= '</tr></thead><tbody>';
			
			if($a_osoby)
			{
				foreach($a_osoby as $a_osoba)
				{
					$html .= '<tr data-id-osoby="'.$a_osoba['id_legitymacje'].'">';
					if(!$czy_koszyk)
					{
						$html .= '<td>'.$a_osoba['id_legitymacje'];
						
						if($a_osoba['czy_bylo_zamowienie'])
							$html .= '<img src="'.app::base_url().'images/site/cart-icon-small.png" class="pokazHistorieZamowienLegitymacji pointer">';
						
						$html .= '</td>';
					}
					
					if($czy_szukane)
					{
						$html .= '<td>'.$a_osoba['nazwa_legitymacji'].'</td>';
						$html .= '<td>'.$a_osoba['id_placowki'].'</td>';
					}
					
					foreach($a_pola as $a_pole)
					{
						if(in_array($a_pole['typ'],array('zdjęcie','podpis','zdjęcie i podpis','zdjęcie i podpis (złożony)','zdjęcie (złożony)','podpis (złożony)')))
						{
							$a_photos = self::get_photos($a_osoba['id_legitymacje']);

							$html .= '<td class="center">';
								if($a_photos['zdjecie'] && ($a_pole['typ']!='zdjęcie' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)'))
								{
									$html .= "<img src='".app::base_url()."images/site/uploaded-photo-icon.png' data-hint='".app::base_url()."img.php?id_legitymacje={$a_osoba['id_legitymacje']}&typ=zdjecie' class='pokazZdjecie'>";
									
									if(!$czy_szukane)
										$html .= "<img src='".app::base_url()."images/site/delete.png' class='deleteImage pointer' data-type='zdjecie' data-id-osoby='".$a_osoba['id_legitymacje']."' title='Usuń'>";
								}
								if($a_photos['podpis'] && ($a_pole['typ']=='podpis' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)'))
								{
									$html .= "<img src='".app::base_url()."images/site/uploaded-sign-icon.png' data-hint='".app::base_url()."img.php?id_legitymacje={$a_osoba['id_legitymacje']}&typ=podpis' class='pokazZdjecie'>";
									
									if(!$czy_szukane)
										$html .= "<img src='".app::base_url()."images/site/delete.png' class='deleteImage pointer' data-type='podpis' data-id-osoby='".$a_osoba['id_legitymacje']."' title='Usuń'>";
								}
								
								if($a_photos['zdjecie_obrobka'] || $a_photos['podpis_obrobka'])
									$html .= '<span>zdjęcia są obecnie poddawane obróbce</span>';
								elseif(!$a_photos['zdjecie'] && !$a_photos['podpis'] && !$a_photos['zdjecie_obrobka'] && $a_photos['podpis_obrobka'])
									$html .= '<span>Wysyłka Pocztą</span>';
								else
									$html .= '<span></span>';
								
							if(/*session::who('admin') && */!$czy_koszyk && !$id_zamowienia && !$czy_szukane)
							{
								$typ_karty = $id_karty==1 ? 'nauczyciela' : 'szkolna';
								if($a_pole['typ']=='zdjęcie' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)' || $a_pole['typ']=='zdjęcie (złożony)' && !$a_photos['zdjecie'])
									$html .= '<div class="file_browse"><input type="file" class="file_upload jExtension" data-extensions="jpg jpeg gif png pdf" name="files[]" data-karta="'.$typ_karty.'" data-typ="zdjecie" data-zrodlo="lista" data-id-legitymacji="'.$a_osoba['id_legitymacje'].'">Z</div>';
								if($a_pole['typ']=='podpis' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)' || $a_pole['typ']=='podpis(złożony)' && !$a_photos['podpis'])
									$html .= '<div class="file_browse"><input type="file" class="file_upload jExtension" data-extensions="jpg jpeg gif png pdf" name="files[]" data-karta="'.$typ_karty.'" data-typ="podpis" data-zrodlo="lista" data-id-legitymacji="'.$a_osoba['id_legitymacje'].'">P</div>';
							}
							
							$html .= '</td>';
			
						}
						elseif($a_pole['typ']=='pracodawca' )
						{
							$a_pracodawca = mod_placowki::get_pracodawca($a_osoba['kol'.$a_pole['kolumna']]);
							$html .= '<td>'.$a_pracodawca['dane1'].'</td>';
						}
						elseif($a_pole['typ']=='szkoła')
						{
							$a_pracodawca = mod_placowki::get_szkola($a_osoba['kol'.$a_pole['kolumna']]);
							$html .= '<td>'.$a_pracodawca['dane1'].'</td>';
						}
						elseif($a_pole['typ']=='okres zatrudnienia (prosty)' || $a_pole['typ']=='okres zatrudnienia (złożony)')
						{
							if(empty($a_osoba['kol'.$a_pole['kolumna']]))
							{
								if(!$czy_koszyk && !$id_zamowienia)
									$html .= '<td>na czas nieokreślony</td>';
								else
									$html .= '<td>'.mod_panel::get_parametr('data_waznosci_legitymacji').'</td>';
							}
							else
							{
								if(!$czy_koszyk && !$id_zamowienia)
									$html .= '<td>'.substr($a_osoba['kol'.$a_pole['kolumna']],0,10).'</td>';
								else
								{
									if(strpos($a_osoba['kol'.$a_pole['kolumna']],'-'))
										$html .= '<td>'.date('dmY',strtotime(substr($a_osoba['kol'.$a_pole['kolumna']],0,10))).'</td>';
									else
										$html .= '<td>'.$a_osoba['kol'.$a_pole['kolumna']].'</td>';
								}
							}
						}
						else
							$html .= '<td>'.$a_osoba['kol'.$a_pole['kolumna']].'</td>';
					}

					if($id_zamowienia && session::get('czy_zdalny'))
						$html .= "<td>{$a_osoba['kod_karty']}</td>";

					if(!$czy_koszyk && !$czy_szukane)
					{
						$czy_zamowienie = $id_zamowienia ? "/id_zamowienia/$id_zamowienia" : '';
						$html .= '<td><a href="legitymacje/pokaz_podglad_legitymacji/id_legitymacje/'.$a_osoba['id_legitymacje'].$czy_zamowienie.'" class="modal" data-width="879px">Podgląd</a></td>';
					}
	
					if(!$czy_koszyk && !$id_zamowienia && !$czy_szukane)
					{
						$html .= '<td><a href="'.app::base_url().'legitymacje/formularz-osoby/id_legitymacje/'.$a_osoba['id_legitymacje'].'" class="modal">Edytuj</a></td>';
						$html .= '<td><a href="#" class="czy_usunac_legitymacje">Usuń</a></td>';
					}
					
					if(!$id_zamowienia && !$czy_szukane)
						$html .= '<td class="center"><input type="checkbox" name="a_legitymacje['.$a_osoba['id_legitymacje'].']" class="checkAllTarget user" data-check-id="zaznaczDane_'.$uniqid.'"></td>';
					
					
					
					$html .= '</tr>';
				}
			}
		}
		$html .= '</tbody></table>';
		
		return $html;
	}


	public static function generuj_csv_zamowienia($a_pola,$a_osoby)
	{
		$csv = '';
		if($a_osoby)
		{
			foreach($a_osoby as $a_osoba)
			{
				$csv .= $a_osoba['id_legitymacje'].';';
				
				foreach($a_pola as $a_pole)
				{
					if(in_array($a_pole['typ'], array('imię 1','nazwisko 1','nr legitymacji','uwagi')))
						$csv .= $a_osoba['kol'.$a_pole['kolumna']].';';
					elseif($a_pole['typ']=='pracodawca' )
					{
						$a_pracodawca = mod_placowki::get_pracodawca($a_osoba['kol'.$a_pole['kolumna']]);
						$csv .= $a_pracodawca['dane1'].';';
					}
					elseif($a_pole['typ']=='szkoła')
					{
						$a_pracodawca = mod_placowki::get_szkola($a_osoba['kol'.$a_pole['kolumna']]);
						$csv .= $a_pracodawca['dane1'].';';
					}

				}

				$csv .= $a_osoba['kod_karty'].";\r\n";
			}
		}

		return $csv;
	}

	public static function get_photos($id_legitymacje)
	{
		$a_photos['zdjecie_obrobka'] = file_exists("images/do_obrobki/$id_legitymacje.jpg") ? "images/do_obrobki/$id_legitymacje.jpg" : false;
		$a_photos['podpis_obrobka'] = file_exists("images/do_obrobki/Kopia {$id_legitymacje}.jpg") ? "images/do_obrobki/Kopia {$id_legitymacje}.jpg" : false;
		
		$id_placowki = db::get_one("SELECT id_placowki FROM legitymacje WHERE id_legitymacje=$id_legitymacje");
		
		$a_photos['zdjecie'] = file_exists("images/placowki/$id_placowki/$id_legitymacje.jpg") ? "images/placowki/$id_placowki/$id_legitymacje.jpg" : false;
		$a_photos['podpis'] = file_exists("images/placowki/$id_placowki/Kopia {$id_legitymacje}.jpg") ? "images/placowki/$id_placowki/Kopia {$id_legitymacje}.jpg" : false;

		return $a_photos;
	}
	
	public static function usun_zdjecia($a_zdjecia)
	{
		if($a_photos['zdjecie_obrobka'])
			unlink($a_photos['zdjecie_obrobka']);
		if($a_photos['podpis_obrobka'])
			unlink($a_photos['podpis_obrobka']);
		if($a_photos['zdjecie'])
			unlink($a_photos['zdjecie']);
		if($a_photos['podpis'])
			unlink($a_photos['podpis']);
	}
	
	public static function get_legitymacja($id_legitymacje)
	{
		return db::get_many("SELECT legitymacje.*, karty.nazwa as nazwa_karty FROM legitymacje JOIN karty USING(id_karty) WHERE id_legitymacje=$id_legitymacje");
	}
	
	public static function get_karty($ids,$id_karty,$czy_prosto=false)
	{
		$a_legitymacje = db::get_many("SELECT * FROM legitymacje WHERE id_legitymacje IN($ids)");
		
		if($czy_prosto)
			return $a_legitymacje;
		
		$a_pola = db::get_many("SELECT * FROM karty_pola JOIN karty_pola_typy USING(id_karty_pola_typy) WHERE id_karty=$id_karty");
		$a_user = array();
		
		if($a_legitymacje)
		{
			foreach($a_legitymacje as $index=>$a_legitymacja)
			{
				$a_user[$index]['id_legitymacje'] = $a_legitymacja['id_legitymacje'];
				foreach($a_pola as $a_pole)
				{/*
					if(in_array($a_pole['typ'],array('imie1','imie2','nazwisko1','nazwisko2')))
						$a_user[$index][$a_pole['typ']] = $a_legitymacja['kol'.$a_pole['kolumna']];
					elseif($a_pole['typ']=='data')*/
					if(isset($a_legitymacja['kol'.$a_pole['kolumna']]))
						$a_user[$index][$a_pole['nazwa']] = $a_legitymacja['kol'.$a_pole['kolumna']];
				}
			}
		}
		//var_dump($a_user);exit;
		return $a_user;
	}

	public static function generuj_pdf($ids,$id_karty,$a_placowka,$czy_landscape=false)
	{
		$file_name = "raport-".uniqid();

		view::add('a_placowka',$a_placowka);
		view::add('a_legitymacje',self::get_karty($ids,$id_karty));
		view::add('srv',mod_panel::$js);
		
		if($id_karty==1)
			$html = view::display('legitymacje/umowa_nauczyciela.tpl',true,true);
		else
			$html = view::display('legitymacje/umowa_szkolna.tpl',true,true);

 		$landscape = $czy_landscape ? "-O landscape" : '';
 		file_put_contents("images/raporty/{$file_name}.html", $html);
 		system("/usr/local/bin/wkhtmltopdf $landscape images/raporty/{$file_name}.html images/raporty/{$file_name}.pdf");
		unlink("images/raporty/{$file_name}.html");
		
		return $file_name;
	}
	
	public static function sprawdz_dostep_legitymacji($id_legitymacji)
	{
		return db::get_one("SELECT 1 FROM legitymacje WHERE id_legitymacje=$id_legitymacji AND id_placowki=".session::get('id_placowki'));
	}
	/*
	public static function sprawdz_dostep_legitymacji($id_legitymacji)
	{
		$id_users = session::get_user('parent_id');
		return db::get_one("SELECT 1 FROM legitymacje JOIN users_placowki USING(id_placowki) WHERE id_legitymacje=$id_legitymacji AND id_users IN ($id_users)");
	}
	*/
	public static function get_legitymacja_prev($id_legitymacje,$id_karty,$id_zamowienia=false)
	{
		$tabela = $id_zamowienia ? "zamowienia_legitymacje" : 'legitymacje';
		$sql_filtr = $id_zamowienia ? " AND id_zamowienia=$id_zamowienia" : "AND id_placowki=".session::get('id_placowki');
		
		return db::get_one("SELECT id_legitymacje FROM $tabela WHERE id_legitymacje<$id_legitymacje AND id_karty=$id_karty $sql_filtr ORDER BY id_legitymacje DESC LIMIT 1");			
	}
	
	public static function get_legitymacja_next($id_legitymacje,$id_karty,$id_zamowienia=false)
	{
		$tabela = $id_zamowienia ? "zamowienia_legitymacje" : 'legitymacje';
		$sql_filtr = $id_zamowienia ? " AND id_zamowienia=$id_zamowienia" : "AND id_placowki=".session::get('id_placowki');
		
		return db::get_one("SELECT id_legitymacje FROM $tabela WHERE id_legitymacje>$id_legitymacje AND id_karty=$id_karty $sql_filtr LIMIT 1");
	}
	
	public static function get_podglad_karty($a_pola,$a_osoba)
	{
		$html = '';
		if($a_pola)
		{
			foreach($a_pola as $a_pole)
			{
				if(in_array($a_pole['typ'],array('zdjęcie','podpis','zdjęcie i podpis','zdjęcie i podpis (złożony)','zdjęcie (złożony)')))
				{
					$a_photos = self::get_photos($a_osoba['id_legitymacje']);

					if($a_photos['zdjecie'] && ($a_pole['typ']=='zdjęcie' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)' || $a_pole['typ']=='zdjęcie (złożony)'))
						$html .= "<img src='".app::base_url()."img.php?id_legitymacje={$a_osoba['id_legitymacje']}&typ=zdjecie' class='podglad_karty_zdjecie'>";
					if($a_photos['podpis'] && ($a_pole['typ']=='podpis' || $a_pole['typ']=='zdjęcie i podpis' || $a_pole['typ']=='zdjęcie i podpis (złożony)'))
						$html .= "<img src='".app::base_url()."img.php?id_legitymacje={$a_osoba['id_legitymacje']}&typ=podpis' class='podglad_karty_podpis'>";
					if($a_photos['zdjecie_obrobka'])
						$html .= "<img src='".app::base_url()."images/site/user.png' class='podglad_karty_zdjecie'>";
					if($a_photos['podpis_obrobka'])
						$html .= "<img src='".app::base_url()."images/site/podpis.png' class='podglad_karty_podpis'>";
	
				}
				elseif($a_pole['typ']=='pracodawca')
				{
					$a_pracodawca = mod_placowki::get_pracodawca($a_osoba['kol'.$a_pole['kolumna']]);
					$html .= '<p class="podglad_karty_pracodawca">'.$a_pracodawca['dane1'].'<br>'.$a_pracodawca['dane2'].'<br>'.$a_pracodawca['dane3'].'<br>'.$a_pracodawca['dane4'].'</p>';
				}
				elseif($a_pole['typ']=='szkoła')
				{
					$a_szkola = mod_placowki::get_pracodawca($a_osoba['kol'.$a_pole['kolumna']]);
					$html .= "<p class='podglad_karty_szkola'>{$a_szkola['dane1']}<br>{$a_szkola['dane2']}<br>{$a_szkola['dane3']}<br>{$a_szkola['dane4']}<br>{$a_szkola['dane5']}<br>{$a_szkola['dane6']}</p>";
				}
				elseif($a_pole['typ']=='okres zatrudnienia (prosty)' || $a_pole['typ']=='okres zatrudnienia (złożony)')
				{
					if(empty($a_osoba['kol'.$a_pole['kolumna']]))
					{
						$html .= '<p class="podglad_karty_data_waznosci">'.mod_panel::get_parametr('data_waznosci_legitymacji').'</p>';
					}
					else
					{
						$html .= '<p class="podglad_karty_data_waznosci">'.substr($a_osoba['kol'.$a_pole['kolumna']],0,10).'</p>';
					}
				}
				elseif($a_pole['typ']!='uwagi')
					$html .= '<p class="podglad_karty_'.str_replace('-','_',hlp_functions::make_sludge($a_pole['typ'])).'">'.$a_osoba['kol'.$a_pole['kolumna']].'</p>';
			}

			if($a_osoba['id_karty']!=1)
			{
				$a_placowka = mod_placowki::get_placowka($a_osoba['id_placowki']);
				$html .= '<p class="podglad_karty_dyrektor">'.$a_placowka['dyrektor'].'</p>';
			}
		}
		
		return $html;
	}

	public static function czy_placowka_ma_legitymacje_szkolne($id_placowki)
	{
		return db::get_one("SELECT 1 FROM karty_placowki WHERE id_karty IN(4,5,6,7,8,9) AND id_placowki=$id_placowki");
	}

}

?>