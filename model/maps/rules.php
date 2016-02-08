<?php 

/*
	Массив правил для таблицы
	$table_name = array(
	
		# формат записи 'название_правила' => array('имя_поля1', 'имя_поля2')		
		'fields' => array(),       // - допустимые поля в передаваемом массиве 
															 //    (названий полей в таблице $table_name, 
															 //    которым могут быть обновлены и добавлены, 
															 //    первичный ключ не входит в массив(обычно)
		'not_empty' => array(),    // - массив полей которые не могут пустыми
		'html_allowed' => array(), // - массив полей в которых разрешены теги
		'unique' => array(),       // - массив полей которые должны быть уникальными
		'date' => array(),         // - массив полей для проверки корректности даты
		'phone' => array(),        // - массив полей для проверки формата телефона
		'email' => array(),        // - массив полей для проверки формата email
		'email_domain' => array(), // - массив полей для проверки email домена
		'not_url' => array(),      // - массив полей для проверки url на допустимые символы
		
		# формат записи 'название_правила' => array('имя_поля1' => '3', 'имя_поля2' => '10')		
		'min_length' => array(),   // - массив полей с ограничением минимальной длины поля    
		'max_length' => array(),   // - массив полей с ограничением максимальной длины поля
		'exact_length' => array(), // - массив полей с точным совпадением длины поля

		# формат записи 'название_правила' => array('имя_поля1' => array('3', '20'), 
		#																					 'имя_поля2' => array('10', '220'))		
		'range' => array(),        // - массив полей с ограничением длины в диапазоне от до
		
		# формат записи 'название_правила' => array('имя_поля1' => 'имя_поля_для_сравнения', 
		#                                           'имя_поля2' => 'имя_поля_для_сравнения2')
		'equals' => array(),       // - массив полей для проверки на равенство другим полям
		'not_equals' => array(),   // - массив полей которые не могут быть равны другим		
		
		# формат записи 'название_правила' => array('имя_поля1' => array('min' => 10, 'max' => 25,'int' => true);
		# min - минимальное значение, max - максимальное значение, int - только целые числа                                                               
		# любой из 3 параметров можно не задавать, при передачи без параметров проверит только число ли это 
		'number' => array(),       // - массив полей для проверки как числа
		
		# формат записи 'название_правила' => array('имя_поля1' => '"синоним_поля1"', 
		#																					 'имя_поля2' => '"синоним_поля2"')
		'labels' => array(),       // - позволяет присвоить полю человекопонятное название (обязательно для заполнения!!!)
		
		# Формат записи 'pk' => 'имя_поля'
		'pk' => 'post_id',	       // первичный ключ у таблицы	
		
		#
		'special_rules' => array(), // - специальные правила
	);
*/	
	
$posts = array(
	'fields' => array('post_title', 'post_content', 'post_date_create', 
										'post_date_update',	'post_author', 'post_status', 
										'post_type', 'comment_status', 'mlid'),
	'not_empty' => array('post_title'),
	'html_allowed' => array('post_content'),
	'unique' => array(),
	'min_length' => array(),
	'max_length' => array(),
	'range' => array(),
	'not_url' => array(),
	'special_rules' => array(),
	'labels' => array(
		'post_title' => '"Название"',
		'post_content' => '"Текст поста"'
	),
	'pk' => 'post_id'		
);

$users = array(
	'fields' => array('login', 'password', 'role_id', 'user_name', 'mimemail'),
	'not_empty' => array('login', 'password', 'role_id', 'user_name'),
	'unique' => array('login'),
	'min_length' => array('password' => 6),
	'max_length' => array('password' => 12),
	'email' => array('login'),
	'labels' => array(
		'login' => '"Email"',
		'password' => '"Пароль"',
		'user_name' => '"Имя"',
		'role_id' => '"Роль на сайте"',
		'mimemail' => '"Тип сообщений рассылки"'
	),
	'pk' => 'user_id'		
);

$menu = array(
	'fields' => array('menu_title', 'menu_description', 'block_id'),
	'not_empty' => array('menu_title'),
	'labels' => array(
		'menu_title' => '"Название меню"',
		'menu_description' => '"Описание меню"'
	),
	'pk' => 'menu_id'
);

$menu_link = array(
	'fields' => array('plid', 'menu_id', 'link_path', 
										'link_title', 'link_description', 'weight'),
	'not_empty' => array('plid', 'menu_id', 'link_title'),
	'labels' => array(
		'plid' => '"Родительская ссылка"',
		'menu_id' => '"Родительская ссылка"',
		'link_path' => '"Ссылка"',
		'link_title' => '"Название ссылки в меню"',
		'link_description' => '"Описание ссылки"',
		'weight' => '"Вес"'
	),
	'pk' => 'mlid'
);

$roles = array(
	'fields' => array('role_name', 'role_description'),
	'not_empty' => array('role_name'),
	'labels' => array(
		'role_name' => '"Название роли"',
		'role_description' => '"Описание роли"',
	),
	'pk' => 'role_id'
);

$options = array(
	'fields' => array('option_name', 'option_value'),
	'not_empty' => array('option_value'),
	'labels' => array(
		'option_name' => '"Название настройки"',
		'option_value' => '"Значение"',
	),
	'pk' => 'option_name'
);

$comments = array(
	'fields' => array('comment_content', 'dateCreate', 
										'dateUpdate', 'comment_author', 
										'comment_author_email', 
										'comment_author_id', 
										'comment_approved'),
	'not_empty' => array('comment_content', 'comment_author', 
									'comment_author_email'),
	'email' => array('comment_author_email'),
	'labels' => array(
		'comment_author' => '"Имя"',
		'comment_author_email' => '"Email"',
		'comment_content' => '"Комментарий"'
	),
	'pk' => 'comment_id'
);

$blocks = array(
	'fields' => array('block_name', 'block_title', 'block_desc'),
	'pk' => 'block_id'
);

$gallery = array(
	'fields'=> array('gallery_title', 'gallery_desc'),
	'not_empty' => array('gallery_title'),
	'labels' => array(
		'gallery_title' => '"Название"',
		'gallery_desc' => '"Описание"'
	),
	'pk' => 'gallery'
);

$mediafile = array(
	'fields'=> array('name', 'title', 'alt', 'type'),
	'not_empty' => array('name'),
	'labels' => array(
		'name' => '"Имя файла"',
		'title' => '"Заголовок"',
		'alt' => 'Альтернативный текст'
	),
	'pk' => 'fid'
);

$mailinglists = array(
	'fields' => array('listname', 'blurb', 'is_show'),
	'not_empty' => array('listname'),
	'labels' => array(
		'listname' => '"Заголовок"',
		'blurb' => '"Описание"',
		'is_show' => '"Состояние"'
	),	
	'pk' => 'listid'
);

$mail = array(
	'fields' => array('email', 'subject', 'listid', 'status', 'send', 'modified', 'images'),
	'not_empty' => array('email', 'subject', 'listid'),
	'labels' => array(
		'email' => '"Адрес отправителя"',
		'subject' => '"Тема письма"',
		'listid' => '"Список рассылки"'
	),	
	'pk' => 'mail_id'
);

$poll = array(
	'fields' => array('question', 'runtime'),
	'not_empty' => array('question'),
	'labels' => array(
		'question' => '"Вопрос"',
		'runtime' => '"Время создания"'
	),	
	'pk' => 'pid'
);

$poll_answers = array(
	'fields' => array('pid', 'weight', 'answer'),
	'not_empty' => array('pid', 'answer'),
	'label' => array(
		'pid' => '"Вопрос"',
		'weight' => '"Вес"',
		'answer' => '"Ответ"'
	),
);

return array(
	'posts' => $posts,
	'users' => $users,
	'menu' => $menu,
	'menu_link' => $menu_link,
	'roles' => $roles,
	'options' => $options,
	'comments' => $comments,
	'blocks' => $blocks,
	'gallery' => $gallery,
	'mediafile' => $mediafile,
	'mailinglists' => $mailinglists,
	'mail' => $mail,
	'poll' => $poll,
	'poll_answers' => $poll_answers
);