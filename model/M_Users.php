<?php

//
// Менеджер пользователей
//
class M_Users extends M_Model
{
	private static $instance;  // экземпляр класса
	private $sid;              // идентификатор текущей сессии
	private $uid;              // идентификатор текущего пользователя
	private $onlineMap;        // карта пользователей
	private $privsActive = null;
	
	//
	// Получение экземпляра класса
	// результат - экземпляр класса MSQL
	//
	public static function Instance()
	{
		if(self::$instance == null)
			self::$instance = new M_Users();
		
		return self::$instance;
	}
	
	// 
	// Конструктор
	//
	public function __construct()
	{
		parent::__construct('users', 'user_id');
		$this->sid = null;
		$this->uid = null;
		$this->onlineMap = null;
	}
	
	//
	// Получить всех пользователей с ролями и привилегиями
	//
	public function all()
	{
		$query =  "SELECT u.user_id, u.login, u.user_name,
								r.role_description, p.priv_description 
								FROM users u
								LEFT JOIN roles r USING(role_id)
								LEFT JOIN privs_roles pr USING(role_id)
								LEFT JOIN privs p USING(priv_id)";
		return $this->db->Select($query);
	}
	
	//
	// Добавление пользователя
	//
	public function add($fields)
	{		
		$this->errors = array(); // обнуляем список ошибок
		$valid = $this->load_validation(); //подгружаем модуль валидации
		
		$valid->execute($fields);
		if ($valid->good())
		{
			$clearObj = $valid->getObj();
			$clearObj['password'] = md5($clearObj['password']);
	
			return $this->db->Insert($this->table, $clearObj);;
		}
		$this->errors = $valid->errors();
		
		return false;		
	}
	
	//
	// Редактирование данных пользователя
	//
	public function edit($user_id, $fields)
	{
		if(trim($fields['password']) == '')
			unset($fields['password']);		
		
		$user_id = (int)$user_id;		
		
		$this->errors = array(); // обнуляем список ошибок
		$valid = $this->load_validation(); //подгружаем модуль валидации
		
		$valid->execute($fields, $user_id);
		
		if($valid->good())
		{
			$clearObj = $valid->getObj();
			
			if (isset($clearObj['password']))
				$clearObj['password'] = md5($clearObj['password']);
			
			$this->db->Update($this->table, $clearObj, "{$this->pk}=?", array($user_id));
			return true;
		}
		$this->errors = $valid->errors();
		
		return false;
	}	
	
	//
	// Очистка неиспользуемых сессий
	//
	public function ClearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - 60 * 20);		
		$where = "time_last < ?";
		$this->db->Delete('sessions', $where, array($min));
	}
	
	//
	// Авторизация
	// $login     - логин
	// $password  - пароль
	// $remember - нужно ли запомнить в куках
	// результат - true или false
	//
	public function Login($login, $password, $remember = true) 
	{
		if($login == null)
			return false;

		// вытаскиваем пользователя из БД
		$user = $this->GetByLogin($login); 
		if($user == null)
			return false;
		
		//проверяем пароль
		if ($user['password'] !=  md5($password))
			return false;
		
		//запоминаем имя и md5(пароль)
		if ($remember) 
		{
			$expire = time() + 3600 * 24 * 1000;
			setcookie('login', $login, $expire, BASE_URL);
			setcookie('password', md5($password), $expire, BASE_URL);
		}
		
		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($user['user_id']);
		
		return true;
	}
	
	//
	// Выход
	//
	public function Logout()
	{
		setcookie('login', '', time() - 1, BASE_URL);
		setcookie('password', '', time() - 1, BASE_URL);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);
		$this->sid = null;
		$this->sid = null;
	}
	
	//
	// Получение пользователя
	// $user_id - если не указан, брать текущего
	// результат - объект пользователя
	// 
	public function Get($user_id = null) 
	{
		// Если id_user не указан, берем его по текущей сессии.
		if ($user_id == null)
			$user_id = $this->GetUid();
		
		if($user_id == null)
			return null;
		
		// А теперь просто возращаем пользователя по id_user.
		$user_id = (int)$user_id;
		$query = "SELECT u.* FROM users u WHERE user_id = $user_id";
		$result = $this->db->Select($query);
		return $result[0];
	}

	//
	// Получает пользователя по логину
	//
	public function GetByLogin($login)
	{		
		$query = "SELECT u.* FROM users u WHERE login=?";
		$result = $this->db->Select($query, array($login));
		if (!$result)
			$result[0] = false;
		return $result[0];
	}
	
	//
	// Проверка наличия привилегии
	// $priv 		- имя привилегии
	// $id_user		- если не указан, значит, для текущего
	// результат	- true или false
	//
	public function Can($priv, $user_id = null)
	{		
		// Если id_user не указан, берем его по текущей сессии.
		if ($user_id == null)
			$user_id = (int)$this->GetUid();
		
		if($user_id == null)
			return false;
		
		
		$query = "SELECT COUNT(*) c FROM users u 
					INNER JOIN privs_roles pr USING (role_id)
					INNER JOIN privs p USING(priv_id)
					WHERE u.user_id=? and (p.priv_name=? or p.priv_name=?)";
		$result = $this->db->Select($query, array($user_id, $priv, 'ALL'));
		return ($result[0]['c'] > 0);		
	}

	public function ActiveCan($priv)
	{
		$privs = $this->GetPrivs();
		return in_array($priv, $privs) || in_array('ALL', $privs);
	}
	
	public function GetPrivs()
	{
		if ( $this->privsActive === null)
		{
			$user_id = $this->GetUid();
			
			if($user_id == null)
				return array();
			
			$user_id = (int)$user_id;
			
			$query = "SELECT priv_name FROM users u
								INNER JOIN privs_roles USING(role_id)
								INNER JOIN privs USING(priv_id)
								WHERE user_id = ?";
			$privs = $this->db->Select($query, array($user_id),  PDO::FETCH_COLUMN);	
			
			$this->privsActive = !empty($privs) ? $privs : array();
		}
		
		return $this->privsActive;
	}
	

	//
	// Проверка активности пользователя
	// $id_user		- идентификатор
	// результат	- true если online
	//
	public function IsOnline($user_id)
	{		
		if($this->onlineMap == null)
		{
			$query = "SELECT DISTINCT user_id FROM sessions";
			$result = $this->db->Select($query);
			
			foreach ($result as $item)
				$this->onlineMap[$item['user_id']] = true;
		}
		
		return ($this->onlineMap[$user_id] != null);
	}	
	
	public function getRoles()
	{
		return M_Roles::Instance()->all();
	}
	
	//
	// Получение id текущего пользователя
	// результат - UID
	//
	public function GetUid()
	{
		// Проверка кеша
		if($this->uid != null)
			return $this->uid;
		
		//Берем по текущей сессси
		$sid = $this->GetSid();
		
		if ($sid == null)
			return null;
		
		
		$query = "SELECT user_id FROM sessions WHERE sid = ?";
		$result = $this->db->Select($query, array($sid));
		
		// Если сессию не нашли - значит пользователь не авторизован
		if (count($result) == 0)
			return null;
		
		// Если нашли - запомним ее.
		$this->uid = $result[0]['user_id'];
		return $this->uid;
	}
	
	//
	// Функция возращает идентификатор текущей сессии
	// результат - SID
	//
	private function GetSid()
	{
		// Проверка кеша.
		if ($this->sid != null)
			return $this->sid;
		
		// Ищем SID в сессии
		$sid = isset($_SESSION['sid']) ?  $_SESSION['sid'] : null;
		
		// Если нашли, попробуем обновить time_last в базе.
		// Заодно и проверим, есть ли сессия там.
		if ($sid != null) 
		{
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s');
			$where = "sid = ?";
			$affected_rows = $this->db->Update('sessions', $session, $where, array($sid));
			
			if ($affected_rows == 0) 
			{
				$query = "SELECT count(*) c FROM sessions WHERE sid = ?";
				$result = $this->db->Select($query,  array($sid));
				if($result[0]['c'] == 0)
					$sid = null;
			}
		}
			
		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключится
		if ($sid == null && isset($_COOKIE['login'])) 
		{
			$user = $this->GetByLogin($_COOKIE['login']);
			
			if ($user != null && $user['password'] == $_COOKIE['password'])
				$sid = $this->OpenSession($user['user_id']);
		}
		
		// Запоминаем в кеш.
		if ($sid != null)
			$this->sid = $sid;
		
		// Возращаем, наконец, SID
		return $sid;		
	}
	
	//
	// открытие новой сессии
	// результат - SID
	//
	private function OpenSession($user_id)
	{
		//генерируем SID
		$sid = $this->GenerateStr(10);
				
		//вставляем SID в БД
		$now = date('Y-m-d H:i:s');
		$session = array();
		$session['user_id'] = $user_id;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;
		$this->db->Insert('sessions', $session);
		
		//регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;
		
		//возращаем SID
		return $sid;
	}
	
	//
	// Генерация случайной последовательности
	// $length - ее длина
	// результат - случайная строка
	//
	private function GenerateStr($lenght=10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;		
		
		for($i = 1; $i <= $lenght; $i++)
			$code .= $chars[mt_rand(0, $clen)];
		
		return $code;
	}
	
	//
	// Генерирует случайный пароль 
	// @param $min_length - минимальная длина
	// @param $max_length - максимальная длина
	// @return string
	private function GeneratePassword($min_length, $max_length)
	{
		$str = str_shuffle($this->GenerateStr($max_length * 5));
		
		$start = mt_rand(0, $max_length * 4 - 1);
		$length = mt_rand($min_length, $max_length);
		
		return mb_substr($str, $start , $length);
	}
	
	//
	// Отправляет пользователю новый пароль
	// @param $to_email - почта пользователя 
	// @param $password - пароль 
	// @return boolean
	private function notify_password($to_email, $password)
	{
		$from_name = SITE_NAME;		
		$from_address = SITE_EMAIL;
		
		$headers   = array();
		$headers[] = "Content-type: text/plain; charset=utf-8";
		$headers[] = "From: $from_name <$from_address>";
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		$subject = "Вход на сайт <$from_name> изменен";
		$text = "Ваш пароль для входа на сайт <$from_name> изменен на " . $password . "\r\n"
						. "Пожалуйста, учтите это при будущем входе в систему. \r\n";
		
		return mail($to_email, $subject, $text, implode("\r\n", $headers)); 
	}
	
	//
	// Меняет пароль пользователя на случайный
	// и отправляет письмо пользователю с новым паролем
	// @$login - почта пользователя 
	// @return boolean
	public function reset_password($login)
	{
		$login = filter_var($login, FILTER_VALIDATE_EMAIL);
		
		if (!$login)
		{
			$this->errors['login'] = "Неправильный формат email-адреса";
			return false;
		}
		
		$user = $this->GetByLogin($login);
		
		if (empty($user))
		{
			$this->errors['login'] = "Пользователь с email < $login > не найден";
			return false;
		}
		$user_id = $user['user_id'];
		$fields['password'] = $this->GeneratePassword(6, 12);
		if ($this->db->beginTransaction()) 
		{
			if ($this->edit($user_id, $fields) &&
				$this->notify_password($login, $fields['password']))
				if ($this->db->commit())
					return true;
			$this->db->rollBack();
		}		
		return false;
	}
	
	// смена пароля
	public function change_pass($user, $fields)
	{
		$new_pass = trim($fields['newpassword']);
		$new_pass2 = trim($fields['newpassword2']);
		$this->errors = array();
		
		// проверяем пароль
		if ($user['password'] !=  md5(trim($fields['password'])))
			$this->errors['oldpassword'] = "Неверно введен текущий пароль";
		
		if (empty($new_pass) || empty($new_pass2))
			$this->errors['password'] = "Вы не заполнили поля для нового пароля";
		
		if ($new_pass !== $new_pass2)
			$this->errors['password'] = "Пароли не совпадают";
		
		if (!empty($this->errors))
			return false;
		
		return $this->edit($user['user_id'], array('password' => $new_pass));
	}
}