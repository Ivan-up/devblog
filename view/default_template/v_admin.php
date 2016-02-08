<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="ru"> <!--<![endif]-->

<head>

	<meta charset="utf-8">
	<base href="<?=BASE_URL?>">
	<title><?=$object->title?></title>
	

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
	<script src="/<?=$object->templateDir . '/media/javascript/' . $script?>.js"></script> 	
	<? endforeach; ?>		

</head>

<body>

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">			
			<div class="navbar-header">
				<button class="navbar-toggle" data-target="#login-collapse" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=M_Link::ToPage()?>"><?=SITE_NAME?></a>
			</div>
			<div id="login-collapse" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?=M_Link::ToAuth('account')?>"><?=$object->getUserName()?></a></li>
					<li><a href="<?=M_Link::ToAuth('logout')?>"> Выйти</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 sidebar">
			
				<div class="navbar navbar-default">
				
					<div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
						
					<div id="sidebar-collapse" class="navbar-collapse collapse">
					
						<ul class="nav nav-sidebar">
							<?$active = $object->getCurrController()?>
							
							<? if ($object->check_priv('C_Posts:action_index')):?>
							<li <? if ($active == 'C_Posts') echo 'class="active"'?> >
								<a href="<?=M_Link::ToAdminPosts()?>"> Записи</a>
							</li>
							<? endif?>
							
							<? if ($object->check_priv('C_Users:action_index')):?>
							<li <? if ($active == 'C_Users') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminUsers()?>">Пользователи</a>
							</li>
							<? endif?>
							
							<? if ($object->check_priv('C_Menu:action_index')):?>
							<li <? if ($active=='C_Menu') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminMenu()?>">Меню</a>
							</li>	
							<?endif?>
							
							<?if($object->check_priv('C_Comments:action_index')):?>
							<li <?if($active=='C_Comments') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminComments()?>">Комментарии</a>
							</li>
							<?endif?>
							
							<?if($object->check_priv('C_Templates:action_set')):?>
							<li <?if($active=='C_Templates') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminTemplates('set')?>">Оформление</a>
							</li>
							<?endif?>
							
							<?if($object->check_priv('C_Gallery:action_index')):?>
							<li <?if($active=='C_Gallery') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminGallery()?>">Галерии</a>
							</li>
							<?endif?>
							
							<?if($object->check_priv('C_Video:action_index')):?>
							<li <?if($active=='C_Video') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminVideo()?>">Видео</a>
							</li>
							<?endif?>
							
							<?if($object->check_priv('C_Audio:action_index')):?>
							<li <?if($active=='C_Audio') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminAudio()?>">Аудио</a>
							</li>
							<?endif?>
							
							<?if($object->check_priv('C_Mailing:action_index')):?>
							<li <?if($active=='C_Mailing') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminMailing()?>">Рассылка</a>
							</li>
							<?endif?>
							
							<?if($object->check_priv('C_Poll:action_index')):?>
							<li <?if($active=='C_Poll') echo 'class="active"'?>>
								<a href="<?=M_Link::ToAdminPoll()?>">Опросы</a>
							</li>
							<?endif?>
							
						</ul>
						
					</div>
					
				</div>
				
			</div>
			<div class="col-md-10 col-sm-offset-2 main">
				<h1 class="page-header">Панель управления</h1>			
					<?=$object->content?>	
			</div>		
		</div>		
	</div>
	
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