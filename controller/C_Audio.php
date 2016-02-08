<?php
//
// Контроллер администратирования аудиозаписей
//  
class C_Audio extends C_Admin_Base
{
	protected $audio = array() ;
	protected $navParams = array();
	protected $navBar;
	protected $messages;
	protected $fields;
	
	public function __construct()
	{
		parent::__construct();
	}
	public function before()
	{
		parent::before();
	}
	
	public function action_index()
	{
		$this->action_all();
	}
	
	//
	// Получит весь список аудиозаписей
	// 
	public function action_all()
	{		
		$this->title .= '::Аудио';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mediafile', M_Link::ToAdminAudio('all'));
		
		// Задание свойств объекта 
		$this->audio = $mPagination->on_page(5)->page_num($page_num)->where("type='audio'")->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/audio/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Добавление аудиозаписей
	// 
	public function action_add()
	{
		$this->title .= '::Добавить аудио';	
		$this->scripts[] = 'jquery.uploadify.min';
		$this->scripts[] = 'custom/init_audio_add';
		$mMediaFiles = M_MediaFiles::Instance();		
		$this->messages = array();
		$this->messages['file_error'] = "";
		
		if($this->isPost())
		{	
			$verifyToken = md5(HASH_KEY . $_POST['timestamp']);
			
			if (!empty($_FILES) && $_POST['token'] == $verifyToken) 
			{				
				if ($mMediaFiles->addFile($_FILES['Filedata'], 'audio'))
					die(" успешно загружен");
				else {
					$this->messages = $mMediaFiles ->errors();
					die (" не удалось загрузить. " . $this->messages['file_error']);
				}
			}
			die("Произошла ошибка при загрузке");
		}
		
		$this->_setToken();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/audio/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Редактирование аудиозаписей
	// 
	public function action_edit()
	{
		$this->title .= '::Редактирование аудио';	
		$mMediaFiles = M_MediaFiles::Instance();
		$this->messages = array();
		$id_audio = isset($_GET['param']) ? $_GET['param'] : 0;
		$this->fields = $mMediaFiles->get($id_audio);
		
		if (empty($this->fields))	
			$this->p404();
			
	
		if($this->isPost())
		{
			if(isset($_POST['save'])){
				if($mMediaFiles->edit($id_audio, $_POST))
					$this->redirect(M_Link::ToAdminAudio('all'));
			}
			elseif(isset($_POST['delete']) && $this->check_priv('C_Audio:action_delete')){
				$mMediaFiles->deleteFile($id_audio);
				$this->redirect(M_Link::ToAdminAudio('all'));
			}
			
			$this->messages = $mMediaFiles->errors();
			$this->fields = $_POST;
		}			
	
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/audio/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);

	}
	
	//
	// удаление аудиозаписи
	// 
	public function action_delete()
	{
		if($this->IsGet())
		{
			$id_audio = $_GET['param'];
			M_MediaFiles::Instance()->deleteFile($id_audio);
			$this->redirect($_SERVER['HTTP_REFERER']); 
		}
  }
	
	//
	// Установка токена валидации для загрузки аудио
	// 
	private function _setToken()
	{		
		// путь к файлу инсталяции uploadify
		$filename = JS_DIR . 'custom/init_audio_add.js';
		$timestamp = time();
		$token = md5(HASH_KEY . $timestamp);
		
		$var_name[0] = '_timestampUploadFiles';
		$var_name[1] = '_tokenUploadFiles';
		$new_val[0] = $timestamp;
		$new_val[1] = $token;
		//var_dump($timestamp, $token, HASH_KEY);	
		return M_Helpers::replace_var_js_file($var_name, $new_val, $filename);
	}
}