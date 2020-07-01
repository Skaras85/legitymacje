<?php

class mod_galerie{
	
	public static function check_data($ia_galeria)
	{
		if(!hlp_validator::alfanum($ia_galeria['sludge']))
		{
			app::err('Niepoprawny format przyjaznego linku');
			return false;
		}
		
		if(isset($ia_galeria['id_galleries']) && !hlp_validator::id($ia_galeria['id_galleries']))
		{
			app::err('Nieprawidłowy numer galerii');
			view::message();
			exit();
		}
		
		if($ia_galeria['add_date']!='' && !hlp_validator::data($ia_galeria['add_date']))
		{
			app::err('Nieprawidłowa data');
			return false;
		}
		
		return true;
	}
	
	public static function dodaj_galerie($ia_galeria)
	{
		$_SESSION['form']['a_galeria']=$ia_galeria;
		
		if(!self::check_data($ia_galeria))
			return false;
		
		if($ia_galeria['add_date']=='')
			$sql_add_date='NOW()';
		else
			$sql_add_date=$ia_galeria['add_date'];
		
		//$title = strip_tags($ia_galeria['title']);
		
		$a_dane = array('title'=>$ia_galeria['title'],'sludge'=>$ia_galeria['sludge'],'add_date'=>$sql_add_date,
				'seo_title'=>$ia_galeria['seo_title'],'seo_keywords'=>$ia_galeria['seo_keywords'],
				'seo_description'=>$ia_galeria['seo_description'],'tags'=>$ia_galeria['tags']);

		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_galeria['title_'.$a_lang['short']],
							 "sludge_{$a_lang['short']}"=>$ia_galeria['sludge_'.$a_lang['short']],
							  "seo_title_{$a_lang['short']}"=>$ia_galeria['seo_title_'.$a_lang['short']],
							  "seo_keywords_{$a_lang['short']}"=>$ia_galeria['seo_keywords_'.$a_lang['short']],
							  "seo_description_{$a_lang['short']}"=>$ia_galeria['seo_description_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);
		
		$id=db::insert('galleries', $a_dane);
		
		
		
		db::update('galleries','id_galleries='.$id, array('galleries.order'=>$id));
		
		mkdir('images/galleries/'.$id);
		
		app::ok('Galeria ' . $ia_galeria['title'] . ' dodana!');
		return true;
	}
	
	public static function edytuj_galerie($ia_galeria)
	{
		$_SESSION['form']['a_galeria']=$ia_galeria;
		
		if(!self::check_data($ia_galeria))
			return false;
		
		if($ia_galeria['add_date']=='')
			$sql_add_date='NOW()';
		else
			$sql_add_date=$ia_galeria['add_date'];
		
		//$title = strip_tags($ia_galeria['title']);
		
		$a_dane = array('title'=>$ia_galeria['title'],'sludge'=>$ia_galeria['sludge'],'add_date'=>$sql_add_date,
				'seo_title'=>$ia_galeria['seo_title'],'seo_keywords'=>$ia_galeria['seo_keywords'],
				'seo_description'=>$ia_galeria['seo_description'],'tags'=>$ia_galeria['tags']);

		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_galeria['title_'.$a_lang['short']],
							  "sludge_{$a_lang['short']}"=>$ia_galeria['sludge_'.$a_lang['short']],
							  "seo_title_{$a_lang['short']}"=>$ia_galeria['seo_title_'.$a_lang['short']],
							  "seo_keywords_{$a_lang['short']}"=>$ia_galeria['seo_keywords_'.$a_lang['short']],
							  "seo_description_{$a_lang['short']}"=>$ia_galeria['seo_description_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);
				
		$id=db::update('galleries','id_galleries='.$ia_galeria['id_galleries'],$a_dane);
		
		app::ok('Galeria ' . $ia_galeria['title'] . ' zedytowana!');
		return true;
	}

	public static function get_galleries()
	{
		if(!session::who('admin'))
			$sql_where=" WHERE galleries.is_visible=1";
		else
			$sql_where='';

		$a_galerie = db::get_many("SELECT galleries.*,IFNULL((SELECT filename FROM photos WHERE photos.id_galleries=galleries.id_galleries AND photos.is_visible=1 AND is_mainphoto=1 LIMIT 1),(SELECT filename FROM photos WHERE photos.id_galleries=galleries.id_galleries AND photos.is_visible=1 ORDER BY photos.order LIMIT 1)) as mainphoto FROM galleries $sql_where ORDER BY galleries.order");
	
		if(!$a_galerie)
		{
			app::err('Brak aktywnych galerii');
			return false;
		}
		return $a_galerie;
	}
	
	public static function get_gallery($i_id_galleries)
	{
		return db::get_by_id('galleries', $i_id_galleries);
	}
	
	public static function get_photos($i_id_galleries)
	{
		if(!session::who('admin'))
			$sql_where=" and photos.is_visible=1";
		else
			$sql_where='';
		
		return db::get_many("SELECT photos.* FROM photos WHERE id_galleries=$i_id_galleries $sql_where ORDER BY photos.order");
	}

}

?>