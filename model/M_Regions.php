<?php 
class M_Regions extends M_Model
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
		parent::__construct('regions', 'regions_id');
	}
	
	public function getByTemplate($template_name)
	{
		$sql = "SELECT region_id, region_name, region_title, 
							region_desc, region_weight
						FROM regions WHERE template_name = ?
						UNION ALL 
						SELECT	0, 'not_region', 'Отключены' , '0', 51";
		
		$regions = $this->db->Select($sql, array($template_name));			
		
		return !empty($regions) ? $regions : array();
	}	
	
}