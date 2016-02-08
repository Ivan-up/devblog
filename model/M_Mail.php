<?php 
class M_Mail extends M_Model
{
	private static $instance;
	
	//
	// Получение единственного экземпляра(одиночка)
	//	
	public static function Instance()
	{
		if (self::$instance == null)
			self::$instance = new self();
		
		return self::$instance;
	}
	
	public function __construct()
	{
		parent::__construct('mail', 'mail_id');
	}
	
	// Добавляет письмо
	public function addmail($fields, $files)
	{	
		$this->errors = array();
		
		$mFiles = M_Files::Instance();
		
		if (!$files['htmlfile']['name'] ||
				(M_Files::get_extention_file($files['htmlfile']['name']) != 'html'))
		{
			$this->errors['htmlfile'] = 'Не выбран html файл или неверный формат';
		}
		
		if (!$files['txtfile']['name'] ||
				(M_Files::get_extention_file($files['txtfile']['name']) != 'txt'))
		{
			$this->errors['txtfile'] = 'Не выбран txt файл или неверный формат';
		}
		
		if (!empty($this->errors))
			return false;
		
		$mailid = parent::add($fields);
		
		if (!$mailid) {
				return false;
		}
		$folder = SITE_ROOT . '/'. MAILING_DIR . (int)$fields['listid'] . '/';
		@mkdir($folder, 0700);
		$folder .=  $mailid . '/';
		@mkdir($folder, 0700);
		
		$html = $mFiles->upload_file($files['htmlfile'], $folder, null, array('html' => array('text/html')), 'index');
		//rename($folder.$html, $folder.'index.html');
		$txt = $mFiles->upload_file($files['txtfile'], $folder, null, array('txt' => array('text/plain')), 'text');		
		//rename($folder.$txt, $folder.'text.txt');
		
		
		$image = array();
		$images = array();
		
		$i = 0;		
		while ($files['images']['name'][$i] && 
					($files['images']['tmp_name'][$i] != 'none'))
		{
			$image['name'] = $files['images']['name'][$i];
			$image['type'] = $files['images']['type'][$i];
			$image['tmp_name'] = $files['images']['tmp_name'][$i];
			$image['error'] = $files['images']['error'][$i];
			$image['size'] = $files['images']['size'][$i];
			
			$img_new_name = M_Files::get_name_without_ext($image['name']);
			
			$file = $mFiles->set_type('image')->upload_file($image, $folder, null, null, $img_new_name);
			
			$filename = isset($file['name']) ? $file['name'] : '';
			
			if ($filename && ($size = getimagesize($folder.$filename))) 			
				$images[] = array('name' => $filename, 'mime' => $size['mime']);
			
			++$i;
		}
		
		if (!empty($images))		
			$this->edit($mailid, array('images' => base64_encode(serialize($images))));
		
		return true;
	}
	
	// Отправляет письмо
	// @param $mailid - номер письма 
	// @return array() - информацию о письме(status, mail_id, list_id)
	public function send($mailid)
	{
		$mailid = (int)$mailid;
		$mail = $this->get($mailid);
		
		if (!$mail)
			return false;
		
		$result = array('status' => '', 'mail_id' => $mail['mail_id'], 'listid' => $mail['listid']);
		$from_name = SITE_NAME;		
		$from_address = SITE_EMAIL;
		$mail_dir = MAILING_DIR ;
		
		
		if (empty($mail))
			return false;		
		
		// Заголовки для писем 
		$headers = array();
		$headers[] = "From: $from_name <$from_address>";
		$headers[] = "X-Mailer: PHP/" . phpversion();
		 
		// Cоздать заголовки сообщений для html версии
		$headers_html = array();		
		$headers_html[] = "Content-type: text/html; charset=utf-8";
		$headers_html = array_merge($headers_html, $headers);
		$headers_html_str = implode("\r\n", $headers_html);		
		
		// Cоздать заголовки сообщений для текстовой версии
		$headers_txt = array();		
		$headers_txt[] = "Content-type: text/plain; charset=utf-8";
		$headers_txt = array_merge($headers_txt, $headers);
		$headers_txt_str = implode("\r\n", $headers);
		
		// папка, где расположены файлы
		$folder_base = $mail_dir . (int)$mail['listid'] . '/' . (int)$mail['mail_id'] . '/';
		$folder = SITE_ROOT . '/'. $folder_base;
		
		// файлы сообщений
		$file_txt = $folder . "text.txt";
		$html_file = $folder . "index.html";
		
		// Прочитать текстовую версию сообщения 
		$tfp = fopen($file_txt, "r");
		$text = fread($tfp, filesize($file_txt));
		fclose($tfp);
		
		if ($mail['status'] == 'STORED')
		{
			$images = unserialize(base64_decode($mail['images']));
			$prefix = 'http://'.$_SERVER['SERVER_NAME'] . BASE_URL . $folder_base;								
			$new_name = basename($html_file);
			
			// Зачитываем html-файл и делаем необходимые замены для картинок
			$html = $this->set_images_link($html_file, $prefix, $images, $new_name);
			
			// Отправить HTML-версию сообщения администратору
			mail($mail['email'], $mail['subject'], $html, $headers_html_str);
			
			// Отправить текстовую версию сообщения администратору
			mail($mail['email'], $mail['subject'], $text, $headers_txt_str);
			
			$new_status = 'TESTED';
			// Меняем статус письма на протестировано
			if ($this->Edit($mail['mail_id'], array('status' => $new_status)))
				$result['status'] = $new_status;
		}
		else if ($mail['status'] == 'TESTED')
		{
			$query = "SELECT u.user_name, m.email, u.mimemail
									FROM users u
								INNER JOIN mailinglists_users m
									ON u.login = m.email
								WHERE m.listid =". $mail['listid'];
			// получаем всех подписчиков
			$subscribers = $this->db->Select($query);
			
			// Читаем HTML-версию версию
			$hfp = fopen($html_file, "r");
			$html = fread($hfp, filesize($html_file));
			fclose($hfp);
			
			foreach ($subscribers as $subscriber) 
			{
				if ($subscriber['mimemail'] == 'H') 
				{
					// Отправить HTML-версию всем желающим подписчикам
					mail($subscriber['email'], $mail['subject'], $html, $headers_html_str);
				} else {
					// Отправить текстовую версию подписчикам,
					// которые не желают иметь дело с HTML 
					mail($subscriber['email'], $mail['subject'], $text,	$headers_txt_str);
				}
			}
			
			$new_status = 'SENT';
			// Меняем статус письма на отправлено
			if ($this->Edit($mail['mail_id'], array('status' => $new_status, 'send' => date('Y-m-d H:i:s'))))
				$result['status'] = $new_status;
		}
		
		return $result;		
	}
	
	// возращает число неотправленных писем
	// return int
	public function count_unsent_mail()
	{
		$query = "SELECT COUNT(*) FROM mail WHERE status != 'SENT'";
		$result = $this->db->Select($query, null, PDO::FETCH_COLUMN);
		return (int)$result[0];
	}
	
	// Удаляет письмо 
	public function delete_mail($listid, $mail_id)
	{
		$dir = SITE_ROOT . '/'. MAILING_DIR . (int)$listid . '/'. (int)$mail_id . '/';
		parent::delete($mail_id);
		if (file_exists($dir))
			M_Files::Instance()->remove_directory($dir);
		return true;
	}
	
	// меняет в файле пути для ссылок
	// Например, src="img1.jpg" -> src="http://startupdev.ru/upload_files/mailing/1/2/img1.jpg"
	//           url("img1.jpg") -> url("http://startupdev.ru/upload_files/mailing/1/2/img1.jpg")
	// @param $filename - имя файла (полный путь), в котором делаем замену
	// @param $prefix   - что нужно дописать перед именем картинки,  
	//                    например, 'http://startupdev.ru/upload_files/mailing/1/2/'
	// @param $images   - массив картинок для которых производить замену [0=>['name'=>'1.jpg'],...]
	// @param $new_name - имя файла куда будет сохранен результат, например 'dev.html'
	//                    если не передать парамент, сохранения в файл не будет
	// @return string   - содержимое файла с произведенным заменами
	private function set_images_link($filename, $prefix, $images, $new_name='')
	{
		$folder = dirname($filename) . '/';
		
		@$page = file_get_contents($filename);
	
		if (!$page)
			return false;
		
		// Если нет картинок, просто сохраняем файл
		// и возращаем содержимое
		if (empty($images))
		{
			file_put_contents($folder . $new_name, $page);
			return $page;
		}
		
		$folder = dirname($filename) . '/';
		
		$index = 0;
		$patterns = array();
		$replacements = array();
		var_dump($images);
		foreach ($images as $img) 
		{
			if ((is_file($folder . $img['name'])) && ($type = exif_imagetype($folder . $img['name'])))
			{
				$link = $prefix . $img['name'];
				$patterns[$index] = '/(src|url) {0,2}([=\(]) {0,2}([\'\"]) {0,2}(' . preg_quote($img['name']) . ')/';
				$replacements[$index] = '$1$2$3'.$link;
				++$index;
			}
		}
		
		if ($patterns && $replacements)
			$html = preg_replace($patterns, $replacements , $page);
		else 
			$html = $page;
		
		if ($new_name)
		  file_put_contents($folder . $new_name, $html);
		
		return $html;
	}
}