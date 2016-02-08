<div class="comments-area">
	<? if (!isset($object->fields['comment_id'])) : ?>
	<div class="respond">
		<? if (!empty($object->messages) && is_array($object->messages)) : ?>
		<ul class="list-group">
			<? foreach ($object->messages as $message): ?>
			<li class="list-group-item list-group-item-danger"><?=$message?></li>
			<? endforeach?>
		</ul>
		<? endif?>
		<form class="form-horizontal" method="post">
			<fieldset>
				<legend>Добавить комментарий</legend>
				<? if ($object->isLogged == false):?>
				<div class="form-group">
					<label for="inputName" class="col-lg-2 control-label">Имя</label>
					<div class="col-lg-10">
						<input class="form-control" id="inputName" name="comment_author" 
							placeholder="Имя" type="text" value="<?=$object->fields['comment_author']?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail" class="col-lg-2 control-label">Email</label>
					<div class="col-lg-10">
						<input class="form-control" id="inputEmail" name="comment_author_email" 
							placeholder="Email" type="text" value="<?=$object->fields['comment_author_email']?>">
						<span class="help-block">При выводе сообщения Ваш email не будет отображаться.</span>
					</div>
				</div>
				<? endif?>
				<div class="form-group">
					<label for="textArea" class="col-lg-2 control-label">Комментарий</label>
					<div class="col-lg-12">
						<textarea class="form-control" name="comment_content" rows="3" id="textArea"><?=$object->fields['comment_content']?></textarea>						
					</div>
				</div>								
			
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<button type="submit" name="comment_btn" class="btn btn-primary">Отправить</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<?endif;?>
	
<?php

if (isset($this->comments) && is_array($this->comments)) :
		$url = M_Link::ToPage('post',$object->post_id);          
		$comments = $object->comments; 
		//var_dump($comments);		
?>

	<? function form_replay($comment, $object) { ?>
	<? 	ob_start()?>
	<div class="well well-sm">
		<? if (!empty($object->messages) && is_array($object->messages)) : ?>
		<ul class="list-group">
			<? foreach ($object->messages as $message): ?>
			<li class="list-group-item list-group-item-danger"><?=$message?></li>
			<? endforeach?>
		</ul>
		<? endif?>
		<form class="form-horizontal" method="post">
			<fieldset>
				<legend>Ответ</legend>
				<input type="hidden" name="comment_id" value="<?=$comment['comment_id']?>">
				<? if ($object->isLogged == false):?>
				<div class="form-group">
					<label for="inputName" class="col-lg-2 control-label">Имя</label>
					<div class="col-lg-10">
						<input class="form-control" id="inputName" name="comment_author" 
							placeholder="Имя" type="text" value="<?=$object->fields['comment_author']?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail" class="col-lg-2 control-label">Email</label>
					<div class="col-lg-10">
						<input class="form-control" id="inputEmail" name="comment_author_email" 
							placeholder="Email" type="text" value="<?=$object->fields['comment_author_email']?>">
						<span class="help-block">При выводе сообщения Ваш email не будет отображаться.</span>
					</div>
				</div>
				<? endif?>
				<div class="form-group">
					<label for="textArea" class="col-lg-2 control-label">Комментарий</label>
					<div class="col-lg-12">
						<textarea class="form-control" name="comment_content" rows="3" id="textArea"><?=$object->fields['comment_content']?></textarea>
					</div>
				</div>								
			
				<div class="form-group">
					<div class="col-lg-10 col-lg-offset-2">
						<button type="submit" name="comment_btn" class="btn btn-primary">Ответить</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<? echo ob_get_clean();} ?>


	<? for($i = 0, $c = count($comments); $i < $c; $i++) : ?>
	<?  $date = M_Helpers::strf_time('%d-%m-%Y, %a, %H:%M:%S', strtotime($comments[$i]['dateCreate'])) ?>
	
	<? if ($comments[$i]['level'] == 0) :?>
	
		<div id="commentItem<?=$comments[$i]['comment_id'];?>" class="comment">
			<div class="attribution">
				<p class="commenter-name"><?=$comments[$i]['comment_author']?></p>
				<p class="comment-time"><?=$date?></p>
			</div>
			<div class="comment-text">
				<p><?=$comments[$i]['comment_content']?></p>
			</div>
			
			<? $cid = $comments[$i]['comment_id'];
				 if (!isset($_GET['replay']) || $_GET['replay'] !== $cid) : 
			?>			
			<div class="btn-group reply">				
				<a href="<?=$url . "/" . $cid?>#commentItem<?=$cid;?>" class="btn btn-default">Ответить</a>
			</div>
			<? else :?>
			<?form_replay($comments[$i], $object)?>
			<? endif;?>
			
		</div>
		
	<?continue; endif;?>
		
		<div id="commentItem<?=$comments[$i]['comment_id'];?>" class="indented">
			<div class="attribution">
				<p class="commenter-name"><?=$comments[$i]['comment_author']?></p>
				<p class="comment-time"><?=$date?></p>
			</div>
			<div class="comment-text">
				<p><?=$comments[$i]['comment_content']?></p>
			</div>
			
			<? $cid = $comments[$i]['comment_id'];
				 if (!isset($_GET['replay']) || $_GET['replay'] !== $cid) : 
			?>		
			<div class="btn-group reply">
				<a href="<?=$url . "/" . $cid?>#commentItem<?=$cid;?>" class="btn btn-default">Ответить</a>
			</div>
			<? else :?>
			<?form_replay($comments[$i], $object)?>
			<? endif;?>
			
		<?if (!isset($comments[$i + 1]['level']) || 
						$comments[$i + 1]['level'] < $comments[$i]['level']) :
				 echo str_repeat("</div>\n", $comments[$i]['level']);
			endif;
		?>
			
	<?endfor?>		

<?endif?>
</div>
