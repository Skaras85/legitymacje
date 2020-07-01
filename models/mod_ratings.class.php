<?php

class mod_ratings extends db
{
	public static function get_user_rating($i_id,$i_type,$i_id_users)
	{
		return db::get_one("SELECT rating FROM ratings WHERE id_users=$i_id_users AND
							type='$i_type' AND subject_id=$i_id");
	}
	
	public static function add($i_id,$i_type,$i_raintg)
	{
		return db::insert('ratings',array('id_users'=>session::get_id(),
										  'type'=>$i_type,
										  'subject_id'=>$i_id,
										  'rating'=>$i_raintg,
										  'add_date'=>'NOW()'));
	}
	
	public static function get_rating($i_id,$i_type)
	{
		return db::get_row("SELECT COUNT(*) as votes, SUM(rating)/COUNT(*) as rating
							FROM ratings WHERE subject_id=$i_id AND type='$i_type'");
	}

}

?>