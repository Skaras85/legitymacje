<?php

class mod_logi{

	public static function tworz_tabele($data)
	{
		db::query("CREATE TABLE  IF NOT EXISTS logi_{$data} (
					id_logi_{$data} INT(11) NOT NULL AUTO_INCREMENT,
					id_users INT(11) NOT NULL,
					akcja VARCHAR(255),
					id_obce INT(11) DEFAULT 0,
					id_placowki INT(11) DEFAULT 0,
					opis TEXT,
					data DATETIME,
					id_pracownika INT(11) DEFAULT 0,
					ip VARCHAR (15),
					PRIMARY KEY ( id_logi_{$data} )
					) ENGINE=InnoDB");
	}
	
	public static function dodaj($akcja, $id_obce=0, $opis='')
	{
		$data = date('Y_m');
		self::tworz_tabele($data);
		db::insert("logi_{$data}",array('id_users'=>session::is_logged() ? session::get_id() : 0,
										'akcja'=>$akcja,
										'id_obce'=>$id_obce,
										'opis'=>$opis,
										'data'=>'NOW()',
										'id_placowki'=>session::get('id_placowki') ? session::get('id_placowki') : 0,
										'id_pracownika'=>session::get('czy_zdalny') ? session::get('id_pracownika') : 0,
										'ip'=>hlp_functions::get_ip_address()));
	}
	
	public static function get_logi($id_placowki=false,$miesiac=false,$rok=false)
	{
		$miesiac = $miesiac ? $miesiac : date('m');
		$rok = $rok ? $rok : date('Y');
		$logi_table = 'logi_'.date("{$rok}_{$miesiac}");

		$sql_id_placowki = $id_placowki ? " AND id_placowki=$id_placowki" : '';
		return db::get_many("SELECT $logi_table.*, imie, nazwisko,
							(SELECT CONCAT(imie,' ',nazwisko) FROM users WHERE users.id_users=$logi_table.id_pracownika) as pracownik
							 FROM $logi_table LEFT JOIN users USING(id_users) WHERE 1=1 $sql_id_placowki ORDER BY data DESC");
	}

}

?>