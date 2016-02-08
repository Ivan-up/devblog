<?php 
//
// Контроллер обработки AJAX-запросов
// 
class C_Ajax extends C_Controller
{
	private $ck_sort_img_folder;
	private $ckupload_dir;
	private $ck_temp_prefix;
	private $base_url;
	
	//
	// Конструктор
	//
	public function __construct()
	{
		$this->ck_sort_img_folder = CK_SORT_IMG_FOLDER;
		$this->ckupload_dir = CKUPLOAD_DIR;
		$this->ck_temp_prefix = CK_TEMP_PREFIX ;
		$this->base_url = BASE_URL;
	}
	
	public function before(){
		
	}
	
	protected function render() {}
	
	//
	// Загрузка картинок CKEDITOR
	//
	public function action_ckupload()
	{
		
			if(M_Users::Instance()->Get() == null)
				die();
		
			if(!isset($_FILES['upload']))
				die();
			
			$callback = $_REQUEST['CKEditorFuncNum'];
			//$callback = 3;
			
			$mFiles = M_Files::Instance()->set_type('image'); //$mMediaFiles = M_MediaFiles::Instance();
			
			if ($this->ck_sort_img_folder === true && isset($_GET['pid']))
			{	
				$pid = strip_tags($_GET['pid']);
				$destination =  $this->ckupload_dir . $pid;
				
				if ($pid == 'post_0'){
					$destination .=  '_' . $this->ck_temp_prefix . '/';
					$_SESSION['destination'] = $destination;
				}
				else 
					$destination .= '/';
			}
			else 
				$destination =  $this->ckupload_dir;			
						
			if (!is_dir($destination))
				mkdir($destination);
					
			$file = $mFiles->upload_file($_FILES['upload'], $destination);	
			
			if ($file)
			{
				$http_path =  $destination . $file['name'];
				$message = 'Файл загружен';
			}
			else 
			{
				$http_path = '';
				$message = $mFiles->error();
			}			
			
			echo "<script>window.parent.CKEDITOR.tools.callFunction($callback, \"".$http_path."\",\"".$message."\");</script>";
	}
	
	//
	// Загрузка картинок в галерею
	//	
	public function action_image()
	{
		if(M_Users::Instance()->Get() == null)
				die();
		//sleep(10);
		if($image_id = M_MediaFiles::Instance()
				->upload_img_base64($_POST['name'], $_POST['value']))
		{
			M_Gallery::Instance()->add_image($_POST['gallery_id'], $image_id);
			die($_POST['name'] . ':загружен успешно');
		}
		
		die($_POST['name'] . ':ошибка загрузки');
	}
	
	//
	// Сортировка картинок в галерии
	//
	public function action_galsort()
	{		
		if(M_Users::Instance()->Get() == null)
			die();
		
		echo (int)M_Gallery::Instance()->sorting($_POST['id_gallery'], $_POST['images']);
	}
	
	//
	// Загрузка видео частями
	//
	public function action_uploadVideo()
	{
		if (!isset($_SESSION['hash']) || 
				!isset($_SESSION['name']) || 
				!isset($_SESSION['uploaddir']))
		{
			header("HTTP/1.0 500 Internal Server Error");
			print "Wrong session hash.";
			die();
		}	
	
		$uploaddir = $_SESSION['uploaddir'];
		$name = $_SESSION['name'];

		if (preg_match("/^[0123456789abcdef]{32}$/i", $_SESSION['hash'])) {
			if ($this->IsGet() && isset($_GET['st'])) {
				if ($_GET['st'] == "abort") {
					if (is_file($uploaddir.$name.".html5upload")) unlink($uploaddir.$name.".html5upload");
					print "ok abort";
					unset($_SESSION['hash'],$_SESSION['name'], $_SESSION['uploaddir']);
					return;
				}

				if ($_GET['st'] == "done") {
					if (is_file($uploaddir.$name.".original")) 
						unlink($uploaddir.$name.".original");

					rename($uploaddir.$name.".html5upload", $uploaddir.$name);		
					$_SESSION['done'] = $name;
					unset($_SESSION['hash'],$_SESSION['name'], $_SESSION['uploaddir']);
				}
			}
			elseif ($_SERVER["REQUEST_METHOD"]=="POST"){

				$filename = $uploaddir . $name .  ".html5upload";
				
				if (intval($_SERVER["HTTP_PORTION_FROM"]) == 0) 
					$fout = fopen($filename,"wb");
				else
					$fout = fopen($filename,"ab");

				if (!$fout) {
					header("HTTP/1.0 500 Internal Server Error");
					print "Can't open file for writing.";
					return;
				}

				$fin = fopen("php://input", "rb");
				if ($fin) {
					while (!feof($fin)) {
						$data=fread($fin, 1024*1024);
						fwrite($fout,$data);
						}
					fclose($fin);
					}

				fclose($fout);
				}

			header("HTTP/1.0 200 OK");
			print "ok\n";
			}
		else {
			header("HTTP/1.0 500 Internal Server Error");
			print "Wrong session hash.";
		}
	}
	
	//
	// Получения списка галерей для CKEDITOR
	//
	public function action_getgallery()
	{  
		foreach (M_Gallery::Instance()->all() as $key => $val)
		{	 
			$data[$key]['id_gallery'] = $val['gallery_id'];
			$data[$key]['title'] = $val['gallery_title'];	
		}	
		
		echo json_encode($data);                
	}
	
	//
	// Получения списка видеозаписей для CKEDITOR
	//
	public function action_getvideo()
	{  
		foreach (M_MediaFiles::Instance()->getAllType('video') as $key => $val)
		{	 
			$data[$key]['fid'] = $val['fid'];
			$data[$key]['title'] = $val['title'];	
		}	
		
		echo json_encode($data);                
	}
	
	//
	// Получения списка аудиозапсей для CKEDITOR
	//
	public function action_getaudio()
	{  
		foreach (M_MediaFiles::Instance()->getAllType('audio') as $key => $val)
		{	 
			$data[$key]['fid'] = $val['fid'];
			$data[$key]['title'] = $val['title'];	
		}	
		
		echo json_encode($data);                
	}
	
	//
	// Получения списка аудиозапсей для CKEDITOR
	//
	public function action_getpolls()
	{  
		foreach (M_Poll::Instance()->all() as $key => $val)
		{	 
			$data[$key]['pid'] = $val['pid'];
			$data[$key]['question'] = $val['question'];	
		}	
		
		echo json_encode($data);                
	}
	
	//
	// Получения контента страницы с php.net для CKEDITOR
	//	
	public function action_get_cont_php_net()
	{
		if (M_Users::Instance()->Get() == null)
				die();
			
		$str = "";
		
		if (isset($_POST['url']))	
			$str = M_Helpers::get_desc_func_from_php_net($_POST['url']);		
		
		echo $str;
	}
	
	
}