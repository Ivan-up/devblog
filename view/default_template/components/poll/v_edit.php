<h2> Редактирование опроса</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
	<input type="hidden" name="pid" value="<?=$object->fields['pid']?>">
	<div class="form-group <?if (isset($object->messages['question'])) echo ' has-error'?>">
		<label for="inputQuestion" class="col-lg-12">Вопрос</label>
		<div class="col-lg-10">
			<input type="text" name="question" id="inputQuestion" class="form-control" 
				value="<?=$object->fields['question']?>"/>
		</div>
	</div>
	<fieldset>
		<div class="form-group">
			<div class="col-lg-10"><span>Варианты ответов (минимум 2)</span></div>
			<div class="col-lg-2"><span>Вес</span></div>
		</div>
		<? foreach ($object->fields['answers'] as $key => $answer) : ?>
		<div class="form-group">
			<div class="col-lg-10">
				<input type="text" name="answers[<?=$key?>]" class="form-control" 
					value="<?=$answer?>" placeholder="Пустые поля будут удалены при сохранение"/>
			</div>
			<div class="col-lg-2">
				<select name="weights[<?=$key?>]" class="form-control">
				<? for ($j = -$object->fields['count']; $object->fields['count'] >= $j; $j++) :?>
					<option value="<?=$j?>" <?if ($object->fields['weights'][$key] == $j) echo "selected"?>><?=$j?></option>
				<? endfor?>
				</select>
			</div>	
		</div>
		<? endforeach?>
	</fieldset>
	<div class="form-group">
		<div class="col-lg-12">
			<button class="btn btn-primary" type="submit" name="addAnswer">Добавить вариант</button>
		</div>
	</div>	
	<div class="form-group">
		<div class="col-lg-12 text-center">
			<button class="btn btn-primary" name="savePoll" type="submit">Сохранить изменения</button>			
			<a class="btn btn-primary" target ="_blank" href="<?=M_Link::ToPage('poll', $object->fields['pid'])?>">Просмотреть</a>
			<a class="btn btn-primary" href="<?=M_Link::ToAdminPoll('all')?>">Вернуться к списку опросов</a>
		</div>
	</div>	
</form>
