<?=$object->inner_nav?>
<h2> Создание письма</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal mail" enctype="multipart/form-data" >
	<div class="form-group <?if (isset($object->messages['listid'])) echo ' has-error'?>">
		<label for="select" class="col-lg-2 control-label">Список рассылки</label>
		<div class="col-lg-10">
			<select name="listid" id="select" class="form-control">
			<?foreach ($object->lists as $key => $list):?>
				<option value="<?=$list['listid']?>" 
					<? if ( ($key == 0 && empty($object->fields['list'])) ||
									$object->fields['listid'] == $list['listid']) echo "selected"?> >
					<?=$list['listname']?>
				</option>
			<?endforeach?>
			</select>
		</div>
	</div>
	<div class="form-group <?if (isset($object->messages['subject'])) echo ' has-error'?>">
		<label for="inputsubject" class="col-lg-2 control-label">Тема</label>
		<div class="col-lg-10">
			<input type="text" name="subject" id="inputsubject" class="form-control" 
				value="<?=$object->fields['subject']?>"/>
		</div>
	</div>
	<div class="form-group <?if (isset($object->messages['txtfile'])) echo ' has-error'?>">
		<label for="mailtxt" class="col-lg-2 control-label">Текстовая версия</label>
		<div class="col-lg-10">
			<input type="file" name="txtfile" id="mailtxt"/>
		</div>
	</div>	
	<div class="form-group <?if (isset($object->messages['htmlfile'])) echo ' has-error'?>">
		<label for="mailhtml" class="col-lg-2 control-label">HTML-версия</label>
		<div class="col-lg-10">
			<input type="file" name="htmlfile" id="mailhtml"/>
		</div>
	</div>
	<div class="form-group">
		<label for="images" class="col-lg-2 control-label">Картинки для HTML-версии</label>
		<div class="col-lg-10">
			<input type="file" name="images[]" id="images" multiple/>
			<span class="help-block">Возможен выбор нескольких картинок(максимум 18)</span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Создать письмо</button>
			<a class="btn btn-danger" target="_blank" href="<?=BASE_URL.MAILING_DIR.'test/email_for_test.rar'?>">
				<i class="glyphicon glyphicon-hand-right"></i> 
				Где взять письмо для теста?
				<i class="glyphicon glyphicon-download-alt"></i>
				<i class="glyphicon glyphicon-hand-left"></i>
			</a>
		</div>
	</div>	
</form>
<div class="preview_mail col-md-12"></div>
