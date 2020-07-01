<?php
class con_legitymacje extends controllers_parent{
	
	public static function formularz_wyboru_karty__lg()
	{
		if(!isset($_GET['id_placowki']) || !hlp_validator::id($_GET['id_placowki']))
		{
			app::err('Nieznana placówka');
			view::message();
		}
		
		$a_placowka = mod_placowki::sprawdz_poprawnosc_placowki($_GET['id_placowki']);
		
		if(!app::get_result())
			view::message();
		
		view::add('a_placowka',$a_placowka);
		view::add('a_karty',mod_legitymacje::get_dostepne_karty($_GET['id_placowki']));
		view::display();
	}
	
	public static function dodaj_legitymacje__lg()
	{
		$a_placowka = mod_placowki::sprawdz_poprawnosc_placowki(session::get('id_placowki'));
		
		if(!app::get_result())
			view::message();
		
		if(!isset($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieznany typ legitymacji');
			view::message();
		}

		if(!mod_legitymacje::sprawdz_dostepnosc_karty($_GET['id_karty'],session::get('id_placowki')))
		{
			app::err('Ta placówka już ma przypisany ten typ legitymacji');
			view::message();
		}

		if(!mod_umowy::get_umowa_placowki($_GET['id_karty']==1 ? 1 : 2, session::get('id_placowki')))
		{
			$a_strona = db::get_row("SELECT * FROM sites WHERE id_sites=38");
			app::err(strip_tags($a_strona['text']));
			view::message();
		}
		
		mod_legitymacje::przypisz_karte_do_placowki($_GET['id_karty'],session::get('id_placowki'),'aktywna');
		
		$email = db::get_one("SELECT email FROM users WHERE id_users=".session::get_id());
		
		mod_users::wyslij_maila($email,21,'','',array('id_placowki'=>session::get('id_placowki'),'id_karty'=>$_GET['id_karty']));

		mod_logi::dodaj('dodano legitymację', $_GET['id_karty']);
		view::redirect('placowki/placowka/id/'.session::get('id_placowki'));
	}
	
	public static function pobierz_umowe_aktywacji_karty__lg()
	{
		if(!isset($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieznany typ legitymacji');
			view::message();
		}
		
		if(!isset($_GET['id_placowki']) || !hlp_validator::id($_GET['id_placowki']))
		{
			app::err('Nieznany typ placówki');
			view::message();
		}
		
		if(mod_legitymacje::sprawdz_dostepnosc_karty($_GET['id_karty'],$_GET['id_placowki']))
		{
			app::err('Nie masz przypisanej tej karty');
			view::message();
		}

		if(!mod_placowki::sprawdz_dostep($_GET['id_placowki']))
		{
			app::err('Brak dostępu do tej placówki');
			view::message();
		}
		
		$a_placowka = mod_placowki::get_placowka($_GET['id_placowki']);
		
		if(empty($_GET['pdf']))
		{
			$_SESSION['form']['a_placowka'] = $a_placowka;
			
			view::add('umowa',1);
			view::add('id_karty',$_GET['id_karty']);
			view::add('id_placowki',$_GET['id_placowki']);
			view::add('a_typ_szkoly',db::get_by_id("typy_szkol",$a_placowka['id_typy_szkol']));
			view::display('placowki/zapisz_placowke_podglad.tpl');
		}
		else
		{
			unset($_SESSION['form']['a_placowka']);

			view::add('a_placowka',$a_placowka);
			$html = view::display('legitymacje/umowa.tpl',true,true);
	
	 		file_put_contents("images/raporty/umowa.html", $html);
			$filename = "images/raporty/umowa.pdf";
			
	 		system("/usr/bin/wkhtmltopdf  images/raporty/umowa.html $filename");
			
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="'.basename($filename).'"');
			header('Content-Length: ' . filesize($filename));
			
			flush();
			readfile($filename);
			// delete file
			unlink($filename);
			unlink("images/raporty/umowa.html");
		}
	}
	
	public static function aktywuj_karte()
	{
		if(!session::who('admin') && !session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień, aby oglądać tę stronę');
			view::message();
		}
		
		
		if(!isset($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieznany typ legitymacji');
			view::message();
		}
		
		if(!isset($_GET['id_placowki']) || !hlp_validator::id($_GET['id_placowki']))
		{
			app::err('Nieznany typ placówki');
			view::message();
		}
		
		if(mod_legitymacje::sprawdz_dostepnosc_karty($_GET['id_karty'],$_GET['id_placowki']))
		{
			app::err('Masz już przypisaną tą kartę');
			view::message();
		}

		if(!mod_placowki::sprawdz_dostep($_GET['id_placowki']))
		{
			app::err('Brak dostępu do tej placówki');
			view::message();
		}
		
		db::update('karty_placowki',"id_karty={$_GET['id_karty']} AND id_placowki={$_GET['id_placowki']}",array('status'=>'aktywna'));
		view::message("Karta aktywowana");
	}
	
	public static function lista_osob_legitymacji__lg()
	{
		if(empty($_REQUEST['id_zamowienia']) && !session::get('id_placowki'))
		{
			app::err('Nieznana placówka');
			view::message();
		}
		
		$a_placowka = mod_placowki::get_placowka(session::get('id_placowki'));

		if(empty($_REQUEST['id_zamowienia']))
		{
			if(!isset($_REQUEST['id_karty']) || !hlp_validator::id($_REQUEST['id_karty']))
			{
				app::err('Nieznany typ legitymacji');
				view::message();
			}
			
			if(!mod_karty::sprawdz_dostep_karty($_REQUEST['id_karty'],session::get('id_placowki')))
			{
				app::err('Karta nie jest przypisana do tej placówki');
				view::message();
			}

			$a_karta = mod_karty::get_karta_placowki($_REQUEST['id_karty']);
			
			if(!mod_placowki::get_pracodawcy(session::get('id_placowki'),$a_karta['id_karty']==1 ? false : true))
			{
				view::redirect('placowki/formularz-pracodawcy/id_karty/'.$_REQUEST['id_karty'].($a_karta['id_karty']!=1 ? '/czy_szkoly/1' : ''));
			}
	
			$a_umowa = mod_umowy::get_umowa_placowki($_REQUEST['id_karty']==1 ? 1 : 2,session::get('id_placowki'));
			view::add('czy_moze_zamawiac', $a_umowa['status']=='aktywna' ? true : false);
			view::add('a_placowka',$a_placowka);
			view::add('a_karta',$a_karta);
			$id_karty = $_REQUEST['id_karty'];
			$id_placowki = session::get('id_placowki');
		}
		else
		{
			if(!hlp_validator::id($_REQUEST['id_zamowienia']))
			{
				app::err('Nieprawidłowe zamówienie');
				view::message();
			}
			
			if(!session::who('admin') && !session::who('mod') && !mod_zamowienia::sprawdz_dostep($_REQUEST['id_zamowienia']))
			{
				app::err('Brak dostępu');
				view::message();
			}

			$a_zamowienie = mod_zamowienia::get_zamowienie($_REQUEST['id_zamowienia']);
			$id_karty = $a_zamowienie['id_karty'];
			$id_placowki = $a_zamowienie['id_placowki'];

			view::add('a_placowka',mod_placowki::get_placowka($id_placowki));
			view::add('a_karta',mod_karty::get_karta($id_karty));
			view::add('a_zamowienie',$a_zamowienie);
		}
		
		$a_pola_karty = mod_karty::get_pola_karty($id_karty);
		$a_dane_legitymacji = mod_legitymacje::get_dane_legitymacji($id_karty,$id_placowki,isset($_REQUEST['id_zamowienia']) ? $_REQUEST['id_zamowienia'] : false);
		
		if(!empty($_REQUEST['csv']))
		{
			$csv = mod_legitymacje::generuj_csv_zamowienia($a_pola_karty,$a_dane_legitymacji);
			$file_name = "zamowienie-{$_REQUEST['id_zamowienia']}.csv";
			file_put_contents("images/temp/{$file_name}", $csv);
			app::ok('CSV wygenerowany. Możesz go ściągnąć <a href="'.app::base_url().'get.php?typ=zamowienie.csv&id_zamowienia='.$_REQUEST['id_zamowienia'].'" download>tutaj</a>');
		}
		
		view::add('tabela',mod_legitymacje::zwroc_tabele_z_danymi($a_pola_karty,$a_dane_legitymacji,false,isset($_REQUEST['id_zamowienia']) ? $_REQUEST['id_zamowienia'] : false,$id_karty));
		
		if(isset($_REQUEST['pdf']))
		{
			if(empty($_POST['a_legitymacje']))
				app::err('Nie wybrano osób');
			else
			{
				$ids = '';
				foreach($_POST['a_legitymacje'] as $id_legitymacji=>$asd)
				{
					if(mod_legitymacje::sprawdz_dostep_legitymacji($id_legitymacji))
						$ids .= $id_legitymacji.',';
				}
				$ids = trim($ids,',');
				$file_name = mod_legitymacje::generuj_pdf($ids,$_REQUEST['id_karty'],$a_placowka);
				app::ok('Raport wygenerowany. Możesz go <a href="'.app::base_url().'get.php?typ=raporty&nazwa='.$file_name.'.pdf" download class="button">ściągnąć</a> lub <a href="'.app::base_url().'get.php?typ=raporty&nazwa='.$file_name.'.pdf" target="_blank" class="button">otworzyć</a>');
			}
		}

		$srv = mod_panel::$js;
			
		view::add('czy_formularze_zaakceptowane',mod_users::czy_formularze_zaakceptowane(session::get_id()));
		view::add('czy_zamawianie_wlaczone',mod_panel::get_setting('czy_zamawianie_wlaczone'));
		head::add_js_file($srv.'libs/jcrop/jquery.Jcrop.min.js');
		head::add_css_file($srv.'libs/jcrop/jquery.Jcrop.min.css');
		head::add_js_file($srv."libs/file_upload/js/vendor/jquery.ui.widget.js");
		head::add_js_file($srv."libs/file_upload/js/jquery.iframe-transport.js");
		head::add_js_file($srv."libs/file_upload/js/jquery.fileupload.js");
		
		view::display();
	}
	
	public static function formularz_osoby__lg()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Nieznana placówka');
			view::message();
		}
		
		if(!isset($_GET['id_legitymacje']))
		{
			if(!isset($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
			{
				app::err('Nieznany typ legitymacji');
				view::message();
			}
			
			if(!mod_karty::sprawdz_dostep_karty($_GET['id_karty'],session::get('id_placowki')))
			{
				app::err('Karta nie jest przypisana do tej placówki');
				view::message();
			}
			
			$a_placowka = mod_placowki::get_placowka(session::get('id_placowki'));
			$a_karta = mod_karty::get_karta($_GET['id_karty']);

			view::add('a_photos',false);
			view::add('a_placowka',$a_placowka);
			view::add('a_karta',$a_karta);
			view::add('a_pola',mod_karty::get_pola_karty($_GET['id_karty']));
		}
		elseif(isset($_GET['id_legitymacje']) && hlp_validator::id($_GET['id_legitymacje']))
		{
			$a_legitymacja = db::get_by_id('legitymacje',$_GET['id_legitymacje']);
			$a_photos = mod_legitymacje::get_photos($_GET['id_legitymacje']);

			$a_placowka = mod_placowki::sprawdz_poprawnosc_placowki($a_legitymacja['id_placowki']);
			
			if(!app::get_result())
				view::message();
			
			$a_karta = mod_karty::get_karta($a_legitymacja['id_karty']);
			
			view::add('a_photos',$a_photos);
			view::add('a_legitymacja',$a_legitymacja);
			view::add('a_karta',$a_karta);
			view::add('a_pola',mod_karty::get_pola_karty($a_legitymacja['id_karty']));
			view::add('a_placowka',$a_placowka);
		}
		else
		{
			app::err('Brak dostępu');
			view::message();
		}

		view::add('a_zapamietane_wartosci',!empty($_SESSION['a_zapamietane_wartosci']) ? $_SESSION['a_zapamietane_wartosci'] : false);
		view::add('a_pracodawcy',mod_placowki::get_pracodawcy(session::get('id_placowki'),$a_karta['id_karty']==1 ? false : true));
		view::add('img_rand',rand(1,1000));
		view::display();
	}

	public static function formularz_importu_osob__lg()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Nieznana placówka');
			view::message();
		}

		if(!isset($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieznany typ legitymacji');
			view::message();
		}
		
		if(!mod_karty::sprawdz_dostep_karty($_GET['id_karty'],session::get('id_placowki')))
		{
			app::err('Karta nie jest przypisana do tej placówki');
			view::message();
		}

		view::add('a_pracodawcy',mod_placowki::get_pracodawcy(session::get('id_placowki'),$_GET['id_karty']==1 ? false : true));
		view::add('id_karty',$_GET['id_karty']);
		view::display();
	}
	
	public static function upload_danych_z_pliku__lg()
	{
		$filename = uniqid().'.csv';
		file_put_contents('images/temp/'.$filename, fopen('php://input', 'r'));

		$a_plik = file('images/temp/'.$filename);

		$a_dane = array();
		foreach($a_plik as $index=>$linijka)
		{
			$a_dane[$index] = explode(';',$linijka);
			
			foreach($a_dane[$index] as $index2=>$dana)
			{
				$a_dane[$index][$index2] = self::WIN1250_2_UTF8($dana);
			}
		}
		
		unlink('images/temp/'.$filename);
		echo json_encode($a_dane);
		exit;
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
	
	public static function importuj_dane__lg()
	{
		set_time_limit(600);
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);
		$a_dane['id_karty'] = $_POST['id_karty'];
		$a_dane['id_pracodawcy'] = $_POST['id_pracodawcy'];

		mod_logi::dodaj('import osób', $_GET['id_karty']);
		$id_legitymacje = mod_legitymacje::zapisz_dane_legitymacji($a_dane);
	}
	
	public static function zapamietaj_wartosc__lg()
	{
		$_SESSION['a_zapamietane_wartosci'][$_GET['id_pola']] = $_GET['wartosc'];
		//session::set('zapamietana_wartosc_'.$_GET['id_pola'],$_GET['wartosc']);
	}
	
	public static function zapisz_dane_legitymacji__lg()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Nieznana placówka');
			view::message();
		}
		
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);
		
		if(empty($a_dane['id_legitymacje']))
		{
			if(!isset($a_dane['id_karty']) || !hlp_validator::id($a_dane['id_karty']))
			{
				app::err('Nieznany typ legitymacji');
				view::message();
			}
			
			if(!mod_karty::sprawdz_dostep_karty($a_dane['id_karty'],session::get('id_placowki')))
			{
				app::err('Karta nie jest przypisana do tej placówki');
				view::message();
			}
		}
		elseif(!hlp_validator::id($a_dane['id_legitymacje']))
		{
			app::err('nieprawidłowe id legitymacji');
			view::message();
		}

		$id_legitymacje = mod_legitymacje::zapisz_dane_legitymacji($a_dane);
		session::set('id_legitymacje_zapamietane',$id_legitymacje);
		mod_logi::dodaj('dodano osobę',$id_legitymacje);
		app::ok('Nadano ID: '.$id_legitymacje);
		view::json(true,'', array('id_legitymacje'=>$id_legitymacje));		
	}
	
	public static function zapisz_zdjecie()
	{
		if(!session::get('id_placowki'))
			view::json(false,'Musisz najpierw wybrać placówkę');
		
		$rodzaj = $_POST['rodzaj'];

		$_POST['w'] = ceil($_POST['w']);
		$_POST['h'] = ceil($_POST['h']);
		
		if($rodzaj=='zdjecie')
		{
			$targ_w = 225;
			$targ_h = 307;
		}
		else
		{
			$targ_w = 300;
			$targ_h = 60;
		}

		$ext = hlp_image::get_extension($_POST['img']);
		$src = str_replace(app::base_url(), '', $_POST['img']);

		if($ext=='.jpg' or $ext=='.jpeg')
			$img_r = imagecreatefromjpeg($src);
  		elseif($ext=='.png')
			$img_r = imagecreatefrompng($src); 
  		elseif($ext=='.gif')
			$img_r = imagecreatefromgif($src);

		$dst_r = imagecreatetruecolor( $targ_w, $targ_h );
		
		if($ext=='.png' || $ext=='.gif')
		{
			$background = @imagecolorallocate($dst_r, 0, 0, 0);
            @imagecolortransparent($dst_r, $background);
            @imagealphablending($dst_r, false);
            @imagesavealpha($dst_r, true);
		}

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);

		$ext = hlp_image::get_extension(basename($src));

		$dir = 'images/do_obrobki/';
		
		$id_legitymacje_zapamietane = !empty($_POST['id_legitymacje']) && $_POST['id_legitymacje']!='false' ? $_POST['id_legitymacje'] : session::get('id_legitymacje_zapamietane');

		if(!is_dir($dir))
			mkdir($dir);
		
		$file = $id_legitymacje_zapamietane;
		
		if($rodzaj=='podpis')
			$file = 'Kopia '.$file;
		
		$file .= '.jpg';

		$filename = $dir.$file;
		$result=imagejpeg($dst_r,$filename,100);
		unlink('images/users/temp/'.basename($_POST['img']));

		if(mod_panel::$js=='js/')
		{
			system("/bin/mogrify -resample  300x300! $filename");
			system("/bin/mogrify -resize  {$targ_w}x{$targ_h} -quality 100 $filename");
		}

		view::json(true,'',array('file'=>$filename,'id_legitymacje'=>$id_legitymacje_zapamietane));
	}

	public static function formularz_zdjec__admin_mod()
	{
		view::display();
	}
	
	public static function importowanie_zdjec__admin_mod()
	{
		set_time_limit(600);
		
		if(strpos($_GET['filename'],'Kopia')!==false)
			$file = substr($_GET['filename'],5);
		else
			$file = $_GET['filename'];

		$a_filename = explode('.',$file);
		$id_legitymacje = db::get_one("SELECT id_placowki FROM legitymacje WHERE id_legitymacje=".$a_filename[0]);
		
		$url = "images/placowki/$id_legitymacje/";
		
		if(is_dir($url))
			mkdir($url,777);

		$url .= $_GET['filename'];
		file_put_contents($url, fopen('php://input', 'r'));
			
		if(file_exists("images/do_obrobki/".$_GET['filename']))
			unlink("images/do_obrobki/".$_GET['filename']);
			
		echo json_encode(array('success' => true, 'file' => $_GET['filename']));
		//exit();
	}
	
	public static function spakuj_i_pobierz_zdjecia__admin_mod()
	{
		 $typ = isset($_GET['typ']) && in_array($_GET['typ'], array('nauczyciela', 'szkolne')) ? $_GET['typ'] : 'nauczyciela';
		
		 $zip = new ZipArchive();
		 
		 $a_pliki = glob('images/do_obrobki/*.jpg');
		 
		 $filename = "./do_obrobki.zip";
		 @unlink($filename);
		 
		 $zip->open($filename, ZipArchive::CREATE);
		 $liczba_plikow = 0;
		 
		 if($a_pliki)
		 {
		 	foreach($a_pliki as $plik)
			{
				$plik_temp = str_replace('Kopia ', '', $plik);
				$a_legitymacja = explode('.', $plik_temp);
				$id_legitymacje = str_replace('images/do_obrobki/','',$a_legitymacja[0]);

				$id_karty = db::get_one("SELECT id_karty FROM zamowienia_legitymacje WHERE id_legitymacje=$id_legitymacje");

				if($_GET['typ']=='nauczyciela' && $id_karty==1 || $_GET['typ']=='szkolne' && $id_karty!=1)
				{
					$zip->addFile($plik);
					$liczba_plikow++;
				}
			}
			
			if($liczba_plikow)
			{
				$zip->close();
				app::ok('Pliki gotowe do pobrania <a href="get.php?typ=do_obrobki.zip">pobierz</a>');
				view::redirect('legitymacje/formularz-zdjec');
			}
			else
				view::message("Brak plików do obróbki");
		 }
		 else
		 {
		 	view::message("Brak plików do obróbki");
		 }
	}
	
	public static function usun_zdjecie__lg()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowe id zdjęcia');
			view::message();
		}
		
		
		if(empty($_GET['typ']) || !in_array($_GET['typ'],array('zdjecie','podpis')))
		{
			app::err('Nieprawidłowe typ zdjęcia');
			view::message();
		}
		
		if(!mod_legitymacje::sprawdz_dostep_legitymacji($_GET['id']))
		{
			app::err('Nie możesz usunąć tego zdjęcia');
			view::message();
		}
		
		$plik = $_GET['typ']=='podpis' ? 'Kopia ' : '';
		$plik .= $_GET['id'].'.jpg';
		
		unlink('images/placowki/'.session::get('id_placowki').'/'.$plik);
		app::ok('Zdjęcie usunięte');
		
		if(empty($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieprawidłowe id_karty');
			view::message();
		}
		
		view::redirect('legitymacje/lista-osob-legitymacji/id_karty/'.$_GET['id_karty']);
	}
	
	public static function pokaz_podglad_legitymacji__lg()
	{
		if(empty($_GET['id_legitymacje']) || !hlp_validator::id($_GET['id_legitymacje']))
		{
			app::err('Nieprawidłowe id');
			view::message();
		}
		/*
		if(!mod_legitymacje::sprawdz_dostep_legitymacji($_GET['id_legitymacje']))
		{
			app::err('Brak dostępu');
			view::message();
		}
*/
		$id_zamowienia = !empty($_GET['id_zamowienia']) ? $_GET['id_zamowienia'] : false;

		if(!$id_zamowienia)
			$a_osoba = db::get_row("SELECT * FROM legitymacje WHERE id_legitymacje=".$_GET['id_legitymacje']);
		else
			$a_osoba = db::get_row("SELECT * FROM zamowienia_legitymacje WHERE id_legitymacje=".$_GET['id_legitymacje']." AND id_zamowienia=".$id_zamowienia);

		view::add('a_osoba', $a_osoba);
		view::add('id_legitymacji', $_GET['id_legitymacje']);
		view::add('id_zamowienia', $id_zamowienia);
		view::add('id_prev',mod_legitymacje::get_legitymacja_prev($_GET['id_legitymacje'],$a_osoba['id_karty'],$id_zamowienia));
		view::add('id_next',mod_legitymacje::get_legitymacja_next($_GET['id_legitymacje'],$a_osoba['id_karty'],$id_zamowienia));
		view::add('podglad_karty',mod_legitymacje::get_podglad_karty(mod_karty::get_pola_karty($a_osoba['id_karty']),$a_osoba));
		view::add('a_karta',mod_karty::get_karta($a_osoba['id_karty']));
		view::display();
	}
	
	public static function umowy_legitymacji_szkolnych__lg()
	{
		if(empty($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieprawidłowe id karty');
			view::message();
		}
		
		$id_cenniki_karty = mod_karty::get_cennik_karty($_GET['id_karty']);
			
		if(!$id_cenniki_karty)
			$id_cenniki_karty = mod_karty::get_domyslny_cennik_karty($_GET['id_karty']);
		
		view::add('a_cenniki',mod_karty::get_ceny_z_cennika($id_cenniki_karty));
		
		$a_legitymacja = db::get_by_id('karty',$_GET['id_karty']);
		
		view::add('czy_bez_tekstu', isset($_GET['bez_tekstu']));
		view::add('a_opis',db::get_row("SELECT * FROM sites WHERE id_sites=".$a_legitymacja['id_sites']));
		view::add('a_strona',db::get_row("SELECT * FROM sites WHERE id_sites=38"));
		view::add('czy_umowa',mod_umowy::get_umowa_placowki(2, session::get('id_placowki')));
		view::add('id_karty', $_GET['id_karty']);
		view::display();
	}
	
	public static function usun_legitymacje__lg()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']))
			view::json(false, 'Nieprawidłowe id legitymacje');
		
		if(!mod_legitymacje::sprawdz_dostep_legitymacji($_POST['id']))
			view::json(false, 'Brak dostępu');
		
		db::delete('legitymacje',"id_legitymacje=".$_POST['id']);
		$a_photos = mod_legitymacje::get_photos($id_legitymacje);
		mod_legitymacje::usun_zdjecia($a_photos);
		
		view::json(true, 'Legitymacja usunięta');
	}
	
	public static function pokaz_zdjecie()
	{
		if(empty($_GET['id_legitymacje']) || !hlp_validator::id($_GET['id_legitymacje']))
			exit;
		
		if(!mod_legitymacje::sprawdz_dostep_legitymacji($_GET['id_legitymacje']))
			exit;

		$id_placowki = db::get_one("SELECT id_placowki FROM legitymacje WHERE id_legitymacje=".$_GET['id_legitymacje']);
		
		$podpis = $_GET['typ']=='podpis' ? 'Kopia ' : '';
		
		if(empty($_GET['obrobka']))
			$imagepath = "images/placowki/$id_placowki/{$podpis}{$_GET['id_legitymacje']}.jpg";
		else
			$imagepath = "images/do_obrobki/{$podpis}{$_GET['id_legitymacje']}.jpg";

		//$imagepath = '1.jpg';
		header("Content-Type: image/jpeg");
		header("Content-Length: " . filesize($imagepath));
		echo file_get_contents($imagepath);

		exit;
	}
	
	public static function szukaj_legitymacji__admin_mod()
	{
		$fraza = isset($_GET['fraza']) ? $_GET['fraza'] : '';
		view::add('fraza', $fraza);
		
		$id_karty = isset($_GET['id_karty']) ? $_GET['id_karty'] : false;
		view::add('id_karty', $id_karty);

		if(isset($_GET['szukaj']))
		{
			$sql_id_karty = $id_karty ? " AND id_karty=$id_karty" : '';
			$sql_fraza = " AND (id_legitymacje LIKE '$fraza%' OR kol1 LIKE '$fraza%' OR kol2 LIKE '$fraza%' OR kol3 LIKE '$fraza%' OR kol4 LIKE '$fraza%' OR kol5 LIKE '$fraza%' OR kol6 LIKE '$fraza%' OR kol7 LIKE '$fraza%' OR kol8 LIKE '$fraza%' OR kol9 LIKE '$fraza%' OR kol10 LIKE '$fraza%' OR kol11 LIKE '$fraza%' OR kol12 LIKE '$fraza%' OR kol13 LIKE '$fraza%')";
			$a_osoby_wyszukane = db::get_many("SELECT id_karty, id_legitymacje FROM legitymacje WHERE 1=1 $sql_id_karty $sql_fraza");
			$tabela = '';
			
			if($a_osoby_wyszukane)
			{
				foreach($a_osoby_wyszukane as $a_osoba)
				{
					$tabela .= mod_legitymacje::zwroc_tabele_z_danymi(mod_karty::get_pola_karty($a_osoba['id_karty']),mod_legitymacje::get_legitymacja($a_osoba['id_legitymacje']),false,false,$a_osoba['id_karty'],true);
				}
			}
		}
		
		view::add('tabela', $tabela);
		view::add('a_karty', db::get_all('karty'));
		view::display();
	}

	public static function sprawdz_numer_legitymacji()
	{//db::deb();
		if(!empty($_GET['numer']) && !empty($_GET['id_karty']) && hlp_validator::id($_GET['id_karty']))
		{
			$numer = str_replace('---', '/', $_GET['numer']);
			$kolumna = db::get_one("SELECT kolumna FROM karty_pola WHERE id_karty_pola_typy=15 AND id_karty=".$_GET['id_karty']);
			$sql_id_legitymacje = $_GET['id_legitymacje']!='undefined' && hlp_validator::id($_GET['id_legitymacje']) ? " AND id_legitymacje<>".$_GET['id_legitymacje'] : '';
			$result = db::get_one("SELECT 1 FROM legitymacje WHERE kol{$kolumna}='$numer' AND id_karty={$_GET['id_karty']} $sql_id_legitymacje AND id_placowki=".session::get('id_placowki'));

			view::json(!$result);
		}
		
		view::json(true);
	}
	
	public static function get_dostepne_karty__lg()
	{
		view::json(true, '', array('a_karty'=>mod_legitymacje::get_dostepne_karty(session::get('id_placowki'))));
	}
	
	public static function przenies_osoby_do_karty__lg()
	{
		if(empty($_POST['a_dane']))
			view::json(false,'Brak elementów');
		
		$a_dane = array();
		parse_str($_POST['a_dane'], $a_dane);
		
		if(empty($_POST['id_karty']) || !hlp_validator::id($_POST['id_karty']) || mod_legitymacje::sprawdz_dostepnosc_karty($_POST['id_karty'], session::get('id_placowki')))
			view::json(false, 'Brak ddostępu do karty');
		
		foreach($a_dane['a_legitymacje'] as $id_legitymacje=>$asd)
		{//db::deb();
			db::update('legitymacje', "id_legitymacje=$id_legitymacje", array('id_karty'=>$_POST['id_karty']));
			/*
			$a_legitymacja = mod_legitymacje::get_legitymacja($id_legitymacje);
			$a_legitymacja = $a_legitymacja[0];
			unset($a_legitymacja['id_legitymacje']);
			unset($a_legitymacja['nazwa_karty']);
			$a_legitymacja['id_karty'] = $_POST['id_karty'];

			$id_legitymacje_new = db::insert('legitymacje',$a_legitymacja);
			
			if($id_legitymacje_new)
			{
				$a_photos = mod_legitymacje::get_photos($id_legitymacje);
				$id_placowki = session::get('id_placowki');
				
				if($a_photos['zdjecie_obrobka'])
					copy($a_photos['zdjecie_obrobka'], "images/do_obrobki/$id_legitymacje_new.jpg");
				
				if($a_photos['podpis_obrobka'])
					copy($a_photos['podpis_obrobka'], "images/do_obrobki/Kopia {$id_legitymacje_new}.jpg");
				
				if($a_photos['zdjecie'])
					copy($a_photos['zdjecie'], "images/placowki/$id_placowki/$id_legitymacje_new.jpg");
					
				if($a_photos['zdjecie'])
					copy($a_photos['zdjecie'], "images/placowki/$id_placowki/Kopia {$id_legitymacje_new}.jpg");
			}*/
		}
		
		view::json(true, 'Legitymacje przeniesione');
	}
}

	
?>