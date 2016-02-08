<h2>Редактирование аудио</h2>

<div class="col-md-6">
	<? if (!empty($object->messages) && is_array($object->messages)) : ?>
	<ul class="list-group">
		<? foreach ($object->messages as $message): ?>
		<li class="list-group-item list-group-item-danger"><?=$message?></li>
		<? endforeach?>
	</ul>
	<? endif?>
	<form class="form-horizontal" method="post">	
		<div class="form-group <?if(isset($object->messages['title'])) echo ' has-error'?>">
			<label class="control-label" for="title">Заголовок</label>			
			<div>
				<input class="form-control" name="title" id="title" type="text" class="input-xxlarge" value="<?=$object->fields['title']?>"/>
			</div>
			<audio src="<?=BASE_URL.AUDIO_DIR.$object->fields['name']?>" controls style="width: 100%">
				Your browser does not support the <code>audio</code> element.
			</audio>
		</div>
		<div class="form-group">
			<input type="submit" name="save" class="btn btn-primary" value="Сохранить изменения"/>
			<? if ($object->check_priv('C_Audio:action_delete')) :?>
			<input type="submit" name="delete" class="btn btn-danger" value="Удалить запись"/>
			<? endif?>
			<a href="<?=M_Link::ToAdminAudio('all')?>" class="btn btn-default">Вернуться к списку аудио</a>
		</div>
	</form>
</div>