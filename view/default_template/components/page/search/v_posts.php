<div>
	<h2>Страницы</h2>
		<? foreach ($records as $post): ?>
			<p>
				<a href="<?=M_Link::ToPage('post', $post['post_id'])?>" target="_blank">
					<?=$post['post_title'] ?>
				</a>
			</p>
		<? endforeach; ?>
</div>