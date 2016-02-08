-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 08 2016 г., 00:24
-- Версия сервера: 5.5.45
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `devblog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `block_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `block_name` varchar(100) NOT NULL COMMENT 'Машинное имя блока',
  `block_title` varchar(100) NOT NULL COMMENT 'Заголовок блока',
  `block_desc` varchar(100) NOT NULL COMMENT 'Описание блока',
  PRIMARY KEY (`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `blocks`
--

INSERT INTO `blocks` (`block_id`, `block_name`, `block_title`, `block_desc`) VALUES
(1, '_content_page', 'Содержимое', 'Основной текст'),
(2, 'searchBox', 'Поиск', 'Поиск по сайту'),
(3, 'postsMostViews', 'Cамые читаемые заметки', 'Cамые читаемые заметки'),
(4, 'loginBox', 'Авторизация', 'Авторизация на сайте'),
(5, 'postsMostComments', 'Самые обсуждаемые ', 'Самые обсуждаемые '),
(6, 'lastestComments', 'Последние добавленные комментария', 'Последние добавленные комментария'),
(7, 'postsArchive', 'Архив записей', 'Архив записей'),
(8, 'menu_2', 'Веб-технологиии', 'Меню'),
(9, 'menu_3', 'Полезные ресурсы', 'Меню');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `comment_content` text NOT NULL COMMENT 'Текст комментария',
  `dateCreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания комментария',
  `dateUpdate` timestamp NULL DEFAULT NULL COMMENT 'Дата последнего изменения комментария',
  `comment_author` varchar(100) NOT NULL COMMENT 'Имя автора',
  `comment_author_email` varchar(100) NOT NULL COMMENT 'E-mail адрес автора',
  `comment_author_id` varchar(100) NOT NULL COMMENT 'Id автора',
  `comment_approved` tinyint(1) DEFAULT '0' COMMENT 'Статус комментария',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Структура таблицы `commentsTree`
--

CREATE TABLE IF NOT EXISTS `commentsTree` (
  `idAncestor` int(10) unsigned NOT NULL COMMENT 'Предок',
  `idDescendant` int(10) unsigned NOT NULL COMMENT 'Потомок',
  `idNearestAncestor` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Ближайщий предок',
  `level` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Уровень вложения',
  `idSubject` int(10) unsigned NOT NULL COMMENT 'Id комментируемого материала',
  `dataCreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
  PRIMARY KEY (`idAncestor`,`idDescendant`),
  KEY `idSubject` (`idSubject`),
  KEY `commentsTree_ibfk_2` (`idDescendant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `gallery_title` varchar(100) NOT NULL COMMENT 'Название галереи',
  `gallery_desc` text COMMENT 'Описание галереи',
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `gallery_title`, `gallery_desc`) VALUES
(1, 'Сборник шпаргалок', ''),
(2, 'Зверополис', '');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_mediafile`
--

CREATE TABLE IF NOT EXISTS `gallery_mediafile` (
  `gallery_id` int(10) unsigned NOT NULL COMMENT 'Внешняя ссылка на id gallery',
  `fid` int(10) unsigned NOT NULL COMMENT 'Внешняя ссылка на id mediafile',
  `weight` tinyint(10) NOT NULL DEFAULT '0' COMMENT 'Вес при сортировки',
  PRIMARY KEY (`gallery_id`,`fid`),
  KEY `mediafile_ibfk` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gallery_mediafile`
--

INSERT INTO `gallery_mediafile` (`gallery_id`, `fid`, `weight`) VALUES
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(1, 6, 5),
(1, 7, 6),
(1, 8, 0),
(1, 9, 7),
(1, 11, 8),
(1, 15, 1),
(2, 17, 0),
(2, 18, 0),
(2, 19, 0),
(2, 20, 0),
(2, 21, 0),
(2, 22, 0),
(2, 23, 0),
(2, 24, 0),
(2, 25, 0),
(2, 26, 0),
(2, 27, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `mail_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор письма',
  `email` varchar(100) NOT NULL COMMENT 'Адрес отправителя',
  `subject` varchar(100) NOT NULL COMMENT 'Тема письма',
  `listid` int(10) unsigned NOT NULL COMMENT 'Внешняя ссылка на id mailinglists',
  `status` varchar(10) NOT NULL COMMENT 'Статус сообщения',
  `send` timestamp NULL DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `images` text NOT NULL,
  PRIMARY KEY (`mail_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mailinglists`
--

CREATE TABLE IF NOT EXISTS `mailinglists` (
  `listid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор листа',
  `listname` varchar(100) NOT NULL COMMENT 'Название листа рассылки',
  `blurb` varchar(255) DEFAULT NULL COMMENT 'Описания листа рассылки',
  `is_show` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`listid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `mailinglists`
--

INSERT INTO `mailinglists` (`listid`, `listname`, `blurb`, `is_show`) VALUES
(1, 'Новости', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mailinglists_users`
--

CREATE TABLE IF NOT EXISTS `mailinglists_users` (
  `email` varchar(100) NOT NULL,
  `listid` int(11) NOT NULL,
  PRIMARY KEY (`email`,`listid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mediafile`
--

CREATE TABLE IF NOT EXISTS `mediafile` (
  `fid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `name` varchar(256) NOT NULL COMMENT 'Имя файла',
  `title` varchar(256) DEFAULT NULL COMMENT 'Заголовок для файла',
  `alt` varchar(256) NOT NULL COMMENT 'Альтернативный текст',
  `type` enum('image','video','audio') NOT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Дамп данных таблицы `mediafile`
--

INSERT INTO `mediafile` (`fid`, `name`, `title`, `alt`, `type`) VALUES
(2, '1050856.png', 'Git', '', 'image'),
(3, '1970122.png', 'Jquery', '', 'image'),
(4, '1042264.png', 'Регулярные выражения', '', 'image'),
(6, '1678161.png', NULL, '', 'image'),
(7, '418521.png', NULL, '', 'image'),
(8, '6897584.png', 'CSS', '', 'image'),
(9, '1743366.jpg', NULL, '', 'image'),
(11, '2701014.png', 'Linux commands', '', 'image'),
(13, 'Zveropolis_-_vtoroy_treyler.mp4', 'Зверополис', '', 'video'),
(15, '8070269.png', 'HTML', '', 'image'),
(17, '5624792.jpg', NULL, '', 'image'),
(18, '4627606.jpg', NULL, '', 'image'),
(19, '5148555.jpg', NULL, '', 'image'),
(20, '6096395.jpg', NULL, '', 'image'),
(21, '9380323.jpg', NULL, '', 'image'),
(22, '9388933.jpg', NULL, '', 'image'),
(23, '8689672.jpg', NULL, '', 'image'),
(24, '2248652.jpg', NULL, '', 'image'),
(25, '87864.jpg', NULL, '', 'image'),
(26, '5540196.jpg', NULL, '', 'image'),
(27, '8901782.jpg', NULL, '', 'image'),
(29, '9477355.mp3', 'Rammstein - Nebel', '', 'audio');

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `menu_title` varchar(255) NOT NULL COMMENT 'Заголовок меню',
  `menu_description` varchar(1000) DEFAULT NULL COMMENT 'Описание меню',
  `block_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `block_id` (`block_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_title`, `menu_description`, `block_id`) VALUES
(1, 'Главное меню', '', 0),
(2, 'Веб-технологиии', '', 8),
(3, 'Полезные ресурсы', '', 9);

-- --------------------------------------------------------

--
-- Структура таблицы `menu_link`
--

CREATE TABLE IF NOT EXISTS `menu_link` (
  `mlid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `plid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Id родителя, при id=0 значит корневой элемент',
  `menu_id` int(10) unsigned NOT NULL COMMENT 'Внешняя ссылка на id таблицы menu',
  `link_path` varchar(255) NOT NULL COMMENT 'Ссылка пункта меню',
  `link_title` varchar(255) NOT NULL COMMENT 'Заголовок пункта меню',
  `link_description` varchar(1000) DEFAULT NULL COMMENT 'Описание пункта меню',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT 'Вес пункта при сортировки',
  PRIMARY KEY (`mlid`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Дамп данных таблицы `menu_link`
--

INSERT INTO `menu_link` (`mlid`, `plid`, `menu_id`, `link_path`, `link_title`, `link_description`, `weight`) VALUES
(2, 0, 2, 'page/post/1', 'Серверные', '', -10),
(3, 2, 2, 'page/post/2', 'MySQL', '', -1),
(4, 3, 2, 'page/post/3', 'Нормализация БД', '', 0),
(5, 3, 2, 'page/post/4', 'Функции', '', 0),
(6, 0, 2, 'page/post/5', 'Библиотеки и инструменты', '', 10),
(7, 6, 2, 'page/post/6', 'Git команды', '', 0),
(8, 0, 1, 'page/contacts', 'Контакты', '', 3),
(9, 0, 1, 'page/audio', 'Аудио', '', 0),
(10, 0, 1, 'page/video', 'Видео', '', 1),
(11, 0, 1, 'page/galleries', 'Галерии', '', -1),
(12, 0, 2, 'page/post/7', 'Javascript', '', -5),
(13, 12, 2, 'page/post/8', 'Работа  с файлами', '', 0),
(14, 12, 2, 'page/post/9', 'Основы Ajax', '', 0),
(15, 12, 2, 'page/post/10', 'Video и Audio', '', 0),
(16, 12, 2, 'page/post/11', 'Drag-N-Drop', '', 0),
(17, 12, 2, 'page/post/12', 'FormData', '', 0),
(18, 0, 3, 'https://www.php.net/', 'PHP', '', 0),
(19, 0, 3, 'http://dev.mysql.com/', 'MySQL', '', 0),
(20, 0, 3, 'https://www.w3.org/', 'W3C', '', 0),
(21, 2, 2, 'page/post/13', 'PHP', '', 0),
(22, 21, 2, 'page/post/14', 'Регулярные выражения', '', 0),
(23, 22, 2, 'page/post/15', 'Функция preg_match', '', 0),
(24, 22, 2, 'page/post/16', 'Функция preg_replace', '', 0),
(25, 22, 2, 'page/post/17', 'Функция preg_replace_callback', '', 0),
(26, 5, 2, 'page/post/19', 'Строковые', '', 0),
(27, 5, 2, 'page/post/20', 'Дата и время', '', 0),
(28, 2, 2, 'page/post/21', 'Настройки htaccess', '', 0),
(29, 0, 3, 'https://developer.mozilla.org/ru/', 'MDN', '', 0),
(30, 6, 2, 'page/post/22', 'Препроцессоры', '', 0),
(31, 30, 2, 'page/post/23', 'LESS', '', 0),
(32, 30, 2, 'page/post/24', 'SCSS', '', 0),
(33, 6, 2, 'page/post/25', 'Emmet', '', 0),
(34, 6, 2, 'page/post/26', 'Grunt', '', 0),
(35, 0, 2, 'page/post/27', 'HTML&amp;CSS', '', 0),
(36, 0, 3, 'https://webref.ru', 'webref.ru', '', 0),
(37, 0, 1, 'page/post/28', 'Шпаргалки', '', -3),
(38, 0, 1, 'page/post/29/', 'О проекте', '', -10),
(40, 0, 1, 'page/post/31/', 'Комбо-контент', '', -5);

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `option_name` varchar(100) NOT NULL,
  `option_value` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `options`
--

INSERT INTO `options` (`option_name`, `option_value`) VALUES
('template_name', 'web_template');

-- --------------------------------------------------------

--
-- Структура таблицы `poll`
--

CREATE TABLE IF NOT EXISTS `poll` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор вопроса',
  `question` varchar(255) NOT NULL COMMENT 'Текст вопроса',
  `runtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `poll`
--

INSERT INTO `poll` (`pid`, `question`, `runtime`) VALUES
(5, 'Собираетесь смотреть мультфильм &quot;Зверополис&quot;?', '2016-02-01 15:28:36');

-- --------------------------------------------------------

--
-- Структура таблицы `poll_answers`
--

CREATE TABLE IF NOT EXISTS `poll_answers` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор ответа',
  `pid` int(10) unsigned NOT NULL,
  `answer` varchar(255) NOT NULL,
  `weight` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Дамп данных таблицы `poll_answers`
--

INSERT INTO `poll_answers` (`aid`, `pid`, `answer`, `weight`) VALUES
(10, 5, 'Да, пойду в кинотеатр.', 0),
(11, 5, 'Да, буду смотреть дома.', 2),
(12, 5, 'Нет, он не заинтересовал меня.', 3),
(13, 5, 'Нет, вообще не люблю мультфильмы.', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `poll_vote`
--

CREATE TABLE IF NOT EXISTS `poll_vote` (
  `vid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор голоса',
  `aid` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `hostname` varchar(128) DEFAULT NULL,
  `vote_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vid`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `poll_vote`
--

INSERT INTO `poll_vote` (`vid`, `aid`, `user_id`, `hostname`, `vote_time`) VALUES
(10, 11, 1, '127.0.0.1', '2016-02-01 17:29:54');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `post_title` varchar(256) NOT NULL COMMENT 'Заголовок',
  `post_content` text NOT NULL COMMENT 'Содержимое',
  `post_date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
  `post_date_update` timestamp NULL DEFAULT NULL COMMENT 'Дата обновления',
  `post_author` int(10) unsigned NOT NULL COMMENT 'Автор записи',
  `post_status` enum('publish','pending') DEFAULT NULL COMMENT 'Статус публикации',
  `post_type` varchar(100) NOT NULL DEFAULT 'post' COMMENT 'Тип материал',
  `comment_status` enum('open','close') DEFAULT NULL COMMENT 'Разрешение на комментария',
  `mlid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id пункта в меню',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Просмотров',
  PRIMARY KEY (`post_id`),
  KEY `menu_link_ibfk` (`mlid`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `post_title`, `post_content`, `post_date_create`, `post_date_update`, `post_author`, `post_status`, `post_type`, `comment_status`, `mlid`, `views`) VALUES
(1, 'Серверные технологии', '<p>Языки программировая, базы данных, настройка сервераю.</p>\r\n', '2016-01-14 19:19:50', '2016-01-14 19:21:24', 1, 'publish', 'parent', 'open', 2, 0),
(2, 'MySQL', '<p>База данных.</p>', '2016-01-14 19:41:01', NULL, 1, 'publish', 'parent', 'open', 3, 0),
(3, 'Нормализация базы данных', '<p>Нормальзация - приведения структуры базы данных к виду, обеспечивающему минимальную избыточность.</p>\r\n\r\n<p>Нормальные формы базы данных </p>\r\n\r\n<ul><li>Первая нормальная форма</li>\r\n	<li>Вторая нормальная форма </li>\r\n	<li>Третья нормальня форма</li>\r\n	<li>Нормальная форма Бойса-Кодда</li>\r\n	<li>Четвертая нормальная форма</li>\r\n	<li>Пятая нормальная форма</li>\r\n	<li>Шестая нормальная форма</li>\r\n</ul><p> </p>', '2016-01-14 20:44:37', NULL, 1, 'publish', 'post', 'open', 4, 32),
(4, 'Встроенные функции', '<p>MySQL поддерживает ряд функция для работы с датой, строками и числами.</p>\r\n', '2016-01-14 20:55:38', '2016-01-14 20:56:42', 1, 'publish', 'parent', 'open', 5, 0),
(5, 'Библиотеки и инструменты', '<p><img alt="" src="upload_files/images/ck/post_5/284094.png" style="height:42px; width:100px" /></p>\r\n', '2016-01-15 18:16:40', '2016-01-15 18:24:02', 1, 'publish', 'parent', 'open', 6, 0),
(6, 'Git команды', '<h3>Начальные настройки</h3>\r\n\r\n<dl>\r\n	<dt>git config --system</dt>\r\n	<dd>Настройки уровня системы</dd>\r\n	<dt>git config --global</dt>\r\n	<dd>Настройки уровня пользователя системы</dd>\r\n	<dt>git config --project</dt>\r\n	<dd>Настройки уровня проекта Git</dd>\r\n	<dt>git config --global user.name &quot;Имя&quot;</dt>\r\n	<dd>Устанавлиет имя пользователя для Git</dd>\r\n	<dt>git config --global user.email &quot;адрес&quot;</dt>\r\n	<dd>Устанавлиет адрес электроной почты пользователя для Git</dd>\r\n	<dt>git config --list</dt>\r\n	<dd>Выводит список настроек</dd>\r\n	<dt>git config user.name</dt>\r\n	<dd>Выводит имя пользователя</dd>\r\n	<dt>git config user.email</dt>\r\n	<dd>Выводит email пользователя</dd>\r\n	<dt>git config --global core.editor &quot;notepad.exe&quot;</dt>\r\n	<dd>Настройка редактора</dd>\r\n	<dt>git config --global color.ui true</dt>\r\n	<dd>Использование цветов в интерфейсе</dd>\r\n</dl>\r\n<!--anonsbreak-->\r\n\r\n<h3>Основные команды</h3>\r\n\r\n<dl>\r\n	<dt>git help</dt>\r\n	<dd>Справка</dd>\r\n	<dt>git init</dt>\r\n	<dd>Инсталирует репозиторий</dd>\r\n	<dt>git clone</dt>\r\n	<dd>Клонирует репозиторий</dd>\r\n	<dt>git add</dt>\r\n	<dd>Добавляет в буфер для коммита</dd>\r\n	<dt>git commit</dt>\r\n	<dd>Совершает коммит</dd>\r\n	<dt>git mv</dt>\r\n	<dd>Переместить(переименовать) файл</dd>\r\n	<dt>git rm</dt>\r\n	<dd>Удалить файл</dd>\r\n	<dt>git status</dt>\r\n	<dd>Информация о состояние репозитория</dd>\r\n	<dt>git log</dt>\r\n	<dd>Список совершенных коммитов</dd>\r\n	<dt>git diff</dt>\r\n	<dd>Сравнивает рабочую папку с репозиторием</dd>\r\n	<dt>git --staged или git --cached</dt>\r\n	<dd>Сравнивает буфер с репозиторием</dd>\r\n	<dt>git reset HEAD имя_файла</dt>\r\n	<dd>Изымает из буфера файл</dd>\r\n	<dt>git checkout --имя_файла</dt>\r\n	<dd>Откатывает файл для состояния как в репозитории</dd>\r\n	<dt>git checkout 845sef</dt>\r\n	<dd>Загружает в рабочую папку указанный коммит</dd>\r\n	<dt>git checkout имя_ветки</dt>\r\n	<dd>Переключает на указанную ветку</dd>\r\n	<dt>git checkout -b имя_ветки</dt>\r\n	<dd>Создает указанную ветку и преключается на нее</dd>\r\n	<dt>git branch имя_ветки</dt>\r\n	<dd>Создает указанную ветку но не переключается на нее</dd>\r\n	<dt>git branch</dt>\r\n	<dd>Список локальных веток</dd>\r\n	<dt>git branch -remote</dt>\r\n	<dd>Список удаленных веток</dd>\r\n	<dt>git branch -a</dt>\r\n	<dd>Список локальных и удаленных веток</dd>\r\n	<dt>git branch -m имя_ветки новое_имя</dt>\r\n	<dd>Переименовывает бранч</dd>\r\n	<dt>git branch --merged</dt>\r\n	<dd>Показывает в каком бранче учтены все изменения</dd>\r\n	<dt>git branch -d имя_ветки</dt>\r\n	<dd>Удаляет ветку, если все изменения учтены</dd>\r\n	<dt>git branch -D имя_ветки</dt>\r\n	<dd>Принудительно удаляет ветку, если даже изменения не учтены</dd>\r\n	<dt>git merge имя_ветки</dt>\r\n	<dd>Сливает текущую ветку с указанной</dd>\r\n	<dt>git merge --abort</dt>\r\n	<dd>Обрывание слияния</dd>\r\n	<dt>git fetch</dt>\r\n	<dd>Получает изменения с удаленного репозитория</dd>\r\n	<dt>git pull</dt>\r\n	<dd>Получает изменения с удаленного репозитория и сливает с локальным</dd>\r\n	<dt>git push -u</dt>\r\n	<dd>Отправляет изменения на удаленный репозиторий</dd>\r\n	<dt>git push --delete имя_ветки</dt>\r\n	<dd>удаляет ветку на удаленном сервере</dd>\r\n	<dt>git remote -v</dt>\r\n	<dd>Список удаленных репозиториев</dd>\r\n	<dt>git remote add алиас http://репозиторий_адрес</dt>\r\n	<dd>Добавление удаленного репозитория</dd>\r\n	<dt>git remote rm алиас_репозитория</dt>\r\n	<dd>Удаление удаленного репозитория</dd>\r\n</dl>\r\n\r\n<h3>Работа с копилкой</h3>\r\n\r\n<dl>\r\n	<dt>git stash save &quot;Сообщение&quot;</dt>\r\n	<dd>Сохранение в копилку</dd>\r\n	<dt>git stash list</dt>\r\n	<dd>Показывает содержимое копилки</dd>\r\n	<dt>git stash show stash@{0}</dt>\r\n	<dd>Описание изменения</dd>\r\n	<dt>git stash -p show stash@{0}</dt>\r\n	<dd>Более полное описание изменения</dd>\r\n	<dt>git stash pop</dt>\r\n	<dd>Забрать из копилки, и удалить из копилки</dd>\r\n	<dt>git stash apply</dt>\r\n	<dd>Забрать из копилки, но оставить копию в копилке</dd>\r\n	<dt>git stash drop stash{0}</dt>\r\n	<dd>Удалить из копилки</dd>\r\n	<dt>git stash clear</dt>\r\n	<dd>Удалить все из копилки</dd>\r\n</dl>\r\n', '2016-01-15 18:57:17', '2016-01-18 21:47:30', 1, 'publish', 'post', 'open', 7, 44),
(7, 'Javascript', '<p>Делаем странички интерактивными.</p>', '2016-01-16 19:13:32', NULL, 1, 'publish', 'parent', 'open', 12, 0),
(8, 'Работа  с файлами', '<p>FileReader позволяет читать cодержимое файлы локально, без загрузки на сервер, и затем отправлять на сервер через объект XMLHttpRequest</p>\r\n\r\n<p>Обращение к выбраным файлам возможен через массив files:</p>\r\n\r\n<ul>\r\n	<li>выбор input через DOM, например <code>document.querySelector(&#39;#myfile&#39;).files</code></li>\r\n	<li>при событые &quot;change&quot; через <code>event.target.files</code> или <code>this.files</code></li>\r\n	<li>при событые &quot;drop&quot; через <code>event.dataTransfer.files</code></li>\r\n</ul>\r\n<!--anonsbreak-->\r\n\r\n<p>Объект файл имеет следующие свойства и методы:</p>\r\n\r\n<ul>\r\n	<li><code>file.filename</code> - имя файла</li>\r\n	<li><code>file.lastModifiedDate</code> - время последнего изменения файла(объект Date)</li>\r\n	<li><code>file.size</code> - размер файла</li>\r\n	<li><code>file.type</code> - Mime-тип файла, определяет по расширению файла</li>\r\n	<li><code>file.slice([start [, end [, contentType]]])</code> - Позволяет читать файл частями(байты) и передавать их объекту FileReader</li>\r\n</ul>\r\n\r\n<table class="table table-condensed">\r\n	<caption>FileReader</caption>\r\n	<tbody>\r\n		<tr>\r\n			<th>Свойства, методы, события</th>\r\n			<th>Описание</th>\r\n		</tr>\r\n		<tr>\r\n			<td><code>var reader = new FileReader</code></td>\r\n			<td>Создает объект для чтения файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td class="info" colspan="2">Варианты чтения файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.readAsText(file)</code></td>\r\n			<td>reader.result будет содержать данные файла в виде текста.</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.readAsDataURL(file)</code></td>\r\n			<td>reader.result будет содержать данные файла в виде data: URL.</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.readAsArrayBuffer(file)</code></td>\r\n			<td>reader.result будет содержать бинарные данные файла в виде строки.</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.readAsBinaryString(file)</code></td>\r\n			<td>result.result будет содержать данные файла в виде ArrayBuffer</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.abort()</code></td>\r\n			<td>отмена чтения файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td class="info" colspan="2">События при чтении файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.onloadstart = function(){}</code></td>\r\n			<td>событие при запуске чтения файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.onprogress = function(){}</code></td>\r\n			<td>событие во чтения файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.onloadend = function(){}</code></td>\r\n			<td>событие при завершении процесса чтения файла, не зависимо от успеха чтения</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.onload = function(){}</code></td>\r\n			<td>событие при успешном завершение чтения файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.onerror = function(){}</code></td>\r\n			<td>событие при возникновении ошибки во время чтении файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.onabort = function(){}</code></td>\r\n			<td>событие возникает в случаи прерывания чтении файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td class="info" colspan="2">Свойства, где хранится результат</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.error</code></td>\r\n			<td>содержит ошибку в случаи возникновении ее при чтении файла</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.readyState</code></td>\r\n			<td>хранится сообщение о состоянии чтении файла: EMPTY(0), LOADING(1), DONE(2)</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>reader.result</code></td>\r\n			<td>результат чтения файла</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '2016-01-16 19:16:40', '2016-01-18 21:43:07', 1, 'publish', 'post', 'open', 13, 40),
(9, 'Основы Ajax', '<p>XMLHttpRequest позволяет отправлять данные на сервер без перезагрузки страницы, синхронно или асинхронно. Объект&nbsp;XMLHttpRequest обладает следующими свойствами:</p>\r\n<!--anonsbreak-->\r\n\r\n<table class="table table-condensed">\r\n	<caption>XMLHttpRequest</caption>\r\n	<tbody>\r\n		<tr>\r\n			<th>Свойства, методы, события</th>\r\n			<th>Описание</th>\r\n		</tr>\r\n		<tr>\r\n			<td class="info" colspan="2">Настраиваем объект для отправки и отправляем</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr = new XMLHttpRequest()</code></td>\r\n			<td>Создание объекта</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.open(method, url, async, user, password)</code></td>\r\n			<td>Настраивает запрос для отправки, где только method и url обязательные\r\n			<ul>\r\n				<li>method - метод запроса, обычное это GET или POST</li>\r\n				<li>url - адрес запроса, включая GET данные при необходимости</li>\r\n				<li>async - тип запроса, если true - асинхронно(по умолчанию), и false - синхронно</li>\r\n				<li>user, password - данные для HTTP-авторизации</li>\r\n			</ul>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.setRequestHeader(header, value)</code></td>\r\n			<td>Устанавливает дополнительные HTTP-заголовки запроса. Например, для эмуляции формы(создался $_POST на сервере) req.setRequestHeader(&quot;Content-Type&quot;, &quot;application/x-www-form-urlencoded&quot;)</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.send(data)</code></td>\r\n			<td>Отправляет запрос, где data - тело запроса при POST, для GET это параметр передавать не нужно</td>\r\n		</tr>\r\n		<tr>\r\n			<td class="info" colspan="2">Отслеживаем состояние запроса</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.onreadystatechange = function(){}</code></td>\r\n			<td>Отслеживает изменения состояния запроса</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.readyState</code></td>\r\n			<td>Хранит статус состояния запроса от 0 до 4. Необходим статус 4 (DONE) - операция завершена</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.status</code></td>\r\n			<td>Хранит статус кода ответа сервена. Статус равен 200 при успешном запросе</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.responseText</code></td>\r\n			<td>Текст ответа сервера при удачном завершении запроса</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.getResponseHeader(header)</code></td>\r\n			<td>Возвращает значение заголовка ответа сервера с именем header</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.getAllResponseHeaders()</code></td>\r\n			<td>Возвращает значения всех HTTP-заголовков из ответа сервера</td>\r\n		</tr>\r\n		<tr>\r\n			<td class="info" colspan="2">Отслеживаем процесс загрузки</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.upload.onprogress = function(event) {}</code></td>\r\n			<td>Событие срабатывает переодически при загрузки, где\r\n			<ul>\r\n				<li>event.lengthComputable - true, то известно полное количество байт для пересылки</li>\r\n				<li>event.loaded - сколько загружено байт</li>\r\n				<li>event.total - общий размер байт</li>\r\n			</ul>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.upload.onload = function() {}</code></td>\r\n			<td>Событие возникает, когда данные полностью загружены</td>\r\n		</tr>\r\n		<tr>\r\n			<td><code>xhr.upload.onerror = function() {}</code></td>\r\n			<td>Событие возникает, при возникновение ошибки</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '2016-01-17 13:47:44', '2016-01-18 21:42:14', 1, 'publish', 'post', 'open', 14, 18),
(10, 'Video и Audio', '<p><code>var media = html-элемент video или audio</code></p>\r\n\r\n<h3>Методы</h3>\r\n\r\n<dl>\r\n	<dt><code>media.play()</code></dt>\r\n	<dd>Запустить проигрывание</dd>\r\n	<dt><code>media.pause()</code></dt>\r\n	<dd>Поставить на паузу</dd>\r\n</dl>\r\n<!--anonsbreak-->\r\n\r\n<h3>Cвойства воспроизведения</h3>\r\n\r\n<dl>\r\n	<dt><code>media.currentTime</code></dt>\r\n	<dd>Текущий момент проигрывания(секунды)</dd>\r\n	<dt><code>media.duration</code></dt>\r\n	<dd>длительность видео (секунды)</dd>\r\n	<dt><code>media.paused</code></dt>\r\n	<dd>находится ли воспроизведение на паузе</dd>\r\n	<dt><code>media.ended</code></dt>\r\n	<dd>закончилось ли проигрывание</dd>\r\n	<dt><code>media.muted</code></dt>\r\n	<dd>включение/выключение звука</dd>\r\n	<dt><code>media.volume</code></dt>\r\n	<dd>уровень звука [0, 1]</dd>\r\n	<dt><code>media.buffered</code></dt>\r\n	<dd>буферизованные куски видeo</dd>\r\n</dl>\r\n\r\n<h3>Cобытия</h3>\r\n\r\n<dl>\r\n	<dt><code>media.onloadeddata = function(){}</code></dt>\r\n	<dd>Завершена загрузка первого кадра</dd>\r\n	<dt><code>media.onloadedmetadata = function(){}</code></dt>\r\n	<dd>Метаданные медиа были загружены</dd>\r\n	<dt><code>media.onstart = function(){}</code></dt>\r\n	<dd>Начинается загрузка видео</dd>\r\n	<dt><code>media.oncanplay = function(){}</code></dt>\r\n	<dd>Можно начать проигрывание</dd>\r\n	<dt><code>media.onplay = function(){}</code></dt>\r\n	<dd>Запущено проигрывание</dd>\r\n	<dt><code>media.onpause = function(){}</code></dt>\r\n	<dd>Нажата пауза</dd>\r\n	<dt><code>media.ontimeupdate = function(){}</code></dt>\r\n	<dd>Изменена позиция проигрывания</dd>\r\n	<dt><code>media.onvolumechange = function(){}</code></dt>\r\n	<dd>Изменена громкость</dd>\r\n	<dt><code>media.onratechange = function(){}</code></dt>\r\n	<dd>Изменена скорость воспроизведения</dd>\r\n	<dt><code>media.onprogress = function(){}</code></dt>\r\n	<dd>Информирует переодически о загрузке(буферизации)</dd>\r\n	<dt><code>media.onended = function(){}</code></dt>\r\n	<dd>Воспроизведение закончилось</dd>\r\n	<dt><code>media.onerror = function(){}</code></dt>\r\n	<dd>Произошла ошибка</dd>\r\n</dl>\r\n', '2016-01-17 17:32:44', '2016-01-18 21:41:29', 1, 'publish', 'post', 'open', 15, 18),
(11, 'Drag-N-Drop', '<h3>События для объекта перемещения</h3>\r\n\r\n<p class="alert alert-info">Для определения элемент, как переносимый, необходимо присвоить атрибут <code>&quot;draggable=true&quot;</code>.</p>\r\n\r\n<p><code>ondragstart</code> пользователь начинает перетаскивание элемента</p>\r\n\r\n<p><code>ondrag</code> курсор двигается при перетаскивании</p>\r\n\r\n<p><code>ondragend</code> пользователь отпускает курсор мыши в процессе перетаскивания</p>\r\n<!--anonsbreak-->\r\n\r\n<h3>События целевого объекта</h3>\r\n\r\n<p class="alert alert-info">По умолчанию браузер не позволяет перемещать что-либо на HTML элемент(не будет срабатывать событие <code>ondrop</code>).Cделать элемент активным для перемещения других элементов на него, необходимо минимальное условие отменить действие по умолчанию для событий &quot;ondragenter&quot; и &quot;ondragover&quot;. Чтобы правильно отрабатывало событие <code>ondrop</code> для него, также нужно отменить действие по умолчанию, и отменить эффект всплытия для событий <code>ondragenter, ondragover, ondrop </code></p>\r\n\r\n<p><code>ondragenter</code> перетаскиваемый элемент достигает конечного элемента</p>\r\n\r\n<p><code>ondragover</code> курсор мыши наведен на элемент при перетаскивании.</p>\r\n\r\n<p><code>ondragleave</code> курсор мыши покидает пределы перетаскиваемого элемента</p>\r\n\r\n<p><code>ondrop</code> происходит drop элемента</p>\r\n\r\n<h3>Объект dataTransfer свойства</h3>\r\n\r\n<p><code>DataTransfer.effectAllow=value</code> определяет разрешенные эффекты переноса. Возможны эффекты:</p>\r\n\r\n<ul>\r\n	<li><strong>none </strong> операция не разрешена</li>\r\n	<li><strong>copy</strong> только копирование</li>\r\n	<li><strong>move</strong> только перемещение</li>\r\n	<li><strong>link</strong> только ссылка</li>\r\n	<li><strong>copyMove</strong> копирование или перемещение</li>\r\n	<li><strong>copyLink</strong> копирование или ссылка</li>\r\n	<li><strong>linkMove</strong> ссылка или перемещение</li>\r\n	<li><strong>all</strong> копирование, перемещение или ссылка</li>\r\n</ul>\r\n\r\n<p><code>dataTransfer.files</code> содержит список всех локальных файлов, перемещенных в drop зону</p>\r\n\r\n<h3>Объект dataTransfer методы</h3>\r\n\r\n<p><code>dataTransfer.setData(format, data)</code> добавляет данные в нужном формате</p>\r\n\r\n<p><code>dataTransfer.getData(format)</code> возвращает данные</p>\r\n\r\n<p><code>dataTransfer.clearData([format])</code> удаляет данные указанного типа</p>\r\n\r\n<p><code>dataTransfer.setDragImage(img, xOffset, yOffset)</code> устанавливает изображение для перетаскивания с координатами курсора (0, 0 &mdash; левый верхний угол)</p>\r\n', '2016-01-18 10:30:25', '2016-01-18 21:34:39', 1, 'publish', 'post', 'open', 16, 12),
(12, 'FormData', '<h3>Создание объекта FormData</h3>\r\n\r\n<p>Выбираем форму<code>var myForm = document.querySelector(''.my-form'')</code></p>\r\n\r\n<p>Передаем форму конструктору <code>var formData = new FormData(myForm)</code>. Теперь можно отдавать для отправке XMLHttpRequest. FormData использует такой же формат на выходе, как если бы мы отправляли обыкновенную форму с encoding установленным в "multipart/form-data".</p>\r\n<!--anonsbreak-->\r\n\r\n<h3>Методы FormData</h3>\r\n\r\n<dl><dt><code>formData.append(name, value, [filename])</code></dt>\r\n	<dd>Добавляет новое значение существующего поля объекта FormData, либо создаёт его и присваивает значение\r\n	<ul><li><strong>name</strong> - имя поля</li>\r\n		<li><strong>value</strong> - значение поля</li>\r\n		<li><strong>filename</strong> - имя файла, при отправке файла в значение value(необязательный)</li>\r\n	</ul></dd>\r\n	<dt><code>formData.set(name, value, [filename])</code></dt>\r\n	<dd>Работает аналогично <em>formData.append</em>, за исключением если поле уже существует, для него будет задано новое значение, а не добавит в конец существующего</dd>\r\n	<dt><code>formData.delete(name)</code></dt>\r\n	<dd>Удаляет поля с ключом name из объекта FormData</dd>\r\n	<dt><code>formData.get(name)</code></dt>\r\n	<dd>Возвращает первое значение ассоциированное с ключом <em>name</em> из объекта FormData.</dd>\r\n	<dt><code>formData.getAll(name)</code></dt>\r\n	<dd>Возвращает массив всех значений ассоциированных с ключом <em>name</em> из объекта FormData.</dd>\r\n	<dt><code>formData.has(name)</code></dt>\r\n	<dd>Возвращает булево значение касательно наличия поле <em>name</em> в объекте FormData</dd>\r\n</dl>', '2016-01-19 04:58:59', NULL, 1, 'publish', 'post', 'open', 17, 33),
(13, 'PHP', '', '2016-01-20 12:13:12', NULL, 1, 'publish', 'parent', 'open', 21, 0),
(14, 'Регулярные выражения', '<p><a data-lightbox="roadtrip" href="upload_files/images/ck/post_14/7090079.png"><img alt="" src="upload_files/images/ck/post_14/7090079.png" style="width: 200px; height: 279px;" /> </a></p>\r\n\r\n<p>Полезный ресурс&nbsp;<a href="https://regex101.com/" target="_blank">https://regex101.com/</a></p>\r\n', '2016-01-20 12:19:47', '2016-01-20 12:41:51', 1, 'publish', 'parent', 'open', 22, 0),
(15, 'Функция preg_match', '<div class="refnamediv">\r\n<p class="verinfo">(PHP 4, PHP 5, PHP 7)</p>\r\n\r\n<p class="refpurpose"><span class="refname">preg_match</span> &mdash; <span class="dc-title">Выполняет проверку на соответствие регулярному выражению</span></p>\r\n</div>\r\n\r\n<div class="refsect1 description" id="refsect1-function.preg-match-description">\r\n<h3 class="title">Описание</h3>\r\n\r\n<div class="methodsynopsis dc-description"><span class="type">int</span> <span class="methodname"><strong>preg_match</strong></span> ( <span class="methodparam"><span class="type">string</span> <code class="parameter">$pattern</code></span> , <span class="methodparam"><span class="type">string</span> <code class="parameter">$subject</code></span> [, <span class="methodparam"><span class="type">array</span> <code class="parameter reference">&amp;$matches</code></span> [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$flags</code><span class="initializer"> = 0</span></span> [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$offset</code><span class="initializer"> = 0</span></span> ]]] )</div>\r\n\r\n<p class="para rdfs-comment">Ищет в заданном тексте <code class="parameter">subject</code> совпадения с шаблоном <code class="parameter">pattern</code>.</p>\r\n</div>\r\n<!--anonsbreak-->\r\n\r\n<div class="refsect1 parameters" id="refsect1-function.preg-match-parameters">\r\n<h3 class="title">Список параметров</h3>\r\n\r\n<p class="para">&nbsp;</p>\r\n\r\n<dl>\r\n	<dt><code class="parameter">pattern</code></dt>\r\n	<dd>\r\n	<p class="para">Искомый шаблон, строка.</p>\r\n	</dd>\r\n	<dt><code class="parameter">subject</code></dt>\r\n	<dd>\r\n	<p class="para">Входная строка.</p>\r\n	</dd>\r\n	<dt><code class="parameter">matches</code></dt>\r\n	<dd>\r\n	<p class="para">В случае, если указан дополнительный параметр <code class="parameter">matches</code>, он будет заполнен результатами поиска. Элемент <var class="varname"><var class="varname">$matches[0]</var></var> будет содержать часть строки, соответствующую вхождению всего шаблона, <var class="varname"><var class="varname">$matches[1]</var></var> - часть строки, соответствующую первой подмаске, и так далее.</p>\r\n	</dd>\r\n	<dt><code class="parameter">flags</code></dt>\r\n	<dd>\r\n	<p class="para"><code class="parameter">flags</code> может принимать значение следующего флага:</p>\r\n\r\n	<dl>\r\n		<dt><strong><code>PREG_OFFSET_CAPTURE</code></strong></dt>\r\n		<dd><span class="simpara">В случае, если этот флаг указан, для каждой найденной подстроки будет указана ее позиция в исходной строке. Необходимо помнить, что этот флаг меняет формат возвращаемого массива <code class="parameter">matches</code> в массив, каждый элемент которого содержит массив, содержащий в индексе с номером <em>0</em> найденную подстроку, а смещение этой подстроки в параметре <code class="parameter">subject</code> - в индексе <em>1</em>. </span></dd>\r\n	</dl>\r\n	</dd>\r\n	<dt><code class="parameter">offset</code></dt>\r\n	<dd>\r\n	<p class="para">Обычно поиск осуществляется слева направо, с начала строки. Можно использовать дополнительный параметр <code class="parameter">offset</code> для указания альтернативной начальной позиции для поиска (в байтах).</p>\r\n\r\n	<blockquote class="note">\r\n	<p><strong class="note">Замечание</strong>:</p>\r\n\r\n	<p class="para">Использование параметра <code class="parameter">offset</code> не эквивалентно замене сопоставляемой строки выражением <em>substr($subject, $offset)</em> при вызове функции <span class="function"><strong>preg_match()</strong></span>, поскольку шаблон <code class="parameter">pattern</code> может содержать такие условия как <em class="emphasis">^</em>, <em class="emphasis">$</em> или <em class="emphasis">(?&lt;=x)</em>. Сравните:</p>\r\n\r\n	<div class="informalexample">\r\n	<div class="example-contents">\r\n	<div class="phpcode"><code><span style="color: #000000"><span style="color: #0000BB"><!--?php<br--> $subject&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #DD0000">&quot;abcdef&quot;</span><span style="color: #007700">;</span><br />\r\n	<span style="color: #0000BB">$pattern&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #DD0000">&#39;/^def/&#39;</span><span style="color: #007700">;</span><br />\r\n	<span style="color: #0000BB">preg_match</span><span style="color: #007700">(</span><span style="color: #0000BB">$pattern</span><span style="color: #007700">,&nbsp;</span><span style="color: #0000BB">$subject</span><span style="color: #007700">,&nbsp;</span><span style="color: #0000BB">$matches</span><span style="color: #007700">,&nbsp;</span><span style="color: #0000BB">PREG_OFFSET_CAPTURE</span><span style="color: #007700">,&nbsp;</span><span style="color: #0000BB">3</span><span style="color: #007700">);</span><br />\r\n	<span style="color: #0000BB">print_r</span><span style="color: #007700">(</span><span style="color: #0000BB">$matches</span><span style="color: #007700">);</span><br />\r\n	<span style="color: #0000BB">?&gt;</span> </span> </code></div>\r\n	</div>\r\n\r\n	<p class="para">Результат выполнения данного примера:</p>\r\n\r\n	<div class="example-contents screen">\r\n	<div class="cdata">\r\n	<pre>\r\nArray\r\n(\r\n)\r\n</pre>\r\n	</div>\r\n	</div>\r\n\r\n	<p class="para">В то время как этот пример</p>\r\n\r\n	<div class="example-contents">\r\n	<div class="phpcode"><code><span style="color: #000000"><span style="color: #0000BB"><!--?php<br--> $subject&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #DD0000">&quot;abcdef&quot;</span><span style="color: #007700">;</span><br />\r\n	<span style="color: #0000BB">$pattern&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #DD0000">&#39;/^def/&#39;</span><span style="color: #007700">;</span><br />\r\n	<span style="color: #0000BB">preg_match</span><span style="color: #007700">(</span><span style="color: #0000BB">$pattern</span><span style="color: #007700">,&nbsp;</span><span style="color: #0000BB">substr</span><span style="color: #007700">(</span><span style="color: #0000BB">$subject</span><span style="color: #007700">,</span><span style="color: #0000BB">3</span><span style="color: #007700">),&nbsp;</span><span style="color: #0000BB">$matches</span><span style="color: #007700">,&nbsp;</span><span style="color: #0000BB">PREG_OFFSET_CAPTURE</span><span style="color: #007700">);</span><br />\r\n	<span style="color: #0000BB">print_r</span><span style="color: #007700">(</span><span style="color: #0000BB">$matches</span><span style="color: #007700">);</span><br />\r\n	<span style="color: #0000BB">?&gt;</span> </span> </code></div>\r\n	</div>\r\n\r\n	<p class="para">выведет следующее:</p>\r\n\r\n	<div class="example-contents screen">\r\n	<div class="cdata">\r\n	<pre>\r\nArray\r\n(\r\n    [0] =&gt; Array\r\n        (\r\n            [0] =&gt; def\r\n            [1] =&gt; 0\r\n        )\r\n\r\n)\r\n</pre>\r\n	</div>\r\n	</div>\r\n	</div>\r\n	</blockquote>\r\n	</dd>\r\n</dl>\r\n</div>\r\n\r\n<div class="refsect1 returnvalues" id="refsect1-function.preg-match-returnvalues">\r\n<h3 class="title">Возвращаемые значения</h3>\r\n\r\n<p class="para"><span class="function"><strong>preg_match()</strong></span> возвращает 1, если параметр <code class="parameter">pattern</code> соответствует переданному параметру <code class="parameter">subject</code>, 0 если нет, или <strong><code>FALSE</code></strong> в случае ошибки.</p>\r\n\r\n<div class="warning"><strong class="warning">Внимание</strong>\r\n\r\n<p class="simpara">Эта функция может возвращать как boolean <strong><code>FALSE</code></strong>, так и не-boolean значение, которое приводится к <strong><code>FALSE</code></strong>. За более подробной информацией обратитесь к разделу Булев тип. Используйте оператор === для проверки значения, возвращаемого этой функцией.</p>\r\n</div>\r\n</div>\r\n', '2016-01-20 12:40:37', '2016-01-20 13:41:09', 1, 'publish', 'post', 'open', 23, 10),
(16, 'Функция preg_replace', '<div class="refnamediv">\r\n<p class="verinfo">(PHP 4, PHP 5, PHP 7)</p>\r\n\r\n<p class="refpurpose"><span class="refname">preg_replace</span> &mdash; <span class="dc-title">Выполняет поиск и замену по регулярному выражению</span></p>\r\n</div>\r\n\r\n<div class="refsect1 description" id="refsect1-function.preg-replace-description">\r\n<h3 class="title">Описание</h3>\r\n\r\n<div class="methodsynopsis dc-description"><span class="type">mixed</span> <span class="methodname"><strong>preg_replace</strong></span> ( <span class="methodparam"><span class="type">mixed</span> <code class="parameter">$pattern</code></span> , <span class="methodparam"><span class="type">mixed</span> <code class="parameter">$replacement</code></span> , <span class="methodparam"><span class="type">mixed</span> <code class="parameter">$subject</code></span> [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$limit</code><span class="initializer"> = -1</span></span> [, <span class="methodparam"><span class="type">int</span> <code class="parameter reference">&amp;$count</code></span> ]] )</div>\r\n\r\n<p class="para rdfs-comment">Выполняет поиск совпадений в строке <code class="parameter">subject</code> с шаблоном <code class="parameter">pattern</code> и заменяет их на <code class="parameter">replacement</code>.</p>\r\n</div>\r\n<!--anonsbreak-->\r\n\r\n<div class="refsect1 parameters" id="refsect1-function.preg-replace-parameters">\r\n<h3 class="title">Список параметров</h3>\r\n\r\n<p class="para">&nbsp;</p>\r\n\r\n<dl>\r\n	<dt><code class="parameter">pattern</code></dt>\r\n	<dd>\r\n	<p class="para">Искомый шаблон. Может быть как строкой, так и массивом строк.</p>\r\n\r\n	<p class="para">Также доступны некоторые модификаторы PCRE, включая устаревший &#39;<em>e</em>&#39; (PREG_REPLACE_EVAL), специфичный только для этой функции.</p>\r\n	</dd>\r\n	<dt><code class="parameter">replacement</code></dt>\r\n	<dd>\r\n	<p class="para">Строка или массив строк для замены. Если этот параметр является строкой, а <code class="parameter">pattern</code> является массивом, все шаблоны будут заменены этой строкой. Если и <code class="parameter">pattern</code> и <code class="parameter">replacement</code> являются массивами, каждый элемент <code class="parameter">pattern</code> будет заменен соответствующим элементом из <code class="parameter">replacement</code>. Если массив <code class="parameter">replacement</code> содержит меньше элементов, чем массив <code class="parameter">pattern</code>, то все лишние шаблоны из <code class="parameter">pattern</code> будут заменены пустыми строками.</p>\r\n\r\n	<p class="para"><code class="parameter">replacement</code> может содержать ссылки вида <em>\\\\<span class="replaceable">n</span></em>, либо (начиная с PHP 4.0.4) <em>$<span class="replaceable">n</span></em>, причем последний вариант предпочтительней. Каждая такая ссылка будет заменена на подстроку, соответствующую <span class="replaceable">n</span>-ой подмаске. <span class="replaceable">n</span> может принимать значения от 0 до 99, причем ссылка <em>\\\\0</em> (либо <em>$0</em>) соответствует вхождению всего шаблона. Подмаски нумеруются слева направо, начиная с единицы. Для использования обратного слеша, его необходимо продублировать (строка PHP <em>&quot;\\\\\\\\&quot;</em>).</p>\r\n\r\n	<p class="para">При замене по шаблону с использованием ссылок на подмаски может возникнуть ситуация, когда непосредственно за маской следует цифра (например, установка цифры сразу после совпавшей маски). В таком случае нельзя использовать знакомую нотацию вида <em>\\\\1</em> для ссылки на подмаски. Запись, например, <em>\\\\11</em>, смутит <span class="function"><strong>preg_replace()</strong></span>, так как она не сможет понять, хотите ли вы использовать ссылку <em>\\\\1</em>, за которой следует цифра <em>1</em> или же вы хотите просто использовать ссылку <em>\\\\11</em>, за которой ничего не следует. Это недоразумение можно устранить, если воспользоваться конструкцией <em>\\${1}1</em>, использующей изолированную ссылку <em>$1</em>, и следующую за ней цифру <em>1</em>.</p>\r\n\r\n	<p class="para">При использовании устаревшего модификатора <em>e</em> эта функция экранирует некоторые символы (а именно <em>&#39;</em>, <em>&quot;</em>, <em>\\</em> и NULL) в строках, замещающих обратные ссылки. Это сделано для удостоверения корректности синтаксиса при использовании обратных ссылок внутри одинарных или двойных кавычек (например, <em>&#39;strlen(\\&#39;$1\\&#39;)+strlen(&quot;$2&quot;)&#39;</em>). Убедитесь, что вы владеете синтаксисом обработки строк PHP для того, чтобы точно осознавать, как будет выглядеть интерпретированная строка.</p>\r\n	</dd>\r\n	<dt><code class="parameter">subject</code></dt>\r\n	<dd>\r\n	<p class="para">Строка или массив строк для поиска и замены.</p>\r\n\r\n	<p class="para">Если <code class="parameter">subject</code> является массивом, то поиск с заменой осуществляется для каждого элемента массива <code class="parameter">subject</code>, а возвращаемое значение также будет являться массивом.</p>\r\n	</dd>\r\n	<dt><code class="parameter">limit</code></dt>\r\n	<dd>\r\n	<p class="para">Максимально возможное количество замен каждого шаблона для каждой строки <code class="parameter">subject</code>. По умолчанию равно <em>-1</em> (без ограничений).</p>\r\n	</dd>\r\n	<dt><code class="parameter">count</code></dt>\r\n	<dd>\r\n	<p class="para">Если указана, то эта переменная будет заполнена количеством произведенных замен.</p>\r\n	</dd>\r\n</dl>\r\n</div>\r\n\r\n<div class="refsect1 returnvalues" id="refsect1-function.preg-replace-returnvalues">\r\n<h3 class="title">Возвращаемые значения</h3>\r\n\r\n<p class="para"><span class="function"><strong>preg_replace()</strong></span> возвращает массив, если параметр <code class="parameter">subject</code> является массивом, иначе возвращается строка.</p>\r\n\r\n<p class="para">Если найдены совпадения, возвращается новая версия <code class="parameter">subject</code>, иначе <code class="parameter">subject</code> возвращается нетронутым, в случае ошибки возвращается <strong><code>NULL</code></strong>.</p>\r\n</div>\r\n', '2016-01-20 13:33:35', '2016-01-20 13:41:46', 1, 'publish', 'post', 'open', 24, 3);
INSERT INTO `posts` (`post_id`, `post_title`, `post_content`, `post_date_create`, `post_date_update`, `post_author`, `post_status`, `post_type`, `comment_status`, `mlid`, `views`) VALUES
(17, 'Функция preg_replace_callback', '<div class="refnamediv">\r\n<p class="verinfo">(PHP 4 &gt;= 4.0.5, PHP 5, PHP 7)</p>\r\n\r\n<p class="refpurpose"><span class="refname">preg_replace_callback</span> &mdash; <span class="dc-title">Выполняет поиск по регулярному выражению и замену с использованием callback-функции</span></p>\r\n</div>\r\n\r\n<div class="refsect1 description" id="refsect1-function.preg-replace-callback-description">\r\n<h3 class="title">Описание</h3>\r\n\r\n<div class="methodsynopsis dc-description"><span class="type">mixed</span> <span class="methodname"><strong>preg_replace_callback</strong></span> ( <span class="methodparam"><span class="type">mixed</span> <code class="parameter">$pattern</code></span> , <span class="methodparam"><span class="type">callable</span> <code class="parameter">$callback</code></span> , <span class="methodparam"><span class="type">mixed</span> <code class="parameter">$subject</code></span> [, <span class="methodparam"><span class="type">int</span> <code class="parameter">$limit</code><span class="initializer"> = -1</span></span> [, <span class="methodparam"><span class="type">int</span> <code class="parameter reference">&amp;$count</code></span> ]] )</div>\r\n\r\n<p class="para rdfs-comment">Поведение этой функции во многом напоминает <span class="function">preg_replace()</span>, за исключением того, что вместо параметра <code class="parameter">replacement</code> необходимо указывать <code class="parameter">callback</code>-функцию.</p>\r\n</div>\r\n<!--anonsbreak-->\r\n\r\n<div class="refsect1 parameters" id="refsect1-function.preg-replace-callback-parameters">\r\n<h3 class="title">Список параметров</h3>\r\n\r\n<p class="para">&nbsp;</p>\r\n\r\n<dl>\r\n	<dt><code class="parameter">pattern</code></dt>\r\n	<dd>\r\n	<p class="para">Искомый шаблон. Может быть как строкой, так и массивом строк.</p>\r\n	</dd>\r\n	<dt><code class="parameter">callback</code></dt>\r\n	<dd>\r\n	<p class="para">Вызываемая callback-функция, которой будет передан массив совпавших элементов из строки <code class="parameter">subject</code>. Callback-функция должна вернуть строку с заменой. Callback-функция должна быть описана так:</p>\r\n\r\n	<p class="para">&nbsp;</p>\r\n\r\n	<div class="methodsynopsis dc-description"><span class="type">string</span> <span class="methodname"><span class="replaceable">handler</span></span> ( <span class="methodparam"><span class="type">array</span> <code class="parameter">$matches</code></span> )</div>\r\n\r\n	<p class="para">Достаточно часто <code class="parameter">callback</code> функция, кроме как в вызове <span class="function"><strong>preg_replace_callback()</strong></span>, ни в чем больше не участвует. Исходя из этих соображений, можно использовать анонимные функции для создания callback-функции непосредственно в вызове <span class="function"><strong>preg_replace_callback()</strong></span>. Если вы используете такой подход, вся информация, связанная с заменой по регулярному выражению, будет собрана в одном месте, и пространство имен функций не будет загромождаться неиспользуемыми записями.</p>\r\n\r\n	<p class="para">&nbsp;</p>\r\n\r\n	<div class="example" id="example-5323">\r\n	<p><strong>Пример #1 <span class="function"><strong>preg_replace_callback()</strong></span> и анонимная функция</strong></p>\r\n\r\n	<div class="example-contents">\r\n	<div class="phpcode"><code><span style="color: #000000"><span style="color: #0000BB"><!--?php</span--><br />\r\n	<span style="color: #FF8000">/*&nbsp;фильтр,&nbsp;подобный&nbsp;тому,&nbsp;что&nbsp;используется&nbsp;в&nbsp;системах&nbsp;Unix<br />\r\n	&nbsp;*&nbsp;для&nbsp;преобразования&nbsp;заглавных&nbsp;букв&nbsp;в&nbsp;началье&nbsp;параграфа&nbsp;в&nbsp;строчные&nbsp;*/</span><br />\r\n	<span style="color: #0000BB">$fp&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">fopen</span><span style="color: #007700">(</span><span style="color: #DD0000">&quot;php://stdin&quot;</span><span style="color: #007700">,&nbsp;</span><span style="color: #DD0000">&quot;r&quot;</span><span style="color: #007700">)&nbsp;or&nbsp;die(</span><span style="color: #DD0000">&quot;не&nbsp;удалось&nbsp;прочесть&nbsp;stdin&quot;</span><span style="color: #007700">);<br />\r\n	while&nbsp;(!</span><span style="color: #0000BB">feof</span><span style="color: #007700">(</span><span style="color: #0000BB">$fp</span><span style="color: #007700">))&nbsp;{<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #0000BB">$line&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">fgets</span><span style="color: #007700">(</span><span style="color: #0000BB">$fp</span><span style="color: #007700">);<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #0000BB">$line&nbsp;</span><span style="color: #007700">=&nbsp;</span><span style="color: #0000BB">preg_replace_callback</span><span style="color: #007700">(<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #DD0000">&#39;|</span></span></span></code>\r\n\r\n	<p><code>\\s*\\w|&#39;<span style="color: #007700">,<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;function&nbsp;(</span><span style="color: #0000BB">$matches</span><span style="color: #007700">)&nbsp;{<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return&nbsp;</span><span style="color: #0000BB">strtolower</span><span style="color: #007700">(</span><span style="color: #0000BB">$matches</span><span style="color: #007700">[</span><span style="color: #0000BB">0</span><span style="color: #007700">]);<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #0000BB">$line<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="color: #007700">);<br />\r\n	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;</span><span style="color: #0000BB">$line</span><span style="color: #007700">;<br />\r\n	}</span><br />\r\n	<span style="color: #0000BB">fclose</span><span style="color: #007700">(</span><span style="color: #0000BB">$fp</span><span style="color: #007700">);</span><br />\r\n	<span style="color: #0000BB">?&gt;</span> </code></p>\r\n	</div>\r\n	</div>\r\n	</div>\r\n	</dd>\r\n	<dt><code class="parameter">subject</code></dt>\r\n	<dd>\r\n	<p class="para">Строка или массив строк для поиска и замены.</p>\r\n	</dd>\r\n	<dt><code class="parameter">limit</code></dt>\r\n	<dd>\r\n	<p class="para">Максимально возможное количество замен для каждого шаблона в каждой строке <code class="parameter">subject</code>. По умолчанию равно <em>-1</em> (без ограничений).</p>\r\n	</dd>\r\n	<dt><code class="parameter">count</code></dt>\r\n	<dd>\r\n	<p class="para">Если указана, то эта переменная будет заполнена количеством произведенных замен.</p>\r\n	</dd>\r\n</dl>\r\n</div>\r\n\r\n<div class="refsect1 returnvalues" id="refsect1-function.preg-replace-callback-returnvalues">\r\n<h3 class="title">Возвращаемые значения</h3>\r\n\r\n<p class="para"><span class="function"><strong>preg_replace_callback()</strong></span> возвращает массив, если параметр <code class="parameter">subject</code> является массивом, иначе возвращается строка. В случае ошибок возвращается <strong><code>NULL</code></strong></p>\r\n\r\n<p class="para">Если найдены совпадения, будет возвращена результирующая строка, иначе <code class="parameter">subject</code> вернется неизмененным.</p>\r\n</div>\r\n', '2016-01-20 13:37:57', '2016-01-20 13:42:15', 1, 'publish', 'post', 'open', 25, 4),
(19, 'Строковые', '<dl>\r\n	<dt><code>CONCAT(str1,str2,...)</code></dt>\r\n	<dd>Возвращает объединеную строку склеянных значений с помощью запятой</dd>\r\n	<dt><code>CONCAT_WS(separator, str1, str2, ...)</code></dt>\r\n	<dd>Возвращает объединеную строку склеянных значений с помощью символа указанном в <em>separator</em></dd>\r\n	<dt><code>LEFT(str, len)</code></dt>\r\n	<dd>Возвращает len крайних слева символов из строки str (или NULL, если какой-нибудь из аргументов имеет значение NULL).</dd>\r\n	<dt><code>RIGHT(str, len)</code></dt>\r\n	<dd>Возвращает len крайних справа символов из строки str(или NULL, если какой-нибудь из аргументов имеет значение NULL). Следующий код возвращает строку Columbus:</dd>\r\n	<dt><code>MID(str, pos, len)</code></dt>\r\n	<dd>Возвращает до len символов из строки str, начиная с позиции pos. Если аргумент len опущен, то возвращаются все символы до конца строки. Для аргумента pos можно использовать отрицательное значение, тогда он будет представлять позицию символа, вычисляемую с конца строки. Первой позицией в строке является 1.</dd>\r\n</dl>\r\n<!--anonsbreak-->\r\n\r\n<dl>\r\n	<dt><code>LOCATE(substr, str, pos)</code></dt>\r\n	<dd>Возвращает позицию первой же встреченной подстроки substr в строке str. Если функции передан параметр pos, то поиск начинается с позиции pos. Если substr не была найдена в строке str, возвращается значение 0.</dd>\r\n	<dt><code>LOWER(str)</code></dt>\r\n	<dd>Возвращает строку str, где все буквы которой переводятся в нижний регистр.</dd>\r\n	<dt><code>UPPER(str)</code></dt>\r\n	<dd>Возвращает строку str, где все буквы которой переводятся в верхний регистр.</dd>\r\n	<dt><code>REPEAT(str, count)</code></dt>\r\n	<dd>Возвращает строку, содержащую count копий строки str.</dd>\r\n	<dt><code>REPLACE(str, from, to)</code></dt>\r\n	<dd>Возвращает строку str, в которой все появления строки from заменены строкойto. Чувствительная к регистру букв</dd>\r\n	<dt><code>TRIM([specifier remove FROM] str)</code></dt>\r\n	<dd>Возвращает строку str, из которой удалены все префиксы и суффиксы, имеющие значение remove. В качестве specifier может быть указан один из спецификаторов &mdash; BOTH (оба), LEADING (ведущие) или TRAILING (замыкающие). Если спецификатор не указан, предполагается спецификатор BOTH. Строка remove является необязательным параметром, и при ее отсутствии удаляются пробелы.</dd>\r\n	<dt><code>LTRIM(str) и RTRIM(str)</code></dt>\r\n	<dd>Функция LTRIM возвращает строку str, у которой удалены все пробелы в начале, а функция RTRIM делает то же самое, но в отношении замыкающих пробелов.</dd>\r\n	<dt><code>LENGTH(str)</code></dt>\r\n	<dd>Возвращает длину строки str в байтах. Если нужно узнать количество символов в строке многобайтовой кодировке, нужно использовать функцию <code>CHAR_LENGTH</code></dd>\r\n</dl>\r\n\r\n<p>&nbsp;<a href="http://dev.mysql.com/doc/refman/5.7/en/string-functions.html" target="_blank">http://dev.mysql.com/doc/refman/5.7/en/string-functions.html</a></p>\r\n', '2016-01-20 18:30:28', '2016-01-20 18:40:23', 1, 'publish', 'post', 'open', 26, 30),
(20, 'Дата и время', '<dl>\r\n	<dt><code>CURTIME()</code></dt>\r\n	<dd>Возвращает текущее время в виде значения, имеющего формат HH:MM:SS или HHMMSS.uuuuuu в зависимости от того, в каком контексте используется функция: строковом или числовом. Значение дается с учетом текущего часового пояса.</dd>\r\n	<dt><code>HOUR(time)</code></dt>\r\n	<dd>Возвращает значение часа для времени time.</dd>\r\n	<dt><code>MINUTE(time)</code></dt>\r\n	<dd>Возвращает значение минуты для времени time.</dd>\r\n	<dt><code>SECOND(time)</code></dt>\r\n	<dd>Возвращает значение секунды для времени time.</dd>\r\n	<dt><code>MAKETIME(hour, minute, second)</code></dt>\r\n	<dd>Возвращает значение времени, вычисленное на основе аргументов часа hour, минуты minute и секунды second.</dd>\r\n	<dt><code>TIMEDIFF(expr1, expr2)</code></dt>\r\n	<dd>Возвращает разницу между expr1 и expr2 (expr1 &ndash; expr2) в виде значения времени. Оба аргумента должны быть выражениями одинакового типа в формате TIME или DATETIME.</dd>\r\n</dl>\r\n<!--anonsbreak-->\r\n\r\n<dl>\r\n	<dt><code>UNIX_TIMESTAMP([date])</code></dt>\r\n	<dd>Возвращает отметку времени TIMESTAMP</dd>\r\n	<dt><code>FROM_UNIXTIME(unix_timestamp [, format])</code></dt>\r\n	<dd>Возвращает параметр unix_timestamp в формате date</dd>\r\n	<dt><code>CURDATE()</code></dt>\r\n	<dd>Возвращает текущую дату в формате YYYY-MM-DD или YYYMMDD в зависимости от того, в каком контексте используется функция: строковом или числовом.</dd>\r\n	<dt><code>DATE(expr)</code></dt>\r\n	<dd>Извлекает дату из выражения DATETIME, переданного в аргументе expr.</dd>\r\n	<dt><code>DATE_ADD(date, INTERVAL expr unit)</code></dt>\r\n	<dd>Возвращает результат добавления выражения expr, в котором к дате применяется единица измерения unit. Аргумент date является стартовой датой или значением DATETIME, а expr для отрицательных интервалов может начинаться с минуса (-).</dd>\r\n	<dt><code>DATE_FORMAT(date, format)</code></dt>\r\n	<dd>Эта функция возвращает значение даты date, отформатированное в соответствии со строкой форматирования format.</dd>\r\n	<dt><code>DAY(date)</code></dt>\r\n	<dd>Возвращает для даты date день месяца в диапазоне от 1 до 31 или возвращает 0 для дат, содержащих нулевую составляющую дней, таких как 0000-00-00 или 2018-00-00.</dd>\r\n	<dt><code>DAYNAME(date)</code></dt>\r\n	<dd>Возвращает название дня недели для даты date.</dd>\r\n	<dt><code>DAYOFWEEK(date)</code></dt>\r\n	<dd>Возвращает номер дня недели для даты date в диапазоне от 1 для воскресенья до 7 для субботы.</dd>\r\n	<dt><code>LAST_DAY(date)</code></dt>\r\n	<dd>Возвращает последний день месяца для заданной в формате DATETIME даты date. Если аргумент имеет неправильный формат, возвращает NULL.</dd>\r\n	<dt><code>MAKEDATE(year, dayofyear)</code></dt>\r\n	<dd>Возвращает дату, соответствующую предоставленному году year и дню года dayofyear. Если dayofyear имеет нулевое значение, результат будет в виде значения NULL.</dd>\r\n	<dt><code>MONTH(date)</code></dt>\r\n	<dd>Возвращает месяц даты date в диапазоне от 1 до 12 с января по декабрь. Для дат, у которых часть, относящаяся к месяцу, имеет нулевое значение, например &laquo;0000-00-00&raquo; или &laquo;2018-00-00&raquo;, возвращает нуль.</dd>\r\n	<dt><code>MONTHNAME(date)</code></dt>\r\n	<dd>Возвращает полное название месяца для даты date.</dd>\r\n	<dt><code>YEAR(date)</code></dt>\r\n	<dd>Возвращает год для даты date в диапазоне от 1000 до 9999 или 0 для нулевой даты.</dd>\r\n</dl>\r\n\r\n<p><a href="http://dev.mysql.com/doc/refman/5.7/en/date-and-time-functions.html" target="_blank">http://dev.mysql.com/doc/refman/5.7/en/date-and-time-functions.html</a></p>\r\n', '2016-01-20 18:35:53', '2016-01-21 05:09:20', 1, 'publish', 'post', 'close', 27, 8),
(21, 'Настройки htaccess', '<p>.htaccess &mdash; файл дополнительной конфигурации веб-сервера Apache, а также подобных ему серверов. Позволяет задавать большое количество дополнительных параметров и разрешений для работы веб-сервера у отдельных пользователей (а также на различных папках отдельных пользователей), таких как управляемый доступ к каталогам, переназначение типов файлов и т.д., не предоставляя доступа к главному конфигурационному файлу, т.е. не влияя на работу всего сервиса целиком.</p>\r\n<!--anonsbreak-->\r\n\r\n<dl>\r\n	<dt><code>php_value upload_max_filesize 2M</code></dt>\r\n	<dd>Ограничение на размер загружаемых файлов</dd>\r\n	<dt><code>php_value post_max_size 10M</code></dt>\r\n	<dd>Ограничение максимальный размер передаваемых при загрузке в PHP данных</dd>\r\n	<dt><code>php_value max_execution_time 240</code></dt>\r\n	<dd>Ограничение время выполнения скриптов в секундах</dd>\r\n	<dt><code>Options All -Indexes</code></dt>\r\n	<dd>Запрет на просмотр директорий</dd>\r\n	<dt><code>Options All +Indexes</code></dt>\r\n	<dd>Разрешение просмотра директорий</dd>\r\n	<dt><code>deny from all</code></dt>\r\n	<dd>Закрытие доступа к каталогу</dd>\r\n	<dt><code>AddDefaultCharset UTF-8</code></dt>\r\n	<dd>Задает кодировку по умолчанию</dd>\r\n	<dt><code>ErrorDocument 403</code></dt>\r\n	<dd>Задает страницу по умолчанию для кода состояния 403</dd>\r\n	<dt><code>ErrorDocument 404</code></dt>\r\n	<dd>Задает страницу по умолчанию для кода состояния 404</dd>\r\n	<dt><code>DirectoryIndex mypage.html</code></dt>\r\n	<dd>Устанавливает главную страницу, отличную от стандартной (index.html, index.php и т. д.)</dd>\r\n</dl>\r\n\r\n<p><a href="http://htaccess.net.ru/doc/php/index.php" target="_blank">http://htaccess.net.ru/doc/php/index.php</a></p>\r\n', '2016-01-20 19:56:01', '2016-01-25 20:58:22', 1, 'publish', 'post', 'open', 28, 9),
(22, 'Препроцессоры', '<p>Препроцессоры инструмент позволяющий значительно упростить написания стиливый файлов. Они позволяют использовать <strong>переменные</strong> для задания часто повторяющих значений, <strong>математические операции</strong> для вычисления различных значений, лучшую организацию кода внутри, используя <strong>вложенности</strong>, и возможность распределения кода по файлам, которые впоследствии могут быть соединены в один файл при помощи <strong>импорта</strong>. Еще из плюсов препроцессов это <strong>примеси и расширения</strong>, благодаря которым можно писать меньше кода руками. </p>\r\n	<p>Наиболее популярные препроцессорами являются SCSS и LESS. Опробовать можно на онлайн сервисах, такие как <a href="http://codepen.io/">codepen.io</a> или <a href="http://jsbin.com/?css,output">jsbin.com</a></p>\r\n	<p>Использовать препроцессоры в своем проекте можно через ПО с GUI, например PreProc, Koala, или через Task Runner(Grunt, Gulp)</p>', '2016-01-23 11:02:18', NULL, 1, 'publish', 'parent', 'open', 30, 0),
(23, 'LESS', '<p><strong>Переменные</strong> задаются с помощью символа <code>@</code>, могу содержать любые значения, например <code>@font: Arial, Helvetica, sans-serif</code>. Переменные можно вставлять(интерполировать) непосредственно в строку названия css-свойств или их значений, но для это ее нужно экранировать символами <code>{ }</code>, например <code>background: url(&quot;@{dir}/fon.jpg&quot;)</code>.</p>\r\n<!--anonsbreak-->\r\n\r\n<p><strong>Расширения</strong>, позволять расширять для селекторов набор правил другим (другими словами создаться копия селектора расширения, где его на место будет поставлен расширяемый селектор), при это будет создать объединяющий набор правил, что позволит избежать дублирования кода. Задается через ключевое слово <code>:extend</code>, например <code>nav ul { &amp;:extend(.inline); background: blue;}</code> или альтернативный вариант <code>nav ul:extend(.inline) {background: blue;}</code>. Чтобы при расширении учитывались вложенные правила, необходимо, добавить ключево слово <code>all</code>, например <code> nav ul { &amp;:extend(.inline all); background: blue;}</code>.</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.error {\r\nborder: 1px #f00;\r\nbackground-color: #fdd;\r\n\r\n &amp;:hover {\r\n   color: #000;\r\n }\r\n}\r\n\r\n.error.intrusion {\r\n  background-image: url(...);\r\n}\r\n\r\n.seriousError {\r\n  &amp;:extend(.error all);    \r\n  border-width: 3px;\r\n}\r\n</pre>\r\n\r\n<p>Результат:</p>\r\n\r\n<pre>\r\n.error,\r\n.seriousError {\r\n  border: 1px #f00;\r\n  background-color: #fdd;\r\n}\r\n.error:hover,\r\n.seriousError:hover {\r\n  color: #000;\r\n}\r\n.error.intrusion,\r\n.seriousError.intrusion {\r\n  background-image: url(...);\r\n}\r\n.seriousError {\r\n  border-width: 3px;\r\n}\r\n</pre>\r\n\r\n<p><strong>Примесями</strong> в LESS могут служить любые существующие слекторы-классы(.a) или id-селекторы(#b). Примесь в отличии от расширения просто продублирует набор правил. Примеси могут быть не выводимые в CSS файл, для них нужно указать скобки <code>()</code>, например <code>.my-mixin() { background: white;}</code>. Вызов примесей <code> selector {.my-mixin;}</code>. Примеси могут принимать параметры, можно задавать значения по умолчанию,&nbsp;передавать параметры не по порядку и не все значения.</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.mixin(@color: black; @margin: 10px; @padding: 20px) { \r\n  color: @color;\r\n  margin: @margin;\r\n  padding: @padding;\r\n} \r\n.class1 {.mixin(@margin: 20px; @color: #33acfe);}\r\n.class2 {.mixin(#efca44; @padding: 40px);}\r\n</pre>\r\n\r\n<p>Примеси могут использоваться с <strong>условным оператором&nbsp;</strong><code>when</code>, проверка на выполнения условия.</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.mixin (@a) when (lightness(@a) &gt;= 50%) {\r\n  background-color: black;\r\n}\r\n.mixin (@a) when (lightness(@a) &lt; 50%) {\r\n  background-color: white;\r\n}\r\n.mixin (@a) {\r\n  color: @a;\r\n}\r\n.class1 { .mixin(#ddd) }\r\n.class2 { .mixin(#555) }\r\n</pre>\r\n\r\n<pre>\r\n.mixin (@a; @b) when (@a &gt; @b) { width: @a }</pre>\r\n\r\n<p>В LESS нету циклов, но есть поддержка <strong>рекруссии</strong></p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.generate-columns(@n, @i: 1) when (@i =&lt; @n) { \r\n  // @n &mdash; количество колонок\r\n  // @i &mdash; внутренний счетчик от 1 до @n\r\n  \r\n  .column-@{i} {\r\n    width: (@i * 100% / @n);\r\n  }\r\n  \r\n  // Рекурсия\r\n  .generate-columns(@n, (@i + 1));\r\n}\r\n\r\n// Вызываем примесь\r\n.generate-columns(4);\r\n</pre>\r\n\r\n<p>Препроцессор имеет <strong>встроенные функции:</strong></p>\r\n\r\n<ul>\r\n	<li><strong>ceil(3.2)</strong> - округление вверх (результат 4)</li>\r\n	<li><strong>floor(3.2)</strong> - округление вниз (результат 3)</li>\r\n	<li><strong>round(3.51)</strong> - округление по правилам математики (результат 4)</li>\r\n	<li><strong>round(1.84231, 1)</strong> - округление по правилам математики, c указанием точности (результат 1.8)</li>\r\n	<li><strong>lighten(hsl(90, 80%, 50%), 20%)</strong> - делает цвет светлее, на указанную величину(20%)</li>\r\n	<li><strong>darken(hsl(90, 80%, 50%), 20%);</strong> - делает цвет темнее, на указанную величину(20%)</li>\r\n</ul>\r\n\r\n<p>При написании вложенных правил можно использовать <strong>ссылку на родительские селекторы</strong> c помощью амперсанда<strong> &amp; </strong>, при генерации CSS-файла на место амперсанта будут подставлен родительские селекторы.</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.my_class {\r\n  \r\n  // Вложения с родством\r\n  \r\n  &amp;__item { color: #000;}\r\n  \r\n  &amp;:hover, &amp;:focus { background-color: #eee;}\r\n  \r\n  &amp; + &amp; { padding-top: 10px; }\r\n  \r\n  &amp;&amp; { padding-bottom: 30px; }\r\n  \r\n  .book &amp; { margin: 0;}\r\n  \r\n  .book&amp; { margin: 0; }\r\n	\r\n	.title {\r\n	 .link &amp; {height: 10px} // Третий уровень вложенности!!!\r\n	}\r\n  \r\n}\r\n</pre>\r\n\r\n<p>Результат:</p>\r\n\r\n<pre>\r\n.my_class__item { color: #000;}\r\n\r\n.my_class:hover,\r\n.my_class:focus { background-color: #eee;}\r\n\r\n.my_class + .my_class { padding-top: 10px;}\r\n\r\n.my_class.my_class { padding-bottom: 30px;}\r\n\r\n.book .my_class { margin: 0;}\r\n\r\n.book.my_class { margin: 0;}\r\n\r\n// Третий уровень вложенности!!!\r\n.my_class .title .link { height: 10px;}\r\n</pre>\r\n\r\n<p>Также можно создавать <strong>комбинации селекторов</strong></p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\np, ul, li {\r\n border-top: 2px dotted #366;\r\n &amp; + &amp; {border-top: 0;}\r\n}\r\n</pre>\r\n\r\n<p>Результат:</p>\r\n\r\n<pre>\r\np, ul, li {\r\n border-top: 2px dotted #366;\r\n}\r\np + p,\r\np + ul,\r\np + li,\r\nul + p,\r\nul + ul,\r\nul + li,\r\nli + p,\r\nli + ul,\r\nli + li {\r\n border-top: 0;\r\n}\r\n</pre>\r\n\r\n<p>Весь перечень возможностей можно прочитать на <a href="http://lesscss.org/" target="_blank">официальном сайте</a></p>\r\n', '2016-01-23 11:05:48', '2016-01-23 14:36:38', 1, 'publish', 'post', 'open', 31, 35),
(24, 'SCSS', '<p><strong>Переменные</strong> задаются с помощью символа <code>$</code>, могу содержать любые значения, например <code>$font: Arial, Helvetica, sans-serif</code>. Переменные можно вставлять(интерполировать) непосредственно в строку названия css-свойств или их значений, но для это ее нужно экранировать символами <code>#{ }</code>, например <code>background: url(&quot;#{$dir}/fon.jpg&quot;)</code>.</p>\r\n<!--anonsbreak-->\r\n\r\n<p><strong>Расширения</strong>, позволять расширять для селекторов набор правил другим (другими словами создаться копия селектора расширения, где его на место будет поставлен расширяемый селектор), при это будет создать объединяющий набор правил, что позволит избежать дублирования кода. Задается через ключевое слово <code>@extend</code>, например <code>nav ul { @extend .list; background: blue;}</code>. В SCSS при расширении всегда учитываются вложенные правила, в отличии от LESS(где есть ключево слово <em>all</em>)</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.error {\r\nborder: 1px #f00;\r\nbackground-color: #fdd;\r\n\r\n &amp;:hover {\r\n   color: #000;\r\n }\r\n}\r\n\r\n.error.intrusion {\r\n  background-image: url(...);\r\n}\r\n\r\n.seriousError {\r\n  @extend .error ;    \r\n  border-width: 3px;\r\n}\r\n</pre>\r\n\r\n<p>Результат:</p>\r\n\r\n<pre>\r\n.error,\r\n.seriousError {\r\n  border: 1px #f00;\r\n  background-color: #fdd;\r\n}\r\n.error:hover,\r\n.seriousError:hover {\r\n  color: #000;\r\n}\r\n.error.intrusion,\r\n.seriousError.intrusion {\r\n  background-image: url(...);\r\n}\r\n.seriousError {\r\n  border-width: 3px;\r\n}\r\n</pre>\r\n\r\n<p>Есть возможность созданить не выводимые расширения в CSS-файл</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n#context a%extreme { // не выведется в CSS, расширение подставится на место %extreme\r\n  color: blue;\r\n  font-weight: bold;\r\n  font-size: 2em;\r\n}\r\n\r\n.notice {\r\n  @extend %extreme;\r\n}\r\n</pre>\r\n\r\n<p><strong>Примеси</strong> в SCSS задаются с помощью ключего слова <code>@mixin</code>, например <code>@mixin my_mixin { background: white;}</code>. Для вызова примеси, также нужно использовать ключевое слово <code>@include</code>, например <code> selector {@include my_mixin;}</code>. Примеси могут принимать параметры, можно задавать значения по умолчанию, передавать параметры не по порядку и не все значения.</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n@mixin my_mixin($color: black; $margin: 10px; $padding: 20px) { \r\n  color: $color;\r\n  margin: $margin;\r\n  padding: $padding;\r\n} \r\n.class1 {@include my_mixin($margin: 20px; $color: #33acfe);}\r\n.class2 {@include my_mixin(#efca44; $padding: 40px);}\r\n</pre>\r\n\r\n<p>Примеси могут использоваться с <strong>условным оператором</strong><code>@if</code>, проверка на выполнения условия. Для объединения условий используются <code>and, or, and not</code></p>\r\n\r\n<pre>\r\n$mixin mixin_1 ($a) {\r\n  @if lightness($a) &gt;= 50% {\r\n    background-color: black;\r\n  }\r\n  @if lightness($a) \r\n</pre>\r\n\r\n<pre>\r\n@mixin mixin_2 ($a; $b) { \r\n  @if ($a &gt; $b) and ($b == 1) \r\n    width: $a \r\n} \r\n</pre>\r\n\r\n<p>Пример:</p>\r\n\r\n<p>SCSS имеет в своем функционале 3 вида циклов <code>@for, @while, @each</code></p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n@mixin generate-columns($n){ \r\n  // $n &mdash; количество колонок\r\n @for $i from 1 through $n {\r\n   .column-#{$i} {\r\n     width: ($i * 100%/$n);\r\n	 }\r\n }  \r\n}\r\n\r\n// Вызываем примесь\r\n@include generate-columns(4);\r\n</pre>\r\n\r\n<pre>\r\n	\r\n@each $animal, $color, $cursor in (puma, black, default),\r\n                                 (sea-slug, blue, pointer),\r\n                                 (egret, white, move) {\r\n  .#{$animal}-icon {\r\n    background-image: url(&#39;/images/#{$animal}.png&#39;);\r\n    border: 2px solid $color;\r\n    cursor: $cursor;\r\n  }\r\n}\r\n</pre>\r\n\r\n<p>Препроцессор имеет <strong>встроенные функции:</strong></p>\r\n\r\n<ul>\r\n	<li><strong>ceil(3.2)</strong> - округление вверх (результат 4)</li>\r\n	<li><strong>floor(3.2)</strong> - округление вниз (результат 3)</li>\r\n	<li><strong>round(3.51)</strong> - округление по правилам математики (результат 4)</li>\r\n	<li><strong>lighten(hsl(90, 80%, 50%), 20%)</strong> - делает цвет светлее, на указанную величину(20%)</li>\r\n	<li><strong>darken(hsl(90, 80%, 50%), 20%);</strong> - делает цвет темнее, на указанную величину(20%)</li>\r\n</ul>\r\n\r\n<p>При написании вложенных правил можно использовать <strong>ссылку на родительские селекторы</strong> c помощью амперсанда<strong> &amp; </strong>, при генерации CSS-файла на место амперсанта будут подставлен родительские селекторы.</p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\n.my_class {\r\n  \r\n  // Вложения с родством\r\n  \r\n  &amp;__item { color: #000;}\r\n  \r\n  &amp;:hover, &amp;:focus { background-color: #eee;}\r\n  \r\n  &amp; + &amp; { padding-top: 10px; }\r\n  \r\n  &amp;&amp; { padding-bottom: 30px; }\r\n  \r\n  .book &amp; { margin: 0;}\r\n	\r\n	.title {\r\n	 .link &amp; {height: 10px} // Третий уровень вложенности!!!\r\n	}\r\n  \r\n}\r\n</pre>\r\n\r\n<p>Результат:</p>\r\n\r\n<pre>\r\n.my_class__item { color: #000;}\r\n\r\n.my_class:hover,\r\n.my_class:focus { background-color: #eee;}\r\n\r\n.my_class + .my_class { padding-top: 10px;}\r\n\r\n.my_class.my_class { padding-bottom: 30px;}\r\n\r\n.book .my_class { margin: 0;}\r\n\r\n// Третий уровень вложенности!!!\r\n.my_class .title .link { height: 10px;}\r\n</pre>\r\n\r\n<p>Также можно создавать <strong>комбинации селекторов</strong></p>\r\n\r\n<p>Пример:</p>\r\n\r\n<pre>\r\np, ul, li {\r\n border-top: 2px dotted #366;\r\n &amp; + &amp; {border-top: 0;}\r\n}\r\n</pre>\r\n\r\n<p>Результат:</p>\r\n\r\n<pre>\r\np, ul, li {\r\n border-top: 2px dotted #366;\r\n}\r\np + p,\r\np + ul,\r\np + li,\r\nul + p,\r\nul + ul,\r\nul + li,\r\nli + p,\r\nli + ul,\r\nli + li {\r\n border-top: 0;\r\n}\r\n</pre>\r\n\r\n<p>Весь перечень возможностей можно прочитать на <a href="http://sass-lang.com" target="_blank">официальном сайте</a></p>\r\n', '2016-01-23 14:38:22', '2016-01-23 14:52:46', 1, 'publish', 'post', 'open', 32, 16),
(25, 'Emmet', '<p>Emmet плагин для многих популярных текстовых редакторов, который позволяет серьезно упростить написания HTML и CSS, за счет использования сокращений, которые по команде разворачиваются в нужный текст</p>\r\n<!--anonsbreak-->\r\n\r\n<p>Использования для HTML</p>\r\n\r\n<dl>\r\n	<dt>Шаблон html 5</dt>\r\n	<dd>html:5</dd>\r\n	<dt>Cелектор тэга</dt>\r\n	<dd>ul&gt;li</dd>\r\n	<dt>Селектор ID and CLASS</dt>\r\n	<dd>#header<br />\r\n	.title</dd>\r\n	<dt>Селектор дети: &gt;</dt>\r\n	<dd>nav&gt;ul&gt;li</dd>\r\n	<dt>Селектор Sibling: +</dt>\r\n	<dd>div+p+bq</dd>\r\n	<dt>Селектор поднять на уровень выше: ^</dt>\r\n	<dd>div+div&gt;p&gt;span+em^bq</dd>\r\n	<dt>Селектор группировка: ()</dt>\r\n	<dd>(div&gt;dl&gt;(dt+dd)*3)+footer&gt;p</dd>\r\n	<dt>Селектор размножение: *</dt>\r\n	<dd>ul&gt;li*3</dd>\r\n	<dt>Генерация английского текста</dt>\r\n	<dd>lorem</dd>\r\n	<dt>Генерация русского текста</dt>\r\n	<dd>loremru</dd>\r\n</dl>\r\n\r\n<table class="table table-striped">\r\n	<caption>Использования для CSS</caption>\r\n	<tbody>\r\n		<tr>\r\n			<th>Команда</th>\r\n			<th>Свойство CSS</th>\r\n		</tr>\r\n		<tr>\r\n			<td>pos</td>\r\n			<td><code>position:relative;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>pos:a</td>\r\n			<td><code>position:absolute;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>t</td>\r\n			<td><code>top:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>r</td>\r\n			<td><code>right:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>b</td>\r\n			<td><code>bottom:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>l</td>\r\n			<td><code>left:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>z</td>\r\n			<td><code>z-index:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>fl</td>\r\n			<td><code>float: left;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>fl:r</td>\r\n			<td><code>float: right;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>cl</td>\r\n			<td><code>clear: both;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>d</td>\r\n			<td><code>display:block;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>d:n</td>\r\n			<td><code>display: none;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>d:i</td>\r\n			<td><code>display:inline;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>d:ib</td>\r\n			<td><code>display: inline-block;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ov</td>\r\n			<td><code>overflow:hidden</code>;</td>\r\n		</tr>\r\n		<tr>\r\n			<td>ov:a</td>\r\n			<td><code>overflow:auto;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>m</td>\r\n			<td><code>margin:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>m:a</td>\r\n			<td><code>margin:auto;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>mt</td>\r\n			<td><code>margin-top:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>mr</td>\r\n			<td><code>margin-right:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ml</td>\r\n			<td><code>margin-left:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>mb</td>\r\n			<td><code>margin-bottom:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>p</td>\r\n			<td><code>padding:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>pt</td>\r\n			<td><code>padding-top:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>pr</td>\r\n			<td><code>padding-right:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>pb</td>\r\n			<td><code>padding-bottom;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>pl</td>\r\n			<td><code>padding-left;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>fz</td>\r\n			<td><code>font-size:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ff</td>\r\n			<td><code>font-family:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ta</td>\r\n			<td><code>text-align: left;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ta:c</td>\r\n			<td><code>text-align: center;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ta:r</td>\r\n			<td><code>text-align: right;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>td:n</td>\r\n			<td><code>text-decoration: none;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>td:u</td>\r\n			<td><code>text-decoration: underline;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>tt:n</td>\r\n			<td><code>text-transform: none;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>tt:u</td>\r\n			<td><code>text-transform: uppercase;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>tt:l</td>\r\n			<td><code>text-transform: lowercase;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>lh</td>\r\n			<td><code>line-height: ;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bg</td>\r\n			<td><code>background:#000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bg+</td>\r\n			<td><code>background:#fff url() 0 0 no-repeat;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bg</td>\r\n			<td><code>background:#000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bgi</td>\r\n			<td><code>background-image:url();</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bgp</td>\r\n			<td><code>background-position:0 0;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bgs</td>\r\n			<td><code>background-size:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>c</td>\r\n			<td><code>color:#000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bgi</td>\r\n			<td><code>background-image:url();</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>ol</td>\r\n			<td><code>outline:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bd</td>\r\n			<td><code>border:;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bd+</td>\r\n			<td><code>border:1px solid #000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bdt+</td>\r\n			<td><code>border-top:1px solid #000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bdr+</td>\r\n			<td><code>border-right:1px solid #000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bdb+</td>\r\n			<td><code>border-bottom:1px solid #000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>bdl+</td>\r\n			<td><code>border-left:1px solid #000;</code></td>\r\n		</tr>\r\n		<tr>\r\n			<td>lis:n</td>\r\n			<td><code>list-style:none;</code></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p><a href="http://docs.emmet.io/cheat-sheet/" target="_blank">Официальный справочник</a></p>\r\n', '2016-01-23 20:06:47', '2016-01-23 21:53:39', 1, 'publish', 'post', 'open', 33, 24),
(26, 'Grunt', '<p>Позволяет избавиться от постоянных рутинных задач при верстки страницы. Для работы grunt необходимо установить <strong>Nodejs</strong>.</p>\r\n\r\n<p>Grunt и плагины к нему устанавливаются с помощью <strong>npm&nbsp;</strong>(из пакета&nbsp;Nodejs): <code>npm&nbsp;i&nbsp;-g&nbsp;grunt&nbsp;grunt-cli</code>, где</p>\r\n\r\n<ul>\r\n	<li><em>i</em> - install</li>\r\n	<li><em>-g</em> - флаг global</li>\r\n	<li><em>grunt</em> - пакет грант</li>\r\n	<li><em>grunt-cli</em> - для возможности работы через консоль</li>\r\n</ul>\r\n<!--anonsbreak-->\r\n\r\n<p>Чтобы установить плагин, необходимо перейти в папку проекта через консоль и написать команду <code>npm&nbsp;install&nbsp;grunt-contrib-less&nbsp;--save-dev</code>, где</p>\r\n\r\n<ul>\r\n	<li><em>grunt-contrib-less</em> - имя плагина</li>\r\n	<li><em>--save-dev</em> - флаг, которые записывает автоматически название плагина в файл <strong>package.json</strong></li>\r\n</ul>\r\n\r\n<p>Для подключаемых плагинов необходимо создать в папке проекта файл <strong>Gruntfile.js</strong>, где и будем писаться настройки. Там же следует создать файл <strong>package.json</strong>, который будет хранить информацию об уставленных плагинах, это позволить более удобно подключать плагины, и также установить их в следующий раз одной командой <code>npm&nbsp;i</code>.</p>\r\n\r\n<p><code>require(&quot;load-grunt-tasks&quot;)(grunt)</code> - подключение всех плагинов к проекту из файла <strong>package.json</strong></p>\r\n\r\n<p>Запуск на выполнение задачи <code>grunt less </code>. Для запуска одной командой несколько задач существует пакетный запуск (плагин load-grunt-tasks) <code>grunt.registerTask(&quot;build&quot;,[&quot;less&quot;])</code>,</p>\r\n\r\n<h4>Список полезных плагинов</h4>\r\n\r\n<dl>\r\n	<dt><strong>grunt-contrib-clean</strong></dt>\r\n	<dd>Удаляет файлы и папки</dd>\r\n	<dt><strong>grunt-contrib-copy</strong></dt>\r\n	<dd>Копирует файлы и папки</dd>\r\n	<dt><strong>grunt-contrib-less</strong></dt>\r\n	<dd>Компилирует LESS в CSS</dd>\r\n	<dt><strong>grunt-autoprefixer</strong></dt>\r\n	<dd>Проставляет префиксы для CSS свойств</dd>\r\n	<dt><strong>grunt-combine-media-queries</strong></dt>\r\n	<dd>Собирает совпадающие медиа-запросы в один медиа-запрос</dd>\r\n	<dt><strong>grunt-contrib-cssmin</strong></dt>\r\n	<dd>Минимизирует css-файл</dd>\r\n	<dt><strong>grunt-contrib-imagemin</strong></dt>\r\n	<dd>Минимизирует картинки</dd>\r\n	<dt><strong>grunt-contrib-htmlmin</strong></dt>\r\n	<dd>Минимизирует html</dd>\r\n	<dt><strong>grunt-replace</strong></dt>\r\n	<dd>Позволяет выполнять замены в тексте по регулярному выражению</dd>\r\n	<dt><strong>grunt-csscomb</strong></dt>\r\n	<dd>Позволяет форматировать код в соответствие с заданным оформлением</dd>\r\n	<dt><strong>load-grunt-tasks</strong></dt>\r\n	<dd>Позволяет запускать несколько задач подряд одной командой</dd>\r\n	<dt><strong>grunt-contrib-watch</strong></dt>\r\n	<dd>Отслеживает изменения заданных файлов, и выполняет соответствующую задачу</dd>\r\n	<dt><strong>grunt-contrib-concat</strong></dt>\r\n	<dd>Соединяет файлы в один</dd>\r\n	<dt><strong>grunt-newer</strong></dt>\r\n	<dd>Запускает задачу только для измененyых файлов с прошлого успешного запуска</dd>\r\n</dl>\r\n', '2016-01-23 20:12:25', '2016-01-25 21:32:31', 1, 'publish', 'post', 'open', 34, 32),
(27, 'HTML&amp;CSS', '', '2016-01-23 20:15:01', '2016-01-23 20:15:27', 1, 'publish', 'parent', 'open', 35, 1),
(28, 'Шпаргалки', '<p><widget title="Галерея" widget-type="lightbox">[[--widget/gallery/1--]]</widget></p>', '2016-01-26 10:36:51', NULL, 1, 'publish', 'page', 'open', 37, 0);
INSERT INTO `posts` (`post_id`, `post_title`, `post_content`, `post_date_create`, `post_date_update`, `post_author`, `post_status`, `post_type`, `comment_status`, `mlid`, `views`) VALUES
(29, 'О проекте', '<p>Здесь представлена древовидная структура файлов сайта, с описанием назначения файлов. Дерево разворачивается по клику на значок <i class="glyphicon glyphicon-hand-right">&nbsp;</i> <i class="glyphicon glyphicon-folder-close">&nbsp;</i></p>\r\n\r\n<ul class="desc-site">\r\n	<li><span>config - <em>настройки сайта</em></span>\r\n\r\n	<ul>\r\n		<li><span>config.php</span></li>\r\n	</ul>\r\n	</li>\r\n	<li><span>controller - <em>обработка действий пользователя</em></span>\r\n	<ul>\r\n		<li><span>C_Controller.php - <em>базовый класс для всех страниц</em></span></li>\r\n		<li><span>C_Admin_Base.php - <em>базовый класс админ-страниц</em></span></li>\r\n		<li><span>C_Base.php - <em>базовый класс обычных страниц</em></span></li>\r\n		<li><span>C_Admin.php - <em>главная страница админки</em></span></li>\r\n		<li><span>C_Ajax.php - <em>обрабочик AJAX запросов</em></span></li>\r\n		<li><span>C_Audio.php - <em>админка аудиозаписей</em></span></li>\r\n		<li><span>C_Auth.php - <em>авторизация пользователей</em></span></li>\r\n		<li><span>C_Comments.php - <em>обработка комментариев</em></span></li>\r\n		<li><span>C_Gallery.php - <em>админка галереи</em></span></li>\r\n		<li><span>C_Mailing.php - <em>админка рассылок</em></span></li>\r\n		<li><span>C_Menu.php - <em>админка меню</em></span></li>\r\n		<li><span>C_Page.php - <em>основной контент сайта</em></span></li>\r\n		<li><span>C_Poll.php - <em>админка голосования</em></span></li>\r\n		<li><span>C_Posts.php - <em>админка записей(страниц, посты)</em></span></li>\r\n		<li><span>C_Regions.php - <em>админка регионов для шаблона (не реализована)</em></span></li>\r\n		<li><span>C_Template.php - <em>админка шаблонов сайта</em></span></li>\r\n		<li><span>C_Users.php - <em>админка пользователей</em></span></li>\r\n		<li><span>C_Video.php - <em>админка видеозаписей</em></span></li>\r\n		<li><span>C_Widget.php- <em>обрабатывает запросы виджетов</em></span></li>\r\n	</ul>\r\n	</li>\r\n	<li><span>dev - <em>папка для хранения файлов при разработке</em></span>\r\n	<ul>\r\n		<li><span>...</span></li>\r\n	</ul>\r\n	</li>\r\n	<li><span>media - <em>стили, скрипты, шрифты движка</em></span>\r\n	<ul>\r\n		<li><span>сss - <em>стилевые файлы плагинов, библиотек</em></span>\r\n		<ul>\r\n			<li><span>bootstrap.css</span></li>\r\n			<li><span>...</span></li>\r\n		</ul>\r\n		</li>\r\n		<li><span>fonts - <em>шрифты Bootstrap</em></span>\r\n		<ul>\r\n			<li><span>...</span></li>\r\n		</ul>\r\n		</li>\r\n		<li><span>javascript - <em>файлы javascript</em></span>\r\n		<ul>\r\n			<li><span>ckeditor</span>\r\n			<ul>\r\n				<li><span>plugins - <em>плагины</em></span>\r\n				<ul>\r\n					<li><span>anonsbreak - <em>Отделяет анонс статьи</em></span></li>\r\n					<li><span>audio - <em> Позволяет добавлять аудио на страницу</em></span></li>\r\n					<li><span>gallery - <em> Позволяет добавлять галерии на страницу</em></span></li>\r\n					<li><span>poll - <em> Позволяет добавлять опросы на страницу</em></span></li>\r\n					<li><span>stealcontent - <em> Позволяет получать текст статьи с php.net </em></span></li>\r\n					<li><span>video - <em> Позволяет добавлять видео-записи на страницу</em></span></li>\r\n					<li><span>...</span></li>\r\n				</ul>\r\n				</li>\r\n				<li><span>...</span></li>\r\n			</ul>\r\n			</li>\r\n			<li><span>custom - <em>измененные и самописанные скрипты</em></span>\r\n			<ul>\r\n				<li><span>init_audio_add.js - <em>добавления аудиозаписей</em></span></li>\r\n				<li><span>init_gallery_images.js - <em>сортировки и удаление картинок в галереи</em></span></li>\r\n				<li><span>init_posts_add_edit.js - <em>добавления и редактирования записей</em></span></li>\r\n				<li><span>init_maling_addmail.js - <em>обработка создания письма для рассылки</em></span></li>\r\n				<li><span>init_video_add.js - <em>добавления видеозаписей</em></span></li>\r\n				<li><span>main_script.js - <em>скрип, который подключен на всех страницах</em></span></li>\r\n				<li><span>plugin_diafilm.js - <em>слайдер</em></span></li>\r\n				<li><span>plugin_fileuploader.js - <em>загрузка файла кусками</em></span></li>\r\n				<li><span>plugin_upload_img.js - <em>загрузка картинок с возможностью препросмотра</em></span></li>\r\n			</ul>\r\n			</li>\r\n			<li><span>другие файлы - разные скаченные плагины...</span></li>\r\n		</ul>\r\n		</li>\r\n	</ul>\r\n	</li>\r\n	<li><span>model - <em>обработка, хранение и выдача данных</em></span>\r\n	<ul>\r\n		<li><span>map - <em>настройки для валидации, поиска, загрузки файлов</em></span>\r\n		<ul>\r\n			<li><span>file_mime.php - <em>типы файлов, использует M_Files</em></span></li>\r\n			<li><span>messages.php - <em>сообщение ошибок валидации, использует M_Validation</em></span></li>\r\n			<li><span>rules.php - <em>правила валидации валидации, использует M_Validation</em></span></li>\r\n			<li><span>search.php - <em>настройки поиска, использует M_Search</em></span></li>\r\n		</ul>\r\n		</li>\r\n		<li><span>M_MSQL.php - <em>работает с базой данных</em></span></li>\r\n		<li><span>M_Files.php - <em>работает с файлами</em></span></li>\r\n		<li><span>M_Validation.php - <em>выполняет валидацию данных</em></span></li>\r\n		<li><span>M_Link.php - <em>подготавливает ссылки</em></span></li>\r\n		<li><span>M_Rout.php - <em>подключает нужные контроллеры</em></span></li>\r\n		<li><span>M_Helpers.php - <em>вспомогательные функции</em></span></li>\r\n		<li><span>M_Pagination.php - <em>обеспечивает выборку для постраничной загрузки</em></span></li>\r\n		<li><span>M_Model.php - <em>базовый класс для многих моделей</em></span></li>\r\n		<li><span>M_Comments.php - <em>работа с даными комментарий</em></span></li>\r\n		<li><span>M_Gallery.php - <em>работа с данными галерей</em></span></li>\r\n		<li><span>M_Mail.php - <em>работа с данными писем</em></span></li>\r\n		<li><span>M_Mailinglists.php - <em>работа с данными рассылок</em></span></li>\r\n		<li><span>M_MediaFiles.php - <em>работа с файлами, с использованием базы данных</em></span></li>\r\n		<li><span>M_Menu.php - <em>работа с данными меню</em></span></li>\r\n		<li><span>M_MenuLink.php - <em>работа с данными ссыллок меню</em></span></li>\r\n		<li><span>M_Options.php - <em>хранения не больших данных(типа настроек)</em></span></li>\r\n		<li><span>M_Poll.php - <em>работа с данными опросов</em></span></li>\r\n		<li><span>M_PollAnswers.php - <em>работа с данными опросов</em></span></li>\r\n		<li><span>M_Posts.php - <em>работа с данными записей(постов)</em></span></li>\r\n		<li><span>M_Search.php - <em>работа с данными поиска</em></span></li>\r\n		<li><span>M_Users.php - <em>работа с данными пользователей</em></span></li>\r\n		<li><span>M_Roles.php - <em>работа с данными ролей пользователей</em></span></li>\r\n		<li><span>M_Templates.php - <em>работа с данными шаблонов сайта</em></span></li>\r\n		<li><span>M_Regions.php - <em>следит что у шаблона сайта, куда выводится</em></span></li>\r\n		<li><span>M_Block.php - <em>работа с блоками для данных(для отображение в шаблоне меню, архив и т.п)</em></span></li>\r\n	</ul>\r\n	</li>\r\n	<li><span>upload_files - <em>место загрузки всех файлов</em></span>\r\n	<ul>\r\n		<li><span>audio - <em>загруженные аудиозаписи</em></span></li>\r\n		<li><span>gallery - <em>загруженные картинки галерей</em></span></li>\r\n		<li><span>images - <em>картинки для постов, загруженные из ckeditor</em></span></li>\r\n		<li><span>mailing - <em>загруженные письма для рассылок</em></span></li>\r\n		<li><span>video - <em>загруженные видеозаписи</em></span></li>\r\n	</ul>\r\n	</li>\r\n	<li><span>view - <em>отображение страниц</em></span>\r\n	<ul>\r\n		<li><span>default_template - <em>шаблон по умолчанию</em></span>\r\n		<ul>\r\n			<li><span>components - <em>шаблоны компонентов</em></span>\r\n			<ul>\r\n				<li><span>page - <em>отображение обычных страниц</em></span></li>\r\n				<li><span>admin - <em>главная страница админки</em></span></li>\r\n				<li><span>audio - <em>админка аудиозаписей</em></span></li>\r\n				<li><span>auth - <em>авторизация, регистрация</em></span></li>\r\n				<li><span>comments - <em>админка комментария</em></span></li>\r\n				<li><span>gallery - <em>админка галереи</em></span></li>\r\n				<li><span>mailing - <em>админка рассылок</em></span></li>\r\n				<li><span>menu - <em>админка меню</em></span></li>\r\n				<li><span>poll - <em>админка опросов</em></span></li>\r\n				<li><span>posts - <em>админка постов</em></span></li>\r\n				<li><span>template - <em>админка шаблонов</em></span></li>\r\n				<li><span>users - <em>админка управления пользователями</em></span></li>\r\n				<li><span>video - <em>админка видеозаписи</em></span></li>\r\n				<li><span>widgets - <em>отображение виджетов</em></span></li>\r\n			</ul>\r\n			</li>\r\n			<li><span>img - <em>картинки шаблона</em></span>\r\n			<ul>\r\n				<li><span>...</span></li>\r\n			</ul>\r\n			</li>\r\n			<li><span>media - <em>стили и скрипты</em></span>\r\n			<ul>\r\n				<li><span>css - <em>стили шаблона</em></span>\r\n				<ul>\r\n					<li><span>...</span></li>\r\n				</ul>\r\n				</li>\r\n				<li><span>scripts - <em>скрипты шаблона</em></span>\r\n				<ul>\r\n					<li><span>...</span></li>\r\n				</ul>\r\n				</li>\r\n			</ul>\r\n			</li>\r\n			<li><span>media_admin.info - <em>какие стили и скрипты для админки</em></span></li>\r\n			<li><span>media_main.info - <em>какие стили и скрипты для обычной страницы</em></span></li>\r\n			<li><span>v_admin.php - <em>шаблон расположения блоков для админки</em></span></li>\r\n			<li><span>v_main.php - <em>шаблон расположения блоков для обычной страницы</em></span></li>\r\n			<li><span>v_bread_crambs.php - <em>шаблон навигационного пути</em></span></li>\r\n			<li><span>v_navbar.php - <em>шаблон панели постраничной навигации</em></span></li>\r\n		</ul>\r\n		</li>\r\n		<li><span>web_template - <em>другой шаблон</em></span>\r\n		<ul>\r\n			<li><span>...</span></li>\r\n		</ul>\r\n		</li>\r\n	</ul>\r\n	</li>\r\n	<li><span>index.php</span></li>\r\n</ul>\r\n', '2016-01-27 17:11:23', '2016-02-07 19:01:04', 1, 'publish', 'page', 'open', 38, 0),
(31, 'Комбо-контент', '<p><img alt="" src="upload_files/images/ck/post_31/3968971.jpg" style="float: left; width: 100px; height: 141px; border-width: 1px; border-style: solid; margin: 5px;" />Добро пожаловать в&nbsp;Зверополис&nbsp;&mdash;&nbsp;современный город, населенный самыми разными животными, от&nbsp;огромных слонов до&nbsp;крошечных мышек. Зверополис разделен на&nbsp;районы, полностью повторяющие естественную среду обитания разных жителей&nbsp;&mdash;&nbsp;здесь есть и&nbsp;элитный район Площадь Сахары и&nbsp;неприветливый Тундратаун. В&nbsp;этом городе появляется новый офицер полиции, жизнерадостная зайчиха Джуди Хоппс, которая с&nbsp;первых дней работы понимает, как&nbsp;сложно быть маленькой и&nbsp;пушистой среди больших и&nbsp;сильных полицейских. Джуди хватается за&nbsp;первую же&nbsp;возможность проявить себя, несмотря на&nbsp;то, что&nbsp;ее партнером будет болтливый хитрый лис&nbsp;Ник Уайлд. Вдвоем им&nbsp;предстоит раскрыть сложное дело, от&nbsp;которого будет зависеть судьба всех обитателей Зверополиса.</p>\r\n\r\n<p><widget title="Зверополис" widget-type="video">[[--widget/video/13--]]</widget></p>\r\n\r\n<p><widget title="Собираетесь смотреть мультфильм &amp;quot;Зверополис&amp;quot;?" widget-type="poll">[[--widget/poll/5--]]</widget></p>\r\n\r\n<p><widget title="Зверополис" widget-type="diafilm">[[--widget/diafilm/2--]]</widget></p>\r\n', '2016-02-01 15:09:44', '2016-02-07 17:47:15', 1, 'publish', 'page', 'open', 40, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `privs`
--

CREATE TABLE IF NOT EXISTS `privs` (
  `priv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `priv_name` varchar(100) NOT NULL,
  `priv_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`priv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Дамп данных таблицы `privs`
--

INSERT INTO `privs` (`priv_id`, `priv_name`, `priv_description`) VALUES
(1, 'ALL', '1. Все может'),
(2, 'C_Posts:action_index', 'Записи - админ-страница'),
(3, 'C_Posts:action_all', 'Записи - просмотр списка'),
(4, 'C_Posts:action_edit', 'Записи - редактирование'),
(5, 'C_Posts:action_add', 'Записи - добавление'),
(6, 'C_Posts:action_delete', 'Записи - удаление'),
(7, 'C_Users:action_index', 'Пользователи - админ-страница'),
(8, 'C_Users:action_all', 'Пользователи - просмотр списка пользователей'),
(9, 'C_Users:action_edit', 'Пользователи - редактирование пользователей'),
(10, 'C_Users:action_add', 'Пользователи - добавление пользователей'),
(11, 'C_Users:action_delete', 'Пользователи - удаление пользователей'),
(12, 'C_Users:action_allroles', 'Пользователи - просмотр списка ролей'),
(13, 'C_Users:action_editrole', 'Пользователи - редактирование ролей'),
(14, 'C_Users:action_addrole', 'Пользователи - добавление ролей'),
(15, 'C_Users:action_deleterole', 'Пользователи - удаление ролей'),
(16, 'C_Users:action_allroles_privs', 'Пользователи - изменения привилегий'),
(17, 'C_Menu:action_index', 'Меню - админ-страница'),
(18, 'C_Menu:action_all', 'Меню - просмотр списка всех меню'),
(19, 'C_Menu:action_edit', 'Меню - редактирование меню'),
(20, 'C_Menu:action_add', 'Меню - добавление меню'),
(21, 'C_Menu:action_addlink', 'Меню - добавление пункта в меню'),
(22, 'C_Menu:action_editlink', 'Меню - редактирование пункта меню'),
(23, 'C_Menu:action_editlink', 'Меню - удаление пункта меню'),
(24, 'C_Menu:action_itemslist', 'Меню - просмотр списка пунктов меню'),
(25, 'C_Comments:action_index', 'Комментарии - админ-страница'),
(26, 'C_Comments:action_all', 'Комментарии - просмотр списка'),
(27, 'C_Comments:action_edit', 'Комментарии - редактирования'),
(28, 'C_Comments:action_delete', 'Комментарии - удаление'),
(29, 'C_Templates:action_set', 'Шаблон сайта - настройка'),
(30, 'C_Gallery:action_index', 'Галерии - админ-страница'),
(31, 'C_Gallery:action_all', 'Галерии - просмотр списка галерей'),
(32, 'C_Gallery:action_add', 'Галерии - добавление галерии'),
(33, 'C_Gallery:action_edit', 'Галерии - редактирование галерии'),
(34, 'C_Gallery:action_delete', 'Галерии - удаление галерии'),
(35, 'C_Gallery:action_images', 'Галерии - сортировка картинок галерии'),
(36, 'C_Gallery:action_editimg', 'Галерии - редактирование картинок галерии'),
(37, 'C_Gallery:action_upload', 'Галерии - загрузка картинок в галерию'),
(38, 'C_Video:action_index', 'Видео - админ-страница'),
(39, 'C_Video:action_all', 'Видео - просмотр списка'),
(40, 'C_Video:action_add', 'Видео - загрузка видео'),
(41, 'C_Video:action_edit', 'Видео - редактирование'),
(42, 'C_Video:action_delete', 'Видео - удаление'),
(43, 'C_Audio:action_index', 'Аудио - админ-страница'),
(44, 'C_Audio:action_all', 'Аудио - просмотр списка'),
(45, 'C_Audio:action_add', 'Аудио - загрузка аудио'),
(46, 'C_Audio:action_edit', 'Аудио - редактирование'),
(47, 'C_Audio:action_delete', 'Аудио - удаление'),
(48, 'C_Poll:action_index', 'Опросы - админ-страница'),
(49, 'C_Poll:action_all', 'Опросы - просмотр списка'),
(50, 'C_Poll:action_add', 'Опросы - создание'),
(51, 'C_Poll:action_edit', 'Опросы - редактирование'),
(52, 'C_Poll:action_delete', 'Опросы - удаление'),
(53, 'C_Mailing:action_index', 'Рассылка - админ-страница'),
(54, 'C_Mailing:action_all', 'Рассылка - просмотр списка'),
(55, 'C_Mailing:action_add', 'Рассылка - создание листа рассылки'),
(56, 'C_Mailing:action_edit', 'Рассылка - редактирование листа рассылки'),
(57, 'C_Mailing:action_subscribers', 'Рассылка - редактирования списка подписчиков'),
(58, 'C_Mailing:action_viewmail', 'Рассылка - список неотправленных писем'),
(59, 'C_Mailing:action_addmail', 'Рассылка - создание письма'),
(60, 'C_Mailing:action_deletemail', 'Рассылка - удаление письма'),
(61, 'C_Mailing:action_send', 'Рассылка - рассылка писем'),
(62, 'C_Mailing:action_archive', 'Рассылка - просмотр архива писем'),
(63, 'C_Admin:action_index', '2. Главная страница админки');

-- --------------------------------------------------------

--
-- Структура таблицы `privs_roles`
--

CREATE TABLE IF NOT EXISTS `privs_roles` (
  `priv_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`priv_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `privs_roles`
--

INSERT INTO `privs_roles` (`priv_id`, `role_id`) VALUES
(1, 1),
(53, 3),
(54, 3),
(56, 3),
(57, 3),
(58, 3),
(59, 3),
(60, 3),
(61, 3),
(62, 3),
(63, 3),
(2, 4),
(3, 4),
(4, 4),
(5, 4),
(17, 4),
(18, 4),
(24, 4),
(30, 4),
(31, 4),
(32, 4),
(33, 4),
(35, 4),
(36, 4),
(37, 4),
(38, 4),
(39, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(63, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `region_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
  `region_name` varchar(100) NOT NULL COMMENT 'Машинное имя региона',
  `region_title` varchar(100) NOT NULL COMMENT 'Заголовок региона',
  `region_desc` varchar(100) NOT NULL COMMENT 'Описание региона',
  `region_weight` int(10) NOT NULL DEFAULT '0' COMMENT 'Вес при сортировки',
  `template_name` varchar(100) NOT NULL COMMENT 'Название шаблона',
  PRIMARY KEY (`region_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`region_id`, `region_name`, `region_title`, `region_desc`, `region_weight`, `template_name`) VALUES
(1, 'content', 'Содержимое', 'Содержимое страницы', 0, 'default_template'),
(2, 'header', 'Шапка', 'Верхняя часть ', 0, 'default_template'),
(3, 'footer', 'Подвал сайта', 'Нижняя часть', 0, 'default_template'),
(4, 'rightsidebar', 'Правая панель', 'Правая часть сайта', 0, 'default_template'),
(5, 'footerPanelLeft', 'Панель нижняя левая', 'Панель нижняя левая', 0, 'default_template'),
(6, 'footerPanelMiddle', 'Панель нижняя средняя', 'Панель нижняя средняя', 0, 'default_template'),
(8, 'footerPanelRight', 'Панель нижняя правая', 'Панель нижняя правая', 0, 'default_template'),
(9, 'content', 'Содержимое', 'Содержимое страницы', 0, 'web_template'),
(10, 'rightsidebar', 'Правая панель', 'Правая часть сайта', 0, 'web_template');

-- --------------------------------------------------------

--
-- Структура таблицы `regions_blocks`
--

CREATE TABLE IF NOT EXISTS `regions_blocks` (
  `region_id` int(10) unsigned NOT NULL COMMENT 'Внешняя ссылка на id regions',
  `block_id` int(10) unsigned NOT NULL COMMENT 'Внешняя ссылка на id blocks',
  `weight` int(10) NOT NULL DEFAULT '0' COMMENT 'Вес при сортировки',
  PRIMARY KEY (`region_id`,`block_id`),
  KEY `blocks_ibfk` (`block_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `regions_blocks`
--

INSERT INTO `regions_blocks` (`region_id`, `block_id`, `weight`) VALUES
(1, 1, 0),
(4, 2, -9),
(4, 4, -10),
(4, 7, 0),
(4, 8, -8),
(4, 9, 0),
(5, 3, 0),
(6, 5, 0),
(8, 6, 0),
(9, 1, 0),
(10, 2, 0),
(10, 4, -10),
(10, 7, 0),
(10, 8, -5),
(10, 9, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) NOT NULL,
  `role_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_description`) VALUES
(1, 'admin', 'Администратор'),
(2, 'user', 'Обычный пользователь'),
(3, 'postman', 'Почтальон'),
(4, 'editor', 'Редактор');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sid` varchar(100) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_last` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` char(32) DEFAULT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `mimemail` char(1) NOT NULL DEFAULT 'H' COMMENT 'Тип писем при рассылки',
  PRIMARY KEY (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `login`, `password`, `role_id`, `user_name`, `mimemail`) VALUES
(1, 'admin@devblog.ru', 'e10adc3949ba59abbe56e057f20f883e', 1, 'Админ', 'H'),
(2, 'postman@devblog.ru', 'e10adc3949ba59abbe56e057f20f883e', 3, 'Михаил', 'H'),
(4, 'editor@devblog.ru', 'e10adc3949ba59abbe56e057f20f883e', 4, 'Гриша', 'H');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `commentsTree`
--
ALTER TABLE `commentsTree`
  ADD CONSTRAINT `commentsTree_ibfk_1` FOREIGN KEY (`idAncestor`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentsTree_ibfk_2` FOREIGN KEY (`idDescendant`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentsTree_ibfk_3` FOREIGN KEY (`idSubject`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `gallery_mediafile`
--
ALTER TABLE `gallery_mediafile`
  ADD CONSTRAINT `gallery_ibfk` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`gallery_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mediafile_ibfk` FOREIGN KEY (`fid`) REFERENCES `mediafile` (`fid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `menu_link`
--
ALTER TABLE `menu_link`
  ADD CONSTRAINT `menu_link_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `poll_answers`
--
ALTER TABLE `poll_answers`
  ADD CONSTRAINT `polls_ibfk` FOREIGN KEY (`pid`) REFERENCES `poll` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `poll_vote`
--
ALTER TABLE `poll_vote`
  ADD CONSTRAINT `answer_ibfk` FOREIGN KEY (`aid`) REFERENCES `poll_answers` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_author`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `privs_roles`
--
ALTER TABLE `privs_roles`
  ADD CONSTRAINT `privs_roles_ibfk_1` FOREIGN KEY (`priv_id`) REFERENCES `privs` (`priv_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `privs_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `regions_blocks`
--
ALTER TABLE `regions_blocks`
  ADD CONSTRAINT `blocks_ibfk` FOREIGN KEY (`block_id`) REFERENCES `blocks` (`block_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `regions_ibfk` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
