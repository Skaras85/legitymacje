<?php

class con_comments extends controllers_parent{

	public static function add__lg()
	{
		$a_user = db::get_by_id('users',session::get_id());
		
		if($a_user['czy_zbanowany']==1)
			view::json(false,'Jesteś zbanowany');
		
		$id_comments = mod_comments::add($_POST['a_comment']);
		$a_comment = array(mod_comments::get_comment($id_comments));

		
		if(app::get_result())
		{
			$msg = view::get_message();
			if($_POST['a_comment']['type']=='works')
			{
				$a_praca = db::get_by_id('works',$_POST['a_comment']['subject_id']);
				$a_work_owner = db::get_by_id('users',$a_praca['id_users']);
				
				//jezeli piszacy koma nie jest wlascicielem pracy
				if(session::get_id()!=$a_work_owner['id_users'])
				{
					mailer::add_address($a_work_owner['email']);
					mailer::add_address('oiskaras@wp.pl');
					$tresc = db::get_one("SELECT text from sites WHERE id_sites=37");
					
					$tresc = str_replace('{{$link}}',"<a href='".app::base_url()."konkursy/projekt/numer/{$a_praca['uniqid_works']}#commentsWrapper' style='color: #1dabd4;text-decoration: none'>".app::base_url()."konkursy/projekt/numer/{$a_praca['uniqid_works']}",$tresc);
	
					$stopka = db::get_one("SELECT text FROM sites WHERE id_sites=32");
					$tresc = '<!DOCTYPE HTML><html><head><meta charset="utf-8"></head>'.$tresc.$stopka.'</html>';
					
					$temat = "Nowy komentarz do Twojej pracy";
					$mail = mailer::send($temat,$tresc,false,true);
				}
			}
			elseif($_POST['a_comment']['type']=='sites')
			{
				$a_art = db::get_row("SELECT sludge,title,id_sites FROM sites WHERE id_sites=".$_POST['a_comment']['subject_id']);
				mailer::add_address('oiskaras@wp.pl');
				$tresc = "<!DOCTYPE HTML><html><head><meta charset='utf-8'></head><a href='".app::base_url()."sites/{$a_art['sludge']},{$a_art['id_sites']}'>{$a_art['title']}</a></html>";
				//mailer::send('nowy komentarz na blogu',$tresc,false,true);
			}
			elseif($_POST['a_comment']['type']=='contests')
			{
				mailer::add_address('oiskaras@wp.pl');
				$tresc = db::get_one("SELECT text from sites WHERE id_sites=37");

				$id_konkursu = db::get_one("SELECT uniqid_contests FROM contests WHERE id_contests={$_POST['a_comment']['subject_id']}");
				
				$tresc = str_replace('{{$link}}',"<a href='".app::base_url()."konkursy/asd,$id_konkursu#komentarze-konkursu' style='color: #1dabd4;text-decoration: none'>".app::base_url()."konkursy/asd,$id_konkursu#komentarze-konkursu",$tresc);

				$stopka = db::get_one("SELECT text FROM sites WHERE id_sites=32");
				$tresc = '<!DOCTYPE HTML><html><head><meta charset="utf-8"></head>'.$tresc.$stopka.'</html>';
				
				$temat = "Nowy komentarz do konkursu";
				$mail = mailer::send($temat,$tresc,false,true);
			}
			
			view::json(app::get_result(),$msg,array('id_comments'=>$a_comment[0]['id_comments'],
								   				  'comments_string'=>mod_comments::get_comments_string($a_comment)));
		
		}
		else
			view::json(app::get_result(),view::get_message());
	}
	
	public static function usun_komentarz__lg()
	{
		if(!isset($_POST['uniq_key']) || !con_main::check_csrf_uniqkey($_POST['uniq_key']))
			echo (string)false;
		
		$a_comment = mod_comments::get_comment($_POST['comment_id']);
		
		if(session::who('admin') || $a_comment['id_users']==session::get_id())
			echo (string)db::query("DELETE FROM comments WHERE id_comments=".$_POST['comment_id']);
	}
	
	public static function edytuj_komentarz__lg()
	{
		if(!isset($_POST['uniq_key']) || !con_main::check_csrf_uniqkey($_POST['uniq_key']))
			echo (string)false;
		
		$a_comment = mod_comments::get_comment($_POST['comment_id']);
		
		if(session::who('admin') || $a_comment['id_users']==session::get_id())
			echo (string)db::update('comments','id_comments='.$_POST['comment_id'],array('text'=>$_POST['text']));
	}

	public static function get_comment_ajax()
	{
		$a_comment = mod_comments::get_comment($_POST['id']);
		
		if($_POST['mode']=='bbcode')
			$a_comment['text']=mod_comments::parse_bb_code($a_comment['text']);
		
		view::json(true, '', $a_comment);
	}
	
	public static function get_comments()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']))
			return false;
		
		$a_comments = db::get_many("SELECT chat.*,users.nazwa FROM chat JOIN users USING(id_users) WHERE id_knajpy=".$_POST['id']." ORDER BY data_dodania DESC");

		if(session::get_id()==7)
			db::update('chat',"id_knajpy={$_POST['id']} AND lukasz=1",array('lukasz'=>0));
		else
			db::update('chat',"id_knajpy={$_POST['id']} AND konrad=1",array('konrad'=>0));
		
		foreach($a_comments as $index=>$a_comment)
		{
			$a_comments[$index]['data_dodania'] = hlp_functions::date_ago($a_comment['data_dodania']);
		}
		
		view::json(true, '', array('a_comments'=>$a_comments));
	}
	
	public static function save()
	{
		if(empty($_POST['id']) || !hlp_validator::id($_POST['id']) || empty($_POST['tresc']))
			return false;
		
		$id = db::insert('chat',array('id_users'=>session::get_id(),'id_knajpy'=>$_POST['id'], 'data_dodania'=>'NOW()', 'tresc'=>$_POST['tresc'], 'konrad'=>session::get_id()==1 ? 1 : 0, 'lukasz'=>session::get_id()==128 ? 1 : 0));
		
		$a_comment = db::get_row("SELECT chat.*,users.nazwa FROM chat JOIN users USING(id_users) WHERE id_chat=".$id);
		$a_comment['data_dodania'] = hlp_functions::date_ago($a_comment['data_dodania']);
		view::json(true, '', array('a_comment'=>$a_comment));
	}
}

	
?>