<?php

class lang{

	public static $msg = '';					//komunikat ustawiany przy wielu metodach
	
	public static function set_lang($lang=false)
	{
		$default_lang = self::get_default_lang();
		if($lang)
		{
			//sprawdzamy czy taki język istnieje
			$is_exists = db::get_one("SELECT 1 FROM langs WHERE short='$lang' AND is_active=1");
			
			//jezeli istnieje
			if($is_exists)
			{
				//sprawdzamy, czy taki jezyk nie jest juz ustawiony
				//if((isset($_SESSION['lang']) && $_SESSION['lang']!=$lang) || !isset($_SESSION['lang']))
				{
					$_SESSION['lang'] = $lang;
					self::get_lang_texts();
				}
			}
			else
			{
				//jezeli taki jezyk nie istnieje to ustawiamy na domyslny
				if(isset($_SESSION['lang']) && $_SESSION['lang']!=$default_lang)
					$_SESSION['lang'] = $default_lang;
			}
		}
		else
		{
			//jezeli nie ma zadnego jezyka ustawiamy na domyslny
			//if(!isset($_SESSION['lang']))
			{
				$_SESSION['lang'] = $default_lang;
				self::get_lang_texts();
			}
		}	

		return true;
	}
	
	public static function get_default_lang()
	{
		return db::get_one("SELECT short FROM langs WHERE is_default=1");
	}
	
	public static function get_lang_texts()
	{
		$a_texts = db::get_all('lang_texts','',"name,value_{$_SESSION['lang']}");
		
		foreach($a_texts as $a_text)
		{
			$_SESSION['lang_texts'][$a_text['name']] = $a_text["value_{$_SESSION['lang']}"];
		}
	}
	
	public static function get_langs($only_active=false,$without_default=false)
	{
		$sql_active = $only_active ? "AND is_active=1" : '';
		$sql_without_default = $without_default ? "AND is_default<>1" : '';
		return db::get_many("SELECT * FROM langs WHERE 1=1 $sql_active $sql_without_default ORDER BY `order`");
	}
	
	public static function add_lang($ia_lang)
	{
		if(db::get_one("SELECT 1 FROM langs WHERE short='{$ia_lang['short']}'"))
		{
			app::err('Masz już język o skrócie '.$ia_lang['short']);
			return false;
		}
		
		$is_active = isset($ia_lang['is_active']) ? 1 : 0;
		
		$id = db::insert('langs', array('name'=>$ia_lang['name'],'short'=>$ia_lang['short'],'is_active'=>$is_active));

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		if(isset($ia_lang['is_default']))
		{
			db::update('langs','1=1',array('is_default'=>0));
			db::update('langs','id_langs='.$id,array('is_default'=>1));
		}
		
		db::update('langs','id_langs='.$id,array('langs.order'=>$id));
		
		db::query("ALTER TABLE sites ADD COLUMN title_{$ia_lang['short']} TEXT NULL AFTER title");
		db::query("ALTER TABLE sites ADD COLUMN text_{$ia_lang['short']} TEXT NULL AFTER text");
		db::query("ALTER TABLE sites ADD COLUMN appetizer_{$ia_lang['short']} TEXT NULL AFTER appetizer");
		db::query("ALTER TABLE sites ADD COLUMN sludge_{$ia_lang['short']} TEXT NULL AFTER sludge");
		db::query("ALTER TABLE sites ADD COLUMN seo_title_{$ia_lang['short']} TEXT NULL AFTER seo_title");
		db::query("ALTER TABLE sites ADD COLUMN seo_keywords_{$ia_lang['short']} TEXT NULL AFTER seo_keywords");
		db::query("ALTER TABLE sites ADD COLUMN seo_description_{$ia_lang['short']} TEXT NULL AFTER seo_description");
	
		db::query("ALTER TABLE galleries ADD COLUMN title_{$ia_lang['short']} TEXT NULL AFTER title");
		db::query("ALTER TABLE galleries ADD COLUMN sludge_{$ia_lang['short']} TEXT NULL AFTER title");
		db::query("ALTER TABLE galleries ADD COLUMN seo_title_{$ia_lang['short']} TEXT NULL AFTER seo_title");
		db::query("ALTER TABLE galleries ADD COLUMN seo_keywords_{$ia_lang['short']} TEXT NULL AFTER seo_keywords");
		db::query("ALTER TABLE galleries ADD COLUMN seo_description_{$ia_lang['short']} TEXT NULL AFTER seo_description");
	
		db::query("ALTER TABLE article_categories ADD COLUMN title_{$ia_lang['short']} TEXT NULL AFTER title");
		db::query("ALTER TABLE article_categories ADD COLUMN sludge_{$ia_lang['short']} TEXT NULL AFTER title");
		db::query("ALTER TABLE article_categories ADD COLUMN seo_title_{$ia_lang['short']} TEXT NULL AFTER seo_title");
		db::query("ALTER TABLE article_categories ADD COLUMN seo_keywords_{$ia_lang['short']} TEXT NULL AFTER seo_keywords");
		db::query("ALTER TABLE article_categories ADD COLUMN seo_description_{$ia_lang['short']} TEXT NULL AFTER seo_description");
	
		db::query("ALTER TABLE slides ADD COLUMN title_{$ia_lang['short']} TEXT NULL AFTER title");
		db::query("ALTER TABLE slides ADD COLUMN link_{$ia_lang['short']} TEXT NULL AFTER link");
		
	
		db::query("ALTER TABLE lang_texts ADD COLUMN value_{$ia_lang['short']} TEXT NULL AFTER value_PL");
		db::query("ALTER TABLE menu ADD COLUMN name_{$ia_lang['short']} TEXT NULL AFTER name");
	
	
		app::ok('Dodano nowy język');	
		return $id;		
	}

	public static function edit_langs($ia_langs,$id_default)
	{
		foreach($ia_langs as $id_langs=>$ia_lang)
		{
			if(db::get_one("SELECT 1 FROM langs WHERE short='{$ia_lang['short']}' AND id_langs<>$id_langs"))
			{
				app::err('Masz już język o skrócie '.$ia_lang['short']);
				return false;
			}
			db::deb();
			$old_short = db::get_one("SELECT short FROM langs WHERE id_langs=$id_langs");
			
			$is_active = isset($ia_lang['is_active']) ? 1 : 0;
			
			db::update('langs', "id_langs=$id_langs",array('name'=>$ia_lang['name'],'short'=>$ia_lang['short'],'is_active'=>$is_active));

			db::update('langs','1=1',array('is_default'=>0));
			db::update('langs','id_langs='.$id_default,array('is_default'=>1));
			
			if($old_short!=$ia_lang['short'])
			{
				db::query("ALTER TABLE sites CHANGE title_{$old_short} title_{$ia_lang['short']} TEXT NULL");
				db::query("ALTER TABLE sites CHANGE text_{$old_short} text_{$ia_lang['short']} TEXT NULL");
				db::query("ALTER TABLE sites CHANGE appetizer_{$old_short} appetizer_{$ia_lang['short']} TEXT NULL");
				db::query("ALTER TABLE sites CHANGE sludge_{$old_short} sludge_{$ia_lang['short']} TEXT NULL");
				db::query("ALTER TABLE sites CHANGE seo_title_{$old_short} seo_title_{$ia_lang['short']} TEXT NULL");
				db::query("ALTER TABLE sites CHANGE seo_keywords_{$old_short} seo_keywords_{$ia_lang['short']} TEXT NULL");
				db::query("ALTER TABLE sites CHANGE seo_description_{$old_short} seo_description_{$ia_lang['short']} TEXT NULL");
	
				db::query("ALTER TABLE galleries CHANGE title_{$ia_lang['short']} TEXT NULL AFTER title");
				db::query("ALTER TABLE galleries CHANGE sludge_{$ia_lang['short']} TEXT NULL AFTER title");
				db::query("ALTER TABLE galleries CHANGE seo_title_{$ia_lang['short']} TEXT NULL AFTER seo_title");
				db::query("ALTER TABLE galleries CHANGE seo_keywords_{$ia_lang['short']} TEXT NULL AFTER seo_keywords");
				db::query("ALTER TABLE galleries CHANGE seo_description_{$ia_lang['short']} TEXT NULL AFTER seo_description");
			
				db::query("ALTER TABLE article_categories CHANGE title_{$ia_lang['short']} TEXT NULL AFTER title");
				db::query("ALTER TABLE article_categories CHANGE sludge_{$ia_lang['short']} TEXT NULL AFTER title");
				db::query("ALTER TABLE article_categories CHANGE seo_title_{$ia_lang['short']} TEXT NULL AFTER seo_title");
				db::query("ALTER TABLE article_categories CHANGE seo_keywords_{$ia_lang['short']} TEXT NULL AFTER seo_keywords");
				db::query("ALTER TABLE article_categories CHANGE seo_description_{$ia_lang['short']} TEXT NULL AFTER seo_description");
	
				db::query("ALTER TABLE slides CHANGE title_{$ia_lang['short']} TEXT NULL AFTER title");
				db::query("ALTER TABLE slides CHANGE link_{$ia_lang['short']} TEXT NULL AFTER link");
				
				db::query("ALTER TABLE menu CHANGE name_{$old_short} name_{$ia_lang['short']} TEXT NULL AFTER name");
				db::query("ALTER TABLE lang_texts CHANGE value_{$old_short} value_{$ia_lang['short']} TEXT NULL");
			}
		}
		
		app::ok('Języki zostały zapisane');	
		return true;		
	}

	public static function delete_lang($id_lang)
	{
		$short = db::get_one("SELECT short FROM langs WHERE id_langs=".$id_lang);
		db::query("DELETE FROM langs WHERE id_langs=".$id_lang);
		db::query("ALTER TABLE sites DROP title_{$short}");
		db::query("ALTER TABLE sites DROP text_{$short}");
		db::query("ALTER TABLE sites DROP appetizer_{$short}");
		db::query("ALTER TABLE sites DROP sludge_{$short}");
		db::query("ALTER TABLE sites DROP seo_title_{$short}");
		db::query("ALTER TABLE sites DROP seo_keywords_{$short}");
		db::query("ALTER TABLE sites DROP seo_description_{$short}");
		
		db::query("ALTER TABLE galleries DROP title_{$ia_lang['short']}");
		db::query("ALTER TABLE galleries DROP sludge_{$ia_lang['short']}");
		db::query("ALTER TABLE galleries DROP seo_title_{$ia_lang['short']}");
		db::query("ALTER TABLE galleries DROP seo_keywords_{$ia_lang['short']}");
		db::query("ALTER TABLE galleries DROP seo_description_{$ia_lang['short']}");
	
		db::query("ALTER TABLE article_categories DROP title_{$ia_lang['short']}");
		db::query("ALTER TABLE article_categories DROP sludge_{$ia_lang['short']}");
		db::query("ALTER TABLE article_categories DROP seo_title_{$ia_lang['short']}");
		db::query("ALTER TABLE article_categories DROP seo_keywords_{$ia_lang['short']}");
		db::query("ALTER TABLE article_categories DROP seo_description_{$ia_lang['short']}");
		
		db::query("ALTER TABLE slides DROP title_{$ia_lang['short']}");
		db::query("ALTER TABLE slides DROP link_{$ia_lang['short']}");
				
		
		db::query("ALTER TABLE menu DROP name_{$short}");
		db::query("ALTER TABLE lang_texts DROP value_{$short}");
	}

	public static function get($name,$is_return=0)
	{
		if(isset($_SESSION['lang_texts'][$name]))
		{
			if($is_return===0)
				echo $_SESSION['lang_texts'][$name];
			else
				return $_SESSION['lang_texts'][$name];
		}
		else
		{
			if($is_return===0)
				echo 'xxx';
			else
				return 'xxx';
		}
	}
	
	public static function get_lang()
	{
		if(isset($_SESSION['lang']))
			return $_SESSION['lang'];
		else
			return self::get_default_lang();
	}
	
}

?>