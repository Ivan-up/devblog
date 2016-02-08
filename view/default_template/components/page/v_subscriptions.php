<? extract($object->navParams); ?>
<div class="table-resposive">
	<h2 class="sub-header">Каналы подписок (<?=$count?>)</h2>
	<form method="post" action="" class="form-horizontal" >
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->pages as $page): ?>
		
		<div class="panel panel-info">
			<div class="panel-heading"><?=$i.'. '.$page['listname']?></span></div>
			<div class="panel-body">
				<p><?=$page['blurb']?></p>
				<? if (in_array($page['listid'],$object->user_subscr)) : ?>
				<input type="submit" name="listid_unsubsc_<?=$page['listid']?>" class="form-control" value="Отменить подписку"/>
				<? else :?>
				<input type="submit" name="listid_subsc_<?=$page['listid']?>" class="form-control" value="Подписаться"/>
				<? endif?>
			</div>
		</div>		
		
		<? $i++; endforeach ?>
	</form>
	<?=$object->navBar ?>
</div>














