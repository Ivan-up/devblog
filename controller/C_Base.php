<?
//
// Базовый контроллер обычных страниц сайта
//
abstract class C_Base extends C_Controller
{
	protected $title;		              // заголовок страницы
	protected $content;		            // содержание страницы
	protected $needLogin;	            // необходима ли авторизация
	protected $user;		              // авторизованный пользователь || null
	protected $styles = array();	    // стили шаблона 
	protected $scripts = array();	    // скрипты шаблона
	protected $template;							// имя шаблона главной страницы
	protected $template_name;         // имя текущего шаблона 
	protected $regions = array();     // Регионы шаблона
	protected $enableBlocks = array(); // Включенные блоки в шаблоне 
	protected $menuLinkId = 0;				 // первичный ключ ссылки
	protected $pageUrl;
	protected $mainMenu;               // Главное меню
	protected $breadCrambsActive;
	//
	// Конструктор
	//
	function __construct()
	{
		$this->needLogin = false;
		$this->user = M_Users::Instance()->Get();
		$this->keywords = "";
		$this->description = "";
		$this->styles = array('bootstrap','lightbox', 'video-js.min', 'plugin_diafilm', 'main');		
		$this->scripts = array('jquery-1.11.2.min' ,'bootstrap.min', 'lightbox-2.6.min', 'video.min', 'custom/plugin_diafilm', 'custom/main_script');		
	}
		
	protected function before()	
	{
		if($this->needLogin && $this->user === null)
			$this->redirect(M_Link::ToAuth('login'));	
		
		$this->title = SITE_NAME;
		$this->content = "";
		// Получает данные о шаблоне 
		$this->template_name = M_Templates::Instance()->getCurrentTemplate();
		$this->templateDir = BASE_URL . TEMPLATE_DIR . $this->template_name;
		$this->template = $this->getTemplate('v_main.php');
		$this->getMediaTemplate(substr($this->templateDir, iconv_strlen(BASE_URL)) . '/media_main.info');
		$this->enableBlocks = M_Templates::Instance()->getBlocksTemplate($this->template_name);
		// Получаем данные главного меню
		$this->mainMenu = M_MenuLink::Instance()->getTopItemsByMenu(1);
	}
	
	//
	// Генерация базового шаблона
	//
	public function render()
	{	
		$mTemplates = M_Templates::Instance();
		
		// Загружаем данные блоков
		$this->loadBlocks();
		
		//Получаем блоки который есть регионах
		$blocks = $mTemplates->regionsBlocks($this->template_name); 
		
		// Список всех регионов
		$_regions = M_Regions::Instance()->getByTemplate($this->template_name);
		
		$regions = array();
		
		// Подключаем хлебные крошки(навигацию)		
			$this->blocks['_content_page'] = $this->getBreadCrumbs($this->menuLinkId) . 
					"" .$this->blocks['_content_page'];
		
		
		// Наполняем регионы содержимым блоков
		foreach ($_regions as $region)
		{
			$rid = $region['region_id'];
			
			if ($rid == 0) continue;
			
			if (isset($blocks[$rid]))
			{
				foreach ($blocks[$rid] as $block)
				{
					$regions[$region['region_name']][] = 
						isset($this->blocks[$block['block_name']]) ? 
										$this->blocks[$block['block_name']] : "";
				}
			}
			else 
				$regions[$region['region_name']]= array();
		}
		
		
		$regions_s = array();
		
		// Делаем каждый регион строкой
		foreach ($regions as $key => $region)
		{
			$regions_s[$key] = implode("\n", $region);
		}
		
		$this->regions = $regions_s;	
		
		$page = $this->Template($this->template, $this);
		
		//print_r($page);
		//echo $page;
		echo $this->replace_widgets($page);
	}	
	
	//
	// Загружает данные для включенных блоков
	//
	protected function loadBlocks()
	{	
		foreach ($this->enableBlocks as $block)
		{
			$name = $block['block_name']; 
			if (strpos($name, 'menu_') === 0 && substr_count($name, '_') == 1)
			{
				list($act, $menu_id) = explode('_', $name);
				$this->blocks[$name] = $this->getMenu($menu_id); 
			}
			elseif ($name == 'searchBox')
			{
				$this->blocks[$name] = $this->getSearchBox();
			}
			elseif ($name == 'loginBox')
			{
				$this->blocks[$name] = $this->getLoginBox();
			}
			elseif ($name == 'postsMostViews')
			{
				$this->blocks[$name] = $this->getPostMostViews();
			}
			elseif($name == 'postsMostComments')
			{
				$this->blocks[$name] = $this->getPostMostComments();
			}
			elseif($name == 'lastestComments')
			{
				$this->blocks[$name] = $this->getLastestComments();
			}
			elseif($name == 'postsArchive')
			{
				$this->blocks[$name] = $this->getCountPostsForMonths();
			}
		}		
	}
	
	//
	// Получаем количество записей в месяце, блок архив
	//
	public function getCountPostsForMonths()
	{
		$posts = M_Posts::Instance()->getCountPostsForMonths();		
		$months = array( 1 => 'Январь', 2 => 'Февраль', 3 =>'Март', 4 => 'Апрель',
						5 => 'Май', 6 =>'Июнь',	7 => 'Июль', 8 => 'Август', 
						9 => 'Сентябрь', 10 => 'Октябрь',  11 =>'Ноябрь', 12 => 'Декабрь');
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_box_posts_archive.php');
		$archive = $this->Template($templateName, array('posts' => $posts, 'months' => $months));
		
		return $archive;
	}
	
	//
	// Блок списка последних комментарий
	//
	public function getLastestComments()
	{
		$comments = M_Comments::Instance()->getLastestComments(5);
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_posts_lastest_comments.php');
		$_comments = $this->Template($templateName, array('comments' => $comments));
		
		return $_comments;
	}
	
	//
	// Блок списка наиболее комментируемых постов
	//
	public function getPostMostComments()
	{
		$posts = M_Posts::Instance()->getPostMostComments(5);
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_posts_most_comments.php');
		$_posts = $this->Template($templateName, array('posts' => $posts));
		
		return $_posts;
	}
	
	//
	// Блок списка наиболее просматриваемых постов
	//
	public function getPostMostViews()
	{
		$posts = M_Posts::Instance()->getPostMostViews(5);
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_posts_most_views.php');
		$_posts = $this->Template($templateName, array('posts' => $posts));
		
		return $_posts;
	}
	
	//
	// Получает меню по id в виде заполненого шаблона
	//
	protected function getMenu($menu_id) 
	{
		// 
		$menu = M_Menu::Instance()->getMenuWithChildren($menu_id);
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_menu.php');
		$pageUrl = $this->getPageUrl();
		$_menu = $this->Template($templateName, array('menu' => $menu[0],
																									'pageUrl' => $pageUrl));
		//var_dump($_menu);
		return $_menu;
	}	
	
	//
	// Блок поиска
	//
	protected function getSearchBox()
	{
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_search_box.php');		
		return $this->Template($templateName);
	}
	
	//
	// Блок поиска
	//
	protected function getLoginBox()
	{		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('components/page/v_login_box.php');
		
		return $this->Template($templateName, array('user'=>$this->user));
	}
	
	//
	// Блог навигационного пути
	//
	public function getBreadCrumbs($mlid)
	{
		$breadCrambs = M_MenuLink::Instance()->getItemsBreadCrumbs($mlid);
		
		if (!empty($this->breadCrambsActive))
		{
			$max = 20;
			$link_title = trim(strip_tags($this->breadCrambsActive));
			$link_title = mb_strlen($link_title) > $max ? mb_substr($link_title, 0, $max - 3) . '...' : $link_title;
			$breadCrambs[] = array('link_title' => $link_title, 'active' => true);
		}
			
		
		// Получение пути к шаблону 													
		$templateName = $this->getTemplate('v_bread_crambs.php');
		return $this->Template($templateName, array('breadCrambs'=>$breadCrambs, 'mlid' => $mlid));
	}
	
	//
	// url текущей страницы, необходим для определения 
	// активен ли пункт меню
	//
	public function getPageUrl()
	{
		if (empty($this->pageUrl))
		{
			$this->pageUrl = rtrim($this->pageUrl = $_SERVER['REQUEST_URI'], '/');
		}
		
		return $this->pageUrl;
	}
	
	//
	// Проверяет является страницы главной
	// @return boolean
	//
	public function isMainPage()
	{
		$url = $this->getPageUrl();
		return $url == BASE_URL.'page';
	}
	
	//
	// Проверяет является ли пункт меню активный
	// @param $url - ссылка пункта меню
	// @return boolean
	//
	public function isActiveUrl($url)
	{
		$url_active = $this->getPageUrl();
		$url = rtrim(trim($url),'/');
		if (empty($url) || $url == rtrim(BASE_URL, '/')) $url = rtrim(M_Link::ToPage('index'), '/');
		return $url_active == $url;
	}
}