<?php

class mod_umowy extends db
{
	public static function sprawdz_dostep($id_umowy)
	{
		return db::get_one("SELECT 1 FROM umowy2 WHERE id_umowy2=$id_umowy AND id_users=".session::get_id());
	}
	
	public static function get_umowy($id_placowki=false, $typ_umowy='', $fraza=false, $filtr = false)
	{
		$sql_id_placowki = $id_placowki ? " AND id_placowki=$id_placowki" : '';
		$sql_typ_umowy = $typ_umowy ? " AND id_umowy_typy=$typ_umowy" : '';
		$sql_fraza = $fraza ? " AND (numer_umowy LIKE '%$fraza%' OR id_placowki='$fraza')" : '';
		
		$sql_filtr = '';
		
		if(!empty($filtr))
			$sql_filtr = " AND status='$filtr'";
		//elseif($filtr=='wygasła')
		//	$sql_filtr = " AND okres_obowiazywania<=NOW() AND okres_obowiazywania<>'0000-00-00'";
		
		
		return db::get_many("SELECT id_placowki,nazwa,data_dodania,id_umowy2,status,data_potwierdzenia,numer_umowy,okres_obowiazywania FROM umowy2 LEFT JOIN umowy_typy USING(id_umowy_typy) WHERE 1=1 $sql_id_placowki $sql_typ_umowy $sql_fraza $sql_filtr");
	}
	
	public static function get_umowy_typy()
	{
		return db::get_all('umowy_typy');
	}
	
	public static function czy_ma_typ_umowy($id_umowy_typy)
	{
		return db::get_one("SELECT 1 FROM umowy2 WHERE id_umowy_typy=$id_umowy_typy AND id_placowki=".session::get('id_placowki'));
	}
	
	public static function get_umowy_naglowki()
	{
		return db::get_all('umowy_naglowki');
	}
	
	public static function check_umowa_data($a_umowa, $czy_krok1 = false)
	{
		$kom = 'Nie zapisano, ';
		
		if(isset($a_umowa['id_umowy']) && !hlp_validator::id($a_umowa['id_umowy']))
			return app::err($kom.' nieznana umowa');
		
		if(empty($a_umowa['id_umowy_typy']) || !hlp_validator::id($a_umowa['id_umowy_typy']))
			return app::err($kom.' nieznany typ umowy');
		
		if(empty($a_umowa['id_umowy_naglowki']) || !hlp_validator::id($a_umowa['id_umowy_naglowki']))
			return app::err($kom.' nieznany typ nagłówka');

		if($czy_krok1)
			return true;
		
		if($a_umowa['id_umowy_naglowki']==1 || $a_umowa['id_umowy_naglowki']==2)
		{
			if(empty($a_umowa['email_naruszenia']) || !hlp_validator::email($a_umowa['email_naruszenia']))
				return app::err($kom.' nieprawidłowy e-mail do powiadomień ws. naruszeń przetwarzania danych osobowych');
		}
		
		if($a_umowa['id_umowy_naglowki']==1 || $a_umowa['id_umowy_naglowki']==3 || $a_umowa['id_umowy_naglowki']==4)
		{
			if(empty($a_umowa['nazwa']) || !hlp_validator::alfanum($a_umowa['nazwa']))
				return app::err($kom.' nieprawidłowa nazwa');
			
			if(empty($a_umowa['kod_pocztowy']) || !hlp_validator::kod_pocztowy($a_umowa['kod_pocztowy']))
				return app::err($kom.' nieprawidłowy kod pocztowy');
			
			if(empty($a_umowa['miasto']) || !hlp_validator::alfanum($a_umowa['miasto']))
				return app::err($kom.' nieprawidłowe miasto');
			
			if(empty($a_umowa['adres']) || !hlp_validator::alfanum($a_umowa['adres']))
				return app::err($kom.'  nieprawidłowy adres');
			
			if(empty($a_umowa['nip']) || !hlp_validator::nip($a_umowa['nip']))
				return app::err($kom.' nieprawidłowy NIP');
		}
		/*
		if($a_umowa['id_umowy_naglowki']==1)
		{
			if(empty($a_umowa['nr_pelnomocnictwa']))
				return app::err($kom.' nieprawidłowy numer pelnomocnictwa');
		}
		*/
		if($a_umowa['id_umowy_naglowki']==3 || $a_umowa['id_umowy_naglowki']==4)
		{
			if(empty($a_umowa['regon']) || !hlp_validator::regon($a_umowa['regon']))
				return app::err($kom.' nieprawidłowy regon');
		}
			

		if(empty($a_umowa['imie']) || !hlp_validator::alfanum($a_umowa['imie']))
			return app::err($kom.' nieprawidłowe imię');
		
		if(empty($a_umowa['nazwisko']) || !hlp_validator::alfanum($a_umowa['nazwisko']))
			return app::err($kom.' nieprawidłowe nazwisko');

		if($a_umowa['id_umowy_naglowki']==1 || $a_umowa['id_umowy_naglowki']==2)
		{
			if(empty($a_umowa['placowka_nazwa']) || !hlp_validator::alfanum($a_umowa['placowka_nazwa']))
				return app::err($kom.' nieprawidłowa nazwa placowki');
			
			if(empty($a_umowa['placowka_kod_pocztowy']) || !hlp_validator::kod_pocztowy($a_umowa['placowka_kod_pocztowy']))
				return app::err($kom.' nieprawidłowy kod pocztowy placowki');
			
			if(empty($a_umowa['placowka_miasto']) || !hlp_validator::alfanum($a_umowa['placowka_miasto']))
				return app::err($kom.' nieprawidłowe miasto placowki');
			
			if(empty($a_umowa['placowka_adres']) || !hlp_validator::alfanum($a_umowa['placowka_adres']))
				return app::err($kom.' nieprawidłowy adres placowki');
			
			if(empty($a_umowa['placowka_regon']) || !hlp_validator::regon($a_umowa['placowka_regon']))
				return app::err($kom.' nieprawidłowy regon placowki');

			if(empty($a_umowa['email']) || !hlp_validator::email($a_umowa['email']))
				return app::err($kom.' nieprawidłowy email');
		}
		
		return true;
	}
	
	public static function generuj_naglowek_umowy($id_umowy_naglowki, $czesc, $a_umowa)
	{
		if($czesc==1)
		{
			switch($id_umowy_naglowki)
			{
				case 1:
					$id_sites = 57;
				break;
				case 2:
					$id_sites = 58;
				break;
				case 3:
					$id_sites = 59;
				break;
				case 4:
					$id_sites = 60;
				break;
			}
		}
		else
		{
			switch($id_umowy_naglowki)
			{
				case 1:
					$id_sites = 64;
				break;
				case 2:
					$id_sites = 65;
				break;
				case 3:
					$id_sites = 66;
				break;
				case 4:
					$id_sites = 67;
				break;
			}
		}
		
		$a_naglowek = db::get_by_id('sites', $id_sites);
		$naglowek = $a_naglowek['text'];
		
		$naglowek = self::podmien_dane($naglowek, $a_umowa);

		return $naglowek;
	}

	public static function podmien_dane($tresc, $a_umowa)
	{
		$tresc = str_replace('{id_placowki}',session::get('id_placowki'),$tresc);
		$tresc = str_replace('{data}',date('Y-m-d'),$tresc);
		$tresc = str_replace('{e-mail_umowa}',$a_umowa['email'],$tresc);
		$tresc = str_replace('{nazwa}',$a_umowa['nazwa'],$tresc);
		$tresc = str_replace('{kod}',$a_umowa['kod_pocztowy'],$tresc);
		$tresc = str_replace('{miasto}',$a_umowa['miasto'],$tresc);
		$tresc = str_replace('{adres}',$a_umowa['adres'],$tresc);
		$tresc = str_replace('{nip}',$a_umowa['nip'],$tresc);
		$tresc = str_replace('{regon}',$a_umowa['regon'],$tresc);
		$tresc = str_replace('{imie}',$a_umowa['imie'],$tresc);
		$tresc = str_replace('{nazwisko}',$a_umowa['nazwisko'],$tresc);
		$tresc = str_replace('{email}',$a_umowa['nazwisko'],$tresc);
		$tresc = str_replace('{nr_pelnomocnictwa}',$a_umowa['nr_pelnomocnictwa'],$tresc);
		$tresc = str_replace('{placowka_nazwa}',$a_umowa['placowka_nazwa'],$tresc);
		$tresc = str_replace('{placowka_kod}',$a_umowa['placowka_kod_pocztowy'],$tresc);
		$tresc = str_replace('{placowka_miasto}',$a_umowa['placowka_miasto'],$tresc);
		$tresc = str_replace('{placowka_adres}',$a_umowa['placowka_adres'],$tresc);
		$tresc = str_replace('{placowka_regon}',$a_umowa['placowka_regon'],$tresc);
		$tresc = str_replace('{numer}',self::tworz_nr_umowy($a_umowa['id_umowy_typy']),$tresc);
		$tresc = str_replace('{email_naruszenia}',$a_umowa['email_naruszenia'],$tresc);
		$tresc = str_replace('{dyrektor}',$a_umowa['dyrektor'],$tresc);
		
		$a_placowka = mod_placowki::get_placowka(session::get('id_placowki'));
		$tresc = str_replace('{imie_nazwisko_dyrektora}',$a_placowka['dyrektor'],$tresc);
		
		$a_pracodawcy = mod_placowki::get_pracodawcy(session::get('id_placowki'),false);
		$a_szkoly = mod_placowki::get_pracodawcy(session::get('id_placowki'),true);
		
		$a_user = db::get_by_id('users', $a_placowka['id_users']);
		$tresc = str_replace('{imie_usera}',$a_user['imie'],$tresc);
		$tresc = str_replace('{nazwsko_usera}',$a_user['nazwisko'],$tresc);
		$tresc = str_replace('{email_usera}',$a_user['email'],$tresc);
		
		if($a_pracodawcy)
		{
			$pracodawcy = '';
			foreach($a_pracodawcy as $a_pracodawca)
			{
				//$pracodawcy .= $a_pracodawca['nazwa'].'<br>';
				$pracodawcy .= $a_pracodawca['dane1'].'<br>';
				$pracodawcy .= $a_pracodawca['dane2'].'<br>';
				$pracodawcy .= $a_pracodawca['dane3'].'<br>';
				$pracodawcy .= $a_pracodawca['dane4'].'<br>';
				$pracodawcy .= '<br>';
			}
			
			$tresc = str_replace('{pracodawcy}',$pracodawcy,$tresc);
		}
		
		if($a_szkoly)
		{
			$szkoly = '';
			foreach($a_szkoly as $a_szkola)
			{
				//$szkoly .= $a_szkola['nazwa'].'<br>';
				$szkoly .= $a_szkola['dane1'].'<br>';
				$szkoly .= $a_szkola['dane2'].'<br>';
				$szkoly .= $a_szkola['dane3'].'<br>';
				$szkoly .= $a_szkola['dane4'].'<br>';
				$szkoly .= $a_szkola['dane5'].'<br>';
				$szkoly .= $a_szkola['dane6'].'<br>';
				$szkoly .= '<br>';
			}

			$tresc = str_replace('{szkoly}',$szkoly,$tresc);
		}
		
		return $tresc;
	}

	public static function generuj_umowe($id_umowy_naglowki, $id_umowy_typy, $a_umowa)
	{
		if($id_umowy_typy==1)
		{
			$id_sites_czesc_1 = 55;
			$id_sites_czesc_2 = 61;
		}
		elseif($id_umowy_typy==2)
		{
			$id_sites_czesc_1 = 56;
			$id_sites_czesc_2 = 62;
		}

		$umowa = self::generuj_naglowek_umowy($id_umowy_naglowki, 1, $a_umowa);
		$a_tresc = db::get_by_id('sites', $id_sites_czesc_1);
		$umowa .= self::podmien_dane($a_tresc['text'], $a_umowa);
		$umowa .= '<div class="page_break"></div>';
		
		$umowa .= self::generuj_naglowek_umowy($id_umowy_naglowki, 2, $a_umowa);
		$a_tresc = db::get_by_id('sites', $id_sites_czesc_2);
		$umowa .= self::podmien_dane($a_tresc['text'], $a_umowa);
		$umowa .= '<div class="page_break"></div>';
		
		$a_tresc = db::get_by_id('sites', $id_umowy_typy==2 ? 80 : 81);
		$umowa .= self::podmien_dane($a_tresc['text'], $a_umowa);
		
		$umowa .= '<div class="page_break"></div>';
		
		$a_tresc = db::get_by_id('sites', 63);
		$umowa .= self::podmien_dane($a_tresc['text'], $a_umowa);
		
		return $umowa;
	}
	
	public static function zapisz($a_umowa)
	{
		if(!self::check_umowa_data($a_umowa))
			return false;
		
		$tresc = self::generuj_umowe($a_umowa['id_umowy_naglowki'], $a_umowa['id_umowy_typy'], $a_umowa);
		$id_users = session::get_id() ? session::get_id() : db::get_one("SELECT id_users FROM placowki WHERE id_placowki=".session::get('id_placowki'));
		
		$a_dane = array('id_umowy_typy'=>$a_umowa['id_umowy_typy'],
						'id_umowy_naglowki'=>$a_umowa['id_umowy_naglowki'],
						'id_users'=>$id_users,
						'id_placowki'=>session::get('id_placowki'),
						'tresc'=>$tresc,
						'numer_umowy'=>self::tworz_nr_umowy($a_umowa['id_umowy_typy']),
						'data_dodania'=>'NOW()',
						'email'=>$a_umowa['email'],
						'email_naruszenia'=>$a_umowa['email_naruszenia'],
						'dyrektor'=>$a_umowa['dyrektor']
		);
		
		if(isset($a_umowa['id_umowy']))
			$id_umowy = db::update('umowy2', "id_umowy2=".$a_umowa['id_umowy'], $a_dane);
		else
			$id_umowy = db::insert('umowy2', $a_dane);
		
		if($id_umowy===false)
			return app::err('Błąd bd');
		else
		{
			app::ok();
			db::update('placowki', "id_placowki=".session::get('id_placowki'), array('dyrektor'=>$a_umowa['dyrektor']));
		}
		
		return isset($a_umowa['id_umowy']) ? $a_umowa['id_umowy'] : $id_umowy;
	}
	
	public static function tworz_nr_umowy($id_umowy_typy)
	{
		$numer = db::get_one("SELECT COUNT(*) FROM umowy2 WHERE id_umowy_typy=$id_umowy_typy")+1;
		
		if($numer<10)
			$numer = '00'.$numer;
		elseif($numer<100)
			$numer = '0'.$numer;
		
		$rok = date('Y');
		if($id_umowy_typy==1)
			return "LN/$numer/$rok";
		else
			return "LS/$numer/$rok";
	}
	
	public static function get_umowa($id_umowy)
	{
		return db::get_by_id('umowy2', $id_umowy);
	}

	public static function generuj_pdf($umowa, $numer_umowy)
	{
		$html = '
			<html>
				<head>
	
					<link rel="stylesheet" media="all" type="text/css" href="'.app::base_url().'css/print.css">
					<meta http-equiv="Content-Type" content="text/html charset=utf-8"/>
					<style>
						body{
						    font-family: dejavu sans;
							background: #fff;
						}
						
						ol, ul{
							list-style-position: outside !important;
						}
					</style>
				</head>
				<body>'.$umowa.'</body></html>';
				
		$pdf = "images/placowki/".session::get('id_placowki').'/umowy/';
		if(!is_dir($pdf))
			mkdir($pdf,0777);

		$pdf .= str_replace('/','-',$numer_umowy);
		unlink($pdf.'.pdf');
		unlink($pdf.'.html');

		file_put_contents($pdf.".html", $html);
 		system("/usr/local/bin/wkhtmltopdf --footer-center [page]/[topage] {$pdf}.html {$pdf}.pdf");
 		
 		return $pdf;
	}
	
	public static function get_umowa_placowki($id_umowy_typy, $id_placowki)
	{
		$a_umowy =  db::get_many("SELECT * FROM umowy2 WHERE id_umowy_typy=$id_umowy_typy AND id_placowki=$id_placowki");
	
		if($a_umowy)
		{
			foreach($a_umowy as $a_umowa)
			{
				if($a_umowa['status']=='podpisana')
				{
					if($a_umowa['okres_obowiazywania']=='0000-00-00' || strtotime($a_umowa['okres_obowiazywania']) >= time())
					{
						$a_umowa['status'] = 'aktywna';
						return $a_umowa;
					}
					else
						$a_umowa['status'] = 'nieaktywna';
				}
				elseif($a_umowa['status']=='unieważniona' || $a_umowa['status']=='rozwiązana')
					$a_umowa['status'] = 'nieaktywna';
			}
		}

		return !empty($a_umowy) ? $a_umowy[0] : false;
	}

	public static function zapisz_umowe_zew($a_umowa)
	{
		if(empty($a_umowa['numer_umowy']))
			return app::err('Nieprawidłowy numer umowy');
			
		if(empty($a_umowa['id_umowy_typy']) || !hlp_validator::id($a_umowa['id_umowy_typy']))
			return app::err('Nieprawidłowy typ umowy');

		$a_dane = array('id_umowy_typy'=>$a_umowa['id_umowy_typy'],
						'id_umowy_naglowki'=>0,
						'id_users'=>0,
						'id_placowki'=>session::get('id_placowki'),
						'status'=>'podpisana',
						'numer_umowy'=>$a_umowa['numer_umowy'],
						'data_dodania'=>empty($a_umowa['data_zawarcia']) ? 'NOW()' : $a_umowa['data_zawarcia'],
						'data_potwierdzenia'=>'NOW()',
						'email'=>$a_umowa['email'],
						'email_naruszenia'=>$a_umowa['email_naruszenia'],
						'okres_obowiazywania'=>$a_umowa['czas_umowy']=='nieokreslony' ? '0000-00-00' : $a_umowa['okres_obowiazywania'],
						'wersja'=>$a_umowa['wersja'],
						'uwagi'=>$a_umowa['uwagi']
		);
		
		if(isset($a_umowa['id_umowy']))
			$id_umowy = db::update('umowy2', "id_umowy2=".$a_umowa['id_umowy'], $a_dane);
		else
			$id_umowy = db::insert('umowy2', $a_dane);
		
		if($id_umowy===false)
			return app::err('Błąd bd');
		else
			app::ok();
		
		if(is_uploaded_file($_FILES['umowa']['tmp_name']))
		{
			$dir = "images/placowki/".session::get('id_placowki').'/umowy';
		
			if(!is_dir($dir))
				mkdir($dir,0777);
			
			hlp_image::save($_FILES['umowa'],$dir.'/'.str_replace('/','-',$a_umowa['numer_umowy']));
		}
		
		return isset($a_umowa['id_umowy']) ? $a_umowa['id_umowy'] : $id_umowy;
	}

}

?>