<?php 

class M_Templates
{
	protected $db;     // модуль для работы с бд
	protected $errors; //список ошибок
	private $valid;    //модуль валидации
	
	private static $instance; // экземпляр класса
	private $templatesDir;
	
	public static function Instance()
	{
		if(self::$instance == null)
			self::$instance = new self();
		
		return self::$instance;
	}
	
	public function __construct()
	{
		$this->templatesDir = TEMPLATE_DIR; // Путь к папке с шаблонами
		$this->db = M_MSQL::Instance();
	}
	
	// получает список шаблонов 
	// из папке определенной в $this->templatesDir
	// @return array()
	public function get_templates()
	{
		$files = array();
		
		if (is_dir($this->templatesDir))
		{
			$files = scandir($this->templatesDir);
			//$unset_s = array('.', '..'); 
			//$files = array_diff($files, $unset_s);
			$path = SITE_ROOT . '/' . $this->templatesDir;
			foreach ($files as $key => $value){
				if (in_array($value, array('.', '..')) || !is_dir($path . $value))
					unset($files[$key]);
			}

		}
		return $files;
	}
	
	// Получает имя текущего шаблона
	// @return string
	public function getCurrentTemplate()
	{
		$query = "SELECT option_value FROM options 
							WHERE option_name='template_name'";
		$res = M_MSQL::Instance()->Select($query);
		$template_name = isset($res[0]['option_value']) ? $res[0]['option_value'] : 'default_template';
			
		return $template_name;
	}	
	
	
	// Задает шаблона для сайта
	// $fields - массив значений
	// @return boolean
	public function setTemplate($fields)
	{
		$this->errors = array();
		
		$valid = $this->load_validation('options');
		$valid->execute($fields);
		if($valid->good()) 
		{			
			$clearObj = $valid->getObj();
			$where = "option_name = ? ";
			$res = $this->db->Update('options', $clearObj, $where, array('template_name'));
			if ($res === 1)
				return true;
			elseif ($res === 0)
			{				
				$clearObj['option_name'] = $template_name;
				return (bool)$this->db->Insert('options', $clearObj);				
			}
		}
		$this->errors = $valid->errors();
		return false;
	}
	
	protected function load_validation($table)
	{		
		$this->valid = null;			
		$this->valid = new M_Validation($table);		
		return $this->valid;
	}
	
	public function errors()
	{
		return $this->errors;
	}
	
	// Возращает массив имен блоков 
	// используемые в шаблоне
	// $template_name - string имя шаблона
	// @return array()
	public function getBlocksTemplate($template_name)
	{
		$sql = 'SELECT b.block_name FROM regions r
					INNER JOIN regions_blocks rb
						USING(region_id)
					INNER JOIN blocks b
						USING(block_id)
					WHERE template_name = ?
					ORDER BY weight';
					
		$blocks_names = $this->db->Select($sql, array($template_name));
		
		return !empty($blocks_names) ? $blocks_names : array();
	}
	
	// Возращает информацию по блокам
	// и местах размещения их в шаблоне 
	// $template_name - string имя шаблона
	// @return array()
	public function regionsBlocks($template_name)
	{
		$params = array($template_name); 
		
		$sql = "SELECT b.*, 0 as region_id, 0 as weight FROM blocks b WHERE block_id NOT IN(
								SELECT block_id FROM regions 
								INNER JOIN regions_blocks USING (region_id)
								WHERE template_name = ?) ";
		$blocks_free = $this->db->Select($sql, $params); 
								
		$sql2 = "SELECT b.*, rb.region_id, rb.weight FROM regions r
						INNER JOIN regions_blocks rb
							USING(region_id)
						INNER JOIN blocks b
							USING(block_id)
						WHERE template_name = ?
						ORDER BY region_id , weight";
		$blocks_in_reg = $this->db->Select($sql2, $params);
		
		$regions = array();
		
		foreach ($blocks_in_reg as $block)
			$regions[$block['region_id']][] = $block;
		
		$regions[0] =  $blocks_free;

		return $regions;
	} 
	
	// Обновляет расположение блоков
	// в шаблоне 
	// $template_name - string имя шаблона
	// $fields - массив значений
	// @return boolean
	public function updateRegionsBlocks($fields, $template_name)
	{
		$_fields = array();
		
		foreach ($fields as $key => $value)
		{
			if (substr_count($key, '_') != 1)
				continue;
			
			list($act, $block_id) = explode('_', $key);
			
			$block_id = abs((int)$block_id);
			$act = trim(strip_tags($act));
			
			if ($block_id <= 0) continue;
			
			$_fields[$block_id]['block_id'] = $block_id;
			
			if ($act == "region")
				$_fields[$block_id]['region_id'] = (int)$value;
			elseif ($act == 'weight')
				$_fields[$block_id]['weight'] = (int)$value;		
		}
		
		$values = array();
		
		foreach ($_fields as $key =>$val)
		{
			if ($val['region_id'] > 0)			
				$values[] = '('.$val['block_id'].','.$val['region_id'].','.$val['weight'].')';			
		}
		
		$values_s = "";
		$values_s = implode(',' , $values);			
		
		
		if ($this->db->beginTransaction())
		{
		$where = "region_id IN (SELECT region_id 
							FROM regions WHERE template_name = ?)";
			$sql = "SELECT COUNT(*) c FROM regions WHERE template_name = ?";
			$result = $this->db->Select($sql, array($template_name));
			
			if (isset($result[0]['c']) && $result[0]['c'] > 0)
				$this->db->Delete('regions_blocks', $where, array($template_name));
			
			if (!empty($values_s))
			{
				$sql2 = "INSERT INTO regions_blocks (block_id, region_id, weight) VALUES " . $values_s;
				$this->db->Execute($sql2);
			}
			if ($this->db->commit())
				return true;
			
			$this->db->rollBack();
		}
		
		return false;		
	}
	
}