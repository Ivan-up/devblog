<? extract($object->navParams); ?>
<div class="table-resposive">
	<h2 class="sub-header">Список комментариев (<?=$count?>)</h2>
	<? if ($count < 1) :?>
	<p>Комментария еще не были сделаны</p>
	<? else:?>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Комментарий</th>
		<th class="hidden-xs hidden-sm"> Автор комментария</th>
		<th> Отправлен</th>
		<th> Просмотреть</th>
		<th> Редактриовать</th>
		<th> Удалить</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->comments as $comment): ?>
			<tr>
			<? $id=$comment['comment_id'] ?>
				<td><?=$i?></td>
				<td><?=$comment['comment_content'] ?></td>				
				<td class="hidden-xs hidden-sm">
					<?=$comment['comment_author'] ?> -
					<?=$comment['comment_author_email'] ?>
				</td>
				<td><?=$comment['dateCreate']?></td>
				<td>
				<a target="_blank" 
				href="<?=M_Link::ToPage('post', $comment['idSubject'])?>#commentItem<?=$id;?>">Просмотреть</a>
				</td>
				<td>
				<? if ($object->check_priv('C_Comments:action_edit')) :?>
					<a href="<?=M_Link::ToAdminComments('edit', $id)?>"> Редактировать</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Comments:action_delete')) :?>
					<a href="<?=M_Link::ToAdminComments('delete', $id)?>" onClick="javascript: return confirm('Вы действительно хотите удалить?')"> Удалить</a>
				<? endif?>
				</td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<? endif?>
</div>














