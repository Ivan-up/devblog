<article>	
	<header>
		<h2><?=$object->page['post_title'] ?></h2>							
	</header>
	<div>
		<?=$object->page['post_content'] ?>
	</div>
	<footer>
		<? if (!empty($object->page['children'])):?>
			<ul class="list-unstyled">
			<? foreach ($object->page['children'] as $item):?>
				<li><a href="<?=$item['link_path']?>"><?=$item['link_title']?></a></li>
			<? endforeach?>
			</ul>
		<? endif?>
	</footer>
</article>


