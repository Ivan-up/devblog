<nav class="sidebar-module">
<h4>Самые обсуждаемые</h4>
<ul class="nav nav-list">
<? foreach($posts as $post) :?>
<li>
	<a href="<?=M_Link::ToPage('post',$post['post_id'])?>">
		<?=$post['post_title']?>
	</a>
</li>
<? endforeach?>
</ul>
</nav>











