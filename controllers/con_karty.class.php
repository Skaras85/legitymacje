<?php
class con_karty extends controllers_parent{
	
	public static function formularz_karty__admin()
	{
		if(isset($_GET['id']) && !hlp_validator::id($_GET['id']))
		{
			app::err('Nieprawidłowe id');
			view::message();
		}
	
		if(isset($_GET['id']))
		{
			if(isset($_GET['hologram']))
			{
				$a_karta = mod_produkty::get_produkt($_GET['id']);
				view::add('a_domyslne_cenniki_karty_wysylki',mod_produkty::get_cenniki_produktu_wysylki($_GET['id'],0));
			}
			else
			{
				$a_karta = mod_karty::get_karta($_GET['id']);
				view::add('a_domyslne_cenniki_karty_wysylki',mod_karty::get_domyslne_cenniki_karty_wysylki($_GET['id']));
			}
			
			if(!$a_karta)
			{
				app::err('Brak produktu o podanym numerze');
				view::message();
			}
			
			view::add('a_karta',$a_karta);
		}

		view::add('a_strony',db::get_many("SELECT title,id_sites FROM sites WHERE id_article_categories=4"));
		view::add('a_sposoby_wysylki',db::get_all('sposoby_wysylki'));
		
		view::add('a_cenniki',mod_cenniki::get_cenniki());
		
		if(isset($_GET['hologram']))
		{
			view::add('a_kategorie',mod_produkty::get_kategorie());
		}
		view::add('czy_hologramy',isset($_GET['hologram']));
		view::display();
	}
	

	public static function zapisz_karte__admin()
	{
		$a_karta=$_POST['a_karta'];

		$id_karty = mod_karty::zapisz_dane($a_karta);
		
		if(!app::get_result())
		{
			if(isset($a_karta['id_karty']))
				view::redirect('karty/formularz_karty/id/'.$a_karta['id_karty']);
			else
				view::redirect('karty/formularz_karty');
		}
		else
		{
			mod_karty::zapisz_domyslne_cenniki_wysylki_karty($id_karty,$_POST['a_cenniki'],$_POST['a_max_ilosc_legitymacji']);
			view::redirect('karty/lista_kart');
		}
	}

	public static function lista_kart__admin()
	{
		view::add('a_karty',mod_karty::get_karty());
		view::display();
	}
	
	public static function formularz_pol__admin()
	{
		if(empty($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieprawidłowa karta');
			view::message();
		}
	
		view::add('a_karta',mod_karty::get_karta($_GET['id_karty']));
		view::add('a_pola',mod_karty::get_pola_karty_raw($_GET['id_karty']));
		view::add('a_typy',db::get_all('karty_pola_typy'));
		view::display();
	}
	
	public static function zapisz_pola__admin()
	{
		$a_pola=$_POST['a_pola'];
		$id_karty = $_POST['id_karty'];
		
		if(empty($_POST['id_karty']) || !hlp_validator::id($_POST['id_karty']))
		{
			app::err('Nieprawidłowa karta');
			view::message();
		}

		mod_karty::usun_pola($id_karty);
		mod_karty::zapisz_pola($a_pola,$id_karty);
		
		if(!app::get_result())
		{
			view::redirect('karty/formularz_pol/id_karty/'.$id_karty);

		}
		else
			view::redirect('karty/lista_kart');
	}
	
	public static function formularz_cennika()
	{
		if(!session::who('admin') && !session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień, aby oglądać tę stronę');
			view::message();
		}
		
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}
		
		if(empty($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieprawidłowa karta');
			view::message();
		}
		
		view::add('a_placowka',mod_placowki::get_placowka(session::get('id_placowki')));
		view::add('a_karta',mod_karty::get_karta($_GET['id_karty']));
		view::add('id_karty',$_GET['id_karty']);
		view::add('a_sposoby_wysylki',mod_koszyk::get_sposoby_wysylki());
		view::add('a_cenniki',mod_cenniki::get_cenniki());
		view::add('a_cenniki_wysylka',mod_karty::get_cenniki_karty_wysylka($_GET['id_karty']));
		view::add('id_cenniki',mod_karty::get_cennik_karty($_GET['id_karty']));
		view::display();
	}
	
	public static function zapisz_cennik()
	{
		if(!session::who('admin') && !session::get('czy_zdalny'))
		{
			app::err('Nie masz uprawnień, aby oglądać tę stronę');
			view::message();
		}
		
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}
		
		if(empty($_POST['id_karty']) || !hlp_validator::id($_POST['id_karty']))
		{
			app::err('Nieprawidłowa karta');
			view::message();
		}

		mod_karty::zapisz_cennik($_POST['id_karty'],$_POST['id_cenniki'],$_POST['a_sposoby_wysylki']);
		view::redirect('legitymacje/lista-osob-legitymacji/id_karty/'.$_POST['id_karty']);
	}

	public static function get_pola_karty()
	{
		if(!isset($_GET['id_karty']) || !hlp_validator::id($_GET['id_karty']))
		{
			app::err('Nieznany typ legitymacji');
			view::message();
		}
		
		if(!mod_karty::sprawdz_dostep_karty($_GET['id_karty'],session::get('id_placowki')))
		{
			app::err('Karta nie jest przypisana do tej placówki');
			view::message();
		}
		
		view::json(true,'',array('a_pola'=>mod_karty::get_pola_karty($_GET['id_karty'],isset($_GET['bez-zdjecia']))));
	}
	
}

	
?>