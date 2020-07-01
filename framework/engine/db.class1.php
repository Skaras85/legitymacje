<?php

class db{
	
	private static $host = 'localhost';
	private static $user = 'root';
	private static $pass;
	private static $dbname;
	private static $conn = false;

	//w dowolnym miejscu można ustawić na true, wtedy wyświetlą się wykonywane zapytania
	protected static $debug=false;
	
	public static function set_host($i_host){ self::$host=$i_host; }
	public static function set_user($i_user){ self::$user=$i_user; }
	public static function set_pass($i_pass){ self::$pass=$i_pass; }
	public static function set_db($i_dbname){ self::$dbname=$i_dbname; }
	public static function get_db_name(){ return self::$dbname; }

	public static function connect()
	{
		if(!self::$conn)
		{
			$conn = mysqli_connect(self::$host,self::$user,self::$pass,self::$dbname);
			
			if ($conn->connect_error) {
			    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
			}
			
			$conn->query("SET NAMES utf8");
			self::$conn=$conn;
		}
		
		return self::$conn;
	}

	public static function deb()
	{
		self::$debug=true;
	}
	
	public static function debug($data)
	{
		if(self::$debug==true)
		{
			echo "<pre style='font-size: 14px;'>".$data."</pre>";
			echo "<div style='font-size: 14px;'>".mysqli_error()."</div>";
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
	public static function get_one($sql)
	{
		$conn = self::connect();
		$dane=$conn->query($sql);
		self::debug($sql);

		if($dane->num_rows)
		{
			$a_wynik=$dane->fetch_row();
			return self::stripslashes_deep($a_wynik[0]);
		}

		return false;
	}

	/* zwraca tablicę asocjacyjną z pojedynczmy wierszem z tabeli 
	 * $i_sql - zapytanie SQL
	 * */
	public static function get_row($sql)
	{
		$conn = self::connect();
		
		$dane=$conn->query($sql);
		self::debug($sql);

		if($dane->num_rows)
			return self::stripslashes_deep($dane->fetch_assoc());

		return false;
	}
	
	/* zwraca wiersz z tabeli w postaci tablicy asocjacyjnej po wskazanym kluczu głównym 
	 * $i_table - tabela, z której chcemy pobrać dane
	 * $i_id - id rekordu, który chcemy mieć zwrócony
	 * */
	public static function get_by_id($table,$id,$fields='*')
	{
		if(!hlp_validator::id($id))
		{
			app::err('Nieprawidłowe id');
			return false;
		}
		
		
		$conn = self::connect();
		
		$sql="SELECT " . $fields . " FROM " . $table . " WHERE id_" . $table . "=" . $id;
		return self::get_row($sql);
	}
	
	/* zwraca wiele rekordów z tabeli w postaci tablicy asocjacyjnej
	 * $i_sql - zapytanie SQL
	 *  */
	public static function get_many($sql,$subpage=false,$per_page=false)
	{
		$conn = self::connect();

		if($subpage)
		{
			$offset=($subpage-1)*$per_page;
			$sql.=" LIMIT $offset,$per_page";
		}
		
		$dane=$conn->query($sql);
		self::debug($sql);

		if($dane->num_rows)
		{
			while($a_jeden=$dane->fetch_assoc())
				$a_wielu[]=$a_jeden;

			if($subpage!==false)
				self::add_data_pagination($subpage,$sql,$per_page,$conn);

			return self::stripslashes_deep($a_wielu);
		}

		return false;
	}
	
	/* zwraca wszystkie rekordy z tabeli w postaci tablicy asocjacyjnej
	 * $i_table - tabela, z której chcemy pobrać dane
	 * $i_fields (opcjonalne) - pola, ktore chcemy pobrac (domyslnie '*' - wszystkie)
	 * */
	public static function get_all($table,$order='',$fields='*',$subpage=false,$per_page=false)
	{
		$conn = self::connect();
		
		if($order!=='')
			$sql_order = " ORDER BY " . $order;
		else
			$sql_order = '';
		
		$query="SELECT ".$fields." FROM " . $table . $sql_order;
		
		return self::get_many($query,$subpage,$per_page);
	}
	
	/* wstawia rekord do tabelki 
	 * $i_tabela - nazwa tabeli, do której chcemy wstawić rekord
	 * $a_dane (tablica) - tablica o strukturze array('nazwa kolumny z tabeli'=>'wstawiana wartość')
	 * */
	public static function insert($tabela, array $a_dane)
	{
		$conn = self::connect();
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
		
		$kolumny=trim($kolumny,',');
		$wartosci=trim($wartosci,',');
		
		$query="INSERT INTO $tabela($kolumny) VALUES($wartosci)";
		
		$result=$conn->query($query);
		self::debug($query);

		if(!$result)
			return false;
		else
			return $conn->insert_id;
		
	}
	
	/* aktualizuje rekord tabelki 
	 * $i_tabela - nazwa tabeli, ktora chcemy aktualizowac
	 * $i_where (opcjonalny) - warunek, ktory rekord chcemy aktualizowac
	 * $a_dane (tablica) - tablica o strukturze array('nazwa kolumny z tabeli'=>'aktualizowana wartość')
	 * */
	public static function update($tabela, $where='', array $a_dane)
	{
		$conn = self::connect();
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
		$string=trim($string,',');

		if($where!='')
			$sql_where = "WHERE $where";
		else
			$sql_where = '';

		$query="UPDATE $tabela SET $string $sql_where";

		self::debug($query);
		$result=$conn->query($query);

		if(!$result)
			return false;
		else
			return $conn->affected_rows;
	}
	
	/* funkcja wykonuje zapytanie sql
	 * $i_sql - zapytanie do wykonania
	 * zwraca liczba zmienionych rekordow
	 */
	public static function query($sql,$subpage=false,$per_page=false)
	{
		$conn = self::connect();
		
		if($subpage)
		{
			$offset=($subpage-1)*$per_page;
			$sql.=" LIMIT $offset,$per_page";
		}
		
		self::debug($sql);
		$result=$conn->query($sql);
		
		if($subpage!==false)
			self::add_data_pagination($subpage,$sql,$per_page,$conn);
		
		if(!$result)
			return false;
		else
			return $conn->affected_rows;
	}
	
	public static function delete($table,$where)
	{
		$conn = self::connect();
		
		$sql="DELETE FROM $table WHERE $where";
		
		self::debug($sql);
		$result=$conn->query($sql);
		
		if(!$result)
			return false;
		else
			return $conn->affected_rows;
	}
	
	/*
	 * Dodaje do widoku subpage_number czyli ilosc stron paginacji, oraz
	 * current_subpage, czyli numer aktywnej podstrony
	 * $i_subpage - numer aktywnej podstrony
	 * $i_sql - zapytanie, które ma być poddane paginacji
	 * $i_per_page - ilosc wynikow na strone
	 * */
	public static function add_data_pagination($subpage,$sql,$per_page,$conn)
	{
		if(!hlp_validator::id($subpage))
			$subpage = 1;
		
		if(!hlp_validator::id($per_page))
			$per_page = 10;
		
		if($subpage)
		{
			if (preg_match('/ORDER/Usi',$sql,$a_wynik)){
			  		if (preg_match('/JOIN/Usi',$sql,$a_wynik))
				  		preg_match('/^.+ (FROM)*.+(FROM.+JOIN.+)ORDER.+$/si',$sql,$a_wynik);
					else
						preg_match('/^.+ (FROM)*.+(FROM.+)ORDER .+$/si',$sql,$a_wynik);
			  }else{
		
				  preg_match('/^.+ (FROM)*.+(FROM.+)$/is',$sql,$a_wynik);
			  }

			$result = $conn->query("SELECT COUNT(*) ".$a_wynik[2]);

			$a_subpage_number=0;
			
			if($result->num_rows)
				$a_subpage_number=($result->fetch_row());

			$number_of_pages = (int)ceil($a_subpage_number[0]/$per_page);

			view::add('number_of_pages',$number_of_pages);
			view::add('current_subpage',$subpage);
		}
	}
	
	public static function start_trans()
	{
		$conn = self::connect();
		$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
	}
	
	public static function commit_trans()
	{
		$conn = self::connect();
		$conn->commit();
	}

}

?>