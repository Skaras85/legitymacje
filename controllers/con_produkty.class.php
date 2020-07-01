<?php

class con_produkty extends controllers_parent{
	
	public static function pokaz_produkty()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieznany typ produktów');
			view::message();
		}
		
		if($_GET['id']==1 && !mod_legitymacje::czy_placowka_ma_legitymacje_szkolne(session::get('id_placowki')))
		{
			app::err('Hologramy można zamawiać tylko do legitymacji szkolnych');
			view::message();
		}
		
		view::add('a_kategoria',mod_produkty::get_kategoria($_GET['id']));
		view::add('a_produkty',mod_produkty::get_produkty_po_kategorii($_GET['id']));
		view::display();
	}
	
	public static function zapisz_produkt__admin_mod()
	{
		$a_produkt=$_POST['a_karta'];

		$id_produkty = mod_produkty::zapisz_dane($a_produkt);

		if(!app::get_result())
		{
			if(isset($a_produkt['id_produkty']))
				view::redirect('karty/formularz_karty/hologram/1/id/'.$a_karta['id_karty']);
			else
				view::redirect('karty/formularz_kartyhologram/1');
		}
		else
		{
			mod_produkty::zapisz_cenniki_wysylki_produkty($id_produkty,$_POST['a_cenniki']);
			view::redirect('produkty/pokaz_produkty/id/1');
		}
	}
	
	public static function usun_produkt__admin_mod()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieznany produkt');
			view::message();
		}
		
		if(mod_zamowienia::sprawdz_czy_produkt_byl_zamawiany($_GET['id']))
		{
			app::err('Ten produkt był już zamawiany, więc nie możesz go usunąć. Możesz go jedynie ukryć');
			view::message();
		}
		
		$a_kategoria = mod_produkty::get_kategoria_produktu($_GET['id']);
		db::delete('produkty','id_produkty='.$_GET['id']);
		db::delete('produkty_cenniki_wysylki','id_produkty='.$_GET['id']);
		
		app::ok('Produkt usunięty');
		view::redirect('produkty/pokaz_produkty/id/'.$a_kategoria['id_produkty_kategorie']);
	}

	public static function zmien_widocznosc_produktu__admin_mod()
	{
		if(empty($_GET['id']) || !hlp_validator::id($_GET['id']))
		{
			app::err('Nieznany produkt');
			view::message();
		}
		
		$a_kategoria = mod_produkty::get_kategoria_produktu($_GET['id']);
		$a_produkt = mod_produkty::get_produkt($_GET['id']);
		
		db::update('produkty',"id_produkty={$a_produkt['id_produkty']}",array('is_visible'=>(int)!$a_produkt['is_visible']));
		
		app::ok('Produkt '. ($a_produkt['is_visible'] ? 'ukryty' : 'odkryty'));
		view::redirect('produkty/pokaz_produkty/id/'.$a_kategoria['id_produkty_kategorie']);
	}
	
	public static function formularz_cennika__admin_mod()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}
		
		if(empty($_GET['id_produktu']) || !hlp_validator::id($_GET['id_produktu']))
		{
			app::err('Nieprawidłowy produkt');
			view::message();
		}
		
		view::add('a_placowka',mod_placowki::get_placowka(session::get('id_placowki')));
		view::add('a_produkt',mod_produkty::get_produkt($_GET['id_produktu']));
		view::add('id_produktu',$_GET['id_produktu']);
		view::add('a_sposoby_wysylki',mod_koszyk::get_sposoby_wysylki());
		view::add('a_cenniki',mod_cenniki::get_cenniki());
		view::add('a_cenniki_wysylka',mod_produkty::get_cenniki_produktu_wysylki($_GET['id_produktu'],session::get('id_placowki')));
		view::add('id_cenniki',mod_produkty::get_cennik_produktu($_GET['id_produktu']));
		view::display('karty/formularz_cennika.tpl');
	}
	
	public static function zapisz_cennik__admin_mod()
	{
		if(!session::get('id_placowki'))
		{
			app::err('Musisz najpierw wybrać placówkę');
			view::message();
		}
		
		if(empty($_POST['id_produktu']) || !hlp_validator::id($_POST['id_produktu']))
		{
			app::err('Nieprawidłowy produkt');
			view::message();
		}
		
		mod_produkty::zapisz_cennik($_POST['id_produktu'],$_POST['id_cenniki'],$_POST['a_sposoby_wysylki']);
		view::redirect('produkty/pokaz-produkty/id/1');
	}
	
}

?>