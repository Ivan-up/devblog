<? extract($object->navParams); ?>
<h3><?=$object->title?></h3>
<? if(!empty($object->pages)) : ?>
<? 	$i = ($page_num - 1) * $on_page + 1; ?>
<ul class="list-unstyled">
<? foreach ($object->pages as $page) : ?>
	<li><span><?=$i?>.</span><a href="<?=M_Link::ToPage('gallery', $page['gallery_id'])?>"><?=$page['gallery_title']?></a></li>
<? $i++; endforeach?>
</ul>
<?else :?>
<p>Нет ни одной галереи</p>
<?endif;?>
<?=$object->navBar ?>