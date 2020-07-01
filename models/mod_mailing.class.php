<?php

class mod_mailing{
	
	public static function get_szablony()
	{
		return db::get_all('mailing_szablony');
	}
	
	public static function get_szablon($id)
	{
		return db::get_by_id("mailing_szablony", $id);
	}
	
	public static function zapisz_szablon($ia_strona)
	{
		if(empty($ia_strona['email']) || !hlp_validator::email($ia_strona['email']))
			return app::err("Nieprawidłowy email");
		
		if(!empty($ia_strona['email_kopia']) && !hlp_validator::email($ia_strona['email_kopia']))
			return app::err("Nieprawidłowy email kopii");
		
		if(empty($ia_strona['nadawca']))
			return app::err("Nieprawidłowy nadawca");
		
		if(empty($ia_strona['temat']))
			return app::err("Nieprawidłowy temat");
		
		if(empty($ia_strona['text']))
			return app::err("Nieprawidłowa treść");
		
		if(isset($ia_strona['id_mailing_szablony']) &&  !hlp_validator::id($ia_strona['id_mailing_szablony']))
			return app::err("Nieprawidłowe id szablonu");

		if(isset($ia_strona['id_mailing_szablony']))
			db::update('mailing_szablony',"id_mailing_szablony={$ia_strona['id_mailing_szablony']}",$ia_strona);
		else
			$id = db::insert('mailing_szablony',$ia_strona);
		
		$id = isset($id) ? $id : $ia_strona['id_mailing_szablony'];

		if(is_uploaded_file($_FILES['zalaczniki']['tmp_name'][0]))
		{
			for($i=0; $i<count($_FILES['zalaczniki']['name']); $i++)
			{
				if(!hlp_validator::extension($_FILES['zalaczniki']['name'][$i],array('jpg','png','pdf','doc','docx')))
					return app::ok('Zapisano szablon, ale nie zapisano załącznika - nieprawidowy typ pliku');
				else
				{
					$a_file = explode('.',$_FILES['zalaczniki']['name'][$i]);
					$ext = $a_file[count($a_file)-1];
					$a_file_count = glob("images/zalaczniki/{$id}\-*");
					$file_count = count($a_file_count)+1;
					
					@mkdir("images/zalaczniki");
					
					move_uploaded_file($_FILES['zalaczniki']['tmp_name'][$i], "images/zalaczniki/{$id}-{$file_count}.$ext");
				}
			}
		}

		return app::ok('Zapisano szablon');
	}
	
	public static function zapisz_mailing($id_mailing_szablony=0,$ids,$temat=false,$tresc=false,$email_od=false,$email_kopia=false,$nadawca=false)
	{
		if(!empty($id_mailing_szablony))
		{
			$a_szablon = db::get_row("SELECT * FROM mailing_szablony WHERE id_mailing_szablony=$id_mailing_szablony");
			
			if(!$a_szablon)
				return app::err('Brak szablonu powiadomienia');
			
			if($tresc)
				$a_szablon['text'] .= $tresc;
		}
		else
		{
			if($tresc)
				$a_szablon['text'] = $tresc;
		}
		
		if($temat)
			$a_szablon['temat'] = $temat;

		if($email_od)
			$a_szablon['email'] = $email_od;
		
		if($email_kopia)
			$a_szablon['email_kopia'] = $email_kopia;
		
		if($nadawca)
			$a_szablon['nadawca'] = $nadawca;

		$id_mailing = db::insert('mailing',array('id_users'=>session::get_id(),
												 'id_mailing_szablony'=>$id_mailing_szablony,
												 'email'=>$a_szablon['email'],
												 'email_kopia'=>$a_szablon['email_kopia'],
												 'nadawca'=>$a_szablon['nadawca'],
												 'temat'=>$a_szablon['temat'],
												 'text'=>$a_szablon['text'],
												 'status'=>'open',
												 'date_start'=>'NOW()'));
												 
		 $a_ids = explode(',',$ids);

		 foreach($a_ids as $id_placowki)
		 {
		 	db::insert("mailing_osoby",array('id_mailing'=>$id_mailing,
										     'id_placowki'=>$id_placowki,
										     'email'=>db::get_one("SELECT email FROM placowki JOIN users USING(id_users) WHERE id_placowki=$id_placowki"),
										     'id_users'=>db::get_one("SELECT id_users FROM placowki WHERE id_placowki=$id_placowki"),
											 'text'=>self::stworz_tresc_mailingu($id_mailing_szablony, $a_szablon['text'], $id_placowki)));
		 }

		 app::ok('Rozpoczęto wysyłanie powiadomień');
		 return $id_mailing;
	}
	
	public static function get_adresaci($id_mailing,$limit)
	{
		return db::get_many("SELECT mailing_osoby.* FROM mailing_osoby WHERE id_mailing=$id_mailing AND is_sent=0 LIMIT $limit");
	}
	
	public static function wyslij_mailing($a_mailing,$a_adresaci,$a_zalaczniki=false)
	{
		if(!$a_mailing || !$a_adresaci)
			return false;

		foreach($a_adresaci as $a_adresat)
		{
			if(hlp_validator::email($a_adresat['email']))
			{
				$tresc = $a_adresat['text'];
				$tresc = '<!DOCTYPE HTML><html><head><meta charset="utf-8"></head><table style="color:#505b68;font-family: Arial; font-size: 14px; line-height: 23px"><tr><td>'.$tresc.'</td></tr></table></html>';
	
				mailer::add_address($a_adresat['email']);
	
				if($a_mailing['email_kopia']!='')
					mailer::add_address($a_mailing['email_kopia']);
				
				if(!empty($a_mailing['id_powiadomienia']))
					$a_zalaczniki = glob("images/zalaczniki/{$a_mailing['id_powiadomienia']}\-*");
	
				if($a_zalaczniki)
				{
					foreach($a_zalaczniki as $zalacznik)
					{
						mailer::add_attachment($zalacznik,basename($zalacznik));
					}
				}
	
				mailer::send($a_mailing['temat'],$tresc,array('email'=>$a_mailing['email'],'name'=>$a_mailing['nadawca']),strip_tags($tresc));
				//echo $a_mailing['email'].'<br>';
				
				db::update('mailing_osoby',"id_mailing={$a_mailing['id_mailing']} AND id_placowki={$a_adresat['id_placowki']}",array('is_sent'=>1,'data_wysylki'=>'NOW()','czy_wyslano'=>app::get_result() ? 1 : 0,'msg'=>view::get_message()));
				
				if(app::get_result())
				{
					$a_wiadomosc = array('temat'=>$a_mailing['temat'],
										'tresc'=>$tresc,
										'id_nadawcy'=>0,
										'id_adresata'=>$a_adresat['id_users'],
										'status'=>'wyslana');
					mod_wiadomosci::nowa_wiadomosc($a_wiadomosc);
				}
			}
			else
				db::update('mailing_osoby',"id_mailing={$a_mailing['id_mailing']} AND id_placowki={$a_adresat['id_placowki']}",array('is_sent'=>1,'data_wysylki'=>'NOW()','czy_wyslano'=>0,'msg'=>'Nieprawdiłowy adres email'));
		}
	}

	public static function stworz_tresc_mailingu($id_mailing_szablony,$text,$id_placowki)
	{
		$a_placowka = mod_placowki::get_placowka($id_placowki);

		$tresc = str_ireplace('[imię]',$a_placowka['imie'],$text);
		$tresc = str_ireplace('[imie]',$a_placowka['imie'],$tresc);
		$tresc = str_ireplace('[nazwisko]',$a_placowka['nazwisko'],$tresc);
		$tresc = str_ireplace('[kod]',$a_placowka['kod_pocztowy'],$tresc);
		$tresc = str_ireplace('[kod_pocztowy]',$a_placowka['kod_pocztowy'],$tresc);
		$tresc = str_ireplace('[nazwa_placowki]',$a_placowka['nazwa'],$tresc);
		$tresc = str_ireplace('[nazwa]',$a_placowka['nazwa'],$tresc);
		$tresc = str_ireplace('[mail]',$a_placowka['email'],$tresc);
		$tresc = str_ireplace('[email]',$a_placowka['email'],$tresc);
		$tresc = str_ireplace('[miasto]',$a_placowka['poczta'],$tresc);
		$tresc = str_ireplace('[adres]',$a_placowka['adres'],$tresc);
		$tresc = str_ireplace('[wypisz]',"Jeśli nie chcesz otrzymywać newslettera kliknij ten <a href='".app::base_url()."mailing/wypisz/id/{$a_placowka['uniqid_users']}'>link</a>",$tresc);

		return $tresc;
	}

	public static function get_osoby_mailingu()
	{
		return db::get_many("SELECT placowki.nazwa,mailing_osoby.email, temat, data_wysylki, msg, id_mailing_osoby
							 FROM placowki
							 JOIN mailing_osoby ON placowki.id_placowki=mailing_osoby.id_placowki
							 JOIN mailing USING(id_mailing)
							 ORDER BY id_mailing_osoby DESC");
	}
	
}

?>