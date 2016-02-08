<?php 

class M_Link
{
	public static function Build($link, $type = 'http')
	{
		$base = ($type == 'http' || USE_SSL == 'no') ? '' : 'https://' 
					. $_SERVER['SERVER_NAME'];
					
		// Если константа HTTP_SERVER_PORT определена и значение отличается
		// от используемого по умолчанию ...
		if (defined('HTTP_SERVER_PORT') && HTTP_SERVER_PORT != 80 && 
				strpos($base, 'https') === false)
		{
			$base .= ':' . HTTP_SERVER_PORT;
		}
		$link = $base . BASE_URL . $link;
		
		// Escape - символы для html
		return htmlspecialchars($link, $page = 1);
	}
	
	// Подготавливает строку к использованию в URL
	public static function ClearUrlText($string)
	{
		// Удаляем все символы, кроме a-z, 0-9, дефиса,
		// знаки подчеркивания и пробела
		$not_acceptable_characters_regex = '#[^-a-zA-Z0-9_ ]#';
		$string = preg_replace($not_acceptable_characters_regex, '', $string);
		// Удаляет все пробелы в начале и в конце строки 
		$string = trim($string);
		// Заменяет все дефисы, знаки подчеркивания и пробелы дефисами 
		$string = preg_replace('#[-_]+#', '-', $string);
		return strtolower($string);
	}
	
	// Создаем ссылку для страницы вывода
	public static function ToPage($action = '', $param = null, $param2 = null)
	{
		$link = "";	
		switch ($action)
		{
			case 'post':
				$link = 'page/post/';	break;
			case 'index':
				$link = 'page/'; break;
			case 'search': 
				$link = 'page/search/'; break;
				break;
			case 'archive':
				$link = 'page/archive/'; break;
			case 'audio':
				$link = 'page/audio/'; break;
			case 'video':
				$link = 'page/video/'; break;
			case 'wvideo':
				$link = 'page/wvideo/'; break;
			case 'gallery':
				$link = 'page/gallery/'; break;
			case 'galleries':
				$link = 'page/galleries/'; break;
			case 'subscriptions':
				$link = 'page/subscriptions/'; break;
			case 'poll':
				$link = 'page/poll/'; break;
			case 'contacts':
				$link = 'page/contacts/'; break;
			default:
				$link = 'page/';			
		}
		
		if ($param !== null) $link .= $param . '/';
		if ($param2 !== null) $link .= $param2 . '/';
		
		return self::Build($link);
	}
	
	public static function ToAuth($action , $param = null)
	{
		$link = "";
		switch ($action)
		{
			case 'logout':
				$link = "auth/logout/"; break;
			case 'login': 
				$link = "auth/login/"; break;
			case 'registr':
				$link = "auth/registr/"; break;
			case 'success':
				$link = "auth/success/"; break;
			case 'forgetpass':
				$link = "auth/forgetpass/"; break;
			case 'account':
				$link = "auth/account/"; break;
		}
		
		if ($param !== null) $link .= $param;
		
		return self::Build($link);
	}
	
	// Создаем ссылку на страницу админстратирования 
	public static function ToAdmin($link)
	{		
		return self::Build('admin/'.$link, 'https');
	}
	
	// Создаем ссылку на администратирования поста
	public static function ToAdminPosts($action = '', $param = null)
	{
		$link = "";		
		switch($action)
		{
			case 'add':
				$link = 'posts/add/';	break;
			case 'edit':
				$link = 'posts/edit/'; break;
			case 'all':
				$link = 'posts/all/'; break;
			case 'delete':
				$link = 'posts/delete/'; break;				
			case 'index':
			default:
				$link = 'posts/';			
		}
		
		if ($param !== null) $link .= $param;
		
		return self::ToAdmin($link);
	}
	
	// Создаем ссылку на администратирования пользователей
	public static function ToAdminUsers($action = '', $param = null, $param2 = null)
	{
		$link = "";		
		switch($action)
		{
			case 'add':
				$link = 'users/add/';	break;
			case 'edit':
				$link = 'users/edit/'; break;
			case 'all':
				$link = 'users/all/'; break;
			case 'delete':
				$link = 'users/delete/'; break;
			case 'allroles':
				$link = 'users/allroles/'; break;
			case 'allroles_privs':
				$link = 'users/allroles_privs/'; break;
			case 'addrole':
				$link = 'users/addrole/'; break;
			case 'editrole':
				$link = 'users/editrole/'; break;
			case 'deleterole':
				$link = 'users/deleterole/'; break;
			case 'index':
			default:
				$link = 'users/';			
		}
		
		if ($param !== null) $link .= $param;
		if ($param2 !== null) $link .= '/'. $param2;
		
		return self::ToAdmin($link);
	}
	
	
	// Создаем ссылку на администратирования меню
	public static function ToAdminMenu($action = '', $param = null, $param2 = null)
	{
		$link = "";		
		switch($action)
		{
			case 'add':
				$link = 'menu/add/';	break;
			case 'edit':
				$link = 'menu/edit/'; break;
			case 'all':
				$link = 'menu/all/'; break;
			case 'delete':
				$link = 'menu/delete/'; break;
			case 'addlink':
				$link = 'menu/addlink/'; break;
			case 'editlink':
				$link = 'menu/editlink/'; break;
			case 'deletelink':
				$link = 'menu/deletelink/'; break;
			case 'itemslist':
				$link = 'menu/itemslist/'; break;
			case 'index':
			default:
				$link = 'menu/';			
		}
		
		if ($param !== null) $link .= $param;
		if ($param2 !== null) $link .= '/'. $param2;
		
		return self::ToAdmin($link);
	}
	
	// Создаем ссылку на администратирования меню
	public static function ToAdminTemplates($action = '', $param = null)
	{
		$link = "";		
		switch($action)
		{
			case 'set':
				$link = 'templates/set/';	break;
			case 'index':
			default:
				$link = 'templates/';			
		}
		
		if ($param !== null) $link .= $param;
		
		return self::ToAdmin($link);
	}
	
	
	// Создаем ссылку на администратирования комментариев 
	public static function ToAdminComments($action = '', $param = null)
	{
		$link = "";
		switch($action)
		{
			case 'all':
				$link = 'comments/all/'; break;
			case 'edit':
				$link = 'comments/edit/'; break;
			case 'delete': 
				$link = 'comments/delete/'; break;
			case 'index':
			default:
				$link = 'comments/';		
		}
		
		if ($param !== null) $link .= $param;
	
		return self::ToAdmin($link);	
	}
	
	// Создаем ссылку на администратирования галерии 
	public static function ToAdminGallery($action = '', $param = null, $param2 = null)
	{
		$link = "";
		switch($action)
		{
			case 'all':
				$link = 'gallery/all/'; break;
			case 'edit':
				$link = 'gallery/edit/'; break;
			case 'editimg':
				$link = 'gallery/editimg/'; break;
			case 'delete': 
				$link = 'gallery/delete/'; break;
			case 'add':
				$link = 'gallery/add/'; break;
			case 'images':
				$link = 'gallery/images/'; break;
			case 'upload':
				$link = 'gallery/upload/'; break;
			case 'index':
			default:
				$link = 'gallery/';		
		}
		
		if ($param !== null) $link .= $param;
		if ($param2 !== null) $link .= '/'. $param2;
		
		return self::ToAdmin($link);	
	}

	// Создаем ссылку на администратирования видео 
	public static function ToAdminVideo($action = '', $param = null)
	{
		$link = "";
		switch($action)
		{
			case 'all':
				$link = 'video/all/'; break;
			case 'edit':
				$link = 'video/edit/'; break;
			case 'delete': 
				$link = 'video/delete/'; break;
			case 'add':
				$link = 'video/add/'; break;
			case 'index':
			default:
				$link = 'video/';		
		}
		
		if ($param !== null) $link .= $param;
		
		
		return self::ToAdmin($link);	
	}

	// Создаем ссылку на администратирования видео 
	public static function ToAdminAudio($action = '', $param = null)
	{
		$link = "";
		switch($action)
		{
			case 'all':
				$link = 'audio/all/'; break;
			case 'edit':
				$link = 'audio/edit/'; break;
			case 'delete': 
				$link = 'audio/delete/'; break;
			case 'add':
				$link = 'audio/add/'; break;
			case 'index':
			default:
				$link = 'audio/';		
		}
		
		if ($param !== null) $link .= $param;
		
		
		return self::ToAdmin($link);	
	}	
	
	// Создаем ссылку на администратирования рассылки
	public static function ToAdminMailing($action = '', $param = null, $param2 = null)
	{
		$link = "";
		switch($action)
		{
			case 'all':
				$link = 'mailing/all/'; break;
			case 'edit':
				$link = 'mailing/edit/'; break;
			case 'delete': 
				$link = 'mailing/delete/'; break;
			case 'add':
				$link = 'mailing/add/'; break;
			case 'addmail':
				$link = 'mailing/addmail/'; break;
			case 'deletemail':
				$link = 'mailing/deletemail/'; break;
			case 'viewmail':
				$link = 'mailing/viewmail/'; break;
			case 'send':
				$link = 'mailing/send/'; break;
			case 'archive':
				$link = 'mailing/archive/'; break;
			case 'subscribers':
				$link = 'mailing/subscribers/'; break;
			case 'index':
			default:
				$link = 'mailing/';		
		}
		
		if ($param !== null) $link .= $param;
		if ($param2 !== null) $link .= '/'. $param2;
		
		return self::ToAdmin($link);	
	}
	
	// Создаем ссылку на администратирования голосования
	public static function ToAdminPoll($action = '', $param = null, $param2 = null)
	{
		$link = "";
		switch($action)
		{
			case 'all':
				$link = 'poll/all/'; break;
			case 'edit':
				$link = 'poll/edit/'; break;
			case 'delete': 
				$link = 'poll/delete/'; break;
			case 'add':
				$link = 'poll/add/'; break;
			case 'index':
			default:
				$link = 'poll/';		
		}
		
		if ($param !== null) $link .= $param;
		if ($param2 !== null) $link .= '/'. $param2;
		
		return self::ToAdmin($link);	
	}	
	
} 