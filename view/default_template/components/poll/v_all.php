<? extract($object->navParams); ?>
<div class="table-resposive">
	<h2 class="sub-header">Список опросов (<?=$count?>)</h2>
	<? if ($count < 1): ?>
	<p>Нету ни одного опроса</p>
	<? else :?>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Название опроса</th>
		<th> Посмотреть</th>
		<th> Редактриовать</th>		
		<th> Удалить</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->polls as $poll): ?>
			<tr>
			<? $id=$poll['pid'] ?>
				<td><?=$i?></td>
				<td><?=$poll['question'] ?></td>
				<td><a target="_blank" href="<?=M_Link::ToPage('poll', $id)?>">Просмотреть</a></td>
				<td>
				<? if ($object->check_priv('C_Poll:action_edit')) :?>
					<a href="<?=M_Link::ToAdminPoll('edit', $id)?>"> Редактировать</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Poll:action_delete')) :?>
					<a href="<?=M_Link::ToAdminPoll('delete', $id)?>"> Удалить</a>
				<? endif?>
				</td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<? endif?>
	<? if ($object->check_priv('C_Poll:action_add')) :?>
	<p>
		<a class="btn btn-primary btn" href="<?=M_Link::ToAdminPoll('add')?>">Добавить опрос&raquo;</a>
	</p>
	<? endif?>
</div>














