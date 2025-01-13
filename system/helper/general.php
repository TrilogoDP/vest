<?php
	function token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		
		$max = strlen($string) - 1;
		
		$token = '';
		
		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}	
		
		return $token;
	}

	function pluralForm($number, $forms)
	{
		$cases = array(2, 0, 1, 1, 1, 2);
		return $number." ".$forms[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}
	
		
	function prepareBannerAnalitycsID($string){
		
		$string = keyErrFilterTranslitRUToEN($string);
		$string = preg_replace("/[^a-zA-Zа-яА-я0-9\s]/i", "", $string);
		$string = str_replace(' ', '_', $string);
		
		
		return mb_strtoupper($string);
	}
	
	function keyErrFilterTranslitRUToEN($string){
		
		$map =  array("а" => "a", "А" => "a", "б" => "b", "Б" => "b", "в" => "v", "В" => "v", "г" => "g", "Г" => "g", "д" => "d", "Д" => "d", "е" => "e", "ё" => "e", "Ё" => "e", "Е" => "e", "ж" => "zh", "Ж" => "zh", "з" => "z", "З" => "z", "и" => "i", "И" => "i", "й" => "y", "Й" => "y", "к" => "k", "К" => "k", "л" => "l", "Л" => "l", "м" => "m", "М" => "m", "н" => "n", "Н" => "n", "о" => "o", "О" => "o", "п" => "p", "П" => "p", "р" => "r", "Р" => "r", "с" => "s", "С" => "s", "т" => "t", "Т" => "t", "у" => "u", "У" => "u", "ф" => "f", "Ф" => "f", "х" => "h", "Х" => "h", "ц" => "c", "Ц" => "c", "ч" => "ch", "Ч" => "ch", "ш" => "sh", "Ш" => "sh", "щ" => "sch", "Щ" => "sch", "ъ" => "", "Ъ" => "", "ы" => "y", "Ы" => "y", "ь" => "", "Ь" => "", "э" => "e", "Э" => "e", "ю" => "yu", "Ю" => "yu", "я" => "ya", "Я" => "ya", "і" => "i", "І" => "i", "ї" => "yi", "Ї" => "yi", "є" => "e", "Є" => "e");
		
		return strtr($string, $map);
		
	}
	
	function prepareEcommString($string){
		$string = str_replace('&amp;', '&', $string);
		$string = str_replace('&quot;', '', $string);
		$string = str_replace("'", "`", $string);
		$string = str_replace('"', '\"', $string);
		
		return $string;
	}
	
	function randomnumeric($length = 32) {
		// Create random token
		$string = '0123456789';
		
		$max = strlen($string) - 1;
		
		$token = '';
		
		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}	
		
		return $token;
	}
	
	if (!function_exists('echoLine')){
		function echoLine($line, $type = 'l'){
		if (php_sapi_name() === 'cli'){
			switch ($type) {
				case 'e':
				echo "\033[31m$line \033[0m" . PHP_EOL;
				break;
				case 's':
				echo "\033[32m$line \033[0m" . PHP_EOL;
				break;
				case 'w':
				echo "\033[33m$line \033[0m" . PHP_EOL;
				break;  
				case 'i':
				echo "\033[36m$line \033[0m" . PHP_EOL;
				break;    
				case 'l':
				echo $line . PHP_EOL;
				break;  
				default:
				echo $line . PHP_EOL;
				break;
			}
		}
	}
	}
	
	function is_cli(){
			
		return (php_sapi_name() === 'cli');
		
	}
	
	function dateDiff($date){
		
		$date1 = new DateTime(); 
		$date2 = new DateTime($date);
		$diff = $date2->diff($date1)->format("%a");				
		$days = intval($diff) + 1;
		return $days;
		
	}	
	
	function returnProductIDFromArray($item){
		return $item['product_id'];
	}
	
	
	function explodeByEOL($data){
		$result = array();
	
		$exploded = explode(PHP_EOL, $data);
		
		foreach($exploded as $line){
			$tmp = str_replace(PHP_EOL, '', $line);
			$tmp = trim($tmp);
			$result[] = $tmp;
		}
		
		return $result;
	
	}
	
	function phoneToNineDigits($phone){
		
		$phone = preg_replace('/[^0-9]/', '', $phone);
		
		if (mb_strlen($phone) >= 9){
			$phone = substr($phone, -9);
			} else {
			return false;
		}
		
		return $phone;		
	}
	
	
	function rseo_nofollow($content) {
		$content = preg_replace_callback('~<(a\s[^>]+)>~isU', "cb2", $content);
		return $content;
	}
	
	function array_key_unique($arr){
		
		$temp = array();
		
		foreach ($arr as $key => $value){
			$temp[$key] = $value;
		}
		
		return $temp;
	}
	
	function super_normalize($str){
		return mb_strtolower(trim(str_replace('  ', '', $str)));
	}
	
	function checkIfIsSeparatedString($string, $separator = ','){
		$exploded = explode($separator, $string);
		
		if (count($exploded) > 1){
			return array_map('trim', $exploded); 
		}
		
		return false;
	}
	
	function attributeToOCFilterLinkMagic($attribute_groups, $ocfilters){
		
		foreach ($attribute_groups as &$ag){
			if (!empty($ag['attribute'])){				
				foreach ($ag['attribute'] as &$attribute){
					foreach ($ocfilters as $ocfilter){
						if (super_normalize($ocfilter['value']) == super_normalize($attribute['text'])){
							$attribute['option_id'] = $ocfilter['option_id'];
							$attribute['value_id'] = $ocfilter['value_id'];
						}
					}									
				}			
			}
		}
		
		return $attribute_groups;
	}	
	
	function attributesOcFilterUnique($attributes, $ocfilters, $return = 'ocfilter'){
		
		$uniquied_ocfilter = array();
		
		foreach ($ocfilters as &$ocfilter){		
			$ocfilter['add'] = true;
		}	
		unset($ocfilter);
		
		foreach ($attributes as $ag){
			if (!empty($ag['attribute'])){
				foreach ($ag['attribute'] as $attribute){						
					foreach ($ocfilters as &$ocfilter){						
						if (super_normalize($ocfilter['name']) == super_normalize($attribute['name'])){							
							$ocfilter['add'] = false;
							continue;
						}
						
						if (super_normalize($ocfilter['value']) == super_normalize($attribute['text'])){
							$ocfilter['add'] = false;
							continue;
						}					
					}
				}
			}
		}
		unset($ocfilter);
		
		foreach ($ocfilters as $ocfilter){
			if ($ocfilter['add']){
				$uniquied_ocfilter[$ocfilter['name']] = array(
				'option_id'  => $ocfilter['option_id'],
				'name'  	 => $ocfilter['name'],
				'value' 	 => $ocfilter['value'],
				);
			}
		}
		
		//$uniquied_ocfilter = array_unique($uniquied_ocfilter);
		
		if ($return == 'ocfilter'){
			return $uniquied_ocfilter;			
			} else {
			return $attributes;
		}
	}
	
	function cb2($match) { 
		list($original, $tag) = $match;   // regex match groups
		// re-add quirky config here
		$blog_url = HTTPS_SERVER;
		
		if (strpos($tag, "nofollow")) {
			return $original;
			} elseif (strpos($tag, $blog_url)) {
			return $original;
		}
		else {
			return "<$tag rel='nofollow'>";
		}
	}
	
	/**
		* Backwards support for timing safe hash string comparisons
		* 
		* http://php.net/manual/en/function.hash-equals.php
	*/
	
	if(!function_exists('hash_equals')) {
		function hash_equals($known_string, $user_string) {
			$known_string = (string)$known_string;
			$user_string = (string)$user_string;
			
			if(strlen($known_string) != strlen($user_string)) {
				return false;
				} else {
				$res = $known_string ^ $user_string;
				$ret = 0;
				
				for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
				
				return !$ret;
			}
		}
	}								