<?php

class con_cenniki extends controllers_parent{
	
	public static function lista_cennikow__admin()
	{
		view::add('a_cenniki_placowki',mod_cenniki::get_cenniki(session::get('id_placowki')));
		view::add('a_cenniki',mod_cenniki::get_cenniki(0));
		view::display();
	}

	public static function zapisz_cennik_wysylki__admin()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']))
			echo 'false';
		
		db::update('cenniki','1=1',array('czy_wysylki'=>0));
		db::update('cenniki','id_cenniki='.$_POST['id'],array('czy_wysylki'=>1));
	}
	
	public static function formularz_cennika__admin()
	{
		if(isset($_GET['id']) && !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowy cennik');
			view::message();
		}

		if(isset($_GET['id']) && !session::who('admin'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		if(isset($_GET['id']))
		{
			$a_cennik = mod_cenniki::get_cennik($_GET['id']);
			
			if(!$a_cennik)
			{
				app::err('Brak cennika o podanym numerze');
				view::message();
			}
			
			view::add('a_przedzialy_cenowe',mod_cenniki::zwroc_przedzialy_cenowe($_GET['id']));
			view::add('a_cennik',$a_cennik);
		}

		view::display();
	}
	

	public static function zapisz_cennik__admin()
	{
		$a_cennik=$_POST['a_cennik'];

		$id_cenniki = mod_cenniki::zapisz_cennik($a_cennik);
		
		if(!app::get_result())
		{
			if(isset($a_cennik['id_cenniki']))
				view::redirect('cenniki/formularz_cennika/id/'.$a_cennik['id_cenniki']);
			else
				view::redirect('cenniki/formularz_cennika');
		}
		else
		{
			if($_POST['a_przedzialy'])
			{
				mod_cenniki::usun_przedzialy_cenowe($id_cenniki);
				mod_cenniki::zapisz_przedzialy_cenowe($_POST['a_przedzialy'],$id_cenniki);
			}
/*
			if(empty($a_cennik['id_cenniki']))
				mod_logi::dodaj('nowy cennik',$id_cenniki,$a_cennik['nazwa']);
			else
				mod_logi::dodaj('edytowano cennik',$id_cenniki,$a_cennik['nazwa']);
			*/
			view::redirect('cenniki/lista-cennikow');
		}
	}
	
	public static function usun_cennik__admin()
	{
		if(isset($_POST['id']) && !session::who('admin'))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		db::delete('cenniki',"id_cenniki=".$_POST['id']);
		mod_cenniki::usun_przedzialy_cenowe($_POST['id']);
		//mod_logi::dodaj('usunięto cennik',$_POST['id']);
		
		$a_domyslny_cennik = mod_cenniki::get_domyslny_cennik();
		
		if($a_domyslny_cennik['id_cenniki']==$_POST['id'])
		{
			$id_cenniki = db::get_one("SELECT id_cenniki FROM cenniki LIMIT 1");
			db::update('karty',"id_cenniki=".$_POST['id'],array('id_cenniki'=>$id_cenniki));
			db::update('karty_placowki',"id_cenniki=".$_POST['id'],array('id_cenniki'=>0));
		}
	}
}

?>