<h2>Редактирование комментария</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
	<input type="hidden" name="comment_id" value="<?=$object->fields['comment_id']?>"/>
	
	<div class="form-group <?if (isset($object->messages['comment_author'])) echo ' has-error'?>">
		<label for="inputAuhor" class="col-lg-2 control-label">Имя автора</label>
		<div class="col-lg-10">
			<input type="text" name="comment_author" id="inputAuhor" class="form-control" 
				value="<?=$object->fields['comment_author']?>"/>
		</div>
	</div>
	<div class="form-group <?if (isset($object->messages['comment_author_email'])) echo ' has-error'?>">
		<label for="inputAuhorEmail" class="col-lg-2 control-label">Email автора</label>
		<div class="col-lg-10">
			<input type="text" name="comment_author_email" id="inputAuhorEmail" class="form-control" 
				value="<?=$object->fields['comment_author_email']?>"/>
		</div>
	</div>	
	<div class="form-group <?if(isset($object->messages['comment_content'])) echo ' has-error'?>">
		<label for="textArea" class="col-lg-2 control-label">Текст комментария</label>
		<div class="col-lg-10">
			<textarea name="comment_content"  id="textArea" class="form-control" 
			rows="3" ><?=$object->fields['comment_content']?></textarea>
		</div>		
	</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Сохранить</button>
			<a class="btn btn-default" target="_blank" 	href="<?=M_Link::ToPage('post', $object->fields['idSubject'])?>#commentItem<?=$object->fields['comment_id'];?>">
				Просмотреть
			</a>
			<a class="btn btn-default" href="<?=M_Link::ToAdminComments('all')?>">Вернуться к списку комментариев</a>
		</div>
	</div>	
</form>


