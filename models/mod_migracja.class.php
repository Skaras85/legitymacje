<?php

class mod_migracja extends db{
	
	//private static $encryption_type = 'bcrypt';	
	private static $encryption_type = 'MD5';
	
	public static function czy_migracja($ia_user)
	{
		if(!hlp_validator::id($ia_user['email']))
		{
			app::err('nieprawidłowe ID');
			return false;
		}

		//sprawdzamy, czy to nie jest konto starego typu
		return db::get_one("SELECT 1 FROM old_placowki WHERE id_placowki=".$ia_user['email']." 
							AND haslo='".MD5($ia_user['haslo'])."'");
	}
	
	public static function get_dane_placowki($ia_user=false,$id_users_old=false)
	{
		if($ia_user)
			return db::get_row("SELECT * FROM old_placowki WHERE id_placowki=".$ia_user['email']." 
								AND haslo='".MD5($ia_user['haslo'])."'");
		else
			return db::get_row("SELECT * FROM old_placowki WHERE id_placowki=$id_users_old");
	}
	
	public static function get_pracodawcy($id_placowki)
	{
		return db::get_many("SELECT * FROM old_pracodawcy WHERE id_placowki=$id_placowki");
	}
	
	public static function get_nauczyciele($id_placowki)
	{
		return db::get_many("SELECT old_nauczyciele.*,old_pracodawcy.linia1 FROM old_nauczyciele LEFT JOIN old_pracodawcy USING(id_pracodawcy) WHERE old_nauczyciele.id_placowki=$id_placowki AND old_nauczyciele.status='aktywny'");
	}
	
	public static function get_nauczyciel($id_nauczyciela)
	{
		return db::get_many("SELECT old_nauczyciele.*,old_pracodawcy.linia1 FROM old_nauczyciele LEFT JOIN old_pracodawcy USING(id_pracodawcy) WHERE old_nauczyciele.id_nauczyciela=$id_nauczyciela AND old_nauczyciele.status='aktywny'");
	}
	
	public static function sprawdz_czy_migracja_juz_byla($id_placowki)
	{
		return db::get_one("SELECT 1 FROM placowki WHERE id_placowki=$id_placowki");
	}

	public static function zapisz_legitymacje($a_legitymacje,$id_placowki,$id_pracodawcy=false)
	{
		foreach($a_legitymacje as $id_nauczyciela=>$asd)
		{
			$a_nauczyciel_old = db::get_row("SELECT * FROM old_nauczyciele WHERE id_nauczyciela=$id_nauczyciela");
			
			if(!db::get_one("SELECT 1 FROM legitymacje WHERE id_legitymacje=$id_nauczyciela"))
			{
				$id_legitymacji = db::insert('legitymacje',array('id_legitymacje'=>$id_nauczyciela,
											   'id_karty'=>1,
											   'id_placowki'=>$id_placowki,
											   'kol1'=>$a_nauczyciel_old['imie1'],
											   'kol2'=>$a_nauczyciel_old['imie2'],
											   'kol3'=>$a_nauczyciel_old['nazwisko1'],
											   'kol4'=>$a_nauczyciel_old['nazwisko2'],
											   'kol5'=>$a_nauczyciel_old['umowa_na_czas']=='nieokreslony' ? '' : substr($a_nauczyciel_old['data_wygasniecia_umowy'],0,10),
											   'kol6'=>$id_pracodawcy ? $id_pracodawcy : $a_nauczyciel_old['id_pracodawcy']
											   ));
				mod_logi::dodaj('migracja dodano osobę', $id_legitymacji);
			}
											   
		   if(file_exists('images/migracja/'.$a_nauczyciel_old['id_nauczyciela'].'.jpg'))
		  	 copy('images/migracja/'.$a_nauczyciel_old['id_nauczyciela'].'.jpg',"images/placowki/$id_placowki/{$id_legitymacji}.jpg");
		   
		   if(file_exists('images/migracja/Kopia '.$a_nauczyciel_old['id_nauczyciela'].'.jpg'))
		   	 copy('images/migracja/Kopia '.$a_nauczyciel_old['id_nauczyciela'].'.jpg',"images/placowki/$id_placowki/Kopia {$id_legitymacji}.jpg");
		}
	}
	
	public static function zapisz_pracodawce($ia_pracodawca,$id_users,$id_placowki,$id_pracodawcy)
	{

		$a_dane = array("id_pracodawcy"=>$id_pracodawcy,
						"nazwa"=>$ia_pracodawca['dane1'],
						"dane1"=>$ia_pracodawca['dane1'],
						 "dane2"=>$ia_pracodawca['dane2'],
						 "dane3"=>$ia_pracodawca['dane3'],
						 "dane4"=>$ia_pracodawca['dane4'],
						 "id_users"=>$id_users,
						 "id_placowki"=>$id_placowki
						 );
						 
		return self::insert("pracodawcy",$a_dane);
	}

}

?>