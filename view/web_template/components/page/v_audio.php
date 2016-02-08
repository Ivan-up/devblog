<? extract($object->navParams); ?>
<h3><?=$object->title?></h3>
<? if(!empty($object->pages)) : ?>
<? 	$i = ($page_num - 1) * $on_page + 1; ?>
<ul class="list-unstyled">
<? foreach ($object->pages as $page) : ?>
	<li>
		<div class="panel panel-warning">
			<div class="panel-heading"><span><?=$i?>.</span><?=$page['title']?></div>
			<div class="panel-body"><audio src="<?=BASE_URL.AUDIO_DIR.$page['name']?>" controls></div>
		</div>
	</li>
<? $i++; endforeach?>
</ul>
<?else :?>
<p>Нет загруженных аудио записей</p>
<?endif;?>
<?=$object->navBar ?>