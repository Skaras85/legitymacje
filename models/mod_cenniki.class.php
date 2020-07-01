<?php

class mod_cenniki extends db
{
	public static function get_cenniki($id_placowki=false)
	{
		$sql_id_placowki = $id_placowki!==false ? " WHERE id_placowki=$id_placowki" : '';
		return db::get_many("SELECT * FROM cenniki $sql_id_placowki");
	}

	public static function get_cennik($id_cenniki)
	{
		return db::get_row("SELECT * FROM cenniki WHERE id_cenniki=$id_cenniki");
	}
	
	public static function zapisz_cennik($ia_cennik)
	{
		$_SESSION['form']['a_cennik']=$ia_cennik;

		if(empty($ia_cennik['nazwa']))
			return app::err('Musisz wpisać nazwę');
		
		$a_dane = array("nazwa"=>$ia_cennik['nazwa'],'id_placowki'=>isset($ia_cennik['czy_placowki']) ? session::get('id_placowki') : 0);

		if(empty($ia_cennik['id_cenniki']))
			$id_cenniki=self::insert("cenniki",$a_dane);
		else
			self::update("cenniki","id_cenniki=".$ia_cennik['id_cenniki'],$a_dane);

		$id_cenniki = empty($ia_cennik['id_cenniki']) ? $id_cenniki : $ia_cennik['id_cenniki'];

		app::ok("Cennik zapisany");
		unset($_SESSION['form']['a_cennik']);
		return $id_cenniki;
	}
	
	public static function usun_przedzialy_cenowe($id_cenniki)
	{
		db::delete('cenniki_przedzialy_cenowe','id_cenniki='.$id_cenniki);
	}
	
	public static function zapisz_przedzialy_cenowe($a_przedzialy,$id_cenniki)
	{
		foreach($a_przedzialy as $a_przedzial)
		{
			if($a_przedzial['od'] && $a_przedzial['do'])
				db::insert('cenniki_przedzialy_cenowe',array('od'=>$a_przedzial['od'],
															 'do'=>$a_przedzial['do'],
															 'cena'=>str_replace(',','.',$a_przedzial['cena']),
															 'id_cenniki'=>$id_cenniki));
		}
	}
	
	public static function zwroc_przedzialy_cenowe($id_cenniki)
	{
		return db::get_many("SELECT * FROM cenniki_przedzialy_cenowe WHERE id_cenniki=$id_cenniki");
	}

}

?>