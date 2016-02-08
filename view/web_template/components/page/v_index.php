<? extract($object->navParams); ?>
<? foreach ($object->pages as $page) : ?>
<article class="well blog-post">	
	<h3><a href="<?=M_Link::ToPage('post',$page['post_id'])?>"><?=$page['post_title'] ?></a></h3>							
	<p class="post-info">
		Опубликовал <?=$page['user_name'] ?>
		<?=$page['post_date_create'] ?>
	</p>
	<? if (isset($page['anons'])) :?>
	<?=$page['anons'] ?>
	<? else :?>
	<?=$page['post_content'] ?>
	<? endif?>
	<a href="<?=M_Link::ToPage('post',$page['post_id'])?>" class="btn btn-primary">Читать далее <span class="glyphicon glyphicon-chevron-right"></span></a>
</article>
<? endforeach?>
<?=$object->navBar ?>