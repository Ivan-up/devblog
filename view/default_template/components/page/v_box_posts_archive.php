<div class="sidebar-module">
<h4>Архивы</h4>
<ol class="list-unstyled">
<? foreach($posts as $item) :?>
<li class="active">
	<a href="<?=M_Link::ToPage('archive',$item['cdate'])?>">
		<?="{$months[$item['month']]}  {$item['year']} ({$item['countItems']})"?>
	</a>
</li>
<? endforeach?>
</ol>
</div>







