<div class="well">
	<h2>Отправить сообщение</h2>
	<? if (!empty($object->messages) && is_array($object->messages)) : ?>
		<? if (isset($object->messages['success'])) :?>
		<div class="alert alert-success"><?=$object->messages['success']?></div>
		<? else :?>
		<ul class="list-group">
			<? foreach ($object->messages as $message): ?>
			<li class="list-group-item list-group-item-danger"><?=$message?></li>
			<? endforeach?>
		</ul>
		<? endif?>
	<? endif?>
	<form action="" method="post">
	<div class="form-group <?if(isset($object->messages['name'])) echo ' has-error'?>">
		<label>Ваше имя</label>
		<input type="text" class="form-control" name="name" value="<?=$this->fields['name']?>">
	</div>
	<div class="form-group <?if(isset($object->messages['email'])) echo ' has-error'?>">
		<label>Ваш email адрес</label>
		<input type="email" name="email" class="form-control" value="<?=$this->fields['email']?>">
	</div>
	<div class="form-group <?if(isset($object->messages['message'])) echo ' has-error'?>">
		<label>Сообщение</label>
		<textarea class="form-control" name="message"><?=$this->fields['message']?></textarea>
	</div>
	<button type="submit" class="btn btn-default" name="sendEmail">Отправить</button>
</form>
<br><br>

<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d6474.313744596437!2d73.3979914715286!3d54.990360885823094!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sru!4v1444253731618" width="100%" frameborder="0" style="border:0" allowfullscreen></iframe>

</div>	


