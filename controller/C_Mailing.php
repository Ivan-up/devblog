<?
//
// Контроллер рассылок
//
class C_Mailing extends C_Admin_Base
{
	public $fields;
	public $navParams;
	public $navBar;
	public $maillists;
	public $messages;
	public $lists;
	public $pages;
	public $unsubscr_users;
	public $inner_nav;
	
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
		
		$active = $this->currAction;
		$unsent = M_Mail::Instance()->count_unsent_mail();
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_inner_nav.php');		
		// Формирование контента страницы 
		$this->inner_nav = $this->Template($templateName, array('active' => $active, 'unsent' => $unsent));		
	}
	
	public function action_index()
	{
		$this->action_all();
	}
	
	//
	// Получить список рассылок.
	//
	public function action_all() 
	{
		$this->title .= '::Рассылки';
		
		//получение номера страницы
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mailinglists m', M_Link::ToAdminMailing('all'));
		
		//задание свойств объекта
		$this->pages = $mPagination->fields("m.listid, m.listname, m.blurb, m.is_show, COUNT(mu.email) as subscr")
															 ->left_join("mailinglists_users mu USING(listid)")
															 ->group_by('m.listid')
															 ->on_page(5)
															 ->page_num($page_num)
															 ->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_all.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Добавление рассылки.
	//
	public function action_add()
	{
		$this->title .= '::Рассылка';
				
		if ($this->isPost())
		{
			if ($listid = M_Mailinglists::Instance()->add($_POST))
				$this->redirect(M_Link::ToAdminMailing('all'));
			
			$this->fields = $_POST;
			$this->messages = M_Mailinglists::Instance()->errors();
		}
		else 
		{
			$this->fields = array_fill_keys(array('listname', 'is_show', 'blurb'), '');
			$this->messages = array();
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Редактирование рассылки.
	//
	public function action_edit()
	{
		$this->title .= '::Рассылка';
				
		if ($this->isPost())
		{
			if ($listid = M_Mailinglists::Instance()->edit($_POST['listid'], $_POST))
				$this->redirect(M_Link::ToAdminMailing('all'));
			
			$this->fields = $_POST;
			$this->messages = M_Mailinglists::Instance()->errors();
		}
		else 
		{
			$listid = isset($_GET['param'])? $_GET['param'] : 0;
			$this->fields =  M_Mailinglists::Instance()->get($listid);
			
			$this->messages = array();
			if (empty($this->fields))
				$this->p404();
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Добавление письма в рассылку.
	//
	public function action_addmail()
	{
		$this->title .= '::Письмо';
		$this->scripts[] = 'custom/init_maling_addmail';
		
		$this->lists = M_Mailinglists::Instance()->all();		
		
		if ($this->isPost())
		{
			$user = M_Users::Instance()->Get();
			$_POST['email'] = $user['login'];
			$_POST['status'] = 'STORED';

			if ($listid = M_Mail::Instance()->addmail($_POST, $_FILES))
				$this->redirect(M_Link::ToAdminMailing('viewmail'));
			
			$this->fields = $_POST;
			$this->messages =  M_Mail::Instance()->errors();
		}
		else 
		{
			$this->fields = array_fill_keys(array('subject', 'listid'), '');
			$this->messages = array();
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_addmail.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Просмотр состояние неоправленных писем.
	//
	public function action_viewmail()
	{
		$this->title .= '::Письма';
		
		//получение номера страницы
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mail m', M_Link::ToAdminMailing('viewmail'));
		
		//задание свойств объекта
		$this->pages = $mPagination->fields("m.mail_id, m.listid, m.subject, m.status, m.email, 
																					ml.listname, COUNT(mu.email) as subscr")
															 ->join('mailinglists ml USING(listid)')
															 ->left_join("mailinglists_users mu USING(listid)")
															 ->group_by('m.mail_id')
															 ->on_page(5)
															 ->page_num($page_num)
															 ->where("status != 'SENT'")
															 ->page();
															 
															 
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_viewmail.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Список отправленных писем для указанной рассылки.
	//
	public function action_archive()
	{
		$this->title .= '::Письма';
		
		//получение номера рассылки
		$listid = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		
		if ($listid == 0)
			$this->p404();
		
		//получение номера страницы
		$page_num = isset($_GET['param2']) ? (int)$_GET['param2'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mail', M_Link::ToAdminMailing('archive', $listid));
		
		//задание свойств объекта
		$this->pages = $mPagination->on_page(5)->page_num($page_num)->join('mailinglists USING(listid)')->where("status = 'SENT' AND listid = $listid")->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		$this->maillists = M_Mailinglists::Instance()->get($listid);
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_archive.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Отправка письма.
	//
	public function action_send()
	{		
		$mail = M_Mail::Instance()->send($_GET['param']);
		
		if ($mail['status'] == 'SENT')
			$this->redirect(M_Link::ToAdminMailing('archive', $mail['listid']));
		
		$this->redirect(M_Link::ToAdminMailing('viewmail'));
	}
	
	//
	// Список подписчиков для указанной рассылки.
	// добавление, исключение подписчиков
	//
	public function action_subscribers()
	{
		$this->title .= '::Подписчики';
				
		//получение номера рассылки
		$listid = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		
		if ($listid == 0)
			$this->p404();		
		
		if ($this->isPost())
		{			
			if (isset($_POST['unsubscr']))
			{
				M_Mailinglists::Instance()->unsubscribe($_POST);
			}
			else if(isset($_POST['subscr']) && isset($_POST['emails']) && is_array($_POST['emails']))
			{	
				$fields['listid'] = $listid;
				foreach ($_POST['emails'] as $email)
				{
					$fields['email'] = $email; 
					M_Mailinglists::Instance()->subscribe($fields);
				}							
			}
			
			$this->redirect(M_Link::ToAdminMailing('subscribers', $listid));
		}
		
		//получение номера страницы
		$page_num = isset($_GET['param2']) ? (int)$_GET['param2'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mailinglists_users', M_Link::ToAdminMailing('subscribers', $listid));
		
		//задание свойств объекта
		$this->pages = $mPagination->on_page(5)->page_num($page_num)->join('mailinglists USING(listid)')->where("listid = $listid")->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		$mMailinglists = M_Mailinglists::Instance();
		$this->unsubscr_users = $mMailinglists->get_user_not_subscr_list($listid);
		$this->maillists = $mMailinglists->get($listid);
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/mailing/v_subscribers.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Удаление письма.
	//
	public function action_deletemail()
	{
		$list_id = isset($_GET['param']) ? $_GET['param'] : 0;
		$mail_id = isset($_GET['param2']) ? $_GET['param2'] : 0;
		
		if (!$mail_id || !$list_id)		
			$this->p404();		
		
		M_Mail::Instance()->delete_mail($list_id, $mail_id);
		$this->redirect(M_Link::ToAdminMailing('viewmail'));
	}
}