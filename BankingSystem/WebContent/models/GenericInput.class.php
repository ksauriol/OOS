<?php
class GenericInput {
	public static function stripInput($input, $hardCode){
		$count=0;
		$token = Array();
		
		$parts =  preg_split("/\?/", $input, null, PREG_SPLIT_NO_EMPTY);
		$not = array("%7B","%7D");
		$token[]=str_replace($not,"",$parts[0]);
		//print_r($token[0].'<br>');
	    $temps =  preg_split("/\&/", $parts[1], null);
	   // print_r($hardCode[$count].'***'.$hardCode[1].'<br>');
		foreach ($temps as $temp){ 
			$value =  preg_split("/\=/", $temp, null, PREG_SPLIT_NO_EMPTY);
			//print_r($hardcode[$count].'---'.$value[0].'***'.$value[1].'<br>');
			if (strcmp($hardCode[$count],$value[0])==0)//Makes sure the non parsed fields are correct
				$token[$count+1] = htmlspecialchars($value[1]);
			$count++;
		}
		//print_r($token[1].'*<br>-'.$token[2]);
		return $token;
	}
}

error_reporting(E_ALL ^ E_NOTICE);
?>