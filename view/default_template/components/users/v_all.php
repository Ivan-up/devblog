<? extract($object->navParams); ?>
<div class="table-resposive">
	<?=$object->tabs?>
	<h2 class="sub-header">Все пользователи (<?=$count?>)</h2>
	<?if (count($object->users) == 0) : ?>
	<p>Вы еще не создали пользователей</p>
	<?else : ?>
	<table class="table table-striped">
	<thead>
		<tr>
			<th class="numberlist">№</th>
			<th>Логин</th>
			<th>Имя</th>
			<th>Роль</th>
			<th class="hidden-xs hidden-sm">Привелегии</th>
			<th>Редактировать</th>
			<th>Удалить</th>
		</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->users as $user): ?>
		<tr>
			<td><?=$i?></td>
			<td><?=$user['login']?></td>
			<td><?=$user['user_name']?></td>
			<td><?=$user['role_name']?></td>
			<td class="hidden-xs hidden-sm"><?=$user['allprivs']?></td>
			<td>
			<? if ($object->check_priv('C_Users:action_edit')) :?>	
				<a href="<?=M_Link::ToAdminUsers('edit', $user['user_id'])?>">Редактировать</a></td>
			<? endif?>
			<td>
			<? if ($object->check_priv('C_Users:action_edit')) :?>
				<? if($user['user_id'] != 1): ?>
				<a href="<?=M_Link::ToAdminUsers('delete', $user['user_id'])?>" onClick="javascript: return confirm('Вы действительно хотите удалить?')">Удалить</a>
				<? endif?>
			<? endif?>
			</td>
		</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<?endif?>
</div>














