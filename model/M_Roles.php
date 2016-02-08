<? 
class M_Roles extends M_Model
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
		parent::__construct('roles', 'role_id');
	}
	
	// все роли с их привелегиями
	// @return array()
	public function getRolesWithPrivs()
	{
		$roles = $this->all();
		$roles_with_priv = array();		
		foreach($roles as $role)
		{
			$role_privs = $this->getRolePrivs($role['role_id']);
			$roles_with_priv[] = array('role' => $role, 
																'privs' => $role_privs);
		}
		return $roles_with_priv;
	}
	
	// получает роль с ее привелегиями
	// @return array()
	public function getRolePrivs($role_id)
	{
		$priv = array();
		$query = 	"SELECT * FROM privs_roles
								INNER JOIN privs 
								USING (priv_id)
								WHERE role_id =" . (int)$role_id;
		$res = $this->db->Select($query);
		if ($res != false) $priv = $res;
		return $priv;
	}
	
	// получает все привелегии
	// @return array()
	public function allPrivs()
	{
		$priv = array();
		$query = 	"SELECT priv_id, priv_name, priv_description 
								FROM privs ORDER BY priv_description";
		$res = $this->db->Select($query);
		if ($res != false) $priv = $res;
		return $priv;
	}
	
	// Обновление привелегий ролей
	// @return boolean
	public function updatePrivsAllRoles($fields)
	{
		$values = array();
		
		if (!array_key_exists('priv_1_1', $fields))
			$fields['priv_1_1'] = 'on';
		
		foreach ($fields as $key => $field)
		{
			list($act, $role_id, $priv_id) = explode('_', $key);
			$role_id  = abs((int)$role_id);
			$priv_id  = abs((int)$priv_id);
			if ($role_id != 0 && $priv_id != 0)
				$values [] = "($role_id, $priv_id)";			
		}
		$values_s = implode(',' , $values);
		
		if (empty($values_s))
			return false;
		
		$query = "INSERT INTO privs_roles(role_id, priv_id) VALUES " . $values_s;
		
		$this->db->Execute('TRUNCATE TABLE privs_roles');
		$this->db->Execute($query);		
		
		return true;
	}
	
	public function countUsersHasRole($role_id)
	{
		$query = "SELECT COUNT(*) FROM users WHERE role_id = ?";
		$result = $this->db->Select($query, array($role_id), PDO::FETCH_COLUMN);
		return $result;
	}
	
	public function delete($role_id)
	{
		$role_id = (int)$role_id;
		$this->db->beginTransaction('role_delete');
		$this->db->Delete('privs_roles', 'role_id = ?', array($role_id));
		parent::delete($role_id);
		$this->db->commit('role_delete');
	}
}