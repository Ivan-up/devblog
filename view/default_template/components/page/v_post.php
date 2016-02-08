<article class="blog-post">	
	<header>
		<h2 class="blog-post-title"><?=$object->page['post_title'] ?></h2>							
		<p class="block-post-meta">
			<span><?=M_Helpers::strf_time('%d %B %Y, %a, %H:%M', strtotime($object->page['post_date_create'])) ?></span>
			<span> - <?=$object->page['user_name'] ?></span>
			<p>Просмотров: <?=$object->page['views']+1?></p>
		</p>		
	</header>
	<div>
		<?=$object->page['post_content'] ?>
	</div>
	<footer>								
	</footer>
</article>


