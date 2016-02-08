<?/*
Шаблон авторизации пользователя
===============================
$login - логин пользователя

*/
?>
<? if (empty($user)) :?>
<div class="well">
	<?if ($message) :?>
	<div class="alert alert-danger"><?=$message?></div>
	<? endif?>
	<form action="" method="post" class="form-signin">
		<h2 class="form-signin-heading">Авторизация</h2>
		<label for="inputEmail1" class="sr-only">Email</label>
		<input type="text" name="login" id="inputEmail1" class="form-control" 
			required="" placeholder="Email address" value="<?=$email?>">
		<label class="sr-only" for="inputPassword1">Пароль</label>
		<input id="inputPassword1" class="form-control" type="password" 
			name="password" required="" placeholder="Пароль">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="remember" value="">
				Запомнить
			</label>
			<a href="<?=M_Link::ToAuth('registr');?>">Зарегистрироваться</a><br>
			<a href="<?=M_Link::ToAuth('forgetpass');?>">Забыл пароль</a>
		</div>
		<button class="btn btn-sm btn-primary btn-block" type="submit">Войти</button>
	</form>
</div>

<?else :?>
<div class="well well-sm">
	<p>Вы вошли как <?=$user['user_name']?>, <a href="<?=M_Link::ToAuth('logout')?>">Выйти</a></p>
	<p><a href="<?=M_Link::ToAuth('account')?>">Настройки профиля</a></p>
</div>
<?endif?>