<?php

class hlp_functions{
	
	public static function get_ip_address() {
		$ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
		foreach ($ip_keys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					// trim for safety measures
					$ip = trim($ip);
					// attempt to validate IP
					if (self::validate_ip($ip)) {
						return $ip;
					}
				}
			}
		}

		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
	}

	public static function validate_ip($ip)
	{
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
			return false;
		}
		return true;
	}
	
	public static function make_sludge($text)
	{
		//$text=strtolower($text);
	    $text=preg_replace('/\./','',$text);   
	    $text=preg_replace('/ę/','e',$text);
	    $text=preg_replace('/ó/','o',$text);
	    $text=preg_replace('/ą/','a',$text);
	    $text=preg_replace('/ś/','s',$text);
	    $text=preg_replace('/ł/','l',$text);
	    $text=preg_replace('/ż/','z',$text);
	    $text=preg_replace('/ź/','z',$text);
	    $text=preg_replace('/ć/','c',$text);
	    $text=preg_replace('/ń/','n',$text);
		
		$text=preg_replace('/Ę/','E',$text);
	    $text=preg_replace('/Ó/','O',$text);
	    $text=preg_replace('/Ą/','A',$text);
	    $text=preg_replace('/Ś/','S',$text);
	    $text=preg_replace('/Ł/','L',$text);
	    $text=preg_replace('/Ż/','Z',$text);
	    $text=preg_replace('/Ź/','Z',$text);
	    $text=preg_replace('/Ć/','C',$text);
	    $text=preg_replace('/Ń/','N',$text);
	    $text=preg_replace('/[^a-zA-Z0-9\.:_\-\s]/','',$text);
	    $text=preg_replace('/\s/','-',$text);
		
		return $text;
	}
	
		/**
	 * Formatuje date do formatu: Przedwczoraj[, 13:15]; Ponad tydzień temu[, 2013-06-21]
	 *
	 * @version 1.0
	 * @author Piotr tlenex Hebda {@link http://tlenex.pl}
	 * @copyright NIE usuwaj tego komentarza! (Do NOT remove this comment!)
	 *
	 * @param string   $data_wejsciowa  zalecana data w formacie ISO
	 */
	public static function date_ago($data_wejsciowa, $show_date = true)
	{
		$okres = strtotime($data_wejsciowa);
		$now = time();
		$ending_date = "";
		$ending_time = "";
		if ($okres > time()) {
			return "przed chwilą";
		}
		
		if ($show_date) {
			$ending_date = '';//" (".date("Y-m-d", $okres) . ")";
			$ending_time = " o ".date("H:i", $okres);
		}
	
		$diff = $now - $okres;
	
		$minut = floor($diff/60);
		$godzin = floor($minut/60);
		$dni = floor($godzin/24);     
		$miesiecy = intval((date('Y', $now) - date('Y', $okres))*12 + (date('m', $now) - date('m', $okres)));
		$lata = intval(date('Y', $now) - date('Y', $okres));
	
		if ($minut <= 60) {
			switch($minut) {
				case 0: return "przed chwilą";
				case 1: return "minutę temu";
				case ($minut >= 2 && $minut <= 4):
				case ($minut >= 22 && $minut <= 24):
				case ($minut >= 32 && $minut <= 34):
				case ($minut >= 42 && $minut <= 44): 
				case ($minut >= 52 && $minut <= 54): return "$minut minuty temu"; break;
				default: return "$minut minut temu"; break;
			}     
		}
	
		$okres_wczoraj = $now-(60*60*24);
		$okres_przedwczoraj = $now-(60*60*24*2);
	
		if ($godzin > 0 && $godzin <= 6) {
	
			if ($godzin == 1) {
				return "godzinę temu ";
			} else {
				if ($godzin >1 && $godzin<5) return "$godzin godziny temu";
				if ($godzin >4)return "$godzin godzin temu";
			}
	
		} else if (date("Y-m-d", $okres) == date("Y-m-d", $now)) {
			return "dzisiaj".$ending_time;
		} else if (date("Y-m-d", $okres_wczoraj) == date("Y-m-d", $okres)) {
			return "wczoraj".$ending_time;
		} else if (date("Y-m-d", $okres_przedwczoraj) == date("Y-m-d", $okres)) {
			return "przedwczoraj".$ending_time;
		}
		
		if ($dni > 0 && $dni <= intval(date('t', $okres))) {
			switch($dni) {
				case ($dni < 7): return "$dni dni temu".$ending_date; break;
				case 7: return "tydzień temu".$ending_date; break;
				case ($dni > 7 && $dni < 14): return "Ponad tydzień temu".$ending_date; break;
				case 14: return "dwa tygodznie temu".$ending_date; break;
				case ($dni > 14 && $dni < 21): return "Ponad 2 tygodnie temu".$ending_date; break;
				case 21: return "3 tygodnie temu, ".date("Y-m-d", $okres); break;
				case ($dni > 21 && $dni < 28): return "ponad 3 tygodnie temu".$ending_date; break;
				case ($dni >= 28 && $dni < 32): return "miesiąc temu"; break;       
			}
		}
		  
		
		if ($miesiecy > 0 && $miesiecy <= 12) {
			switch($miesiecy) {
				case 1: return "miesiąc temu".$ending_date; break;
				case 2: case 4: return "$miesiecy miesiące temu".$ending_date; break;
				case 3: return "ćwierć roku temu".$ending_date; break;
				case 6: return "pół roku temu".$ending_date; break;
				case 12: return "rok temu".$ending_date; break;
				default: return "$miesiecy miesiecy temu".$ending_date; break;
			}
		}
		
		if ($lata > 0) {
			$lata1 = array("2", "3", "4");
			$lata2 = array("0", "1", "5", "6", "7", "8", "9", "12", "13", "14");
			if ($lata == 1) {
				return "rok temu".$ending_date;
			} else if (in_array(substr("".$lata, -1), $lata2) || in_array(substr("".$lata, -2, 2), $lata2)) {
				return "$lata lat temu".$ending_date;
			} else if (in_array(substr((string)$lata, -1), $lata1)) {
				return "$lata lata temu".$ending_date;
			}
		}
	
		return date("Y-m-d", $okres); 
	}

	public static function get_uniq_id($length=5)
	{
		$uniq_id=rand(1,9);
		for($i=0;$i<$length;$i++)
			$uniq_id.=rand(0,9);
		return $uniq_id;
	}
	
	private static $slowa = Array(
	  'minus',
	
	  Array(
	    'zero',
	    'jeden',
	    'dwa',
	    'trzy',
	    'cztery',
	    'pięć',
	    'sześć',
	    'siedem',
	    'osiem',
	    'dziewięć'),
	
	  Array(
	    'dziesięć',
	    'jedenaście',
	    'dwanaście',
	    'trzynaście',
	    'czternaście',
	    'piętnaście',
	    'szesnaście',
	    'siedemnaście',
	    'osiemnaście',
	    'dziewiętnaście'),
	
	  Array(
	    'dziesięć',
	    'dwadzieścia',
	    'trzydzieści',
	    'czterdzieści',
	    'pięćdziesiąt',
	    'sześćdziesiąt',
	    'siedemdziesiąt',
	    'osiemdziesiąt',
	    'dziewięćdziesiąt'),
	
	  Array(
	    'sto',
	    'dwieście',
	    'trzysta',
	    'czterysta',
	    'pięćset',
	    'sześćset',
	    'siedemset',
	    'osiemset',
	    'dziewięćset'),
	
	  Array(
	    'tysiąc',
	    'tysiące',
	    'tysięcy'),
	
	  Array(
	    'milion',
	    'miliony',
	    'milionów'),
	
	  Array(
	    'miliard',
	    'miliardy',
	    'miliardów'),
	
	  Array(
	    'bilion',
	    'biliony',
	    'bilionów'),
	
	  Array(
	    'biliard',
	    'biliardy',
	    'biliardów'),
	
	  Array(
	    'trylion',
	    'tryliony',
	    'trylionów'),
	
	  Array(
	    'tryliard',
	    'tryliardy',
	    'tryliardów'),
	
	  Array(
	    'kwadrylion',
	    'kwadryliony',
	    'kwadrylionów'),
	
	  Array(
	    'kwintylion',
	    'kwintyliony',
	    'kwintylionów'),
	
	  Array(
	    'sekstylion',
	    'sekstyliony',
	    'sekstylionów'),
	
	  Array(
	    'septylion',
	    'septyliony',
	    'septylionów'),
	
	  Array(
	    'oktylion',
	    'oktyliony',
	    'oktylionów'),
	
	  Array(
	    'nonylion',
	    'nonyliony',
	    'nonylionów'),
	
	  Array(
	    'decylion',
	    'decyliony',
	    'decylionów')
	);
	
	public static function odmiana($odmiany, $int){ // $odmiany = Array('jeden','dwa','pięć')
	  $txt = $odmiany[2];
	  if ($int == 1) $txt = $odmiany[0];
	  $jednosci = (int) substr($int,-1);
	  $reszta = $int % 100;
	  if (($jednosci > 1 && $jednosci < 5) &! ($reszta > 10 && $reszta < 20))
	    $txt = $odmiany[1];
	  return $txt;
	}
	
	public static function liczba($int){ // odmiana dla liczb < 1000
	  $slowa = self::$slowa;
	  $wynik = '';
	  $j = abs((int) $int);
	
	  if ($j == 0) return $slowa[1][0];
	  $jednosci = $j % 10;
	  $dziesiatki = ($j % 100 - $jednosci) / 10;
	  $setki = ($j - $dziesiatki*10 - $jednosci) / 100;
	
	  if ($setki > 0) $wynik .= $slowa[4][$setki-1].' ';
	
	  if ($dziesiatki > 0)
	        if ($dziesiatki == 1) $wynik .= $slowa[2][$jednosci].' ';
	  else
	    $wynik .= $slowa[3][$dziesiatki-1].' ';
	
	  if ($jednosci > 0 && $dziesiatki != 1) $wynik .= $slowa[1][$jednosci].' ';
	  return $wynik;
	}
	
	public static function slownie($int){
	
	  $slowa = self::$slowa;
	
	  $in = preg_replace('/[^-\d]+/','',$int);
	  $out = '';
	
	  if ($in{0} == '-'){
	    $in = substr($in, 1);
	    $out = $slowa[0].' ';
	  }
	
	  $txt = str_split(strrev($in), 3);
	
	  if ($in == 0) $out = $slowa[1][0].' ';
	
	  for ($i = count($txt) - 1; $i >= 0; $i--){
	    $liczba = (int) strrev($txt[$i]);
	    if ($liczba > 0)
	      if ($i == 0)
	        $out .= self::liczba($liczba).' ';
	          else
	        $out .= ($liczba > 1 ? self::liczba($liczba).' ' : '')
	          .self::odmiana( $slowa[4 + $i], $liczba).' ';
	  }
	  return trim($out);
	}
	
	public static function kwotaslownie($kwota){
	  $kwota = explode('.', $kwota);
	 
	  $zl = preg_replace('/[^-\d]+/','', $kwota[0]);
	  $gr = preg_replace('/[^\d]+/','', substr(isset($kwota[1]) ? $kwota[1] : 0, 0, 2));
	  while(strlen($gr) < 2) $gr .= '0';
	
	  echo self::slownie($zl) . ' ' . self::odmiana(Array('złoty', 'złote', 'złotych'), $zl) .
	      (intval($gr) == 0 ? '' :
	      ' ' . self::slownie($gr) . ' ' . self::odmiana(Array('grosz', 'grosze', 'groszy'), $gr));
	}
	
	public static function to_price($iv_cena)
	{
		if(strpos($iv_cena,'.') || strpos($iv_cena,','))
		{
			$iv_cena=str_replace('.', ',', $iv_cena);
			$a_cena=explode(',',$iv_cena);
			if(strlen($a_cena[1])==1)
				return $iv_cena.'0';
			else
				return $a_cena[0].','.substr($a_cena[1],0,2);
		}
		else
			return $iv_cena.',00';
	}

}

?>