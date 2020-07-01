<?php

class head{
	private static $a_js_files = array();		//tablica z urlami plików javascript podpinnych w headzie
	private static $a_con_js_files = array();	//tablica z urlami plików javascript podpinnych warunkowo w headzie
	
	private static $a_css_files = array();		//tablica z urlami plików css podpinnych w headzie
	private static $a_con_css_files = array();	//tablica z urlami plików css podpinnych warunkowo w headzie
	
	private static $encoding = 'utf-8';
	private static $nofollow = false;
	private static $rel_next = false;
	private static $rel_prev = false;
	public static $title = '';
	public static $description = '';
	public static $keywords = '';
	private static $doctype = 'html5';
	private static $author;

	/*
	 * $i_file - sciezka do pliku
	 * $i_con - czy ma być dołączony warunkowo
	 * $i_place -  miejsce podpięcia pliku. Domyślnie to koniec body, można dać head
	*/
	public static function add_js_file($i_file,$i_con=false,$i_place='bottom')
	{
		if($i_con===false)
			self::$a_js_files[$i_place][] = $i_file;
		else
			self::$a_con_js_files[$i_place][$i_con][] = $i_file;
	}
	
	public static function remove_js_file($i_file)
	{
		$result=array_search($i_file, self::$a_js_files['head']);
		if($result!==false)
			unset(self::$a_js_files[$result]);
		
		$result=array_search($i_file, self::$a_js_files['bottom']);
		if($result!==false)
			unset(self::$a_js_files[$result]);
	}
	
	public static function js_files($i_place='bottom')
	{
		$string='';
		foreach(self::$a_js_files[$i_place] as $js_file)
		{
			$string.='<script';
			
			if(self::$doctype!='html5')
				$string.=' type="text/javascript" ';
			
			$string.=' src="';
			
			if(substr($js_file, 0, 4)!='http')
				$string.=app::base_url();
			$string.=$js_file;
			$string.="\"></script>\r\n";
		}
		return $string;
	}
	
	public static function con_js_files($i_place='bottom')
	{
		$string='';
		foreach(self::$a_con_js_files[$i_place] as $cond=>$a_files)
		{
			$string.="<!--[if " . $cond . "]>\r\n";
			
			foreach($a_files as $file)
			{
				$string.="\t<script";
	
				if(strtolower(self::$doctype)!='html5')
					$string.=' type="text/javascript" ';
				
				$string.=' src="';
				if(substr($file, 0, 4)!='http')
					$string.=app::base_url();
				$string.=$file;
				$string.="\"></script>\r\n";
			}
			$string.="<![endif]-->\r\n";
		}
		return $string;
	}
	
	public static function remove_con_js_file($i_file)
	{
		$result=array_search($i_file, self::$a_con_js_files['head']);
		if($result!==false)
			unset(self::$a_con_js_files[$result]);
		
		$result=array_search($i_file, self::$a_con_js_files['bottom']);
		if($result!==false)
			unset(self::$a_con_js_files[$result]);
			
	}
	
	public static function add_css_file($i_file,$i_con=false)
	{
		if($i_con===false)
			self::$a_css_files[] = $i_file;
		else
			self::$a_con_css_files[$i_con][] = $i_file;
	}
	
	public static function remove_css_file($i_file)
	{
		$result=array_search($i_file, self::$a_css_files);
		if($result!==false)
			unset(self::$a_css_files[$result]);
	}
	
	public static function css_files()
	{
		$string='';
		foreach(self::$a_css_files as $css_file)
		{
			$string.='<link rel="stylesheet" type="text/css" href="';
			if(substr($css_file, 0, 4)!='http')
				$string.=app::base_url(); 
			$string.=$css_file;
			$string.="\">\r\n";
		}
		return $string;
	}
	
	public static function con_css_files()
	{
		$string='';
		foreach(self::$a_con_css_files as $cond=>$a_files)
		{
			$string.="<!--[if " . $cond . "]>\r\n";
			foreach($a_files as $file)
			{
				$string.="\t<link rel=\"stylesheet\" type=\"text/css\" href=\"";
				if(substr($file, 0, 4)!='http')
					$string.=self::$base_url;
				$string.=$file."\">\r\n";
			}
			$string.="<![endif]-->\r\n";
		}
		return $string;
	}
	
	public static function remove_con_css_file($i_file)
	{
		foreach(self::$a_con_css_files as $klucz=>$a_css_file)
		{
			if($a_css_file['file']==$i_file)
				unset(self::$a_con_css_files[$klucz]);
		}
			
	}
	
	public static function set_encoding($i_encoding)
	{
		self::$encoding=$i_encoding;
	}

	public static function encoding()
	{
		if(strtolower(self::$doctype)!='html5')
			return '<meta http-equiv="Content-type" content="text/html; charset=' . self::$encoding . '">' . "\r\n";
		else
			return '<meta charset="'.self::$encoding.'">';
	}

	public static function set_title($i_title)
	{
		self::$title=$i_title;
	}

	public static function title()
	{
		if(self::$title!='')
		{
			return "<title>" . self::$title . "</title>\r\n";
		}
		else
			return false;
	}

	public static function set_description($i_description)
	{
		self::$description=$i_description;
	}

	public static function description()
	{
		if(self::$description!='')
			return "<meta name=\"description\" content=\"" . self::$description . "\">\r\n";
		else
			return false;
	}

	public static function set_keywords($i_keywords)
	{
		self::$keywords=$i_keywords;
	}

	public static function keywords()
	{
		if(self::$keywords!='')
			return '<meta name="keywords" content="' . self::$keywords . "\">\r\n";
		else
			return false;
	}
	
	public static function set_author($i_author)
	{
		self::$author=$i_author;
	}

	public static function author()
	{
		if(self::$author!='')
			return '<meta name="author" content="' . self::$author . "\">\r\n";
		else
			return false;
	}
	
	public static function set_doctype($i_doctype)
	{
		self::$doctype=$i_doctype;
	}
	
	public static function doctype()
	{
		switch(strtolower(self::$doctype))
		{
			case 'html4':
				return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'."\r\n";
			break;
			case 'xhtml':
				return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\r\n";
			break;
			default:
				self::add_js_file('http://html5shiv.googlecode.com/svn/trunk/html5.js','lt IE 9');
				return "<!DOCTYPE HTML>"."\r\n";
			break;
		}
	}
	
	public static function rel_next($href)
	{
		self::$rel_next = $href;
	}
	
	public static function get_rel_next()
	{
		if(self::$rel_next)
			return '<link rel="next" href="'.self::$rel_next.'">';
	}
	
	public static function rel_prev($href)
	{
		self::$rel_prev = $href;
	}
	
	public static function get_rel_prev()
	{
		if(self::$rel_prev)
			return '<link rel="prev" href="'.self::$rel_prev.'">';
	}
	
	public static function nofollow()
	{
		self::$nofollow = true;
	}
	
	public static function get_nofollow()
	{
		if(self::$nofollow)
			return '<meta name="robots" content="noindex, nofollow">';
	}

	public static function get_header()
	{
		$string = self::encoding();
		$string .= '<!--poprawniejsze wyswietlanie dla IE-->'."\r\n".
        	  '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">'."\r\n".
			  '<!--poprawniejsze wyswietlanie dla urzadzen mobilnych-->'."\r\n".
       		  '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\r\n";

		$string .= self::author();
		$string .= self::keywords();
		$string .= self::description();
		$string .= self::title();
		$string .= self::css_files(); 
		$string .= self::con_css_files();
		$string .= self::js_files('head');
		$string .= self::con_js_files();
		$string .= self::get_nofollow();
		$string .= self::get_rel_prev();
		$string .= self::get_rel_next();
		return $string;
	}
	
}

?>