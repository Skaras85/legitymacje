<?php

class cache{
	
	public static $cache=false;
	public static $class;
	public static $func;
	
	/**
	 * funkcja odpalana w view::display jezeli flaga cache=true. Tworzy ona plik w odpowiednim 
	 * katalogu, kazde nastepne uruchomienie cachowanej funkcji bedzie odpalalo zapisany przez 
	 * nie plik
	 * i_class - klasa cachowanej funkcji
	 * i_func - cachowana funkcja
	 * ia_data (opcjonalne) - tablica z danymi jakie bedziemy cachowac do pliku
	 * i_id (opcjonalne) - jezeli cachowana funkcja wyciaga cos po id to tutaj je podajemy 
	 * (bo na podstawie czegos trzeba stworzyc nazwe pliku - czyms sie one musza roznic,
	 * np. pokaz_usera.php?id=3 stworzy plik pokaz_usera/3.php)
	 */
	public static function make_file($i_class, $i_func, array $ia_data=NULL, $i_id=0)
	{
		if(is_array($ia_data))
		{
			foreach($ia_data as $key=>$value)
				$$key = $value;
		}

		ob_start();
			
		require('view/'.$i_class.'/'.$i_func.'.php');
		$out=ob_get_contents();
		
		ob_end_clean();
		
		if(!file_exists('cache/'.$i_class))
			mkdir('cache/'.$i_class, 0777);

		if($i_id!=0)
		{
			if(!file_exists('cache/'.$i_class.'/'.$i_func))
				mkdir('cache/'.$i_class.'/'.$i_func,0777);
			$cache_filename='cache/'.$i_class.'/'.$i_func.'/'.$i_id.'.php';
		}
		else 
			$cache_filename='cache/'.$i_class.'/'.$i_func.'.php';
		
		
		$title='{title:'.head::$title.'}';
		$description='{description:'.head::$description.'}';
		$keywords='{keywords:'.head::$keywords.'}';
		file_put_contents($cache_filename, $title.$description.$keywords.$out);
	}
	
	public static function get_file($i_filename)
	{
		$plik = file_get_contents($i_filename);
		
		if(preg_match('/{title:(.*)}{description:(.*)}{keywords:(.*)}(.*)/s',$plik,$a_saved))
		{
			$a_result['title']=$a_saved[1];
			$a_result['description']=$a_saved[2];
			$a_result['keywords']=$a_saved[3];
			$a_result['file']=$a_saved[4];
			
			return $a_result;
		}

		return false;
	}
	
	/**
	 * odpalana w funkcji ktora chcemy cachowac. Jezeli funkcja jest juz schashowana to zostaje
	 * odczytana z pliku. Jesli nie odpalamy flage cachowania przez, co view::display odpali
	 * funkcje make_file robiaca cache do pliku
	 * $i_time - czas po którym ma scachowany plik wygasnąć (w godzinach!)
	 */
	public static function run($i_class, $i_func, $i_id=false, $i_time=0)
	{
		if($i_id!=false && !hlp_validator::id($i_id))
		{
			app::err('Nieprawidłowe id');
			return false;
		}
		
		$a_func=explode('__',$i_func);
		$i_func=$a_func[0];
		
		if($i_id!=false)
			$filename='cache/'.$i_class.'/'.$i_func.'/'.$i_id.'.php';
		else
			$filename='cache/'.$i_class.'/'.$i_func.'.php';

		if(file_exists($filename))
		{
			if(filemtime($filename)+$i_time*3600>time() or $i_time==0)
			{
				$a_meta=self::get_file($filename);
				head::set_title($a_meta['title']);
				head::set_description($a_meta['description']);
				head::set_keywords($a_meta['keywords']);

				view::display('','cache/',$a_meta['file']);
			}
			else
				self::uncache($i_class, $i_func, $i_id);		
		}

		//wlaczamy cachowanie jezeli poprzedni kod nie wykryl istnienia scachowanego pliku
		//wtedy funkcja view::display() odpali tworzenie pliku
		self::$cache=true;
		self::$class=$i_class;
		self::$func=$i_func;
	}
	
	public static function uncache($i_class, $i_func, $i_id=false)
	{
		if($i_id!=false)
			$filename='cache/'.$i_class.'/'.$i_func.'/'.$i_id.'.php';
		else
			$filename='cache/'.$i_class.'/'.$i_func.'php';	
		
		if(file_exists($filename))
			unlink($filename);
	}
}


?>