<h2 class="sub-header">Создание новой галереи</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>

<form method="post" class="form-horizontal">
	<div class="form-group <?if(isset($object->messages['gallery_title'])) echo ' has-error'?>">
		<label class="col-lg-2 control-label" for="name">Название</label>
		<div class="col-lg-10">			
			<input class="form-control" type="text" name="gallery_title" id="name" value="<?=$object->fields['gallery_title']?>">		
		</div>
	</div>
	<div class="form-group <?if(isset($object->messages['gallery_title'])) echo ' has-error'?>">
		<label class="col-lg-2 control-label">Описание</label>
		<div class="col-lg-10">			
			<textarea class="form-control" name="gallery_desc"><?=$object->fields['gallery_desc']?></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<input type="submit" value="Создать галерею" class="btn btn-primary btn">
			<a class="btn btn-primary" href="<?=M_Link::ToAdminGallery('all')?>">Вернуться к списку галерей</a>
		</div>
	</div>
</form>
