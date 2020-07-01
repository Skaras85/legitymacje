<?php

class session{
	
	//nazwa tablicy w sesji przechowującej dane o zalogowanym userze, można dowolnie sobie zmienić
	public static $tab='a_user';	
	//nazwa kolumny w bazie danych w tabeli users odpowiedzialnej za rodzaj uzytkownika (np. user, admin itd.)
	public static $type='rodzaj';	
	
	public static function set($i_field,$i_value)
	{
		$_SESSION[$i_field]=$i_value;
	}
	
	public static function get($i_field)
	{
		if(isset($_SESSION[$i_field]))
			return $_SESSION[$i_field];
		else
			return false;
	}
	
	public static function delete($i_field)
	{
		unset($_SESSION[$i_field]);
	}
	
	public static function get_id()
	{
		if(!empty($_SESSION[self::$tab]['id_users']))
			return $_SESSION[self::$tab]['id_users'];
		else
			return false;
	}
	
	/*zwraca rodzaj zalogowanego usera*/
	public static function get_user_type()
	{
		if(self::is_logged())
			return self::get_user(self::$type);
		else
			return false;
	}
	
	/* wraca wartość wskazanego pola z sesji usera
	 * $i_field - pole z sesji, którego wartość chcemy poznać
	 * */
	public static function get_user($i_field)
	{
		if(self::is_logged() and isset($_SESSION[self::$tab][$i_field]))
			return $_SESSION[self::$tab][$i_field];
		else
			return false;
	}
	
	/*zwraca prawdę, gdy user jest zalogowany, fałsz jeśli nie jest*/
	public static function is_logged()
	{
		if(isset($_SESSION[self::$tab]['is_logged']))
			return true;
		else
			return false;
	}
	
	/* blokuje usera i wyświetla komunikat jeśli nie jest zalogowany
	 * $i_kom (opcjonalny) - komunikat jaki ma się userowi wyświetlić
	 * */
	public static function block_unlog($i_kom='Musisz być zalogowany, aby oglądać tą stronę')
	{
		if(!self::is_logged())
		{
			app::err($i_kom);
			view::message();
		}
		return true;
	}
	
	/* blokuje usera i wyświetla komunikat jeśli jest zalogowany
	 * przydatne np. gdy user jest zalogowany, a próbuje wejść na formularz logowania, czy rejestracji
	 * $i_kom - komunikat jaki ma się userowi wyświetlić
	 * */
	public static function block_logged($i_kom)
	{
		if(self::is_logged())
		{
			app::err($i_kom);
			view::message();
		}
		return true;
	}
	
	/* zwraca prawdę jeśli zalogowany user jest podanego w paramtrze typu
	 * $i_field - sprawdzany typ usera (np. user, admin, mod itd.)
	 * */
	public static function who($i_field)
	{
		if(self::is_logged() and self::get_user_type()==$i_field)
			return true;
		else
			return false;
	}
	
	/* jeżeli user nie jest adminem to zostaje wyświetlony komunikat
	 * $i_kom - komunikat jaki ma się userowi wyświetlić
	 * */
	public static function block_admin($i_kom='Nie masz uprawnień, aby oglądać tą stronę')
	{
		if(!self::is_logged() or !self::who('admin'))
		{
			app::err($i_kom);
			view::message();
		}
	}
	
	public static function save_last_active_date()
	{
		db::query("UPDATE users SET data_ost_ruchu=NOW() WHERE id_users=".self::get_user('id_users') );
	}
	
	public static function check_last_active_date()
	{
		$id_users=db::get_one("SELECT data_ost_ruchu FROM users WHERE id_users=".self::get_user('id_users'));

		if(strtotime(date('Y-m-d H:i:s'))-strtotime($id_users)>10)
			return false;
		else
			return true;
	}
	
	public static function logout()
	{
		unset($_SESSION[session::$tab]);
		unset($_COOKIE['framework_uniqid']);
		setcookie("framework_uniqid", '',time()-3600);
		session_destroy();
	}
	
}

?>