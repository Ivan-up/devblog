<?php 

//
// Помощник работы с БД
//
class M_MSQL
{	
	private static $instance;
	private $db;
	
	// для возможности использования вложенных транзакций(эмуляция запусков)
	private $transaction_name = null; 
    
	private function __construct()
	{ 
			
			// Настройки подключения к БД.
			$host = MYSQL_SERVER;
			$port = DB_PORT;
			$user = MYSQL_USER; 
			$pass = MYSQL_PASSWORD;
			$dbName = MYSQL_DB;		
			$engine = 'mysql';
		
			$dns = $engine.":host=".$host.';port='.$port .';dbname='.$dbName; 
			$this->db = new PDO($dns, $user, $pass);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db->query("SET CHARACTER SET 'utf8'");
	} 	
	
	public static function Instance()
	{
		if(self::$instance == null)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	
    /**
     * Задаёт параметры полей таблицы в массив $this->fields
     * @param $table
     * @return $this
     */
		 /*
    protected function getFields($table)
    {
			if (!empty($table) && is_string($table) && empty($this->fields[$table])) 
			{
				if (!isset($this->stmt['showColumns'])) 
				{
					$this->stmt['showColumns'] = $this->db->prepare("SHOW COLUMNS FROM `{$table}`");
				}

				$sth = $this->stmt['showColumns'];

				if ($sth instanceof \PDOStatement) 
				{
					if ($sth->execute()) 
					{
						if ($fields = $sth->fetchAll(\PDO::FETCH_ASSOC)) 
						{
								$this->fields[$table] = $fields;
						}
					}
				}
			}

			return $this;
    }*/
	
	
    /**
     * Возвращает массив имён полей таблицы
     * @param $table
     * @return array
     */
  /*  protected function getFieldsName($table)
    {
        if (!empty($table) && empty($this->fieldsName[$table])) {
            foreach ($this->fields[$table] as $item) {
                $this->fieldsName[$table][] = $item['Field'];
            }
        }

        return !empty($this->fieldsName[$table])
            ?  $this->fieldsName[$table]
            : array();
    }*/

	
	
	
	
		
	//
	// Выборка строк
	// $query - полный текст SQL запроса
	// @return array - массив выбранных объектов
	//
	public function Select($query, $params = null, $fetchStyle = PDO::FETCH_ASSOC)
	{			
		$result = array();
		
		try
		{
			$sth = $this->db->prepare($query);		
			$sth->execute($params);
			$result= $sth->fetchAll($fetchStyle);
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
		
		return !empty($result) ? $result : array();
	}
	
	//
	// Вставка строки
	// $table - имя таблицы
	// $object - ассоциативный массив с парами вида "имя столбца - значение"
	// @return string - идентификатор вставленной строки
	//
	public function Insert($table, $object)
	{
		$columns = array();
		$values = array();
		$params = array();
		$table = $this->quote($table);
		
		foreach ($object as $key => $value)
		{
			$key = $this->quote($key);			
			$columns[] = $key;		
			$values[] = '?';
			$params[] = $value;
		}
		
		$columns_s = implode(',', $columns);
		$values_s = implode(',' , $values);
		$query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";	
		
		
		try
		{			
			$sth = $this->db->prepare($query);		
			$sth->execute($params);
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
		
		return $this->db->lastInsertId();		
	}	
	
	//
	// Изменение строк
	// $table - имя таблицы
	// $object - ассоциативный массив с парами вида "имя столбца - значение"
	// $where		- условие (часть SQL запроса)
	// $where_param - доп. параметры для where
	// @return int - число обновленных строк
	//
	public function Update($table, $object, $where, $where_param) 
	{
		$sets = array();
		$param = array();
		$table = $this->quote($table);
		
		foreach ($object as $key => $value) 
		{
			$key = $this->quote($key);
			$sets[] = "$key=?";
			$params[] = $value;
		}
		
		$sets_s = implode(',', $sets);
		$params_all = array_merge($params, $where_param);
		$query = "UPDATE $table SET $sets_s WHERE $where";
		
		try
		{			
			$sth = $this->db->prepare($query);
			$sth->execute($params_all);
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}		
			
		return $sth->rowCount();
	}
	
	//
	// Удаление строк
	// $table - имя таблицы
	// $where - условие (часть SQL запроса)
	// $where_param - доп. параметры для where
	// @return int - число удаленных строк
	//
	public function Delete($table, $where, $where_param)
	{
		$table = $this->quote($table);
		$query = "DELETE FROM $table WHERE $where";
		//$this->debugSqlInFile('log.txt', $query, $where_param);
		try
		{
			$sth = $this->db->prepare($query);
			$sth->execute($where_param);
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}
		
		return $sth->rowCount();
	}	
	
	//
	// Выполняет запрос
	// @return int - число затронутых строк
	public function Execute($query, $params = null)
	{
		try
		{
			$sth = $this->db->prepare($query);
			$sth->execute($params);
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}	
				
		return $sth->rowCount();
	}
	
	//
	// Запускает транзакцию
	// @return boolean
	public function beginTransaction($name = null)
	{
		if ($this->transaction_name == null)
		{			
			if (!empty($name))
				$this->transaction_name = $name;
			
			return $this->db->beginTransaction();
		}
		
	 return true;
	}
	
	//
	// Заканчивает транзакцию и Фиксирует изменения
	// @return boolean
	public function commit($name = null)
	{
		if ($this->transaction_name == null || $this->transaction_name == $name)
		{
			$this->transaction_name = null; 
			return $this->db->commit();
		}
		return true;
	}
	
	//
	// Заканчивает транзакцию и откатывает на состояние до транзакции
	// @return boolean
	public function rollBack($name = null)
	{
		if ($this->transaction_name == null || $this->transaction_name == $name)
		{
			$this->transaction_name == null;
			return $this->db->rollBack();
		}
		return false;
	}
	
	// Экранирует спец символы
	// $string - исходная строка 
	// $wrap - нужно ли обворачивать в кавычки
	// @return string
	public function quote($string, $wrap = false)
	{
		$string = $this->db->quote($string);
		
		if ($wrap === false)
			$string = trim($string, "'");
		
		return $string;
	}
	
	public function debugSqlInFile($file, $sql, $params = '')
	{
		file_put_contents($file, "\n" . $sql . ' --- ' .implode(', ', $params), FILE_APPEND);
	}
	
}