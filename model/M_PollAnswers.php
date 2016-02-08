<?php 

class M_PollAnswers extends M_Model
{
	private static $instance;
	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
		
		return self::$instance;
	}
	
	function __construct()
	{
		parent::__construct('poll_answers', 'aid');
	}
	
	public function delete($pk)
	{
		$pk = (int)$pk;
		$where = "aid = ?";
		$this->db->delete('poll_vote', $where, array($pk));
		return parent::delete($pk);
	}
	
	public function check_answer($aid)
	{
		$query = "SELECT COUNT(*) c FROM poll_answers WHERE aid =" . (int)$aid;
		$result = $this->db->Select($query);
		return $result[0]['c'] > 0;
	}
	
}