<?=$object->inner_nav?>
<h2> Редактирование листа рассылки</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
	<input type="hidden" name="listid" value="<?=$object->fields['listid']?>">
	<div class="form-group <?if (isset($object->messages['listname'])) echo ' has-error'?>">
		<label for="inputlistname" class="col-lg-2 control-label">Заголовок</label>
		<div class="col-lg-10">
			<input type="text" name="listname" id="inputlistname" class="form-control" 
				value="<?=$object->fields['listname']?>"/>
		</div>
	</div>
	<div class="form-group <?if(isset($object->messages['blurb'])) echo ' has-error'?>">
		<label for="blurbtextArea" class="col-lg-2 control-label">Описание</label>
		<div class="col-lg-10">
			<textarea name="blurb"  id="blurbtextArea" class="form-control" 
			rows="3" ><?=$object->fields['blurb']?></textarea>
		</div>		
	</div>
	<div class="form-group">
			<label class="col-lg-2 control-label">Состояние</label>
			<div class="col-lg-10">
				<div class="radio">
					<label>
						<input type="radio" name="is_show" value="1"							
							<?if ($object->fields['is_show'] == '1') echo "checked"?> />
						Включен
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="is_show" value="0"							
							<? if (empty($object->fields['is_show'])) echo "checked"?> />
						Отключен
					</label>
				</div>
			</div>
		</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Cохранить изменения</button>
			<a class="btn btn-primary" href="<?=M_Link::ToAdminMailing('all')?>">Вернуться к списку рассылок</a>
		</div>
	</div>	
</form>
