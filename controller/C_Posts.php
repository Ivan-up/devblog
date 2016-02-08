 <?php 
//
// Контроллер админстратирования страниц.
//

class C_Posts extends C_Admin_Base
{
	protected $pages = array() ;
	protected $navParams = array();
	protected $navBar;
	protected $messages = array();
	protected $fields = array();
	protected $map = array();
	protected $messageSuccess;
	
	
	//
	// Конструктор
	//
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
	// Все страницы.
	//
	public function action_all()
	{
		$this->title .='::Все страницы';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('posts', M_Link::ToAdminPosts('all'));
		
		// Задание свойств объекта 
		$this->pages = $mPagination->on_page(5)->page_num($page_num)->order_by('post_id DESC')->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/posts/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Добавление страницы.
	//
	public function action_add()
	{
		$this->title .= '->Добавление страницы';
		$this->scripts[] = 'ckeditor/ckeditor';
		$this->scripts[] = 'custom/init_posts_add_edit';
		
		$this->messages = array();
		
		if($this->isPost())
		{			
			$_POST['post_author'] = M_Users::Instance()->GetUid();
			
			if ($post_id = M_Posts::Instance()->add($_POST))
			{
				$this->redirect(M_Link::ToAdminPosts('all'));
			}
			
			$this->fields = $_POST;
			$this->messages = M_Posts::Instance()->errors();			
		}
		else 
		{
			$this->fields = array_fill_keys(
							array('post_title', 'post_content', 'post_status', 
										'comment_status', 'parent', 'link_title',
										'link_description', 'post_type') , '');
		}
		
		$this->map =  M_Menu::Instance()->getMenuWithChildren();
		
		// Переписываем URL загрузки картинок CKEditor
		$this->EnableParamsUrlCKEditor();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/posts/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Редактирование страницы.
	//
	public function action_edit()
	{
		$this->title .= '->Редактирование страницы';
		$this->scripts[] = 'ckeditor/ckeditor';
		$this->scripts[] = 'custom/init_posts_add_edit';
		$mPosts = M_Posts::Instance();
		$this->messages = array();
		
		if($this->isPost())
		{
			if($mPosts->edit($_POST['post_id'], $_POST))
			{
				$_SESSION['saved_post'] = true;
				$this->redirect(M_Link::ToAdminPosts('edit', (int)$_POST['post_id']));
			}
			
			$this->fields = $_POST;
			
			$this->messages = M_Posts::Instance()->errors();
		}	
		else
		{	
			$post_id = isset($_GET['param']) ? $_GET['param'] : 0;
			$this->fields = $mPosts->get($post_id);
			if (empty($this->fields))
				$this->p404();
			$this->fields['parent'] = $this->fields['menu_id'] . ':' . 
																$this->fields['plid'];
		}

		if (isset($_SESSION['saved_post']))
		{
			unset($_SESSION['saved_post']);
			$this->messageSuccess = 'Изменения сохранены';
		}
		
		$this->map =  M_Menu::Instance()->getMenuWithChildren();
		
		// Переписываем URL загрузки картинок CKEditor
		$this->EnableParamsUrlCKEditor();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/posts/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Удалить запись
	//
	public function action_delete()
	{
		M_Posts::Instance()->delete($_GET['param']) === true;
		$url = M_Link::ToAdminPosts('all');
		$this->redirect($url);	
	}
	
	// Переписывает URL загрузки картинок CKEditor
	// добавляет дополнительные параметры 
	private function EnableParamsUrlCKEditor()
	{
		// путь к файлу инсталяции CKEditor		
		$filename = substr(JS_DIR, iconv_strlen(BASE_URL)) . 'custom/init_posts_add_edit.js';		
		$var_name = '_fileUploadUrl';
		
		// При добавление нового поста $post_id = 0
		if(isset($_GET['param']))
			$post_id = (int)$_GET['param'];
		else 
			$post_id = 0;
		
		$action_url = BASE_URL.'index.php?c=ajax&action=ckupload&pid=post_' . $post_id;
		
		return M_Helpers::replace_var_js_file($var_name, $action_url, $filename);
	}
}