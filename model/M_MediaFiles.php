<?php
class M_MediaFiles extends M_Model
{	
	public $errors;
	private static $instance;	// экземпляр класса
	private $imgDir;
	private $imgSmallDir;
	private $imgSmallWidht;
	private $videoDir;
	private $audioDir;

	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
			
		return self::$instance;
	}

	public function __construct()
	{
		$this->imgDir = IMG_DIR;
		$this->imgSmallDir = IMG_SMALL_DIR;
		$this->imgSmallWidht = IMG_SMALL_WIDTH;
		$this->videoDir = VIDEO_DIR;
		$this->audioDir = AUDIO_DIR;
		parent::__construct('mediafile', 'fid');
	}
	
	public function getAllType($type)
	{
		$query = "SELECT fid, name, title FROM mediafile WHERE type=?";
		return $this->db->Select($query, array($type));
	}
	
	public function deleteImg($fid) 
	{
		$fid = (int)$fid;
		$one = $this->get($fid);
		parent::delete($fid);
		$filename = $one['name'];
		
		$big = SITE_ROOT . '/' . $this->imgDir . $filename;
		$small = SITE_ROOT . '/' . $this->imgSmallDir . $filename;
		
		if(file_exists($big))
			unlink($big);
			
		if(file_exists($small))
			unlink($small);
			
		return true;
	}
	
	public function deleteVideo($fid) 
	{
		$fid = (int)$fid;
		$one = $this->get($fid);
		parent::delete($fid);
		$filename = $one['name'];
		
		$path = SITE_ROOT . '/' . $this->videoDir . $filename;
		
		if(file_exists($path))
			unlink($path);			
			
		return true;
	}
	
	public function deleteFile($fid) 
	{
		$fid = (int)$fid;
		$one = $this->get($fid);		
		$filename = $one['name'];
		$filetype = $one['type'];
		
		switch ($filetype)
		{
			case 'audio': 
				$folders = array($this->audioDir);
				break;
			case 'video':
				$folders = array($this->videoDir);
				break;
			case 'image':
				$folders = array($this->imgDir, $this->imgSmallDir);
				break;
			default:
				$folders = array();
		}
		
		if (empty($folders))
			return false;
		
		parent::delete($fid);
		
		foreach ($folders as $folder)
		{			
			$path = SITE_ROOT . '/' . $folder . $filename;
			
			if(file_exists($path))
				unlink($path);			
		}
		
		return true;
	}
	
	public function addFile($files, $type, $fields = array())
	{
		$path = "";
		switch ($type) 
		{
			case 'audio':
				$path = $this->audioDir;
				break;
			case 'video':
				$path = $this->videoDir;
				break;
		}
		
		if(empty($path))
			return false;
		
		$folder = SITE_ROOT . '/' . $path;
		
		$mFiles = M_Files::Instance()->set_type($type);
		$_file = $mFiles->upload_file($files, $folder);

		if (!$_file)
		{
			$this->errors['file_error'] = $mFiles->error(); 
			return false;
		}
		
		$file['name'] = $_file['name'];
		$file['type'] = $type;
		
		//$title = mb_substr($files['name'], 0, (int)mb_strrpos($files['name'], '.'));
		$title = M_Files::get_name_without_ext($files['name']);
		
		$file['title'] = isset($field['title']) ? $field['title'] : $title;		
		
		return parent::add($file);		
	}
		
	public function upload_img_base64($name, $value)
	{
		$types = M_Files::Instance()->set_type('image')->get_allow_types();
		$ext_file = M_Files::get_extention_file($name);
		
		if (!array_key_exists($ext_file, $types))		
			return false;		
		
		$filename = M_Files::generate_file_name($ext_file, $this->imgDir);
		
		if (!$this->move_upload_base64($value, $filename))
			return false;
		
		$id = $this->db->Insert('mediafile', array('name' => $filename));
		
		return $id;
	}
	
	private function move_upload_base64($file, $name) 
	{ 
		// Выделим данные
		$data = explode(',', $file);
		
		// Декодируем данные, закодированные алгоритмом MIME base64
		$encodedData = str_replace(' ','+',$data[1]);
		$decodedData = base64_decode($encodedData);
		
		// Создаем изображение на сервере
		if(file_put_contents($this->imgDir . $name, $decodedData)){
			M_Files::Instance()->resize_img($this->imgDir . $name, $this->imgSmallDir . $name, $this->imgSmallWidht);
			return true;
		}
		
		return false;
	}	
		
}