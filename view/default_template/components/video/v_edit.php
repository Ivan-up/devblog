<h2>Редактирование видео</h2>
<div class="row">
	<div class="col-md-6">
		<video class="video-js" controls preload="auto" data-setup="{}">
			<source src="<?=BASE_URL.VIDEO_DIR.$object->fields['name']?>" type='video/mp4'>
			<p class="vjs-no-js">
				To view this video please enable JavaScript, and consider upgrading to a web browser that
				<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
			</p>
		</video>
	</div>
</div>
<div class="row">
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
			</div>
			<div class="form-group">
				<input type="submit" name="save" class="btn btn-primary" value="Сохранить изменения"/>
				<? if ($object->check_priv('C_Video:action_delete')) :?>
				<input type="submit" name="delete" class="btn btn-danger" value="Удалить видео"/>
				<? endif?>
				<a href="<?=M_Link::ToAdminVideo('all')?>" class="btn btn-default">Вернуться к списку видео</a>
			</div>
		</form>
	</div>
</div>