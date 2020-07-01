<?php

class con_przesylki extends controllers_parent{

	public static function lista_placowek__admin_mod()
	{
		if(!empty($_GET['fraza']))
			view::add('a_placowki',mod_placowki::get_placowki($_GET['fraza'],'',true));
		view::display();
	}
	
	public static function formularz_przesylki__admin_mod()
	{
		if(isset($_GET['id_przesylki']) && !hlp_validator::id($_GET['id_przesylki']))
		{
			app::err('Nieprawidłowa przesyłka');
			view::message();
		}
		
		if(!isset($_GET['id_przesylki']) && (empty($_GET['id_placowki']) || !hlp_validator::id($_GET['id_placowki'])))
		{
			app::err('Nieprawidłowa placówka');
			view::message();
		}
		
		if(isset($_GET['id_przesylki']))
		{
			$a_przesylka = mod_przesylki::get_przesylka($_GET['id_przesylki']);
			
			if(!$a_przesylka)
			{
				app::err('Brak przesyłki o podanym numerze');
				view::message();
			}
			
			$a_przesylka['uwagi'] = str_replace('<br />','',$a_przesylka['uwagi']);
			view::add('a_przesylka',$a_przesylka);
		}
		else
		{
			view::add('przesylki_rok',session::get('przesylki_rok') ? session::get('przesylki_rok') : date('Y'));
			view::add('przesylki_miesiac',session::get('przesylki_miesiac') ? session::get('przesylki_miesiac') : date('m'));
			view::add('przesylki_data_otrzymania',session::get('przesylki_data_otrzymania') ? session::get('przesylki_data_otrzymania') : date('Y-m-d'));
		}
		
		$id_placowki = isset($_GET['id_placowki']) ? $_GET['id_placowki'] : $a_przesylka['id_placowki'];

		view::add('id_placowki',$id_placowki);
		view::add('a_zamowienia',mod_zamowienia::get_zamowienia_prosto($id_placowki,'złożone'));
		view::add('a_adresaci',mod_placowki::subkonta_placowki($id_placowki));
		view::add('a_placowka',mod_placowki::get_placowka($id_placowki));
		view::display();
	}
	
	public static function zapisz_przesylke__admin_mod()
	{
		$a_przesylka = $_POST['a_przesylka'];
		mod_przesylki::zapisz_przesylke($a_przesylka);
		
		if(!app::get_result())
		{
			if(isset($a_przesylka['id_przesylki']))
				view::redirect('przesylki/formularz_przesylki/id/'.$a_przesylka['id_placowki']);
			else
				view::redirect('przesylki/formularz_przesylki/id_placowki/'.$a_przesylka['id_placowki']);
		}
		else
		{
			session::set('przesylki_rok',$a_przesylka['rok']);
			session::set('przesylki_miesiac',$a_przesylka['miesiac']);
			session::set('przesylki_data_otrzymania',$a_przesylka['data_otrzymania']);

			if(!empty($a_przesylka['czy_wyslac_maila']))
			{
				$id_users = db::get_one("SELECT id_users FROM placowki WHERE id_placowki=".$a_przesylka['id_placowki']);
				$email = db::get_one("SELECT email FROM users WHERE id_users=".$id_users);
				mod_users::wyslij_maila($email,7);
			}
			view::redirect('przesylki/lista-przesylek');
		}
	}
	
	public static function lista_przesylek__lg()
	{
		view::add('a_przesylki',mod_przesylki::get_przesylki(false,false,!session::who('admin') ? session::get_id() : false));
		view::display();
	}
	
	public static function pokaz_przesylke__lg()
	{
		if(empty($_GET['id_przesylki']) || !hlp_validator::id($_GET['id_przesylki']))
		{
			app::err('Brak przesyłki o podanym numerze');
			view::message();
		}
		
		if(!session::who('admin') && !mod_przesylki::sprawdz_dostep_przesylki($_GET['id_przesylki']))
		{
			app::err('Brak dostępu');
			view::message();
		}
		
		view::add('a_przesylka',mod_przesylki::get_przesylka($_GET['id_przesylki']));
		view::display();
	}

}

	
?>