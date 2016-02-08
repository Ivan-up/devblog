<? extract($object->navParams); ?>
<h3><?=$object->title?></h3>
<? if(!empty($object->pages)) : ?>
<? 	$i = ($page_num - 1) * $on_page + 1; ?>
<ul class="list-unstyled">
<? foreach ($object->pages as $page) : ?>
	<li><span><?=$i?>.</span><a href="<?=M_Link::ToPage('wvideo', $page['fid'])?>"><?=$page['title']?></a></li>
<? $i++; endforeach?>
</ul>
<?else :?>
<p>Нет загруженных видео записей</p>
<?endif;?>
<?=$object->navBar ?>