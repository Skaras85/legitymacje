<?php

class db{
	
	private static $host = 'localhost';
	private static $user = 'root';
	private static $pass;
	private static $dbname;

	//w dowolnym miejscu można ustawić na true, wtedy wyświetlą się wykonywane zapytania
	protected static $debug=false;
	
	public static function set_host($i_host){ self::$host=$i_host; }
	public static function set_user($i_user){ self::$user=$i_user; }
	public static function set_pass($i_pass){ self::$pass=$i_pass; }
	public static function set_db($i_dbname){ self::$dbname=$i_dbname; }
	public static function get_db_name(){ return self::$dbname; }

	public static function connect()
	{
		mysql_connect(self::$host,self::$user,self::$pass);
		mysql_select_db(self::$dbname);
		mysql_query("SET NAMES utf8mb4");
	}

	public static function deb()
	{
		self::$debug=true;
	}
	
	public static function debug($i_data)
	{
		if(self::$debug==true)
		{
			echo "<pre style='font-size: 14px;'>".$i_data."</pre>";
			echo "<div style='font-size: 14px;'>".mysql_error()."</div>";
		}
	}
	
	/* metody insert i update wszystkie dane przepuszczają przez add_slashes żeby pomóc zapobiegać
	 * atakom sql_injection. Dlatego wszystkie metody zwracające dane muszę je przepuścić przez
	 * funkcje, która pozbawia dane slashy
	 */
	public static function stripslashes_deep($variable)
	{
		if ( is_string( $variable ) )
			return (stripslashes( $variable )) ;
		if ( is_array( $variable ) )
			foreach( $variable as $i => $value )
				$variable[ $i ] = self::stripslashes_deep( $value ) ;
		
		return $variable ; 
	}
	
	/* zwraca pojedynczą wartość z tabeli
	 * $i_sql - zapytanie SQL
	 *  */
	public static function get_one($i_sql)
	{
		self::connect();
		
		$dane=mysql_query($i_sql);
		self::debug($i_sql);

		if($dane && mysql_num_rows($dane)>0)
		{
			$a_wynik=mysql_fetch_row($dane);
			return self::stripslashes_deep($a_wynik[0]);
		}

		return false;
	}

	/* zwraca tablicę asocjacyjną z pojedynczmy wierszem z tabeli 
	 * $i_sql - zapytanie SQL
	 * */
	public static function get_row($i_sql)
	{
		self::connect();
		
		$dane=mysql_query($i_sql);
		self::debug($i_sql);
		
		if(mysql_num_rows($dane)>0)
		{
			return self::stripslashes_deep(mysql_fetch_assoc($dane));
		}

		return false;
	}
	
	/* zwraca wiersz z tabeli w postaci tablicy asocjacyjnej po wskazanym kluczu głównym 
	 * $i_table - tabela, z której chcemy pobrać dane
	 * $i_id - id rekordu, który chcemy mieć zwrócony
	 * */
	public static function get_by_id($i_table,$i_id,$i_fields='*')
	{
		self::connect();
		
		if(!hlp_validator::id($i_id))
		{
			app::err('Nieprawidłowe id');
			return false;
		}
		
		$query="SELECT " . $i_fields . " FROM " . $i_table . " WHERE id_" . $i_table . "=" . $i_id;
		
		$dane=mysql_query($query);
		self::debug($query);
		
		if(mysql_num_rows($dane)>0)
		{
			return self::stripslashes_deep(mysql_fetch_assoc($dane));
		}

		return false;
	}
	
	/* zwraca wiele rekordów z tabeli w postaci tablicy asocjacyjnej
	 * $i_sql - zapytanie SQL
	 *  */
	public static function get_many($i_sql,$i_subpage=false,$i_per_page=false)
	{
		self::connect();

		if($i_subpage)
		{
			$offset=($i_subpage-1)*$i_per_page;
			$i_sql.=" LIMIT $offset,$i_per_page";
		}
		
		$dane=mysql_query($i_sql);
		self::debug($i_sql);

		if($i_subpage!==false)
			self::add_data_pagination($i_subpage,$i_sql,$i_per_page);

		if(mysql_num_rows($dane)>0)
		{
			while($a_jeden=mysql_fetch_assoc($dane))
				$a_wielu[]=$a_jeden;

			return self::stripslashes_deep($a_wielu);
		}

		return false;
	}
	
	/* zwraca wszystkie rekordy z tabeli w postaci tablicy asocjacyjnej
	 * $i_table - tabela, z której chcemy pobrać dane
	 * $i_fields (opcjonalne) - pola, ktore chcemy pobrac (domyslnie '*' - wszystkie)
	 * */
	public static function get_all($i_table,$i_order='',$i_fields='*',$i_subpage=false,$i_per_page=false)
	{
		self::connect();
		
		if($i_order!=='')
			$sql_order = " ORDER BY " . $i_order;
		else
			$sql_order = '';
		
		$query="SELECT ".$i_fields." FROM " . $i_table . $sql_order;
		
		if($i_subpage)
		{
			$offset=($i_subpage-1)*$i_per_page;
			$query.=" LIMIT $offset,$i_per_page";
		}
		
		$dane=mysql_query($query);
		self::debug($query);
		
		if(mysql_num_rows($dane)>0)
		{
			while($a_jeden=mysql_fetch_assoc($dane))
				$a_wielu[]=$a_jeden;
			
			if($i_subpage!==false)
				self::add_data_pagination($i_subpage,$query,$i_per_page);
			
			return self::stripslashes_deep($a_wielu);
		}

		return false;
	}
	
	/* wstawia rekord do tabelki 
	 * $i_tabela - nazwa tabeli, do której chcemy wstawić rekord
	 * $a_dane (tablica) - tablica o strukturze array('nazwa kolumny z tabeli'=>'wstawiana wartość')
	 * */
	public static function insert($i_tabela, array $a_dane)
	{
		self::connect();
		$kolumny='';
		$wartosci='';
		
		foreach($a_dane as $kolumna=>$wartosc)
		{
			$kolumny.="$kolumna,";
			
			if(is_numeric($wartosc) or strpos(strtolower($wartosc),'now()')!==false)
				$wartosci.=addslashes($wartosc).',';
			else
				$wartosci.="'".addslashes($wartosc)."',";
		}
		
		$kolumny=substr($kolumny,0,strlen($kolumny)-1);
		$wartosci=substr($wartosci,0,strlen($wartosci)-1);
		
		$query="INSERT INTO $i_tabela($kolumny) VALUES($wartosci)";
		
		mysql_query($query);
		self::debug($query);
		file_put_contents('log_insert.txt', session::get_id().'|'.$query.'|'.date('H:i:s')."\r\n",FILE_APPEND);
		
		if(mysql_affected_rows()==-1)
			return false;
		else
			return mysql_insert_id();
		
	}
	
	/* aktualizuje rekord tabelki 
	 * $i_tabela - nazwa tabeli, ktora chcemy aktualizowac
	 * $i_where (opcjonalny) - warunek, ktory rekord chcemy aktualizowac
	 * $a_dane (tablica) - tablica o strukturze array('nazwa kolumny z tabeli'=>'aktualizowana wartość')
	 * */
	public static function update($i_tabela, $i_where='', array $a_dane)
	{
		self::connect();
		$string='';
		
		foreach($a_dane as $kolumna=>$wartosc)
		{
			$string.="$kolumna=";
			
			if(is_numeric($wartosc) or strpos(strtolower($wartosc),'now()')!==false)
				$string.=addslashes($wartosc);
			else
				$string.="'".addslashes($wartosc)."'";
			
			$string.=",";
		}
		$string=substr($string, 0, strlen($string)-1);

		if($i_where!='')
			$sql_where = "WHERE $i_where";
		else
			$sql_where = '';

		$query="UPDATE $i_tabela SET $string $sql_where";

		self::debug($query);
		mysql_query($query);

		if(mysql_affected_rows()==-1)
			return false;
		else
			return mysql_affected_rows();
	}
	
	/* funkcja wykonuje zapytanie sql
	 * $i_sql - zapytanie do wykonania
	 * zwraca liczba zmienionych rekordow
	 */
	public static function query($i_sql,$i_subpage=false,$i_per_page=false)
	{
		self::connect();
		
		if($i_subpage)
		{
			$offset=($i_subpage-1)*$i_per_page;
			$i_sql.=" LIMIT $offset,$i_per_page";
		}
		
		self::debug($i_sql);
		mysql_query($i_sql);
		
		if($i_subpage!==false)
			self::add_data_pagination($i_subpage,$i_sql,$i_per_page);
		
		if(mysql_affected_rows()==-1)
			return false;
		else
			return mysql_affected_rows();
	}
	
	public static function delete($i_table,$i_where)
	{
		self::connect();
		
		$sql="DELETE FROM $i_table WHERE $i_where";
		
		self::debug($sql);
		mysql_query($sql);
		
		if(mysql_affected_rows()==-1)
			return false;
		else
			return mysql_affected_rows();
	}
	
	/*
	 * Dodaje do widoku subpage_number czyli ilosc stron paginacji, oraz
	 * current_subpage, czyli numer aktywnej podstrony
	 * $i_subpage - numer aktywnej podstrony
	 * $i_sql - zapytanie, które ma być poddane paginacji
	 * $i_per_page - ilosc wynikow na strone
	 * */
	public static function add_data_pagination($i_subpage,$i_sql,$i_per_page)
	{
		if(!hlp_validator::id($i_subpage))
			$i_subpage = 1;
		
		if(!hlp_validator::id($i_per_page))
			$i_per_page = 10;
		
		if($i_subpage)
		{
			/*$a_subpage_sql_limit=explode('LIMIT',$i_sql);
			$a_subpage_sql_from=explode('FROM',$a_subpage_sql_limit[0]);
			$result = mysql_query("SELECT COUNT(*) FROM ".$a_subpage_sql_from[1]);*/
			
			if (preg_match('/ORDER/Usi',$i_sql,$a_wynik)){
			  		if (preg_match('/JOIN/Usi',$i_sql,$a_wynik))
				  		preg_match('/^.+ (FROM)*.+(FROM.+JOIN.+)ORDER.+$/si',$i_sql,$a_wynik);
					else
						preg_match('/^.+ (FROM)*.+(FROM.+)ORDER .+$/si',$i_sql,$a_wynik);
			  }else{
		
				  preg_match('/^.+ (FROM)*.+(FROM.+)$/is',$i_sql,$a_wynik);
			  }

			$result = mysql_query("SELECT COUNT(*) ".$a_wynik[2]);

			$a_subpage_number=0;
			
			if($result)
				$a_subpage_number=(mysql_fetch_row($result));

			$number_of_pages = (int)ceil($a_subpage_number[0]/$i_per_page);

			view::add('number_of_pages',$number_of_pages);
			view::add('current_subpage',$i_subpage);
		}
	}

	

}

?>