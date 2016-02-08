<?php 
class M_Options extends M_Model
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
		parent::__construct('options', 'option_name');
	}
	
}