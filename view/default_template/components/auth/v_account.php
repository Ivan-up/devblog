<h2>Настройки профиля</h2>

<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<? if (!empty($object->success)):?>
<div class="alert alert-success" role="alert">
	<span><?=$object->success?></span>
</div>
<? endif?>
<div class="panel panel-info">
	<div class="panel-body">
		<form method="post" action="" class="form-horizontal" >
			<fieldset>
				<legend class="text-center">Смена пароля</legend>
				<div class="form-group <?if (isset($object->messages['oldpassword'])) echo ' has-error'?>">
					<label for="inputPassword" class="col-sm-12">Текущий пароль</label>
					<div class="col-sm-12">
						<input type="password" name="password" id="inputPassword" class="form-control"/>
					</div>
				</div>
				<div class="form-group <?if (isset($object->messages['password'])) echo ' has-error'?>">
					<label for="inputNewPassword" class="col-sm-12">Новый пароль</label>
					<div class="col-sm-12">
						<input type="password" name="newpassword" id="inputNewPassword" class="form-control"/>
						<span class="help-block">Пароль разрешены английсие буквы и цифры, длина от 6 до 12 символов</span>
					</div>
				</div>
				<div class="form-group <?if (isset($object->messages['password'])) echo ' has-error'?>">
					<label for="inputNewPassword2" class="col-sm-12">Подтвердите пароль</label>
					<div class="col-sm-12">
						<input type="password" name="newpassword2" id="inputNewPassword2" class="form-control"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<button class="btn btn-primary " type="submit" name="changepass">Сменить пароль</button>
					</div>
				</div>
			</fieldset>	
		</form>
	</div>
</div>

<div class="panel panel-info">
	<div class="panel-body">
		<form method="post" action="" class="form-horizontal" >
			<fieldset>
				<legend class="text-center">Параметры профиля</legend>
				<div class="form-group <?if(isset($object->messages['user_name'])) echo ' has-error'?>">
					<label for="inputUserName" class="col-sm-12">Имя</label>
					<div class="col-sm-12">
						<input type="text" name="user_name" id="inputUserName" class="form-control" 
							value="<?=$object->fields['user_name']?>"/>
					</div>
				</div>
				<div class="form-group <?if(isset($object->messages['mimemail'])) echo ' has-error'?>">
					<label for="mimemail" class="col-sm-12">Тип рассылки</label>
					<div class="col-sm-12">
						<select name="mimemail" id="mimemail" class="form-control">
							<option value="H" <? if ($object->fields['mimemail'] == 'H') echo "selected"?> >HTML-версия</option>
							<option value="T" <? if ($object->fields['mimemail'] == 'T') echo "selected"?>>Текстовая-версия</option>
						</select>
					</div>
				</div>	
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<button class="btn btn-primary" name="profile" type="submit">Сохранить</button>
						<a href="<?=M_Link::ToPage('subscriptions');?>" class="btn btn-default" target="_blank">Cписок рассылок</a>
					</div>
				</div>
			</fieldset>	
		</form>
	</div>
</div>
