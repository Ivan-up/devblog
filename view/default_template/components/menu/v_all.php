<? extract($object->navParams); ?>
<div class="table-resposive">
	<h2 class="sub-header">Список меню (<?=$count?>)</h2>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок</th>
		<th> Редактриовать</th>
		<th> Удалить</th>
		<th> Список ссылок</th>
		<th class="hidden-xs hidden-sm"> Добавить ссылку</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->menu as $menu): ?>
			<tr>
			<? $id=$menu['menu_id'] ?>
				<td><?=$i?></td>
				<td><?=$menu['menu_title'] ?></td>				
				<td>
				<? if ($object->check_priv('C_Menu:action_edit')) :?>
					<a href="<?=M_Link::ToAdminMenu('edit', $id)?>"> Редактировать</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Menu:action_delete')) :?>
					<a href="<?=M_Link::ToAdminMenu('delete', $id)?>"> Удалить</a>
				<? endif?>	
				</td>
				<td>
				<? if ($object->check_priv('C_Menu:action_itemslist')) :?>
					<a href="<?=M_Link::ToAdminMenu('itemslist', $id)?>">Список ссылок</a>
				<? endif?>
				</td>
				<td class="hidden-xs hidden-sm">
				<? if ($object->check_priv('C_Menu:action_addlink')) :?>
					<a href="<?=M_Link::ToAdminMenu('addlink', $id)?>">Добавить ссылку</a>
				<? endif?>
				</td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	
	<p>
		<? if ($object->check_priv('C_Menu:action_add')) :?>
		<a class="btn btn-primary btn" href="<?=M_Link::ToAdminMenu('add')?>">Добавить меню&raquo;</a>
		<? endif?>
	</p>
</div>














