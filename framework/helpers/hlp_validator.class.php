<?php

class hlp_validator{
	
	public static function between($i_dane,$i_min,$i_max)
	{
		if(strlen($i_dane)<$i_min || strlen($i_dane)>$i_max)
			return false;
		else
			return true;
	}

	public static function alfanum($i_dane)
	{
		if(preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\._,-\/]/ui',$i_dane))
			return false;
		else
			return true;
	}

	public static function alfanum_strict($i_dane)
	{
		if(preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\._-]/ui',$i_dane))
			return false;
		else
			return true;
	}
	
	public static function alfanum_hc($i_dane)
	{
		if(preg_match('/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄ0-9]/ui',$i_dane))
			return false;
		else
			return true;
	}
	
	public static function url($i_dane)
	{
		if(preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i',$i_dane))
			return true;
		else
			return false;
	}
	
	//Funkcja pobrana z serwisu PHPedia i leciutko zmodyfikowana o komunikaty
  	public static function numer_konta_bankowego($i_nr)
  	{
	    // Usuniecie spacji
		$iNRB = str_replace(' ', '', $i_nr);
	
	 	// Sprawdzenie czy przekazany numer zawiera 26 znaków
	 	if(strlen($iNRB) != 26)
	 	{
	 		app::err("Numer konta bankowego powinien zawierać 26 znaków.");
			return false;
		}
	 	// Zdefiniowanie tablicy z wagami poszczególnych cyfr                
	 	$aWagiCyfr = array(1, 10, 3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53,
	                    45, 62, 38, 89, 17, 73, 51, 25, 56, 75, 71, 31, 19, 93, 57);
	
	 	// Dodanie kodu kraju (w tym przypadku dodajemu kod PL)        
	 	$iNRB = $iNRB.'2521';
	 	$iNRB = substr($iNRB, 2).substr($iNRB, 0, 2); 

	 	// Wyzerowanie zmiennej
	 	$iSumaCyfr = 0;
	
	 	// Pętla obliczająca sumę cyfr w numerze konta
	 	for($i = 0; $i < 30; $i++) 
	   		$iSumaCyfr += $iNRB[29-$i] * $aWagiCyfr[$i];
	
	 	// Sprawdzenie czy modulo z sumy wag poszczegolnych cyfr jest rowne 1
		if ($iSumaCyfr % 97 !== 1)
		{
	      	app::err("Numer konta bankowego jest nieprawidłowy.");
			return false;
	    }
	    return true;
  	}

	public static function kod_pocztowy($i_napis)
	{
	    if (!preg_match('/^\d\d-\d\d\d$/',$i_napis))
	    {
	     	app::err('Kod pocztowy powinien być w formacie: nn-nnn . Np. 01-222 .');
	    	return false;
	    }

    	return true;
  	}
	
	public static function email($email,$iv_sprawdz_domene=true)
	{
    	if(preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/" , $email))
    	{/*
      		if ($iv_sprawdz_domene===true)
      		{
      			list($username,$domain)=explode('@',$email); 
        		if(!checkdnsrr($domain,'MX'))
        		{
          			app::err('Podana domena nie istnieje.');
          			return false;
        		}
     		}*/
	  		app::ok();
      		return true;
    	}
    	app::err('Format adresu email jest niepoprawny.');
    	return false;
  	}

	public static function id($i_dane)
	{
		if(preg_match("/[^0-9]/",$i_dane))
			return false;
		if($i_dane<1)
			return false;
		return true;
	}
	
	public static function numer($i_dane)
	{
		if(preg_match("/[^0-9]/",$i_dane))
			return false;

		return true;
	}
	
	public static function price($i_dane)
	{
		if(preg_match("/^[0-9]+[\.,]?[0-9]{0,2}$/",$i_dane))
			return true;
		else
			return false;
    }
	
	public static function coords($i_dane)
	{
		if(preg_match("/^([0-9.-]+).+?([0-9.-]+)$/",$i_dane))
			return true;
		else
			return false;
    }

	public static function extension($filename, array $ia_extensions)
	{
		$found = 0;
		foreach($ia_extensions as $ext)
		{
			$length = strlen(trim($ext));
			$file_ext = substr($filename, -$length, $length);

			if($file_ext==$ext)
				$found++;
		}
		
		if(!$found)
		{
			app::err("Nieprawidłowy typ pliku. Dopuszczalne: ".implode(', ', $ia_extensions));
			return false;
		}
		return true;
	}
	
	public static function data($i_data)
	{
	    if(!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d)$/',$i_data,$a_dopasowania))
	    {
	      	app::err('Data powinna być w formacie: rrrr-mm-dd.');
	      	return false;
	    }  
	    
	    if (!checkdate($a_dopasowania[2],$a_dopasowania[3],$a_dopasowania[1]))
	    {
	      	app::err('Podano nieprawidłową datę.');
	      	return false;
	    }
		return true;
	}
	
	public static function czas($czas)
	{
		return preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $czas);
	}
	
	public static function nip($pNip)
	{
		if(!empty($pNip)) {
            $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
            $nip = preg_replace('/[\s-]/', '', $pNip);
            if (strlen($nip) == 10 && is_numeric($nip)) {	 
                $sum = 0;
                for($i = 0; $i < 9; $i++)
                    $sum += $nip[$i] * $weights[$i];
                return ($sum % 11) == $nip[9];
            }
		}
        return false;
    }
	
	public static function regon($str)
	{
		$str=str_replace(' ', '', $str);
		if (strlen($str) != 9)
			return false;
	 
		$arrSteps = array(8, 9, 2, 3, 4, 5, 6, 7);
		$intSum=0;
		for ($i = 0; $i < 8; $i++)
		{
			$intSum += $arrSteps[$i] * $str[$i];
		}
		$int = $intSum % 11;
		$intControlNr=($int == 10)?0:$int;
		if ($intControlNr == $str[8]) 
		{
			return true;
		}
		return false;
	}
	
	//pierwsza data musi byc mlodsza od drugiej
	  public static function porownaj_daty($i_pierwsza,$i_druga)
	  {
	    if (!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d) (\d\d):(\d\d)$/',$i_pierwsza,$a_dopasowania1)){
	      app::err("Nieprawidłowa godzina (parametr 1)");
	      return false;
	    }
	    
	    if (!preg_match('/^(\d\d\d\d)-(\d\d)-(\d\d) (\d\d):(\d\d)$/',$i_druga,$a_dopasowania2)){
	      app::err("Nieprawidłowa godzina (parametr 2)");
	      return false;
	    }
	    $v_czas1 = mktime($a_dopasowania1[4],$a_dopasowania1[5],0,$a_dopasowania1[2],$a_dopasowania1[3],$a_dopasowania1[1]);
	    $v_czas2 = mktime($a_dopasowania2[4],$a_dopasowania2[5],0,$a_dopasowania2[2],$a_dopasowania2[3],$a_dopasowania2[1]);
	    if ($v_czas1 < $v_czas2){
	      return true;
	    }else{
	      return false;
	    }  
	    
	  }
	  
	  public function txt_time_left($end, $separator)
	{
		$left = $end - time();
		$days = floor($left/86400);
		
		$left_2 = $left-($days*86400);
		$hours = floor($left_2/3600);
		
		$left_3 = $left_2-($hours*3600);
		$minutes = floor($left_3/60);
		
		$left_4 = $left_3-($minutes*60);
		$seconds = $left_4;
		
		$str = '';
		$count = 1;
		
		if($days > 0 and $count < 3)
		{
			$str .= $days;
			if($days == 1) $str .= ' dzień'.$separator;
			else $str .= ' dni'.$separator;
			
			$count++;
		}
		if($hours > 0 and $count < 3)
		{
			$str .= $hours;
			if($hours == 1) $str .= ' godzina'.$separator;
			else if($hours == 2 or $hours == 3 or $hours == 4
			or substr($hours, 0, -1) == 2 or substr($hours, 0, -1) == 3 or substr($hours, 0, -1) == 4) $str .= ' godziny'.$separator;
			else $str .= ' godzin'.$separator;
			
			$count++;
		}
		if($minutes > 0 and $count < 3)
		{
			$str .= $minutes;
			if($minutes == 1) $str .= ' minuta'.$separator;
			else if($minutes == 2 or $minutes == 3 or $minutes == 4
			or substr($minutes, 0, -1) == 2 or substr($minutes, 0, -1) == 3 or substr($minutes, 0, -1) == 4) $str .= ' minuty'.$separator;
			else $str .= ' minut'.$separator;
			
			$count++;
		}
		if($seconds > 0 and $count < 3)
		{
			$str .= $seconds;
			if($seconds == 1) $str .= ' sekunda'.$separator;
			else if($seconds == 2 or $seconds == 3 or $seconds == 4
			or substr($seconds, 0, -1) == 2 or substr($seconds, 0, -1) == 3 or substr($seconds, 0, -1) == 4) $str .= ' sekundy'.$separator;
			else $str .= ' sekund'.$separator;
			
			$count++;
		}
		
		return substr($str, 0, -strlen($separator));
	}

	public function txt_time_diff($time, $separator = ' temu')
	{
		$diff = time() - $time;
		$days = floor($diff/86400);
		
		$diff_2 = $diff-($days*86400);
		$hours = floor($diff_2/3600);
		
		$diff_3 = $diff_2-($hours*3600);
		$minutes = floor($diff_3/60);
		
		$diff_4 = $diff_3-($minutes*60);
		$seconds = $diff_4;
		
		$str = '';
		$count = 0;
		
		if($days > 0 and $count < 1)
		{
			$str .= $days;
			if($days == 1) $str .= ' dzień'.$separator;
			else $str .= ' dni'.$separator;
			
			$count++;
		}
		if($hours > 0 and $count < 1)
		{
			$str .= $hours;
			if($hours == 1) $str .= ' godzinę'.$separator;
			else if($hours == 2 or $hours == 3 or $hours == 4
			or substr($hours, 0, -1) == 2 or substr($hours, 0, -1) == 3 or substr($hours, 0, -1) == 4) $str .= ' godziny'.$separator;
			else $str .= ' godzin'.$separator;
			
			$count++;
		}
		if($minutes > 0 and $count < 1)
		{
			$str .= $minutes;
			if($minutes == 1) $str .= ' minutę'.$separator;
			else if($minutes == 2 or $minutes == 3 or $minutes == 4
			or substr($minutes, 0, -1) == 2 or substr($minutes, 0, -1) == 3 or substr($minutes, 0, -1) == 4) $str .= ' minuty'.$separator;
			else $str .= ' minut'.$separator;
			
			$count++;
		}
		if($seconds > 0 and $count < 1)
		{
			$str .= $seconds;
			if($seconds == 1) $str .= ' sekundę'.$separator;
			else if($seconds == 2 or $seconds == 3 or $seconds == 4
			or substr($seconds, 0, -1) == 2 or substr($seconds, 0, -1) == 3 or substr($seconds, 0, -1) == 4) $str .= ' sekundy'.$separator;
			else $str .= ' sekund'.$separator;
			
			$count++;
		}
		
		return $str;
	}

}

?>