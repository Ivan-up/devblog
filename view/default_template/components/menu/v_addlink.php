<?php 
		
	function print_tree($map, $selected,  $shift = 0)
	{
		if(!empty($map))
		{
			foreach($map as $section)
			{
				$parent_v = $section['menu_id'].':'.$section['mlid'];
				?>
				<option value="<?=$parent_v?>"
				<? if ($parent_v == $selected) echo 'selected'?>>
				<? for($i = 0; $i < $shift; $i++) echo '&nbsp;';?>
				<?=$section['link_title']?>
				</option>
				<? print_tree($section['children'], $selected, $shift + 5); 
			}
		}
	}
	
?>
<h2> Добавление ссылки в меню</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
	<div class="form-group <?if (isset($object->messages['link_title'])) echo ' has-error'?>">
		<label for="inputLinkTitle" class="col-lg-2 control-label">Название cсылки в меню</label>
		<div class="col-lg-10">
			<input type="text" name="link_title" id="inputLinkTitle" class="form-control" 
				value="<?=$object->fields['link_title']?>"/>
		</div>
	</div>
	<div class="form-group <?if (isset($object->messages['link_path'])) echo ' has-error'?>">
		<label for="inputLinkPath" class="col-lg-2 control-label">Ссылка</label>
		<div class="col-lg-10">
			<input type="text" name="link_path" id="inputLinkPath" class="form-control" 
				value="<?=$object->fields['link_path']?>"/>
		</div>
	</div>	
	<div class="form-group <?if(isset($object->messages['link_description'])) echo ' has-error'?>">
		<label for="textArea" class="col-lg-2 control-label">Описание ссылки</label>
		<div class="col-lg-10">
			<textarea name="link_description"  id="textArea" class="form-control" 
			rows="3" ><?=$object->fields['link_description']?></textarea>
		</div>		
	</div>
	<div class="form-group <?if(isset($object->messages['parent'])) echo ' has-error'?>">
		<label for="selectParent" class="col-lg-2 control-label">Родительская ссылка</label>
		<div class="col-lg-10">
			<select name="parent" id="selectParent" class="form-control">
				<? foreach($object->map as $key=>$item) : ?>
				<option value="<?=$item['menu_id']. ':0'?>" 
					<? if ($this->fields['parent'] == ($item['menu_id']. ':0')) echo "selected"?> >
					<?=$item['menu_title']?>
				</option>
				<? print_tree($item['children'], $this->fields['parent'], 5)?>
				<? endforeach?>
			</select>
		</div>
	</div>
	<div class="form-group <?if(isset($object->messages['weight'])) echo ' has-error'?>">
		<label for="selectWeight" class="col-lg-2 control-label">Вес</label>
		<div class="col-lg-1">
			<select name="weight" id="selectWeight" class="form-control">
				<? for($i = -50; $i <= 50; $i++) : ?>
					<? $is_selected = ""; 
						 if($i == $object->fields['weight']) 
							$is_selected = 'selected';
						 elseif ($i == 0 && $object->fields['weight'] === '')
							$is_selected = 'selected';?>
				<option value="<?=$i?>" <?=$is_selected?>><?=$i?></option>
				<? endfor?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<button class="btn btn-primary" type="submit">Добавить</button>
			<a class="btn btn-primary" href="<?=M_Link::ToAdminMenu('all')?>">Вернуться к списку меню</a>
		</div>
	</div>	
</form>
