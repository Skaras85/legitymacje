<?php

class mod_panel extends db{
	
	public static $js = 'js/';
	
	public static function check_slide_data($ia_slide)
	{
		$kom = "Nie dodano slide'a: ";
		if($ia_slide['title']=='')
		{
			app::err($kom.'nie wpisałeś tytułu');
			return false;
		}
		
		/*if($ia_slide['link']=='')
		{
			app::err($kom.'nie podałeś linku');
			return false;
		}*/
		
		return true;
	}
	
	public static function add_slide($ia_slide)
	{
		$_SESSION['form']['a_slide']=$ia_slide;
		
		if(!self::check_slide_data($ia_slide))
			return false;

		$a_dane = array('title'=>$ia_slide['title'],
						 'link'=>$ia_slide['link']
						 //'date_from'=>$ia_slide['date_from'],
						 //'date_to'=>$ia_slide['date_to']
						 );
						 
		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_slide['title_'.$a_lang['short']],
									  "link_{$a_lang['short']}"=>$ia_slide['link_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);
		
		$id = db::insert('slides',$a_dane);

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		db::update('slides','id_slides='.$id,array('slides.order'=>$id));

		if(is_uploaded_file($_FILES['image']['tmp_name']))
		{
			$url="images/slides/".$id;

			$result=hlp_image::save($_FILES['image'],$url);
			
			if(!$result)
				return false;
		}
		
		unset($_SESSION['form']['a_slide']);		
		app::ok('Dodano slide '.$ia_slide['title']);	
		return true;		
	}
	
	public static function edit_slide($ia_slide)
	{
		$_SESSION['form']['a_slide']=$ia_slide;
		
		if(!self::check_slide_data($ia_slide))
			return false;

		$a_dane = array('title'=>$ia_slide['title'],
					   //'date_from'=>$ia_slide['date_from'],
					  // 'date_to'=>$ia_slide['date_to']
						'link'=>$ia_slide['link']);

		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_slide['title_'.$a_lang['short']],
									  "link_{$a_lang['short']}"=>$ia_slide['link_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);
	
		$wynik = db::update('slides','id_slides='.$ia_slide['id_slides'],$a_dane);

		if($wynik===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		if(is_uploaded_file($_FILES['image']['tmp_name']))
		{
			$url="images/slides/".$ia_slide['id_slides'];

			$a_zdjecie = glob($url.'.*');
			
			if(count($a_zdjecie[0]));
				unlink($a_zdjecie[0]);

			$result=hlp_image::save($_FILES['image'],$url);

			if(!$result)
				return false;
		}
		
		unset($_SESSION['form']['a_slide']);		
		app::ok('Zedytowano slide '.$ia_slide['title']);	
		return true;		
	}
	
	public static function check_menu_data($ia_menu)
	{
		$kom = "Nie dodano pozycji do menu: ";

		if($ia_menu['name']=='')
		{
			app::err($kom.'nie podałeś nazwy');
			return false;
		}
		
		if($ia_menu['type']=='')
		{
			app::err($kom.'nie wybrałeś gdzie link ma prowadzić');
			return false;
		}
		
		if(preg_match('/[^0-9]/',$ia_menu['parent_id']))
		{
			app::err($kom.'nieprawidowy numer menu');
			return false;
		}
		
		if(isset($ia_menu['id']) && preg_match('[^0-9]',$ia_menu['id']))
		{
			app::err($kom.'nieprawidłowe id elementu, do którego link ma prowadzić');
			return false;
		}
		
		return true;
	}
	
	public static function add_menu($ia_menu)
	{
		$_SESSION['form']['a_menu']=$ia_menu;
		
		if(!self::check_menu_data($ia_menu))
			return false;

		if(!isset($ia_menu['id']))
			$ia_menu['id']=0;
		
		if(!isset($ia_menu['link']))
			$ia_menu['link']='';
		
		$a_dane = array('name'=>$ia_menu['name'],
				'type'=>$ia_menu['type'],'id'=>$ia_menu['id'],'parent_id'=>$ia_menu['parent_id'],'link'=>$ia_menu['link']);
		
		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("name_{$a_lang['short']}"=>$ia_menu['name_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);

		$id = db::insert('menu', $a_dane);

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		db::update('menu','id_menu='.$id,array('menu.order'=>$id));

		unset($_SESSION['form']['a_menu']);		
		app::ok('Dodano menu '.$ia_menu['name']);	
		return true;		
	}
	
	public static function edytuj_menu($ia_menu)
	{
		$_SESSION['form']['a_menu']=$ia_menu;
		
		if(!self::check_menu_data($ia_menu))
			return false;

		if(!isset($ia_menu['id']))
			$ia_menu['id']=0;
		
		if(!isset($ia_menu['link']))
			$ia_menu['link']='';
			
		$a_dane = array('name'=>$ia_menu['name'],
				'type'=>$ia_menu['type'],'id'=>$ia_menu['id'],'parent_id'=>$ia_menu['parent_id'],'link'=>$ia_menu['link']);
		
		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("name_{$a_lang['short']}"=>$ia_menu['name_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);

		$id = db::update('menu','id_menu='.$ia_menu['id_menu'] ,$a_dane);

		if($id===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		unset($_SESSION['form']['a_menu']);		
		app::ok('Zedytowano pozycję w menu '.$ia_menu['name']);	
		return true;		
	}
	
	public static function get_all_menu()
	{
		$a_main_menus=db::get_many('SELECT * FROM menu WHERE parent_id=0 AND is_visible=1 ORDER BY menu.order');
		
		if($a_main_menus)
		{
			foreach($a_main_menus as $key=>$a_main_menu)
			{
				$a_main_menus[$key]['link'] = self::get_menu_link($a_main_menu);
				$a_main_menus[$key]['a_submenu'] = db::get_many("SELECT * FROM menu WHERE is_visible=1 AND parent_id=".$a_main_menu['id_menu']." ORDER BY `order`");
	
				if($a_main_menus[$key]['a_submenu'])
				{
					foreach($a_main_menus[$key]['a_submenu'] as $key2=>$a_podmenu)
					{
						$a_main_menus[$key]['a_submenu'][$key2]['link'] = self::get_menu_link($a_podmenu);
					}
				}
				
				if($a_main_menus[$key]['type']=='dishes')
				{
					
					$a_kategorie=db::get_many("SELECT * FROM dishes_categories WHERE is_visible=1 ORDER by dishes_categories.order");
					$a_main_menus[$key]['link'] = "sites/menu/".$a_kategorie[0]['id_dishes_categories']."/".$a_kategorie[0]['sludge'];
					
					if($a_kategorie)
					{
						foreach($a_kategorie as $key2=>$a_kategoria)
						{
							$a_main_menus[$key]['a_submenu'][$key2]['link'] = "sites/menu/".$a_kategoria['id_dishes_categories']."/".$a_kategoria['sludge'];
							$a_main_menus[$key]['a_submenu'][$key2]['name'] = $a_kategoria['title'];
						}
					}
				}
			}
		}
		return $a_main_menus;
	}
	
	public static function get_menu_link($a_menu)
	{
		if(lang::get_lang()!=lang::get_default_lang())
			$lang = '_'.lang::get_lang();
		else
			$lang = '';
		
		if($a_menu['type']=='main')
		{
			return '';
		}
		elseif($a_menu['type']=='site')
		{
			$sludge=db::get_one("SELECT sludge{$lang} FROM sites WHERE id_sites=".$a_menu['id']);
			return $sludge;
		}
		elseif($a_menu['type']=='sites_by_category')
		{
			$sludge=db::get_one("SELECT sludge{$lang} FROM article_categories WHERE id_article_categories=".$a_menu['id']);
			return 'strony/artykuly/'.$sludge.','.$a_menu['id'];
		}
		elseif($a_menu['type']=='galleries')
		{
			return 'galerie/lista-galerii';
		}
		elseif($a_menu['type']=='gallery')
		{
			$sludge=db::get_one("SELECT sludge{$lang} FROM galleries WHERE id_galleries=".$a_menu['id']);
			return 'galerie/'.$sludge.','.$a_menu['id'];

		}
		elseif($a_menu['type']=='dishes')
		{
			$a_menu=db::get_row("SELECT * FROM dishes_categories ORDER BY id_dishes_categories LIMIT 1");
			return 'sites/menu/'.$a_menu['id_dishes_categories'].'/'.$a_menu['sludge'];
		}
		elseif($a_menu['type']=='link')
		{
			return $a_menu['link'];
		}
		elseif($a_menu['type']=='blog')
		{
			return 'blog';
		}
		else
			return false;
	}
	
	public static function get_chosen_menu_elements()
	{
		$url='';
		$modul='';
		$action='';
		if(count($_GET)>0)
		{
			$a_url = array_keys($_GET);
			$url = trim($a_url[0],'/');
			
			$a_url = explode('/',trim($a_url[0],'/'));
	
			if(isset($a_url[0]) && !isset($a_url[10]))
				$modul = 'strony';
			
			//galerie/lista-galerii
			if(isset($a_url[1]) && !preg_match('/([A-Za-z0-9\.\-_]+),([0-9]+)/',$a_url[1],$a_saved))
				$action = $a_url[1];
			if(!isset($a_url[1]) && !isset($a_url[2]) && $modul!='')
			{
				//np. blog
				$default_action = eval('return con_'.$modul.'::$default_action;');
				$action = str_replace('-', '_', $default_action);
			}
			elseif(isset($a_url[1]) && preg_match('/([A-Za-z0-9\.\-_]+),([0-9]+)+/',$a_url[1],$a_saved))
			{
				//np sites/jakis-sludge,10
				$default_action = eval('return con_'.$modul.'::$default_action;');
				$action = str_replace('-', '_', $default_action);
			}
			elseif(isset($a_url[2]) && preg_match('/([A-Za-z0-9\.\-_]+),([0-9]+)/',$a_url[2],$a_saved))
			{
				//np sites/artykuly,jakis-sludge,10
				$action = $a_url[1];
			}
		}

		view::add('chosen_menu','');
		view::add('chosen_submenu',$url);
		view::add('module',$modul);
		view::add('action',$action);

		//jezeli modul to sites
		if($modul=='strony')
		{
			if($action=='strona')
			{
				//jesli drugi element adresu to numer (id)
				if(isset($a_saved[2]) && preg_match('/[0-9]+/',$a_saved[2]))
				{
					$id = $a_saved[2];
					view::add('chosen_article',$id);

					$type = db::get_one("SELECT type FROM menu WHERE type='site' AND id=".$id);

					//sprawdzamy typ strony. NIektore strony go nie maja, bo wogole nie sa przypisane
					//do tabeli menu bezposrednio, a posrednio przez tabele article_categories
					if($type)
					{
						//sprawdzamy, czy dla tego id w tabeli menu jest jakis
						//parent_id. Jesli jest oznacza, ze jest to podmenu
						$parent_id = db::get_one("SELECT parent_id FROM menu WHERE id=".$id);

						//podmenu
						if($parent_id!=0)	//np. 2
						{//db::deb();var_dump(db::get_row("SELECT menu.* FROM menu  WHERE id_menu=".$parent_id));
							view::add('chosen_menu',mod_panel::get_menu_link(db::get_row("SELECT menu.* FROM menu WHERE id_menu=".$parent_id)));
							view::add('chosen_submenu',$url);
						}
						else
						{
							if($type=='sites_by_category')
								view::add('chosen_menu',mod_panel::get_menu_link(db::get_row("SELECT menu.* FROM menu JOIN sites ON id_sites=id WHERE type='sites_by_category' AND id=".$id)));
							else
								view::add('chosen_menu',mod_panel::get_menu_link(db::get_row("SELECT menu.* FROM menu JOIN sites ON id_sites=id WHERE type='site' AND id=".$id)));	
						}
					}
					else
					{
						$id_article_categories = db::get_one("SELECT id_article_categories FROM sites JOIN article_categories USING(id_article_categories) WHERE id_sites=".$id);
						
						if($id_article_categories)
						{
							//opcja specjalnie dla bloga, który ma id_article_categories=5
							//if($id_article_categories==5)
							//	view::add('chosen_menu','blog');
							//else
							{
								$a_menu = db::get_row("SELECT * FROM menu WHERE type='sites_by_category' AND id=".$id_article_categories);
								$chosen_menu=mod_panel::get_menu_link(db::get_row("SELECT menu.* FROM menu WHERE type='sites_by_category' AND id=".$id_article_categories));
							
								if($chosen_menu)
								{
									view::add('chosen_menu',$chosen_menu);
									view::add('chosen_submenu',mod_panel::get_menu_link(db::get_row("SELECT * FROM menu WHERE id_menu=".$a_menu['id_menu'])));
								}
							}
						}
					}
				}
			}
			elseif($action=='menu')
			{	
				if(isset($a_url[2]) && preg_match('/[0-9]+/',$a_url[2]))
				{
					$id = $a_url[2];
					view::add('chosen_article',$id);
					view::add('chosen_menu',mod_panel::get_menu_link(db::get_row("SELECT * FROM menu WHERE type='dishes'")));
				}
			}
			elseif($action=='artykuly')
			{	
				$id = $a_saved[2];
				view::add('chosen_menu',mod_panel::get_menu_link(db::get_row("SELECT * FROM menu WHERE type='sites_by_category' AND id=$id")));
			}
		}
		elseif($modul=='galerie')
		{
			if($action=='lista-galerii')
				view::add('chosen_menu',mod_panel::get_menu_link(array('type'=>'galleries')));
			elseif($action=='pokaz')
			{
				if(isset($a_saved[2]))
				{
					$id = $a_saved[2];
					$a_submenu = db::get_row("SELECT * FROM menu WHERE type='gallery' AND id=$id");

					if($a_submenu)
						$a_menu = db::get_row("SELECT menu.* FROM menu WHERE id_menu=".$a_submenu['parent_id']);
					
					if(isset($a_menu) && $a_menu)
						view::add('chosen_menu',mod_panel::get_menu_link($a_menu));
					else
						view::add('chosen_menu',mod_panel::get_menu_link($a_submenu));
					view::add('chosen_submenu',mod_panel::get_menu_link($a_submenu));
				}
			}
		}
		elseif($modul=='blog')
		{
			view::add('chosen_menu','blog');
		}
	}
	
	public static function check_article_categorie_data($ia_kategoria)
	{
		$kom = "Nie zapisano kategorii artykułu: ";
		
		if(isset($ia_kategoria['id_article_categories']) && !hlp_validator::id($ia_kategoria['id_article_categories']))
		{
			app::err($kom.'nieprawidłowy numer kategorii');
			return false;
		}
		
		if($ia_kategoria['title']=='')
		{
			app::err($kom.'nie podałeś tytułu');
			return false;
		}
		
		return true;
	}
	
	public static function add_article_categorie($ia_kategoria)
	{
		$_SESSION['form']['a_kategoria']=$ia_kategoria;
		
		if(!self::check_article_categorie_data($ia_kategoria))
			return false;

		$a_dane = array('title'=>$ia_kategoria['title'],
						 'sludge'=>$ia_kategoria['sludge'],
						 'seo_keywords'=>$ia_kategoria['seo_keywords'],
						 'seo_title'=>$ia_kategoria['seo_title'],
						 'seo_description'=>$ia_kategoria['seo_description']
						);

		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_kategoria['title_'.$a_lang['short']],
									  "sludge_{$a_lang['short']}"=>$ia_kategoria['sludge_'.$a_lang['short']],
									  "seo_title_{$a_lang['short']}"=>$ia_kategoria['seo_title_'.$a_lang['short']],
									  "seo_keywords_{$a_lang['short']}"=>$ia_kategoria['seo_keywords_'.$a_lang['short']],
									  "seo_description_{$a_lang['short']}"=>$ia_kategoria['seo_description_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);
		
		$id = db::insert('article_categories',$a_dane);

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		db::update('article_categories','id_article_categories='.$id,array('article_categories.order'=>$id));

		unset($_SESSION['form']['a_kategoria']);		
		app::ok('Dodano kategorię artykułu '.$ia_kategoria['title']);	
		return true;		
	}

	public static function edytuj_kategorie_artykulu($ia_kategoria)
	{
		$_SESSION['form']['a_kategoria']=$ia_kategoria;
		
		if(!self::check_article_categorie_data($ia_kategoria))
			return false;

		$a_dane = array('title'=>$ia_kategoria['title'],
						 'sludge'=>$ia_kategoria['sludge'],
						 'seo_keywords'=>$ia_kategoria['seo_keywords'],
						 'seo_title'=>$ia_kategoria['seo_title'],
						 'seo_description'=>$ia_kategoria['seo_description']
						);

		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_kategoria['title_'.$a_lang['short']],
									  "sludge_{$a_lang['short']}"=>$ia_kategoria['sludge_'.$a_lang['short']],
									  "seo_title_{$a_lang['short']}"=>$ia_kategoria['seo_title_'.$a_lang['short']],
									  "seo_keywords_{$a_lang['short']}"=>$ia_kategoria['seo_keywords_'.$a_lang['short']],
									  "seo_description_{$a_lang['short']}"=>$ia_kategoria['seo_description_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);

		$id = db::update('article_categories','id_article_categories='.$ia_kategoria['id_article_categories'], $a_dane);

		if($id===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		unset($_SESSION['form']['a_kategoria']);		
		app::ok('Zedytowano kategorię artykułu '.$ia_kategoria['title']);	
		return true;		
	}
	
	/******************/
	
	public static function check_dishes_categorie_data($ia_kategoria)
	{
		$kom = "Nie zapisano kategorii do karty dań: ";
		
		if(isset($ia_kategoria['id_dishes_categories']) && !hlp_validator::id($ia_kategoria['id_dishes_categories']))
		{
			app::err($kom.'nieprawidłowy numer kategorii');
			return false;
		}
		
		if($ia_kategoria['title']=='')
		{
			app::err($kom.'nie podałeś tytułu');
			return false;
		}
		
		if($ia_kategoria['sludge']=='')
		{
			app::err($kom.'nie podałeś przyjaznego linku');
			return false;
		}
		
		return true;
	}
	
	public static function add_dishes_categorie($ia_kategoria)
	{
		$_SESSION['form']['a_kategoria']=$ia_kategoria;
		
		if(!self::check_dishes_categorie_data($ia_kategoria))
			return false;

		$id = db::insert('dishes_categories', array('title'=>$ia_kategoria['title'],'sludge'=>$ia_kategoria['sludge']));

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		db::update('dishes_categories','id_dishes_categories='.$id,array('dishes_categories.order'=>$id));

		unset($_SESSION['form']['a_kategoria']);		
		app::ok('Dodano do karty dań kategorię '.$ia_kategoria['title']);	
		return true;		
	}

	public static function edytuj_kategorie_dan($ia_kategoria)
	{
		$_SESSION['form']['a_kategoria']=$ia_kategoria;
		
		if(!self::check_dishes_categorie_data($ia_kategoria))
			return false;

		$id = db::update('dishes_categories','id_dishes_categories='.$ia_kategoria['id_dishes_categories'] ,array('title'=>$ia_kategoria['title'],'sludge'=>$ia_kategoria['sludge']));

		if($id===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		unset($_SESSION['form']['a_kategoria']);		
		app::ok('Zedytowano z karty dań kategorię '.$ia_kategoria['title']);	
		return true;		
	}
	
	/*************************/
	
	public static function check_dish_data($ia_danie)
	{
		$kom = "Nie zapisano dania: ";
		
		if(isset($ia_danie['id_dishes']) && !hlp_validator::id($ia_danie['id_dishes']))
		{
			app::err($kom.'nieprawidłowy numer dania');
			return false;
		}
		
		if($ia_danie['name']=='')
		{
			app::err($kom.'nie podałeś tytułu');
			return false;
		}
		
		if(!hlp_validator::price($ia_danie['price']))
		{
			app::err($kom.'nieprawidłowy format ceny');
			return false;
		}
		
		if($ia_danie['price_second']!='' && !hlp_validator::price($ia_danie['price_second']))
		{
			app::err($kom.'nieprawidłowy format ceny za butelkę');
			return false;
		}

		if(!hlp_validator::id($ia_danie['id_dishes_categories']))
		{
			app::err($kom.'nie wybrałeś kategorii dania');
			return false;
		}
		
		return true;
	}
	
	public static function add_dish($ia_danie)
	{
		$_SESSION['form']['a_danie']=$ia_danie;
		
		if(!self::check_dish_data($ia_danie))
			return false;
		
		$_SESSION['saved_id_dishes_categories'] = $ia_danie['id_dishes_categories'];

		if($ia_danie['price_second']=='')
			$ia_danie['price_second']=0;

		$id = db::insert('dishes', array('name'=>$ia_danie['name'],'id_dishes_categories'=>$ia_danie['id_dishes_categories'],'price'=>$ia_danie['price'],'eng_description'=>$ia_danie['eng_description'],'pl_description'=>$ia_danie['pl_description'],'price_second'=>$ia_danie['price_second']));

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		db::update('dishes','id_dishes='.$id,array('dishes.order'=>$id));

		unset($_SESSION['form']['a_danie']);		
		app::ok('Dodano do karty danię '.$ia_danie['name']);	
		return $id;		
	}
	
	public static function edytuj_danie($ia_danie)
	{
		$_SESSION['form']['a_danie']=$ia_danie;
		
		if(!self::check_dish_data($ia_danie))
			return false;
		
		if($ia_danie['price_second']=='')
			$ia_danie['price_second']=0;

		$id = db::update('dishes','id_dishes='.$ia_danie['id_dishes'], array('name'=>$ia_danie['name'],'id_dishes_categories'=>$ia_danie['id_dishes_categories'],'price'=>$ia_danie['price'],'eng_description'=>$ia_danie['eng_description'],'pl_description'=>$ia_danie['pl_description'],'price_second'=>$ia_danie['price_second']));

		if($id===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		unset($_SESSION['form']['a_danie']);		
		app::ok('Zedytowano danię '.$ia_danie['name']);	
		return $id;		
	}
	
	/*******************ads***************************/
	
	public static function check_ad_data($ia_ad)
	{
		$kom = "Nie dodano reklamy: ";
		if($ia_ad['title']=='')
		{
			app::err($kom.'nie wpisałeś tytułu');
			return false;
		}
		
		if(!hlp_validator::id($ia_ad['width']))
		{
			app::err($kom.'nieprawidłowa szerokość reklamy');
			return false;
		}
		
		if(!hlp_validator::id($ia_ad['height']))
		{
			app::err($kom.'nieprawidłowa wysokość reklamy');
			return false;
		}
		
		/*if($ia_strona['link']=='')
		{
			app::err($kom.'nie podałeś linku');
			return false;
		}*/
		
		return true;
	}
	
	public static function add_ad($ia_ad)
	{
		$_SESSION['form']['a_ad']=$ia_ad;
		
		if(!self::check_ad_data($ia_ad))
			return false;
		
		if(isset($ia_ad['is_cookie']))
			$is_cookie = true;
		else
			$is_cookie = false;

		$id = db::insert('ads', array('title'=>$ia_ad['title'],
										 'link'=>$ia_ad['link'],
										 'text'=>$ia_ad['text'],
										 'type'=>$ia_ad['type'],
										 'bg_color'=>$ia_ad['bg_color'],
										 'width'=>$ia_ad['width'],
										 'height'=>$ia_ad['height'],
										 'is_cookie'=>$is_cookie,
										 'date_from'=>$ia_ad['date_from'],
										 'date_to'=>$ia_ad['date_to']));

		if(!$id)
		{
			app::err('Błąd bazy danych');
			return false;
		}

		db::update('ads','id_ads='.$id,array('ads.order'=>$id));

		if(is_uploaded_file($_FILES['image']['tmp_name']))
		{
			$url="images/ads/".$id;

			$result=hlp_image::save($_FILES['image'],$url);
			
			if(!$result)
				return false;
		}
		
		unset($_SESSION['form']['a_ad']);		
		app::ok('Dodano reklamę '.$ia_ad['title']);	
		return true;		
	}
	
	public static function edit_ad($ia_ad)
	{
		$_SESSION['form']['a_ad']=$ia_ad;
		
		if(!self::check_ad_data($ia_ad))
			return false;

		if(isset($ia_ad['is_cookie']))
			$is_cookie = true;
		else
			$is_cookie = false;

		$wynik = db::update('ads','id_ads='.$ia_ad['id_ads'],array('title'=>$ia_ad['title'],
																   'link'=>$ia_ad['link'],
																   'text'=>$ia_ad['text'],
																   'type'=>$ia_ad['type'],
																   'bg_color'=>$ia_ad['bg_color'],
																   'width'=>$ia_ad['width'],
										 						   'height'=>$ia_ad['height'],
																   'is_cookie'=>$is_cookie,
																   'date_from'=>$ia_ad['date_from'],
							 									   'date_to'=>$ia_ad['date_to']));

		if(isset($ia_ad['usun_zdjecie']))
			hlp_image::delete('images/ads/'.$ia_ad['id_ads']);

		if($wynik===false)
		{
			app::err('Błąd bazy danych');
			return false;
		}
		
		if($ia_ad['type']=='floater')
			 db::update('ads','id_ads='.$ia_ad['id_ads'],array('position_x'=>$ia_ad['position_x'],
			 												   'position_y'=>$ia_ad['position_y'],
			 												   'remove_after'=>$ia_ad['remove_after'],
			 												   'attachment'=>$ia_ad['attachment']));

		if(is_uploaded_file($_FILES['image']['tmp_name']))
		{
			$url="images/ads/".$ia_ad['id_ads'];

			$a_zdjecie = glob($url.'.*');
			
			if(count($a_zdjecie[0]));
				unlink($a_zdjecie[0]);

			$result=hlp_image::save($_FILES['image'],$url);
			
			if(!$result)
				return false;
		}
		
		unset($_SESSION['form']['a_ad']);		
		app::ok('Zedytowano reklamę '.$ia_ad['title']);	
		return true;		
	}

	public static function get_ads($i_type='banner')
	{
		$a_ads = db::get_many("SELECT * FROM ads WHERE is_visible=1 AND type='$i_type' AND date(NOW())>=date_from AND date(NOW())<=date_to ORDER BY ads.order");

		if($a_ads)
		{
			$i=0;
			foreach($a_ads as $a_ad)
			{
				if($a_ad['is_cookie'])
				{
					if(isset($_COOKIE['app_ads_'.$a_ad['id_ads']]))
						unset($a_ads[$i]);
					setcookie('app_ads_'.$a_ad['id_ads'],true);
					$i++;
				}
			}
		}

		return $a_ads;
	}
	
	/*************************************************/
	
	public static function edit_settings($ia_settings)
	{
		$_SESSION['form']['a_settings']=$ia_settings;
		
		if(!is_array($ia_settings))
		{
			app::err('Nie zapisano ustawień, nieprawidłowe dane');
			return false;
		}

		foreach($ia_settings as $key=>$value)
		{
			$is_prop = db::query("SELECT 1 FROM settings WHERE `key`='$key'");
			
			if($is_prop)
			{
				db::update('settings',"`key`='$key'",array('value'=>$value));
			}
		}
		
		$is_image = isset($ia_settings['is_image']) ? 'tak' : 'nie';
		db::update('settings',"`key`='bg_image'",array('value'=>$is_image));
		
		$is_slider_visible = isset($ia_settings['is_slider_visible']) ? 'tak' : 'nie';
		db::update('settings',"`key`='is_slider_visible'",array('value'=>$is_slider_visible));
		
		$is_sticky_header = isset($ia_settings['is_sticky_header']) ? 'tak' : 'nie';
		db::update('settings',"`key`='is_sticky_header'",array('value'=>$is_sticky_header));

		if(is_uploaded_file($_FILES['image']['tmp_name']))
		{
			hlp_image::rrmdir("images/background/",false);
			$url="images/background/".$_FILES['image']['name'];

			hlp_image::save($_FILES['image'],$url);
		}

		unset($_SESSION['form']['a_settings']);		
		app::ok('Zaktualizowano ustawienia ');	
		return true;		
	}

	public static function get_setting($key)
	{
		return db::get_one("SELECT value FROM settings WHERE `key`='$key'");
	}
	
	public static function increase_visit_counter($table,$i_id)
	{
		if(!isset($_COOKIE['site_visited_'.$table.'_'.$i_id]))
		{
			$visit_counter = db::get_one("SELECT visit_counter FROM $table WHERE id_$table=".$i_id);
			setcookie('site_visited_'.$table.'_'.$i_id,true,time()+3600);
			db::update($table,"id_$table=".$i_id,array('visit_counter'=>$visit_counter+1));
		}
	}
	
	public static function set_referer()
	{
		if(!isset($_COOKIE['referer']))
		{
			$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';	
			setcookie('referer',$referer,time()+3600);
		}
	}
	
	public static function wyslij_maila($ia_wiadomosc)
	{
		$_SESSION['form']['wiadomosc']=$ia_wiadomosc;
		
		if(!hlp_validator::email($ia_wiadomosc['email']))
		{
			return false;
		}
		
		if($ia_wiadomosc['temat']=='')
		{
			app::err('Nie wpisałeś tematu');
			return false;
		}
		
		if($ia_wiadomosc['tresc_wiadomosci']=='')
		{
			app::err('Nie wpisałeś treści wiadomości');
			return false;
		}

		mailer::add_address('kontakt@greatescaperoom.pl');
		mailer::send($ia_wiadomosc['temat'],$ia_wiadomosc['tresc_wiadomosci'],array('email'=>$ia_wiadomosc['email'],'name'=>$ia_wiadomosc['email']));
		
		app::ok();
		
		unset($_SESSION['form']['wiadomosc']);
	}
	
	public static function get_parametr($nazwa)
	{
		return db::get_one("SELECT wartosc FROM parametry WHERE nazwa='$nazwa'");
	}
	
	public static function zapisz_parametry($a_parametry)
	{
		if($a_parametry)
		{
			foreach($a_parametry as $id_parametry=>$wartosc)
			{
				db::update('parametry',"id_parametry=$id_parametry",array('wartosc'=>$wartosc));
			}
		}	
		
		return true;
	}
	
}

?>