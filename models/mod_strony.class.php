<?php

class mod_strony extends db{
	
	public static function zwroc_sludge($id_sites)
	{
		return db::get_one("SELECT sludge FROM sites WHERE id_sites=$id_sites");
	}
	
	public static function check_data($ia_strona)
	{
		$kom = 'Nie dodano strony: ';
		if($ia_strona['title']=='')
		{
			app::err($kom.'nie wpisałeś tytułu');
			return false;
		}
		/*
		if($ia_strona['sludge']=='')
		{
			app::err($kom.'nie wpisałeś przyjaznego linku');
			return false;
		}
*/
		if(!is_numeric($ia_strona['id_article_categories']))
		{
			app::err($kom.'nieprawidłowa kategoria artykułu');
			return false;
		}
		
		if($ia_strona['add_date']!='' && !hlp_validator::data($ia_strona['add_date']))
		{
			app::err($kom.'nieprawidłowa data');
			return false;
		}
		
		return true;
	}
	
	public static function add($ia_strona)
	{
		$_SESSION['form']['a_strona']=$ia_strona;
		
		if(!self::check_data($ia_strona))
			return false;
		
		if($ia_strona['add_date']=='')
			$sql_add_date='NOW()';
		else
			$sql_add_date=$ia_strona['add_date'];
/*
		$ia_strona['text'] = str_replace('src="//www.youtube.com','src="http://www.youtube.com',$ia_strona['text']);
		$ia_strona['appetizer'] = str_replace('src="//www.youtube.com','src="http://www.youtube.com',$ia_strona['appetizer']);
		*/
		$a_dane = array('title'=>$ia_strona['title'],'appetizer'=>$ia_strona['appetizer'],
				'sludge'=>$ia_strona['sludge'],'text'=>$ia_strona['text'],
				'add_date'=>$sql_add_date,'id_article_categories'=>$ia_strona['id_article_categories'],
				'seo_title'=>$ia_strona['seo_title'],'seo_keywords'=>$ia_strona['seo_keywords'],
				'seo_description'=>$ia_strona['seo_description'],'tags'=>$ia_strona['tags']);

		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_strona['title_'.$a_lang['short']],
							  "text_{$a_lang['short']}"=>$ia_strona['text_'.$a_lang['short']],
							  "appetizer_{$a_lang['short']}"=>$ia_strona['appetizer_'.$a_lang['short']],
							  "sludge_{$a_lang['short']}"=>$ia_strona['sludge_'.$a_lang['short']],
							  "seo_title_{$a_lang['short']}"=>$ia_strona['seo_title_'.$a_lang['short']],
							  "seo_keywords_{$a_lang['short']}"=>$ia_strona['seo_keywords_'.$a_lang['short']],
							  "seo_description_{$a_lang['short']}"=>$ia_strona['seo_description_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);

		$id=db::insert('sites', $a_dane);
		
		$ext = hlp_image::save($_FILES['zdjecie'],"images/sites/$id");
		
		if(app::get_result())
			db::update('sites','id_sites='.$id,array('img'=>$id.$ext));
		
		db::update('sites','id_sites='.$id,array('sites.order'=>$id));
		
		self::add_tags($ia_strona['tags'],$id,'sites');
		
		unset($_SESSION['form']['a_strona']);		
		app::ok('Dodano stronę '.$ia_strona['title'].', nr '.$id);	
		return true;
	}

	public static function add_tags($tags,$owner_id,$type)
	{
		$a_tags = explode(',', $tags);
		
		foreach($a_tags as $tag)
		{
			if($tag!='')
			{
				$id_tags = db::get_one("SELECT id_tags FROM tags WHERE tagname='$tag'");
				if(!$id_tags)
					$id_tags = db::insert('tags',array('tagname'=>$tag));
				
				db::insert('tags_in_usage',array('owner_id'=>$owner_id,'id_tags'=>$id_tags,'type'=>$type));
			}
		}
	}
	
	public static function get_subject_tags($owner_id,$type)
	{
		return db::get_many("SELECT * FROM tags_in_usage JOIN tags USING(id_tags) WHERE owner_id=$owner_id AND type='$type'");
	}
	
	public static function get_tags_in_usage($type,$id_article_categories)
	{
		return db::get_many("SELECT tags.* FROM tags_in_usage JOIN tags USING(id_tags) JOIN sites ON id_sites=owner_id WHERE type='$type' AND id_article_categories=$id_article_categories GROUP BY id_tags");
	}
	
	public static function edit_site($ia_strona)
	{
		$_SESSION['form']['a_strona']=$ia_strona;
		
		if(!self::check_data($ia_strona))
			return false;

		if($ia_strona['add_date']=='')
			$sql_add_date='NOW()';
		else
			$sql_add_date=$ia_strona['add_date'];
		/*
		$ia_strona['text'] = str_replace('src="//www.youtube.com','src="http://www.youtube.com',$ia_strona['text']);
		$ia_strona['appetizer'] = str_replace('src="//www.youtube.com','src="http://www.youtube.com',$ia_strona['appetizer']);
*/
		$a_dane = array('title'=>$ia_strona['title'],'appetizer'=>$ia_strona['appetizer'],
				'sludge'=>$ia_strona['sludge'],
				'text'=>$ia_strona['text'],'add_date'=>$sql_add_date,
				'id_article_categories'=>$ia_strona['id_article_categories'],
				'seo_title'=>$ia_strona['seo_title'],'seo_keywords'=>$ia_strona['seo_keywords'],
				'seo_description'=>$ia_strona['seo_description'],'tags'=>$ia_strona['tags']
				);

		$a_dane_langs = array();
		
		$a_langs = lang::get_langs(true,true);
		
		if($a_langs)
		{
			foreach($a_langs as $a_lang)
			{
				$a_dane_langs = array("title_{$a_lang['short']}"=>$ia_strona['title_'.$a_lang['short']],
							  "text_{$a_lang['short']}"=>$ia_strona['text_'.$a_lang['short']],
							  "appetizer_{$a_lang['short']}"=>$ia_strona['appetizer_'.$a_lang['short']],
							  "sludge_{$a_lang['short']}"=>$ia_strona['sludge_'.$a_lang['short']],
							  "seo_title_{$a_lang['short']}"=>$ia_strona['seo_title_'.$a_lang['short']],
							  "seo_keywords_{$a_lang['short']}"=>$ia_strona['seo_keywords_'.$a_lang['short']],
							  "seo_description_{$a_lang['short']}"=>$ia_strona['seo_description_'.$a_lang['short']]);
			}
		}
		
		$a_dane = array_merge($a_dane,$a_dane_langs);
		
		$ext = hlp_image::save($_FILES['zdjecie'],"images/sites/{$ia_strona['id_sites']}");

		if(app::get_result())
			db::update('sites','id_sites='.$ia_strona['id_sites'],array('img'=>$ia_strona['id_sites'].$ext));

		db::update('sites','id_sites='.$ia_strona['id_sites'], $a_dane);

		db::delete('tags_in_usage',"owner_id={$ia_strona['id_sites']} AND type='sites'");
		self::add_tags($ia_strona['tags'],$ia_strona['id_sites'],'sites');
		
		unset($_SESSION['form']['a_strona']);		
		app::ok('Zedytowano stronę '.$ia_strona['title']);	
		return true;		
	}

	public static function get_number_of_sites($id_article_categories,$fraza='',$id_tags=false)
	{
		if($fraza!='')
			$sql_fraza = " AND (title LIKE '%$fraza%' OR text LIKE '%$fraza%' OR tagname LIKE '%$fraza%')";
		else
			$sql_fraza = '';	
		
		$sql_id_tags = $id_tags ? " AND id_tags=$id_tags" : '';

		$a_strony = db::get_many("SELECT DISTINCT id_sites FROM sites 
							LEFT JOIN tags_in_usage ON owner_id=id_sites
		 					LEFT JOIN tags USING(id_tags) 
		 					WHERE id_article_categories=$id_article_categories AND is_visible=1 $sql_id_tags $sql_fraza
		 					GROUP BY id_sites");
	
		return count($a_strony);
	}
	
	public static function get_glosy_slownie($liczba_glosow)
	{
		if($liczba_glosow==1)
			return 'głos';
		elseif((strlen($liczba_glosow)>1 && (substr($liczba_glosow,strlen($liczba_glosow),-1)==2 || 
			   substr($liczba_glosow,strlen($liczba_glosow),-1)==3 ||
			   substr($liczba_glosow,strlen($liczba_glosow),-1)==4) || $liczba_glosow==2 || $liczba_glosow==3 || $liczba_glosow==4))
			   return 'głosy';
		else
			return 'głosów';
	}
	/*
	public static function get_sites_by_category($id_article_categories,$fraza='',$id_tags=false,$i_article_site=1,$i_quantity=10)
	{
		if($fraza!='')
			$sql_fraza = " AND (title LIKE BINARY '%$fraza%' OR text LIKE BINARY '%$fraza%' OR tagname LIKE BINARY '%$fraza%')";
		else
			$sql_fraza = '';	

		$sql_id_tags = $id_tags ? " AND id_tags=$id_tags" : '';

		$a_articles = db::get_many("SELECT DISTINCT sites.*,
							(SELECT COUNT(*) FROM comments WHERE type='sites' AND is_visible=1 AND subject_id=id_sites) as number_of_comments,
							IFNULL(ROUND((SELECT SUM(rating)/COUNT(*) FROM ratings WHERE type='sites' AND subject_id=id_sites),1),0) as rating,
							IFNULL(ROUND((SELECT COUNT(*) FROM ratings WHERE type='sites' AND subject_id=id_sites),1),0) as number_of_votes
		 					FROM sites 
		 					LEFT JOIN tags_in_usage ON owner_id=id_sites
		 					LEFT JOIN tags USING(id_tags) 
		 					WHERE id_article_categories=$id_article_categories AND is_visible=1 $sql_fraza $sql_id_tags ORDER BY add_date DESC",$i_article_site,$i_quantity);
	
		if($a_articles)
		{
			foreach($a_articles as $index=>$a_article)
			{
				$a_articles[$index]['number_of_votes_slownie'] = self::get_glosy_slownie($a_article['number_of_votes']);
			}
		}
		
		return $a_articles;
	}
	*/
	public static function get_random_sites($a_tags,$limit=3,$id_sites=false)
	{
		$sql_ids = '';
		if($a_tags)
		{
			$ids = '';
			
			foreach($a_tags as $a_tag)
			{
				$ids .= $a_tag['id_tags'].',';
			}
			$ids = trim($ids,',');
			
			$sql_ids = "AND id_tags IN($ids) AND type='sites'";
		}

		$sql_not_in = $id_sites ? " AND id_sites NOT IN($id_sites)" : '';

		return db::get_many("select * from sites join tags_in_usage on id_sites=owner_id where id_article_categories=3 $sql_ids and is_visible=1 $sql_not_in GROUP BY id_sites ORDER BY RAND() LIMIT $limit");
	}
	
	public static function get_sites_by_category($id_article_categories)
	{
		return db::get_many("SELECT * FROM sites WHERE id_article_categories=$id_article_categories");
	}
}

?>