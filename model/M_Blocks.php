<?php 
class M_Blocks extends M_Model
{
	private static $instance;
	
	//
	// Получение единственного экземпляра (одиночка)
	//
	public static function Instance()
	{
		if(self::$instance == null)
			self::$instance = new self();
			
		return self::$instance;
	}
	
	function __construct()
	{
		parent::__construct('blocks', 'block_id');
	}
	
	// Получение всех блоков
	// @return array()
	public function all()
	{
		$blocks_res = array();
		$blocks = parent::all();
		foreach ($blocks as $block)
		{
			$blocks_res[$block['block_id']] = $block;
		}
		return $blocks_res;
	}
	
	// Удаление блока 
	// @param $block_id - номер удаляемого блока 
	// @return boolean
	public function delete($block_id)
	{
		if ($block_id <= 0)
			return false;
		
		if ($this->db->beginTransaction('block'))
		{
			$where = "block_id = ?";
			$this->db->Delete('regions_blocks',$where, array($block_id));
			
			if (parent::delete($block_id) == 1 && $this->db->commit('block'));
				return true;
				
			$this->db->rollBack('block');
		}
		return false;
	}
}