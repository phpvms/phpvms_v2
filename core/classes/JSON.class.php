<?php


class JSON
{

	public $fromjson;

	
	public function ToJSON($exp, $name='records')
	{
		$ret='{"'.$name.'":';
		
		if($this)
			$ret.=$this->RecordToJSON($exp);
		else
			$ret.=self::RecordToJSON($exp);
			
		$ret.='}';
		
		return $ret;
	}
	
	/*
		Based on code from http://www.bin-co.com/php/scripts/array2json/
	*/
	public function RecordToJSON($records)
	{
		$exp = '';
		$parts = array();
	    $is_list = false;

	    //Find out if the given array is a numerical array
	    $keys = array_keys($records);
	    $max_length = count($arr)-1;
		//See if the first key is 0 and last key is length - 1
	    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {
	        $is_list = true;
	        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
	            if($i != $keys[$i]) { //A key fails at position check.
	                $is_list = false; //It is an associative array.
	                break;
	            }
	        }
	    }

	    foreach($records as $key=>$value) 
		{
	        if(is_array($value)) 
			{ 
				if($is_list) 
				{
					if($this)
						$parts[] = $this->RecordToJSON($value); /* :RECURSION: */
					else
						$parts[] = self::RecordToJSON($value); /* :RECURSION: */
				}
	            else 
				{
					if($this)
						$parts[] = '"' . $key . '":' . $this->RecordToJSON($value);
					else
						$parts[] = '"' . $key . '":' . self::RecordToJSON($value); 
				}
			}
        
			else 
			{
	            $str = '';
	            if(!$is_list) $str = '"' . $key . '":';

	            //Custom handling for multiple data types
	            if(is_numeric($value)) $str .= $value; //Numbers
	            elseif($value === false) $str .= 'false'; //The booleans
	            elseif($value === true) $str .= 'true';
				elseif($value == null) $str .= 'null';
	            else $str .= '"' . addslashes($value) . '"'; //All other things
	            

	            $parts[] = $str;
	        }
		}
    
		$json = implode(',',$parts); 
	    	
	    if($is_list == true)
		{
			if(substr($json, strlen($json)-1, 1) == ',')
				$json = substr($json, 0, strlen($json)-1);
				
			return '[' . $json . ']';//Return numerical JSON
		}
			
	    return '{'. $json .'}';
	}
}

?>