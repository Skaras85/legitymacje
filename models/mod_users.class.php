<?php

class mod_users extends db{
	
	//private static $encryption_type = 'bcrypt';	
	private static $encryption_type = 'MD5';
	
	public static function check_login($i_login, $i_msg)
	{
		if($i_login=='')
		{
			app::err($i_msg.'brak nazwy lokalu');
			return false;
		}
		/*
		if(hlp_validator::alfanum_strict($i_login)==false)
		{
			app::err($i_msg.lang::get('rejestracja-msg-login-zle-znaki',1));
			return false;
		}
*/
		return true;
	}

	public static function check_password($i_haslo,$i_haslo_repeat,$i_msg='',$edit=false)
	{
		if(!$edit && $i_haslo=='' && !session::who('admin'))
		{
			app::err($i_msg.'brak hasła');
			return false;
		}
		
		if(((!$edit && strlen($i_haslo)<8) || ($edit && strlen($i_haslo)>1 && strlen($i_haslo)<8)) && !session::who('admin') )
		{
			app::err($i_msg.'hasło jest za krótkie');
			return false;
		}
		
		if(!$edit && $i_haslo_repeat=='' && !session::who('admin'))
		{
			app::err($i_msg.'nie powtórzone hasła');
			return false;
		}
		
		if($i_haslo!=$i_haslo_repeat && !session::who('admin'))
		{
			app::err($i_msg.'wpisane hasła do siebie nie pasują');
			return false;
		}
		
		return true;
	}
	
	public static function check_email($i_email,$i_email_repeat, $i_msg='')
	{
		if($i_email=='' && !session::who('admin'))
		{
			app::err($i_msg.'nie podane emaila');
			return false;
		}
		
		if(!hlp_validator::email($i_email) && !session::who('admin'))
		{
			app::err($i_msg.view::$msg);
			return false;
		}
		
		if($i_email_repeat && $i_email!=$i_email_repeat)
		{
			app::err($i_msg.' podane adresy email nie pasują do siebie');
			return false;
		}
		
		return true;
	}
	
	public static function check_user_data($ia_user,$edit=false,$czy_subkoto=false,$czy_edycja_subkonta=false)
	{
		$kom = $edit ? "Błąd edycji danych, " : 'Nie zarejestrowano, ';
		
		if(//!self::check_login($ia_user['nazwa'],$kom) or 
		   !$czy_subkoto && !self::check_password($ia_user['haslo'],$ia_user['haslo_powtorzone'],$kom,$edit) or 
		   !$edit && !self::check_email($ia_user['email'],isset($ia_user['email_repeat']) ? $ia_user['email_repeat'] : false,$kom))
			return false;

		if(!$edit && (empty($ia_user['imie']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['imie'])))
		{
			app::err($kom.'nieprawidłowe imię');
			return false;
		}
		
		if(!$edit && (empty($ia_user['nazwisko']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['nazwisko'])))
		{
			app::err($kom.'nieprawidłowe nazwisko');
			return false;
		}
		
		if(empty($ia_user['telefon']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['telefon']))
		{
			app::err($kom.'nieprawidłowy telefon');
			return false;
		}
		
		if(!$czy_subkoto && !$czy_edycja_subkonta && (empty($ia_user['typ']) || !in_array($ia_user['typ'], array('placowka','agencja'))))
		{
			app::err($kom.'nieprawidłowy typ konta');
			return false;
		}
		if($ia_user['typ']=='agencja')
		{
			if((empty($ia_user['nazwa']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['nazwa'])))
			{
				app::err($kom.'nieprawidłowa nazwa');
				return false;
			}
	
			if((empty($ia_user['ulica']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['ulica'])))
			{
				app::err($kom.'nieprawidłowa ulica');
				return false;
			}
			
			if((empty($ia_user['miasto']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['miasto'])))
			{
				app::err($kom.'nieprawidłowe miasto');
				return false;
			}
			
			if((!empty($ia_user['kod_pocztowy']) && !hlp_validator::kod_pocztowy($ia_user['kod_pocztowy'])))
			{
				app::err($kom.'nieprawidłowy kod pocztowy');
				return false;
			}
			
			if((empty($ia_user['nip']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['nip'])))
			{
				app::err($kom.'nieprawidłowy NIP');
				return false;
			}
			
			if((empty($ia_user['wysylka_nazwa']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['wysylka_nazwa'])))
			{
				app::err($kom.'nieprawidłowa nazwa wysyłki');
				return false;
			}
	
			if((empty($ia_user['wysylka_adres']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['wysylka_adres'])))
			{
				app::err($kom.'nieprawidłowy adres wysyłki');
				return false;
			}
			
			if((empty($ia_user['wysylka_poczta']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['wysylka_poczta'])))
			{
				app::err($kom.'nieprawidłowe miasto wysyłki');
				return false;
			}
			
			if((!empty($ia_user['wysylka_kod_pocztowy']) && !hlp_validator::kod_pocztowy($ia_user['wysylka_kod_pocztowy'])))
			{
				app::err($kom.'nieprawidłowy kod pocztowy wysyłki');
				return false;
			}

			if((empty($ia_user['platnik_nazwa']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['platnik_nazwa'])))
			{
				app::err($kom.'nieprawidłowa nazwa wysyłki');
				return false;
			}
	
			if((empty($ia_user['platnik_adres']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['platnik_adres'])))
			{
				app::err($kom.'nieprawidłowy adres wysyłki');
				return false;
			}
			
			if(empty($ia_user['platnik_kod_pocztowy']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['platnik_kod_pocztowy']))
			{
				app::err($kom.'nieprawidłowe miasto wysyłki');
				return false;
			}
			
			if((empty($ia_user['platnik_poczta']) || preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$ia_user['platnik_poczta'])))
			{
				app::err($kom.'nieprawidłowe miasto wysyłki');
				return false;
			}
		}
		
		if(!$edit)
		{
			$id=self::get_one("SELECT 1 FROM users WHERE email='{$ia_user['email']}'");

			if($id>0)
			{
				app::err($kom.str_replace('{email}',$ia_user['email'],'adres {email} już jest zajęty'));
				return false;
			}
			/*
			$id=self::get_one("SELECT count(*) FROM users WHERE nazwa='{$ia_user['nazwa']}'");
			
			if($id>0)
			{
				app::err($kom.str_replace('{login}',$ia_user['login'],lang::get('rejestracja-msg-login-zajety',1)));
				return false;
			}*/
		}
		else
		{/*
			$id=self::get_one("SELECT 1 FROM users WHERE email='{$ia_user['email']}' AND id_users<>".session::get_id());
			
			if($id>0)
			{
				app::err($kom." adres email {$ia_user['email']} już jest w naszej bazie. Jeśli należy do Ciebie skorzystaj z opcji przypomnienia hasła lub skontaktuj się z nami.");
				return false;
			}*/
			/*
			$id=self::get_one("SELECT count(*) FROM users WHERE login='{$ia_user['login']}' AND id_users<>".session::get_id());
			
			if($id>0)
			{
				app::err($kom." login {$ia_user['email']} jest zajęty.");
				return false;
			}*/
	}

		return true;
	}

	public static function zarejestruj($ia_user,$czy_subkoto=false,$czy_konto_wewnetrzne=false,$czy_edycja_subkonta=false)
	{
		$_SESSION['form']['a_user']=$ia_user;

		if(!self::check_user_data($ia_user,false,$czy_subkoto,$czy_edycja_subkonta))
			return false;

		if(!$czy_konto_wewnetrzne && !$czy_subkoto)
		{
			if(self::$encryption_type=='MD5')
				$pass_encrypted = MD5($ia_user['haslo'].strtolower($ia_user['email']));
			elseif(self::$encryption_type=='bcrypt')
				$pass_encrypted = hlp_bcrypt::hash($ia_user['haslo'].strtolower($ia_user['email']));
			else
			{
				app::err($kom."błąd systemu: nieznany rodzaj szyfrowania hasła");
				return false;
			}
		}
		else
			$pass_encrypted = '';

		$user_uniq_id = hlp_functions::get_uniq_id();
		while(db::get_one("SELECT uniqid_users FROM users WHERE uniqid_users='$user_uniq_id'"))
			$user_uniq_id = hlp_functions::get_uniq_id();
		
		$a_dane = array("nazwa"=>$ia_user['nazwa'],
						 "haslo"=>$pass_encrypted,
						 "email"=>$ia_user['email'],
						 "miasto"=>!empty($ia_user['miasto']) ? $ia_user['miasto'] : '',
						 "telefon"=>$ia_user['telefon'],
						 "ulica"=>$ia_user['ulica'],
						 "kod_pocztowy"=>$ia_user['kod_pocztowy'],
						 "imie"=>$ia_user['imie'],
						 "nazwisko"=>$ia_user['nazwisko'],
						 "typ"=>!$czy_subkoto ? $ia_user['typ'] : 'placowka',
						 "nip"=>$ia_user['nip'],
						 "wysylka_nazwa"=>$ia_user['wysylka_nazwa'],
						 "wysylka_adres"=>$ia_user['wysylka_adres'],
						 "wysylka_poczta"=>$ia_user['wysylka_poczta'],
						 "wysylka_kod_pocztowy"=>$ia_user['wysylka_kod_pocztowy'],
						 "uwagi_dla_kuriera"=>$ia_user['uwagi_dla_kuriera'],
						 "platnik_nazwa"=>$ia_user['platnik_nazwa'],
						 "platnik_adres"=>$ia_user['platnik_adres'],
						 "platnik_kod_pocztowy"=>$ia_user['platnik_kod_pocztowy'],
						 "platnik_poczta"=>$ia_user['platnik_poczta'],
						 "uwagi_dla_kuriera"=>$ia_user['uwagi_dla_kuriera'],
						 "uniqid_users"=>$user_uniq_id,
						 "ip"=>hlp_functions::get_ip_address(),
						 "data_ost_ruchu"=>"NOW()",
						 "data_dodania"=>"NOW()",
						 "parent_id"=>$czy_subkoto ? session::get_id() : 0,
						 "czy_aktywny"=>$czy_konto_wewnetrzne || isset($ia_user['id_users_old']) ? 1 : 0,
						 "czy_newsletter"=>isset($ia_user['czy_newsletter']) ? 1 : 0,
						 "czy_konto_wewnetrzne"=>$czy_konto_wewnetrzne ? 1 : 0,
						 
						 );
						 
		 if(isset($ia_user['id_users_old']))
		 	$a_dane=array_merge($a_dane,array('id_users'=>$ia_user['id_users_old']));
										 
		 $id_users=self::insert("users",$a_dane);

		if($id_users===0)
		{
			app::err("Nie zarejestrowano, błąd bazy danych");
			return false;
		}
		
		mkdir("images/users/$id_users/messages");
		
		app::ok("Zarejestrowano");
		unset($_SESSION['form']['a_user']);
		return $id_users;
	}
	
	public static function edytuj_dane($ia_user,$czy_subkonto,$czy_edycja_subkonta=false)
	{
		$_SESSION['form']['a_user']=$ia_user;
		$kom='Nie zapisano danych, ';
		$id_users = $czy_subkonto ? $ia_user['id_users'] : session::get_id();

		if(!self::check_user_data($ia_user,true,$czy_subkonto,$czy_edycja_subkonta))
			return false;

		if(!empty($ia_user['haslo']))
		{
			if(session::get_user('rodzaj')!='admin')
			{
				if(self::$encryption_type=='bcrypt')
				{
					$haslo = db::get_one("SELECT haslo FROM users WHERE email='{$ia_user['email_old']}'");
					$haslo_crypt=$ia_user['haslo_old'].strtolower($ia_user['email_old']);
					$is_pass=hlp_bcrypt::verify($haslo_crypt,$haslo);
					
					if(!empty($haslo) && $is_pass==false)
					{
						app::err($kom."nieprawidłowe stare hasło");
						return false;
					}
				}
				elseif(self::$encryption_type=='MD5')
				{
					if(!session::get('uniqid_users'))
					{
						$a_user=self::get_row("SELECT * FROM users WHERE email='".$ia_user['email_old']."' 
										AND haslo='".MD5($ia_user['haslo_old'].strtolower($ia_user['email_old']))."'");
		
						if (!$a_user)
						{
							app::err($msg."nieprawidłowe stare hasło");
							return false;
						}
					}
				}
				else
				{
					app::err("Błąd systemu: nieznany rodzaj szyfrowania hasła");
					return false;
				}
			}
		}

		$a_dane = array(//"nazwa"=>$ia_user['nazwa'],
						 //"email"=>$ia_user['email'],
						 //"miasto"=>$ia_user['miasto'],
						 "telefon"=>$ia_user['telefon'],
						 //"ulica"=>$ia_user['ulica'],
						 //"kod_pocztowy"=>$ia_user['kod_pocztowy'],
						 //"imie"=>$ia_user['imie'],
						 //"nazwisko"=>$ia_user['nazwisko'],
						 //"imie"=>$ia_user['imie'],
						 //"nip"=>$ia_user['nip'],
						 "czy_aktywny"=>1,
						 "wysylka_nazwa"=>$ia_user['wysylka_nazwa'],
						 "wysylka_adres"=>$ia_user['wysylka_adres'],
						 "wysylka_poczta"=>$ia_user['wysylka_poczta'],
						 "wysylka_kod_pocztowy"=>$ia_user['wysylka_kod_pocztowy'],
						 "uwagi_dla_kuriera"=>$ia_user['uwagi_dla_kuriera'],
						 "platnik_nazwa"=>$ia_user['platnik_nazwa'],
						 "platnik_adres"=>$ia_user['platnik_adres'],
						 "platnik_kod_pocztowy"=>$ia_user['platnik_kod_pocztowy'],
						 "platnik_poczta"=>$ia_user['platnik_poczta'],
						 "uwagi_dla_kuriera"=>$ia_user['uwagi_dla_kuriera']
						 );

		if(empty($ia_user['haslo']))
		{
			$wynik=self::update('users','id_users='.$id_users,$a_dane);
		}
		elseif($ia_user['haslo']!='')
		{
			if(self::$encryption_type=='bcrypt')
			{
				$a_haslo=array('haslo'=>hlp_bcrypt::hash($ia_user['haslo'].strtolower($ia_user['email'])));
				$a_dane=array_merge($a_dane,$a_haslo);
			}
			elseif(self::$encryption_type=='MD5')
				$a_dane=array_merge($a_dane,array('haslo'=>MD5($ia_user['haslo'].strtolower($ia_user['email_old'])), 'czy_haslo_losowe'=>0));
			
			$wynik=self::update('users','id_users='.$id_users,$a_dane);
		}

		if($wynik===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}
		else
		{
			app::ok('Poprawnie zapisano dane');
			unset($_SESSION['form']['a_user']);
			return true;
		}
	}
	
	public static function zaloguj($ia_user)
	{
		$msg='Nie zalogowano, ';

		if(count($ia_user)<2)
			return app::err($msg."brak wszystkich danych");
		
		if(empty($ia_user['email']))
			return app::err($msg.'brak adresu email');
		
		if(empty($ia_user['haslo']))
			return app::err($msg.'brak hasła');
		
		if(strpos($ia_user['email'],'@') && !hlp_validator::email($ia_user['email']) || !strpos($ia_user['email'],'@') && !hlp_validator::id($ia_user['email']))
			return app::err($msg.'nieprawidłowy email lub ID');
		
		//zabezpieczenie przed session fixation i session hijacking
		if (!isset($_SESSION['sprawdz']))
		{
		 	@session_regenerate_id();
			$_SESSION[session::$tab]['sprawdz'] = true;
			$_SESSION[session::$tab]['adres_ip'] = $_SERVER['REMOTE_ADDR'];
		}
		
		if($_SESSION[session::$tab]['adres_ip'] !== $_SERVER['REMOTE_ADDR'])
			return app::err('Błąd: Próba przejęcia sesji');
		
		if(self::$encryption_type=='MD5')
		{
			$a_user=self::get_row("SELECT * FROM users WHERE email='".$ia_user['email']."' 
									AND haslo='".MD5($ia_user['haslo'].strtolower($ia_user['email']))."'");

			//jeżeli nie udało się zalogować
			if (!is_array($a_user) or @count($a_user)<1)
			{
				//sprawdzamy czy email był poprawny
				$logi_table = 'logi_'.date("Y_m");
				$id_users = db::get_one("SELECT id_users FROM users WHERE email='{$ia_user['email']}'");
				
				if($id_users)
				{
					mod_logi::dodaj('nieprawidłowe hasło logowania',$id_users,$ia_user['email']);
					//$nieudane_logowania = db::get_one("SELECT COUNT(*) FROM $logi_table WHERE id_obce=".$id_users." AND DAY(data)=".date('d')." AND MONTH(data)=".date('m')." AND YEAR(data)=".date('Y')." AND akcja='nieprawidłowe hasło logowania'");
					session::set('nieudane_logowania', session::get('nieudane_logowania')+1);

					if(session::get('nieudane_logowania')>=5)
					{
						db::update('users', 'id_users='.$id_users, array('czy_konto_zablokowane'=>1));
						//mailer::add_address('mariusz@loca.pl');
						mailer::add_address($ia_user['email']);
						$tresc = "$nieudane_logowania razy wpisano błędne hasło dla adresu {$ia_user['email']}. Konto zostało zablokowane. Aby się zalogować wygeneruj nowe hasło.";
						mailer::send('Blokada konta',$tresc);
						
						return app::err($msg.$tresc);
					}
				}
				else
				{
					mod_logi::dodaj('nieudane logowanie',0,$ia_user['email']);
					$nieudane_logowania = db::get_one("SELECT COUNT(*) FROM $logi_table WHERE ip='".hlp_functions::get_ip_address()."' AND DAY(data)=".date('d')." AND MONTH(data)=".date('m')." AND YEAR(data)=".date('Y')." AND akcja='nieudane logowanie'");
					
					if($nieudane_logowania>=10)
					{
						mailer::add_address('mariusz@loca.pl');
						$tresc = "$nieudane_logowania prób logowania na email {$ia_user['email']} z adresu ip ".hlp_functions::get_ip_address();
						mailer::send('10 nieudanych prób logowania',$tresc);
					}
				}
				
				return app::err($msg.'nieprawidłowy email lub hasło');
			}
		}
		elseif(self::$encryption_type=='bcrypt')
		{
			$a_user = db::get_row("SELECT * FROM users WHERE email='{$ia_user['email']}'");
			$haslo_crypt=$ia_user['haslo'].strtolower($ia_user['email']);
			$is_pass=hlp_bcrypt::verify($haslo_crypt,$a_user['haslo']);

			if($is_pass==false)
				return app::err($msg.'nieprawidłowy email lub hasło');
		}
		else
			return app::err("Błąd systemu: nieznana metoda szyfrowania hasła");
		
		if($a_user['czy_zbanowany']==1)
		{
			$ban_msg = 'konto zbanowane';
			
			if($a_user['powod_banu']!='')
				$ban_msg.='<br>Powód: '.$a_user['powod_banu'];
			
			return app::err($ban_msg);
		}
		
		if($a_user['czy_konto_zablokowane']==1)
			return app::err("Twoje konto zostało zablokowane przez zbyt dużą ilość razy wpisania złego hasła. Wygeneruj nowe hasło, aby się zalogować.");

		if($a_user['czy_aktywny']==0)
			return app::err("Twoje konto nie jest aktywne. Aby je aktywować <a href='".app::base_url()."users/wyslij-link-aktywacyjny/id/".$a_user['uniqid_users']."'>wyślij link aktywacyjny</a>");

		$ip = hlp_functions::get_ip_address();

		if($a_user['rodzaj']=='admin' && $ip!='46.171.3.114' && $ip!='::1' && $ip!='84.10.187.235' && $ip!='94.130.234.6')
			return app::err('Brak dostępu do konta');
		
		$_SESSION[session::$tab]=$a_user;
		$_SESSION[session::$tab]['is_logged']=true;
		session::save_last_active_date();

		if(isset($ia_user['czy_pamietac']))
	    {
		  	$uniq_id=self::get_one("SELECT uniqid_users FROM users WHERE id_users=".$a_user['id_users']);
	      	setcookie("framework_uniqid", $uniq_id,time()+2592000);
	    }
		
		return true;
	}

	public static function zaloguj_fb($email,$imie)
	{
		if(session::is_logged())
			return false;
		
		if(!hlp_validator::email($email))
		{
			app::err('Nie zalogowano, '.view::message());
			return false;
		}
		
		$a_user=db::get_row("SELECT * FROM users WHERE email='$email'");

		$a_login = explode(' ',$imie);

		//jezeli takiego usera nie ma
		if(!$a_user)
		{
			$user_uniq_id = hlp_functions::get_uniq_id();
			while(db::get_one("SELECT uniqid_users FROM users WHERE uniqid_users='$user_uniq_id'"))
				$user_uniq_id = hlp_functions::get_uniq_id();
			
			$referer = isset($_COOKIE['referer']) ? $_COOKIE['referer'] : '';	
			
			db::insert('users',array("login"=>$a_login[0],
										"sludge"=>hlp_functions::make_sludge($a_login[0]),
										 "email"=>$email,
										 "rodzaj"=>'Grafik',
										 "uniqid_users"=>$user_uniq_id,
										 "referer"=>$referer,
										 "ip"=>hlp_functions::get_ip_address(),
										 "data_ost_ruchu"=>"NOW()",
										 "data_dodania"=>"NOW()",
										 "register_source"=>"fb"));
			$a_user=db::get_row("SELECT * FROM users WHERE email='$email'");
		}
		
		$_SESSION[session::$tab]=$a_user;
		$_SESSION[session::$tab]['is_logged']=true;
		app::ok(lang::get('logowanie-fb-sukces',1));
		return true;
	}

	public static function zaloguj_na_usera($id_users,$id_pracownika)
	{
		$a_user = mod_users::get_user($id_users);
		
		if(!$a_user)
		{
			app::err('brak takiego użytkownika w bazie');
			return false;
		}
		
		$a_user['imie_i_nazwisko'] = $a_user['imie'].' '.$a_user['nazwisko'];
		/*$a_user['zmiana_danych_konta_wewnetrznego'] = true;*/
		$_SESSION[session::$tab]=$a_user;
		$_SESSION[session::$tab]['is_logged']=true;
		session::set('czy_zdalny',true);
		//session::set('id_placowki',$id_placowki);
		session::set('id_pracownika',$id_pracownika);

		return app::ok();
	}

	public static function autologin()
	{
		/*if(!session::is_logged() && isset($_COOKIE["framework_uniqid"]))
		{
			$wynik=self::get_row("SELECT * FROM users WHERE uniqid_users='".$_COOKIE["framework_uniqid"]."'");
			
			if($wynik)	
			{
				$_SESSION[session::$tab]=$wynik;
				$_SESSION[session::$tab]['is_logged']=true;
				//session::save_last_act_date();
			}
		}*/
	}
	
	public static function generuj_haslo($haslo,$email)
	{
		if(self::$encryption_type=='MD5')
			return MD5($haslo.strtolower($email));
		elseif(self::$encryption_type=='bcrypt')
			return hlp_bcrypt::hash($haslo.strtolower($email));
		else
			return app::err($kom."błąd systemu: nieznany rodzaj szyfrowania hasła");
	}
	
	public static function generate_password($i_email)
	{
		return hlp_functions::get_uniq_id();
	}
	
	public static function update_and_send_password($i_password,$i_email)
	{
		$email = db::get_one("SELECT email FROM users WHERE email='$i_email'");
		
		if(!$email)
		{
			app::err("Brak adresu w bazie");
			return false;
		}
		
		if(self::$encryption_type=='bcrypt')
			$haslo_crypt=hlp_bcrypt::hash($i_password.strtolower($i_email));
		elseif(self::$encryption_type=='MD5')
			$haslo_crypt=MD5($i_password.strtolower($i_email));
		else
		{
			app::err("Błąd systemu: nieznany rodzaj szyfrowania hasła");
			return false;
		}
		db::update('users',"email='$i_email'",array('haslo'=>$haslo_crypt,'czy_haslo_losowe'=>1,'czy_konto_zablokowane'=>0));
		
		mod_users::wyslij_maila($i_email,5,false,false,array('haslo'=>$i_password));
		session::delete('nieudane_logowania');

		app::ok('Nowe hasło zostało wysłane na podany adres e-mail');
	}
	
	public static function get_avatar($uniqid_users,$type)
	{
		$a_files = glob("images/users/$uniqid_users/avatars/$type.*");
		
		if($a_files)
			return $a_files[0];
		else
		{
			if($type=='small')
				return 'images/site/no-avatar-icon-small.jpg';
			else
				return 'images/site/no-avatar-icon.jpg';
		}
	}
	
	public static function get_user($id,$type='id_users')
	{
		if($type=='id_users' && !hlp_validator::id($id))
		{
			app::err('Nieprawidłowe id');
			return false;
		}
		
		if($type=='uniqid_users' && !hlp_validator::alfanum_hc($id))
		{
			app::err('Nieprawidłowe id');
			return false;
		}

		$a_user = db::get_row("SELECT * FROM users WHERE $type=$id");

		return $a_user;
	}
	
	public static function wyslij_link_aktywacyjny($id_users)
	{
		$email = db::get_one("SELECT email FROM users WHERE id_users={$id_users}");
		$hash = uniqid();
		db::update('users', "id_users={$id_users}", array('token_maila_aktywacja_konta'=>$hash));
		
		mod_users::wyslij_maila($email,1,'','',array('token_maila_aktywacja_konta'=>$hash));
	}
	
	public static function wyslij_maila($email,$id_sites=false,$text='',$title='',$_data=false)
	{
		if(!$email)
			return app::err('Brak adresu email');
		
		if($id_sites)
		{
			$a_strona = db::get_by_id('sites',$id_sites);
			$a_zalaczniki = array();
			
			if($id_sites==1)
				$a_strona['text'] = str_replace('{link}',"<a href='".app::base_url()."users/aktywuj-konto/hash/{$_data['token_maila_aktywacja_konta']}'>link</a>",$a_strona['text']);
			elseif($id_sites==2)
			{
				$a_strona['text'] = str_replace('{szczegoly}',$_data['szczegoly'],$a_strona['text']);
			}
			elseif($id_sites==3)
			{
				$a_strona['text'] = str_replace('{karty}',$_data['cena_legitymacji'],$a_strona['text']);
				$a_strona['text'] = str_replace('{przesylka}',$_data['cena_przesylki'],$a_strona['text']);
			}
			elseif($id_sites==5)
			{
				//$a_strona['text'] = str_replace('{link}',"<a href='".app::base_url()."'>zaloguj</a>",$a_strona['text']);
				//$a_strona['text'] = str_replace('{haslo}',$_data['haslo'],$a_strona['text']);

				$uniqid = uniqid();
				db::update('users','id_users='.$_data['id_users'],array('token_resetu_hasla'=>$uniqid));

				$link = app::base_url()."users/formularz_resetu_hasla/hash/{$uniqid}";
				$a_strona['text'] = str_replace('[link]',"<a href='$link'>$link</a>",$a_strona['text']);
			}
			elseif($id_sites==6)
			{
				$a_strona['text'] = str_replace('{szczegoly}',$_data['szczegoly'],$a_strona['text']);
			}
			elseif($id_sites==12)
			{
				$link = "<a href='".app::base_url()."users/aktywuj_konto_wewnetrzne/param/{$_data['param']}' target='_blank'>link</a>";
				$a_strona['text'] = str_replace('{link}',$link,$a_strona['text']);
				//mailer::add_attachment($_data['url'],'formularz_aktywacyjny_podpipsany.pdf','base64','application/pdf');
			}
			elseif($id_sites==21)
			{
				$a_strona['text'] = str_replace('{nazwa}',db::get_one("SELECT nazwa FROM karty WHERE id_karty=".$_data['id_karty']),$a_strona['text']);
				$link = "<a href='".app::base_url()."legitymacje/pobierz_umowe_aktywacji_karty/id_placowki/{$_data['id_placowki']}/id_karty/{$_data['id_karty']}' target='_blank'>link</a>";
				$a_strona['text'] = str_replace('{link}',$link,$a_strona['text']);
			}
			elseif($id_sites==28)
			{
				$a_strona['text'] = str_replace('{imie}',session::get_user('imie'),$a_strona['text']);
				$a_strona['text'] = str_replace('{nazwisko}',session::get_user('nazwisko'),$a_strona['text']);
				
				$url_zalacznika = "images/placowki/".session::get('id_placowki').'/umowy/'.str_replace('/','-',$_data['numer_umowy']).'.pdf';
				$nazwa_zalacznika = str_replace('/','-',$_data['numer_umowy']).'.pdf';
				mailer::add_attachment($url_zalacznika, $nazwa_zalacznika,'base64','application/pdf');
				$a_zalaczniki[$nazwa_zalacznika] = $url_zalacznika;
			}
			elseif($id_sites==39)
			{
				$a_strona['text'] = str_replace('{link}',"<a href='https://legitymacje.loca.pl/users/formularz-uzytkownika/uniqid/{$_data['uniqid']}'>link</a>",$a_strona['text']);
				$a_strona['text'] = str_replace('{imie_nazwisko}',$_data['imie_nazwisko'],$a_strona['text']);
			}
			elseif($id_sites==40)
			{
				$a_strona['text'] = str_replace('{imie}',$_data['imie'],$a_strona['text']);
				$a_strona['text'] = str_replace('{nazwisko}',$_data['nazwisko'],$a_strona['text']);
				$a_strona['text'] = str_replace('{telefon}',$_data['telefon'],$a_strona['text']);
				$a_strona['text'] = str_replace('{ip}',hlp_functions::get_ip_address(),$a_strona['text']);
				$a_strona['text'] = str_replace('{przegladarka}',$_SERVER['HTTP_USER_AGENT'],$a_strona['text']);
				$a_strona['text'] = str_replace('{godzina}',date('Y-m-d H:i:s'),$a_strona['text']);
				$a_strona['text'] = str_replace('{link}',"<a href='".app::base_url()."users/aktywuj-konto/id/{$_data['uniqid_users']}'>".app::base_url()."users/aktywuj-konto/id/{$_data['uniqid_users']}</a>",$a_strona['text']);
			}
			elseif($id_sites==41)
			{
				$a_strona['text'] = str_replace('{data_zawarcia}',substr($_data['a_umowa']['data_potwierdzenia'],0,10),$a_strona['text']);
				$a_strona['text'] = str_replace('{rodzaj_legitymacji}',$_data['a_umowa']['id_umowy_typy']==1 ? 'Legitymacja nauczyciela' : 'E-legitymacja szkolna',$a_strona['text']);
				$a_strona['text'] = str_replace('{okres_obowiazywania}',$_data['a_umowa']['okres_obowiazywania']=='0000-00-00' ? 'na czas nieokreślony' : 'na czas określony do '.$_data['a_umowa']['okres_obowiazywania'].' r.',$a_strona['text']);
				$a_strona['text'] = str_replace('{email_naruszenia}',$_data['a_umowa']['email_naruszenia'],$a_strona['text']);
				$a_strona['text'] = str_replace('{email}',$_data['a_umowa']['email'],$a_strona['text']);
				$a_strona['text'] = str_replace('{numer_umowy}',$_data['a_umowa']['numer_umowy'],$a_strona['text']);
				$a_strona['text'] = str_replace('{data_potwierdzenia}',$_data['a_umowa']['data_potwierdzenia'],$a_strona['text']);
			}
			elseif($id_sites==44)
			{
				$a_strona['title'] = str_replace(' - admin','',$a_strona['title']);
			}
			elseif($id_sites==45)
			{
				$a_strona['text'] = str_replace('{id_placowki}',$_data['id_placowki'],$a_strona['text']);
				$nazwa_zalacznika = 'formularz_aktywacyjny.pdf';
				$url_zalacznika = $_data['url'];
				mailer::add_attachment($url_zalacznika,$nazwa_zalacznika,'base64','application/pdf');
				$a_zalaczniki[$nazwa_zalacznika] = $url_zalacznika;
			}
			elseif($id_sites==49)
			{
				$a_strona['text'] = str_replace('{link}','<a href="'.$_data['link'].'">link</a>',$a_strona['text']);
			}
			elseif($id_sites==50)
			{
				$a_strona['text'] = str_replace('{numer_zamowienia}',$_data['numer_zamowienia'],$a_strona['text']);
			}
			elseif($id_sites==92)
			{
				$link = "<a href='".app::base_url()."users/usun_konto/param/{$_data['param']}' target='_blank'>usuń konto</a>";
				$a_strona['text'] = str_replace('{link}',$link,$a_strona['text']);
			}
			elseif($id_sites==93)
			{
				$a_strona['text'] = str_replace('{email}',$_data['email'],$a_strona['text']);
			}
			elseif($id_sites==94)
			{
				$link = "<a href='https://legitymacje.loca.pl/umowy/formularz_umowy_krok1/hash/{$_data['hash']}' target='_blank'>generuj umowę</a>";
				$a_strona['text'] = str_replace('{link}',$link,$a_strona['text']);
			}
			elseif($id_sites==108)
			{
				$a_strona['text'] = str_replace('{ip}',hlp_functions::get_ip_address(),$a_strona['text']);
				$a_strona['text'] = str_replace('{data}',date('Y-m-d H:i:s'),$a_strona['text']);
			
			}

			$text = $a_strona['text'];
			$title = $a_strona['title'];
		}
		
		mailer::add_address($email);
		
		$a_wiadomosc['temat'] = $title;
		$a_wiadomosc['tresc'] = $text;
		$a_wiadomosc['id_nadawcy'] = 0;
		$a_wiadomosc['id_adresata'] = db::get_one("SELECT id_users FROM users WHERE email='$email'");
		$a_wiadomosc['status'] = 'wyslana';

		mod_wiadomosci::nowa_wiadomosc($a_wiadomosc,$a_zalaczniki);
		db::update('users','id_users='.$a_wiadomosc['id_adresata'],array('czy_nowe_wiadomosci'=>1));
		
		$text = '<!DOCTYPE HTML><html><head><meta charset="utf-8"></head>'.$text.'</html>';
		mailer::send($title,$text,false,true,strip_tags($text));
		
		return $text;
	}
	
	public static function get_subkonta($id_users)
	{
		return db::get_many("SELECT * FROM users WHERE parent_id=$id_users");
	}
	
	public static function sprawdz_dostep_rodzica($id_users)
	{
		return db::get_one("SELECT parent_id FROM users WHERE id_users=$id_users")==session::get_id();
	}
	
	public static function get_cennik_wysylki()
	{
		return db::get_one("SELECT id_cenniki FROM users WHERE id_users=".session::get_id());
	}
	
	public static function get_pracownicy()
	{
		return db::get_many("SELECT * FROM users WHERE rodzaj='admin'");
	}
	
	public static function get_users($czy_konto_wewnetrzne=false,$search=false)
	{
		$sql_search = $search ? " AND (email LIKE '%$search%' OR imie LIKE '%$search%' OR nazwisko LIKE '%$search%')" : '';
		return db::get_many("SELECT users.*, (SELECT COUNT(1) FROM users_placowki WHERE users.id_users=users_placowki.id_users) as ilosc_placowek FROM users WHERE 1=1 $sql_search");
	}
	
	public static function get_umowa($id_sites)
	{
		$sql_typ = session::get_user('typ')=='placowka' ? "id_placowki=".session::get('id_placowki') : "id_users=".session::get_id();
		return db::get_row("SELECT * FROM umowy WHERE id_sites=$id_sites AND $sql_typ");
	}
	
	public static function get_umowy($typ_usera)
	{
		$ids = $typ_usera=='placowka' ? '24,25,26,27,37' : '29,30';
		$a_strony = db::get_many("SELECT * FROM sites WHERE id_sites IN($ids)");
		
		foreach($a_strony as $index=>$a_strona)
		{
			$a_strony[$index]['a_umowa'] = self::get_umowa($a_strona['id_sites']);
		}
		
		return $a_strony;
	}
	
	public static function get_umowy_placowki($id_placowki)
	{
		return db::get_many("SELECT * FROM umowy2 WHERE id_placowki=$id_placowki");
	}
	
	public static function czy_potwierdzone_umowy()
	{
		return self::czy_potwierdzona_umowa(29) && self::czy_potwierdzona_umowa(30);
	}
	
	public static function tworz_nr_umowy($id_sites)
	{
		$numer = db::get_one("SELECT COUNT(*) FROM umowy WHERE id_sites=$id_sites")+1;
		
		if($numer<10)
			$numer = '00'.$numer;
		elseif($numer<100)
			$numer = '0'.$numer;
		
		$rok = date('Y');
		if($id_sites==24)
			return "LN/UP/$numer/$rok";
		elseif($id_sites==25)
			return "LN/UW/$numer/$rok";
		elseif($id_sites==26)
			return "LS/UP/$numer/$rok";
		elseif($id_sites==27)
			return "LS/UW/$numer/$rok";
		elseif($id_sites==29)
			return "AGENT/UW/$numer/$rok";
		elseif($id_sites==30)
			return "AGENT/UP/$numer/$rok";
	}
	
	public static function czy_potwierdzona_umowa($id_umowy)
	{
		return self::get_umowa($id_umowy);
	}
	
	public static function formularz_aktywacyjny_pdf($a_placowka,$a_dokument)
	{
		view::add('a_placowka',$a_placowka);
		view::add('a_dokument',$a_dokument);
		view::add('a_user',db::get_by_id('users',$a_placowka['id_users']));
		view::add('a_text', db::get_by_id('sites', 109));
		$html = view::display('placowki/formularz_aktywacyjny_pdf.tpl',true,true);
		$url = "images/placowki/{$a_placowka['id_placowki']}/form_aktywacyjny_{$a_placowka['uniqid_placowki']}";
 		file_put_contents("{$url}.html", $html);
 		system("/usr/local/bin/wkhtmltopdf {$url}.html {$url}.pdf");
		unlink("{$url}.html");
	}
	
	public static function zapisz_wyslanie_formularza($formularz,$text)
	{
		db::insert('logi_formularzy',array('typ_formularza'=>$formularz,
										   'data_wyslania'=>'NOW()',
										   'id_users'=>session::get_id(),
										   'id_pracownika'=>session::get('id_pracownika'),
										   'text'=>$text,
										   'status'=>'oczekuje'));
	}
	
	public static function get_formularze_usera($id_users)
	{
		return db::get_many("SELECT logi_formularzy.*,imie, nazwisko FROM logi_formularzy JOIN users ON users.id_users=id_pracownika WHERE logi_formularzy.id_users=$id_users");
	}
	
	public static function czy_formularze_zaakceptowane($id_users)
	{
		return db::get_one("SELECT 1 FROM logi_formularzy WHERE status='oczekuje' AND id_users=".$id_users);
	}
	
	public static function get_wybrane_grupy($rodzaj)
	{
		$wybrane_grupy = false;
		$a_wybrane_grupy = false;
		$pagination = '';
		if(isset($_REQUEST['a_'.$rodzaj]))
		{
			if(is_array($_REQUEST['a_'.$rodzaj]))
			{
				$wybrane_grupy = implode(',',array_keys($_REQUEST['a_'.$rodzaj]));
				$a_wybrane_grupy = $_REQUEST['a_'.$rodzaj];
			}
			else
			{
				$wybrane_grupy = $_REQUEST['a_'.$rodzaj];
				$a_wybrane_grupy = array_flip(explode(',',$wybrane_grupy));
			}
		}
		view::add('a_wybrane_'.$rodzaj,$a_wybrane_grupy);
		if(is_array($a_wybrane_grupy))
			$pagination = "/a_$rodzaj/".$wybrane_grupy;
		
		$a_zwrotka['wybrane_'.$rodzaj] = $wybrane_grupy;
		$a_zwrotka['pagination'] = $pagination;

		return $a_zwrotka;
	}

}

?>