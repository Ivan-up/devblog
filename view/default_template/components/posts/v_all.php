<? extract($object->navParams)?>
<div class="table-resposive">
	<h2 class="sub-header">Список страниц (<?=$count?>)</h2>
	<? if ($count < 1): ?>
	<p>Нету ни одной записи</p>
	<? else :?>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок</th>
		<th> Просмотреть</th>
		<th> Редактировать</th>
		<th> Удалить</th>
		<th class="hidden-xs hidden-sm"> Статус</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->pages as $page): ?>
			<tr>
			<? $id=$page['post_id'] ?>
				<td><?=$i?></td>
				<td><?=$page['post_title'] ?></td>
				<td><a href="<?=M_Link::ToPage('post', $id)?>" target="_blank"> Просмотреть</a></td>
				<td><? if ($object->check_priv('C_Posts:action_edit')):?><a href="<?=M_Link::ToAdminPosts('edit', $id)?>" > Редактировать</a><?endif?></td>
				<td>
				<? if ($object->check_priv('C_Posts:action_delete')):?>
					<a href="<?=M_Link::ToAdminPosts('delete', $id)?>" onClick="javascript: return confirm('Вы действительно хотите удалить?')" > Удалить</a>
				<?endif?>
				</td>
				<td class="hidden-xs hidden-sm"> 
				<? if ($page['post_status'] == 'publish') 
					echo "Опубликована"; 
				else
					echo "Неопубликована"; ?></td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<?endif?>
	<? if ($object->check_priv('C_Posts:action_add')):?>
	<p><a class="btn btn-primary btn" href="<?=M_Link::ToAdminPosts('add')?>">Добавить новую запись &raquo;</a></p>
	<? endif?>
</div>














