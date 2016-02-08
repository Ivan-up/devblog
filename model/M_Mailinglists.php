<?php 
class M_Mailinglists extends M_Model
{
	private static $instance;
	
	//
	// Получение единственного экземпляра(одиночка)
	//
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
		
		return self::$instance;
	}
	
	public function __construct()
	{
		parent::__construct('mailinglists', 'listid');
	}
	
	// подписывает пользователя
	public function subscribe($fields)
	{		
		$_fields['listid'] = isset($fields['listid']) ? (int)$fields['listid'] : 0;
		$_fields['email'] = isset($fields['email']) ? filter_var($fields['email'], FILTER_VALIDATE_EMAIL) : '';
		
		if (empty($_fields['listid']) || empty($_fields['email'])
					|| !$this->check_list($_fields['listid'])
					|| $this->is_subcribed($_fields['email'], $_fields['listid']))
			return false;
		
		return $this->db->Insert('mailinglists_users', $_fields);	
	}
	
	// отменяет подписку
	// @return int - число удаленных записей
	public function unsubscribe($fields)
	{
		$_fields['listid'] = isset($fields['listid']) ? (int)$fields['listid'] : 0;
		$_fields['email'] = isset($fields['email']) ? $fields['email'] : '';	
		
		$where = "listid = :listid AND email = :email";
		//var_dump($_fields); die();
		return $this->db->Delete('mailinglists_users', $where, $_fields);	
	}
	
	// проверяет существование листа рассылки 
	// @return boolean
	public function check_list($listid) 
	{
		$query = "SELECT COUNT(*) c FROM mailinglists WHERE listid =" . (int)$listid;
		$result = $this->db->Select($query);
		return $result[0]['c'] > 0;
	}
	
	// проверяет подписан ли пользователи на указанную рассылку 
	// @return boolean
	public function is_subcribed($email, $listid)
	{
		$query = "SELECT COUNT(*) c FROM mailinglists_users WHERE email = ? AND listid = ?";
		$result = $this->db->Select($query, array($email, $listid));
		return $result[0]['c'] > 0;
	}
	
	// получает на что подписан пользователь
	// @return array()
	public function get_user_subscr($email)
	{
		$result = array();
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if (!empty($email))
		{
			$query = "SELECT listid FROM mailinglists_users WHERE email = ?";
			$result = $this->db->Select($query, array($email), PDO::FETCH_COLUMN);
		}
		return empty($result) ? array() : $result;
	}
	
	// Получает список пользователей, которые не подписаны
	// на указанную рассылку 
	// @return array()
	public function get_user_not_subscr_list($listid)
	{
		$listid = (int)$listid;
		
		if (!$this->check_list($listid))
			return array();
		
		$query = "SELECT login FROM users 
								WHERE login NOT IN(
									SELECT email FROM mailinglists_users 
									WHERE listid = $listid)";
		$result = $this->db->Select($query);
		
		return empty($result) ? array() : $result;
	}
	
	// Считает количество подписчиков
	// для указанном листа рассылки
	// @return int
	public function count_subscribers_list($listid)
	{
		$query = "SELECT COUNT(*) FROM mailinglists_users 
							WHERE listid =".(int)$listid;
		$result = $this->db->Select($query, null, PDO::FETCH_COLUMN);
		return isset($result[0]) ? (int)$result[0] : 0;
	}
}