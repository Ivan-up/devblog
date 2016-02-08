<h2> Создание меню</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
	<div class="form-group <?if (isset($object->messages['menu_title'])) echo ' has-error'?>">
		<label for="inputMenuTitle" class="col-lg-2 control-label">Название меню</label>
		<div class="col-lg-10">
			<input type="text" name="menu_title" id="inputMenuTitle" class="form-control" 
				value="<?=$object->fields['menu_title']?>"/>
		</div>
	</div>
	<div class="form-group <?if(isset($object->messages['menu_description'])) echo ' has-error'?>">
		<label for="textArea" class="col-lg-2 control-label">Описание меню</label>
		<div class="col-lg-10">
			<textarea name="menu_description"  id="textArea" class="form-control" 
			rows="3" ><?=$object->fields['menu_description']?></textarea>
		</div>		
	</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Добавить</button>
			<a class="btn btn-primary" href="<?=M_Link::ToAdminMenu('all')?>">Вернуться к списку меню</a>
		</div>
	</div>	
</form>
