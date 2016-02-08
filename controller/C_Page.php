<?php 
//
// Контроллер страницы чтения
//

class C_Page extends C_Base
{
	public $pages;
	public $navParams;
	public $navBar;
	public $page;
	public $blocks;
	public $countComments;
	public $active;
	public $user_subscr; // подписки пользователя
	public $show_result; 
	public $fields;
	public $messages;
	//
	// Конструктор
	//
	function __construct()
	{
		parent::__construct();
	}
	
	protected function before()
	{
		//$this->needLogin = true;
		parent::before();
		
	}
	
	public function action_404()
	{
		$this->title .= '->404';	
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_404.php');		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName);
	}
	
	public function action_index()	
	{			
	
		$this->title = 'Все статьи';		
		$this->pageUrl = rtrim(M_Link::ToPage('index'), '/');
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('posts', M_Link::ToPage('index'));
		
		// Задание свойств объекта 
		$this->pages = $mPagination -> on_page(5)
															  -> page_num($page_num)
																-> fields("post_id, post_title,	post_content, 
																		post_date_create, views, comment_status, user_name")
																-> left_join('users ON post_author = user_id')
																-> where("post_status = 'publish' AND post_type = 'post'")
																-> order_by('post_date_create DESC')
																-> page();
		foreach ($this->pages as $key => $value)
		{
			$postion = mb_strpos($value['post_content'], '<!--anonsbreak-->');
			if ($postion !== false)
				$this->pages[$key]['anons'] = mb_substr($value['post_content'], 0, $postion - 1);
		}

		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		$mComments = new M_Comments();
		$this->countComments = $mComments->countItemsByAllSubjects();
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_index.php');
		
		// Формирование контента страницы 
		//$this->content = $this->Template($templateName, $this);
		$this->blocks['_content_page'] = $this->Template($templateName, $this);
		
	}
	
	//
	// архив за указанный период.
	//
	public function action_archive()
	{		
		// Получение номера страницы 
		$page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		$time = isset($_GET['date']) ? strtotime($_GET['date']) : false;
		
		if ($time == false)
			$this->p404();		
		
		$date = date("Y-m-d", $time);
		
		$this->active =  date("Y-m", $time); 
		$this->title = 'Архив за ' . date("Y-m", $time);
		
		$this->breadCrambsActive = $this->title;
		$this->pageUrl = rtrim(M_Link::ToPage('archive'), '/');
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('posts', M_Link::ToPage('archive', $this->active));
		// Задание свойств объекта 
		$this->pages = $mPagination -> on_page(5)
															  -> page_num($page_num)
																-> fields("post_id, post_title, post_content, 
																		post_date_create, views, comment_status, user_name")
																-> left_join('users ON post_author = user_id')
																-> where("post_status = 'publish' AND post_type = 'post' AND
																				(post_date_create BETWEEN '$date' AND 
																					DATE_ADD(LAST_DAY('$date'), INTERVAL 1 DAY))")
																-> order_by('post_date_create DESC')
																-> page();
		foreach ($this->pages as $key => $value)
		{
			$postion = mb_strpos($value['post_content'], '<!--anonsbreak-->');
			if ($postion !== false)
				$this->pages[$key]['anons'] = mb_substr($value['post_content'], 0, $postion - 1);
		}
													
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		$mComments = M_Comments::Instance();
		$this->countComments = $mComments->countItemsByAllSubjects();
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_index.php');
		
		// Формирование контента страницы 
		//$this->content = $this->Template($templateName, $this);
		$this->blocks['_content_page'] = $this->Template($templateName, $this);
		
	}
	
	//
	// Страница типа "post"
	//
	public function action_post()
	{
		$mPosts = M_Posts::Instance();
		$post_id = isset($_GET['param']) ? $_GET['param'] : 0;
		$this->page = $mPosts->get($post_id);
		
		if (empty($this->page))
			$this->p404();
		
		if ($this->page['post_type'] != 'post')
		{
			$this->action_page();
			return;
		}
		
		$this->title = $this->page['post_title'];
		$this->breadCrambsActive = $this->title;
		$this->menuLinkId = $this->page['mlid'];		
		
		$mPosts->upPostViews($post_id);
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_post.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);	

		if ($this->page['comment_status'] == 'open')
		{
			$comments = new C_Comments();
			$postComments = $comments->get_for_post($post_id);		
			unset($comments);
			$this->blocks['_content_page'] .= $postComments;
		}	
		
	}
	
	//
	// Страница типов "page" и "parent"
	//
	public function action_page()
	{
		$mPosts = M_Posts::Instance();
		$post_id = isset($_GET['param']) ? $_GET['param'] : 0;
		$this->page = $mPosts->get($post_id);
		
		if (empty($this->page))
			$this->p404();
		
		$this->title = $this->page['post_title'];
		$this->breadCrambsActive = $this->title;
		$this->menuLinkId = $this->page['mlid'];		
		$this->page['children'] = array();
		//$mPosts->upPostViews($post_id);
		if ($this->page['mlid'] > 0 && $this->page['post_type'] == 'parent')
			$this->page['children'] = M_MenuLink::Instance()->getTopChildren($this->page['mlid']);
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_page.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);			
	}
	
	//
	// Страница контакты.
	//
	public function action_contacts()
	{
		$this->title = "Контакты";
		$this->breadCrambsActive = $this->title;
		$user = $this->user;
		
		$this->messages = array();
		
		if ($this->isPost() && isset($_POST['sendEmail']))
		{			
			
			if (M_Helpers::sendEmail($_POST, $this->messages))
			{
				$_SESSION['success_sent'] = true;
				$this->redirect(M_Link::ToPage('contacts'));
			}
	
			$this->fields = $_POST;			
		}
		else 
		{
			$this->fields['message'] = '';			
			$this->fields['email'] = isset($user['login']) ? $user['login'] : '';
			$this->fields['name'] = isset($user['user_name']) ? $user['user_name'] : '';
							
			if (isset($_SESSION['success_sent']))
			{
				$this->messages['success'] = 'Сообщение отправлено!';
				unset($_SESSION['success_sent']);
			}
		}
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_contacts.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);			
	}
	
	//
	// Страница поиска.
	//
	public function action_search()
	{
		$this->title .= '->Поиск';
		$this->breadCrambsActive = 'Поиск';
		$templates = array();
		$error = true;
		
		if($this->isPost())
		{
			$mSearch = M_Search::Instance();
		
			$result = $mSearch->find($_POST['search']);
			
			foreach($result as $key => $value)
			{
				if(count($value) > 0)
				{
					// Получение пути к шаблону 													
		      $templateItem = $this->getTemplate('components/page/'. $mSearch->get_template($key));
					$templates[] = $this->Template($templateItem,
				array('records' => $value));
				}
			}
			
			$error = false;
		}
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/search/v_search.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName,
												array('templates'=> $templates));
	}
	
	//
	// Страница аудиозаписи.
	//
	public function action_audio()
	{
		$this->title = 'Все аудио записи';		
		$this->breadCrambsActive = $this->title;
		$this->pageUrl = rtrim(M_Link::ToPage('audio'), '/');
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mediafile', M_Link::ToPage('audio'));
		
		// Задание свойств объекта 
		$this->pages = $mPagination -> on_page(5)
															  -> page_num($page_num)
																-> fields('fid, name, title')
																-> where("type = 'audio'")
																-> order_by('fid')
																-> page();
													
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_audio.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);
	}
	
	//
	// Страница списка видео.
	//
	public function action_video()
	{
		$this->title = 'Все видео записи';		
		$this->breadCrambsActive = $this->title;
		$this->pageUrl = rtrim(M_Link::ToPage('video'), '/');
		
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mediafile', M_Link::ToPage('video'));
		
		// Задание свойств объекта 
		$this->pages = $mPagination -> on_page(5)
															  -> page_num($page_num)
																-> fields('fid, name, title')
																-> where("type = 'video'")
																-> order_by('fid')
																-> page();
												
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_video.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);
	}
	
	//
	// Страница вывода видео.
	//
	public function action_wvideo()
	{
		$mVideo = M_MediaFiles::Instance();
		$video_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		$this->page = $mVideo->get($video_id);
		
		if (empty($this->page))
			$this->p404();
		
		$this->title = $this->page['title'];
		$this->breadCrambsActive = $this->title;
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_wvideo.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);	
		
	}
	
	//
	// Вывод списка галерей.
	//
	public function action_galleries()
	{
		$this->title = 'Все галереи';		
		$this->breadCrambsActive = $this->title;
		$this->pageUrl = rtrim(M_Link::ToPage('galleries'), '/');
		// Получение номера страницы 
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		// Создание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('gallery', M_Link::ToPage('galleries'));
		
		// Задание свойств объекта 
		$this->pages = $mPagination -> on_page(5)
															  -> page_num($page_num)
																-> fields('gallery_id, gallery_title, gallery_desc')
																-> order_by('gallery_id')
																-> page();
												
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		// 
		$this->navParams = $mPagination->navparams();
		
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_galleries.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $this);
	}
	
	//
	// Вывод галерей.
	//
	public function action_gallery()
	{
		$mGallery = M_Gallery::Instance();
		$gallery_id = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		$fields = array();
		$fields['gallery'] = $mGallery->get($gallery_id);
		$fields['images'] = $mGallery->get_images($gallery_id);
		
		if (empty($fields['gallery']))
			$this->p404();
		
		$this->title = $fields['gallery']['gallery_title'];
		$this->breadCrambsActive = $this->title;
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_gallery.php');
		
		// Формирование контента страницы 
		$this->blocks['_content_page'] = $this->Template($templateName, $fields);			
	}
	
	//
	// Вывод списка листов для подписки.
	//
	public function action_subscriptions()
	{
		$user = $this->user;
		
		if (!$user)
			$this->redirect(M_Link::ToAuth('login'));
		
		$this->title = 'Списки подписки';
		$this->breadCrambsActive = $this->title;
		$this->pageUrl = rtrim(M_Link::ToPage('subscriptions'), '/');		
		
		if ($this->isPost())
		{
			foreach ($_POST as $key => $value){
				// Если нажата кнопка отправки ...
				if (substr($key, 0, 6) == 'listid')
				{
					$action = explode('_', $key);
					
					if (count($action == 3) && !empty($user)) 
					{
						$fields['listid'] = $action[2];
						$fields['email'] = $user['login'];
						
						if ($action[1] == 'subsc')						
							M_Mailinglists::Instance()->subscribe($fields);
						else if($action[1] == 'unsubsc')
							M_Mailinglists::Instance()->unsubscribe($fields);
				
						$this->redirect(M_Link::ToPage('subscriptions'));
					}
				}
			}
		}
		
		$this->user_subscr = M_Mailinglists::Instance()->get_user_subscr($user['login']);
		
		//получение номера страницы
		$page_num = isset($_GET['param']) ? (int)$_GET['param'] : 1;
		
		if ($page_num < 1) $page_num = 1;
		
		//cоздание объекта постраничной навигации(обязательные параметры)
		$mPagination = new M_Pagination('mailinglists', M_Link::ToPage('subscriptions'));
		
		//задание свойств объекта
		$this->pages = $mPagination->on_page(5)->page_num($page_num)->where('is_show = 1')->page();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_navbar.php');
		
		// Получение hmlt-кода панели навигации 
		$this->navBar = $this->Template($templateName, $mPagination);
		
		//формирование контента страницы
		$this->navParams = $mPagination->navparams();
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_subscriptions.php');
		
		$this->blocks['_content_page'] = $this->Template($templateName, $this);
	}
	
	//
	// Вывод голосования.
	//
	public function action_poll()
	{
		$pid = isset($_GET['param']) ? (int)$_GET['param'] : 0;
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_poll.php');
		
		$poll = new C_Poll();
		$template = $poll->get_filled_template_poll($pid, $templateName);
		unset($poll);
		
		if (empty($template))
			$this->p404();
			
		$this->blocks['_content_page'] = $template;
	}
}