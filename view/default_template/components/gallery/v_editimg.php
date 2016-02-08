<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h2>Редактирование данных изображения: 
			<a href="<?=M_Link::ToAdminGallery('images', $object->gallery['gallery_id'])?>"><?=$object->gallery['gallery_title']?></a> 
			-> <?=$object->fields['name']?></h2><br>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4 col-sm-push-8">
			<a href="<?=BASE_URL.IMG_DIR.$object->fields['name']?>" target="_blank" data-lightbox="<? $a = explode('.', $object->fields['name']); echo "$a[0]"; ?>">
			<img class="img-polaroid" src="<?=BASE_URL.IMG_SMALL_DIR.$object->fields['name']?>"></a>
		</div>
		<div class="col-sm-8 col-sm-pull-4">
			<? if (!empty($object->messages) && is_array($object->messages)) : ?>
			<ul class="list-group">
				<? foreach ($object->messages as $message): ?>
				<li class="list-group-item list-group-item-danger"><?=$message?></li>
				<? endforeach?>
			</ul>
			<? endif?>

			<form method="post" class="form-horizontal">
				<input type="hidden" name="fid" value="<?=$object->fields['fid']?>">
				<div class="form-group <?if(isset($object->messages['title'])) echo ' has-error'?>">
					<label class="col-md-2 control-label" for="stitle">Заголовок изображения</label>
					<div class="col-md-10">			
						<input class="form-control" type="text" name="title" id="stitle" value="<?=$object->fields['title']?>">			
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label" for="sk">Альтернативный текст</label>
					<div class="col-md-10">			
						<input class="form-control" type="text" name="alt" id="sk" value="<?=$object->fields['alt']?>">			
					</div>
				</div>
				<div class="form-group <?if(isset($object->messages['alt'])) echo ' has-error'?>">
					<div class="col-md-10 col-md-offset-2">			
						<input type="submit" value="Сохранить изменения" class="btn btn-primary">
						<a class="btn btn-default" href="<?=M_Link::ToAdminGallery('images', $object->gallery['gallery_id'])?>">Вернуться в галерею</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
