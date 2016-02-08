<?=$object->tabs?>
<h2>Редактирование роль: <?=$object->fields['role_name']?></h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
	<input type="hidden" name="role_id" value="<?=$object->fields['role_id']?>"/>
	<div class="form-group <?if (isset($object->messages['role_name'])) echo ' has-error'?>">
		<label for="inputRoleName" class="col-lg-2 control-label">Название роли</label>
		<div class="col-lg-10">
			<input type="text" name="role_name" id="inputRoleName" class="form-control" 
				value="<?=$object->fields['role_name']?>"/>
		</div>
	</div>	
	<div class="form-group <?if(isset($object->messages['role_description'])) echo ' has-error'?>">
		<label for="textArea" class="col-lg-2 control-label">Описание меню</label>
		<div class="col-lg-10">
			<textarea name="role_description"  id="textArea" class="form-control" 
			rows="3" ><?=$object->fields['role_description']?></textarea>
		</div>		
	</div>	
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-default" type="submit">Сохранить</button>
		</div>
	</div>	
</form>
