<?php 
//
// Контроллер администрирования меню
//

class C_Menu extends C_Admin_Base
{
	public $fields = array('menu_title' => '', 'menu_description' => '');
	public $navParams;
	public $navBar;
	public $menu;
	public $messages;
	public $map;
	
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
	// Список всех меню
	//
	public function action_all()
	{
		$this->title .= '::Меню';
		
		//получение номера страницы
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('menu', M_Link::ToAdminMenu('all'));
		
		//задание свойств объекта
		$this->menu = $mPagination->on_page(5)->page_num($page_num)->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/menu/v_all.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Добавление меню.
	//
	public function action_add()
	{
		$this->title .= '::Меню';	
		
		$messages = array();
		
		if($this->isPost())
		{
			if($id_menu = M_Menu::Instance()->add($_POST))
				$this->redirect(M_Link::ToAdminMenu());
			
			$this->fields = $_POST;
			$messages = M_Menu::Instance()->errors();
		}	
		
		$this->messages = $messages;	
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/menu/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Редактирование меню.
	//
	public function action_edit()
	{
		$this->title .= '->Меню';
		
		$mMenu = M_Menu::Instance();
		$messages = array();
		
		if($this->isPost())
		{
			if($mMenu->edit($_POST['menu_id'], $_POST))
			{
				$this->redirect(M_Link::ToAdminMenu('all'));
			}
			$this->fields = $_POST;
			$messages = $mMenu->errors();
		}
		else
		{
			$menu_id = isset($_GET['param']) ? $_GET['param'] : 0;
			$this->fields = $mMenu->get($menu_id);
			if (empty($this->fields))
				$this->p404();
		}
		
		$this->messages = $messages;
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/menu/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Удаление меню.
	//
	public function action_delete()
	{
		$menu_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		M_Menu::Instance()->delete($menu_id);
		$this->redirect(M_Link::ToAdminMenu('all'));
	}
	
	//
	// Добавление ссылки в меню.
	//
	public function action_addlink()
	{
		$this->title .= '::Меню ссылки';
		
		$messages = array();	
		
		if($this->isPost())
		{
			$parent = explode(':' ,$_POST['parent']);
			$_POST['menu_id'] = (int)$parent[0];
			$_POST['plid'] = (int)$parent[1];
			
			if($id_menu = M_MenuLink::Instance()->add($_POST))
				$this->redirect(M_Link::ToAdminMenu('itemslist', $_POST['menu_id']));
			
			$this->fields = $_POST;
			$messages = M_MenuLink::Instance()->errors();
		}
		else
		{
			$this->fields = array('link_path' => '', 
														'link_title' => '',
														'link_description' => '');
			$this->fields['parent'] = (int)$_GET['param'] . ':0';
		}
		
		$this->map =  M_Menu::Instance()->getMenuWithChildren();		
		
		$this->messages = $messages;	
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/menu/v_addlink.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Редактирование ссылки.
	//
	public function action_editlink()
	{
		$this->title .= '::Меню ссылки';
		
		$messages = array();	
		$mMenuLink =  M_MenuLink::Instance();
		
		if($this->isPost())
		{
			$parent = explode(':' ,$_POST['parent']);
			$_POST['menu_id'] = (int)$parent[0];
			$_POST['plid'] = (int)$parent[1];
			
			if($mMenuLink->edit($_POST['mlid'],$_POST))
				$this->redirect(M_Link::ToAdminMenu('itemslist', $_POST['menu_id']));
			
			$this->fields = $_POST;
			$messages = $mMenuLink->errors();
		}
		else
		{
			$mlid = isset($_GET['param']) ? (int)$_GET['param'] : 0;
			$this->fields = $mMenuLink->get($mlid);
			
			if (empty($this->fields))
				$this->p404();
			
			$this->fields['parent'] = $this->fields['menu_id'] . ':' . 
																$this->fields['plid'];
		}
		
		$this->map =  M_Menu::Instance()->getMenuWithChildren();		
		
		$this->messages = $messages;		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/menu/v_editlink.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Удаление ссылки из меню.
	//
	public function action_deletelink()
	{		
		$menu_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		$mlid = isset($_GET['param2']) ? (int)$_GET['param2'] : 0;
		M_MenuLink::Instance()->delete($mlid);
		if ($menu_id !=0)
			$this->redirect(M_Link::ToAdminMenu('itemslist', $menu_id));
		else 
			$this->redirect(M_Link::ToAdminMenu('all'));
	}
	
	//
	// Список ссылок меню
	//
	public function action_itemslist()
	{		
		$mMenu = M_Menu::Instance();
		$menu_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		
		if ($this->isPost())
		{
			M_MenuLink::Instance()->editItemList($_POST);
			$this->redirect(M_Link::ToAdminMenu('itemslist', $menu_id));
		}		
		
		$menu =  $mMenu->getMenuWithChildren($menu_id);
		
		if (empty($menu))
			$this->p404();
		
		$this->fields = $menu[0]; 
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/menu/v_itemslist.php');
		
		$this->content = $this->Template($templateName, $this);
		
	}
}