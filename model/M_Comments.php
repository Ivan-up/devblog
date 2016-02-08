<?

class M_Comments 
{
	protected $db;	
	protected $errors; //список ошибок
	protected $tableData;
	protected $pkTableData;
	protected $tableTree;
	private $idSubject;
	private $treeFlat = array();
	private $validData;    //модуль валидации
	private static $instance;
	
	
	function __construct()
	{
		$this->db = M_MSQL::Instance();
		$this->tableData = 'comments'; 
		$this->pkTableData = 'comment_id';   // $idEntry
		$this->tableTree = 'commentsTree';
	}
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
		
		return self::$instance;
	}
	
	
	private function load_validation_data()
	{			
		if($this->validData == null)
			$this->validData = new M_Validation($this->tableData);
		
		return $this->validData;
	}
	
	
	public function edit($pk, $fields)
	{
		$this->errors = array(); // обнуляем список ошибок
		$valid = $this->load_validation_data(); //подгружаем модуль валидации
		
		$valid->execute($fields, $pk);
		
		if($valid->good())
		{
			$this->db->Update($this->tableData, $valid->getObj(), "{$this->pkTableData}=?", array($pk));
			return true;
		}
		
		$this->errors = $valid->errors();
		return false;
	}
	
	//
	// Устанавливать id комментируемого поста
	// @return object 
	public function setIdSubject($idSubject)
	{
		$this->idSubject =(int)$idSubject;
		return $this;
	}
	
	//
	// Возвращает установленный id комментируемого поста
	// @return int
	public function getIdSubject()
	{
		return $this->idSubject;
	}
	
	// Получает список ошибок при выполнение
	// @return array()
	public function errors()
	{
		return $this->errors;
	}
	
	// Добавляет комментарий
	// @params $fields ассоциативный массии
	// $idEntry комментария вслучаи ответа на другой
	// @return int or false  (id добавленного комментарий)
	public function add($fields, $idEntry  = 0)
	{
		$this->errors = array(); // обнуляем список ошибок
		$date = date('Y-m-d H:i:s');
		$fields['dateCreate'] = $date;
		$validData = $this->load_validation_data(); //подгружаем модуль валидации		
		$validData->execute($fields);
		
		if (!$validData->good())
		{
			$this->errors  = $validData->errors();
			return false;
		}
		
		if ($idEntry  >= 0)
		{	
			// Если задан родитель проверяем существование 
			// его и получам уровень вложенности
			$idEntry  = $this->hasEntry($idEntry) ? (int)$idEntry : 0;
			$level = $idEntry  > 0 ? $this->getLevel($idEntry) + 1 : 0;
		
			$idSubject =  $this->idSubject;
			
			if (empty($idSubject))
			{
				$this->errors['idSubject'] = "Не указана статья!!";
				return false;
			}
			
			$tableData = $this->tableData;
			$tableTree = $this->tableTree;
			
			// Вставляем в таблицы комментарий
			if ($this->db->beginTransaction('comments_add'))
			{
				$idNewComment = $this->db->Insert($tableData,  $validData->getObj()); 	
				
				if ($idNewComment > 0)
				{			
					$sql = "INSERT INTO $tableTree (idAncestor, idDescendant,
															idNearestAncestor, idSubject, level, dataCreate)
									SELECT idAncestor, $idNewComment, $idEntry, $idSubject, $level, '$date'
										FROM $tableTree
										WHERE idDescendant = {$idEntry}
									UNION ALL 
										SELECT $idNewComment, $idNewComment, $idEntry, $idSubject, $level, '$date'";
					$this->db->debugSqlInFile('log.txt', $sql);		
					if ($this->db->execute($sql) > 0 && $this->db->commit('comments_add'))		
						return $idNewComment;					
				}
				
				$this->db->rollBack('comments_add');
			}
		}
		return false;
	}
	
	//
	// Проверяет существования комментария $idEntry
	// @return boolean
	public function hasEntry($idEntry)
	{
		if ($idEntry > 0)
		{
			$sql = "SELECT COUNT(*) FROM {$this->tableTree}
							WHERE idDescendant =". $idEntry;
			
			$result = $this->db->Select($sql);
			return !empty($result[0]);
		}
		return false;
	}
	
	//
	// Возвращает уровень вложенности комментария 
	// @return int
	public function getLevel($idEntry)
	{
		 if ($idEntry > 0)
		 {
			  $sql = "SELECT level FROM {$this->tableTree}
									WHERE idAncestor = idDescendant
                           AND idDescendant = $idEntry";
	
				$result = $this->db->Select($sql);
				
				if (isset($result[0]['level'])) 
					return (int)$result[0]['level'];
		 }
		 
		 return 0;
	}
	
	//
	// Выбирает ветку комментариев из базы данных
	// @return array
	protected function getTreeData($idEntry)
	{
		$treeData = array();
		$where = array();
		$tableData = $this->tableData;
		$tableTree = $this->tableTree;
		
		if ($idEntry >= 0) 
		{
			if ($idEntry > 0) 			
				$where[] = "tableTree.idAncestor =" . (int)$idEntry;		

			if ($this->idSubject !== null) 		
				$where[] = "tableTree.idSubject  = " . (int)$this->idSubject;
			
			if (!empty($where))
			{
				$sql = "SELECT tableData.*,
											 tableTree.idAncestor,
											 tableTree.idDescendant,
											 tableTree.idNearestAncestor,
											 tableTree.level,
											 tableTree.idSubject
								FROM $tableData AS tableData
								JOIN $tableTree AS tableTree
									ON tableData.{$this->pkTableData} = tableTree.idDescendant
									WHERE " . implode(' AND ', $where) . "
								ORDER BY tableData.{$this->pkTableData} ASC";
		
				$result = $this->db->Select($sql);
				 //$array = array_combine(array_column($array, 'id'), array_values($array));
				foreach ($result as $item)			
					$treeData[$item[$this->pkTableData]] = $item;			
			}
		}
		
		return empty($treeData) ? array() : $treeData;
	}
	
	// формирует массив отсортированный в соотвествие с родителями
	// где каждый элемент представляет
	// ассоциативный массив комментария 
	// @param $treeData - массив элементов из базы данных
	// @param $idAncestor - начало выборки, при 0 получим все дерево
	// @return array or null
	private function buildTreeFlat($treeData, $idAncestor = 0)
	{
		if (is_int($idAncestor) && $idAncestor >= 0) 
		{
			foreach ($treeData as $item) 
			{
				if ((int)$item['idNearestAncestor'] === (int)$idAncestor || 
							count($this->treeFlat) == 0) 
				{                    
					$this->treeFlat[] = $treeData[(int)$item[$this->pkTableData]];   
					$this->buildTreeFlat($treeData, (int)$item[$this->pkTableData]);
				}
			}
		}

		return count($this->treeFlat) === 0 ? null : $this->treeFlat;
	}
	
	
	// Загружает дерево элементов 
	// @return array
	public function getTree($idEntry = 0)
	{
		$this->treeFlat = array();
		$treeData = $this->getTreeData($idEntry);
		$this->buildTreeFlat($treeData);
		return $this->treeFlat;
	}
	
	// Удаление комментария и его потомков
	// @param $idEntry 
	// @return int
	public function deleteBranch($idEntry)
	{
		if ($idEntry > 0 && $this->db->beginTransaction()) 
		{
			$idEntriesBranch = $this->getIdEntriesBranch($idEntry);
			$countEntries = count($idEntriesBranch);
			
			if ($countEntries > 0) 
			{
				$placeholders = rtrim(str_repeat('?, ', $countEntries), ', ');
				$where = "idDescendant IN( ". $placeholders  . ")";			

				if ($this->db->Delete($this->tableTree, $where, $idEntriesBranch ) > 0) 
				{
					$where = "comment_id IN( ". $placeholders  . ")";	
					if ($countDel = $this->db->Delete($this->tableData, $where, $idEntriesBranch) > 0)
					{						
						if ($this->db->commit()) return $countDel;
					}					
				}
			}

			$this->db->rollBack();
		}
		
		return 0;
	}
	
	// 
	// удаляет все сущности объекта idSubject
	// @return false or кол-во удаленных объектов
	public function deleteEntriesSubject($idSubject = null)
	{
		$idSubject = !empty($idSubject) ? (int)$idSubject : (int)$this->idSubject;
		$idEntries = $this->getIdEntriesSubject($idSubject);
		$countEntries = count($idEntries);
		
		if ($countEntries <= 0)
			return 0;
		
		if ($this->db->beginTransaction())
		{
			$where = "idSubject = ?";
			
			if ($this->db->Delete($this->tableTree, $where, array($idSubject)) > 0)
			{
				$placeholders = rtrim(str_repeat('?, ', $countEntries), ', ');
				$where = "{$this->pkTableData} IN( ". $placeholders  . ")";	
				$res = $this->db->Delete($this->tableData, $where, $idEntries);
				if ($res > 0 && $this->db->commit())
				{
					return $res;
				}						
			}
			$this->db->rollBack();		
		}
		
		return false;		
	}
	
	// Получает список всех id комментариев для Subject
	// @param $idSubject
	// @return array
	public function getIdEntriesSubject($idSubject)
	{
		$result = array();
		
		if ($idSubject > 0)
		{
			$sql = "SELECT DISTINCT idDescendant AS idEntry
															FROM {$this->tableTree}
														WHERE idSubject = $idSubject";
			$result = $this->db->Select($sql, null, PDO::FETCH_COLUMN);
		}
		
		return !empty($result) ? $result : array();
	}
	
	
	// Получает список всех id комментариев для ветки
	// @param $idЕntry 
	// @return array
	public function getIdEntriesBranch($idEntry)
	{
		$result = array();
		
		if($idEntry > 0)
		{
			$sql = "SELECT DISTINCT idDescendant AS idEntry 
                          FROM {$this->tableTree}
                         WHERE idAncestor = $idEntry";
			$result = $this->db->Select($sql, null, PDO::FETCH_COLUMN);
		}
		
		return !empty($result) ? $result : array();
	}
	
	public function getOneItem($idEntry)
	{
		$result = array();
		if($idEntry > 0)
		{
			$sql = "SELECT tableData.*,
									 tableTree.idSubject
						FROM {$this->tableData} AS tableData
						JOIN {$this->tableTree} AS tableTree
							ON tableData.{$this->pkTableData} = tableTree.idDescendant
							WHERE 
							tableData.{$this->pkTableData} = ?
							GROUP BY tableData.{$this->pkTableData}";
			$result = $this->db->Select($sql, array($idEntry));
		}
		return isset($result[0]) ? $result[0] : array();
	} 
	
	// формирует многоуровневый массив дерева комментариев
	// @param $treeData - массив элементов из базы данных
	// @param $idAncestor - начало выборки, при 0 получим все дерево
	// @return array or null
	private function buildHierarchyArrayTree($treeData, $idAncestor = 0)
	{
			$tree = array();

			if (is_int($idAncestor) && $idAncestor >= 0) {
					foreach ($treeData as $item) {
							if ((int)$item['idNearestAncestor'] === (int)$idAncestor) {
									$tree[] = array(
											'idEntry' => (int)$item['idEntry'],
											'data' => $treeData[(int)$item['idEntry']],
											'descendant' => $this->buildHierarchyArrayTree($treeData, (int)$item['idEntry'])
									);
							}
					}
			}

			return count($tree) === 0
					? null
					: $tree;
	}
	
	// Возращает количестов комментириев для Subject
	// @param $idSubject
	// @return int
	 public function countItemsBySubject($idSubject)
    {
        if ($idSubject > 0) {

            $sql = "SELECT COUNT(DISTINCT idDescendant) c
                      FROM {$this->tableTree} AS tableTree
                     WHERE tableTree.idSubject = $idSubject";

           $result = $this->db->Select($sql);
					
					if (isset($data[0]['c'])) return (int)$data[0]['c'];

        }

        return 0;
    }
	
	//
	// Возвращает кол-во комментариев для каждого subject
	// @return array
	public function countItemsByAllSubjects()
	{
		$sql = "SELECT DISTINCT idSubject, 
				(SELECT COUNT(DISTINCT idDescendant) FROM {$this->tableTree}
					WHERE idSubject = c1.idSubject) as countItems 
				FROM {$this->tableTree} c1";	
		$result = $this->db->Select($sql);
		$subject = array();
		
		foreach ($result as $item)
		{
			$subject[$item['idSubject']] = $item['countItems'];
		}
		
		return $subject;
	}
	
	//
	// Возвращает массив последних комментариев 
	// @param $quantity - сколько штук
	// @return array
	public function getLastestComments($quantity)
	{
		$comments = array();
		$quantity = (int)$quantity;
		
		if ($quantity > 0)
		{
			$sql = "SELECT t1.*, (SELECT idSubject FROM {$this->tableTree} 			
													WHERE idDescendant = {$this->pkTableData} LIMIT 1) 
													as idSubject 
							FROM {$this->tableData} t1 ORDER BY dateCreate DESC  LIMIT $quantity";
			$result = $this->db->Select($sql);
			$comments = !empty($result) ? $result : array();
		}
		
		return $comments;
	}
		
}
