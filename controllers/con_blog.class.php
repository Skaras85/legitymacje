<?php

class con_blog extends controllers_parent{
	
	public static $default_action = 'pokaz';
	
	public static function pokaz()
	{
		$fraza = isset($_GET['fraza']) ? $_GET['fraza'] : '';
		view::add('fraza',$fraza);

		$number_of_articles = mod_strony::get_number_of_sites(5,$fraza);

		if(isset($_GET['numer-strony']))
			$art_site = $_GET['numer-strony'];
		else
			$art_site = 1;

		$arts_par_page = 6;
		
		if($art_site>ceil($number_of_articles/$arts_par_page))
			$art_site=ceil($number_of_articles/$arts_par_page);

		$a_sites = mod_strony::get_sites_by_category(3,$fraza,$art_site,$arts_par_page);
		view::add('pagination_data',"blog/pokaz");
		view::add('number_of_pages',ceil($number_of_articles/$arts_par_page));
		
		if($a_sites)
		{
			foreach($a_sites as $index=>$a_site)
			{
				$link = app::base_url()."sites/{$a_site['sludge']},{$a_site['id_sites']}";
				$a_sites[$index]['appetizer'] = substr_replace($a_site['appetizer'], " <a href='$link'>Czytaj dalej...</a></p>", strrpos($a_site['appetizer'], '</p>'), strlen('</p>'));
			}
		}

		view::add('a_tags_in_usage',mod_strony::get_tags_in_usage('sites',5));
		view::add('is_blog',true);
		view::add('a_artykuly',$a_sites);
		view::display('blog/blog.tpl');
	}
}

?>