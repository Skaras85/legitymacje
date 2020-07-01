<?php

class app{
	
	public static $result = true;				//wynik działania metody
	private static $base_url = '';				//zawiera adres bazowy używany przy wszystkich urlach
	private static $host = '';
	
	public static function set_base_url($i_base_url)
	{
		self::$base_url=$i_base_url;
	}

	public static function base_url()
	{
		return self::$base_url;
	}
	
	public static function set_host($host)
	{
		self::$host=$host;
	}
	
	public static function host()
	{
		return self::$host;
	}
	
	//ustawiamy wynik i komunikat (także w sesji)
	public static function err($i_msg='')
	{
		self::$result = false;
		//view::$msg = $i_msg;
		
		//to samo co wyżej zapisujemy w sesji. Przydatne, gdy chcemy mieć do nich
		//dostęp przy przekierowaniu, bo wtedy zmienne klasowe giną
		//zmienne te i tak są usuwane zaraz po wyświetleniu stopki strony
		$_SESSION['app']['result']=false;
		$_SESSION['app']['msg']=$i_msg;
		
		return false;
	}
	
	//ustawiamy wynik i komunikat
	public static function ok($i_msg='')
	{
		self::$result = true;
		view::$msg = $i_msg;
		
		$_SESSION['app']['result']=true;
		$_SESSION['app']['msg']=$i_msg;
		
		return true;
	}
	
	//pobieramy wynik ostatniego działania
	public static function get_result()
	{
		if(self::$result==false or (isset($_SESSION['app']['result']) and $_SESSION['app']['result']==false ))
			return false;
		else
			return true;
	}
	
	//usuwamy wynik i komunikat działania (także z sesji)
	public static function unset_result()
	{
		self::$result = true;
		if(isset($_SESSION['app']['result']))
			unset($_SESSION['app']['result']);
		if(isset($_SESSION['app']['msg']))
			unset($_SESSION['app']['msg']);

		view::$msg='';
	}

	public static function run()
	{
		/*if(session::is_logged())
		{
			if(!session::check_last_active_date())
			{
				session::logout();
				$_SESSION['app']['request']=$_REQUEST;
				$_SESSION['app']['referer']=$_SERVER['HTTP_REFERER'];
				self::err('Zbyt długi czas bezczynności, zaloguj się ponownie');
				view::display("users/formularz_logowania.php",array('edycja'=>false));
			}
			session::save_last_active_date();
		}*/

		$a_route=router::route();
		con_main::get_settings();

		if($a_route)
		{
			$module=$a_route['module'];
			$action=$a_route['action'];
		}
		else
		{
			if(isset($_POST['module']) && isset($_POST['action']))
			{
				$module=$_POST['module'];
				$action=$_POST['action'];
			}
			elseif(isset($_GET['module']) && isset($_GET['action']))
			{
				$module=$_GET['module'];
				$action=$_GET['action'];
			}
			else
			{
				$module='main';
				$action='start';
			}
		}

		if (!file_exists('controllers/con_'.$module.'.class.php'))
		{
			self::err('404: Nieznany moduł ' . $module);
			view::message();
		}
		
		$o_ref = new ReflectionClass('con_'.$module);

		$a_methods=$o_ref->getMethods();
		$found_action=false;

		foreach($a_methods as $o_method)
		{
			$a_parts_of_method_name = explode("__",$o_method->name);
			
			if ($a_parts_of_method_name[0]==$action)
			{
				$found_action=true;
				$action=$o_method->name;
				if(count($a_parts_of_method_name)==2)
				{
					$a_perms = explode('_',$a_parts_of_method_name[1]);
					
					if(in_array('lg',$a_perms) && !session::is_logged())
						session::block_unlog();
					
					if((!in_array('lg',$a_perms) && !in_array(session::get_user_type(),$a_perms)))
					{
						app::err('Nie masz uprawnień, aby oglądać tę stronę');
						view::message();
					}

				}
			}
		}
		
		if(!$found_action)
		{
			self::err('Nieznana akcja '.$action);
			view::message();
		}

		if(session::is_logged() && !session::get('czy_zdalny') && !db::get_one("SELECT czy_przewodnik_odczytany FROM users WHERE id_users=".session::get_id()) && empty($_REQUEST['token']) && $action!='wyloguj' && $action!='zapisz_odczyt_przewodnika__lg' && $action!='formularz_uzytkownika' && $action!='edytuj_dane')
		{
			view::add('a_strony', db::get_many("SELECT * FROM sites WHERE id_article_categories=8 AND is_visible=1 ORDER BY `order`"));
			view::display('users/komunikaty.tpl');
		}
		
		if(session::is_logged() && db::get_one("SELECT czy_haslo_losowe FROM users WHERE id_users=".session::get_id()))
		{
			if($action!='formularz_uzytkownika' && $action!='edytuj_dane' && $action!='wyloguj' && $action!='zapisz_odczyt_przewodnika__lg')
			{
				$module = 'users';
				$action = 'formularz_uzytkownika';
			}
		}

		$function = create_function('$module,$action',"con_{$module}::$action();");
		$function($module,$action);
	}
	
}

?>