<?php 

class M_Menu extends M_Model
{
	private static $instance; // экземпляр класса
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new M_Menu();
		
		return self::$instance;
	}
	
	public function __construct()
	{
			parent::__construct('menu', 'menu_id');
	}
	
	// Добавляет новое меню
	// @param $fields array массив полей и значений
	// @return int or false (успех id-меню)
	public function add($fields)
	{
		if ($this->db->beginTransaction('menu_add'))
		{
			$menu_id = parent::add($fields);
			$block_id = 0;
			if ($menu_id > 0)
			{
				$fields2 = array();
				$fields2['block_name'] = 'menu_' . $menu_id;
				$fields2['block_title'] = $fields['menu_title'];
				$fields2['block_desc'] = 'Меню';
				$block_id = M_Blocks::Instance()->add($fields2);				
			}
			
			if ($block_id > 0)			
				$res = $this->edit($menu_id, array('block_id' => $block_id));
			else 
				$res = false;
			
			if ($res === true && $this->db->commit('menu_add'))
				return $menu_id;
			
			$this->db->rollBack('menu_add');
		}
		return false;
	}
	
	// изменяет элемент
	// @param $pk int первичным ключем
	// @param $fields array массив полей и значений
	// @return boolean (true успех
	// или при неудачи false)
	public function edit($pk, $fields)
	{
		$menu = $this->get($pk);
		if ($this->db->beginTransaction('menu_edit'))
		{
			if (parent::edit($pk, $fields) == true)
			{
				if (isset($fields['menu_title']) && 
						$menu['menu_title'] != $fields['menu_title'])
				{
					M_Blocks::Instance()->edit($menu['block_id'], 
								array('block_title' => $fields['menu_title']));
				}
				
				if ($this->db->commit('menu_edit'))
					return true;
			}
			$this->db->rollBack('menu_edit');
		}
		return false;
	}
	
	// Удаляет меню 
	// @param $pk - первичный ключ
	// return boolean
	public function delete($pk)
	{
		$query = "SELECT count(*) c FROM menu_link WHERE menu_id = $pk";
		$children = $this->db->Select($query);
		
		if ($children[0]['c'] > 0)
		{
			$this->errors['has_children'] = "В меню находится". $children[0]['c'] . "элемента(ов) ";
			die($this->errors['has_children'] );			 
		}
		
		$menu = $this->get($pk);
	
		
		if ($this->db->beginTransaction('menu'))
		{
			if (parent::delete($pk) == 1)
			{
				M_Blocks::Instance()->delete($menu['block_id']);
				
				if ($this->db->commit('menu'))
					return true;
			}
			
			$this->db->rollBack('menu');
		}
		
		return false;
	}
	
	// Если предать $menu_id 
	// возвратит одно меню с элементами
	// иначе все меню
	public function getMenuWithChildren($menu_id = null)
	{
		$query = "SELECT menu_id, menu_title, menu_description
							FROM menu";
							
		if ($menu_id !== null)
			$query .= " WHERE menu_id =" . (int)$menu_id;
		
		$menu = $this->db->Select($query);			
		
		foreach ($menu as $key => $item) 	
			$menu[$key]['children'] = 
				M_MenuLink::Instance()->getItemsByMenu($item['menu_id']);
	
		return $menu;
	}
	
}