<? extract($object->navParams); ?><div>	<h2>Список аудио (<?=$count?>)</h2>	<? if ($count < 1): ?>	<p>Нету ни одной аудиозаписи</p>	<? else :?>	<table class="table table-hover table-bordered">		<thead>		<tr>			<th class="numberlist">№</th>			<th> Заголовок</th>			<th class="hidden-xs hidden-sm"> Прослушать</th>			<th> Редактировать</th>			<th> Удалить</th>		</tr>		</thead>		<tbody>			<? 	$i = ($page_num - 1) * $on_page + 1; ?>			<? foreach ($this->audio as $audio): ?>				<tr>				<? $id = $audio['fid'] ?>					<td><?=$i?></td>					<td><?=$audio['title'] ?></td>					<td class="hidden-xs hidden-sm">						<audio src="<?=BASE_URL.AUDIO_DIR.$audio['name']?>" controls width="300px">							Your browser does not support the <code>audio</code> element.						</audio>					</td>					<td>					<? if ($object->check_priv('C_Audio:action_edit')) :?>						<a href="<?=M_Link::ToAdminAudio('edit',$id)?>"> Редактировать</a>					<? endif?>						</td>					<td>					<? if ($object->check_priv('C_Audio:action_delete')) :?>						<a href="<?=M_Link::ToAdminAudio('delete',$id)?>" onClick="javascript: return confirm('Вы действительно хотите удалить?')"> Удалить</a>					<? endif?>					</td>				</tr>			<? $i++; endforeach ?>		</tbody>	</table>	<?=$object->navBar ?>	<? endif?>	<? if ($object->check_priv('C_Audio:action_add')) :?>	<p><a class="btn btn-primary btn" href="<?=M_Link::ToAdminAudio('add')?>">Добавить новое аудио &raquo;</a></p>	<? endif?></div>