<?php
//
// Контроллер управления пользователями.
//
class C_Users extends C_Admin_Base
{	
	public $users;
	public $navParams;
	public $navBar;
	public $messages;
	public $roles;
	public $fields = array('login' => '','user_name' => '',
												 'role_id' => '', 'user_id' => '');
	public $privs;
	public $roles_with_privs;
	public $tabs;
	
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
		
		// Подготавливаем подменю 
		$action = $this->getInfoAction();
		$privs = M_Users::Instance()->GetPrivs();
		// Определяем активную вкладку
		$tabs_users = array('C_Users:action_index', 'C_Users:action_all', 'C_Users:action_add');
		$tabs_roles = array('C_Users:action_allroles', 'C_Users:action_addroles', 'C_Users:action_allroles_privs');
		$tabs['active'] = "";
		$tabs['empty'] = array();
		if (in_array($action, $tabs_users))
			$tabs['active'] = 'users';
		elseif (in_array($action, $tabs_roles))
			$tabs['active'] = 'roles';
			
		$count_u = 0;
		$count_r = 0;
		
		foreach ($tabs_users as $priv)		
			 if ($this->check_priv($priv)) ++$count_u;
		
		foreach ($tabs_roles as $priv2)		
			 if ($this->check_priv($priv2)) ++$count_r;
		
	
		
		if ($count_u == 0)
			$tabs['empty'][] = 'users';
		
		if ($count_r == 0)
			$tabs['empty'][] = 'roles';
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_tabs.php');		
		// Формирование контента страницы 
		$this->tabs = $this->Template($templateName, $tabs);		
	}
	
	public function action_index()
	{
		$this->action_all();
	}
	
	//
	// Все пользователи.
	//
	public function action_all()
	{
		$this->title .= ' :: Пользователи';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination  = new M_Pagination('users', M_Link::ToAdminUsers('all'));
		
		// Задание свойств объекта 
		$this->users = $mPagination->fields("users.user_id, users.login, users.user_name,
																				roles.role_name, 
																				GROUP_CONCAT(privs.priv_description 
																				ORDER BY privs.priv_description SEPARATOR '; ') allprivs")
															 ->left_join('roles using(role_id)')
															 ->left_join('privs_roles using(role_id)')
															 ->left_join('privs using(priv_id)')
															 ->group_by('users.login')
															 ->order_by('users.user_id')
															 ->on_page(4)->page_num($page_num)->page();
										
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Добавление пользователя.
	//
	public function action_add()
	{
		$this->title .= 'Пользователи';
		$mUsers = M_Users::Instance();
		
		$messages = array();
	
		
		if($this->isPost())
		{
			if($mUsers->add($_POST))
				$this->redirect(M_Link::ToAdminUsers("all"));
			
			$messages = $mUsers->errors();			
			$this->fields = $_POST;
		}		
		
		$this->messages = $messages;
		$this->roles = M_Users::Instance()->getRoles();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_add.php');
		
		$this->content = $this->Template($templateName, $this);		
	}
	
	//
	// Редактирования пользователя.
	//
	public function action_edit()
	{
		$this->title .= ' :: Пользователи';
		$mUsers = M_Users::Instance();
		$messages = array();
		$id_user = isset($_GET['param']) ? $_GET['param'] : 0;
		
		if($this->isPost())
		{
			if($mUsers->edit($_POST['user_id'], $_POST))
			{
				$this->redirect(M_Link::ToAdminUsers('all'));
			}
			
			$messages = $mUsers->errors();
			$this->fields = $_POST;
		}
		else
		{
			$this->fields = $mUsers->get($id_user);
			if (empty($this->fields))
				$this->p404();
		}
		
		$this->fields['user_id'] = $id_user;
		
		$this->messages = $messages;
		$this->roles = $mUsers->getRoles();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Удалить пользователя
	//
	public function action_delete()
	{
		$id_user = $_GET['param'];
		
		if ($id_user > 1)
			M_Users::Instance()->delete($id_user);
		
		$this->redirect(M_Link::ToAdminUsers('all'));
	}
	
	//
	// Добавление роли.
	//
	public function action_addrole()
	{
		$this->title .= 'Роли';
		$mRoles = M_Roles::Instance();
		
		$messages = array();		
		
		if($this->isPost())
		{
			if($mRoles->add($_POST))
				$this->redirect(M_Link::ToAdminUsers('allroles'));
			
			$messages = $mRoles->errors();			
			$this->fields = $_POST;
		}	
		else
			$this->fields = array('role_name' => '', 
											'role_description' => '');
		
		$this->messages = $messages;	
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_add_role.php');
		
		$this->content = $this->Template($templateName, $this);		
	}
	
	//
	// Редактирования роли.
	//
	public function action_editrole()
	{
		$this->title .= ' :: Роли';
		$mRoles = M_Roles::Instance();
		$messages = array();
		$role_id = isset($_GET['param']) ? $_GET['param'] : 0;
		
		if($this->isPost())
		{
			if($mRoles->edit($_POST['role_id'], $_POST))
			{
				$this->redirect(M_Link::ToAdminUsers('allroles'));
			}
			
			$messages = $mRoles->errors();
			$this->fields = $_POST;
		}
		else
		{
			$this->fields = $mRoles->get($role_id);
			if (empty($this->fields))
				$this->p404();
		}
		$this->fields['role_id'] = $role_id;
		
		$this->messages = $messages;		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_edit_role.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Удаление роли.
	//
	public function action_deleterole()
	{
		$this->title .= ' :: Роли';	
		$messages = array();
		$role_id = isset($_GET['param']) ? $_GET['param'] : 0;
		$mRoles = M_Roles::Instance();
		
		if ($role_id > 2)
		{
			$count = $mRoles->countUsersHasRole($role_id);
			
			if ($count[0] == 0)
			{
				$mRoles->delete($role_id);
				$this->redirect(M_Link::ToAdminUsers('allroles'));
			}
				
			$messages['role'] = 'Нельзя удалить роль, есть пользователи с этой ролей - ' . $count[0];	
		}
		else 		
			$messages['role'] = 'Нельзя удалить эту роль!!!';
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_delete_role.php');
		
		$this->content = $this->Template($templateName, array('messages' => $messages, 'tabs' => $this->tabs));
	}
	
	//
	// Список все ролей.
	//
	public function action_allroles()
	{
		$this->title .= ' :: Пользователи';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination  = new M_Pagination('roles', M_Link::ToAdminUsers('allroles'));
		
		// Задание свойств объекта 
		$this->roles = $mPagination->fields('role_id, role_name, role_description')
										->on_page(5)->page_num($page_num)->page();
										
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_all_roles.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);		
	}
	
	//
	// Список всех привелегий всех ролей.
	//
	public function action_allroles_privs()
	{
		$mRoles = M_Roles::Instance();		
		
		if ($this->isPost())
		{
			$mRoles->updatePrivsAllRoles($_POST);
			$this->redirect(M_Link::ToAdminUsers('allroles_privs'));
		}
		
		$this->roles_with_privs = $mRoles->getRolesWithPrivs();
		$this->privs = $mRoles->allPrivs();
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/users/v_allroles_privs.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);		
		
	}	
	
}