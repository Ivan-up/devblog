<ul class="diafilm">	<? foreach($images as $img): ?>	<li>				<img src="<?=BASE_URL.IMG_SMALL_DIR . $img['name']?>" alt="<?=$img['alt']?>"  data-large-src="<?=BASE_URL.IMG_DIR . $img['name']?>" data-description="<?=$img['title']?>"/>		</li>	<? endforeach; ?></ul>