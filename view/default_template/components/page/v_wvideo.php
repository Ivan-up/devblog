<article>	
	<h2><?=$object->page['title'] ?></h2>	
	<video class="video-js vjs-default-skin" controls preload="auto" data-setup="{}">
		<source src="<?=BASE_URL.VIDEO_DIR.$object->page['name']?>" type='video/mp4'>
		<p class="vjs-no-js">
			To view this video please enable JavaScript, and consider upgrading to a web browser that
			<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
		</p>
	</video>	
</article>

