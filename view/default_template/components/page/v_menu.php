<? 
if (!function_exists('print_tree')) :
	function print_tree($map, $url, $shift = 0) { ?>
			<? if (!empty($map)) : ?>
			<ul class="list-unstyled">
				<? foreach ($map as $item) : ?>
					<? $link = rtrim($item['link_path'], '/')?>
					<? if (strpos($link,'http') !== 0) :?> 
					<? $link = BASE_URL.$link; $target = '_self' ?>
					<? else :?>
					<? $target = '_blank'?>
					<? endif?>
					<li <?if($url == $link ):?> class="active"<?endif?> >
						<a href="<?=$link?>" target="<?=$target?>">
							<?=str_repeat('&nbsp;', $shift)?><?=$item['link_title']?>
						</a>
							<? print_tree($item['children'], $url, 0)?>
					</li>
				<? endforeach?>
			</ul>
			<?endif?>
	<? }?>
<?endif;?>

<nav class="sidebar-module">
<h4><?=$menu['menu_title']?></h4>
<div class = "pull-down-menu">
<?print_tree($menu['children'], $pageUrl)?>
</div>
</nav>











