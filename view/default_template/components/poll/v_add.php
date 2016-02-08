<h2> Создание опроса</h2>
<? if (!empty($object->messages) && is_array($object->messages)) : ?>
<ul class="list-group">
	<? foreach ($object->messages as $message): ?>
	<li class="list-group-item list-group-item-danger"><?=$message?></li>
	<? endforeach?>
</ul>
<? endif?>
<form method="post" action="" class="form-horizontal" >
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
		<? for ($i = 0; $object->fields['count'] > $i; $i++):?>
		<div class="form-group">
			<div class="col-lg-10">
				<input type="text" name="answers[<?=$i?>]" class="form-control" 
					value="<?=$object->fields['answers'][$i]?>" placeholder="Пустые поля будут удалены при сохранении"/>
			</div>
			<div class="col-lg-2">
				<select name="weights[<?=$i?>]" class="form-control">
				<? for ($j = -$object->fields['count']; $object->fields['count'] >= $j; $j++) :?>
					<option value="<?=$j?>" <?if ($object->fields['weights'][$i] == $j) echo "selected"?>><?=$j?></option>
				<? endfor?>
				</select>
			</div>	
		</div>
		<? endfor?>
	</fieldset>
	<div class="form-group">
		<div class="col-lg-12">
			<button class="btn btn-primary" type="submit" name="addAnswer">Добавить вариант</button>
		</div>
	</div>	
	<div class="form-group">
		<div class="col-lg-12 text-center">
			<button class="btn btn-primary" name="savePoll" type="submit">Создать опрос</button>
			<a class="btn btn-primary" href="<?=M_Link::ToAdminPoll('all')?>">Вернуться к списку опросов </a>
		</div>
	</div>	
</form>
