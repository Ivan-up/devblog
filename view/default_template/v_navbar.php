<?
	/*Шаблон вывода линейки для тыканья по страничкам
	
	$count - общее количество записей
	$on_page - количество записей на странице
	$page_num - номер текущей страницы	
	*/
	$className ="";
?>
<? extract($object->navparams()); ?>
<? if($max_page > 1):?>
  <ul class="pagination">	
		<? ($page_num <= 1) ? $className = 'class="disabled' : ''?>
		<li>
			<a <?=$className?> href="<?=$url_self?>">
				Начало
			</a>
		</li>
		<li>
			<a <?=$className?> href="<?=$url_self . ($page_num - 1)?>">
				Пред.
			</a>
		</li> 
	<? for($i = $left; $i <= $right; $i++):?>
			<? if($i <1 || $i > $max_page) continue;?>
				<li <? if ($i == $page_num) echo 'class="active"'?> >
					<a href="<?=$url_self . $i?>"><?=$i?></a>
				</li>
	<? endfor; ?>
	
	<? if($page_num * $on_page >= $count) 
			$className = 'class="disabled';
		 else $className= ''?>
   <li><a <?=$className?> href="<?=$url_self . ($page_num + 1)?>">След.</a></li>
	 <li><a <?=$className?> href="<?=$url_self . $max_page?>">Конец</a></li>
  </ul>
<? endif; ?>