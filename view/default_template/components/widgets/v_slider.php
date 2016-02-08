<div id="carousel_<?=$images[0]['gallery_id']?>" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
	<? foreach($images as $key => $img): ?>
		<div class="item <?if($key == 0) echo'active'?>">
			<img src="<?=BASE_URL . IMG_DIR . $img['name']?>"  alt="<?=$img['alt']?>" />
		</div>
	<? endforeach; ?>
	</div>
	<a class="left carousel-control" href="#carousel_<?=$images[0]['gallery_id']?>" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#carousel_<?=$images[0]['gallery_id']?>" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div><!-- /.carousel -->