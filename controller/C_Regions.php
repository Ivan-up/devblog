<?php 

class C_Regions extends C_Admin_Base
{
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
	
	public function action_all()
	{
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/posts/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
	}
	
}