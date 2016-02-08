<?php 
//
// Контроллер администратирования опросов.
//
class C_Poll extends C_Admin_Base
{
	public $fields;
	public $messages;
	public $polls;
	public $poll;
	public $show_result;
	
	//
	// Конструктор
	//
	public function __construct()
	{
		parent::__construct();
	}
	
	public function before()
	{
		parent::before();
	}
	
	public function action_index()
	{
		$this->action_all();
	}
	
	//
	// Список всех опросов.
	//
	public function action_all()
	{
		$this->title .= '::Голосование';
		
		//получение номера страницы
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('poll', M_Link::ToAdminMenu('all'));
		
		//задание свойств объекта
		$this->polls = $mPagination->on_page(5)->page_num($page_num)->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/poll/v_all.php');
		
		$this->content = $this->Template($templateName, $this);
	}
	
	//
	// Добавление опроса.
	//
	public function action_add()
	{
		$this->title .= '::Голосование';	
		
		if ($this->isPost())
		{
			if (isset($_POST['savePoll']) && ($poll_id = M_Poll::Instance()->add($_POST)))
				$this->redirect(M_Link::ToAdminPoll('all'));
			
			$this->fields = $_POST;
			$count = count($this->fields['answers']);
			
			if (isset($_POST['addAnswer']))
			{				
				$this->fields['answers'][$count] = '';
				$this->fields['weights'][$count] = $count + 1;	
				++$count;		
			}			
			
			$this->fields['count'] = ($count > 2) ? $count : 2;
			$this->messages = M_Poll::Instance()->errors();
		}
		else
		{
			$this->fields = array_fill_keys(array('question'), '');
			$this->fields['count'] = 2;
			
			for ($i = 0; $this->fields['count'] > $i; $i++)
			{
				$this->fields['answers'][$i] = '';
				$this->fields['weights'][$i] = 0;
			}
			$this->messages = array();
		}
		
		// Получение пути к шаблону
		$templateName = $this->getTemplate('components/poll/v_add.php');
		
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Редактирование опроса.
	//
	public function action_edit()
	{
		$this->title .= '::Голосование';	
		
		if ($this->isPost())
		{
			if (isset($_POST['savePoll'])&&($poll_id = M_Poll::Instance()->edit($_POST['pid'], $_POST)))
				$this->redirect(M_Link::ToAdminPoll('edit', (int)$_POST['pid']));
			
			$this->fields = $_POST;
			
			$count = count($this->fields['answers']);
			
			if (isset($_POST['addAnswer']))
			{				
				$this->fields['answers']['new'.$count] = '';
				$this->fields['weights']['new'.$count] = $count + 1;	
				++$count;		
			}			
			$this->fields['count'] = ($count > 2) ? $count : 2;
			$this->messages = M_Poll::Instance()->errors();
		}
		else
		{
			$pid = isset($_GET['param']) ? $_GET['param'] : 0;
			$result = M_Poll::Instance()->get($pid);
			if (empty($result))
				return $this->p404();
			$this->fields['question'] = $result[0]['question'];
			$this->fields['pid'] = $result[0]['pid'];
			foreach ($result as $item)
			{
				$this->fields['answers'][$item['aid']] = $item['answer'];
				$this->fields['weights'][$item['aid']] = $item['weight'];				
			}
			$this->fields['count'] = count($this->fields['answers']);
			$this->messages = array();
		}
		
		// Получение пути к шаблону
		$templateName = $this->getTemplate('components/poll/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Удаление опроса.
	//
	public function action_delete()
	{
		$pid = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		M_Poll::Instance()->delete($pid);
		$this->redirect(M_Link::ToAdminPoll('all'));
	}
	
	public function get_filled_template_poll($pid, $templateName)
	{
		$mPoll = M_Poll::Instance();		
		
		$pid = (int)$pid;
		
		if (!$pid)
			return '';
		
		$user['hostname'] = $_SERVER['REMOTE_ADDR'];
		$user['uid'] = M_Users::Instance()->GetUid();
		$this->show_result['status'] = $mPoll->is_voted($pid, $user);
		
		if ($this->isPost())
		{
			if(isset($_POST['poll_result']))
			{
				$this->show_result['status'] = true;
			}
			elseif (isset($_POST['poll_vote']))
			{
				M_Poll::Instance()->save_vote($_POST, $user);
				//$this->redirect(M_Link::ToPage("poll", (int)$_POST['pid']));
				$this->redirect($_SERVER['REQUEST_URI']);
			}
		}
				
		$this->poll = $mPoll->get_result_poll($pid);
		
		if (empty($this->poll)) 
			return '';
		
		if ($this->show_result['status'] == true)
		{
			$this->show_result['value'] = 0;
			foreach ($this->poll as $answer)
				$this->show_result['value'] += $answer['res'];
		}		
		
		// Формирование контента страницы 
		$templatePoll = $this->Template($templateName, $this);
		
		return $templatePoll;
	}
}