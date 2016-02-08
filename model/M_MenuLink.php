<?php 

class M_MenuLink extends M_Model
{
	public $listItems = array();  // список всех элеменов в таблице (индексы id-ники)
	public $treeArray = array();  // иерархический список элементов
	private static $instance; // экземпляр класса
	private $children;
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new M_MenuLink();
		
		return self::$instance;
	}
	
	public function __construct()
	{
			// задаем имя таблицы и первичный ключ
			parent::__construct('menu_link', 'mlid');
	}
	
	// Получает элемент по первичному ключу 
	// @param $pk int первичный ключ 
	// @return array
	public function get($pk)
	{
		return parent::get($pk);
	}
	
	// Добавляет новый элемент
	// @param $fields array массив полей и значений
	// @return string первичный ключ
	// или false при неудачи 
	public function add($fields)
	{	
		// Проверка на существования родителя
		if (!$this->checkParent($fields))
			return false;
		
		$fields['link_path'] = ltrim($fields['link_path'], '/');
		
		return parent::add($fields);
	}

	// изменяет элемент
	// @param $pk int первичным ключем 
	// @param $fields array массив полей и значений
	// @return boolean (true успех
	// или при неудачи false)
	public function edit($pk, $fields)
	{	
		
		$item = $this->get($pk);
		
		if (empty($item))
			return false;
		
		$menu_before = $item['menu_id'];
		
		// Проверка на существования родителя
		if (!$this->checkParent($fields))
			return false;	
		
		if ($this->db->beginTransaction('menu_link_edit'))
		{
			if (parent::edit($pk, $fields))
			{	
				// Если изменено родительское меню
				if ($menu_before != $fields['menu_id'])
				{	
					// Получаем id всех потомков
					$children = $this->getChidlrenId();
					$count = count($children);
					if ($count > 0)
					{
						$placeholders = rtrim(str_repeat('?, ', $count), ', ');
						$obj = array('menu_id' => $fields['menu_id']);
						$where = "WHERE mlid IN($placeholder)"; 
						$this->db->Update($this->table, $obj, $where, $children);
					}
				}
				
				if ($this->db->commit('menu_link_edit'))
				{
					// Дерево изменилось, очищаем кэш
					$this->listItems = array();		
					$this->treeArray = array();					
					return true;
				}				
			}
			$this->rollBack('menu_link_edit');
		}
		return false;
	}
	
	// Возращает навигационных путь для ссылки (массив id предков)
	// @param $mlid int id-ссылки
	// @return array
	public function getItemsBreadCrumbs($mlid)
	{	
		$parents = array();
		
		if ($mlid > 0) 
		{
			$mlid = (int)$mlid;			
			$listItems = $this->getListAllItems();
			
			if (isset($listItems[$mlid]))
			{
				$path = $listItems[$mlid]['path'];
				$parentsIds = explode('-', $path);			
				
				foreach($parentsIds as $id)
				{
					if ((int)$id == 0) continue;			
					$parents[$id] = $listItems[$id];
				}
				
				//$parents[$mlid] = $listItems[$mlid];
			}
		}
		
		return $parents;
	}
	
	// Получает id всех потомков
	// @param $id int id-ссылки
	// @return array
	public function getChidlrenId($id)
	{
		$tree = $this->getArrayTree();
		$children = array();
		$this->getArrayChidlrenId($this->listItems, $id, $children);
		return $children;
	} 
	
	// удаляет элемент c первичным ключем $pk
	// потомков поднимает на свой уровень
	// @return boolean
	public function delete($pk)
	{
		$link = parent::get($pk);
		
		if (empty($link))
			return false;
		
		parent::delete($pk);
		
		M_Posts::Instance()->unlink_menu($pk);
		
		$obj = array('plid' => $link['plid']);
		$where = 'plid = ? ';
		$this->db->Update($this->table, $obj, $where, array($pk));		
		
		$this->listItems = array();		
		$this->treeArray = array();
		return true;
	}
	
	// Меняет сортировку элементов
	// @return boolean
	public function editItemList($fields)
	{
		foreach ($fields as $key => $value)
		{
			list($field_name, $mlid) = explode('_', $key);
			$obj = array( $field_name => (int)$value,);
			parent::edit($mlid, $obj);
		}
		
		$this->listItems = array();		
		$this->treeArray = array();
		
		return true;		
	}
	
	// Проверяет родителя для нового 
	// элемента
	// @return boolean (true 
	// или при неудачи false)
	private function checkParent($fields)
	{
		if (isset($fields['plid']) && $fields['plid'] != 0)
			$sql = "SELECT count(*) c FROM menu 
							INNER JOIN  menu_link 
							USING (menu_id)
							WHERE menu_id ={$fields['menu_id']}
							AND mlid = {$fields['plid']}";
		else 
			$sql = "SELECT count(*) c FROM menu 							
							WHERE menu_id ={$fields['menu_id']}";					
							
		$res = $this->db->Select($sql);
		
		if ($res[0]['c'] == 0)
		{
			$this->errors['parent'] = 'Некорректные данные для поля "Родительская ссылка"' ;
			return false;
		}
		return true;
	}	
	
	
	// Cтроить иерархическое дерево меню
	// записывает все id - детей в children
	public function getArrayChidlrenId($treeData, $idAncestor = 0, &$children)
	{
		$tree = array();
		if (is_int($idAncestor) && $idAncestor >= 0)
		{
			foreach($treeData as $item)
			{
				if ((int)$item['plid'] === (int)$idAncestor)
				{
					$mlid = (int)$item['mlid'];
					$children[] = $mlid;
					$this->getArrayChidlrenId($treeData, $mlid, $children);
				}				
			}
		}
		return ;
	}
	
	
	//
	// Cтроить иерархическое дерево меню
	// @return array or null
	private function buildHierarchyArrayTree($treeData, $idAncestor = 0, $path = 0)
	{
		$tree = array();
		if (is_int($idAncestor) && $idAncestor >= 0)
		{
			foreach($treeData as $item)
			{
				if ((int)$item['plid'] === (int)$idAncestor)
				{
					$mlid = (int)$item['mlid'];
					$this->listItems[$mlid] = $item;
					$this->listItems[$mlid]['path'] = $path;
					$item['children'] = $this->buildHierarchyArrayTree($treeData, $mlid, $path . '-' . $mlid);
					$tree[$mlid]= $item;
				}				
			}
		}
		return count($tree) === 0	? array() : $tree;
	}
	
	//
	// получает все ссылки из базы данных
	// @return array
	private function getTreeData()
	{
		$query = 'SELECT * FROM menu_link ORDER BY weight';
		return $this->db->Select($query);
	}
	
	// Возращает массив всех ссылок указанного меню
	// @param $menu_id int id-меню
	// return array()
	public function getItemsByMenu($menu_id)
	{
		$children = array();
		$tree = $this->getArrayTree();
		
		foreach ($tree as $item)
		{
			if ($item['menu_id'] == $menu_id) 
				$children[] = $item;
		}
		
		return $children;
	}
	
	// Получает всех потомков верхнего уровня
	// @param $menu_id int id-меню
	// @return array
	public function getTopItemsByMenu($menu_id)
	{
		$menu_id = (int)$menu_id;
		
		if ($menu_id == 0)
			return array();
		
		$query = "SELECT mlid, link_path, link_title, link_description 
							FROM menu_link WHERE plid = 0 AND menu_id = ? ORDER BY weight";
		
		return $this->db->Select($query, array($menu_id));
	}
	
	// Получает все ссылки 
	// и строит иерархический массив ссылок
	// @return array 
	public function getArrayTree()
	{
		if (empty($this->listItems)  ||
				empty($this->treeArray) )
		{
			$treeData = $this->getTreeData();
			$this->treeArray = $this->buildHierarchyArrayTree($treeData);
		}
		return $this->treeArray;
	}
	
	// Возращает массив всех ссылок
	// инсталирует свойства $this->listItems, $this->treeArray
	// @return array()
	public function getListAllItems()
	{
		$this->getArrayTree();
		return $this->listItems;
	}
	
	public function getTopChildren($mlid)
	{
		$result = array();
		if ($mlid > 0)
		{
			$query = "SELECT * FROM menu_link WHERE plid =" . (int)$mlid 
								. ' ORDER BY weight';
			$result = $this->db->Select($query);
		}
		
		return !empty($result) ? $result : array();
	}
	
}