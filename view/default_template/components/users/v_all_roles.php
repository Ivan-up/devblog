<? extract($object->navParams); ?>
<div class="table-resposive">
	<?=$object->tabs?>
	<h2 class="sub-header">Все роли (<?=$count?>)</h2>
	<?if (count($object->roles) == 0) : ?>
	<p>Вы еще не создали ролей</p>
	<?else : ?>
	<table class="table table-striped">
	<thead>
		<tr>
			<th class="numberlist">№</th>
			<th>Название</th>
			<th>Описание</th>
			<th>Редактировать</th>
			<th>Удалить</th>
		</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->roles as $role): ?>
		<tr>
			<td><?=$i?></td>
			<td><?=$role['role_name']?></td>
			<td><?=$role['role_description']?></td>
			<td>
			<? if ($object->check_priv('C_Users:action_editrole')) :?>
				<a href="<?=M_Link::ToAdminUsers('editrole', $role['role_id'])?>">Редактировать</a>
			<? endif?>
			</td>
			<td>
			<? if ($object->check_priv('C_Users:action_deleterole')) :?>
				<a href="<?=M_Link::ToAdminUsers('deleterole', $role['role_id'])?>">Удалить</a>
			<? endif?>
			</td>
		</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<br/>
	<?endif?>
</div>














