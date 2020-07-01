<?php

class con_logi extends controllers_parent{
	
	public static $default_action = 'lista';
	
	public static function lista()
	{
		$miesiac = empty($_GET['miesiac']) || !hlp_validator::id($_GET['miesiac']) ? date('m') : $_GET['miesiac'];
		$rok = empty($_GET['rok']) || !hlp_validator::id($_GET['rok']) ? date('Y') : $_GET['rok'];
		
		view::add('wybrany_miesiac', $miesiac);
		view::add('wybrany_rok', $rok);
		view::add('rok', date('Y'));
		view::add('a_logi', mod_logi::get_logi(session::get('id_placowki'),$miesiac,$rok));
		view::display();
	}
}

?>