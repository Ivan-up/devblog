<ul class="nav nav-tabs">
<? if (!in_array('users',$empty)):?>
  <li role="presentation" class="dropdown <?if ($active == 'users') echo "active" ?>">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Пользователи <span class="caret"></a>
		<ul class="dropdown-menu">
		<? if ($this->check_priv('C_Users:action_all')) :?>
			<li><a href="<?=M_Link::ToAdminUsers("all")?>">Все пользователи</a></li>
		<? endif?>
		<? if ($this->check_priv('C_Users:action_add')) :?>
			<li><a href="<?=M_Link::ToAdminUsers("add")?>">Добавить нового пользователя</a></li>
		<? endif?>
		</ul>
	</li>
<? endif?>
<? if (!in_array('roles',$empty)):?>
  <li role="presentation" class="dropdown <?if ($active == 'roles') echo "active" ?>">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Роли <span class="caret"></a>
		<ul class="dropdown-menu">
		<? if ($this->check_priv('C_Users:action_allroles')) :?>
			<li><a href="<?=M_Link::ToAdminUsers("allroles")?>">Все роли</a></li>
		<? endif;?>
		<? if ($this->check_priv('C_Users:action_addrole')) :?>
			<li><a href="<?=M_Link::ToAdminUsers("addrole")?>">Добавить новую роль</a></li>
		<? endif?>
		<? if ($this->check_priv('C_Users:action_allroles_privs')) :?>
			<li><a href="<?=M_Link::ToAdminUsers("allroles_privs")?>">Привелегии</a></li>
		<? endif;?>
		</ul>
	</li>
<? endif?>
</ul>
