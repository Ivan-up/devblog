<div id="usersettings">
	<h2>Галерея: <?=$object->gallery['gallery_title']?></h2>
	<? if ($object->check_priv('C_Gallery:action_editimg')) :?>
	<a href="<?=M_Link::ToAdminGallery('upload', $object->gallery['gallery_id'])?>">Загрузить изображения</a>
	<? endif?>
	<div>
		<input type="button" id="btn_save" value="Сохранить сортировку" class="btn btn-danger">
		<span id="msg_save">Сохранено</span>
	</div>
	<? if(!empty($object->images)):?>
		<ul id="gallery_sortable" class="noicons noshifts list-inline">
		<? foreach($object->images as $img):?>
			<li class="delimg" id_image="<?=$img['fid']?>">
				<? if ($object->check_priv('C_Gallery:action_editimg')) :?>
				<form method="post">
					<input type="submit" class="delete" value="">
					<input type="hidden" name="gallery_id" value="<?=$img['gallery_id']?>">
					<input type="hidden" name="fid" class="delete" value="<?=$img['fid']?>">
				</form>				
				<a href="<?=M_Link::ToAdminGallery('editimg', $img['fid'], $img['gallery_id'])?>">Редактировать</a>
				<? endif?>
				<img class="im" src="<?=BASE_URL . IMG_SMALL_DIR . $img['name']?>">				
			</li>
		<? endforeach ?>
		</ul>
	<? else: ?>
		<p>В галерее нет изображений</p>
	<? endif; ?>
</div>
<div class="clear"></div><br>
<p>Для изменения последовательности показа переместите изображения.</p><br>
<a href="<?=M_Link::ToAdminGallery('all')?> "> Вернуться к списку галерей</a>