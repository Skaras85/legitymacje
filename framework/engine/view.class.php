<?php

include('framework/libs/Smarty-3.1.13/libs/Smarty.class.php');

class view{

	public static $msg = '';					//komunikat ustawiany przy wielu metodach
	private static $o_smarty;
  	private static $templates = 'view';
  	private static $templates_c = 'framework/temp_data/templates_c';
	
	public static function smarty()
	{
	    if (self::$o_smarty == null)
	    {
	      self::$o_smarty = new Smarty();
	      self::$o_smarty->setTemplateDir(self::$templates);
	      self::$o_smarty->setCompileDir(self::$templates_c);
		  //self::$o_smarty->caching = 1;
	    }
	
	    return self::$o_smarty;
	}  
	  	
	public static function message($i_msg=false)
	{
		if($i_msg)
			self::$msg = $i_msg;

		self::display('system_view/komunikat.tpl');
		exit();
	}
	
	public static function get_message()
	{
		if(isset($_SESSION['app']['msg']) && $_SESSION['app']['msg']!='')
			return $_SESSION['app']['msg'];
		else
			return self::$msg;
	}
	
	public static function add($i_name,$i_value)
	{
		self::smarty()->assign($i_name,$i_value);
	}
	
	public static function json($result,$comm,array $data=array())
	{
		$a_result = array('result'=>$result,'comm'=>$comm);
		$a_result = array_merge($a_result,$data);
		echo json_encode($a_result);
		exit();
	}
	
	/*public static function pagination($subpage_number,$current_subpage)
	{
		//include('framework/utils/pagination.php');
	}*/

	public static function redirect($i_url)
	{
		header('Location: '.app::base_url().$i_url,true,301);
	}
	
	/*
	 * $i_raw_content - jeżli chcemy otrzymać samą treść podstrony
	 */
	public static function display($i_filename='',$i_raw_content=false,$return=false)
	{
		if($i_filename=='')
		{
			//jezeli w metodzie display nie ma sciezki do pliku
			//wtedy program bierze nazwa klasy ktora display wywolala
			//lamane na nazwa metody.php
			try{
				throw new Exception();  
			}catch(Exception $e){
				$a_data=$e->getTrace(); 
				$class=str_replace('con_', '', $a_data[1]['class']);
				$function=explode('__',$a_data[1]['function']);
				
				$i_filename=$class.'/'.$function[0].'.tpl';
			}
		}

		view::add('_subpage', $i_filename);
		//self::smarty()->display('index.tpl');
		
		con_main::get_settings();
		
		if(!$i_raw_content)
			$site = self::smarty()->fetch('index.tpl');
		else
			$site = self::smarty()->fetch('index_clear.tpl');
		
		$a_wzorzec[0] = '/href="([a-z0-9\._\/@%&,;\?\-=]*)"/i';
    	$a_wzorzec[1] = '/src="([a-zA-Z0-9\._\/@%&,;\?\-=]*)"/i';
    	$a_wzorzec[2] = '/action="([a-zA-Z0-9\._\/@%&,;\?\-=]*)"/i';

		$a_podmianka[0] = 'href="' . app::base_url() . '$1"';
    	$a_podmianka[1] = 'src="' . app::base_url() . '$1"';
    	$a_podmianka[2] = 'action="' . app::base_url() . '$1"';

		if(!$return)
			echo preg_replace($a_wzorzec,$a_podmianka,$site);
		
		if(empty($_SESSION['app']['save_result']))
			app::unset_result();
		session::delete('form');
		unset($_SESSION['app']['save_result']);
		
		if($return)
			return preg_replace($a_wzorzec,$a_podmianka,$site);;
		exit();
	}
	
}

?>