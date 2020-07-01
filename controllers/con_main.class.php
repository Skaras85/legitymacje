<?php

class con_main extends controllers_parent{

	public static function start()
	{
		if(session::is_logged())
		{
			//$a_slidy = db::get_many("SELECT * FROM slides WHERE is_visible=1 ORDER BY `order`");
			//hlp_image::get_files_by_id($a_slidy,'images/slides','id_slides');
			//view::add('a_slidy',$a_slidy);
			view::add('a_placowki',mod_placowki::get_dostepne_placowki_usera(session::get_id()));
			view::add('a_newsy',db::get_many("SELECT * FROM sites WHERE id_article_categories=6 AND is_visible=1 ORDER BY add_date DESC"));
		}
		
		view::add('a_terminy', db::get_by_id('sites', 96));
		view::display("main/mainsite.tpl");
	}
	
	public static function str_lreplace($search, $replace, $subject)
	{
	    $pos = strrpos($subject, $search);
	
	    if($pos !== false)
	    {
	        $subject = substr_replace($subject, $replace, $pos, strlen($search));
	    }
	
	    return $subject;
	}
	
	public static function get_settings()
	{
			
		//unset($_SESSION);session_destroy();
		view::add('lang',lang::get_lang());
		view::add('def_lang',lang::get_default_lang());
		
		mod_panel::set_referer();
		
		mod_panel::get_chosen_menu_elements();

		//var_dump(copy("https://legitymacje.loca.pl/pokaz_zdjecie.php?dostep=1&v_id_nauczyciela=331745","images/placowki/47/101.jpg"));
		//var_dump(error_get_last());
/*
		$a_ads_banner = mod_panel::get_ads('banner');
		if($a_ads_banner)
			hlp_image::get_files_by_id($a_ads_banner,"images/ads","id_ads");
		view::add('a_ads_banner',$a_ads_banner);
		
		$a_ads_floaters = mod_panel::get_ads('floater');
		if($a_ads_floaters)
			hlp_image::get_files_by_id($a_ads_floaters,"images/ads","id_ads");
		view::add('a_ads_floaters',$a_ads_floaters);
		*/
		//view::add('a_bg_settings',con_panel::get_background_settings());
		view::add('a_main_menus',mod_panel::get_all_menu());
		if(session::is_logged())
		{
			if(session::get('id_placowki'))
			{
				view::add('a_wybrana_placowka',mod_placowki::get_placowka(session::get('id_placowki')));
				view::add('liczba_kart_w_koszyku',mod_koszyk::get_liczba_kart()+mod_koszyk::get_liczba_produktow());
				view::add('czy_placowka_ma_karty_szkolne',mod_legitymacje::czy_placowka_ma_legitymacje_szkolne(session::get('id_placowki')));
			}
			view::add('a_logged_user',mod_users::get_user(session::get_id(), 'id_users'));
			$id_users = session::who('admin') ? 0 : session::get_id();
			//view::add('liczba_nowych_wiadomosci',mod_wiadomosci::get_liczba_wiadomosci($id_users, 'nowe'));
			view::add('liczba_odebranych_wiadomosci',mod_wiadomosci::get_liczba_wiadomosci($id_users, 'odebrane'));
			view::add('liczba_wyslanych_wiadomosci',mod_wiadomosci::get_liczba_wiadomosci($id_users, 'wyslane'));
			view::add('liczba_roboczych_wiadomosci',mod_wiadomosci::get_liczba_wiadomosci($id_users, 'robocze'));
			view::add('czy_nowe_wiadomosci',mod_wiadomosci::czy_nowe_wiadomosci());
		
		}
		
		view::add('_base_url',app::base_url());
		////view::add('is_slider_visible',mod_panel::get_setting('is_slider_visible'));
		//view::add('slider_speed_of_transition',mod_panel::get_setting('slider_speed_of_transition'));
		//view::add('slider_transition',mod_panel::get_setting('slider_transition'));
		view::add('is_sticky_header',mod_panel::get_setting('is_sticky_header'));
		view::add('is_go_to_top_button',mod_panel::get_setting('is_go_to_top_button'));
		view::add('js',mod_panel::$js);
	}
	
	public static function change_lang()
	{
		head::nofollow();
		
		if(!isset($_GET['lang']))
			view::redirect('');

		if($_GET['lang']=='PL')
		{
			$server = "http://".app::host();
			app::set_base_url('http://localhost/fearzone2/');
		}
		else
		{
			$server = "http://".strtolower($_GET['lang']).".".app::host();
			app::set_base_url('http://en.greatescaperoom.pl/');
		}
		
		if(!empty($_GET['from']))
		{
			if($_GET['from']=='rezerwacja')
				$from = "{$server}rezerwacja";
			elseif($_GET['from']=='dla-firm')
				$from = "{$server}cooperation";
			elseif($_GET['from']=='cooperation')
				$from = "{$server}dla-firm";
			elseif($_GET['from']=='contact')
				$from = "{$server}kontakt";
			elseif($_GET['from']=='kontakt')
				$from = "{$server}contact";
			else
				$from =$server.$_GET['from'];
		}
		else
			$from = "{$server}";

		header("Location: $from");
	}
	
	public static function set_csrf_uniqkey()
	{
		$_SESSION['form']['uniq_key']=uniqid();
		echo $_SESSION['form']['uniq_key'];
		exit();
	}
	
	public static function check_csrf_uniqkey($i_check_key)
	{
		if($i_check_key!=$_SESSION['form']['uniq_key'])
			return false;
		else
			return true;
	}
	
	public static function sitemap()
	{
		$txt = '<?xml version="1.0" encoding="UTF-8"?>';
		$txt .= '<urlset
	      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
	            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\r\n";

		$domena = app::base_url();

		$txt .= "<url>\r\n";
		$txt .= "\t<loc>".$domena."</loc>\r\n";
		$txt .= "\t<changefreq>daily</changefreq>\r\n";
		$txt .= "</url>\r\n";
		
	    $a_dzielnice = db::get_many("SELECT dzielnice.*,miasta.sludge as sludge_miasta FROM dzielnice JOIN miasta USING(id_miasta) WHERE is_visible=1");

		foreach($a_dzielnice as $a_dzielnica)
		{
			$txt .= "<url>\r\n";
			$txt .= "\t<loc>".$domena.$a_dzielnica['sludge_miasta'].'/'.$a_dzielnica['sludge']."</loc>\r\n";
			$txt .= "\t<changefreq>monthly</changefreq>\r\n";
			$txt .= "</url>\r\n";
		}
		
		$a_users = db::get_many("SELECT users.sludge,miasta.sludge as sludge_miasta FROM users JOIN miasta USING(id_miasta) WHERE rodzaj='user'");
		
		foreach($a_users as $a_user)
		{
			$txt .= "<url>\r\n";
			$txt .= "\t<loc>".$domena.$a_user['sludge_miasta'].'/'.$a_user['sludge']."</loc>\r\n";
			$txt .= "\t<changefreq>daily</changefreq>\r\n";
			$txt .= "</url>\r\n";
		}

		$a_typy_kuchni = db::get_many("SELECT typy_kuchni.*,miasta.sludge as sludge_miasta from typy_kuchni JOIN sites USING(id_typy_kuchni) JOIN miasta ON miasta.id_miasta=sites.id_miasta");
		
		foreach($a_typy_kuchni as $a_typ_kuchni)
		{
			$txt .= "<url>\r\n";
			$txt .= "\t<loc>".$domena.$a_typ_kuchni['sludge_miasta'].'/'.$a_typ_kuchni['sludge']."</loc>\r\n";
			$txt .= "\t<changefreq>daily</changefreq>\r\n";
			$txt .= "</url>\r\n";
		}
		
		$a_dodatkowo = db::get_many("SELECT dodatkowo.*,miasta.sludge as sludge_miasta from dodatkowo JOIN sites USING(id_dodatkowo) JOIN miasta ON miasta.id_miasta=sites.id_miasta");
		
		foreach($a_dodatkowo as $a_dodatek)
		{
			$txt .= "<url>\r\n";
			$txt .= "\t<loc>".$domena.$a_dodatek['sludge_miasta'].'/'.$a_dodatek['sludge']."</loc>\r\n";
			$txt .= "\t<changefreq>daily</changefreq>\r\n";
			$txt .= "</url>\r\n";
		}
		
		$txt .= "<url>\r\n";
		$txt .= "\t<loc>".$domena."blog</loc>\r\n";
		$txt .= "\t<changefreq>daily</changefreq>\r\n";
		$txt .= "</url>\r\n";
		
		$a_strony = db::get_many("SELECT * from sites WHERE id_article_categories=3");
		
		foreach($a_strony as $a_strona)
		{
			$txt .= "<url>\r\n";
			$txt .= "\t<loc>".$domena.$a_strona['sludge'].','.$a_strona['id_sites']."</loc>\r\n";
			$txt .= "\t<changefreq>daily</changefreq>\r\n";
			$txt .= "</url>\r\n";
		}
		
		$a_tagi = db::get_many("SELECT * from tags");
		
		foreach($a_tagi as $a_tag)
		{
			$txt .= "<url>\r\n";
			$txt .= "\t<loc>".$domena.$a_tag['sludge']."</loc>\r\n";
			$txt .= "\t<changefreq>daily</changefreq>\r\n";
			$txt .= "</url>\r\n";
		}
		
		$txt .= '</urlset>';	
		var_dump($txt);
		file_put_contents('sitemap.xml', $txt);
	}
	
	public static function testuj_maila()
	{
		$data = date('Y-m-d');
		$godzina = date('H:i:s');
		$text = "Mail testowy wysłany dnia $data o godzinie $godzina .Twoja skrzynka e-mail działa poprawnie.";
		file_put_contents("mail_test.html", $text);
 		system("/usr/local/bin/wkhtmltopdf mail_test.html mail_test.pdf");
		unlink("mail_test.html");
		mailer::add_address('legitymacje@loca.pl');
		mailer::add_attachment('mail_test.pdf','mail_test.pdf','base64','application/pdf');
		
		$text = '<!DOCTYPE HTML><html><head><meta charset="utf-8"></head>'.$text.'</html>';
		mailer::send('test maila legitymacje.loca.pl',$text,false,true,strip_tags($text));
		unlink("mail_test.pdf");
	}
}

?>