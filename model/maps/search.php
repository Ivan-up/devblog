<?php

	return array(
		'posts' => array(
						'fields' => array('post_title', 'post_content'),
						'where' => "post_status = 'publish'",
						'template' => 'search/v_posts.php'
						)
	);
