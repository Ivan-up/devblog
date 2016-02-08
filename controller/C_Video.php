<?php 
//
// Контроллер адмистрирования видеозаписей
//
class C_Video extends C_Admin_Base
{
	public $video = array() ;
	public $navParams = array();
	public $navBar;
	public $messages;
	public $fields;
	
	public function __construct(){
		parent::__construct();
	}
	public function before()
	{
		parent::before();
	}
	
	public function action_index(){
		$this->action_all();
	}
	
	public function action_all(){
		$this->title .= '::Видео';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mediafile', M_Link::ToAdminVideo('all'));
		
		// Задание свойств объекта 
		$this->video = $mPagination->on_page(5)->page_num($page_num)->where("type='video'")->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/video/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
		
	}
	
	public function action_add(){
		$this->title .= '::Добавить видео';	
		$this->scripts[] = 'custom/plugin_fileuploader';
		$this->scripts[] = 'custom/init_video_add';
		$mMediaFiles = M_MediaFiles::Instance();
		
		$this->messages = array();
		
		if($this->isPost())
		{
			if(isset($_SESSION['done']))
			{
				$_POST['name'] = $_SESSION['done'];
				unset($_SESSION['done']);
				$_POST['type'] = 'video';
				
				if($mMediaFiles ->add($_POST))
					$this->redirect(M_Link::ToAdminVideo('all'));
			}			
			
			$this->messages = $mMediaFiles ->errors();
			$this->fields = $_POST;
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/video/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
		
	}
	
	public function action_edit()
	{
		$this->title .= '::Редактирование видео';	
		$mMediaFiles = M_MediaFiles::Instance();
		$this->messages = array();
		$id_video = isset($_GET['param']) ? $_GET['param'] : 0;
		
		if($this->isPost())
		{
			if(isset($_POST['save'])){
				if($mMediaFiles->edit($id_video, $_POST))
					$this->redirect(M_Link::ToAdminVideo('all'));
			}
			elseif(isset($_POST['delete']) && $this->check_priv('C_Video:action_delete')){
				$mMediaFiles->deleteVideo($id_video);
				$this->redirect(M_Link::ToAdminVideo('all'));
			}
			
			$this->messages = $mMediaFiles->errors();
			$this->fields = $_POST;
		}
		else
		{
			$this->fields = $mMediaFiles->get($id_video);
			if (empty($this->fields))
				$this->p404();
		}		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/video/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);

	}
	
	public function action_delete()
	{
		if($this->IsGet()){
			$id_video = isset($_GET['param']) ? $_GET['param'] : 0;
			M_MediaFiles::Instance()->deleteVideo($id_video);
			$this->redirect($_SERVER['HTTP_REFERER']); 
		}
  }
	
	public function action_upload(){

		$_SESSION['hash'] = M_Helpers::get_hash($_POST['name'] . time());
		$_SESSION['uploaddir'] = SITE_ROOT . '/' .VIDEO_DIR;
		$_SESSION['name'] = M_Helpers::unique_name($_SESSION['uploaddir'], $_POST['name'], true);
		
		die('1');
	}
}