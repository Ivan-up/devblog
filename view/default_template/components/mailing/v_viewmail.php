<? extract($object->navParams); ?>
<div class="table-resposive">
	<?=$object->inner_nav?>
	<h2 class="sub-header">Неотправленные письма (<?=$count?>)</h2>
<? if ($count > 0) :?>
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок письма</th>
		<th> Название рассылки</th>
		<th class="hidden-xs  hidden-sm"> Статус письма</th>
		<th colspan="2"> Просмотр</th>
		<th> Отправить</th>
		<th> Удалить</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->pages as $page): ?>
			<tr>
			<? $id=$page['mail_id'] ?>
				<td><?=$i?></td>
				<td><?=$page['subject'] ?></td>				
				<td><?=$page['listname'] . '<br>подписчики: ' . $page['subscr']?></td>				
				<td class="hidden-xs  hidden-sm"><?=($page['status'] == 'TESTED') ? 'Проверено<br>' . $page['email'] : 'Не проверено' ?></td>				
				<td><a href="<?=BASE_URL . MAILING_DIR . $page['listid'] . '/' . $id . '/index.html'?>" target="_blank"> HTML</a></td>
				<td><a href="<?=BASE_URL . MAILING_DIR . $page['listid'] . '/' . $id . '/text.txt'?>" target="_blank"> Text</a></td>
				<td>
				<? if ($object->check_priv('C_Mailing:action_send')) :?>
					<a href="<?=M_Link::ToAdminMailing('send', $id)?>">					
						<?=($page['status'] == 'TESTED') ? 'Разослать' : 'Тестировать<br>' . $page['email'] ?>
					</a>
				<? endif?>
				</td>
				<td>
				<? if ($object->check_priv('C_Mailing:action_deletemail')) :?>
					<a href="<?=M_Link::ToAdminMailing('deletemail', $page['listid'], $id)?>">Удалить</a>
				<? endif;?>
				</td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
<? endif?>
</div>














