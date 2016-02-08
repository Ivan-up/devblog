<div>
	<h2>Юзеры</h2>
	<? foreach ($records as $user): ?>
		<p>
			<?=$user['user_name'] ?>
		</p>
	<? endforeach; ?>
</div>