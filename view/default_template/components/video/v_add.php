<h1>Добавить видео</h1>

<div class="col-md-6">
	<? if (!empty($object->messages) && is_array($object->messages)) : ?>
	<ul class="list-group">
		<? foreach ($object->messages as $message): ?>
		<li class="list-group-item list-group-item-danger"><?=$message?></li>
		<? endforeach?>
	</ul>
	<? endif?>
	<form class="form-horizontal" method="post" id="fileloaded">
		<div class="form-group <?if(isset($object->messages['title'])) echo ' has-error'?>">
			<label class="control-label" for="title">Заголовок</label>
			<div>			
				<input class="form-control" name="title" id="title" type="text" value="<?=$object->fields['title']?>"/>			
			</div>
		</div>
		<div class="form-group <?if(isset($object->messages['files'])) echo ' has-error'?>">
			<label  class="control-label" for="files">Выберите файл:</label>
			<div>
				<input type="file" id="files" name="files[]" />
				<span class="help-block">(разрешены: 'mp4')</span>
			</div>
		</div>
		<div class="form-group">
			<input type="submit"  id="btnSubmit" class="btn btn-success" value="Загрузить"/>
			<a href="<?=M_Link::ToAdminVideo('all')?>" class="btn btn-default">Вернуться к списку видео</a>
		</div>
	</form>
</div>
<!-- Прогресс загрузки -->
<div class="modal fade" id="status_load" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Прогресс загрузки</h4>
      </div>
      <div class="modal-body">			
				<div class="progress">
					<div class="progress-bar" style="width: 0%;">0</div>
				</div>					
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->