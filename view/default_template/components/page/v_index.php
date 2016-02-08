<? extract($object->navParams); ?>
<h3><?=$object->title?></h3>
<? foreach ($object->pages as $page) : ?>
<article class="blog-post">	
	<header>
		<h2><a href="<?=M_Link::ToPage('post',$page['post_id'])?>"><?=$page['post_title'] ?></a></h2>							
		<p class="block-post-meta">
			<?=$page['post_date_create'] ?>
			<a href="#"><?=$page['user_name'] ?></a>
		</p>		
	</header>
	<div>
		<? if (isset($page['anons'])) :?>
		<?=$page['anons'] ?>
		<? else :?>
		<?=$page['post_content'] ?>
		<? endif?>
	</div>
	<footer>
		<ul class="nav nav-pills">
			<li><a href="<?=M_Link::ToPage('post',$page['post_id'])?>">Подробнее</a></li>			
			<?if($page['comment_status'] === 'open'):?>
				<li><a href="#">
				<?$comments = isset($this->countComments[$page['post_id']]) ? $this->countComments[$page['post_id']] : 0?>
					Комментарий <span class="badge"><?=$comments?></span></a></li>
			<?endif?>
			<li>Просмотров <span class="badge"><?=$page['views']?></li>
		</ul>
	</footer>
</article>
<? endforeach?>
<?=$object->navBar ?>