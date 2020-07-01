<?php

class con_ratings extends controllers_parent{

	public static function add()
	{
		if(!hlp_validator::id($_POST['id']))
			view::json(false,'Nieprawidłowe id');

		if(!hlp_validator::id($_POST['rate']))
			view::json(false,'Nieprawidłowa ocena');

		if(!con_main::check_csrf_uniqkey($_POST['uniq_key']))
			view::json(false,'Próba ataku CSRF');
		
		if(!session::is_logged())
			view::json(false, lang::get('ocena-msg-zalogowani',1));
		
		$a_user = db::get_by_id('users',session::get_id());
		
		if($a_user['czy_zbanowany']==1)
			view::json(false, 'Jesteś zbanowany');
		
		$rating = mod_ratings::get_user_rating($_POST['id'],$_POST['type'],session::get_id());

		if($rating!==false)
			view::json(false,'Już to oceniłeś');
		
		if($_POST['type']=='works')
		{
			//$czy_bral_udzial_w_konkursie=db::get_one("SELECT COUNT(*) FROM works WHERE id_users=".session::get_id()." AND id_contests=(SELECT id_contests FROM works WHERE id_works={$_POST['id']})");
			//if($czy_bral_udzial_w_konkursie!=0)
			//	view::json(false,'Nie możesz ocenić pracy w konkursie, w którym sam bierzesz udział');
		
			$czy_to_jego = db::get_one("SELECT 1 FROM works WHERE id_works={$_POST['id']} AND id_users=".session::get_id());
			if($czy_to_jego==1)
				view::json(false,lang::get('ocena-nie-mozesz-ocenic-swojej',1)); 
		}

		mod_ratings::add($_POST['id'],$_POST['type'],$_POST['rate']);
		
		view::json(true,'Oceniono!');
		
		exit();
	}

}

	
?>