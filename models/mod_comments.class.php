<?php

class mod_comments extends db{
	
	public static function check_comment_data($ia_comment)
	{
		$i_msg=lang::get('komentarz-nie-dodano',1).'<br>';
		
		if(!in_array($ia_comment['type'],array('works','sites','contests')))
		{
			app::err($i_msg."Nieprawidłowy typ.");
			return false;
		}
		
		if(!hlp_validator::id($ia_comment['subject_id']))
		{
			app::err($i_msg."Nieprawidłowe id podmiotu.");
			return false;
		}
		
		if($ia_comment['text']=='')
		{
			app::err($i_msg.lang::get('komentarz-msg-musisz-wpisac-tresc',1));
			return false;
		}

		return true;
	}
	
	public static function add($ia_comment)
	{
		if(!self::check_comment_data($ia_comment))
			return false;
		
		$id = db::insert('comments', array('text'=>$ia_comment['text'],
									 'id_users'=>session::get_id(),
									 'type'=>$ia_comment['type'],
									 'subject_id'=>$ia_comment['subject_id'],
									 'add_date'=>'NOW()'));
		
		if($id===false)
		{
			app::err('Nie dodano komentarza!<br>Błąd bazy danych');
			return false;
		}
		
		app::ok(lang::get('komentarz-msg-dodany',1));
		return $id;
	}
	
	public static function get_comments($i_type,$i_id,$i_comments_site=1,$i_quantity=10)
	{
		if(!hlp_validator::id($i_id))
		{
			app::err('Błąd wczytywania komentarzy');
			return false;
		}

		$a_comments = db::get_many("SELECT comments.*,users.login,users.uniqid_users FROM comments 
									JOIN users USING(id_users)
									WHERE type='$i_type' AND subject_id=$i_id
									ORDER BY add_date DESC",$i_comments_site,$i_quantity);
	
		return $a_comments;
	}
	public static function parse_bb_code($i_text)
	{
		require_once 'framework/helpers/Bbcode/BbCode.php';
		$parser = new BbCode();
		return $parser->parse($i_text);
	}
	
	public static function get_comments_string($ia_comments)
	{
		$html = '';

		if($ia_comments)
		{
			foreach($ia_comments as $a_comment)
			{
				$avatar = mod_users::get_avatar($a_comment['uniqid_users'], 'small');
				$html.="<article id='comment_{$a_comment['id_comments']}'>
	                	<img src='".app::base_url().$avatar."' alt='' class='commentsAvatar'>
	                    <div class='commentData'>
	                    <a href='".app::base_url()."users/{$a_comment['login']},{$a_comment['uniqid_users']}' class='commentAuthor'>{$a_comment['login']}</a>
	                    <time class='commentsTime' title='{$a_comment['add_date']}' datetime='{$a_comment['add_date']}'>".lang::get('komentarz-dodany',1).': ';
	            $html.=lang::get_lang()=='PL' ? hlp_functions::date_ago($a_comment['add_date']) : $a_comment['add_date'];
	            $html.="</time></div><div class='commentContent'>".self::parse_bb_code($a_comment['text'])."</div><div class='commentOptions'>";
	                if($a_comment['id_users']==session::get_id() || session::who('admin'))
					{
	                	$html.="<a href='#{$a_comment['id_comments']}' class='edytujKomentarz' data-comment-id='{$a_comment['id_comments']}'>".lang::get('komentarz-edytuj',1)."</a> 
	                       		<a href='#{$a_comment['id_comments']}' class='czyUsunacKomentarz' data-comment-id='{$a_comment['id_comments']}'>".lang::get('komentarz-usun',1)."</a> ";
					}
	                if(session::is_logged())
	                	$html.="<a href='#{$a_comment['id_comments']}' class='cytujKomentarz' data-comment-id='{$a_comment['id_comments']}'>".lang::get('komentarz-cytuj',1)."</a>";
	                $html.="</div>
	            </article>";
			}
		}
		return $html;
	}
	
	public static function get_number_of_comments($i_type,$i_id)
	{
		return db::get_one("SELECT COUNT(*) FROM comments WHERE type='$i_type' AND subject_id=$i_id");
	}
	
	public static function get_comment($i_id_comment)
	{
		if(!hlp_validator::id($i_id_comment))
		{
			app::err('Nieprawidłowy numer komentarza');
			return false;
		}

		return db::get_row("SELECT comments.*,users.login,users.uniqid_users FROM comments 
									JOIN users USING(id_users)
									WHERE id_comments=$i_id_comment");
	}
	
	
}

?>