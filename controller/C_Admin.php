<?php
//
// Контроллер главной страницы админки
// 
class C_Admin extends C_Admin_Base
{
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
		$this->title .= '::Панель управления';

		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/admin/v_index.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName);
	}	
	
}