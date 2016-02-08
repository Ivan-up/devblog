<?php 

class C_Templates extends C_Admin_Base
{
	public $listTemplates = array();
	public $currentTemplate = 'default_template';
	public $messages = array();
	public $regions = array();
	public $blocks = array();
	
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
		$this->action_set();
	}
	
	public function action_set()
	{
		
		$mTemplates = M_Templates::Instance();
		$this->messages = array();
		$this->currentTemplate = $mTemplates->getCurrentTemplate();
		
		if ($this->isPost()) 
		{
			if (isset($_POST['templ']))
			{
				if ($mTemplates->setTemplate($_POST))
					$this->redirect(M_Link::ToAdminTemplates('set'));
			}
			elseif (isset($_POST['region']))
			{
				unset($_POST['region']);
				$mTemplates->updateRegionsBlocks($_POST, $this->currentTemplate);
			}
			
			$this->messages = $mTemplates->errors();
		}			

		$this->listTemplates = $mTemplates->get_templates();		
		$this->regions = M_Regions::Instance()->getByTemplate($this->currentTemplate);		
		$this->blocks = $mTemplates->regionsBlocks($this->currentTemplate);		
		
		//var_dump($this->regions, $this->blocks); die();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/templates/v_set_template.php', $this);
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
	}
	
}