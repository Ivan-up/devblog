<?php 
//
// Контроллер страницы чтения.
//

class C_Auth extends C_Base
{
	public $fields;
	public $messages;
	public $success;
	
	//
	// Конструктор
	//
	public function __construct()
	{
		parent:: __construct();
	}
	
	public function before()
	{
		parent::before();
	}
	
	//
	// Авторизация
	//
	public function action_login()
	{
		$this->title = 'Авторизация';
		$this->breadCrambsActive = $this->title;
		$message = '';
		$email = '';
		$mUsers = M_Users::Instance();
		
		$user = $mUsers->Get();		
	
		if (empty($user) && $this->isPost())
		{
			if ($mUsers->Login($_POST['login'], $_POST['password'], isset($_POST['remember'])))
			{
				$url = $this->referer_link();					
				$this->redirect($url);				
			} 
			else 
			{
				$email = strip_tags($_POST['login']);
				$message = "Не верный логин или пароль";
			}
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/auth/v_login.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName, array('user'=>$user, 'message' => $message, 'email' => $email));
	}
	
	// определяет адрес для перенаправления при 
	// авторизации и выходе 
	// @return string
	private function referer_link()
	{
		$url = '';	
				
		if (isset($_SERVER['HTTP_REFERER']))
			$url = (strpos($_SERVER['HTTP_REFERER'], 'http://' . $_SERVER['SERVER_NAME']) === 0) ? 
								$_SERVER['HTTP_REFERER'] : '';
		
		if (!$url)
			$url = M_Link::ToPage();
		
		return $url;
	}
	
	//
	// Выход с аккаунта
	//
	public function action_logout()
	{
		$this->title = 'Выход';
		$this->breadCrambsActive = $this->title;
		$mUsers = M_Users::Instance();
		$user = $mUsers->Get();
		if (!empty($user))
			$mUsers -> Logout();
		
		$url = $this->referer_link();
		
		$this->redirect($url);
	}
	
	//
	// Регистрация
	//
	public function action_registr()
	{
		$this->title = 'Регистрация';
		$this->breadCrambsActive = $this->title;
		$mUsers = M_Users::Instance();
		$user = $mUsers->Get();
		
		if (!empty($user))
			$mUsers -> Logout();
		
		if ($this->isPost()) 
		{	
			$_POST['role_id'] = 2;
			if ($post_id = $mUsers->add($_POST))
			{
				$this->redirect(M_Link::ToAuth('success'));
			}
			
			$this->fields = $_POST;
			$this->messages = $mUsers->errors();
		}
		else
		{
			$this->fields = array_fill_keys(array('login', 'user_name'), "");
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/auth/v_registr.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName, $this);		
	}
	
	//
	// Сообщение успешной регистрации
	//
	public function action_success()
	{
		$this->title = 'Успешная регистрация';
		$this->breadCrambsActive = $this->title;
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/auth/v_success.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName);
	}
	
	//
	// Восстановление пароля
	//
	function action_forgetpass()
	{
		$this->title = ':: Восстановление пароля';
		$this->breadCrambsActive = $this->title;
		$mUsers = M_Users::Instance();
		$user = $mUsers->Get();
		$messages = array();
		$has_changed = false;
		
		if (!empty($user))
			$this->redirect(M_Link::ToAuth('login'));
		
		if ($this->isPost() && isset($_POST['forgetpass']))
		{
			if ($mUsers->reset_password($_POST['login']))
			{
				$_SESSION['has_changed'] = true;
				$this->redirect(M_Link::ToAuth('forgetpass'));				
			}				
			else
				$messages = $mUsers->errors();
		}
		
		if (isset($_SESSION['has_changed'])) 
		{
			$has_changed = $_SESSION['has_changed'];
			unset($_SESSION['has_changed']);
		}
			
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/auth/v_forgetpass.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName,	array(
																											'has_changed' => $has_changed, 
																											'messages' => $messages)
																										);
	}
	
	//
	// Настройки профиля
	//
	public function action_account()
	{
		$this->title = 'Настройки профиля';
		$this->breadCrambsActive = $this->title;
		$mUsers = M_Users::Instance();
		$user = $mUsers->Get();
		
		if (empty($user))
			$this->redirect(M_Link::ToAuth('login'));
		
		$this->success = '';		
		$this->fields['user_name'] = $user['user_name'];
		$this->fields['mimemail'] = $user['mimemail'];
		
		if ($this->isPost())
		{
			// Если меняем пароль
			if (isset($_POST['changepass'])) 
			{
				if ($mUsers->change_pass($user, $_POST))
				{
					$_SESSION['suc_account'] = "Пароль успешно изменен";
					
					$this->redirect(M_Link::ToAuth('account'));
				}
				$this->messages = $mUsers->errors();
			}
			// Смена других настроек
			if (isset($_POST['profile'])) 
			{
				if ($mUsers->edit($user['user_id'],$_POST))
				{
					$_SESSION['suc_account'] = "Изменения сохранены";
					$this->redirect(M_Link::ToAuth('account'));
				}				
				$this->messages = $mUsers->errors();
			}
		}
		
		//Флаг вывода при успешном изменении настроек
		if (isset($_SESSION['suc_account']))
		{
			$this->success = $_SESSION['suc_account'];
			unset($_SESSION['suc_account']);
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/auth/v_account.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName,	$this);
		
	}

}