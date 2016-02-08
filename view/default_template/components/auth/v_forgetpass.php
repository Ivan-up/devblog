<? if ($has_changed == false) :?>
	<? if (!empty($messages) && is_array($messages)) : ?>
	<ul class="list-group">
		<? foreach ($messages as $message): ?>
		<li class="list-group-item list-group-item-danger"><?=$message?></li>
		<? endforeach?>
	</ul>
	<? endif?>
	<form action="" method="post" class="form-signin">
		<h2 class="form-signin-heading">Востановление пароля</h2>
		<label for="inputEmail1" class="sr-only">Введите Email</label>
		<input type="text" name="login" id="inputEmail1" class="form-control" 
			required="" placeholder="Email address">
		<button class="btn btn-sm btn-primary btn-block" name = "forgetpass" type="submit">Востановить</button>
	</form>

<? else :?>
	<div class="well well-sm">
		<p>Пароль был изменен. Письмо с новым паролем отправлено Вам на почту</p>
		<p><a href="<?=M_Link::ToAuth('login')?>">Войти в систему</a></p>
	</div>
<? endif?>