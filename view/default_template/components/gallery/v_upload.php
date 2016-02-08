<div class="col-md-6">
	<h2>
		Загрузка изображений 
		<a href="<?=M_Link::ToAdminGallery('images', $object->gallery['gallery_id']);?>">
			"<?=$object->gallery['gallery_title']?>"
		</a> 		
	</h2>
	<div class="btn-group">
		<a class="btn btn-default" href="<?=M_Link::ToAdminGallery('images', $object->gallery['gallery_id'])?>">
				Перейти к списку картинок <?=$object->gallery['gallery_title']?>
		</a>
		<a class="btn btn-default" href="<?=M_Link::ToAdminGallery('all')?>">Перейти к списку галерей</a>
	</div>
	<div class="upload-img">
		<form class="upload-img__form" action="">
			<div class="upload-img__drop-files">
				<div class="upload-img__drop-header">
					<p><i class="glyphicon glyphicon-hand-right"></i> Перетащи картинки сюда <i class="glyphicon glyphicon-hand-left"></i></p>
					<p class="small"><i class="glyphicon glyphicon-download"></i> или воспользуйтесь кнопкой <i class="glyphicon glyphicon-download"></i></p>			
					<div class="upload-img__btn-files">
						<label class ="btn btn-primary"> Выберите файл
							<input style="display: none" type="file" name="images" class="upload-img__btn-file" multiple>
						</label>
						<input type="hidden" name="gallery_id" value="<?=$object->gallery['gallery_id']?>">
					</div>		
				</div>
				<!-- Область предпросмотра -->
				<ul class="upload-img__preview"></ul>				
			</div>
				
			<!-- Кнопки загрузить и удалить, а также количество файлов -->
			<div class="upload_img__btn-upload">
				<p class="upload_img__files-info">Всего файлов для загрузки: <span>0</span></p>
				<button type="submit" class="btn btn-primary upload_img__submit">Загрузить</button>
				<button type="reset" class="btn btn-danger upload_img__del-all">Удалить все</button>
			</div>
			
		</form>
		
		<!-- Прогресс загрузки -->
		<div class="upload-img__loading">
			<div class="upload-img__loading-file"><span></span></div>
			<div class="progress">
				<div class="progress-bar" style="width: 0%;">0	</div>
			</div>						
		</div>	
		
		<!-- Список загруженных файлов -->
		<div class="upload-img__file-name-holder">
			<h4>Загруженные файлы</h4>
			<ul class="upload-img__uploaded-files">
			</ul>
		</div>		
		
		<!-- Шаблон вывода предпросмотра изображений -->
		<div id="image-template" style="display: none;">	
			<div class="upload-img__img-wrap">
				<img class="img-thumbnail" src="{{image}}" alt="{{name}}" title="{{name}}">
			</div>
			<a href="#" class="upload-img__del-link" title="Удалить">
				<i class="glyphicon glyphicon-remove">Удалить</i>
			</a>
		</div>
	</div>
</div>