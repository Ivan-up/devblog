<?php 
//
// Контроллер админстрирования галерии .
//
class C_Gallery extends C_Admin_Base
{
	protected $pages = array() ;
	protected $navParams = array();
	protected $navBar;
	protected $messages = array();
	protected $fields = array();
	protected $images = array();
	protected $gallery = array();
	protected $galleries = array();

	//
	// Конструктор.
	//
	public function __construct() {
		parent::__construct();
	}
	
	public function before(){
		parent::before();
	}
	
	public function action_index()
	{
		$this->action_all();
	}
	
	//
	// Получить список всех страниц
	//
	public function action_all()
	{
		$this->title .='::Все страницы';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('gallery', M_Link::ToAdminGallery('all'));
		
		// Задание свойств объекта 
		$this->galleries = $mPagination->on_page(5)->page_num($page_num)->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/gallery/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Добавление галерии
	//
	public function action_add()
	{
		$this->title .= '::Галерии';
		$mGallery = M_Gallery::Instance();
		$this->messages = array();
		
		if($this->isPost())
		{						
			if ($gallery_id = $mGallery->add($_POST))
			{
				$this->redirect(M_Link::ToAdminGallery('images', $gallery_id));
			}
			
			$this->fields = $_POST;
			$this->messages = $mGallery->errors();			
		}
		else 
		{
			$this->fields = array_fill_keys(
							array('gallery_title', 'gallery_desc') , '');
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/gallery/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Редактирование галерии.
	//
	public function action_edit()
	{
		$this->title .= '::Галерии';
		$mGallery = M_Gallery::Instance();
		$this->messages = array();
		
		if($this->isPost())
		{						
			if ($mGallery->edit($_POST['gallery_id'],$_POST))
			{
				$this->redirect(M_Link::ToAdminGallery('all'));
			}
			
			$this->fields = $_POST;
			$this->messages = $mGallery->errors();			
		}
		else 
		{
			$gallery_id = isset($_GET['param']) ? $_GET['param'] : 0;
			$this->fields = $mGallery->get($gallery_id);
			if (empty($this->fields))
				$this->p404();
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/gallery/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Спискок всех картинок галерии.
	//
	public function action_images()
	{
		$this->title .= ' :: Галереи';
		$this->styles[] = 'jquery-ui.min';
		$this->scripts[] = 'jquery-ui.min';
		$this->scripts[] = 'custom/init_gallery_images';
		$mGallery = M_Gallery::Instance();
		
		if($this->isPost() && $this->check_priv('C_Gallery:action_editimg'))
		{
			$gallery_id = (int)$_POST['gallery_id'];
			$mGallery->delete_image($gallery_id , $_POST['fid']);
			$this->redirect(M_Link::ToAdminGallery('images', $gallery_id));
		}
		
		$gallery_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		
		$this->gallery = $mGallery->get($gallery_id);
		
		if (empty($this->gallery))
			$this->p404();
		
		$this->images = $mGallery->get_images($gallery_id);
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/gallery/v_images.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Загрузка картинок в галерею
	//
	public function action_upload() 
	{
		$this->title .= ' :: Галереи';
		$this->styles[] = 'plugin_upload_img';
		$this->scripts[] = 'mustache';
		$this->scripts[] = 'custom/plugin_upload_img';
		$id_gallery = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		$this->gallery = M_Gallery::Instance()->get($id_gallery);
		
		if (empty($this->gallery))
			$this->p404();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/gallery/v_upload.php');
		
		$this->content = $this->Template($templateName, $this);				
	}
	
	//
	// Удаление галерии.
	//
	public function action_delete()
	{
		M_Gallery::Instance()->delete_gallery($_GET['param']);
		$this->redirect(M_Link::ToAdminGallery('all'));
	}
	
	//
	// Редактирование изображения
	//
	public function action_editimg()
	{
		$this->title .= ' :: Галереи';
		$mImages = M_MediaFiles::Instance();
		$gallery_id = isset($_GET['param2']) ? (int)$_GET['param2'] : 0;
		$this->gallery = M_Gallery::Instance()->get($gallery_id);
		$this->error = false;
		
		if (empty($this->gallery))
			$this->p404();
		
		if($this->isPost())
		{
			if($mImages->edit($_POST['fid'], $_POST))
				$this->redirect(M_Link::ToAdminGallery('images', $this->gallery['gallery_id']));

			$this->fields = $_POST;
			$this->error = true;
		}
		else
			$this->fields = $mImages->get($_GET['param']);
		
		if (empty($this->fields))
			$this->p404();
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/gallery/v_editimg.php');
		
		$this->content = $this->Template($templateName, $this);
			
	}
	
}