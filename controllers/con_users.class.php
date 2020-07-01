<?php
class con_users extends controllers_parent{
	
	public static $default_action = 'profil';
	
	public static function blokuj_ip()
	{
		if(hlp_functions::get_ip_address()=='31.179.250.162')
		{
			app::err('Twój adres IP jest zablokowany');
			view::message();
		}
		else
			return true;
	}
	
	public static function formularz_logowania()
	{
		self::blokuj_ip();
		head::nofollow();

		view::display();
	}
	/*
	public static function wyslij_nowe_haslo()
	{
		if(!hlp_validator::email($_POST['email']))
		{
			app::err('Nieprawidłowy adres email');
			view::message();
		}

		head::nofollow();
		
		if(db::get_one("SELECT czy_konto_wewnetrzne FROM users WHERE email='{$_POST['email']}'"))
		{
			app::err('Takie konto istnieje w systemie, ale jest kontem wewnętrznym. Prosimy o kontakt telefoniczny w celu uzyskania dostępu - tel. 23 696 90 00 ');
			view::message();
		}
		else
		{
			$new_password = mod_users::generate_password($_POST['email']);
			mod_users::update_and_send_password($new_password,$_POST['email']);
			
			view::message();
		}
	}
	*/
	
	public static function wyslij_nowe_haslo()
	{
		if(!hlp_validator::email($_POST['email']))
		{
			app::err('Nieprawidłowy email');
			view::message();
		}
		
		$a_user = db::get_row("SELECT id_users,email FROM users WHERE email='{$_POST['email']}'");

		if(!$a_user)
		{
			app::err('Brak użytkownika w bazie');
			view::message();
		}

		mod_users::wyslij_maila($a_user['email'],5, false, false, array('id_users'=>$a_user['id_users']));

		app::ok('Na Twojego maila wysłaliśmy link resetujący hasło');
		view::message();
	}

	public static function formularz_resetu_hasla()
	{
		if(empty($_GET['hash']) || !hlp_validator::alfanum_hc($_GET['hash']))
		{
			app::err('Nieprawidłowy kod resetu');
			view::message();
		}
		
		$id_users = db::get_one("SELECT id_users FROM users WHERE token_resetu_hasla='{$_GET['hash']}'");
		
		if(!$id_users)
		{
			app::err('Kod resetu hasła nieaktywny');
			view::message();
		}

		view::add('email', db::get_one("SELECT email FROM users WHERE id_users=$id_users"));
		session::set('reset_hasla_id_users',$id_users);
		view::display();
	}
	
	public static function resetuj_haslo()
	{
		if(!session::get('reset_hasla_id_users'))
		{
			app::err('Sesja wygasła, kliknij w link z maila raz jeszcze');
			view::message();
		}
		
		if(empty($_POST['haslo']))
		{
			app::err('Musisz podać hasło');
			view::message();
		}
		
		if(empty($_POST['haslo_repeat']))
		{
			app::err('Musisz powtórzyć hasło');
			view::message();
		}
		
		if($_POST['haslo']!=$_POST['haslo_repeat'])
		{
			app::err('Wpisane hasła nie pasują do siebie');
			view::message();
		}

		$email = db::get_one("SELECT email FROM users WHERE id_users=".session::get('reset_hasla_id_users'));

		$pass_encrypted = mod_users::generuj_haslo($_POST['haslo'], $email);
		db::update('users','id_users='.session::get('reset_hasla_id_users'),array('haslo'=>$pass_encrypted,'token_resetu_hasla'=>''));
		app::ok('Hasła zostało zresetowane, możesz się zalogować');
		view::redirect();
	}
	
	public static function formularz_uzytkownika()
	{
		if(session::is_logged() && empty($_GET['konto-wewnetrzne']))
		{
			if(isset($_GET['uniqid']))
			{
				app::ok('Już aktywowałeś swoje subkonto');
				view::message();
			}
			
			if((!isset($_GET['id']) || !session::who('admin')) && empty($_GET['czy_subkonto']))
				$id = session::get_id();
			else
			{
				$id = isset($_GET['id']) ? $_GET['id'] : false;
			}

			if($id && !session::who('admin') && $id!=session::get_id() && !mod_users::sprawdz_dostep_rodzica($id))
			{
				app::err('Brak dostępu');
				view::message();
			}
			
			if($id && !hlp_validator::id($id))
			{
				app::err('Nieprawidłowy numer użytkownika');
				view::message();
			}
	
			if($id)
			{
				$a_user = mod_users::get_user($id);
				if(!$a_user)
				{
					app::err('Brak użytkownika o podanym numerze');
					view::message();
				}
				
				view::add('czy_ma_placowki', mod_placowki::get_placowki_usera($id));
				view::add('a_user',$a_user);
			}
			
			if(isset($_GET['czy_subkonto']))
			{
				view::add('czy_subkonto',1);
				view::add('a_placowki',mod_placowki::get_placowki_usera(session::get_id()));
				view::add('a_placowki_usera',mod_placowki::get_dostepne_placowki_usera($id));
			}
		}

		if(isset($_GET['uniqid']) && hlp_validator::id($_GET['uniqid']))
		{
			$a_user = mod_users::get_user($_GET['uniqid'],'uniqid_users');
			
			if(!$a_user)
			{
				app::err('Brak użytkownika o podanym numerze');
				view::message();
			}
			
			$_SESSION[session::$tab]['id_users'] = $a_user['id_users'];
			session::set('uniqid_users',$_GET['uniqid']);
			
			if(!empty($a_user['parent_id']))
				view::add('czy_edycja_subkonta',1);

			view::add('a_user',$a_user);
		}
		
		if(session::who('admin') && !empty($_GET['konto-wewnetrzne']))
			view::add('konto_wewnetrzne',1);
			
		if(session::get_user('czy_przenoszenie'))
			view::add('czy_przenoszenie',1);
		
		view::display("users/form_users.tpl");
	}
	
	public static function zarejestruj()
	{
		$a_user=$_POST['a_user'];
		$_SESSION['form']['a_user']=$a_user;
		
		if(empty($_POST['konto_wewnetrzne']))
		{
			$captcha=$_POST['g-recaptcha-response'];
			$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdhOFcUAAAAAA6VlxJAc0eY-qUpowGCJps6JQ8i&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		/*
			if(!isset($_POST['czy_subkonto']) && strpos($response,'true')===false)
			{
				app::err('Nie zarejestrowano, nie zweryfikowałeś, że jesteś człowiekiem');
				view::redirect('users/formularz-uzytkownika');
				exit;
			}
		*/
			if(empty($_POST['regulamin']) && empty($_POST['czy_subkonto']))
			{
				app::err('Nie zarejestrowano, musisz zaakceptować regulamin');
				view::redirect('users/formularz-uzytkownika');
				exit;
			}
		}
		
		$id_users = mod_users::zarejestruj($a_user,isset($_POST['czy_subkonto']),isset($_POST['konto_wewnetrzne']));
		$a_data = db::get_row("SELECT email,uniqid_users FROM users WHERE id_users=$id_users");
		$a_user = array_merge($a_user,$a_data);
		
		mod_logi::dodaj('rejestracja', $id_users);
		
		if(!empty($_POST['czy_subkonto']) && !empty($_POST['a_placowki']))
		{
			foreach($_POST['a_placowki'] as $id_placowki=>$asd)
			{
				mod_placowki::add_users_to_placowka($id_users,$id_placowki,0);
			}
		}

		if(!app::get_result())
		{
			if(!empty($_POST['konto_wewnetrzne']))
				view::redirect('users/formularz-uzytkownika/konto-wewnetrzne/1');
			elseif(!empty($_POST['czy_subkonto']))
				view::redirect('users/formularz-uzytkownika/czy_subkonto/tak');
			else
				view::redirect('users/formularz-uzytkownika');
		}
		else
		{
			if(!empty($_POST['czy_subkonto']))
			{
				mod_users::wyslij_maila($a_user['email'],39,false,false,array('uniqid'=>$a_user['uniqid_users'],'imie_nazwisko'=>session::get_user('imie').' '.session::get_user('nazwisko')));
				app::ok('Zarejestrowano użytkownika. Na podany adres email wysłano link aktywacyjny.');
				view::message();
				
				view::redirect('users/subkonta');
			}
			elseif(!empty($_POST['konto_wewnetrzne']))
				view::redirect('users/lista_kont_wewnetrznych');
			else
			{
				//mod_users::wyslij_maila($a_user['email'],1,false,false,array('uniqid'=>$a_user['uniqid_users']));
				mod_users::wyslij_link_aktywacyjny($id_users);
				//mod_users::wyslij_maila('legitymacje@loca.pl',40,'','',$a_user);

				app::ok('Zarejestrowano użytkownika. Na podany adres email wysłano link aktywacyjny.');
				view::message();
			}
				
		}
	}

	public static function edytuj_dane()
	{			
		$a_user=$_POST['a_user'];

		if(!mod_users::edytuj_dane($a_user,isset($_POST['czy_subkonto']),isset($_POST['czy_edycja_subkonta'])))
		{
			if(session::get('uniqid_users'))
				view::redirect('users/formularz-uzytkownika/uniqid/'.session::get('uniqid_users'));
			else
			{
				$subkonto = isset($_POST['czy_subkonto']) ? "/czy_subkonto/1" : '';
				view::redirect('users/formularz-uzytkownika/id/'.session::get_id().$subkonto);
			}
		}
		else
		{
			mod_logi::dodaj('edycja danych', session::get_id());
			
			if(isset($_POST['czy_subkonto']))
			{
				mod_placowki::usun_dostep_do_placowek($a_user['id_users']);

				if(!empty($_POST['a_placowki']))
				{
					foreach($_POST['a_placowki'] as $id_placowki=>$asd)
					{
						if(mod_placowki::sprawdz_dostep($id_placowki))
							mod_placowki::add_users_to_placowka($a_user['id_users'],$id_placowki,0);
					}
				}
			}
			
			if(isset($_POST['czy_subkonto']))
				view::redirect('users/subkonta');
			else
			{
				session::delete('uniqid_users');
				view::message();
			}
		}
	}

	public static function zaloguj()
	{
		router::set_checkpoint('users/formularz-logowania');
		
		if(hlp_validator::email($_POST['a_user']['email']))
		{
			if(!mod_users::zaloguj($_POST['a_user']))
			{
				app::err(view::get_message());
				view::redirect('users/formularz-logowania');
			}
			else
			{
				mod_users::wyslij_maila(session::get_user('email'),108);
				app::ok('Zalogowano');
				view::redirect('');
			}
		}
		/*elseif(hlp_validator::id($_POST['a_user']['email']))
		{
			if(mod_migracja::czy_migracja($_POST['a_user']))
			{
				app::unset_result();
				
				//sprawdzamy czy ktoś już nie przeszedł migracji
				if(db::get_one("SELECT 1 FROM users WHERE id_users=".$_POST['a_user']['email']))
				{
					app::ok('Twoje konto jest już zaktualizowane. Użyj swojego E-mail do zalogowania');
					view::redirect('users/formularz-logowania');
				}
				else
					con_migracja::formularz_migracji();
			}
			else
			{
				app::err('Nieprawidłowe hasło lub ID placówki');
				view::redirect('users/formularz-logowania');
			}
		}*/
		else
			view::redirect('users/formularz-logowania');
	}
	
	public static function zaloguj_fb()
	{
		$wynik=mod_users::zaloguj_fb($_POST['email'],$_POST['imie']);
		view::json($wynik, view::get_message());
	}
	
	public static function zaloguj_na_usera()
	{
		if(empty($_POST['id_pracownika']))
		{
			app::err('Nie zalogowano. Nieznany pracownik');
			view::message();
		}

		$rodzaj = db::get_one("SELECT rodzaj FROM users WHERE id_users=".$_POST['id_pracownika']);

		if($rodzaj!='admin')
		{
			app::err('Nie zalogowano. Brak uprawnień');
			view::message();
		}
		
		if(empty($_POST['id_users']) || !hlp_validator::id($_POST['id_users']))
		{
			app::err('Nie zalogowano. Nieznany user');
			view::message();
		}

		if(mod_users::zaloguj_na_usera($_POST['id_users'],$_POST['id_pracownika']))
			header('Location: https://realizacja.loca.pl/placowki/lista-placowek');
		else
			view::message();
	}
	
	public static function wyslij_link_aktywacyjny()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowe id');
			view::message();
		}
		
		mod_users::wyslij_link_aktywacyjny(db::get_one("SELECT id_users FROM users WHERE uniqid_users='{$_GET['id']}'"));

		app::ok('Na podany adres email została wysłana wiadomość z linkiem aktywacyjnym');
		view::message();
	}
/*
	public static function aktywuj_konto()
	{
		if(empty($_GET['hash']) || !hlp_validator::alfanum($_GET['hash']))
		{
			app::err('Nieprawidłowy hash');
			view::message();
		}
		
		$a_user = db::get_row("SELECT email,czy_aktywny FROM users WHERE token_maila_aktywacja_konta='{$_GET['hash']}'");

		if($a_user['czy_aktywny'])
		{
			app::ok('Konto jest już aktywne');
			view::redirect('');
		}
		else
		{
			view::add('token_maila_aktywacja_konta', $_GET['hash']);
			view::display();
		}
	}
	*/
	public static function aktywuj_konto()
	{
		if(empty($_GET['hash']) || !hlp_validator::alfanum($_GET['hash']))
		{
			app::err('Nieprawidłowy hash');
			view::message();
		}//db::deb();
		
		$a_user = db::get_row("SELECT id_users,email,czy_aktywny FROM users WHERE token_maila_aktywacja_konta='{$_GET['hash']}'");

		if($a_user['czy_aktywny'])
		{
			app::ok('Konto jest już aktywne');
			view::redirect('');
		}
		
		db::update('users', "id_users=".$a_user['id_users'], array('czy_aktywny'=>1));
			
		mod_logi::dodaj('aktywacja konta',$a_user['id_users']);
	
		mod_users::wyslij_maila($a_user['email'],4);
		app::ok('Twoje konto zostało aktywowane, możesz się teraz zalogować');
		view::redirect('');
	}
	/*
	public static function aktywuj_konto_confirm()
	{
		if(empty($_POST['a_user']))
		{
			app::err('Brak danych');
			view::message();
		}
		
		if(empty($_POST['a_user']['hash']) || !hlp_validator::alfanum($_POST['a_user']['hash']))
		{
			app::err('Nieprawidłowy hash');
			view::message();
		}

		$a_user = db::get_row("SELECT email,czy_aktywny,id_users FROM users WHERE token_maila_aktywacja_konta='{$_POST['a_user']['hash']}'");

		if($a_user['czy_aktywny'])
		{
			app::ok('Konto jest już aktywne');
			view::redirect('');
		}
		else
		{
			if(empty($_POST['a_user']['haslo']) || empty($_POST['a_user']['haslo_powtorzone']) || $_POST['a_user']['haslo']!=$_POST['a_user']['haslo_powtorzone'])
			{
				app::err('Hasła nie pasują do siebie');
				view::redirect('');
			}
			
			if(empty($_POST['regulamin']))
			{
				app::err('Musisz zaakceptować regulamin');
				view::redirect('');
			}

			if(empty($_POST['polityka_prywatnosci']))
			{
				app::err('Musisz zaakceptować politykę prywatności');
				view::redirect('');
			}

			db::update('users', "id_users=".$a_user['id_users'], array('haslo'=>MD5($_POST['a_user']['haslo'].strtolower($a_user['email'])), 'czy_aktywny'=>1));
			
			mod_logi::dodaj('aktywacja konta',$a_user['id_users']);
		
			mod_users::wyslij_maila($a_user['email'],4);
			app::ok('Twoje konto zostało aktywowane, możesz się teraz zalogować');
			view::redirect('');
		}
	}
*/
/*
	public static function lista_userow__admin()
	{
		if(isset($_POST['login']))
		{
			$login=$_POST['login'];
			$sql_login = ' WHERE login LIKE "%'.$login.'%"';
		}
		else
		{
			$sql_login = '';
			$login=false;
		}

		view::add('login',$login);
		view::add('a_users',db::get_many('SELECT id_users, login FROM users'.$sql_login));
		view::display();
	}
	*/
	public static function profil()
	{
		if(!isset($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieznany użytkownik');
			view::message();
		}

		$a_user = mod_users::get_user($_GET['id'], 'id_users');
		
		if(!$a_user)
		{
			app::err('Nieznany użytkownik');
			view::message();
		}
	
		view::display();
	}

	public static function zmien_obraz__lg()
	{
		$id = !empty($_GET['id']) && hlp_validator::id($_GET['id']) && (session::get_id()==$_GET['id'] || session::who('admin')) ? $_GET['id'] : session::get_id();

		if(!empty($_GET['is_bg']))
			view::add('is_bg',1);

		view::add('a_user',mod_users::get_user($id));
		view::add('uniqid',uniqid());
		view::add('id',$id);
		head::add_js_file('javascript/libs/jcrop/jquery.Jcrop.min.js');
		head::add_css_file('javascript/libs/jcrop/jquery.Jcrop.min.css');
		view::display('users/formularz_zmiany_avatara.tpl');
	}

	public static function tworz_avatar__lg()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']) || (session::get_id()!=$_POST['id'] && !session::who('admin')))
		{
			app::err('Brak dostępu');
			view::redirect('users/zmien_avatar');
		}
		
		$id_users = $_POST['id'];
		
		if(empty($_POST['is_bg']))
		{
			$targ_w = 125;
			$targ_h = 125;
			hlp_image::rrmdir("images/users/$id_users/avatar/",false);
		}
		else
		{
			$targ_w = 1600;
			$targ_h = 400;
			hlp_image::rrmdir("images/users/$id_users/bg/",false);
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
		if(empty($_POST['is_bg']))
			$filename = 'images/users/'.$id_users.'/avatar/'.$_POST['uniqid'].$ext;
		else
			$filename = 'images/users/'.$id_users.'/bg/'.$_POST['uniqid'].$ext;

		if($ext=='.png')
			$result=imagepng($dst_r,$filename,9);
  		if($ext=='.jpg' or $ext=='.jpeg')
			$result=imagejpeg($dst_r,$filename,90);
		if($ext=='.gif')
			$result=imagegif($dst_r,$filename,90);
		
		/******************************/

		if(empty($_POST['is_bg']))
			db::update('users',"id_users=".$id_users,array('avatar'=>$_POST['uniqid'].$ext,'token_odswiezenia'=>uniqid()));
		else
			db::update('users',"id_users=".$id_users,array('bg'=>$_POST['uniqid'].$ext,'token_odswiezenia'=>uniqid()));

		unlink($src);
		
		$a_user = mod_users::get_user($id_users);

		//view::redirect($a_user['sludge']);
		header('Location:'.app::base_url().$a_user['sludge_miasta'].'/'.$a_user['sludge']);
	}

	public static function admin_lista_userow__admin_mod()
	{
		$nazwa = !empty($_GET['nazwa']) ? $_GET['nazwa'] : '';
		$wybrana_data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

		$a_users = mod_users::get_users_admin(session::get('id_miasta'),$nazwa,$wybrana_data);

		view::add('nazwa',$nazwa);
		view::add('wybrana_data',$wybrana_data);
		view::add('a_users',$a_users);
		view::display('users/lista_userow_admin.tpl');
	}

	public static function wyloguj()
	{
		$login=session::get_user('imie');
		session::logout();
		app::ok('Do zobaczenia '." $login");
		unset($_SESSION['lang']);unset($_SESSION['lang_texts']);
		view::redirect('');
	}
	
	public static function get_user_type()
	{
		echo session::who('admin');
		exit;
	}
	
	public static function subkonta()
	{
		view::add('a_subkonta',mod_users::get_subkonta(session::get_id()));
		view::display();
	}

	public static function sprawdz_email()
	{
		if(empty($_GET['val']) || !hlp_validator::email($_GET['val']))
			view::json(false,view::get_message());
		

		if(db::get_one("SELECT 1 FROM users WHERE email='{$_GET['val']}'"))
			view::json(false,'W nowym systemie istnieje już konto na ten adres e-mail.  Zaloguj się przy pomocy tego E-maila, skorzystaj z opcji przypomnienia hasła lub skontaktuj się z nami.');
		else
			view::json(true);
		exit;
	}
	
	public static function lista_kont_wewnetrznych__admin_mod()
	{
		$search = !empty($_GET['search']) ? $_GET['search'] : false;
		
		if($search)
			view::add('a_users',mod_users::get_users(false,$search));
		
		view::add('search', $search);
		view::display();
	}
	
	public static function wyslij_link_aktywacyjny_konta_wewnetrznego__admin_mod()
	{
		if(empty($_POST['id_placowki']) || !hlp_validator::id($_POST['id_placowki']))
		{
			app::err('Nieprawidłowe id_placowki');
			view::message();
		}

		if(!is_uploaded_file($_FILES['formularz']['tmp_name']))
		{
			app::err('Brak formularza');
			view::message();
		}
		
		$a_placowka = mod_placowki::get_placowka($_POST['id_placowki']);

		$param = uniqid().'-'.(time()+604800);

		db::update('users',"id_users=".$a_placowka['id_users'],array('token_maila_aktywacyjnego'=>$param));
		
		$dir = "images/placowki/".$a_placowka['id_placowki'].'/potwierdzenia';
		
		if(!is_dir($dir))
			mkdir($dir,0777);
		
		$url = $dir."/formularz_aktywacyjny_podpisany_{$a_placowka['uniqid_placowki']}";

		hlp_image::save($_FILES['formularz'],$url);
		
		$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_placowka['id_users']);
		
		mod_users::wyslij_maila($email,12,'','',array('param'=>$param,'url'=>$url.'.pdf'));
		app::ok('Link aktywacyjny wysłany');
		view::redirect('placowki/admin_lista_placowek');
	}
	
	public static function wyslij_formularz_aktywacyjny__admin_mod()
	{
		if(empty($_POST['id_placowki']) || !hlp_validator::id($_POST['id_placowki']))
			view::json(false,'Nieprawidłowe id_placowki');
		
		$a_placowka = mod_placowki::get_placowka($_POST['id_placowki']);
		$a_dokument = false;
		
		if($a_placowka['id_dokumenty_sprzedazy'])
			$a_dokument = mod_placowki::get_dokument_sprzedazy($id_dokumenty_sprzedazy);
		
		mod_users::formularz_aktywacyjny_pdf($a_placowka,$a_dokument);
		
		$_user = db::get_row("SELECT email FROM users WHERE id_users=".$_POST['id_users']);
		$url = "images/placowki/{$a_placowka['id_placowki']}/form_aktywacyjny_{$a_placowka['uniqid_placowki']}.pdf";
		$email = db::get_one("SELECT email FROM users WHERE id_users=".$a_placowka['id_users']);

		//mailer::send($a_mail['title'],$text,false,true,strip_tags($text));
		
		mod_users::wyslij_maila($email, 45, '', '', array('id_placowki'=>$_POST['id_placowki'], 'url'=>$url));
		
		view::json(true,'Formularz aktywacyjny wysłany');
	}
	
	public static function aktywuj_konto_wewnetrzne()
	{
		if(session::is_logged())
		{
			app::err('Jesteś już zalogowany');
			view::message();
		}
		
		if(empty($_GET['param']))
		{
			app::err('Brak parametru');
			view::message();
		}
		
		$a_param = explode('-',$_GET['param']);

		if(count($a_param)<2)
		{
			app::err('Nieprawidłowy parametr');
			view::message();
		}
		
		$a_user = db::get_row("SELECT * FROM users WHERE token_maila_aktywacyjnego='{$_GET['param']}'");
		
		if(!$a_user)
		{
			app::err('Nieprawidłowy parametr');
			view::message();
		}
		
		if(time()>$a_param[1])
		{
			app::err('Link jest już nieaktywny');
			view::message();
		}
		
		if(!empty($a_user['haslo']))
		{
			app::err('Link jest już nieaktywny');
			view::message();
		}
		
		db::update('users','id_users='.$a_user['id_users'],array('token_maila_aktywacyjnego'=>'','czy_konto_wewnetrzne'=>0));
		db::update('placowki','id_users='.$a_user['id_users'],array('typ'=>'przypisane'));
		
		$_SESSION[session::$tab]=$a_user;
		$_SESSION[session::$tab]['czy_przenoszenie'] = 1;
		$_SESSION[session::$tab]['is_logged']=true;
		session::set('uniqid_users', $a_user['uniqid_users']);
		
		view::redirect('users/formularz-uzytkownika/id/'.$a_user['id_users']);
	}

	public static function umowy__admin()
	{
		if(session::get_user('typ')=='placowka' && !session::get('id_placowki'))
		{
			app::err('Nieprawidłowa placówka');
			view::message();
		}

		view::add('a_placowka',mod_placowki::get_placowka(session::get('id_placowki')));
		view::add('a_umowy',mod_users::get_umowy(session::get_user('typ')));
		view::display();
	}

	public static function akceptuj_umowe__lg()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowa umowa');
			view::message();
		}
		
		if(mod_users::get_umowa($_GET['id']))
		{
			app::ok('Już akceptowałeś tą umowę');
			view::message();
		}
		
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
			<body>';

		$a_strona = db::get_by_id('sites',$_GET['id']);
		$a_placowka = mod_placowki::get_placowka(session::get('id_placowki'));
		$a_strona['text'] = str_replace('{reprezentacja}',session::get_user('nazwa_firmy').', '.session::get_user('ulica').', '.session::get_user('kod_pocztowy').', '.session::get_user('miasto').', NIP:'.session::get_user('nip'),$a_strona['text']);
		$a_strona['text'] = str_replace('{imie}',session::get_user('imie'),$a_strona['text']);
		$a_strona['text'] = str_replace('{nazwisko}',session::get_user('nazwisko'),$a_strona['text']);
		$a_strona['text'] = str_replace('{nazwa_placowki}',$a_placowka['nazwa'],$a_strona['text']);
		$a_strona['text'] = str_replace('{adres}',$a_placowka['adres'],$a_strona['text']);
		$a_strona['text'] = str_replace('{kod}',$a_placowka['kod_pocztowy'],$a_strona['text']);
		$a_strona['text'] = str_replace('{poczta}',$a_placowka['poczta'],$a_strona['text']);
		$a_strona['text'] = str_replace('{dyrektor}',$a_placowka['dyrektor'],$a_strona['text']);
		$a_strona['text'] = str_replace('{regon}',$a_placowka['regon'],$a_strona['text']);
		$a_strona['text'] = str_replace('{id_placowki}',$a_placowka['id_placowki'],$a_strona['text']);
		$a_strona['text'] = str_replace('{data_zawarcia}',date('Y-m-d'),$a_strona['text']);
		$a_strona['text'] = str_replace('{id_placowki}',$a_placowka['id_placowki'],$a_strona['text']);
		$numer_umowy = mod_users::tworz_nr_umowy($_GET['id']);
		$a_strona['text'] = str_replace('{numer}',$numer_umowy,$a_strona['text']);

		//$a_strona['title'] = str_replace('{numer umowy}','nr '.$numer_umowy.'/LOCACARD',$a_strona['title']);
		
		view::add('a_strona',$a_strona);
		$html .= view::display('strony/strona.tpl',true,true);
		$html .= '</body></html>';

		$pdf = "images/placowki/".session::get('id_placowki').'/umowy/';
		if(!is_dir($pdf))
			mkdir($pdf,0777);

		$pdf .= str_replace('/','-',$numer_umowy);

		file_put_contents($pdf.".html", $html);
 		system("/usr/local/bin/wkhtmltopdf --footer-center [page]/[topage] {$pdf}.html {$pdf}.pdf");

		$data = date('Y-m-d');
		$godzina = date('H:i:s');

		db::insert("umowy",array('id_placowki'=>session::get_user('typ')=='placowka' ? session::get('id_placowki') : 0,
								 'id_users'=>session::get_user('typ')=='agencja' ? session::get_id() : 0,
								 'id_sites'=>$_GET['id'],
								 'ip'=>hlp_functions::get_ip_address(),
								 'data_akceptacji'=>"NOW()",
								 'numer_umowy'=>$numer_umowy,
								 'status'=>'wydrukowana'));

	    //mailer::add_address(session::get_user('email'));
		mailer::add_address('legitymacje@loca.pl');
		
		//$a_strona = db::get_by_id('sites',28);
		//$a_strona['text'] = str_replace('{imie}',session::get_user('imie'),$a_strona['text']);
		//$a_strona['text'] = str_replace('{nazwisko}',session::get_user('nazwisko'),$a_strona['text']);
		//$a_strona['text'] = str_replace('{nazwa_placowki}',session::get_user('nazwisko'),$a_strona['text']);
		//$a_strona['text'] = str_replace('{godzina}',$godzina,$a_strona['text']);
		//$a_strona['text'] = str_replace('{ip}',hlp_functions::get_ip_address(),$a_strona['text']);

		//mailer::add_attachment($pdf.'.pdf',str_replace('/','-',$numer_umowy).'.pdf','base64','application/pdf');
		//mailer::send($a_strona['title'],$a_strona['text'],false,strip_tags($a_strona['text']));
		mod_users::wyslij_maila(session::get_user('email'), 28);
		mod_users::wyslij_maila('legitymacje@loca.pl', 28);
		
		app::ok('Dziękujemy za zaakceptowanie umowy. Na maila została wysłana kopia');
		view::redirect('users/umowy');
	}

	public static function potwierdz_umowe__lg()
	{/*
		if(!session::get('czy_zdalny'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		*/
		if(empty($_POST['id_sites']) || !hlp_validator::id($_POST['id_sites']))
		{
			app::err('Nieprawidłowa umowa');
			view::message();
		}
		
		if(!session::get('id_placowki'))
		{
			app::err('Nieprawidłowa placówka');
			view::message();
		}

		if(!is_uploaded_file($_FILES['potwierdzenie']['tmp_name']))
		{
			app::err('Brak potwierdzenia');
			view::message();
		}
		
		$dir = "images/placowki/".session::get('id_placowki').'/potwierdzenia';
		
		if(!is_dir($dir))
			mkdir($dir,0777);
		
		$a_umowa = mod_users::get_umowa($_POST['id_sites']);

		hlp_image::save($_FILES['potwierdzenie'],$dir.'/'.str_replace('/','-',$a_umowa['numer_umowy']));
		$data = date('Y-m-d H:i:s');
		db::update('umowy',"id_sites={$_POST['id_sites']} AND id_placowki=".session::get('id_placowki'),array('status'=>'potwierdzona','data_potwierdzenia'=>$data));
		mod_logi::dodaj('dodano potwierdzenie umowy', $_POST['id_sites']);
		
		mod_users::wyslij_maila(session::get_user('email'),41,'','',array('numer_umowy'=>$a_umowa['numer_umowy'],
																		  'data_potwierdzenia'=>$data));
		
		view::redirect('users/umowy');
	}

	public static function lista_formularzy()
	{
		if(!session::get('czy_zdalny'))
			exit;
		
		view::add('a_formularze',mod_users::get_formularze_usera(session::get_id()));
		view::display();
	}
	
	public static function wyslij_formularz()
	{
		if(!session::get('czy_zdalny'))
			exit;
			
		//zmienić to, że typy formularzy są w tabeli w bazie jak już będą znane inne typy (przemyśleć)
		if(empty($_POST['formularz']) || !in_array($_POST['formularz'],array('Formularz migracji')))
			view::json(false,'Nieprawidłowy formularz');
		
		if($_POST['formularz']=='Formularz migracji')
			$text = mod_users::wyslij_maila(session::get_user('email'),49,false,false,array('link'=>'http://legitymacje.loca.pl/users/formularz_migracji/id/'.session::get_user('uniqid_users')));

		mod_users::zapisz_wyslanie_formularza($_POST['formularz'],$text);
		
		view::json(true,'Formularz wysłany');
	}
	
	public static function formularz_migracji()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy numer użytkownika');
			view::message();
		}
		
		view::add('uniqid_users',$_GET['id']);
		view::display();
	}
	
	public static function akceptuj_formularz_migracji()
	{
		if(empty($_POST['uniqid_users']) || !hlp_validator::id($_POST['uniqid_users']))
		{
			app::err('Nieprawidłowy numer użytkownika');
			view::message();
		}
		
		$a_user = db::get_row("SELECT id_users,email FROM users WHERE uniqid_users=".$_POST['uniqid_users']);
		
		if(isset($_POST['a_user']['czy_newsletter']) && isset($_POST['a_user']['czy_newsletter2']))
			db::update('users',"id_users=".$a_user['id_users'],array('czy_newsletter'=>1));

		db::update('logi_formularzy',"typ_formularza='Formularz migracji' and id_users=".$a_user['id_users'],array('status'=>'zaakceptowany',
																										   'data_akceptacji'=>'NOW()',
																										   'ip'=>hlp_functions::get_ip_address()));
		
		mod_users::wyslij_maila($a_user['email'],44);
		view::message('Dziękujemy za akceptację formularza');
	}
	
	public static function podglad_formularza()
	{
		if(!session::get('czy_zdalny'))
			exit;
		
		if(!isset($_GET['id']) || !hlp_validator::id($_GET['id']))
			exit;
		
		$a_strona['title'] = 'Formularz';
		$a_strona['text'] = db::get_one("SELECT text FROM logi_formularzy WHERE id_logi_formularzy=".$_GET['id']);
		
		view::add('a_strona',$a_strona);
		view::display('strony/strona.tpl');
	}
	
	public static function zmien_konto_standardowe_na_wewnetrzne__admin_mod()
	{
		if(!isset($_POST['id']) || !hlp_validator::id($_POST['id']))
			exit;
		//db::deb();
		db::update('users', 'id_users='.$_POST['id'], array('czy_konto_wewnetrzne'=>1));
		view::json(true);
	}
	
	public static function zapisz_odczyt_przewodnika__lg()
	{
		db::update('users', "id_users=".session::get_id(), array('czy_przewodnik_odczytany'=>1));
		
		if(!session::get_user('parent_id'))
			view::redirect('placowki/formularz-placowki');
		else
			view::redirect('');
	}
	
	public static function wyslij_maila_usuniecie_konta__lg()
	{
		if(empty($_POST['id_users']) || !hlp_validator::id($_POST['id_users']))
		{
			app::err('Nieprawidłowy uzytkownik');
			view::message();
		}
		
		$parent_id = db::get_one("SELECT parent_id FROM users WHERE id_users=".$_POST['id_users']);

		if(session::get_id()!=$_POST['id_users'] && $parent_id!=session::get_id())
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		$param = uniqid().'-'.(time()+604800);

		db::update('users',"id_users=".$_POST['id_users'],array('token_maila_usuniecie_konta'=>$param));

		$email = db::get_one("SELECT email FROM users WHERE id_users=".$_POST['id_users']);
		mod_users::wyslij_maila($email,92,'','',array('param'=>$param));

		view::json(true,'Wysłaliśmy na e-mail konta wiadomość z linkiem do potwierdzenia usunięcia konta. Konto zostanie skasowane po jego kliknięciu.');
	}
	
	public static function usun_konto__lg()
	{
		if(empty($_GET['param']))
		{
			app::err('Brak parametru');
			view::message();
		}
		
		$a_param = explode('-',$_GET['param']);

		if(count($a_param)<2)
		{
			app::err('Nieprawidłowy parametr');
			view::message();
		}
		
		$a_user = db::get_row("SELECT * FROM users WHERE token_maila_usuniecie_konta='{$_GET['param']}'");
		
		if(!$a_user)
		{
			app::err('Nieprawidłowy parametr');
			view::message();
		}
		
		if(time()>$a_param[1])
		{
			app::err('Link jest już nieaktywny');
			view::message();
		}

		if(!$a_user['czy_aktywny'])
			db::delete('users', "id_users=".$a_user['id_users']);
		else
			db::update('users', "id_users=".$a_user['id_users'], array('parent_id'=>0));

		db::delete('users_placowki', "id_users=".$a_user['id_users']);
		
		mod_users::wyslij_maila($a_user['email'],93,'','',array('email'=>$a_user['email']));
		mod_users::wyslij_maila('legitymacje@loca.pl',93,'','',array('email'=>$a_user['email']));
		
		session::logout();
		app::ok('Potwierdzono zgłoszenie usunięcia konta.  Twoje dane zostały skasowane');
		view::message();
	}
	
	public static function wyslij_link_do_generatora_umow()
	{
		if(!session::get('czy_zdalny'))
			view::ajax(false, 'Brak dostępu');
		
		$hash = uniqid();
		$date = date('Y-m-d', strtotime('+7 days'));
		db::update('placowki', 'id_placowki='.session::get('id_placowki'), array('link_generatora_umowa_hash'=>$hash,
																				 'link_generatora_umowa_data_waznosci'=>$date));
	 	
	 	$email = db::get_one("SELECT email FROM users JOIN placowki USING(id_users) WHERE id_placowki=".session::get('id_placowki'));
	 	mod_users::wyslij_maila($email,94,'','',array('hash'=>$hash));
		view::json(true, 'Wysłano link do generatora');
	}
}

	
?>