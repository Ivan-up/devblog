<?php 
//
// Базовый класс контроллера.
// 
abstract class C_Controller
{			
	protected $stylesTemplate = array();
	protected $scriptsTemplate = array();	
	protected $templateDir;
	protected $currController;
	protected $currAction;
	
	// Генерация внешнего шаблона
	protected abstract function render();
	
	//Функция отрабатывающая до основного метода
	protected abstract function before();
	
	public function Go($action, $controller) {
		$this->currAction = $action;
		$this->currController = $controller;
		$this->before();
		$this->$action();
		$this->render();
	}	
	
	//
	// Запрос произведен методом GET?
	//
	protected function IsGet() {
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}
	
	//
	// Запрос произведен методом POST?
	//
	protected function IsPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	//
	// Получаения пути к шаблону
	//
	protected function getTemplate($file)
	{		
		$template = SITE_ROOT .'/'. TEMPLATE_DIR . $this->template_name. '/'. $file;
		
		if (!is_file($template))
			$template = SITE_ROOT . '/' . TEMPLATE_DIR . 'default_template/' . $file;
		return $template;
	}
	
	//
	// Генерация HTML шаблона в строку.
	//
	protected function Template($fileName, $object = null){		
		if (is_array($object))
			extract($object);
		//Генерация HTML в строку
		ob_start();
		include "$fileName";
		return ob_get_clean();
	}
	
	// Если вызвали метод, которого нет - завершаем работу
	public function __call($name, $params){
		$this->p404();
	}	
	
	// Генерация шаблона 404
	public function p404(){
		$c = new C_Page();
		$c->Go('action_404', 'C_Page');
		die();
	}
	
	public function request($url){
		ob_start();
		
		if(strpos($url, 'http://') === 0 || strpos($url, 'https://'))
			echo file_get_contents($url);
		else{
			$rout = new M_Rout($_GET);
			$rout->Request();
		}
		
		return ob_get_clean();
	}
	
	// 
	// Выполняет переадресацию
	//
	protected function redirect($url)
	{
		header("location: $url");
		exit();
	}
	
	// 
	// проверка привелегий
	//
	public function check_priv($priv)
	{		
		return M_Users::Instance()->ActiveCan($priv);
	}
	
	// 
	// получить имя текущего контроллера
	//
	public function getCurrController()
	{
		return $this->currController;
	}
	
	// 
	// получить название активные контроллер::действие
	//
	protected function getInfoAction()
	{
		return $this->currController . ":" .  $this->currAction;
	}
	
	//
	// Загружает стили и скрипты шаблона
	//
	public function getMediaTemplate($filename)
	{
			
		if(!file_exists($filename))
			return false;

		@$file = fopen($filename, 'rb');

		if (!$file)
			return false;

		while ($params = fgetcsv($file, 1000, "="))
		{	
			$var_name = trim($params[0]) . 'Template';
			$var = null;
	
			if (in_array($var_name, array('stylesTemplate', 'scriptsTemplate')))
				$var = array_map('trim' ,explode(',', $params[1]));
			
			if (is_array($var)){ 
				$this->$var_name = array_merge($this->$var_name, $var);
			}
		}

		return true;
	}
	
	// 
	// Выполняет замену виджетов в шаблоне
	// на соотвествующий контент
	//
	protected function replace_widgets($str)
	{
		return preg_replace_callback(
			WIDGETS_REPLACE_PATTERN,
			function($matches)
			{
				$params = explode('/', $matches[2]);
				if ($params[0] != 'widget')
					return '';
				$action = 'action_' . strip_tags($params[1]);
				$c = new C_Widget($params);
				$content = $c->Go($action, 'C_Widget');
				
				unset($controller);
				return $content;
			},
			$str
		);
	}
	
}