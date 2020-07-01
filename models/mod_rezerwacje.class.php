<?php

class mod_rezerwacje extends db
{
	public static function zapisz_rezerwacje($a_rezerwacja, $is_admin)
	{
		$hash = uniqid();
		db::insert('rezerwacje',array('day'=>$a_rezerwacja['day'],
									  'month'=>$a_rezerwacja['month'],
									  'year'=>$a_rezerwacja['year'],
									  'hour'=>$a_rezerwacja['hour'],
									  'imie'=>$a_rezerwacja['imie'],
									  'email'=>$a_rezerwacja['email'],
									  'telefon'=>$a_rezerwacja['numer'],
									  'room'=>$a_rezerwacja['room'],
									  'add_date'=>'NOW()',
									  'czy_potwierdzone'=>$is_admin ? 1 : 0,
									  'hash'=>$hash));
		return $hash;
	}
	
	public static function get_rezerwacje($data_od,$data_do)
	{
		if($data_od)
		{
			$a_data_od = explode('.',$data_od);
			$year_od = $a_data_od[2];
			$month_od = $a_data_od[1];
			$day_od = $a_data_od[0];
		}
		else
		{
			$year_od = date('Y');
			$month_od = date('m');
			$day_od = 1;
		}
		
		if($data_do)
		{
			$a_data_do = explode('.',$data_do);
			$year_do = $a_data_do[2];
			$month_do = $a_data_do[1];
			$day_do = $a_data_do[0];
		}
		else
		{
			$year_do = date('Y');
			$month_do = date('m');
			$day_do = date('t');
		}
		

		return db::get_many("SELECT * FROM rezerwacje WHERE (date(concat(year, '-', month, '-', day)) between '$year_od-$month_od-$day_od' and '$year_do-$month_do-$day_do') AND czy_potwierdzone=1 ORDER BY year,month,day,room,hour");
	
		//return db::get_many("SELECT * FROM rezerwacje WHERE day>=".(int)$day_od." AND month>=".(int)$month_od." AND year>=$year_od AND day<=".(int)$day_do." AND month<=".(int)$month_do." AND year<=$year_do AND czy_potwierdzone=1 ORDER BY year,month,day,room,hour");
	}
}

?>