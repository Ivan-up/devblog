<div class="template-setting-container">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4> Выбор шаблона (текущий шаблон <strong><?=$object->currentTemplate?></strong>) </h4>
				</div>
				<div class="panel-body">
				
					<? if (!empty($object->messages) && is_array($object->messages)) : ?>
					<ul class="list-group">
						<? foreach ($object->messages as $message): ?>
						<li class="list-group-item list-group-item-danger"><?=$message?></li>
						<? endforeach?>
					</ul>
					<? endif?>
				
					<form method="post" action="" class="form-horizontal" >
						<div class="form-group">
							<div class="col-sm-7 <?if(isset($object->messages['template'])) echo ' has-error'?>">
								<label for="selectTemplate" class="col-md-7 control-label hidden-xs hidden-sm">Значение(название шаблона)</label>
								<div class="col-md-5">
									<select name="option_value" id="selectTemplate" class="form-control">
										<? foreach($object->listTemplates as $template) : ?>
											<? $is_selected = ""; 
												 if ($template == $object->currentTemplate) 
													$is_selected = 'selected';
												 else
													$is_selected = '';?>
										<option value="<?=$template?>" <?=$is_selected?>><?=$template?></option>
										<? endforeach?>
									</select>	
								</div>
							</div>
							<div class="col-sm-5 text-center">
								<button class="btn btn-primary" name="templ" type="submit">Сменить шаблон</button>
							</div>
						</div>	
					</form>
					
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
		
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			
				<div class="panel panel-info">
					<div class="panel-heading" role="tab" id="settingTemplate">
						<h3 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							Настройки шаблона <strong><?=$object->currentTemplate?></strong>
							</a>
						</h3>
					</div>
					
					<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="settingTemplate">
					
						<div class="panel-body">
							<form method="post" action="" class="form-horizontal" >
							<?$options = $object->regions?>
							<?foreach ($object->regions as $region) : ?>
							<fieldset >
							<legend><?=$region['region_title']?></legend>
								<? if (isset($object->blocks[$region['region_id']]) && is_array($object->blocks[$region['region_id']])) :?>
									<? foreach($object->blocks[$region['region_id']] as $block):?>
									<div class="form-group">
									
										<div class="col-sm-4">				
											<?=$block['block_title']?>
										</div>
										
										<div class="col-sm-4">
											<select name="region_<?=$block['block_id']?>" class="form-control">
											<? foreach($options as $option) :?>
												 <option value="<?=$option['region_id']?>"
												 <?if($option['region_id'] == $region['region_id']) echo "selected"?> ><?=$option['region_title']?></option>
											<? endforeach?>
											</select>
										</div>
										
										<div class="col-sm-4">
											<div class="row">
												<label class="control-label col-xs-6" >Вес</label>
												<div class="col-xs-6">
													<select name="weight_<?=$block['block_id']?>" class="form-control">
														<? for($i = -50; $i <= 50; $i++) : ?>
															<? $is_selected = ""; ?>
															<? if($i == $block['weight']) 
																	$is_selected = 'selected';
																 elseif ($i == 0 && $block['weight'] === '')
																	$is_selected = 'selected';?>
														<option value="<?=$i?>" <?=$is_selected?>><?=$i?></option>
														<? endfor?>
													</select>
												</div>	
											</div>
										</div>	
										
									</div>
									<? endforeach?>
								<? endif?>
							</fieldset>
							<? endforeach;?>
								<div class="form-group">
									<div class="text-center">
										<button class="btn btn-info btn-lg" name="region" type="submit">Cохранить изменения</button>
									</div>
								</div>	
							</form>
						</div>
						
					</div>
					
				</div>
				
			</div>
			
			
		</div>
	</div>
</div>
