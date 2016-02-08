<?php 

class M_Files
{
	private static $instance; //ссылка на экземпляр класса
	protected $error;
	protected $ftypes;
	
	public static function Instance()
	{
		if(self::$instance == null)
			self::$instance = new self();
			
		return self::$instance;
	}
	
	//
	// Удаляет директорию
	//
	public function remove_directory($dir)
	{
    if ($objs = glob($dir."/*"))			
       foreach($objs as $obj) 
         is_dir($obj) ? $this->remove_directory($obj) : unlink($obj);       
    
    rmdir($dir);
  }
	
	//
	// Перемещает файлы
	// @return boolean
	public function move_file($last, $new)
	{
		$last = trim($last);
		$new = trim($new);
		
		if (is_file($last))
		{
			copy($last, $new);
			unlink($last);
			return true;
		}
		
		return false;
	}
	
	//
	// Получает список файлов в папке
	// @return array
	public function get_files_in_folder($path)
	{		
		$files = array();
		
		if (is_dir($path))
			$files = scandir($path);
		
		if ($files != false)
		{
			$unset_s = array('.', '..'); 
			$files = array_diff($files, $unset_s);
		} 
		else $files = array();
		
		return $files;
	}
	
	// Возращает расширение файла
	// @param $filename - имя файла 
	// @return string
	public static function get_extention_file($filename)
	{
		$getExt = explode('.', $filename);
		if (count($getExt) < 2)
			return false;
		
		return strtolower(trim(end($getExt)));
	}
	
	// Возращает mime файла
	// @param $filename - имя файла 
	// @return string
	public static function get_mime_file($filename)
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $filename);
		finfo_close($finfo);
		return $mime;
	}
	
	// Возращает имя файла без расширения
	// @param $filename - имя файла 
	// @return string
	public static function get_name_without_ext($filename)
	{
		return mb_substr($filename, 0, (int)mb_strrpos($filename, '.'));
	}
	
	// Генерирует случайное имя файла
	// @param $ext_file - расширение файла 
	// @param $folder - папка, где будет хранится файл
	// @return string
	public static function generate_file_name($ext_file, $folder = null)
	{
		$filename = mt_rand(0, 10000000) . '.' . $ext_file;
		
		if ($folder !== null)
		{ 
			while(file_exists($folder . $filename))
				$filename = mt_rand(0, 10000000) . '.' . $ext_file;
		}
		
		return $filename;
	}
	
	// Устанавливает тип файлов 
	// для экземпляра класса
	// @param $type - тип файлов
	public function set_type($type)
	{
		$mime = include (FILES_MIME);
		$this->ftypes = isset($mime[$type]) ? $mime[$type] : array();
		return $this;
	}
	
	// Возращает массив разрешенных файлов
	// @return array()
	public function get_allow_types(){
		return $this->ftypes;
	}
	
	
	//
	// Загружает файл из формы
	// @param $files - элемент супер глобального массива $_FILES ($_FILES['имя_поля'])
	// @param $folder - папка куда загружать
	// @param $types - многомерный массив, разрешенных файлов array(array(расширение => массив mime),...)
	// @param $new_name - новой имя для файла без расширения
	// @return ассоциативный массив 
	// array('name' => имя_файла c расширением, 'mime' => mime-файла, 'ext' => расширение файла)
	public function upload_file($files, $folder, $max_size = null, $types = null, $new_name = null) 
	{
		if ($types == null)
			$types = $this->ftypes;
		
		if (empty($types))
			return false;
		
		if ($files == "none" || empty($files['name']))
			$this->error = "Вы не выбрали файл";
		elseif ($files['error'] == 1 )
			$this->error = "Вы превысили максимальный размер файла установленный для сервера";
		elseif (!is_uploaded_file ($files['tmp_name']))
			$this->error = "Возникла ошибка при загрузке";
		elseif(!empty($max_size) && $files['size'] > $max_size)
			$this->error = "Вы превысили максимальный размер файла";
		
		if (!empty($this->error))
			return false;
		
		
		$ext_file = self::get_extention_file($files['name']);
		$mime = self::get_mime_file($files['tmp_name']);
		
		$file_info = array('mime' => $mime ,'ext' => $ext_file);		
		
		if (!isset($types[$ext_file]) || !is_array($types[$ext_file])
				|| !in_array($mime, $types[$ext_file]))
		{
			$this->error = "Файл поврежден или имеет недопустимый формат. Разрешены: " . implode(array_keys($types));
			return false;
		}

		if (empty($new_name))
			$new_name = self::generate_file_name($ext_file, $folder);
		else 
			$new_name .= ".$ext_file";
		
		if (!move_uploaded_file($files['tmp_name'], $folder . $new_name))				
			$this->error = "Возникла ошибка при загрузке ";
		else 
			$file_info['name'] = $new_name;

		return isset($file_info['name']) ? $file_info : false;		
	}
	
	public function error()
	{
		return $this->error;
	}
	
	// Cоздает из оригинальной картинки
	// новую картинку с изменеными размерами 
	// @param $src - путь оригинальная картинка
	// @param $desc - куда будем сохранять 
	public function resize_img($src, $dest, $width, $height = null, $rgb = 0xFFFFFF, $quality = 100)
	{
		if (!file_exists($src)) return false;

		$size = getimagesize($src);

		if ($size === false) return false;

		// Определяем исходный формат по MIME-информации, предоставленной
		// функцией getimagesize, и выбираем соответствующую формату
		// imagecreatefrom-функцию.
		$format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
		$icfunc = "imagecreatefrom" . $format;
		
		if (!function_exists($icfunc)) return false;
	
		$x_ratio = $width / $size[0];
	
		if($height === null)
			$height = $size[1] * $x_ratio;
	
		$y_ratio = $height / $size[1];

		$ratio       = min($x_ratio, $y_ratio);
		$use_x_ratio = ($x_ratio == $ratio);

		$new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
		$new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
		$new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
		$new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

		$isrc = $icfunc($src);
		$idest = imagecreatetruecolor($width, $height);

		imagefill($idest, 0, 0, $rgb);
		imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
			$new_width, $new_height, $size[0], $size[1]);

		imagejpeg($idest, $dest, $quality);

		imagedestroy($isrc);
		imagedestroy($idest);

		return true;
	}	
	
}