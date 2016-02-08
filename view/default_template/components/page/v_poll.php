<article class="alert alert-info">	
	<h2 class="text-center"><?=$object->poll[0]['question'] ?></h2>
	<? if ($object->show_result['status'] == false):?>
	<form action="" method="POST">
		<input type="hidden" name="pid" value="<?=$object->poll[0]['pid']?>">
		<? foreach ($object->poll as $answer) : ?>
		<div class="form-group">			
			<div class = "col-md-2 text-right">
				<input type="radio" name="aid" value="<?=$answer['aid']?>" id="answer_<?=$answer['aid']?>">
			</div>
			<label class="col-md-10" for="answer_<?=$answer['aid']?>"><?=$answer['answer']?></label>
		</div>
		<? endforeach;?>
		<div class="form-group text-center">
			<input type="submit" name="poll_vote"  value="Проголосовать">
			<input type="submit" name="poll_result" value="Результаты">			
		</div>
	</form>
	<?else :?>
	<div class="poll-result">		
		<? foreach ($object->poll as $answer) :?>
		<? $rating = ($object->show_result['value'] == 0) ? 0 : ($answer['res']/$object->show_result['value'] * 100)?>			
			<p><?=$answer['answer']?></p>			
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?=$rating?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$rating?>%;">
					(<?=M_Helpers::get_correct_str($answer['res'], 'голос%s' ,'', 'а', 'ов')?>)
				</div>
			</div>		
		<? endforeach?>		
		<p>Всего: <?=M_Helpers::get_correct_str($object->show_result['value'], 'голос%s' ,'', 'а', 'ов')?> </p>
	</div>
	<?endif?>
</article>


