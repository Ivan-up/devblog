<?php 

class M_Model
{
	protected $table;  // имя таблицы
	protected $pk;     // первичный ключ
	protected $db;     // модуль для работы с бд
	protected $errors; //список ошибок
	private $valid;    //модуль валидации
	
	public function __construct($table, $pk)
	{
		$this->table = $table;
		$this->pk = $pk;
		$this->errors = array();
		$this->valid = null;
		$this->db = M_MSQL::Instance();
	}
	
	// 
	// Получить все записи
	// @return array()
	public function all()
	{
		$query = "SELECT t.* FROM {$this->table} t";
		return $this->db->Select($query);
	}
	
	//
	// Получить одну запись 
	// @param $id - номер записи 
	// @return array()
	public function get($id)
	{
		$id = (int)$id;
		$query = "SELECT t.* FROM {$this->table} t WHERE {$this->pk} = '$id'";
		$res = $this->db->Select($query);
		return isset($res[0])? $res[0] : array();
	}
	
	//
	// Добавляет нову запись
	// @return id-новой записи или false
	//
	public function add($fields)
	{	
		$this->errors = array(); // обнуляем список ошибок
		$valid = $this->load_validation(); //подгружаем модуль валидации
		
		$valid->execute($fields);
		
		if($valid->good())
			return $this->db->Insert($this->table, $valid->getObj());
		
		$this->errors = $valid->errors();
		
		return false;
	}
	
	//
	// Изменяет запись
	// @return boolean
	//
	public function edit($pk, $fields)
	{
		$this->errors = array(); // обнуляем список ошибок
		$valid = $this->load_validation(); //подгружаем модуль валидации
		
		$valid->execute($fields, $pk);
		if($valid->good())
		{
			$this->db->Update($this->table, $valid->getObj(), "{$this->pk}=?", array($pk));
			return true;
		}
		$this->errors = $valid->errors();
		return false;
	}
	
	
	//
	// Удаляет запись
	// @return int - число удаленных строк
	//
	public function delete($pk)
	{
		$pk = (int)$pk;		
		return $this->db->Delete($this->table, "{$this->pk} =?", array($pk));
	}

	// Получить массив всех ошибок
	// @return array
	public function errors()
	{
		return $this->errors;
	}
	
	// Подключаем модуль валидаци 
	// @return object
	protected function load_validation()
	{
		if($this->valid == null)
			$this->valid = new M_Validation($this->table);
		
		return $this->valid;
	}
	
	protected function setTable($table, $pk)
	{
		$this->table = $table;
		$this->pk = $pk;
		$this->errors = array();
		$this->valid = null;
	}
}