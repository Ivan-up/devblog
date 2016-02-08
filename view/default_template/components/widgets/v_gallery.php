<div class="gallery-list clearfix">
<? $i = 0; $n = 2; $max = count($images) - 1?>
<? foreach($images as $key => $img): ?>
	<? if($i == 0):?> <div class="row"> <?endif?>
	<div class="col-lg-6">
		<div class="gallery__item">
			<a href="<?=BASE_URL . IMG_DIR . $img['name']?>" data-lightbox="roadtrip" rel="gallery">
				<img class="img-responsive img-thumbnail" src="<?=BASE_URL . IMG_SMALL_DIR . $img['name']?>"  alt="<?=$img['alt']?>" title="<?=$img['title']?>"/>
			</a>
		</div>
	</div>
	<? ++$i?>
	<? if($i == $n || $key == $max):?> </div> <?$i=0; endif;?>
<? endforeach; ?>
</div>

