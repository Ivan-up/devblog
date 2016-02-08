<?php
//
// Менеджер статей
//
class M_Posts extends M_Model
{
	private $ck_sort_img_folder;
	private $ckupload_dir;
	private $ck_temp_prefix;
	private static $instance; //ссылка на экземпляр класса
	
	//
	// Получение единственного экземпляра (одиночка)
	//
	public static function Instance()
	{
		if(self::$instance == null)
			self::$instance = new self();
			
		return self::$instance;
	}
	
	//
	// Конструктор
	//
	public function __construct()
	{
		parent::__construct('posts', 'post_id');
		$this->ck_sort_img_folder = CK_SORT_IMG_FOLDER; // Нужно ли сортировать по папка
		$this->ckupload_dir = CKUPLOAD_DIR; // Папка для загрузки картинок CKEditor
		$this->ck_temp_prefix = CK_TEMP_PREFIX ; // Префикс для временной папки
	}
	
	//
	// Получает запись по первичному ключу
	// @return array
	public function get($id)
	{
		$id = (int)$id;
		$query = "SELECT p.post_id, p.post_title, p.post_content, 
										p.post_date_create, p.post_status, p.post_type,
										p.mlid, p.views, p.comment_status, u.user_name, ml.menu_id,
										ml.link_title, ml.link_description, ml.plid, m.menu_title
								FROM {$this->table} p
							LEFT JOIN users u
								ON p.post_author = u.user_id
							LEFT JOIN menu_link ml
								ON p.mlid = ml.mlid
							LEFT JOIN menu m
								ON ml.menu_id = m.menu_id
							WHERE {$this->pk} = $id";
		$res = $this->db->Select($query);
		return isset($res[0]) ? $res[0] : array();
	}
	
		
	// добавляет запись
	// @params $fields - ассоциативный массив поле-значение
	// @return false or int($post_id)	
	public function add($fields)
	{			
		$fields['post_date_create'] = date('Y-m-d H:i:s');
		$fields2 = array();
		$errors = false;
		$errorsMLink = array();
		
		if ($this->db->beginTransaction('posts_add'))
		{
			// Вставляем "запись"
			if ( !($post_id = parent::add($fields)) )
				$errors = true ; 
			
		
			// Добавляем пункт, если указан 
			if ($errors === false && 
					isset($fields['parent']) && 
					substr_count($fields['parent'], ':') == 1)
			{	
				$parent = explode(':' ,$fields['parent']);
				$menu_id = (int)$parent[0];
				$plid = (int)$parent[1];
				
				$fields_menu = array();
				$fields_menu['link_title'] = $fields['link_title'];
				$fields_menu['menu_id'] = $menu_id;
				$fields_menu['plid'] = $plid;
				$fields_menu['link_description'] = $fields['link_description'];
				$link = M_Link::ToPage('post', $post_id);
				$fields_menu['link_path'] = (strpos($link, BASE_URL) === 0) ? mb_substr($link, iconv_strlen(BASE_URL)) : $link;
				$mMenuLink = M_MenuLink::Instance();
				
				if ( !($mlid = $mMenuLink->add($fields_menu)) )
				{
					$errors = true;					
					$errorsMLink = $mMenuLink->errors();
				}
				else 
					$fields2['mlid'] = $mlid;
			}			
		
			// Если включена сортировка картинок по папкам
			if ($errors === false && $this->ck_sort_img_folder === true)
			{			 
				$search = 'post_0_' . $this->ck_temp_prefix;
				$replace = "post_$post_id";
				$src_images = array();
			
				// Заменяем пути картинок в поле
				$fields2['post_content'] = $this->replace_img_in_src($search, $replace, $fields['post_content'], $src_images);	
				
				// Создаем папку для картинок поста
				$folder_post =  $this->ckupload_dir . $replace . '/';		
				if (!is_dir($folder_post))
						mkdir($folder_post);
				
				// Перемешаем картинки из временной папки
				foreach($src_images as $link) 	
					$this->move_file($link['last'], $link['new']);		
			}
			
			if ($errors === false && !empty($fields2))
			{
				// Обновляем запись в базе данных
				if (!parent::edit($post_id, $fields2))
					$errors = true;
			}
			
			if ($errors === false &&	$this->db->commit('posts_add'))				
				return $post_id;
			
			$this->db->rollBack('posts_add');
		}
		
		$this->errors = array_merge($this->errors, $errorsMLink);
		
		return false;	
	}	
	
	// Обновляет запись по первичному ключу
	// @params $fields - ассоциативный массив поле-значение
	// @return boolean
	public function edit($post_id, $fields)
	{
		$fields['post_date_update'] = date('Y-m-d H:i:s');
		$fields2 = array();
		$errors = false;
		$errorsMLink = array();		
		
		// Если есть родительская ссылка для меню
		if ($errors === false && 
				isset($fields['parent']) && 
				substr_count($fields['parent'], ':') == 1)
		{	
			$parent = explode(':' ,$fields['parent']);
			$menu_id = (int)$parent[0];
			$plid = (int)$parent[1];
			
			$mlid = (int)$fields['mlid'];
			$fields_menu = array();
			$fields_menu['link_title'] = $fields['link_title'];
			$fields_menu['menu_id'] = $menu_id;
			$fields_menu['plid'] =(int)$plid;
			$fields_menu['link_description'] = $fields['link_description'];		
			$link = M_Link::ToPage('post', $post_id);
			$fields_menu['link_path'] = (strpos($link, BASE_URL) === 0) ? mb_substr($link, iconv_strlen(BASE_URL)) : $link;
			$mMenuLink = M_MenuLink::Instance();		
			
			
			if ($mlid == 0 )
			{
				// Если не было в меню пункта добавляем его 
				if ( !($mlid = $mMenuLink->add($fields_menu)) )
				{
					$errors = true;					
					$errorsMLink = $mMenuLink->errors();
				}
				else 
					$fields['mlid'] = $mlid;
			}
			else
			{
				// иначе обновляем данные пункта меню
				if ($mMenuLink->edit($mlid, $fields_menu) == false)
				{
					$errors = true;
					$errorsMLink = $mMenuLink->errors();
				}
			}			
		}
		// Если нету родительской ссылки, 
		// значить запись была убрана из меню, удаляем ссылку в меню
		elseif ( isset($fields['parent']) && $fields['parent'] == 0 &&
						isset($fields['mlid']) && $fields['mlid'] > 0)
		{
			M_MenuLink::Instance()->delete($fields['mlid']);
			$fields['mlid'] = 0;
		}
		
		// Обновляем данные самой "записи"
		if ($errors === false && !parent::edit($post_id, $fields))
			$errors = true;	
		
		// Если включена сортировка картинок по папкам
		if ($errors === false && $this->ck_sort_img_folder === true)
		{		
			$path = $this->ckupload_dir . 'post_' . $post_id . '/';
			if (is_dir($path))
				$this->check_img_uses_in_post($fields['post_content'], $path);
		}
		
		if ($errors === false)
			return true;
		
		$this->errors = array_merge($this->errors, $errorsMLink);
		
		return false;
	}
	
	
	// Удаляет "запись" по первичному ключу
	// @param $post_id
	// @return boolean
	public function delete($post_id)
	{
		if ($post_id <= 0)
			return false;			
			
		$mComments = new M_Comments();
		$sql = "SELECT mlid FROM posts WHERE post_id =" . (int)$post_id;		
		$result = $this->db->Select($sql);
		$mlid = isset($result[0]['mlid']) ? $result[0]['mlid'] : 0; 
		
		if ($mComments->deleteEntriesSubject($post_id) !== false &&
				parent::delete($post_id) > 0)
		{		
			unset($mComments);
			
			if ($mlid > 0)
				M_MenuLink::Instance()->delete($mlid);
			
			$path = $this->ckupload_dir . 'post_' . $post_id;
			
			if (is_dir($path))
				$this->removeDirectory($path);
			
			return true;
		}		
		
		return false;
	}
	
	// Возращает "записи" с наибольшим кол-во комментариев
	// @param $quantity - количестов записей
	// @return array
	public function getPostMostComments($quantity)
	{
		$posts = array();
		$quantity = (int)$quantity;
		if ($quantity > 0)
		{
			$sql = "SELECT post_id, post_title, (SELECT COUNT(DISTINCT idDescendant) FROM commentsTree
								WHERE idSubject=post_id) AS countComments 
							FROM posts ORDER BY countComments DESC LIMIT $quantity";
			$result = $this->db->Select($sql);
			$posts = !empty($result) ? $result : array(); 
		}
		return $posts;
	}
	
	// Возращает "записи" с наибольшим кол-во просмотров
	// @param $quantity - количестов записей
	// @return array
	public function getPostMostViews($quantity)
	{
		$posts = array();
		$quantity = (int)$quantity;
		if ($quantity > 0)
		{
			$sql = "SELECT post_id, post_title, views 
							FROM posts ORDER BY views DESC LIMIT $quantity";
			$result = $this->db->Select($sql);
			$posts = !empty($result) ? $result : array(); 
		}
		return $posts;
	}
	
	//
	// Возращает кол-во "записей" для каждого месяца
	// @return array
	public function getCountPostsForMonths()
	{
		$sql = "SELECT COUNT(*) as countItems, 
								MONTH(post_date_create) as `month`, 
								YEAR(post_date_create) as `year`, 
								DATE_FORMAT(post_date_create, '%Y-%m') as cdate
							FROM posts 
								WHERE post_status = 'publish' and post_type = 'post'
								GROUP BY cdate ORDER BY cdate DESC";
		$result = $this->db->Select($sql);
		return !empty($result) ? $result : array();
	}
	
	// Увеличивает поле просмотров "Views" на 1
	// @param $post_id - первичный ключ "Записи"
	// @return int - число затронутых строк
	public function upPostViews($post_id)
	{
		$post_id = (int)$post_id;
		$sql = "UPDATE posts SET views = views + 1 WHERE post_id = ?";
		return $this->db->Execute($sql, array($post_id));
	}	
	
	
	//
	// Удаляет из папки картинки "записи" 
	// не используемые картинки
	private function check_img_uses_in_post($content, $path)
	{		
		$files_folder  = array();
		$files_post = array();
		$files_to_del = array();
		
		$html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $content;		
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$images = $doc->getElementsByTagName('img');
		foreach($images as $image)
		{
			$src = $image->getAttribute('src');
			$tmp = explode('/', $src);
			 $filename =  trim(end($tmp));
			if (!empty($filename)) 
				$files_post[] = $filename; 
		}

		unset($doc);
		
		$files_folder  = $this->get_files_in_folder($path);		
		$files_to_del = array_diff($files_folder, $files_post);
		
		foreach ($files_to_del as $file)
		{
			$filepath = $path . $file;
			if (is_file($filepath))
				unlink($filepath);
		}
		
		return true;
	}
	
	// 
	// Обновляет пути картинок в тексте
	// @return string
	private function replace_img_in_src($search, $replace, $content ,&$src_images)
	{
		
		$html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $content;
		
		$doc = new DOMDocument();
		$doc->loadHTML($html);
		$src_images = array();				
		
		$images = $doc->getElementsByTagName('img');
		
		foreach($images as $key => $image)
		{
			$link = $image->getAttribute('src');
			$src_images[$key]['last'] = $link;
			$link = str_replace($search, $replace, $link);
			$src_images[$key]['new'] = $link;
			$image->setAttribute('src', $link);	
		}
		
		// Забираем содержимое body
		$ready_html = $doc->saveHTML();
		unset($doc);
		$pattern = '/\<body\>([\D\d]*)\<\/body\>/';
	
		$matches= array();
		$finish_html = '';
		
		if (preg_match($pattern, $ready_html, $matches))
			$finish_html = trim($matches[1]);
		
		return $finish_html;
	}
	
	
	//
	// Удаляет директорию
	//
	public function removeDirectory($dir)
	{
    if ($objs = glob($dir."/*"))			
       foreach($objs as $obj) 
         is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);       
    
    rmdir($dir);
  }
	
	//
	// Перемещает файлы
	// @return boolean
	private function move_file($last, $new)
	{
		$last = trim($last);
		$new = trim($new);
		if ($last[0] != '/') $last = '/' . $last;
		if ($new[0] != '/') $new = '/' . $new;
		$last = SITE_ROOT . $last;
		$new = SITE_ROOT . $new;
		
		if (is_file($last))
		{
			copy($last, $new);
			unlink($last);
			return true;
		}
		
		return false;
	}
	
	//
	// Получает список файлов в папке
	// @return array
	public function get_files_in_folder($path)
	{		
		$files = array();
		
		if (is_dir($path))
			$files = scandir($path);
		
		if ($files != false)
		{
			$unset_s = array('.', '..'); 
			$files = array_diff($files, $unset_s);
		} 
		else $files = array();
		
		return $files;
	}	
	
	// Обнуляет поле указания на ссылку в меню
	public function unlink_menu($mlid)
	{
		if ($mlid > 0)
			$this->db->Update('posts', array('mlid' => 0), "mlid = ?", array($mlid));
	}
	
}