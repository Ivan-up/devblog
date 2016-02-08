<ul class="nav nav-pills" role="tablist">

<? if ($this->check_priv('C_Mailing:action_all')) :?>
  <li role="presentation" class="<?if (in_array($active, array('action_all','action_index'))) echo "active"?>">
		<a href="<?=M_Link::ToAdminMailing('all')?>">Список листов рассылок</a>
	</li>
<? endif?>

<? if ($this->check_priv('C_Mailing:action_add')) :?>
  <li role="presentation" class="<?if ($active == 'action_add') echo "active"?>">
		<a href="<?=M_Link::ToAdminMailing('add')?>">Создания листа рассылки</a>
	</li>
<? endif?>

<? if ($this->check_priv('C_Mailing:action_addmail')) :?>
  <li role="presentation" class="<?if ($active == 'action_addmail') echo "active"?>">
		<a href="<?=M_Link::ToAdminMailing('addmail')?>">Создание письма</a>
	</li>
<? endif?>

<? if ($this->check_priv('C_Mailing:action_viewmail')) :?>
  <li role="presentation" class="<?if ($active == 'action_viewmail') echo "active"?>">
		<a href="<?=M_Link::ToAdminMailing('viewmail')?>">Неотправленые письма<span class="badge"><?=$unsent?></span></a>
	</li>
<? endif?>

</ul>