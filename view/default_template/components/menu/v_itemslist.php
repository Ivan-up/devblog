<?
function print_tree($map, $shift = 0) { ?>
	<? if (!empty($map)) : ?>
		<? foreach ($map as $item) : ?>
			<tr>				
				<td>
					<? 	for($i = 0; $i < $shift; $i++) echo '&nbsp;'?>
					<a href="<?=$item['link_path']?>"><?=$item['link_title']?></a>
				</td>
				<td>
					<select name="weight_<?=$item['mlid']?>" class="form-control">
						<? for($i = -50; $i <= 50; $i++) : ?>
							<? $is_selected = ""; 
								 if($i == $item['weight']) 
									$is_selected = 'selected';
								 elseif ($i == 0 && $item['weight'] === '')
									$is_selected = 'selected';?>
						<option value="<?=$i?>" <?=$is_selected?>><?=$i?></option>
						<? endfor?>
					</select>
				</td>
				<td>
				<? if (M_Users::Instance()->ActiveCan('C_Menu:action_editlink')) :?>
					<a href="<?=M_Link::ToAdminMenu('editlink', $item['mlid'])?>">Изменить</a>
				<? endif?>
				</td>
				<td>
				<? if (M_Users::Instance()->ActiveCan('C_Menu:action_deletelink')) :?>
					<a href="<?=M_Link::ToAdminMenu('deletelink', $item['menu_id'], $item['mlid'])?>" >Удалить</a>
				<? endif?>
				</td>
			</tr>
			<? print_tree($item['children'], $shift + 5)?>
		<? endforeach?>
	<?endif?>
<?}?>


<div class="table-resposive">
	<h2 class="sub-header">Список ссылок в меню "<?=$object->fields['menu_title']?>" </h2>	
	<form method="post" action="" class="form-horizontal" >
		<? if (empty($object->fields['children'])) :?>
		<p>Меню пустое</p>
		<? else :?>
		<table class="table table-striped">
		<thead>
		<tr>
			<th> Ccылка меню</th>
			<th> Вес</th>
			<th> Изменить</th>
			<th> Удалить</th>
		</tr>
		</thead>
		<tbody>
		<?print_tree($object->fields['children'])?>
		</tbody>
		</table>
		<? endif;?>
		<div class="form-group">
			<div class="col-lg-10">
				<div class="btn-group" role="group">
					<? if (!empty($object->fields['children'])) :?>
					<button class="btn btn-default" type="submit">Сохранить изменения</button>
					<? endif?>
					<? if (M_Users::Instance()->ActiveCan('C_Menu:action_addlink')) :?>
					<a class="btn btn-default" href="<?=M_Link::ToAdminMenu('addlink', $object->fields['menu_id'])?>">Добавить ссылку</a>
					<? endif?>
					<a class="btn btn-default" href="<?=M_Link::ToAdminMenu('all')?>">Вернуться к списку меню</a>
				</div>
			</div>
		</div>	
	</form>
	
</div>














