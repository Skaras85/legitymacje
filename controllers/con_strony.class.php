<?php

class con_strony extends controllers_parent{
	
	public static $default_action = 'strona';
	
	public static function formularz_dodawania__admin()
	{
		head::add_js_file(mod_panel::$js.'libs/ckeditor/ckeditor.js',false,'head');
		head::add_js_file(mod_panel::$js.'libs/tagsinput/jquery.tagsinput.min.js');
		head::add_css_file(mod_panel::$js.'libs/tagsinput/jquery.tagsinput.css');
		view::add('a_article_categories',db::get_all("article_categories"));
		view::add('a_langs',lang::get_langs(true,true));
		view::display('strony/form_sites.tpl');
	}
	
	public static function add__admin()
	{
		router::set_checkpoint('panel');
		mod_strony::add($_POST['a_strona']);
		if(!app::get_result())
			view::redirect('strony/formularz_dodawania');
		else
			view::message();
	}
	
	public static function formularz_edycji_strony__admin()
	{
		head::add_js_file(mod_panel::$js.'libs/tagsinput/jquery.tagsinput.min.js');
		head::add_css_file(mod_panel::$js.'libs/tagsinput/jquery.tagsinput.css');
		head::add_js_file(mod_panel::$js.'libs/ckeditor/ckeditor.js',false,'head');
		
		if(isset($_GET['id']) && hlp_validator::id($_GET['id']))
		{
			$a_strona=db::get_by_id('sites', $_GET['id']);
			
			$a_strona['title'] = htmlentities($a_strona['title'],ENT_QUOTES,'UTF-8');
			
			$a_langs = lang::get_langs(true,true);
		
			if($a_langs)
			{
				foreach($a_langs as $a_lang)
				{
					$a_strona['title_'.$a_lang['short']] = htmlentities($a_strona['title_'.$a_lang['short']],ENT_QUOTES,'UTF-8');
				}
			}
			
			
			hlp_image::get_files_by_id($a_strona,'images/sites','id_sites');
			view::add('a_strona',$a_strona);
			view::add('a_article_categories',db::get_all("article_categories"));
			view::add('a_langs',lang::get_langs(true,true));
			view::display('strony/form_sites.tpl');	
		}
		else
		{
			app::err('Nieprawidłowe numer strony');
			view::message();
		}
	}
	
	public static function edit__admin()
	{
		router::set_checkpoint('strony/lista_stron');
		mod_strony::edit_site($_POST['a_strona']);
		
		if(!app::get_result())
			view::redirect('strony/formularz-edycji-strony/id/'.$_POST['a_strona']['id_sites']);	
		else
			view::message();
	}
	
	public static function lista_stron__admin()
	{
		view::add('a_kategorie',db::get_many("SELECT id_article_categories,ac.title FROM sites LEFT JOIN article_categories ac USING(id_article_categories) GROUP BY id_article_categories"));
		view::add('a_sites',db::get_many("SELECT * FROM sites ORDER BY sites.order"));
		view::display();
	}
	
	public static function usun_strone__admin()
	{
		if(hlp_validator::id($_POST['site_id']))
		{
			db::query("DELETE FROM sites WHERE id_sites=".$_POST['site_id']);
			db::delete('tags_in_usage',"owner_id={$_POST['site_id']} AND type='sites'");
		}
	}

	public static function strona()
	{
		if(!hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy numer strony');
			view::message();
		}
		else
		{
			$a_strona = db::get_row("SELECT sites.*, karty.id_karty FROM sites LEFT JOIN karty USING(id_sites) LEFT JOIN karty_placowki USING(id_karty) WHERE id_sites=".$_GET['id']." AND is_visible=1");

			if($a_strona==false)
			{
				app::err(lang::get('strona-msg-niedostepna',1));
				router::set_checkpoint();
				view::message();
			}
			else
			{
				view::add('czy_ma_juz_ta_karte', db::get_one("SELECT 1 FROM karty_placowki WHERE id_karty={$a_strona['id_karty']} AND id_placowki=".session::get('id_placowki')));	
				
				/*if($a_strona['id_article_categories']!=3 && !session::is_logged())
				{
					app::err('Musisz być zalogowany, aby zobaczyć tą stronę');
					view::message();
				}*/
				
				if(lang::get_lang()!=lang::get_default_lang())
					$lang = '_'.lang::get_lang();
				else
					$lang = '';
				
				if($a_strona['seo_title'.$lang])
					head::set_title($a_strona['seo_title'.$lang]);
				else
					head::set_title($a_strona['title']);
	
				if($a_strona['seo_description'.$lang])
					head::set_description($a_strona['seo_description'.$lang]);
	
				if($a_strona['seo_keywords'.$lang])
					head::set_keywords($a_strona['seo_keywords'.$lang]);

				view::add('a_strona',$a_strona);
				mod_panel::increase_visit_counter('sites',$_GET['id']);
/*
				$a_tags = mod_strony::get_subject_tags($_GET['id'],'sites');
				view::add('a_tags',$a_tags);*/
				view::add('a_posts',mod_strony::get_random_sites($a_tags,3,$_GET['id']));
				view::add('podglad_i_druk',isset($_GET['podglad-i-druk']));
				view::display('strony/strona.tpl');
			}
		}
	}

	public static function blog()
	{
		$fraza = isset($_GET['fraza']) ? $_GET['fraza'] : '';
		view::add('fraza',$fraza);
		
		$sludge = isset($_GET['sludge']) ? $_GET['sludge'] : false;
		$a_tag = $sludge ? db::get_row("SELECT * FROM tags WHERE sludge='$sludge'") : false;

		$number_of_articles = mod_strony::get_number_of_sites(3,$fraza,$a_tag ? $a_tag['id_tags'] : false);

		if(isset($_GET['numer-strony']))
		{
			$art_site = $_GET['numer-strony'];
			
			if($art_site==1)
				view::redirect('blog');
		}
		else
			$art_site = 1;

		$arts_par_page = 3;
		$liczba_stron = ceil($number_of_articles/$arts_par_page);
		
		if($art_site>$liczba_stron)
			$art_site=$liczba_stron;
		
		if($a_tag)
		{
			if($a_tag['seo_title'])
				head::set_title($a_tag['seo_title'] . ($art_site>1 ? " - strona $art_site z $liczba_stron" : ''));
			
			if($a_tag['seo_description'])
				head::set_description($a_tag['seo_description']);
			
			view::add('a_tag',$a_tag);
		}
		else
		{
			head::set_title('Blog wegetariański' . ($art_site>1 ? " - strona $art_site z $liczba_stron" : ''));
			head::set_description('Artykuły związane z wegetarianizmem i weganizmem, przepisy vege/vegan, ciekawostki, porady i inne');
		}

		$a_sites = mod_strony::get_sites_by_category(3,$fraza,$a_tag ? $a_tag['id_tags'] : false,$art_site,$arts_par_page);
		view::add('pagination_data',"blog");
		view::add('number_of_pages',ceil($number_of_articles/$arts_par_page));
		
		view::add('a_artykuly',$a_sites);
		view::display('strony/artykuly.tpl');
	}

	public static function artykuly()
	{
		if(!hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy numer kategorii artykułów');
			view::message();
		}
		else
		{
			$a_artykuly = db::get_many("SELECT * FROM sites WHERE id_article_categories=".$_GET['id']." AND is_visible=1 ORDER BY `order` DESC");
			$a_article_cat = db::get_by_id('article_categories', $_GET['id']);
			
			if(lang::get_lang()!=lang::get_default_lang())
				$lang = '_'.lang::get_lang();
			else
				$lang = '';

			if($a_article_cat['seo_title'.$lang])
				head::set_title($a_article_cat['seo_title'.$lang]);
			
			if($a_article_cat['seo_description'.$lang])
				head::set_description($a_article_cat['seo_description'.$lang]);

			if($a_article_cat['seo_keywords'.$lang])
				head::set_keywords($a_article_cat['seo_keywords'.$lang]);
			
			if($a_artykuly==false)
			{
				app::err('Brak artykułów w tej kategorii');
				router::set_checkpoint();
				view::message();
			}
			else
			{
				view::add('a_artykuly',$a_artykuly);
				view::display();
			}
		}
	}
	
	public static function menu()
	{
		if(!hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy numer kategorii dania');
			view::message();
		}
		
		$a_dania = db::get_many("SELECT * FROM dishes JOIN dishes_categories USING(id_dishes_categories) WHERE dishes.is_visible=1 AND dishes_categories.is_visible=1 AND dishes.id_dishes_categories=".$_GET['id']);
		
		if($_GET['id']==16 || $_GET['id']==36)
			hlp_image::get_files_by_id($a_dania, 'images/dishes/thumbs', 'id_dishes');

		view::add('id_dishes_categories',$_GET['id']);
		view::add('kategoria',db::get_one("SELECT title FROM dishes_categories WHERE id_dishes_categories=".$_GET['id']));
		view::add('a_dania',$a_dania);
		view::add('a_kategorie_menu',db::get_many("SELECT * FROM dishes_categories WHERE is_visible=1 ORDER BY dishes_categories.order"));
		view::display();
	}
	
	public static function zapisz_pozycje_stron__admin()
	{
		foreach($_POST['a_site'] as $klucz=>$wartosc)
		{
			db::update('sites', 'id_sites='.$wartosc,array('sites.order'=>$klucz));
		}
	}
	
	public static function zapisz_widocznosc_stron__admin()
	{
		if($_POST['val']=='true')
			$val=1;
		else
			$val=0;
		if(hlp_validator::id($_POST['site_id']))
			db::update('sites', 'id_sites='.$_POST['site_id'],array('is_visible'=>$val));
	}
	
	public static function get_tags()
	{
		$a_tags = db::get_many("SELECT tagname FROM tags WHERE tagname LIKE '{$_GET['term']}%'");
		
		$a_simple_tags = array();
		
		if($a_tags)
		{
			foreach($a_tags as $tag)
				$a_simple_tags[]=$tag['tagname'];
		}

		echo json_encode($a_simple_tags);
	}
	
	public static function get_site_by_id()
	{
		if(!isset($_GET['id']) || !hlp_validator::id($_GET['id']))
			return false;
			
		view::json(true, '', db::get_by_id('sites', $_GET['id']));
	}
	
	public static function poradnik()
	{
		view::add('a_sites',db::get_many("SELECT * FROM sites WHERE id_article_categories=9 AND is_visible=1 ORDER BY `order`"));
		view::display();
	}

}

?>