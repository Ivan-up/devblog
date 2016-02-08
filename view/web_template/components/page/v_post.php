<article class="well blog-post">	
	<header>
		<h3 class="blog-post-title"><?=$object->page['post_title'] ?></h3>							
		<p class="post-info">
			Опубликовал <?=$object->page['user_name'] ?>
			<?=M_Helpers::strf_time('%d %B %Y, %a, %H:%M', strtotime($object->page['post_date_create'])) ?>
		</p>
		<p>Просмотров: <?=$object->page['views']+1?></p>
	</header>
	<div>
		<?=$object->page['post_content'] ?>
	</div>
</article>


