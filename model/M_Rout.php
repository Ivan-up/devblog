<?php 

class M_Rout
{
	private $controller;
	private $action;
	private $params;
	
	public function __construct($get)
	{
		
		$this->action = 'action_';
		$this->action .= isset($get['action']) ? trim(strip_tags($get['action'])) : 'index';
		
		$controller = isset($get['c']) ? trim(strip_tags($get['c'])) : null;
		
		switch ($controller) {
			case 'page':
				$this->controller = 'C_Page';
				break;
			case 'admin':
				$this->controller = 'C_Admin';
				break;
			case 'users':
				$this->controller = 'C_Users';
				break;
			case 'comments':
				$this->controller = 'C_Comments';
				break;
			case 'posts':
				$this->controller = 'C_Posts';
				break;
			case 'menu':
				$this->controller = 'C_Menu';
				break;
			case 'gallery':
				$this->controller = 'C_Gallery';
				break;
			case 'video':
				$this->controller = 'C_Video';
				break;
			case 'audio':
				$this->controller = 'C_Audio';
				break;
			case 'auth':		
				$this->controller = 'C_Auth';
				break;
			case 'ajax':		
				$this->controller = 'C_Ajax';
				break;
			case 'templates':		
				$this->controller = 'C_Templates';
				break;
			case 'mailing':		
				$this->controller = 'C_Mailing';
				break;
			case 'poll':
				$this->controller = 'C_Poll';
				break;
			case 'login':
				$this->controller = 'C_Auth';
				$this->action = 'action_login';
				break;
			case 'search':
				$this->controller = 'C_Page';
				$this->action = 'action_search';
				break;
			default:
				$this->controller = 'C_Page';
		}
		
	}
	
	public function Request(){
		$c = new $this->controller();
		$c -> Go($this->action, $this->controller);
	}
}