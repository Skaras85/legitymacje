<?php

class con_galerie extends controllers_parent{
	
	public static $default_action = 'pokaz';
	
	public static function formularz_galerii__admin()
	{
		view::add('a_langs',lang::get_langs(true,true));
		router::set_checkpoint('galerie/lista-galerii');
		
		if(isset($_GET['id']))
		{
			if(!hlp_validator::id($_GET['id']))
			{
				app::err('Brak takiej galerii');
				view::message();
			}
			
			$a_galeria = mod_galerie::get_gallery($_GET['id']);
			
			$a_galeria['title'] = htmlentities($a_galeria['title'],ENT_QUOTES,'UTF-8');
			
			$a_langs = lang::get_langs(true,true);
		
			if($a_langs)
			{
				foreach($a_langs as $a_lang)
				{
					$a_galeria['title_'.$a_lang['short']] = htmlentities($a_galeria['title_'.$a_lang['short']],ENT_QUOTES,'UTF-8');
				}
			}
		
			view::add('a_galeria',$a_galeria);
		}
		
		view::display();
	}
	
	public static function dodaj_galerie__admin()
	{
		router::set_checkpoint('galerie/formularz_galerii/edycja/false');
		mod_galerie::dodaj_galerie($_POST['a_galeria']);
		
		if(!app::get_result())
			view::message();
		else
			view::redirect('galerie/formularz_dodawania_zdjec');
	}
	
	public static function formularz_dodawania_zdjec__admin()
	{
		$a_galerie = db::get_many('SELECT * FROM galleries ORDER BY id_galleries DESC');
		
		if(!$a_galerie)
		{
			router::set_checkpoint('galerie/formularz_galerii/edycja/false');
			app::err('Musisz najpierw dodać galerie');
			view::message();
		}
		else
		{
			view::add('a_galleries',$a_galerie);
			head::add_css_file('javascript/libs/file_upload/css/style.css');
			view::display();
		}
	}
	
	public static function zapisz_dane_zdjecia__admin()
	{
		if(!hlp_validator::id($_POST['id_galleries']))
			exit();
		$id=db::insert('photos',array('id_galleries'=>$_POST['id_galleries'],'filename'=>htmlspecialchars($_POST['fileName']),'add_date'=>'NOW()'));
		db::update('photos','id_photos='.$id,array('photos.order'=>$id));
		exit();
	}
	
	public static function zapisz_opis_zdjecia__admin()
	{
		if(!hlp_validator::id($_POST['id_galleries']))
			exit();
		
		db::update('photos','id_galleries='.$_POST['id_galleries'].' AND filename="'.htmlspecialchars($_POST['fileName']).'"',array('title'=>addslashes(strip_tags($_POST['title']))));
		exit();
	}
	
	public static function usun_zdjecie__admin()
	{
		if(isset($_POST['id_galleries']) && !hlp_validator::id(isset($_POST['id_galleries'])))
			exit();
			
		if(isset($_POST['id_photos']) && !hlp_validator::id(isset($_POST['id_photos'])))
			exit();

		if(isset($_POST['id_galleries']) && isset($_POST['fileName']))
		{
			db::delete('photos','id_galleries='.$_POST['id_galleries'].' AND filename="'.htmlspecialchars($_POST['fileName']).'"');
			unlink('images/galleries/'.$_POST['id_galleries'].'/'.htmlspecialchars($_POST['fileName']));
			unlink('images/galleries/'.$_POST['id_galleries'].'/thumbnail/'.htmlspecialchars($_POST['fileName']));
		}

		if($_POST['id_photos'])
		{
			$a_foto=db::get_by_id('photos', $_POST['id_photos']);
			db::delete('photos','id_photos='.$_POST['id_photos']);
			
			unlink('images/galleries/'.$a_foto['id_galleries'].'/'.htmlspecialchars($a_foto['filename']));
			unlink('images/galleries/'.$a_foto['id_galleries'].'/thumbnail/'.htmlspecialchars($a_foto['filename']));
		}

		exit();
	}
	
	public static function lista_galerii()
	{
		$a_galerie = mod_galerie::get_galleries();
		
		if(!app::get_result())
			view::message();
		else
		{
			view::add('a_galerie',$a_galerie);
			view::display();
		}
	}

	public static function zapisz_pozycje_galerii__admin()
	{
		$type=$_POST['type'];
		
		if($type!=='galleries' && $type!=='photos')
			exit();
		
		foreach($_POST['a_items'] as $klucz=>$wartosc)
		{
			if(hlp_validator::id($wartosc) && hlp_validator::id($klucz))
				db::update($type, 'id_'.$type.'='.$wartosc,array($type.'.order'=>$klucz));
		}
	}
	
	public static function edytuj_galerie__admin()
	{
		router::set_checkpoint('galerie/lista-galerii');
		$type=$_POST['type'];
		$field=$_POST['field'];

		if(($type!=='galleries' || $type!=='photos') && !hlp_validator::id($_POST['id']))
			exit();

		db::update($type,'id_'.$type.'='.$_POST['id'],array('title'=>$_POST['value']));
		exit();
	}

	public static function edytuj_galerie_wszystko__admin()
	{
		router::set_checkpoint('galerie/lista-galerii');
		mod_galerie::edytuj_galerie($_POST['a_galeria']);

		if(app::get_result())
			view::message();
		else
			view::redirect('galerie/formularz_galerii/id/'.$_POST['a_galeria']['id_galeries'].'/edycja/true');
	}

	public static function zmien_widocznosc__admin()
	{
		$type=$_POST['type'];
		
		if(($type!=='galleries' && $type!=='photos') || !hlp_validator::id($_POST['id']) || ($_POST['is_visible']!=='0' && $_POST['is_visible']!=='1'))
			exit();
		
		db::update($type,'id_'.$type.'='.$_POST['id'],array('is_visible'=>$_POST['is_visible']));
		exit();
	}
	
	public static function usun_galerie__admin()
	{
		if(!hlp_validator::id($_POST['id_galleries']))
			exit();
		
		db::delete('galleries','id_galleries='.$_POST['id_galleries']);
		db::delete('photos','id_galleries='.$_POST['id_galleries']);
		
		hlp_image::rrmdir('images/galleries/'.$_POST['id_galleries']);
		
		exit();
	}

	public static function pokaz()
	{
		if(!isset($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Brak takiej galerii');
			view::message();
		}

		$a_galeria=mod_galerie::get_gallery($_GET['id']);
		
		if(lang::get_lang()!=lang::get_default_lang())
			$lang = '_'.lang::get_lang();
		else
			$lang = '';

		if($a_galeria['seo_title'.$lang])
			head::set_title($a_galeria['seo_title'.$lang]);

		if($a_galeria['seo_description'.$lang])
			head::set_description($a_galeria['seo_description'.$lang]);

		if($a_galeria['seo_keywords'.$lang])
			head::set_keywords($a_galeria['seo_keywords'.$lang]);

		mod_panel::increase_visit_counter('galerie', $_GET['id']);

		if(session::who('admin') || session::who('mod'))
		{
			head::add_js_file('javascript/libs/jcrop/jquery.Jcrop.min.js');
			head::add_css_file('javascript/libs/jcrop/jquery.Jcrop.min.css');
		}

		head::add_css_file('javascript/libs/fancybox/jquery.fancybox.css');
		head::add_js_file('javascript/libs/fancybox/jquery.fancybox.js');
		view::add('a_gallery',$a_galeria);		
		view::add('a_photos',mod_galerie::get_photos($_GET['id']));
		view::display();
	}
	
	public static function zdjecie()
	{
		if(!isset($_GET['id_galerii']) || !hlp_validator::id($_GET['id_galerii']))
		{
			app::err('Brak takiej galerii');
			view::message();
		}

		if(!isset($_GET['id_zdjecia']) || !hlp_validator::id($_GET['id_zdjecia']))
		{
			app::err('Brak takiego zdjęcia');
			view::message();
		}
		
		view::add('a_zdjecie',db::get_by_id('photos',$_GET['id_zdjecia']));
		
		if(isset($_GET['rodzaj']) && $_GET['rodzaj']=='miniatura')
			view::display('galerie/miniatura.tpl',true);
		else
			view::display('',true);
	}
	
	public static function ustaw_zdjecie_profilowe__admin()
	{
		if(!hlp_validator::id($_POST['id_photos']))
			exit();
		db::deb();
		db::update('photos','id_galleries='.$_POST['id_galleries'],array('is_mainphoto'=>0));
		db::update('photos','id_photos='.$_POST['id_photos'].' AND id_galleries='.$_POST['id_galleries'],array('is_mainphoto'=>1));
		exit();
	}

	public static function tworz_miniaturke__admin()
	{
		$targ_w = 250;
		$targ_h = 190;
		$jpeg_quality = 90;

		$src = $_POST['img'];
		
		$ext = hlp_image::get_extension($src);
		
		if($ext=='.jpg' || $ext=='.jpeg')
			$img_r = imagecreatefromjpeg($src);
		elseif($ext=='.gif')
			$img_r = imagecreatefromgif($src);
		else
			$img_r = imagecreatefrompng($src);
		
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		
		$thumb = preg_replace('/http.+\/(images)/','$1',$src);
		$thumb = preg_replace('/(\/\d+\/)/','$1thumbnail/',$thumb);
		preg_match('/(.+)\?t=.+/U',$thumb,$a_saved);
		
		if(isset($a_saved[1]))
			$thumb = $a_saved[1];

		//hlp_image::save_resized($thumb, $thumb, $targ_w, $targ_h, $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h']);

		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'], $targ_w,$targ_h,$_POST['w'],$_POST['h']);

		unlink($thumb);
		imagejpeg($dst_r,$thumb,$jpeg_quality);
	
		exit;
	}
	
}

?>