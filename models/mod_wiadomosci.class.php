<?php

class mod_wiadomosci extends db{

	public static function check_adresat($id_adresata)
	{
		if(isset($id_adresata) && $id_adresata!=0 && (!hlp_validator::id($id_adresata) || !db::get_by_id('users',$id_adresata)))
		{
			app::err('Nieznany adresat');
			return false;
		}

		return app::ok('');
	}
	
	public static function zapisz_zewnetrzne_zalaczniki($id_wiadomosci, $a_zalaczniki)
	{
		if(is_array($a_zalaczniki) && count($a_zalaczniki)>0)
		{
			foreach($a_zalaczniki as $nazwa=>$url)
			{
				db::insert('wiadomosci_zalaczniki_zewnetrzne', array('id_wiadomosci'=>$id_wiadomosci,'nazwa'=>$nazwa, 'url'=>$url));
			}
		}
	}
	
	public static function nowa_wiadomosc($ia_wiadomosc,$a_zalaczniki)
	{
		if($ia_wiadomosc['id_adresata']!='' && !self::check_adresat($ia_wiadomosc['id_adresata']))
			return false;
		
		$uniqid_wiadomosci = hlp_functions::get_uniq_id();
		while(db::get_one("SELECT uniqid_wiadomosci FROM wiadomosci WHERE uniqid_wiadomosci='$uniqid_wiadomosci'"))
			$uniqid_wiadomosci = hlp_functions::get_uniq_id();
		
		$a_dane = array('temat'=>$ia_wiadomosc['temat'],
						'tresc'=>$ia_wiadomosc['tresc'],
						'uniqid_wiadomosci'=>$uniqid_wiadomosci,
						'id_nadawcy'=>$ia_wiadomosc['id_nadawcy'],
						'id_adresata'=>$ia_wiadomosc['id_adresata'],
						'data_utworzenia'=>'NOW()',
						'status'=>$ia_wiadomosc['status']);

		if($ia_wiadomosc['status']=='wyslana')
			$a_dane = array_merge($a_dane,array('data_wyslania'=>'NOW()'));

		$id_wiadomosci = db::insert('wiadomosci',$a_dane);
		self::zapisz_zewnetrzne_zalaczniki($id_wiadomosci, $a_zalaczniki);
		app::ok("Wiadomość wysłana");
		return $id_wiadomosci;
	}
	
	public static function edytuj_wiadomosc($ia_wiadomosc)
	{
		if($ia_wiadomosc['id_adresata']!='' && !self::check_adresat($ia_wiadomosc['id_adresata']))
			return false;

		$a_dane = array('temat'=>$ia_wiadomosc['temat'],
						'tresc'=>$ia_wiadomosc['tresc'],
						'id_nadawcy'=>$ia_wiadomosc['id_nadawcy'],
						'id_adresata'=>$ia_wiadomosc['id_adresata'],
						'status'=>$ia_wiadomosc['status']);
		
		if($ia_wiadomosc['status']=='wyslana')
			$a_dane = array_merge($a_dane,array('data_wyslania'=>'NOW()'));				
						
		db::update('wiadomosci',"id_wiadomosci={$ia_wiadomosc['id_wiadomosci']}",$a_dane);
		
		app::ok("Wiadomość wysłana");
		return $ia_wiadomosc['id_wiadomosci'];
	}
	
	public static function zapisz_zalaczniki($a_zalaczniki,$id_wiadomosci)
	{
		//var_dump($a_zalaczniki);
		$id_users = session::who('admin') || session::who('mod') ? 0  : session::get_id();
		$licznik=0;
		@mkdir("images/users/$id_users");
		@mkdir("images/users/$id_users/messages");
		
		while($licznik<count($a_zalaczniki['a_zalaczniki']['tmp_name']))
		{
			if($a_zalaczniki['a_zalaczniki']['tmp_name'][$licznik]!='')
			{
				if(!is_dir("images/users/$id_users/messages/$id_wiadomosci"))
					mkdir("images/users/$id_users/messages/$id_wiadomosci");
				
				$name = $a_zalaczniki['a_zalaczniki']['name'][$licznik];
				if(hlp_validator::extension($name,array('jpeg','jpg','png','txt','pdf','gif','doc','tiff','txt','docx','csv','xls','xlt','xml','xlsx')))
				{
					$a_pliki = glob("images/users/$id_users/messages/$id_wiadomosci/$name");
					
					if(count($a_pliki))
						$filename = count($a_pliki)+1 .'_'. $name;
					else 
						$filename = $name;
					
					$url = "images/users/$id_users/messages/$id_wiadomosci/$filename";
					move_uploaded_file($a_zalaczniki['a_zalaczniki']['tmp_name'][$licznik], $url);
					self::zapisz_zewnetrzne_zalaczniki($id_wiadomosci, array($filename=>$url));
				}
			}
			$licznik++;
		}
	}
	
	public static function get_liczba_wiadomosci($id_users,$status)
	{
		$data_null = '0000-00-00 00:00:00';
		if($status=='odebrane')
			return db::get_one("SELECT COUNT(*) FROM wiadomosci WHERE id_adresata=$id_users AND data_wyslania<>'$data_null' AND data_usuniecia_adresat='$data_null'");
		elseif($status=='nowe')
			return db::get_one("SELECT COUNT(*) FROM wiadomosci WHERE id_adresata=$id_users AND data_wyslania<>'$data_null' AND data_usuniecia_adresat='$data_null' AND data_przeczytania='$data_null'");
		elseif($status=='wyslane')
			return db::get_one("SELECT COUNT(*) FROM wiadomosci WHERE id_nadawcy=$id_users AND data_wyslania<>'$data_null' AND data_usuniecia_nadawca='$data_null'");
		elseif($status=='robocze')
			return db::get_one("SELECT COUNT(*) FROM wiadomosci WHERE id_nadawcy=$id_users AND data_wyslania='$data_null' AND data_usuniecia_nadawca='$data_null'");
	}
	
	public static function get_wiadomosci($status)
	{
		if($status=='odebrane')
		{
			if(session::who('admin') && !session::get('czy_zdalny'))
				$sql_status = "(id_adresata=0 || id_adresata=".session::get_id().")";
			else
				$sql_status = "id_adresata=".session::get_id();
		}
		elseif($status=='wyslane' || $status=='robocze')
		{
			if(session::who('admin') && !session::get('czy_zdalny'))
				$sql_status = "(id_nadawcy=0 || id_nadawcy=".session::get_id().")";
			else
				$sql_status = "id_nadawcy=".session::get_id();
		}

		$data_null = '0000-00-00 00:00:00';
		if($status=='odebrane')
			return db::get_many("SELECT wiadomosci.*,users.imie,users.nazwisko FROM wiadomosci LEFT JOIN users ON id_nadawcy=id_users WHERE $sql_status AND data_wyslania<>'$data_null' AND data_usuniecia_adresat='$data_null' ORDER BY id_wiadomosci DESC");
		elseif($status=='wyslane')
			return db::get_many("SELECT wiadomosci.*,users.imie,users.nazwisko FROM wiadomosci LEFT JOIN users ON id_adresata=id_users WHERE $sql_status AND data_wyslania<>'$data_null' AND data_usuniecia_nadawca='$data_null' ORDER BY id_wiadomosci DESC");
		elseif($status=='robocze')
			return db::get_many("SELECT wiadomosci.*,users.imie,users.nazwisko FROM wiadomosci LEFT JOIN users ON id_adresata=id_users WHERE $sql_status AND data_wyslania='$data_null' AND data_usuniecia_nadawca='$data_null' ORDER BY id_wiadomosci DESC");
	}
	
	public static function get_wiadomosc($id_wiadomosci)
	{
		$data_null = '0000-00-00 00:00:00';
		$a_wiadomosc = db::get_row("SELECT wiadomosci.*,
								(SELECT concat(imie,' ',nazwisko) FROM users WHERE id_nadawcy=id_users AND uniqid_wiadomosci=$id_wiadomosci) as adresat,
								(SELECT concat(imie,' ',nazwisko) FROM users WHERE id_adresata=id_users AND uniqid_wiadomosci=$id_wiadomosci) as nadawca
								 FROM wiadomosci WHERE uniqid_wiadomosci=$id_wiadomosci AND data_usuniecia_adresat='$data_null'");

		if(!$a_wiadomosc)
		{
			app::err("Nieznana wiadomość");
			view::message();
		}
		
		if($a_wiadomosc['id_adresata']!=session::get_id() && $a_wiadomosc['id_nadawcy']!=session::get_id() && !session::who('admin'))
		{
			app::err("Nie masz dostępu do tej wiadomości");
			view::message();
		}

		app::ok();
		return $a_wiadomosc;
	}
	
	public static function get_users_nowe_wiadomosci()
	{
		$data_null = '0000-00-00 00:00:00';db::deb();
		return db::get_many("SELECT id_adresata,email,COUNT(*) as liczba_wiadomosci FROM wiadomosci JOIN users ON id_adresata=id_users WHERE data_wyslania<>'$data_null' AND data_usuniecia_adresat='$data_null' AND data_przeczytania='$data_null' GROUP BY id_adresata");
	}
	
	public static function przeczytaj_wiadomosc($id_wiadomosci)
	{
		db::update('wiadomosci','uniqid_wiadomosci='.$id_wiadomosci,array('data_przeczytania'=>'NOW()'));
	}
	
	public static function get_zalaczniki($id_wiadomosci,$id_users)
	{
		return glob("images/users/$id_users/messages/$id_wiadomosci/*");
	}
	
	public static function get_zalaczniki_zewnetrzne($id_wiadomosci)
	{
		return db::get_many("SELECT * FROM wiadomosci_zalaczniki_zewnetrzne JOIN wiadomosci USING(id_wiadomosci) WHERE wiadomosci.id_wiadomosci=$id_wiadomosci");
	}
	
	public static function czy_wyslac_maila($id_users)
	{
		return db::get_one("SELECT wiadomosci_email FROM users WHERE id_users=$id_users");
	}
	
	public static function czy_wyslac_maila_cron($id_users)
	{
		return db::get_one("SELECT wiadomosci_email_cron FROM users WHERE id_users=$id_users");
	}
	
	public static function zapisz_ustawienia($ia_ustawienia,$id_users)
	{
		$czy_wyslac_maila = $ia_ustawienia['czy_wyslac_maila']=='czy_wyslac_maila' ? 1 : 0;
		$czy_wyslac_maila_cron = $ia_ustawienia['czy_wyslac_maila']=='czy_wyslac_maila_cron' ? 1 : 0;
		
		$a_dane = array('wiadomosci_email'=>$czy_wyslac_maila,'wiadomosci_email_cron'=>$czy_wyslac_maila_cron);
		db::update('users',"id_users=$id_users",$a_dane);
		
		app::ok(lang::get('wiadomosci-ustawienia-zapisano',1));
		return true;
	}

	public static function usun($ids)
	{
		$a_id = explode(',',trim($ids,','));
		$id_users = session::get_id();
		$data_null = '0000-00-00 00:00:00';

		foreach($a_id as $id)
		{
			$a_wiadomosc = self::get_wiadomosc(db::get_one("SELECT uniqid_wiadomosci FROM wiadomosci WHERE id_wiadomosci=$id"));

			if($a_wiadomosc['data_wyslania']==$data_null)
			{
				db::delete('wiadomosci', "id_wiadomosci=$id");
				db::delete('wiadomosci_zalaczniki_zewnetrzne', 'id_wiadomosci='.$id);
			}
			
			if($a_wiadomosc['id_nadawcy']==$id_users || session::who('admin') && $a_wiadomosc['id_nadawcy']==0)
				db::update('wiadomosci', "id_wiadomosci=$id",array('data_usuniecia_nadawca'=>'now()'));
			
			if($a_wiadomosc['id_adresata']==$id_users || session::who('admin') && $a_wiadomosc['id_adresata']==0)
				db::update('wiadomosci', "id_wiadomosci=$id",array('data_usuniecia_adresat'=>'now()'));
		}
		
	}
	
	public static function czy_nowe_wiadomosci()
	{
		if(!session::who('admin'))
			return db::get_one("SELECT czy_nowe_wiadomosci FROM users WHERE id_users=".session::get_id());
		else
			return db::get_one("SELECT 1 FROM wiadomosci WHERE id_adresata=0 AND status='wyslana' AND data_przeczytania='0000-00-00 00:00:00'");
	}

}

?>