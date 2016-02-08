<ul class="breadcrumb well">
	<li><a href="<?=M_Link::ToPage()?>">Главная</a></li>
	<? foreach ($breadCrambs as $item) :?>
		<?if (!isset($item['active'])) : ?>	
		<li><a href="<?=$item['link_path']?>"><?=$item['link_title']?></a></li>
		<?else :?>
		<li class="active"><?=$item['link_title']?></li>
		<?endif?>
	<? endforeach?>		
</ul>