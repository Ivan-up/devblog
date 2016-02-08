<? extract($object->navParams); ?>
<div class="table-resposive">
	<?=$object->inner_nav?>
	<h2 class="sub-header">Список рассылок (<?=$count?>)</h2>
	<? if ($count < 1): ?>
	<p>Нету ни одной рассылки</p>
	<? else :?>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок</th>
		<th class="hidden-xs  hidden-sm"> Описание</th>
		<th> Подписчики</th>
		<th> Архив писем</th>
		<th> Редактировать</th>
		<th class="hidden-xs"> Статус</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->pages as $page): ?>
			<tr>
			<? $id=$page['listid'] ?>
				<td><?=$i?></td>
				<td><?=$page['listname'] ?></td>				
				<td class="hidden-xs hidden-sm"><?=$page['blurb'] ?></td>				
				<td>
				<? if ($object->check_priv('C_Mailing:action_subscribers')) :?>
					<a href="<?=M_Link::ToAdminMailing('subscribers', $id)?>">Подписчики(<?=$page['subscr']?>)</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Mailing:action_archive')) :?>
					<a href="<?=M_Link::ToAdminMailing('archive', $id)?>">Архив</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Mailing:action_edit')) :?>
					<a href="<?=M_Link::ToAdminMailing('edit', $id)?>">Редактировать</a>
				<? endif?>
				</td>
				<td class="hidden-xs"><? echo ($page['is_show'] == 1) ? "Включено" : "Отключено"?></td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<? endif?>
</div>














