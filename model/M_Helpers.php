<?php

class M_Helpers
{
	// Генерирует уникальное имя
	public static function unique_name($dir, $name, $translit = false){
		$name = preg_replace('/[:\/\"]+/', '', $name);	
		$temp = explode('\\', $name);
		$name = end($temp);
		
		if($name == '')
			return '';
		
		$temp = explode(' ', $name);
		$name = implode('_', $temp);

		if($translit)
			$name = self::make_tarnslit($name);
			
		if(file_exists($dir . $name))
		{
			$temp = explode('.', $name);
			$ext = array_pop($temp);
			$i = 1;
			$str = "($i)";
			$name = implode('.', $temp) . $str . ".$ext";
			
			while(file_exists($dir . $name)){
				$i++;
				$str = "($i)";
				$name = implode('.', $temp) . $str . ".$ext";
			}
		}
		
		return $name;
	}
	
	// Выполняет хеширование с использованем HASH_KEY
	public static function get_hash($str){
		$i = 0;
		while($i++ < 4)
			$str = md5(md5($str . HASH_KEY) . $str);
			
		return $str;
	}
	
	// выполняет транслит строки из руссих символов
	public static function make_tarnslit($str){
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',

			'г' => 'g',   'д' => 'd',   'е' => 'e',

			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',

			'и' => 'i',   'й' => 'y',   'к' => 'k',

			'л' => 'l',   'м' => 'm',   'н' => 'n',

			'о' => 'o',   'п' => 'p',   'р' => 'r',

			'с' => 's',   'т' => 't',   'у' => 'u',

			'ф' => 'f',   'х' => 'h',   'ц' => 'c',

			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',

			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',

			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

			

			'А' => 'A',   'Б' => 'B',   'В' => 'V',

			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',

			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',

			'И' => 'I',   'Й' => 'Y',   'К' => 'K',

			'Л' => 'L',   'М' => 'M',   'Н' => 'N',

			'О' => 'O',   'П' => 'P',   'Р' => 'R',

			'С' => 'S',   'Т' => 'T',   'У' => 'U',

			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',

			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',

			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',

			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

		);
		
		return strtr($str, $converter);
	}
	
	// Задает правильные окончания для слов
	// @param $num - число к которому относится строка 
	// M_Helpers::get_correct_str($num, 'голос%s' ,'', 'а', 'ов')
	// @return string
	public static function get_correct_str($num, $format, $str1, $str2, $str3) 
	{
		$val = $num % 100;	

		if ($val > 10 && $val < 20)	$str = $str3;
		else 
		{
			$val = $num % 10;
			if ($val == 1) $str = $str1;
			elseif ($val > 1 && $val < 5)	$str = $str2;
			else $str = $str3;
		}
		
		return sprintf("$num ". $format, $str);
	}
	
	//
	// Выводит дату с учетом локали
	// @param $format - формат даты
	// @param $timestamp - временная метка 
	//
	public static function strf_time($format, $timestamp, $locale = LOCALE_TIME_RU)
	{
		$date_str = strftime($format, $timestamp);
		//global $locale;
		//$locale = setlocale (LC_TIME, 'ru_RU.UTF-8', 'Rus');
		if (strpos($locale, '1251') !== false)
			return iconv('cp1251', 'utf-8', $date_str);		
		else		
			return $date_str;		
	}
	
	//
	// Значение переменной в файле
	// @param $name - имя переменной 
	// @param $new_val - новое значение для переменной 
	// @param $filename - файл в котором меняем
	// @return boolean
	//
	public static function replace_var_js_file($name, $new_val, $filename)
	{
		$data = array();
		
		if (!is_file($filename))
			return false;
		
		$data = file($filename);
		
		if (is_array($name) && is_array($new_val))
		{
			$lenght = count($name);
			$lenght2 = count($new_val);
			if ($lenght != $lenght2)
				return false;
			
			for ($i = 0; $i < $lenght; $i++)
			{
				$pattern[$i] = '/(' . $name[$i] . ' ?=).+;/';
				$replacement[$i] ='$1"' . $new_val[$i] . '";';
			}
			
		} 
		else if (is_string($name) && is_scalar($new_val))
		{		
			$pattern = '/(' . $name . ' ?=).+;/';
			$replacement ='$1"' . $new_val . '";';
		}
		else 
			return false;		
		
		foreach ($data as $key => &$value)			
			$value = preg_replace($pattern, $replacement, $value);			
		
		
		if (!empty($data))
			return (bool)file_put_contents($filename, $data);
	
		return false;
	}

	// Получает описание функции с php.net
	// @param $url - aдрес которого забираем контент
	// пример http://php.net/manual/ru/function.strlen.php
	// @return string
	public static function get_desc_func_from_php_net($url)
	{
		$id = basename( $url , '.php' );
		@$page = file_get_contents($url);
		
		if (!$page)
			return '';
		
		$doc = new DOMDocument();
		
		// Включаем временно пользовательскую обработку ошибок
		libxml_use_internal_errors(true);
		
		$doc -> loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $page);
		
		// Выбираем нужные div
		$divContainer = $doc->getElementById($id);
		
		// отключать ошибки не хорошо, включаем 
		libxml_use_internal_errors(false);
		
		if (!$divContainer)
			return '';
		
		// Классы детей divContainer, которые будем забирать
		$allowed_class = array('refnamediv', 'refsect1 description', 
												'refsect1 parameters', 'refsect1 returnvalues');
		
		$parts = $divContainer->childNodes;
		
		$content = array();
		
		// Перебираем в детей
		for( $i = 0; $parts->length > $i ; $i++) 
		{
			$part = $parts->item($i);
			
			// Если это тип элемент
			if ($part->nodeType == 1 && 
					in_array($part->getAttribute('class'), $allowed_class))
			{			
				// Удаляем все ссылки в тексте(первый вариант)
				$links = $part->getElementsByTagName('a');								
				while($links && $link = $links->item(0))
				{
					$txt = $link->textContent;
					$new_item = $doc->createTextNode($txt);
					$parent = $link->parentNode;
					$parent->replaceChild($new_item, $link);						
				}							
				
				// добавляем в массив элемент в виде строки
				$content[] = $doc->saveHTML($part);				
			}
			
		}	
		
		unset($doc);
		
		$ready_page = '';		
		$result = implode('', $content);
		
		if ($result)
			$ready_content = $result;
		
		// Удаляем все ссылки в тексте(второй вариант)
		//if ($result)
			//$ready_content = preg_replace('#<a.*>(.*)</a>#Usi', '$1', $result);			
		
		return $ready_content;
	}
	
	// Отправляет форму обратной связки
	// @param $fields - массив полей 
	// @param $errors - будут записаны возникшие ошибки
	// @return boolean
	public static function sendEmail($fields, &$errors = array())
	{
		$to = ADMIN_EMAIL;
		$from_email = SITE_EMAIL;
		$errors = array();
		
		$name = trim(strip_tags($fields['name']));
		$email = trim($fields['email']);
		$message = htmlspecialchars(trim($fields['message']));
		
		if (mb_strlen($name) < 2)
			$errors['name'] = "Имя не должно быть минимум 2 символа";
		
		if (mb_strlen($message) < 10)
			$errors['message'] = "Cообщение не может быть короче 10 символов";
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			$errors['email'] = "Поле email не заполнено или содержит не правильный формат";
		
		if (!empty($errors))
			return false;
		
		$message = "От $name <$email>\r\n" . $message;		
		
		$subject = "Форма обратной связи с сайта http://" . $_SERVER['HTTP_HOST'] . '/';
		
		$headers = array();		
		$headers[] = "Content-type: text/plain; charset=utf-8";
		$headers[] = "From: <$from_email>";
		$headers[] = "X-Mailer: PHP/" . phpversion();
		
		return mail($to, $subject, $message, implode("\r\n", $headers));
	}
}