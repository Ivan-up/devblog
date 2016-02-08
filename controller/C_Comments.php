<?php 
//
// Контроллер администратирования комментариев, 
// и их вывода на страницы
//
class C_Comments extends C_Admin_Base
{
	public $comments = array();
	public $post_id;
	public $messages;
	public $fields;
	public $isLogged;
	
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
	// Получить все комментария
	//
	public function action_all()
	{
		$this->title .='::Все страницы';
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('comments', M_Link::ToAdminComments('all'));
		
		// Задание свойств объекта 
		$this->comments = $mPagination->on_page(5)
																	->page_num($page_num)
																	->fields('comment_id, comment_content, comment_author, 
																						comment_author_id, dateCreate,
																						comment_author_email,	idSubject')
																	->join('commentsTree ON comment_id = idDescendant')
																	->group_by('comment_id')
																	->order_by('dateCreate DESC')
																	->page();
		//var_dump($this->comments); die();
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/comments/v_all.php');
		
		// Формирование контента страницы 
		$this->content = $this->Template($templateName, $this);
		
	}
	
	//
	// Редактирование комментариев
	//
	public function action_edit()
	{
		$this->title .= '->Комментария';
		$comment_id = isset($_GET['param']) ? $_GET['param'] : 0;
		$mComments = M_Comments::Instance();
		$messages = array();
		
		if ($this->isPost())
		{
			if ($mComments->edit($_POST['comment_id'], $_POST))
			{
				$this->redirect(M_Link::ToAdminComments('all'));
			}
			$this->fields = $_POST;
			$messages = $mComments->errors();
		}
		else
		 $this->fields = $mComments->getOneItem($comment_id);
	 
		$this->messages = $messages;
		
		if (empty($this->fields))		
			$this->p404();
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/comments/v_edit.php');
		
		$this->content = $this->Template($templateName, $this);		
	}	
	
	//
	// Удаление комментариев
	//
	public function action_delete()
	{
		$comment_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		M_Comments::Instance()->deleteBranch($comment_id);
		$this->redirect(M_Link::ToAdminComments('all')); 
	}
	
	//
	// Получить комментарий для поста
	// @param $post_id - номер поста
	//
	public function get_for_post($post_id)
	{
		$mComments = M_Comments::Instance();
		$mComments->setIdSubject($post_id);
		$this->isLogged = (bool)$this->user;
		if ($this->isPost() && isset($_POST["comment_btn"]))
		{
			$comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
			if ($this->user)
			{
				$_POST['comment_author_id'] = $this->user['user_id'];
				$_POST['comment_author'] = $this->user['user_name'];
				$_POST['comment_author_email'] = $this->user['login'];
			} 
			else
			{
				$_POST['comment_author_id'] = 0;
			}
			$new_comment_id = $mComments->add($_POST, $comment_id);
			if (!empty($new_comment_id))
			{
				$url = M_Link::ToPage('post', $post_id) . '#commentItem' . $new_comment_id;
				$this->redirect($url);
			}
			$this->messages = $mComments->errors();
			$this->fields = $_POST;
		} 
		else 
		{
			$this->fields = array_fill_keys(
				array('comment_author', 'comment_author_email', 'comment_content'),'');
		}
		
		// Получаем переменные для шаблона
		$this->comments = $mComments->getTree();
		
		$this->post_id = $post_id;
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_comments.php');
		
		// Формирование контента страницы 
		$treeComment = $this->blocks['_content_page'] = $this->Template($templateName, $this);		
		
		return $treeComment;
	}	
	
}