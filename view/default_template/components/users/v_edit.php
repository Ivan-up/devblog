<?=$object->tabs?>
<h2>Редактирование данных пользователя: <?=$object->fields['login']?></h2>

<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>

<form method="post" action="" class="form-horizontal" >
	<input type="hidden" name="user_id" value="<?=$object->fields['user_id']?>"/>
	<div class="form-group <?if (isset($object->messages['login'])) echo ' has-error'?>">
		<label for="inputLogin" class="col-lg-2 control-label">Логин</label>
		<div class="col-lg-10">
			<input type="text" name="login" id="inputLogin" class="form-control" 
				value="<?=$object->fields['login']?>"/>
		</div>
	</div>
	<div class="form-group <?if (isset($object->messages['password'])) echo ' has-error'?>">
		<label for="inputPassword" class="col-lg-2 control-label">Пароль</label>
		<div class="col-lg-10">
			<input type="password" name="password" id="inputPassword" class="form-control"/>
			<span class="help-block">Пароль оставьте пустым, если не хотите вносить изменения</span>
		</div>
	</div>
	<div class="form-group <?if (isset($object->messages['role_id'])) echo ' has-error'?>">
		<label for="selectRole" class="col-lg-2 control-label">Роль</label>
		<div class="col-lg-10">
			<select name="role_id" id="selectRole" class="form-control">
			<? foreach ($object->roles as $key => $role) :?>
				<option value="<?=$role['role_id']?>" 
					<?php if($role['role_id'] == $object->fields['role_id']) echo 'selected';?>>
					<?=$role['role_name'] . ' - ' . $role['role_description']?>
				</option>
			<? endforeach?>
			</select>
		</div>
	</div>
	<div class="form-group <?if(isset($object->messages['user_name'])) echo ' has-error'?>">
		<label for="inputUserName" class="col-lg-2 control-label">Имя</label>
		<div class="col-lg-10">
			<input type="text" name="user_name" id="inputUserName" class="form-control" 
				value="<?=$object->fields['user_name']?>"/>
		</div>
	</div>
	<div class="form-group <?if(isset($object->messages['mimemail'])) echo ' has-error'?>">
		<label for="mimemail" class="col-lg-2 control-label">Тип рассылки</label>
		<div class="col-lg-10">
			<select name="mimemail" id="mimemail" class="form-control">
				<option value="H" <? if ($object->fields['mimemail'] == 'H') echo "selected"?> >HTML-версия</option>
				<option value="T" <? if ($object->fields['mimemail'] == 'T') echo "selected"?>>Текстовая-версия</option>
			</select>
		</div>
	</div>	
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Сохранить</button>
		</div>
	</div>	
</form>
