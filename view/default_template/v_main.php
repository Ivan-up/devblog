<?php
/**
 * Основной шаблон
 * ===============
 * $title - заголовок
 * $content - HTML страницы
 */
extract($object->regions);
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="ru"> <!--<![endif]-->

<head>

	<meta charset="utf-8">
	<base href="<?=BASE_URL?>">
	
	
	<title><?=$this->title?></title>
	
	<link rel="shortcut icon" href="<?=$object->templateDir?>/img/favicon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="<?=$object->templateDir?>/img/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?=$object->templateDir?>/img/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?=$object->templateDir?>/img/favicon/apple-touch-icon-114x114.png">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


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

	<header class="main_head">
		<div class="container">
			<div class="row">				
					<nav class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<button class="navbar-toggle" data-target=".navbar-collapse" 
								data-toggle="collapse" type="button">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>	
							<a class="navbar-brand" href="<?=M_Link::ToPage()?>"><?=SITE_NAME?></a>
						</div>
						<div class="navbar-collapse collapse">
							<ul class="nav navbar-nav navbar-right">
								<?foreach($object->mainMenu as $item) :?>
								<?$url = $item['link_path']?>		
								<? if (strpos($url,'http') !== 0) $url = BASE_URL.$url ?>
								<li <?if ($object->isActiveUrl($url)) echo 'class="active"'?>><a href="<?=$url?>"><?=$item['link_title']?></a></li>
								<?endforeach?>
							</ul>						
						</div>						
					</nav>			
			</div>
		</div>				
	</header>
	<? if ($object->isMainPage()) :?>
	<section class="s_slider">
		<div class="container">
			<div class="row">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarouse1" data-slide-to="1"></li>
						<li data-target="#myCarouse1" data-slide-to="2"></li>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<img class="first-slide" src="<?=$object->templateDir?>/img/slide-image-1.jpg" alt="First slide">
						</div>
						<div class="item">
							<img src="<?=$object->templateDir?>/img/slide-image-2.jpg" alt="First slide">
						</div>
						<div class="item">
							<img src="<?=$object->templateDir?>/img/slide-image-3.jpg" alt="First slide">
						</div>
					</div>
					<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div><!-- /.carousel -->
			</div>
		</div>	
	</section>
	<? endif?>
	
	<section class="s_main">
		<div class="container">
			<div class="row">
				<div class="main_content clearfix">
					<div class="col-sm-8 blog-main">						
						<?=$content?>
					</div>
					
					<div class="col-sm-4">
						<aside class="left_side">							
							<?=$rightsidebar?>
						</aside>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<footer class="main_footer">
		<div class="container">
		
			<div class="row">
				<div class="footer_panels clearfix">				
					<div class="col-sm-4">
						<div class="sidebar-module">
							<?=$footerPanelLeft?>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="sidebar-module">
							<?=$footerPanelMiddle?>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="sidebar-module">
							<?=$footerPanelRight?>
						</div>
					</div>
					
					
				</div>				
				
			</div>
			
			<div class="row">
				<div class="blog-footer">
					<p>Copyrigth ©</p>
				</div>
			</div>
		</div>
	</footer>
	
	<div class="hidden"></div>

	<div class="loader">
		<div class="loader_inner"></div>
	</div>

	<!--[if lt IE 9]>
	<script src="media/javascript/html5shiv/es5-shim.min.js"></script>
	<script src="media/javascript/html5shiv/html5shiv.min.js"></script>
	<script src="media/javascript/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="media/javascript/respond/respond.min.js"></script>
	<![endif]-->	
</body>
</html>