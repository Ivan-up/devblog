<? extract($object->navParams); ?>
<div class="table-resposive">
	<h2 class="sub-header">Все галерии (<?=$count?>)</h2>
	<? if ($count < 1): ?>
	<p>Нету ни одной галерии</p>
	<? else :?>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок</th>
		<th> Редактировать</th>
		<th> Удалить</th>
		<th> Список картинок</th>
		<th class="hidden-xs"> Добавить картинку</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->galleries as $gal): ?>
			<tr>
			<? $id=$gal['gallery_id'] ?>
				<td><?=$i?></td>
				<td><?=$gal['gallery_title'] ?></td>				
				<td>
				<? if ($object->check_priv('C_Gallery:action_edit')) :?>
					<a href="<?=M_Link::ToAdminGallery('edit', $id)?>"> Редактировать</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Gallery:action_delete')) :?>
					<a href="<?=M_Link::ToAdminGallery('delete', $id)?>"> Удалить</a>
				<? endif?>	
				</td>
				<td>
				<? if ($object->check_priv('C_Gallery:action_images')) :?>
					<a href="<?=M_Link::ToAdminGallery('images', $id)?>">Список картинок</a>
				<? endif?>
				</td>
				<td class="hidden-xs">
				<? if ($object->check_priv('C_Gallery:action_upload')) :?>
					<a href="<?=M_Link::ToAdminGallery('upload', $id)?>">Добавить картинку</a>
				<? endif?>
				</td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
	<? endif?>
	<p>
		<? if ($object->check_priv('C_Gallery:action_add')) :?>
		<a class="btn btn-primary btn" href="<?=M_Link::ToAdminGallery('add')?>">Добавить галерею&raquo;</a>
		<? endif?>
	</p>
</div>














