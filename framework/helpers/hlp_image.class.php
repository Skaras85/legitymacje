<?php

class hlp_image{
	
	//funkcja usuwa wskazany katalog wraz z zawartością
	//dir - katalog
	//rmdir - jeśli true prócz zawartości usuwany jest sam katalog
	 public static function rrmdir($dir,$rmdir=true)
	 {
		 if (is_dir($dir)) 
		 {
		 	$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		
		if($rmdir)
			rmdir($dir);
		}
	}
	
	public static function get_extension($i_file)
	{
		$ext = strtolower(substr($i_file, -4, 4));
		
		if($ext=='.jpg' or $ext=='.jpeg' or $ext=='.gif' or $ext=='.png' or $ext=='.pdf')
		{
			if($ext=='jpeg')
				$ext='.'.$ext;
			
			return $ext;
		}
		else
			return false;
	}
	
	/*
	 * funkcja zapisuje plik na dysku
	 * $ia_image - plik z tablicy plików np. $_FILES['zdjecie']
	 * $save_path - sciezka, gdzie ma zostac zapisany/nazwa pliku (bez rozszerzenia)
	 * funkcja zwraca false lub rozszerzenie pliku
	 */
	public static function save(array $ia_image,$save_path)
	{
		//sprawdzamy rozszerzenie pliku i tworzymy obrazek tymczasowy ze sciezki dostepu
  		//podanej jako parametr funkcji
  		$ext = self::get_extension($ia_image['name']);
  
		if(!$ext)
		{
		  	app::err("Nieprawidłowy typ pliku. Dopuszczalne to jpg, jpeg, png, gif");
			return false;
		}

		if(!move_uploaded_file($ia_image['tmp_name'],$save_path.$ext))
		{
			app::err("Błąd zapisu zdjęcia");
			return false;
		}
		
		return $ext;
	}

	/*
	funkcja przeskalowuje obrazek wejciowy
	parametry:
	$orignin_image - skalowany plik z tablicy plików np. $_FILES['zdjecie'], albo sciezka dostepu do pliku na serwerze
	$savepath - cieżka zapisu miniaturki/nazwa pliku (bez rozszerzenia)
	$width_new - (opcjonalny - mozemy wpisac 0) nowa szerokosc. Jesli nie podamy zostanie proporcjonalnie dopasowana do nowej wysokosci
	$height_new - (opcjonalny - nie musimy podawac) nowa wysokosc. Jesli nie podamy zostanie proporcjonalnie dopasowana do nowej szerokosci
	(ponizsze rzeczy jeszcze nie dzialaja zbyt dobrze)
	$x - jesli ustawione to miniaturka bedzie zaczynala sie od tego punktu na osi x oryginalu 
	$y - jesli ustawione to miniaturka bedzie zaczynala sie od tego punktu na osi y oryginalu 
	$w
	$z
	*/
	public static function save_resized($orignin_image,$savepath,$width_new=0,$height_new=0,$x=0,$y=0,$w=0,$h=0)
	{
		//sprawdzamy rozszerzenie pliku i tworzymy obrazek tymczasowy ze sciezki dostepu
  		//podanej jako parametr funkcji
  		
  		if(is_array($orignin_image))
		{
  			$ext = self::get_extension($orignin_image['name']);
			$origin_url=$orignin_image['tmp_name'];
  		}
		else
		{
			$ext = self::get_extension($orignin_image);
			$origin_url=$orignin_image;
		}
		
		if(!$ext)
		{
		  	app::err("Nieprawidłowy typ pliku. Dopuszczalne to jpg, jpeg, png, gif");
			return false;
		}
		
		if($ext=='.jpg' or $ext=='.jpeg')
			$image = imagecreatefromjpeg($origin_url);
  		elseif($ext=='.png')
			$image = imagecreatefrompng($origin_url); 
  		elseif($ext=='.gif')
			$image = imagecreatefromgif($origin_url);
		
 		//pobranie pierwotnych wymiarow obrazka
		$height_old = imagesy($image);
		$width_old = imagesx($image);
  
  		//jezeli nie podalismy ani nowej wysokosci, ani szerokosci to zostaja one bez zmian
  		if($width_new===0 && $height_new===0)
  		{
			$width_new = $width_old;
			$height_new = $height_old;
		}
  
  		//jesli ustawilismy szerokosc na 0 to ma ja dostosowac proporcjonalnie do wysokosci
 		if($width_new===0)
  		{
			$skalar = $height_old / $height_new;
			$width_new = floor($width_old / $skalar);
  		}
  
  		//jesli ustawilismy wysokosci na 0 to ma ja dostosowac proporcjonalnie do szerokosci
  		if($height_new===0)
  		{
			$skalar = $width_old / $width_new;
			$height_new = floor($height_old / $skalar);
  		}

  		//stworzenie pustego plotna na nowy obrazek o wielkosci podanej w parametrach wejsciowych
  		$obraz_zmiana_wielkosci = imagecreatetruecolor($width_new,$height_new); 
		
		if($ext=='.png' || $ext=='.gif')
		{
			$background = @imagecolorallocate($obraz_zmiana_wielkosci, 0, 0, 0);
            @imagecolortransparent($obraz_zmiana_wielkosci, $background);
            @imagealphablending($obraz_zmiana_wielkosci, false);
            @imagesavealpha($obraz_zmiana_wielkosci, true);
		}
  
  		//skopiowanie na wczesniej stworzone plotna przeskalowanego obrazka
  		imagecopyresampled($obraz_zmiana_wielkosci, $image, 0, 0, $x, $y, $width_new,$height_new,$width_old,$height_old);
  
  		if(!self::get_extension($savepath))
			$savepath=$savepath.$ext;
  
  		//zapis naszego pliku do katalogu podanej w parametrze funkcji
  		if($ext=='.png')
			$result=imagepng($obraz_zmiana_wielkosci, $savepath);
  		if($ext=='.jpg' or $ext=='.jpeg')
			$result=imagejpeg($obraz_zmiana_wielkosci, $savepath);
		if($ext=='.gif')
			$result=imagegif($obraz_zmiana_wielkosci, $savepath);

		if(!$result)
		{
			app::err("Błąd zapisu miniaturki");
			return false;
		}
		
		return true;
	}

	public static function delete($url,$ext='*')
	{
		if(file_exists($url) && !is_dir($url))
			unlink($url);
		else
		{	
			$a_files = glob($url.'.'.$ext,GLOB_BRACE);

			foreach($a_files as $file)
				@unlink($file);
		}
		return true;
	}
	
	//funkcja dla każdego elementu tablicy wczytuje zdjęcie i tworzy dla tego elementu
	//nowy klucz 'img' o wartosci zawierajacej url do wczytanego obrazka
	//funkcja nic nie zwraca, natomiast fizycznie zmienia tablice, ktora byla jej argumentem
	//array &$ia_tab - tablica (jedno, badz wielowymiarowa) wczytywana przez referencje
	//	zawierajaca element/y dla ktorych chcemy wczytac zdjecia (pliki)
	//$i_url - url do katalogu, w ktorym znajduja sie zdjecia
	//$i_id_fieldname - nazwa pola w tablicy, w ktorej jest klucz glowny
	//$i_files - pliki, ktore chcemy wczytac, domyslnie wszystkie
	//	jeżeli w parametsze podamy pusty string to wtedy w parametrze $i_id_fieldname
	//	można podać dokładną nazwę pliku z rozszerzeniem
	//$i_img_key - nazwa klucza po którym obrazki zostaną załadowane do wejściowej tablicy
	public static function get_files_by_id(&$ia_tab,$i_url,$i_id_fieldname,$i_files="*",$i_img_key='img')
	{
		if(is_array($ia_tab))
		{
			if($i_files!='')
				$i_files='.'.$i_files;
				
			foreach($ia_tab as $key=>$value)
			{
				if(is_array($value))
				{
					$a_img = glob($i_url."/".$ia_tab[$key][$i_id_fieldname].$i_files,GLOB_BRACE);
					if(count($a_img))
						$ia_tab[$key][$i_img_key] = app::base_url().$a_img[0];
				}
				else
				{
					$a_img = glob($i_url."/".$ia_tab[$i_id_fieldname].$i_files,GLOB_BRACE);
					if(count($a_img))
						$ia_tab[$i_img_key] = app::base_url().$a_img[0];
				}
			}
		}
	}
	
}

?>