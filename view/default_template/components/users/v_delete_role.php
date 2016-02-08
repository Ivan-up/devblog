<?=$tabs?>
<? if (!empty($messages) && is_array($messages)) : ?>
<ul class="list-group">
	<? foreach ($messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<a href="<?=M_Link::ToAdminUsers('allroles')?>">Вернуться к списку ролей</a>
