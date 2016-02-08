<article>	
	<h4><?=$video['title'] ?></h4>	
	<video class="video-js vjs-default-skin" controls preload="metadata" data-setup="{}">
		<source src="<?=BASE_URL.VIDEO_DIR.$video['name']?>" type='video/mp4'>
		<p class="vjs-no-js">
			To view this video please enable JavaScript, and consider upgrading to a web browser that
			<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
		</p>
	</video>	
</article>
