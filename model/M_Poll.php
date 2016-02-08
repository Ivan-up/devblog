<?php 

class M_Poll extends M_Model
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
		parent::__construct('poll', 'pid');
	}
	
	public function add($fields)
	{
		$count = count($fields['answers']);
		$_fields['question'] = $fields['question'];
		$_fields2 = array();
		for ($i = 0; $count > $i; $i++)
		{
			if (empty($fields['answers'][$i]))
				continue;
			$_fields2[] = array('answer' => $fields['answers'][$i], 'weight' => $fields['weights'][$i]);
		}
		
		if (count($_fields2) < 2)
		{
			$this->errors['answers'] = 'Обязательно минимум 2 заполненых варианта ответа';
			return false;
		}
		
		if ($this->db->beginTransaction('poll_add'))
		{
			$pid = parent::add($_fields);
			if ($pid > 0)
			{
				$mPollA = M_PollAnswers::Instance();
				
				foreach ($_fields2 as $_field)
				{
					$_field['pid'] = $pid;
					$aid = $mPollA->add($_field);
					if ($aid == false) 
					{
						$this->errors = array_merge($this->errors, $mPollA->errors);
						break;
					}
				}
				
				if ($aid > 0 && $this->db->commit('poll_add'))
					return $pid;				
			}
			$this->db->rollBack('poll_add');
		}
		
		return false;
	}
	
	public function get($pid)
	{
		$pid = (int)$pid;
		$query = "SELECT p.pid, p.question, pa.aid, pa.answer, pa.weight 
								FROM poll p
							LEFT JOIN poll_answers pa
								USING(pid) 
							WHERE p.pid = $pid
							ORDER BY pa.weight";
		return $this->db->Select($query);
	}
	
	public function get_vote_poll($pid) 
	{
		$pid = (int)$pid;
		//$query = "SELECT COUNT(aid)"
	}
	
	public function edit($pk, $fields)
	{
		$fields_upd = array();
		$fields_del = array();
		$fields_add = array();
		
		foreach ($fields['answers'] as $key => $answer)
		{
			if (substr(trim($key), 0, 3) == 'new')
			{
				if (!empty($answer)) 
					$fields_add[] = array('answer' => $answer, 'weight' =>(int)$fields['weights'][$key], 'pid' => $fields['pid']);
			}
			elseif(is_numeric($key)) 
			{
				if (!empty($answer)) 
					$fields_upd[] = array('answer' => $answer, 'weight' =>(int)$fields['weights'][$key], 'aid' => (int)$key);
				else 
					$fields_del[] = $key;
			}
		}
		
		$count = count($fields_add) + count($fields_upd);
		
		//var_dump($fields_add, $fields_upd, $fields_del); die();
		
		if ($count < 2)
		{
			$this->errors['answers'] = 'Обязательно минимум 2 заполненых варианта ответа';
			return false;
		}
		
		if ($this->db->beginTransaction('poll_edit'))
		{
			parent::edit($pk, array('question' => $fields['question']));
			$mPollA = M_PollAnswers::Instance();
			$count2 = 0;
			foreach ($fields_add as $add)
			{
				if ($mPollA->add($add))
					++$count2;
			}
			
			foreach ($fields_upd as $upd)
			{
				if ($mPollA->edit($upd['aid'], $upd))
					++$count2;
			}
			
			foreach ($fields_del as $aid)
			{
				$mPollA->delete($aid);
			}
			
			if ($count2 >= 2 && $this->db->commit('poll_edit')) 
			 return true;
				
			$this->db->rollBack('poll_edit');	
		}
		return false;
	}
	
	public function delete($pk)
	{
		$pk = (int)$pk;
		$mPollAnswers = M_PollAnswers::Instance();
		$query = "SELECT pa.aid
								FROM poll p
							LEFT JOIN poll_answers pa
								USING(pid) 
							WHERE p.pid = ?";
		$result = $this->db->Select($query, array($pk), PDO::FETCH_COLUMN );
		
		if ($this->db->beginTransaction('poll_del'))
		{
			foreach ($result as $aid)			
				$mPollAnswers->delete($aid);				
			
			if (parent::delete($pk) && $this->db->commit('poll_del'))
				return true;
			
			$this->db->rollBack('poll_edit');	
		}
		return false;
	}
	
	public function get_result_poll($pid)
	{
		$pid = (int)$pid;
							
		$query = "SELECT p.pid, p.question, pa.aid, pa.answer, COUNT(pv.aid) as res 
								FROM poll_answers pa
							LEFT JOIN poll_vote pv USING (aid)								
							LEFT JOIN poll p USING (pid)
							WHERE pid = $pid
							GROUP BY pa.aid
							ORDER BY pa.weight";
							
		return $this->db->Select($query);
	}
	
	public function is_voted($pid, $user)
	{
		$pid = (int)$pid;
		$hostname = $this->db->quote($user['hostname'], true);
		$query = "SELECT COUNT(*) as res FROM poll_answers
							INNER JOIN poll_vote USING (aid)
							WHERE pid = $pid AND hostname = $hostname";
		
		if ($uid = (int)$user['uid'])
			$query .= " AND user_id = $uid";
		
		$result = $this->db->Select($query);
		
		return $result[0]['res'] > 0;
	}
	
	public function save_vote($fields, $user)
	{
		$_fields = array();
		$_fields['hostname'] =  filter_var($user['hostname'], FILTER_VALIDATE_IP);		
		
		$_fields['aid'] = (int)$fields['aid'];
		
		if ($_fields['hostname'] == false || 
				!M_PollAnswers::Instance()->check_answer($_fields['aid']))
			return false;
		
		if ($uid = (int)$user['uid'])
			$_fields['user_id'] = $user['uid'];
		
		return $this->db->Insert('poll_vote', $_fields);	
	}
}