<?php 
		
	function print_tree($map, $obj,  $shift = 0)
	{
		if(!empty($map))
		{
			foreach($map as $section)
			{
				if ($section['mlid'] == $obj['mlid']) continue;
				$parent_v = $section['menu_id'].':'.$section['mlid'];
				?>
				<option value="<?=$parent_v?>"
				<? if ($parent_v == $obj['parent']) echo 'selected'?> >
				<?=str_repeat("&nbsp;", $shift)?>
				<?=$section['link_title']?>
				</option>
				<? print_tree($section['children'], $obj, $shift + 5); 
			}
		}
	}
	
?>

<h2 class="sub-header">Редактирование</h2>
<? if(!empty($object->messageSuccess)) :?>
<div class="alert alert-success post-saved" role="alert"><?=$object->messageSuccess?></div>
<? endif?>

<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>


<form method="post" action="" class="form-horizontal" >
	<fieldset class="col-lg-7">
		<input type="hidden" name="post_id" value="<?=$object->fields['post_id']?>">
		<div class="form-group">
			<label for="inputDefault" class="col-lg-2 control-label">Название</label>
			<div class="col-lg-10">
				<input type="text" name="post_title" id="inputDefault" class="form-control" 
					value="<?=$object->fields['post_title']?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="textArea" class="col-lg-2 control-label">Текст поста</label>
			<div class="col-lg-10">
				<textarea name="post_content" class="form-control" 
				rows="3" ><?=$object->fields['post_content']?></textarea>
			</div>		
		</div>
		<div class="form-group">
			<label for="select" class="col-lg-2 control-label">Статус поста</label>
			<div class="col-lg-10">
				<select name="post_status" id="select" class="form-control">
					<option value="publish" <? if (empty($object->fields['post_status']) || $object->fields['post_status'] == 'publish') echo "selected"?> >
						Опубликован
					</option>
					<option value="pending" <? if ($object->fields['post_status'] == 'pending') echo "selected" ?> >
						В черновиках
					</option>
				</select>
			</div>
		</div>
		<div class="form-group <?if(isset($object->messages['post_type'])) echo ' has-error'?>">
			<label for="selectPostType" class="col-lg-2 control-label">Тип материала</label>
			<div class="col-lg-10">
				<select name="post_type" id="selectPostType" class="form-control">
					<option value="post" <? if (empty($object->fields['post_type']) ||$object->fields['post_type'] == 'post') echo "selected"?> >
						Запись в блог
					</option>
					<option value="page" <? if ($object->fields['post_type'] == 'page') echo "selected" ?> >
						Страница
					</option>
					<option value="parent" <? if ($object->fields['post_type'] == 'parent') echo "selected" ?> >
						Родительская страница
					</option>
				</select>
			</div>
		</div>
		<div id = "commentSt" class="form-group <?if(isset($object->messages['comment_status'])) echo ' has-error'?>">
			<label class="col-lg-2 control-label">Комментария</label>
			<div class="col-lg-10">
				<div class="radio">
					<label>
						<input type="radio" name="comment_status" value="open"  
							<? if (empty($object->fields['comment_status']) || $object->fields['comment_status'] == 'open') echo "checked"?> />
						Включены
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="comment_status" value="close"
							<?if ($object->fields['comment_status'] == 'close') echo "checked"?> />
						Отключены
					</label>
				</div>
			</div>
		</div>
	</fieldset>
	
	
	<fieldset class="col-lg-5">
		<legend>Пункт в меню</legend>
		<input type="hidden" name="mlid" value="<?=$object->fields['mlid']?>"/>
		<div class="form-group <?if(isset($object->messages['parent'])) echo ' has-error'?>">
			<label for="selectParent" class="col-lg-4 control-label">Родительская ссылка</label>
			<div class="col-lg-8">
				<select name="parent" id="selectParent" class="form-control">
					<option value="0">Нет в меню</option>
					<? foreach($object->map as $key=>$item) : ?>
					<option value="<?=$item['menu_id']. ':0'?>" 
						<? if ($this->fields['parent'] == ($item['menu_id']. ':0')) echo "selected"?> >
						<?=$item['menu_title']?>
					</option>
					<? print_tree($item['children'], $this->fields, 5)?>
					<? endforeach?>
				</select>
			</div>
		</div>
		<div class="form-group <?if (isset($object->messages['link_title'])) echo ' has-error'?>">
			<label for="inputLinkTitle" class="col-lg-4 control-label">Название cсылки в меню</label>
			<div class="col-lg-8">
				<input type="text" name="link_title" id="inputLinkTitle" class="form-control" 
					value="<?=$object->fields['link_title']?>"/>
			</div>
		</div>
		<div class="form-group <?if(isset($object->messages['link_description'])) echo ' has-error'?>">
			<label for="textArea" class="col-lg-4 control-label">Описание ссылки</label>
			<div class="col-lg-8">
				<textarea name="link_description"  id="textArea" class="form-control" 
				rows="3" ><?=$object->fields['link_description']?></textarea>
			</div>		
		</div>
	</fieldset>
	
	
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Сохранить изменения</button>
			<a class="btn btn-primary" target="_blank" href="<?=M_Link::ToPage('post', $object->fields['post_id'])?>">Посмотреть страницу</a>
			<a class="btn btn-primary" href="<?=M_Link::ToAdminPosts('all')?>">Вернуться к списку страниц</a>			
		</div>
	</div>
</form>

