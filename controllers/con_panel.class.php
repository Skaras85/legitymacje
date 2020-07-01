<?php

class con_panel extends controllers_parent{
	
	public static $default_action = 'panell';
	
	public static function panell__admin()
	{
		view::display('panel/panel.tpl');
	}
	
	public static function formularz_dodawania_slidu__admin()
	{
		view::add('a_langs',lang::get_langs(true,true));
		head::set_title("Dodaj slide");
		view::display('panel/form_slides.tpl');
	}
	
	public static function dodaj_slide__admin()
	{
		router::set_checkpoint('panel');
		mod_panel::add_slide($_POST['a_slide']);
		
		if(!app::get_result())
			view::redirect('panel/formularz-dodawania-slidu');
		else
			view::message();
	}
	
	public static function formularz_edycji_slidu__admin()
	{
		head::add_js_file('javascript/libs/ckeditor/ckeditor.js',false,'head');
		head::set_title("Edytuj slide");

		if(isset($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			view::add('a_langs',lang::get_langs(true,true));
			$a_slide = db::get_by_id('slides', $_GET['id']);
		
			if($a_slide)
				hlp_image::get_files_by_id($a_slide,"images/slides","id_slides");
			view::add('a_slide',$a_slide);
			
			view::display('panel/form_slides.tpl');
		}
		else
		{
			app::err('Nieprawidłowe id slidu');
			view::message();
		}
	}
	
	public static function edytuj_slide__admin()
	{
		router::set_checkpoint('panel/lista-slidow');
		mod_panel::edit_slide($_POST['a_slide']);

		if(!app::get_result())
			view::redirect('panel/formularz-edycji-slidu/'.$_POST['a_slide']['id_slides']);
		else
			view::message();
	}
	
	public static function lista_slidow__admin()
	{
		$a_slides = db::get_many("SELECT * FROM slides ORDER BY slides.order");
		hlp_image::get_files_by_id($a_slides,"images/slides","id_slides");
		view::add('a_slides',$a_slides);
		
		view::display();
	}
	
	public static function zapisz_pozycje_slidow__admin()
	{
		foreach($_POST['a_slides'] as $klucz=>$wartosc)
		{
			db::update('slides', 'id_slides='.$wartosc,array('slides.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_slidow__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['slide_id']))
			db::update('slides', 'id_slides='.$_POST['slide_id'],array('is_visible'=>$val));
	}
	
	public static function usun_slide__admin()
	{
		if(hlp_validator::id($_POST['slide_id']))
		{
			db::query("DELETE FROM slides WHERE id_slides=".$_POST['slide_id']);
			hlp_image::delete('images/slides/'.$_POST['slide_id']);
		}
	}
	
	public static function formularz_menu__admin()
	{
		if($_GET['rodzaj_menu']!='menu' && $_GET['rodzaj_menu']!='podmenu')
		{
			app::err('Nieprawidłowy rodzaj menu');
			view::message();
		}
		
		if(isset($_GET['id']) && !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowe id menu');
			view::message();
		}
		elseif(isset($_GET['id']) && hlp_validator::id($_GET['id']))
			view::add('a_menu',db::get_by_id('menu', $_GET['id']));

		view::add('a_langs',lang::get_langs(true,true));
		view::add('rodzaj_menu',$_GET['rodzaj_menu']);
		view::add('a_sites',db::get_many("SELECT title,id_sites,id_article_categories FROM sites ORDER BY title"));
		view::add('a_kategorie',db::get_many("SELECT id_article_categories,ac.title FROM sites RIGHT JOIN article_categories ac USING(id_article_categories) GROUP BY id_article_categories"));
		view::add('a_lista_menu',db::get_many("SELECT * FROM menu WHERE parent_id=0 ORDER BY menu.order"));
		view::add('a_galerie',db::get_many("SELECT * FROM galleries ORDER BY galleries.order"));
		view::display('panel/form_menu.tpl');

	}
	
	public static function dodaj_menu__admin()
	{
		router::set_checkpoint('panel');
		mod_panel::add_menu($_POST['a_menu']);
		
		if(!app::get_result())
			view::redirect('panel/formularz-menu/rodzaj_menu/'.$_POST['rodzaj_menu'].'/edycja/'.$_POST['edycja']);
		else
			view::message();
	}
	
	public static function lista_podmenu__admin()
	{
		view::add('a_menu',db::get_many("SELECT *,(SELECT COUNT(*) FROM menu m2 WHERE m1.id_menu=m2.parent_id) as submenu FROM menu m1 WHERE parent_id=0 ORDER BY m1.order"));
		view::add('a_submenu',db::get_many("SELECT * FROM menu WHERE parent_id<>0 ORDER BY menu.order"));
		view::display();
	}
	
	public static function lista_menu__admin()
	{
		view::add('a_menu',db::get_many("SELECT * FROM menu WHERE parent_id=0 ORDER BY menu.order"));
		view::display();
	}
	
	public static function zapisz_pozycje_menu__admin()
	{
		foreach($_POST['a_menu'] as $klucz=>$wartosc)
		{
			db::update('menu', 'id_menu='.$wartosc,array('menu.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_menu__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['menu_id']))
			db::update('menu', 'id_menu='.$_POST['menu_id'],array('is_visible'=>$val));
	}
	
	public static function usun_podmenu__admin()
	{
		if(hlp_validator::id($_POST['menu_id']))
			db::query("DELETE FROM menu WHERE id_menu=".$_POST['menu_id']);
	}
	
	public static function usun_menu__admin()
	{
		if(hlp_validator::id($_POST['menu_id']))
		{
			db::query("DELETE FROM menu WHERE id_menu={$_POST['menu_id']} OR parent_id={$_POST['menu_id']}");
		}
	}

	public static function edytuj_menu__admin()
	{
		router::set_checkpoint('panel/lista-menu');
		mod_panel::edytuj_menu($_POST['a_menu']);

		if(!app::get_result())
			view::redirect('panel/formularz_menu/'.$_POST['a_menu']['id_menu'] . '/rodzaj_menu/'.$_POST['rodzaj_menu'].'/edycja/'.$_POST['edycja']);
		else
			view::message();
	}
	
	public static function get_all_menu()
	{
		return mod_panel::get_all_menu();
	}
	
	public static function formularz_dodawania_kategorii_artykulu__admin()
	{
		view::add('a_langs',lang::get_langs(true,true));
		view::display('panel/form_kategorie_artykulow.tpl');
	}

	public static function dodaj_kategorie_artykulu__admin()
	{
		router::set_checkpoint('panel');
		mod_panel::add_article_categorie($_POST['a_kategoria']);
		
		if(!app::get_result())
			view::redirect('panel/formularz-dodawania-kategorii-artykulu');
		else
			view::message();
	}
	
	public static function lista_kategorii_artykulow__admin()
	{
		view::add('a_kategorie',db::get_many("SELECT * FROM article_categories ORDER BY article_categories.order"));
		view::display();
	}
	
	public static function zapisz_pozycje_kategorii_artykulow__admin()
	{
		foreach($_POST['a_kategoria'] as $klucz=>$wartosc)
		{
			db::update('article_categories', 'id_article_categories='.$wartosc,array('article_categories.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_kategorii_artykulow__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['id_kategorii']))
			db::update('article_categories', 'id_article_categories='.$_POST['id_kategorii'],array('is_visible'=>$val));
	}
	
	public static function usun_kategorie_artykulu__admin()
	{
		if(hlp_validator::id($_POST['id_kategorii']))
			db::query("DELETE FROM article_categories WHERE id_article_categories=".$_POST['id_kategorii']);
	}

	public static function formularz_edycji_kategorii_artykulu__admin()
	{
		if(isset($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			view::add('a_langs',lang::get_langs(true,true));
			view::add('a_kategoria',db::get_by_id('article_categories', $_GET['id']));
			view::display('panel/form_kategorie_artykulow.tpl');
		}
		else
		{
			app::err('Nieprawidłowe id kategorii');
			view::message();
		}
	}

	public static function edytuj_kategorie_artykulu__admin()
	{
		router::set_checkpoint('panel/lista-kategorii-artykulow');
		mod_panel::edytuj_kategorie_artykulu($_POST['a_kategoria']);

		if(!app::get_result())
			view::redirect('panel/formularz_edycji_kategorii_artykulu/'.$_POST['kategorie_artykulu']['id_article_categories']);
		else
			view::message();
	}
	
	/************************************************/
	
	public static function formularz_dodawania_kategorii_dan__admin()
	{
		view::display('panel/form_kategorie_dan.tpl');
	}

	public static function dodaj_kategorie_dan__admin()
	{
		router::set_checkpoint('panel/users');
		mod_panel::add_dishes_categorie($_POST['a_kategoria']);
		
		if(!app::get_result())
			view::redirect('panel/formularz-dodawania-kategorii-dan');
		else
			view::message();
	}
	
	public static function lista_kategorii_dan__admin()
	{
		$a_kategorie = db::get_many("SELECT * FROM dishes_categories ORDER BY dishes_categories.order");
		
		if(!$a_kategorie)
		{
			app::err('Brak kategorii dań');
			view::message();
		}
		else	
		{
			view::add('a_kategorie',$a_kategorie);
			view::display();
		}
	}
	
	public static function zapisz_pozycje_kategorii_dan__admin()
	{
		foreach($_POST['a_kategoria'] as $klucz=>$wartosc)
		{
			db::update('dishes_categories', 'id_dishes_categories='.$wartosc,array('dishes_categories.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_kategorii_dan__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['id_kategorii']))
			db::update('dishes_categories', 'id_dishes_categories='.$_POST['id_kategorii'],array('is_visible'=>$val));
	}
	
	public static function usun_kategorie_dan__admin()
	{
		if(hlp_validator::id($_POST['id_kategorii']))
			db::query("DELETE FROM dishes_categories WHERE id_dishes_categories=".$_POST['id_kategorii']);
	}

	public static function formularz_edycji_kategorii_dan__admin()
	{
		if(isset($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			view::add('a_kategoria',db::get_by_id('dishes_categories', $_GET['id']));
			view::display('panel/form_kategorie_dan.tpl');
		}
		else
		{
			app::err('Nieprawidłowe id kategorii');
			view::message();
		}
	}

	public static function edytuj_kategorie_dan__admin()
	{
		router::set_checkpoint('panel/lista-kategorii-dan');
		mod_panel::edytuj_kategorie_dan($_POST['a_kategoria']);

		if(!app::get_result())
			view::redirect('panel/formularz_edycji_kategorii_dan/'.$_POST['kategorie_dan']['id_dishes_categories']);
		else
			view::message();
	}
	
	/********************************/
	
	public static function formularz_dania__admin()
	{
		$a_kategorie = db::get_many("SELECT * FROM dishes_categories ORDER BY dishes_categories.order");
		
		if(!$a_kategorie)
		{
			app::err('Musisz najpierw dodać kategorię dań, aby dodawać dania');
			view::message();
		}
		
		if(isset($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			$a_danie = db::get_by_id('dishes', $_GET['id']);
			if($a_danie)
				hlp_image::get_files_by_id($a_danie, 'images/dishes/thumbs', 'id_dishes');
			view::add('a_danie',$a_danie);
		}
		elseif(isset($_GET['id']) && !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy numer dania');
			view::message();
		}

		if(isset($_SESSION['saved_id_dishes_categories']))
			view::add('saved_id_dishes_categories',$_SESSION['saved_id_dishes_categories']);
		view::add('a_kategorie_dan',$a_kategorie);
		view::display('panel/form_dania.tpl');
	}
	
	public static function dodaj_danie__admin()
	{
		router::set_checkpoint('panel');
		$id=mod_panel::add_dish($_POST['a_danie']);

		if(!app::get_result())
			view::redirect('panel/formularz_dania/edycja/false');
		else
		{
			if(is_uploaded_file($_FILES['foto']['tmp_name']))
			{
				$ext=hlp_image::save($_FILES['foto'],'images/dishes/'.$id);
				
				if($ext)
					hlp_image::save_resized('images/dishes/'.$id.$ext, 'images/dishes/thumbs/'.$id,100);
			}
			//view::message();
			view::redirect('panel/formularz_dania');
		}
	}
	
	public static function lista_dan__admin()
	{
		view::add('a_kategorie',db::get_many("SELECT * FROM dishes_categories ORDER BY dishes_categories.order"));
		view::add('a_dania',db::get_many("SELECT * FROM dishes ORDER BY dishes.order"));
		view::display();
	}

	public static function zapisz_pozycje_dan__admin()
	{
		foreach($_POST['a_dania'] as $klucz=>$wartosc)
		{
			db::update('dishes', 'id_dishes='.$wartosc,array('dishes.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_dania__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['id_dania']))
			db::update('dishes', 'id_dishes='.$_POST['id_dania'],array('is_visible'=>$val));
	}
	
	public static function usun_danie__admin()
	{
		if(hlp_validator::id($_POST['id_dania']))
			db::query("DELETE FROM dishes WHERE id_dishes=".$_POST['id_dania']);
	}

	public static function edytuj_danie__admin()
	{
		router::set_checkpoint('users/lista-dan');
		mod_panel::edytuj_danie($_POST['a_danie']);
		$id = $_POST['a_danie']['id_dishes'];

		if(!app::get_result())
			view::redirect('panel/formularz_dania/'.$id . '/edycja/true');
		else
		{
			if(is_uploaded_file($_FILES['foto']['tmp_name']))
			{
				hlp_image::delete('images/dishes/'.$id);
				hlp_image::delete('images/dishes/thumbs/'.$id);
				$ext=hlp_image::save($_FILES['foto'],'images/dishes/'.$id);
				
				if($ext)
					hlp_image::save_resized('images/dishes/'.$id.$ext, 'images/dishes/thumbs/'.$id,100);
			}
			view::message();
		}
	}
	
	/****************************ads***************************/
	
	public static function form_ads__admin()
	{
		head::add_js_file('javascript/libs/ckeditor/ckeditor.js',false,'head');
		head::add_js_file('javascript/libs/colorpicker/jquery.minicolors.min.js');
		head::add_css_file('javascript/libs/colorpicker/jquery.minicolors.css');
		view::display();
	}
	
	public static function dodaj_reklame__admin()
	{
		router::set_checkpoint('panel');
		$id=mod_panel::add_ad($_POST['a_ad']);

		if(!app::get_result())
			view::redirect('panel/form-ads');
		else
		{
			view::message();
		}
	}
	
	public static function formularz_edycji_reklamy__admin()
	{
		if(isset($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			$a_ad = db::get_by_id('ads', $_GET['id']);
			
			if($a_ad)
				hlp_image::get_files_by_id($a_ad,"images/ads","id_ads");
			view::add('a_ad',$a_ad);
		}
		else
		{
			app::err('Nieprawidłowe id reklamy');
			view::message();
		}
		
		head::add_js_file('javascript/libs/ckeditor/ckeditor.js',false,'head');
		head::add_js_file('javascript/libs/colorpicker/jquery.minicolors.min.js');
		head::add_css_file('javascript/libs/colorpicker/jquery.minicolors.css');
		view::display('panel/form_ads.tpl');
	}
	
	public static function edytuj_reklame__admin()
	{
		router::set_checkpoint('panel/lista-reklam');
		mod_panel::edit_ad($_POST['a_ad']);

		if(!app::get_result())
			view::redirect('panel/form-ads/'.$_POST['a_ad']['id_ads']);
		else
			view::message();
	}
	
	public static function lista_reklam__admin()
	{
		$a_ads_banner = db::get_many("SELECT * FROM ads WHERE type='banner' ORDER BY ads.order");
		
		if($a_ads_banner)
			hlp_image::get_files_by_id($a_ads_banner,"images/ads","id_ads");
		view::add('a_ads_banner_edit',$a_ads_banner);

		$a_ads_floater = db::get_many("SELECT * FROM ads WHERE type='floater' ORDER BY ads.order");
		
		if($a_ads_floater)
			hlp_image::get_files_by_id($a_ads_floater,"images/ads","id_ads");
		view::add('a_ads_floater_edit',$a_ads_floater);

		view::display();
	}
	
	public static function zapisz_pozycje_reklam__admin()
	{
		foreach($_POST['a_ads'] as $klucz=>$wartosc)
		{
			db::update('ads', 'id_ads='.$wartosc,array('ads.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_reklamy__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['ad_id']))
			db::update('ads', 'id_ads='.$_POST['ad_id'],array('is_visible'=>$val));
	}
	
	public static function usun_reklame__admin()
	{
		if(hlp_validator::id($_POST['ad_id']))
		{
			db::query("DELETE FROM ads WHERE id_ads=".$_POST['ad_id']);
			hlp_image::delete('images/ads/'.$_POST['ad_id']);
		}
	}
	
	/***************************************************************/

	public static function get_submenus_of_menu()
	{
		view::json(true,'',db::get_many("SELECT * FROM menu WHERE parent_id=".$_GET['id']." ORDER BY menu.order"));
	}
	
	public static function form_settings__admin()
	{
		view::add('form_keywords',mod_panel::get_setting('keywords'));
		view::add('form_title',mod_panel::get_setting('title'));
		view::add('form_description',mod_panel::get_setting('description'));
		view::add('form_number_of_news_on_mainsite',mod_panel::get_setting('number_of_news_on_mainsite'));
		view::add('form_slider_transition',mod_panel::get_setting('slider_transition'));
		view::add('form_slider_speed_of_transition',mod_panel::get_setting('slider_speed_of_transition'));
		view::add('form_is_slider_visible',mod_panel::get_setting('is_slider_visible'));
		view::add('form_bg_position',mod_panel::get_setting('bg_position'));
		view::add('form_bg_repeat',mod_panel::get_setting('bg_repeat'));
		view::add('form_bg_attachment',mod_panel::get_setting('bg_atachment'));
		view::add('form_bg_size',mod_panel::get_setting('bg_size'));
		view::add('form_bg_color',mod_panel::get_setting('bg_color'));
		view::add('form_is_bg_image',mod_panel::get_setting('bg_image'));
		view::add('form_is_sticky_header',mod_panel::get_setting('is_sticky_header'));
		view::add('form_is_go_to_top_button',mod_panel::get_setting('is_go_to_top_button'));
		view::add('form_is_cookie_comm',mod_panel::get_setting('is_cookie_comm'));
		
		head::add_js_file('javascript/libs/colorpicker/jquery.minicolors.min.js');
		head::add_css_file('javascript/libs/colorpicker/jquery.minicolors.css');
		view::display();
	}
	
	public static function edit_settings__admin()
	{
		router::set_checkpoint('panel');
		mod_panel::edit_settings($_POST['a_settings']);

		if(!app::get_result())
			view::redirect('panel/form_settings_seo/');
		else
			view::message();
	}
	
	public static function get_background_settings()
	{
		$a_bg_settings['is_bg_image'] = db::get_one('SELECT value FROM settings WHERE `key`="bg_image"');
		$a_bg_settings['bg_color'] = db::get_one('SELECT value FROM settings WHERE `key`="bg_color"');
		
		if($a_bg_settings['is_bg_image']=='tak')
		{
			$a_bg_settings['bg_position'] = db::get_one('SELECT value FROM settings WHERE `key`="bg_position"');
			$a_bg_settings['bg_repeat'] = db::get_one('SELECT value FROM settings WHERE `key`="bg_repeat"');
			$a_bg_settings['bg_attachment'] = db::get_one('SELECT value FROM settings WHERE `key`="bg_attachment"');
			$a_bg_settings['bg_size'] = db::get_one('SELECT value FROM settings WHERE `key`="bg_size"');
			
			$a_img = glob("images/background/*");
			$a_bg_settings['bg_image'] = $a_img[0];
		}
											   
		return $a_bg_settings;									   
	}
	
	public static function get_background_css()
	{
		$a_bg_settings = self::get_background_settings();
		
		$css="<style type=\"text/css\"> \r\n
	       \t body{ \r\n";
    	
    	if($a_bg_settings['is_bg_image']=='tak')
		{
    		$css.="\t background-image: url('".app::base_url(). $a_bg_settings['bg_image']."'); \r\n";
    	    $css.="\t background-position: ".$a_bg_settings['bg_position']."; \r\n";
            $css.="\t background-repeat: ".$a_bg_settings['bg_repeat']."; \r\n";
            $css.="\t background-attachment: ".$a_bg_settings['bg_attachment']."; \r\n";
            $css.="\t background-size: ".$a_bg_settings['bg_size']."; \r\n";
    	}
	    	$css.="\t background-color: ".$a_bg_settings['bg_color']."\r\n
	       } \r\n
	    </style>";
	    
	    return $css;
	}
	
	public static function get_setting()
	{
		$setting = db::get_one("SELECT value FROM settings WHERE `key`='{$_GET['key']}'");
		
		if(isset($_GET['ajax']) && $_GET['ajax']=='true')
		{
			echo trim($setting);
			exit();
		}
		
		return $setting;
	}
	
	public static function wersje_jezykowe_panel__admin()
	{
		view::add('a_langs',lang::get_langs());		//potrzebne do zarządzania
		view::add('a_langs_texts',lang::get_langs(true));	//potrzebne do tłumaczeń
		view::add('a_texts',db::get_many("SELECT * FROM lang_texts WHERE type<>'system'"));
		view::display('panel/form_langs.tpl');
	}

	public static function add_lang__admin()
	{
		lang::add_lang($_POST['a_lang']);
		view::redirect('panel/wersje_jezykowe_panel');
	}
	
	public static function edit_langs__admin()
	{
		lang::edit_langs($_POST['a_langs'],$_POST['id_default']);
		view::redirect('panel/wersje_jezykowe_panel');
	}
	
	public static function zapisz_pozycje_jezykow__admin()
	{
		foreach($_POST['a_langs'] as $klucz=>$wartosc)
		{
			db::update('langs', 'id_langs='.$wartosc,array('langs.order'=>$klucz));
		}
	}
	
	public static function usun_jezyk__admin()
	{
		if(hlp_validator::id($_POST['lang_id']))
		{
			lang::delete_lang($_POST['lang_id']);
		}
	}
	
	public static function save_lang_texts__admin()
	{
		$a_langs = lang::get_langs(true);
		$a_dane = array();
		
		foreach($a_langs as $a_lang)
		{
			$a_dane["value_{$a_lang['short']}"] = $_POST['a_text']["value_{$a_lang['short']}"];
		}
		
		db::update('lang_texts',"id_lang_texts={$_POST['a_text']['id_lang_texts']}",$a_dane);
		unset($_SESSION['lang_texts']);
		unset($_SESSION['lang']);
		view::redirect("panel/wersje_jezykowe_panel#lang_{$_POST['a_text']['id_lang_texts']}");
	}
	
	public static function sql_dump()
	{
		db::sql_dump();
	}
	
	public static function wyslij_maila()
	{
		mod_panel::wyslij_maila($_POST['a_wiadomosc']);
		
		if(app::get_result())
			view::redirect('strony/strona/id/7/wyslano/true#komunikat');
		else
			view::redirect('strony/strona/id/7');
	}
	
	public static function parametry__admin()
	{
		view::add('a_parametry',db::get_all('parametry'));
		view::display();
	}
	
	public static function zapisz_parametry__admin()
	{
		if(!empty($_POST['a_parametry']))
			mod_panel::zapisz_parametry($_POST['a_parametry']);
		
		app::ok('Parametry zapisane');
		view::message();
	}
}

?>