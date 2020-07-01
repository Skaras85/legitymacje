<?php

class hlp_form{
	
	private $a_form = '<form action="index.php">';
		
	public function set_form(array $ia_fields)
	{
		foreach($ia_fields as $a_field)
		{
			if($a_field['type']=='text')
				$this->a_form .= $this->make_text_field($a_field);
		}
		
		$this->a_form .= '</form>';
		
		return $this;
	}
 
 	private function make_text_field($ia_field)
	{
		$string = "<label for='{$ia_field['name']}'>{$ia_field['label']}</label>\r\n
				<input type='text' name='{$ia_field['name']}' id='{$ia_field['name']}' 
				class='".$this->get_validators($ia_field['validators'])."'";
				
				if(isset($ia_field['val']))
					$string.=" value='{$ia_field['val']}'";
				
		$string .= "'>\r\n";
		return $string;
	}
	
	private function get_validators(array $ia_validators)
	{
		return implode(',',$ia_validators);
	}
	
	public function render()
	{
		$_SESSION['app']['o_form'] = $this;
		echo $this->a_form;
	}
		
}

?>
	