<?php
//
// Базовый контроллер админок.
//
abstract class C_Admin_Base extends C_Controller
{
	public $title;   // Заголовок страницы
	public $content; // содержание страницы	
	public $styles = array();  // массив стилей
	public $scripts = array(); // массив скриптов
	public $adminSectionNav;
	public $template_name;         // имя текущего шаблона 
	
	protected $needLogin; // необходима ли авторизация
	protected $user;      //авторизированный пользователь || null
	protected $template;

	
	//
	// Конструктор
	//
	function __construct()
	{
		$this->needLogin = true;
		$this->styles = array('bootstrap', 'dashboard', 'video-js.min');
		$this->scripts = array('jquery-1.11.2.min', 'bootstrap.min', 'custom/main_script','video.min');
		$this->user = M_Users::Instance()->Get();
		
		$this->template_name = M_Templates::Instance()->getCurrentTemplate();
		$this->templateDir = BASE_URL . TEMPLATE_DIR . $this->template_name;
		$this->template = $this->getTemplate('v_admin.php');
		$this->getMediaTemplate(substr($this->templateDir, iconv_strlen(BASE_URL)) . '/media_admin.info');
	}
	
	protected function before()
	{		
		
		$this->access_init();
		
		$this->title = 'Админка';
		$this->content = '';
	}
	
	//
	// Проверка прав доступа
	//
	protected function access_init()
	{
		if ($this->needLogin) 
		{	
			if ($this->user === null)
			{
				$this->redirect(M_Link::ToAuth('login'));
				exit();
			}
			
			$priv = $this->getInfoAction();
		
			// Проверка привелегия для доступа 		
			if ($this->check_priv($priv) == false)
				die('Отказано в доступе');
		}
	}
	
	//
	// Генерация базового шаблона
	//
	public function render()
	{ 
		$page = $this->Template($this->template, $this);
		echo $page;
	}
	
	// Получаем пользователя
	public function getUserName()
	{	
		return isset($this->user['user_name']) ? $this->user['user_name'] : 'не зареган';
	}
	
}