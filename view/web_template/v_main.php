<?php
/**
 * Основной шаблон
 */
extract($object->regions);
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">	
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<base href="<?=BASE_URL?>">
	<title><?=$this->title?></title>
	
	<? foreach($object->styles as $style): ?>
	<link rel="stylesheet" type="text/css" media="screen" href="<?=CSS_DIR . $style?>.css">  	
	<? endforeach; ?>
	
	<? foreach($object->stylesTemplate as $style): ?>
	<link rel="stylesheet" type="text/css" media="screen" href="<?=$object->templateDir. '/media/css/' . $style?>.css">  	
	<? endforeach; ?>
	
	<? foreach($object->scripts as $script): ?>
	<script src="<?=JS_DIR . $script?>.js"></script> 	
	<? endforeach; ?>		
	
	<? foreach($object->scriptsTemplate as $script): ?>
	<script src="<?=$object->templateDir . '/media/javascript/' . $script?>.js"></script> 	
	<? endforeach; ?>	
	
</head>
<body>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<a href="<?=M_Link::ToPage()?>"><img src="<?=$object->templateDir?>/img/logo.png" class="logo" alt="logo"></a>
				</div>
				<div class="col-md-6"></div>
			</div>
		</div>
	</header>
	<div class="container container-main">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" area-expanded="false" area-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<?foreach($object->mainMenu as $item) :?>
						<?$url = $item['link_path']?>		
						<? if (strpos($url,'http') !== 0) $url = BASE_URL.$url ?>
						<li <?if ($object->isActiveUrl($url)) echo 'class="active"'?>><a href="<?=$url?>"><?=$item['link_title']?></a></li>
						<?endforeach?>
					</ul>
				</div>
			</div>
		</nav>
		<? if ($object->isMainPage()) : ?>
		<div id="myCarousel1" class="carousel slide" data-ride="carousel">	
		
			<div class="row">
			
				<div class="col-md-8 carousel-col">	
				
						<div class="carousel-inner">
						
							<div class="item active">
								<img src="<?=$object->templateDir?>/img/git.jpg" alt="">
								<div class="carousel-caption">
									<h4><a target="_blank" href="https://git-scm.com">Git</a></h4>
									<p>Система контролей версий файлов</p>
								</div>
							</div>
							
							<div class="item">
								<img src="<?=$object->templateDir?>/img/grunt.jpg" alt="">
								<div class="carousel-caption">
									<h4><a target="_blank" href="http://gruntjs.com">Grunt</a></h4>
									<p>Инструмент для сборки проектов из командной строки с использованием задач.</p>
								</div>
							</div>
							
							<div class="item">
								<img src="<?=$object->templateDir?>/img/jquery.jpg" alt="">
								<div class="carousel-caption">
									<h4><a target="_blank" href="https://jquery.com">Jquery</a></h4>
									<p>Библиотека javascript. jQuery помогает легко получать доступ к любому элементу DOM, обращаться к атрибутам и содержимому элементов DOM, манипулировать ими. Также библиотека jQuery предоставляет удобный API для работы с AJAX</p>
								</div>
							</div>
							
							<div class="item">
								<img src="<?=$object->templateDir?>/img/scss_less.jpg" alt="">
								<div class="carousel-caption">
									<h4>Препроцессоры</h4>
									<p>Препроцессоры инструмент позволяющий значительно упростить написания стиливый файлов. Наиболее популярные препроцессорами являются <a target="_blank" href="http://sass-lang.com">SCSS</a> и <a target="_blank" href="http://lesscss.org">LESS</a></p>
								</div>
							</div>
							
							<div class="item">
								<img src="<?=$object->templateDir?>/img/html.jpg" alt="">
								<div class="carousel-caption">
									<h4>Больше возможностей при верстке</h4>
									<p>В HTML5 появилось множество семантических элементов, а также тегов, позволяющих вставлять аудио и видео на сайт. CSS3 значительно расширил свои возможности. Появилось HTML5 api. А использование фреймворков позволить не писать все элементы с нуля. Известный фреймворк <a target="_blank" href="http://getbootstrap.com">Bootstrap</a></p>
								</div>
							</div>
							
						</div>
						
				</div>
				
				<ul class="list-group slider-list col-md-4">
					<li class="list-group-item active" data-target="#myCarousel1" data-slide-to="0">
						<img src="<?=$object->templateDir?>/img/git.jpg" alt="" class="img-thumbnail img-slide">
						<h4>Git</h4>
						<p>Система контролей версий файлов</p>
					</li>
					<li class="list-group-item" data-target="#myCarousel1" data-slide-to="1">
						<img src="<?=$object->templateDir?>/img/grunt.jpg" alt="" class="img-thumbnail img-slide">
						<h4>Grunt</h4>
						<p>Cборкa проектов из командной строки.</p>
					</li>
					<li class="list-group-item" data-target="#myCarousel1" data-slide-to="2">
						<img src="<?=$object->templateDir?>/img/jquery.jpg" alt="" class="img-thumbnail img-slide">
						<h4>Jquery</h4>
						<p>Библиотека javascript, облегчает работу с DOM.</p>
					</li>
					<li class="list-group-item" data-target="#myCarousel1" data-slide-to="3">
						<img src="<?=$object->templateDir?>/img/scss_less.jpg" alt="" class="img-thumbnail img-slide">
						<h4>Препроцессоры</h4>
						<p>Позволяют упростить написания стиливый файлов.</p>
					</li>
					<li class="list-group-item" data-target="#myCarousel1" data-slide-to="4">
						<img src="<?=$object->templateDir?>/img/html.jpg" alt="" class="img-thumbnail img-slide">
						<h4>Больше возможностей</h4>
						<p>HTML5, CSS3, HTML5 api, Bootstrap.</p>
					</li>
				</ul>			
			
			</div>
			
		</div>
		<? endif?>
		
		<div class="row">
		
			<div class="col-md-8">
				<?=$content?>
			</div>
			
			<div class="col-md-4">
				<?=$rightsidebar?>
			</div>
			
		</div>		
		
		
	</div>
</body>
</html>