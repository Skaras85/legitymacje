<?php

class router{
	
	public static function route()
	{
		$url=substr($_SERVER['QUERY_STRING'],1);

		view::add('site_url',str_replace('/','---',$url));
		if($url)
		{
			$a_params = explode('&', $url);
			$a_url = explode('/',$a_params[0]);

			$a_route['module'] = str_replace('-', '_', $a_url[0]);

			$data = '';
			for($i=2; $i<count($a_url); $i+=2)
			{
				if(isset($a_url[$i+1]))
				{
					$_GET[$a_url[$i]]=$a_url[$i+1];
					$_REQUEST[$a_url[$i]]=$a_url[$i+1];
					$data .= '/'.$a_url[$i].'/'.$a_url[$i+1];
				}
			}

			if($a_url[0]=='blog')
			{
				$a_route['module'] = 'strony';
				$a_route['action'] = 'blog';
				
				if(preg_match('/numer-strony\/([0-9]+)+/',$url,$a_saved))
					$_GET['numer-strony'] = $a_saved[1];
				
				if(isset($a_url[1]) && preg_match('/([A-Za-z0-9\.\-_]+)/',$a_url[1],$a_saved))
					$_GET['sludge'] = $a_url[1];
			}
			elseif(isset($a_url[1]))
			{
				//kontakt,8
				if(preg_match('/([A-Za-z0-9\.\-_]+),([0-9]+)+/',$a_url[1],$a_saved))
				{
					$default_action = eval('return con_'.$a_route['module'].'::$default_action;');
					
					self::redirect_301($a_route['module'], $default_action, $a_saved[2],$a_saved[1],$data);
					
					$a_route['action'] = str_replace('-', '_', $default_action);
					$_GET['id'] = $a_saved[2];
				}
				elseif(isset($a_url[2]) && preg_match('/([A-Za-z0-9\.\-_]+),([0-9]+)+/',$a_url[2],$a_saved))
				{
					//np. strony/artykuly/aktualnosci,5
					$a_route['action'] = str_replace('-', '_', $a_url[1]);

					if(preg_match('/^[0-9]+$/', $a_saved[2]))
					{
						$default_action = eval('return con_'.$a_route['module'].'::$default_action;');
						
						if($a_route['action']==$default_action)
							self::redirect_301($a_route['module'], $a_route['action'], $a_saved[2], $data);
					
						$_GET['id'] = $a_saved[2];
					}
				}
				else
				{
					//np. strony/formularz-dodawania
					$a_route['action'] = str_replace('-', '_', $a_url[1]);
				}
			}
			elseif(isset($a_url[0]) && preg_match('/([A-Za-z0-9\.\-_\,]+)/',$a_url[0]))
			{
				if(preg_match('/([A-Za-z0-9\.\-_]+),([0-9]+)+/',$a_url[0],$a_saved))
				{
					$sludge = db::get_one('SELECT sludge FROM sites WHERE id_sites='.$a_saved[2]);
					
					if($sludge!=$a_saved[1])
						view::redirect($sludge.','.$a_saved[2]);
					
					$a_route['module'] = 'strony';
					$a_route['action'] = 'strona';
					$_GET['id'] = $a_saved[2];
				}
				else
				{
					$a_miasto = db::get_row("SELECT * FROM miasta WHERE sludge='{$a_url[0]}'");
					///var_dump($a_miasto);
					if($a_miasto)
						session::set('id_miasta',$a_miasto['id_miasta']);

					if($a_miasto['id_miasta']==2)
						view::redirect('');
					
					$a_route['module'] = 'main';
					$a_route['action'] = 'start';
				}
			}

			return $a_route;
		}
		else
			return false;
	}

	public static function redirect_301($module,$action,$id,$link=false,$data='')
	{
		$sludge = false;
		$lang = lang::get_lang();

		if($module=='strony' && $action=='strona')
		{
			if($lang=='PL')
				$sludge = db::get_one('SELECT sludge FROM sites WHERE id_sites='.$id);
			else
				$sludge = db::get_one('SELECT sludge_'.$lang.' FROM sites WHERE id_sites='.$id);
		}
		
		if($module=='users' && $action=='profil')
			$sludge = db::get_one('SELECT sludge FROM users WHERE uniqid_users='.$id);
		
		if($module=='strony' && $action=='menu')
			$sludge = db::get_one('SELECT sludge FROM dishes_categories WHERE id_dishes_categories='.$id);
		
		if($module=='galerie' && $action=='pokaz')
		{
			if($lang=='PL')
				$sludge = db::get_one('SELECT sludge FROM galleries WHERE id_galleries='.$id);
			else
				$sludge = db::get_one('SELECT sludge_'.$lang.' FROM galleries WHERE id_galleries='.$id);
		}

		if($sludge && $sludge!=$link)
		{
			header("HTTP/1.1 301 Moved Permanently");
		  	header("Location: ".app::base_url().$module.'/'.$sludge.','.$id.$data);
		    header("Connection: close");
		    exit;
		}
	}
	
	public static function go_back()
	{
		if(self::get_checkpoint()!==false)
		{
			echo app::base_url().self::get_checkpoint();
			self::destroy_checkpoint();
		}
		else
		{
			echo app::base_url();
		}
	}
	
	public static function set_checkpoint($i_checkpoint='')
	{
		$_SESSION['app']['checkpoint']=$i_checkpoint;
	}
	
	public static function get_checkpoint()
	{
		if(isset($_SESSION['app']['checkpoint']))
			return $_SESSION['app']['checkpoint'];
		else
			return false;
	}
	
	public static function destroy_checkpoint()
	{
		unset($_SESSION['app']['checkpoint']);
	}
}



?>