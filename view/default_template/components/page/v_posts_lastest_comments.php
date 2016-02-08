<nav class="sidebar-module">
<h4>Последние комментария</h4>
<ul class="nav nav-list">
<? foreach($comments as $comment) :?>
<li>
	<a href="<?=M_Link::ToPage('post',$comment['idSubject'])?>#commentItem<?=$comment['comment_id'];?>">
		<?=$comment['dateCreate'] . "-" .$comment['comment_content']?>
	</a>
</li>
<? endforeach?>
</ul>
</nav>











