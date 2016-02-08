<div>
	<h2>Результаты поиска</h2>
	<? if(count($templates) > 0): ?>
		<? foreach ($templates as $template): ?>
			<?=$template?>
		<? endforeach; ?>
	<? else: ?>
		Ничего не найдено
	<? endif; ?>
</div>