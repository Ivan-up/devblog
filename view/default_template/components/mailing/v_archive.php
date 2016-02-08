<? extract($object->navParams); ?>
<div class="table-resposive">
	<?=$object->inner_nav?>
	<h2 class="sub-header"><?=$this->maillists['listname']?> — отправленные письма (<?=$count?>)</h2>
<? if ($count > 0) :?>		
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Заголовок письма</th>
		<th> Название рассылки</th>
		<th colspan="2"> Просмотр</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->pages as $page): ?>
			<tr>
			<? $id=$page['mail_id'] ?>
				<td><?=$i?></td>
				<td><?=$page['subject'] ?></td>				
				<td><?=$page['listname'] ?></td>				
				<td><a href="<?=BASE_URL . MAILING_DIR . $page['listid'] . '/' . $page['mail_id'] . '/index.html'?>" target="_blank"> HTML</a></td>
				<td><a href="<?=BASE_URL . MAILING_DIR . $page['listid'] . '/' . $page['mail_id'] . '/text.txt'?>" target="_blank"> Text</a></td>
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
<? endif?>
</div>














