<? extract($object->navParams); ?>
<div class="table-resposive">
	<?=$object->inner_nav?>
	<h2 class="sub-header"><?=$this->maillists['listname']?> — подписчики (<?=$count?>)</h2>
<? if ($count > 0) :?>	
	<table class="table table-striped">
	<thead>
	<tr>
		<th class="numberlist">№</th>
		<th> Адрес подписчика</th>
		<th> Название рассылки</th>
		<th> Отменить подписку</th>
	</tr>
	</thead>
	<tbody>
		<? 	$i = ($page_num - 1) * $on_page + 1; ?>
		<? foreach ($object->pages as $page): ?>
			<tr>
				<td><?=$i?></td>
				<td><?=$page['email'] ?></td>				
				<td><?=$page['listname'] ?></td>				
				<td>
					<form method="post" action="">
						<input type="hidden" name="email" value="<?=$page['email']?>"/>
						<input type="hidden" name="listid" value="<?=$page['listid']?>"/>
						<input class="btn btn-primary" type="submit" name="unsubscr" value="Отменить">
					</form>
				</td>				
			</tr>
		<? $i++; endforeach ?>
	</tbody>
	</table>
	<?=$object->navBar ?>
<? endif?>
	<form method="post" action="" class="form-horizontal" >
		<fieldset class="col-lg-5">
			<legend>Не подписанные пользователи</legend>
			<div class="form-group">
				<div>
					<select name="emails[]" id="email_user" class="form-control" multiple="multiple" size="5">
					<? foreach ($object->unsubscr_users as $user) :?>
						<option value="<?=$user['login']?>">
							<?=$user['login']?>
						</option>
					<? endforeach?>
					</select>
				</div>			
			</div>
			<div class="form-group">
				<input class="btn btn-primary" name="subscr" type="submit" value="Подписать">
			</div>
		</fieldset>
	</form>
</div>














